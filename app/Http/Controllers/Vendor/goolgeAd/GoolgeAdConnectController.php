<?php

namespace App\Http\Controllers\Vendor\goolgeAd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\User;
use App\SemrushUserAccount;
use App\GoogleAdsCustomer;
use App\GoogleAnalyticsUsers;

use App\Traits\GoogleAdsTrait;

/*Google ads*/
use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\Http\Message\Response;
use React\Http\Server;
use UnexpectedValueException;

use GetOpt\GetOpt;
use App\Http\Controllers\Vendor\goolgeAd\ArgumentNames;
use App\Http\Controllers\Vendor\goolgeAd\ArgumentParser;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsException;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsServerStreamDecorator;
use Google\Ads\GoogleAds\V10\Errors\GoogleAdsError;
use Google\Ads\GoogleAds\V10\Resources\CustomerClient;
use Google\Ads\GoogleAds\V10\Services\CustomerServiceClient;
use Google\Ads\GoogleAds\V10\Services\GoogleAdsRow;
use Google\ApiCore\ApiException;

use Google\Ads\GoogleAds\Util\V10\ResourceNames;
use Google\Ads\GoogleAds\Utils\Helper;
use Google\Ads\GoogleAds\V10\Common\ManualCpc;
use Google\Ads\GoogleAds\V10\Common\ManualCpm;
use Google\Ads\GoogleAds\V10\Enums\AdvertisingChannelTypeEnum\AdvertisingChannelType;
use Google\Ads\GoogleAds\V10\Enums\AdServingOptimizationStatusEnum\AdServingOptimizationStatus;
use Google\Ads\GoogleAds\V10\Enums\BudgetDeliveryMethodEnum\BudgetDeliveryMethod;
use Google\Ads\GoogleAds\V10\Enums\CampaignStatusEnum\CampaignStatus;
use Google\Ads\GoogleAds\V10\Resources\Campaign;
use Google\Ads\GoogleAds\V10\Resources\Campaign\NetworkSettings;
use Google\Ads\GoogleAds\V10\Resources\CampaignBudget;
use Google\Ads\GoogleAds\V10\Services\CampaignBudgetOperation;
use Google\Ads\GoogleAds\V10\Services\CampaignOperation;
use Psr\Log\LogLevel;
use Google\Ads\GoogleAds\Lib\V10\LoggerFactory;

class GoolgeAdConnectController extends Controller {

	use GoogleAdsTrait;

	private const PAGE_SIZE = 1000;
	private const CUSTOMER_ID = '7300053848';

	private const LOGIN_CUSTOMER_ID = null;
	private const MANAGER_CUSTOMER_ID = null;

	private const SCOPE = 'https://www.googleapis.com/auth/adwords';
	private const AUTHORIZATION_URI = 'https://accounts.google.com/o/oauth2/v2/auth';

	public function __construct(){	
		$this->client_id = \config('app.ads_client_id');
		$this->client_secret = \config('app.ads_client_secret');
		$this->developerToken = \config('app.ads_developerToken');
	}

