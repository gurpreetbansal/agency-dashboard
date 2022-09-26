<?php
// error_reporting(E_ALL);
// ini_set('display_errors',1);

require dirname(__DIR__) . '/lib/google-ads-php/vendor/autoload.php';
require dirname(__DIR__).'/config.php';

use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsException;
use Google\Ads\GoogleAds\Util\V8\ResourceNames;
use Google\Ads\GoogleAds\V8\Services\CustomerServiceClient;
use Google\Ads\GoogleAds\V8\Resources\CustomerClient;
use Google\Ads\GoogleAds\V8\Services\GoogleAdsRow;
use Google\ApiCore\ApiException;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsServerStreamDecorator;
use Google\Ads\GoogleAds\V8\Errors\GoogleAdsError;
use Google\Ads\GoogleAds\Utils\Helper;
use Google\Ads\GoogleAds\V8\Common\ManualCpc;
use Google\Ads\GoogleAds\V8\Common\ManualCpm;
use Google\Ads\GoogleAds\V8\Enums\AdvertisingChannelTypeEnum\AdvertisingChannelType;
use Google\Ads\GoogleAds\V8\Enums\AdServingOptimizationStatusEnum\AdServingOptimizationStatus;
use Google\Ads\GoogleAds\V8\Enums\BudgetDeliveryMethodEnum\BudgetDeliveryMethod;
use Google\Ads\GoogleAds\V8\Enums\CampaignStatusEnum\CampaignStatus;
use Google\Ads\GoogleAds\V8\Resources\Campaign;
use Google\Ads\GoogleAds\V8\Resources\Campaign\NetworkSettings;
use Google\Ads\GoogleAds\V8\Resources\CampaignBudget;
use Google\Ads\GoogleAds\V8\Services\CampaignBudgetOperation;
use Google\Ads\GoogleAds\V8\Services\CampaignOperation;

use Psr\Log\LogLevel;
use Google\Ads\GoogleAds\Lib\V8\LoggerFactory;

class My_AdwordsClass{

    private static $rootCustomerClients = [];

    function __construct(){
    }

    function getOauth2Obj(){
        $oauth2 = new OAuth2(
            [
                'clientId' => CLIENT_ID,
                'clientSecret' => CLIENT_SECRET,
                'authorizationUri' => AUTHORIZATION_URI,
                'redirectUri' => REDIRECT_URI,
                'tokenCredentialUri' => CredentialsLoader::TOKEN_CREDENTIAL_URI,
                'scope' => SCOPE,
            ]
        );
        return $oauth2;
    }
    
    function getAuthorizationUri(){
        $oauth2 = $this->getOauth2Obj();
        return $oauth2->buildFullAuthorizationUri(['access_type' => 'offline','prompt'=>'consent']);
    }

    function getGoogleAdsAuthToken($code){
        $oauth2 = $this->getOauth2Obj();
        $oauth2->setCode($code);
        $authToken = $oauth2->fetchAuthToken();
        return $authToken;
    }

    function getAccessibleClients($refreshToken){


        $oAuth2Credential = (new OAuth2TokenBuilder())
                            ->withRefreshToken($refreshToken)
                            ->withClientId(CLIENT_ID)
                            ->withClientSecret(CLIENT_SECRET)
                            ->build();

        $googleAdsClient = (new GoogleAdsClientBuilder())
            ->withOAuth2Credential($oAuth2Credential)
            ->withDeveloperToken(DEVELOPERTOKEN)
            ->build();


        $customerServiceClient = $googleAdsClient->getCustomerServiceClient();
        $accessibleCustomers = $customerServiceClient->listAccessibleCustomers();

        $active_customers = [];
        $error_customers = [];
        $i = 0;
        foreach ($accessibleCustomers->getResourceNames() as $resourceName) {


            $customer_id = CustomerServiceClient::parseName($resourceName)['customer_id'];

            
            try{
                $customer = $customerServiceClient->getCustomer(ResourceNames::forCustomer($customer_id));
                $active_customers[$i]['customerId'] = $customer->getId();
                $active_customers[$i]['is_manager'] = $customer->getManager();
                $active_customers[$i]['account_name'] = $customer->getDescriptiveName();
                $active_customers[$i]['currencyCode'] = $customer->getCurrencyCode();
                $active_customers[$i]['account_time_zone'] = $customer->getTimeZone();
            }catch (GoogleAdsException $googleAdsException) {
                $error_customers['resource_name'] = $resourceName;
                foreach ($googleAdsException->getGoogleAdsFailure()->getErrors() as $error) {
                    $error_customers['error_code'] = $error->getErrorCode()->getErrorCode();
                    $error_customers['message'] = $error->getMessage();
                }
            }

            
            $i++;
        }

        $i = 0;
        $child_accounts = [];
        if(!empty($active_customers)){
            foreach ($active_customers as $customer_info) {
                try {
                    $account_information = [];
                    if($customer_info['is_manager'] == 1){
                        $loginCustomerId = $customer_info['customerId'];
                        $clients = self::get_client_ids_data($googleAdsClient,$loginCustomerId,$refreshToken);
                        if(!empty($clients)){
                            foreach($clients as $val){
                                foreach($val as $v){
                                    $child_account =  [
                                        'customerId' => $v['customerId'],        
                                        'is_manager' => $v['is_manager'],
                                        'account_name' => $v['descriptiveName'],
                                        'account_time_zone' => $v['timezone'],
                                        'currencyCode' => $v['currencyCode'],
                                        'manager_id'=>$loginCustomerId
                                    ];
                                    array_push($child_accounts,$child_account);
                                }
                            }
                        }
                    }
                } catch(Exception $e) {
                    //echo 'Message: ' . $e->getMessage();
                }
                $i++;
            }
        }

        $all_accounts = array_merge($child_accounts,$active_customers);

        $temp = [];
        $final_acc = [];
        foreach($all_accounts as $c){
            if(!in_array($c['customerId'],$temp)){
                array_push($temp,$c['customerId']);
                $final_acc[] = $c;
            }
        }


        return $final_acc;

    }

