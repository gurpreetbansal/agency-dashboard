<?php

namespace App\Logic\Providers;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Mockery\CountValidator\Exception;
use Illuminate\Support\Facades\Log;
use Facebook\Facebook;
use Auth;

class FacebookRepository
{
    protected $facebook;
    protected $redirectUri = 'https://agencydashboard.io/facebookcallback';

    public function __construct()
    {
        $this->facebook = new Facebook([
            'app_id' => config('services.facebook.app_id'),
            'app_secret' => config('services.facebook.app_secret'),
            'default_graph_version' => 'v14.0'
        ]);
    }

    public function redirectTo()
    {
        $helper = $this->facebook->getRedirectLoginHelper();

        $permissions = [
            'pages_show_list',
            'pages_read_engagement',
            'pages_read_user_content',
            'read_insights',
            // 'business_management',
            // 'pages_manage_posts',
        ];

        if(empty(Auth::user()->id)){
            echo  "<script>";
            echo "window.close();";
            echo "</script>";
        }

        $helper->getPersistentDataHandler()->set('state', json_encode(Auth::user()->id.'-!-'.Auth::user()->company_name));
        // $helper->getLoginUrl($this->redirectUri, $permissions);
        return $helper->getReAuthenticationUrl($this->redirectUri, $permissions);
    }

    public function handleCallback()
    {   
        $helper = $this->facebook->getRedirectLoginHelper();
        
        if (request('state')) {
            $helper->getPersistentDataHandler()->set('state', request('state'));
        }
        try {
            $accessToken = $helper->getAccessToken();
        } catch(FacebookResponseException $e) {
            throw new Exception("Graph returned an error: {$e->getMessage()}");
        } catch(FacebookSDKException $e) {
            throw new Exception("Facebook SDK returned an error: {$e->getMessage()}");
        }

        if (!isset($accessToken)) {
            return $accessToken;
        }

        return $accessToken->getValue();
    }


    public function getLongLivedToken($accessToken){
        try {
            $oAuth2Client = $this->facebook->getOAuth2Client();
            $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        } catch (FacebookSDKException $e) {
            return ['error' => $e->getMessage()];
            // throw new Exception("Error : {$e->getMessage()}");
        }

        return $accessToken->getValue();
    }

    public function getOathUserProfile($accessToken){
        try {
            $response = $this->facebook->get(
                '/me?fields=id,name,first_name,last_name,email',$accessToken
            );
            return $response->getGraphObject()->asArray();
        } catch (FacebookResponseException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        } catch (FacebookSDKException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }


    public function getUserPages($accessToken){
        $pages = $this->facebook->get('/me/accounts', $accessToken);
        $pages = $pages->getGraphEdge()->asArray();

        return array_map(function ($item) {
            return [
                'provider' => 'facebook',
                'access_token' => $item['access_token'],
                'id' => $item['id'],
                'name' => $item['name'],
                'image' => "https://graph.facebook.com/{$item['id']}/picture?type=large"
            ];
        }, $pages);
    }

    public function getData($pageId,$pageToken,$since,$until,$durationType,$scope,$campaignId){
        try {
            $response = $this->facebook->get(
                $pageId.'/insights/'.$scope.'?period='.$durationType.'&since='.$since.'&until='.$until,$pageToken
            );
            
            $data = $response->getGraphEdge();
            $captureAllData = array();

            if ($this->facebook->next($data)) {  
                $resultArray = $data->asArray();
                $captureAllData = array_merge($captureAllData, $resultArray); 
                while ($data = $this->facebook->next($data)) { 
                    $resultArray = $data->asArray();
                    $captureAllData = array_merge($captureAllData, $resultArray);
                }
            } else {
                $resultArray = $data->asArray();
                $captureAllData = array_merge($captureAllData, $resultArray);
            }

            return $captureAllData;
        } catch (FacebookResponseException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        } catch (FacebookSDKException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function fetchPagePosts($pageId,$pageToken,$pagePostsInsights,$campaignId,$limit){
        try {
            $response = $this->facebook->get(
                $pageId.'/'.$pagePostsInsights.'?fields=id,message,full_picture,picture,created_time,shares,likes.summary(true),comments.summary(true),from{name,picture,location},attachments{media},admin_creator&limit='.$limit,$pageToken
            );
            
            return $response->getGraphEdge()->asArray();

        } catch (FacebookResponseException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        } catch (FacebookSDKException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function getPostMetrics($postId,$pageToken,$metrics){
        try {
            $response = $this->facebook->get(
                $postId.$metrics,$pageToken
            );
            
            return $response->getGraphEdge()->asArray();
        } catch (FacebookResponseException $e) {
            return $e->getMessage();
        } catch (FacebookSDKException $e) {
            return $e->getMessage();
        }
    }

    public function getPageReviewData($pageId,$pageToken,$scope,$campaignId){
        try {
            $response = $this->facebook->get(
                $pageId.'/'.$scope,$pageToken
            );
            
            return $response->getGraphEdge()->asArray();
        } catch (FacebookResponseException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        } catch (FacebookSDKException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

}