	public function connectGoogleAds(Request $request){

		try {
			$redirectUri = \config('app.base_url').'ppc/connect';
			$ADWORDS_API_SCOPE = array('https://www.googleapis.com/auth/adwords','email','profile');
			$PRODUCTS = [['AdWords', $ADWORDS_API_SCOPE]];
			$scopes = $PRODUCTS[0][1];
			$oauth2 = new OAuth2([
				'authorizationUri' => self::AUTHORIZATION_URI,
				'redirectUri' => $redirectUri,
				'tokenCredentialUri' => CredentialsLoader::TOKEN_CREDENTIAL_URI,
				'clientId' => \config('app.ads_client_id'),
				'clientSecret' => \config('app.ads_client_secret'),
				'scope' => $scopes,
				'state'=>$request->campaignId.'/'.$request->redirectPage
			]);

			if($request->get('code')){
				$exploded_value = explode('/',$request->state);
				$campaign_Id = $exploded_value[0];
				$redirectPage = $exploded_value[1];
				$code = $request->get('code');
				$oauth2->setCode($code);
				$oauthInfo = $oauth2->fetchAuthToken();
				$refreshToken = $oauthInfo['refresh_token'];
				$access_token = $oauthInfo['access_token'];
				$timestamp = $oauthInfo['expires_in'];
		        if (!empty($refreshToken)) {
					$userDetails = file_get_contents('https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . $access_token);
					$googleuser = json_decode($userDetails);

					$getUserDetails = SemrushUserAccount::findorfail($campaign_Id);
					$getLoggedInUser = User::findorfail($getUserDetails->user_id);
					$domainName = $getLoggedInUser->company_name;
					$checkIfExists = GoogleAnalyticsUsers::where('user_id',$getUserDetails->user_id)->where('oauth_uid',$googleuser->id)->where('oauth_provider','google_ads')->first();
					if(empty($checkIfExists)){
						$insert = GoogleAnalyticsUsers::create([
							'user_id'=>$getUserDetails->user_id,
							'google_access_token'=> $oauthInfo['access_token'],
							'google_refresh_token'=>$oauthInfo['refresh_token'],
							'oauth_provider'=>'google_ads',
							'oauth_uid'=>$googleuser->id,
							'first_name'=>$googleuser->given_name,
							'last_name'=>$googleuser->family_name,
							'email'=>$googleuser->email,
							'gender'=>$googleuser->gender??'',
							'locale'=>$googleuser->locale??'',
							'picture'=>$googleuser->picture??'',
							'link'=>$googleuser->link??'',
							'token_type'=>$oauthInfo['token_type'],
							'expires_in'=>$oauthInfo['expires_in'],
							'id_token'=>$oauthInfo['id_token'],
							'service_created'=>time(),
							'created_at'=>now(),
							'updated_at'=>now()
						]);
						sleep(1);
						
						/*GoogleAnalyticsUsers::where('email',$googleuser->email)->where('oauth_uid',$googleuser->id)->where('oauth_provider','google_ads')->update([
							'google_access_token'=> $oauthInfo['access_token'],
							'google_refresh_token'=>$oauthInfo['refresh_token'],
							'oauth_uid'=>$googleuser->id,
							'first_name'=>$googleuser->given_name,
							'last_name'=>$googleuser->family_name,
							'email'=>$googleuser->email,
							'gender'=>$googleuser->gender??'',
							'locale'=>$googleuser->locale??'',
							'picture'=>$googleuser->picture??'',
							'link'=>$googleuser->link??'',
							'token_type'=>$oauthInfo['token_type'],
							'expires_in'=>$oauthInfo['expires_in'],
							'id_token'=>$oauthInfo['id_token'],
							'service_created'=>time(),
							'created_at'=>now(),
							'updated_at'=>now()
						]);*/

						$getLastInsertedId = $insert->id;
						
						$this->googleAdsAccount($getUserDetails->user_id,$campaign_Id,$getLastInsertedId);
					}else{
						GoogleAnalyticsUsers::where('email',$checkIfExists->email)->where('oauth_uid',$googleuser->id)->where('oauth_provider','google_ads')->update([
							'google_access_token'=> $oauthInfo['access_token'],
							'google_refresh_token'=>$oauthInfo['refresh_token'],
							'oauth_uid'=>$googleuser->id,
							'first_name'=>$googleuser->given_name,
							'last_name'=>$googleuser->family_name,
							'email'=>$googleuser->email,
							'gender'=>$googleuser->gender??'',
							'locale'=>$googleuser->locale??'',
							'picture'=>$googleuser->picture??'',
							'link'=>$googleuser->link??'',
							'token_type'=>$oauthInfo['token_type'],
							'expires_in'=>$oauthInfo['expires_in'],
							'id_token'=>$oauthInfo['id_token'],
							'service_created'=>time(),
							'created_at'=>now(),
							'updated_at'=>now()
						]);
						sleep(1);
						$this->googleAdsAccount($getUserDetails->user_id,$campaign_Id,$checkIfExists->id);
					}

					if($redirectPage == 'campaign-detail' || $redirectPage == 'project-settings' || $redirectPage == 'add-new-project'){
						echo  "<script>";
						echo "window.close();";
						echo "</script>";
						return;	
					}
				} else {
					echo "Error please go back and try again.";
					exit;
				}
			} else {
	
				$extra_para = ['access_type' => 'offline','prompt'=>'consent'];
				$google_login_authUrl = $oauth2->buildFullAuthorizationUri($extra_para);
				return redirect()->to($google_login_authUrl);

			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}


	public function connectGoogleAdUpdates(Request $request){
		$response = array();
		$campaign_Id = $request->campaign_id;
		$email_id = $request->email;
		$user_id = User::get_parent_user_id(Auth::user()->id); 
		if($campaign_Id <> null){
			$checkIfExists = GoogleAnalyticsUsers::where('id',$email_id)->where('user_id',$user_id)->where('oauth_provider','google_ads')->first();
			if(!empty($checkIfExists)){
				$data = $this->googleAdsAccount($user_id,$campaign_Id,$checkIfExists->id);
				if($data['status']== 1){
					$response['status'] = 1;
					$response['time'] = 'Last fetched now';
				}
				if($data['status']== 2){
					$response['status'] = 0;
					$response['time'] = 'Error message: '.$data['message'];
				}
			}else{
				$response['status'] = 2;
				$response['time'] = 'Error: Please try again.';
			}
		}else{
			$response['status'] = 2;
			$response['time'] = 'Error: missing campaign id';
		}
		return response()->json($response);
	}

	private function googleAdsAccount($user_id,$campaign_Id,$google_ads_id){
		
		$checkIfExists = GoogleAnalyticsUsers::where('id',$google_ads_id)->where('oauth_provider','google_ads')->first();
		try {
		    $googleAdsClient = $this->googleAdsAuth($checkIfExists->google_refresh_token);
        	// GoogleAdsCustomer::where('google_ads_id',$checkIfExists->id)->update(['status'=>0]);
        	$rootCustomerIds = self::getAccessibleCustomers($googleAdsClient,$checkIfExists);
        	// dd($rootCustomerIds);
        	$allHierarchies = [];
	        $accountsWithNoInfo = [];
	        $childAccounts = $rootCustomerIds;
	        $options = (new ArgumentParser())->parseCommandArguments([
	            ArgumentNames::MANAGER_CUSTOMER_ID => GetOpt::OPTIONAL_ARGUMENT,
	            ArgumentNames::LOGIN_CUSTOMER_ID => GetOpt::OPTIONAL_ARGUMENT
	        ]);
	        // Constructs a map of account hierarchies.
	        if(count($rootCustomerIds) > 0){
	        	foreach ($rootCustomerIds as $key => $rootCustomerId) {
		        	$managerCustomerId = $options[ArgumentNames::MANAGER_CUSTOMER_ID] ?: $rootCustomerId['customerId'];
		        	$loginCustomerId = $options[ArgumentNames::LOGIN_CUSTOMER_ID] ?: self::LOGIN_CUSTOMER_ID;
		        	$customerClientToHierarchy = self::createCustomerClientToHierarchy($loginCustomerId, $managerCustomerId,$checkIfExists->google_refresh_token,$checkIfExists);
		            if(count($customerClientToHierarchy) > 0){
		            	$childAccounts = array_merge($childAccounts, $customerClientToHierarchy);
		            }
		        }
	        }
	        
	        $dataReturn['status'] = 1; 
	  	} catch (Exception $e) {
	  		$dataReturn['status'] = 2;
	  		// $dataReturn['message'] = $e->getMessage();
		}
	      
		return $dataReturn;
	}

	private static function getAccessibleCustomers(GoogleAdsClient $googleAdsClient,$checkIfExists)
    {
    	
        $accessibleCustomerIds = [];
        // Issues a request for listing all customers accessible by this authenticated Google
        // account.
        $customerServiceClient = $googleAdsClient->getCustomerServiceClient();
        
        $query = 'SELECT customer.id, customer.descriptive_name, customer.currency_code, customer.time_zone, customer.tracking_url_template, customer.auto_tagging_enabled FROM customer LIMIT 1';

        try{


        	$accessibleCustomers = $customerServiceClient->listAccessibleCustomers();
        
			$googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
		
			// GoogleAdsCustomer::where('id',$checkIfExists->id)->update($array);
		
	        foreach ($accessibleCustomers->getResourceNames() as $customerResourceName) {
	        	$googleaccount = null;
	            $customerId = CustomerServiceClient::parseName($customerResourceName)['customer_id'];

	            try{
	                $customer = $googleAdsServiceClient->search($customerId, $query)->getIterator()->current()->getCustomer();

	                $googleaccount = [
	                	'user_id' => $checkIfExists->user_id,
	                	'google_ads_id' => $checkIfExists->id,
	                	'customerId' => $customer->getId(),
	                	'LogincustomerId' => $customer->getId(),
		                'is_manager' => $customer->getManager(),
		                'account_name' => $customer->getDescriptiveName(),
		                'currencyCode' => $customer->getCurrencyCode(),
		                'account_time_zone' => $customer->getTimeZone()
	                ];
	                if($googleaccount <> null){
	                	self::addGoogleAdAccount($googleaccount);
	     				$accessibleCustomerIds[] = $googleaccount;
	                }
	            }catch (GoogleAdsException $googleAdsException) {
	            
	            }
	        }
        }catch (Exception $e) {
           
        } catch (GoogleAdsException $googleAdsException) {
            // printf(
            //     "Request with ID '%s' has failed.%sGoogle Ads failure details:%s",
            //     $googleAdsException->getRequestId(),
            //     PHP_EOL,
            //     PHP_EOL
            // );
            // foreach ($googleAdsException->getGoogleAdsFailure()->getErrors() as $error) {
            //     printf(
            //         "\t%s: %s%s",
            //         $error->getErrorCode()->getErrorCode(),
            //         $error->getMessage(),
            //         PHP_EOL
            //     );
            // }
            // exit(1);
        } catch (ApiException $apiException) {
            // printf(
            //     "ApiException was thrown with message '%s'.%s",
            //     $apiException->getMessage(),
            //     PHP_EOL
            // );
            // exit(1);
        }

        return $accessibleCustomerIds;
    }

    private static function createCustomerClientToHierarchy(?int $loginCustomerId,int $rootCustomerId,$refreshToken,$checkIfExists): ?array {
        // Creates a GoogleAdsClient with the specified login customer ID. See
        // https://developers.google.com/google-ads/api/docs/concepts/call-structure#cid for more
        // information.
        // Generate a refreshable OAuth2 credential for authentication.
        /*$oAuth2Credential = (new OAuth2TokenBuilder())->fromFile()->build();*/
        // Construct a Google Ads client configured from a properties file and the
        // OAuth2 credentials above.
        /*$googleAdsClient = (new GoogleAdsClientBuilder())->fromFile()
            ->withOAuth2Credential($oAuth2Credential)
            ->withLoginCustomerId($loginCustomerId ?? $rootCustomerId)
            ->build();*/

        $oAuth2Credential = (new OAuth2TokenBuilder())
			->withClientId(\config('app.ads_client_id'))
			->withClientSecret(\config('app.ads_client_secret'))
			->withRefreshToken($refreshToken)
			->build();

		$googleAdsClient = (new GoogleAdsClientBuilder())
			->withDeveloperToken(\config('app.ads_developerToken'))
            ->withOAuth2Credential($oAuth2Credential)
            ->withLoginCustomerId($loginCustomerId ?? $rootCustomerId)
            ->build();

        // Creates the Google Ads Service client.
        $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
        // Creates a query that retrieves all child accounts of the manager specified in search
        // calls below.
        $query = 'SELECT customer_client.client_customer, customer_client.level, customer_client.manager, customer_client.descriptive_name, customer_client.currency_code, customer_client.time_zone, customer_client.id FROM customer_client WHERE customer_client.level <= 1';

        $customerQuery = 'SELECT customer.id, customer.descriptive_name, customer.currency_code, customer.time_zone, customer.tracking_url_template, customer.auto_tagging_enabled FROM customer LIMIT 1';

        $rootCustomerClient = null;
        // Adds the root customer ID to the list of IDs to be processed.
        $managerCustomerIdsToSearch = [$rootCustomerId];

        // Performs a breadth-first search algorithm to build an associative array mapping
        // managers to their child accounts ($customerIdsToChildAccounts).
        $customerIdsToChildAccounts = $accessibleCustomerIds = [];

       
        while (!empty($managerCustomerIdsToSearch)) {
            $customerIdToSearch = array_shift($managerCustomerIdsToSearch);
            // Issues a search request by specifying page size.
            /** @var GoogleAdsServerStreamDecorator $stream */
            $stream = $googleAdsServiceClient->searchStream(
                $customerIdToSearch,
                $query
            );

            // Iterates over all elements to get all customer clients under the specified customer's
            // hierarchy.
            $rootCustomerClients = [];
            foreach ($stream->iterateAllElements() as $googleAdsRow) {
                /** @var GoogleAdsRow $googleAdsRow */
                $customerClient = $googleAdsRow->getCustomerClient();

                // Gets the CustomerClient object for the root customer in the tree.
                if ($customerClient->getId() === $rootCustomerId) {
                    $rootCustomerClient = $customerClient;
                    $rootCustomerClients[$rootCustomerId] = $rootCustomerClient;
                }

                // The steps below map parent and children accounts. Continue here so that managers
                // accounts exclude themselves from the list of their children accounts.
                if ($customerClient->getId() === $customerIdToSearch) {
                    continue;
                }

                try{
                $customer = $googleAdsServiceClient->search($customerClient->getId(), $customerQuery)->getIterator()->current()->getCustomer();
	            
	            $googleaccount = [
                	'user_id' => $checkIfExists->user_id,
                	'google_ads_id' => $checkIfExists->id,
                	'customerId' => $customer->getId(),
                	'LogincustomerId' => $rootCustomerId,
	                'is_manager' => $customer->getManager(),
	                'account_name' => $customer->getDescriptiveName(),
	                'currencyCode' => $customer->getCurrencyCode(),
	                'account_time_zone' => $customer->getTimeZone()
                ];

                if($googleaccount <> null){
                	self::addGoogleAdAccount($googleaccount);
     				$accessibleCustomerIds[] = $googleaccount;
                }

                }catch (Exception $e) {
           
		        } catch (GoogleAdsException $googleAdsException) {
		            // printf(
		            //     "Request with ID '%s' has failed.%sGoogle Ads failure details:%s",
		            //     $googleAdsException->getRequestId(),
		            //     PHP_EOL,
		            //     PHP_EOL
		            // );
		            // foreach ($googleAdsException->getGoogleAdsFailure()->getErrors() as $error) {
		            //     /** @var GoogleAdsError $error */
		            //     printf(
		            //         "\t%s: %s%s",
		            //         $error->getErrorCode()->getErrorCode(),
		            //         $error->getMessage(),
		            //         PHP_EOL
		            //     );
		            // }
		            // exit(1);
		        } catch (ApiException $apiException) {
		            // printf(
		            //     "ApiException was thrown with message '%s'.%s",
		            //     $apiException->getMessage(),
		            //     PHP_EOL
		            // );
		            // exit(1);
		        }
        
	            
                // For all level-1 (direct child) accounts that are a manager account, the above
                // query will be run against them to create an associative array of managers to
                // their child accounts for printing the hierarchy afterwards.
                $customerIdsToChildAccounts[$customerIdToSearch][] = $customerClient;
                // Checks if the child account is a manager itself so that it can later be processed
                // and added to the map if it hasn't been already.
                if ($customerClient->getManager()) {
                    // A customer can be managed by multiple managers, so to prevent visiting
                    // the same customer multiple times, we need to check if it's already in the
                    // map.
                    $alreadyVisited = array_key_exists(
                        $customerClient->getId(),
                        $customerIdsToChildAccounts
                    );
                    if (!$alreadyVisited && $customerClient->getLevel() === 1) {
                        array_push($managerCustomerIdsToSearch, $customerClient->getId());
                    }
                }
            }
        }

        return $accessibleCustomerIds;
    }


    private static function addGoogleAdAccount($googleAdArr = null){
    	
    	if($googleAdArr <> null){
    		$checkIfExists = GoogleAdsCustomer::where('user_id',$googleAdArr['user_id'])->where('customer_id',$googleAdArr['customerId'])->first();
			$array = [
				'user_id'=> $googleAdArr['user_id'],
				'google_ads_id'=>$googleAdArr['google_ads_id'],
				'name'=>$googleAdArr['account_name'],
				'customer_id'=>$googleAdArr['customerId'],
				'login_customer_id'=>$googleAdArr['LogincustomerId'],
				'currencyCode'=>$googleAdArr['currencyCode'],
				'status'=>1,
				'can_manage_clients'=>$googleAdArr['is_manager'] ?:0
			];

			if(empty($checkIfExists)){

				GoogleAdsCustomer::create($array);
				$array = array();
			}else{
				$updateSemrush = GoogleAdsCustomer::where('user_id',$googleAdArr['user_id'])->where('customer_id',$googleAdArr['customerId'])->update($array);	
			}
    	}
    }

	public function campaignList(){

		dd("HRE");
	}



}