    public static function get_client_ids_data(GoogleAdsClient $googleAdsClient,?int $managerCustomerId,$refreshToken) {
        $rootCustomerIds = [];
        $rootCustomerIds[] = $managerCustomerId;
        $allHierarchies = [];
        $accountsWithNoInfo = [];
        
        // Constructs a map of account hierarchies.
        foreach ($rootCustomerIds as $rootCustomerId) {
            $customerClientToHierarchy = self::createCustomerClientToHierarchy($rootCustomerId,$refreshToken);
            if (is_null($customerClientToHierarchy)) {
                $accountsWithNoInfo[] = $rootCustomerId;
            } else {
                $allHierarchies += $customerClientToHierarchy;
            }
        }
        $all_clients = [];
        $all_clients_data = [];
        foreach ($allHierarchies as $rootCustomerId => $customerIdsToChildAccounts) {
            $d = self::getAccountHierarchy(self::$rootCustomerClients[$rootCustomerId],$customerIdsToChildAccounts,0,$all_clients);
            array_push($all_clients_data,$d);
        }
        return $all_clients_data;
    }
    private static function createCustomerClientToHierarchy(int $rootCustomerId,$refreshToken): ?array {
        $oAuth2Credential = (new OAuth2TokenBuilder())
        ->withRefreshToken($refreshToken)
        ->withClientId(CLIENT_ID)
        ->withClientSecret(CLIENT_SECRET)
        ->build();

        $googleAdsClient = (new GoogleAdsClientBuilder())
            ->withOAuth2Credential($oAuth2Credential)
            ->withLoginCustomerId($rootCustomerId)
            ->withDeveloperToken(DEVELOPERTOKEN)
            ->build();

        $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
        // Creates a query that retrieves all child accounts of the manager specified in search
        // calls below.
        $query = 'SELECT customer_client.client_customer, customer_client.level,'
            . ' customer_client.manager, customer_client.descriptive_name,'
            . ' customer_client.currency_code, customer_client.time_zone,'
            . ' customer_client.id FROM customer_client WHERE customer_client.level <= 1';

        $rootCustomerClient = null;
        // Adds the root customer ID to the list of IDs to be processed.
        $managerCustomerIdsToSearch = [$rootCustomerId];
        $customerIdsToChildAccounts = [];
        while (!empty($managerCustomerIdsToSearch)) {
            $customerIdToSearch = array_shift($managerCustomerIdsToSearch);
            $stream = $googleAdsServiceClient->searchStream($customerIdToSearch,$query);
            foreach ($stream->iterateAllElements() as $googleAdsRow) {
                $customerClient = $googleAdsRow->getCustomerClient();
                if ($customerClient->getId() === $rootCustomerId) {
                    $rootCustomerClient = $customerClient;
                    self::$rootCustomerClients[$rootCustomerId] = $rootCustomerClient;
                }
                // The steps below map parent and children accounts. Continue here so that managers
                // accounts exclude themselves from the list of their children accounts.
                if ($customerClient->getId() === $customerIdToSearch) {
                    continue;
                }
                // For all level-1 (direct child) accounts that are a manager account, the above
                // query will be run against them to create an associative array of managers to
                // their child accounts for printing the hierarchy afterwards.
                $customerIdsToChildAccounts[$customerIdToSearch][] = $customerClient;
                // Checks if the child account is a manager itself so that it can later be processed
                // and added to the map if it hasn't been already.
                if ($customerClient->getManager()) {
                    $alreadyVisited = array_key_exists($customerClient->getId(),$customerIdsToChildAccounts);
                    if (!$alreadyVisited && $customerClient->getLevel() === 1) {
                        array_push($managerCustomerIdsToSearch, $customerClient->getId());
                    }
                }
            }
        }

        return is_null($rootCustomerClient) ? null : [$rootCustomerClient->getId() => $customerIdsToChildAccounts];
    }


    private static function getAccountHierarchy(CustomerClient $customerClient,array $customerIdsToChildAccounts,int $depth,$all_clients) {
        $customerId = $customerClient->getId();

        $temp = [];
        $temp['customerId'] = $customerId;
        $temp['is_manager'] = $customerClient->getManager();
        $temp['descriptiveName'] = $customerClient->getDescriptiveName();
        $temp['currencyCode'] = $customerClient->getCurrencyCode();
        $temp['timezone'] = $customerClient->getTimeZone();

        array_push($all_clients,$temp);

        if (array_key_exists($customerId, $customerIdsToChildAccounts)) {
            foreach ($customerIdsToChildAccounts[$customerId] as $childAccount) {
                $all_clients = self::getAccountHierarchy($childAccount, $customerIdsToChildAccounts, $depth + 1,$all_clients);
            }
        }
        return $all_clients;
    }
    function getCampaigns($googleAdsClient,$customerId){
        $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
        $query = 'SELECT campaign.id, campaign.name FROM campaign ORDER BY campaign.id';
        $stream = $googleAdsServiceClient->searchStream($customerId, $query);
       
        $results = [];
        foreach ($stream->iterateAllElements() as $googleAdsRow) {
            printf(
                "Campaign with ID %d and name '%s' was found.%s",
                $googleAdsRow->getCampaign()->getId(),
                $googleAdsRow->getCampaign()->getName(),
                PHP_EOL
            );
        }
    }
}

function pr($a){
    echo "<pre>";
    return print_r($a);
}
?>
