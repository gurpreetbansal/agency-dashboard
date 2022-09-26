<?php

namespace Google\Ads\GoogleAds\Examples\Planning;

require __DIR__ . '/lib/google-ads-php/vendor/autoload.php';

// require __DIR__ . '/Utils/ArgumentNames.php';
// require __DIR__ . '/Utils/ArgumentParser.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use GetOpt\GetOpt;
use Google\Ads\GoogleAds\Examples\Utils\ArgumentNames;
use Google\Ads\GoogleAds\Examples\Utils\ArgumentParser;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsException;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\V10\Enums\CriterionTypeEnum\CriterionType;
use Google\Ads\GoogleAds\V10\Enums\KeywordMatchTypeEnum\KeywordMatchType;
use Google\Ads\GoogleAds\V10\Errors\GoogleAdsError;
use Google\Ads\GoogleAds\V10\Services\GoogleAdsRow;
use Google\ApiCore\ApiException;

/**
 * This example gets all campaign criteria. To add campaign criteria, run
 * AddCampaignTargetingCriteria.php.
 */
class GetCampaignTargetingCriteria
{
    private const CUSTOMER_ID = '9213121696';
    private const CAMPAIGN_ID = 'INSERT_CAMPAIGN_ID_HERE';
    private const DEVELOPERTOKEN = 'TvEWm8Tg-eZMHMLBW4b6HA';

    private const PAGE_SIZE = 1000;

    public static function main()
    {
        // Either pass the required parameters for this example on the command line, or insert them
        // into the constants above.
        $options = (new ArgumentParser())->parseCommandArguments([
            ArgumentNames::CUSTOMER_ID => GetOpt::REQUIRED_ARGUMENT,
            // ArgumentNames::CAMPAIGN_ID => GetOpt::REQUIRED_ARGUMENT
        ]);

        // Generate a refreshable OAuth2 credential for authentication.
        // $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile()->build();

        $oAuth2Credential = (new OAuth2TokenBuilder())
            ->withRefreshToken($refreshToken)
            ->withClientId(CLIENT_ID)
            ->withClientSecret(CLIENT_SECRET)
            ->build();

        // Construct a Google Ads client configured from a properties file and the
        // OAuth2 credentials above.
        $googleAdsClient = (new GoogleAdsClientBuilder())
            ->withDeveloperToken(DEVELOPERTOKEN)
            ->withOAuth2Credential($oAuth2Credential)
            ->build();

        try {
            self::runExample(
                $googleAdsClient,
                $options[ArgumentNames::CUSTOMER_ID] ?: self::CUSTOMER_ID,
                // $options[ArgumentNames::CAMPAIGN_ID] ?: self::CAMPAIGN_ID
            );
        } catch (GoogleAdsException $googleAdsException) {
            printf(
                "Request with ID '%s' has failed.%sGoogle Ads failure details:%s",
                $googleAdsException->getRequestId(),
                PHP_EOL,
                PHP_EOL
            );
            foreach ($googleAdsException->getGoogleAdsFailure()->getErrors() as $error) {
                /** @var GoogleAdsError $error */
                printf(
                    "\t%s: %s%s",
                    $error->getErrorCode()->getErrorCode(),
                    $error->getMessage(),
                    PHP_EOL
                );
            }
            exit(1);
        } catch (ApiException $apiException) {
            printf(
                "ApiException was thrown with message '%s'.%s",
                $apiException->getMessage(),
                PHP_EOL
            );
            exit(1);
        }
    }

    /**
     * Runs the example.
     *
     * @param GoogleAdsClient $googleAdsClient the Google Ads API client
     * @param int $customerId the customer ID
     * @param int $campaignId the campaign ID for which campaign criteria will be retrieved
     */
    public static function runExample(
        GoogleAdsClient $googleAdsClient,
        int $customerId,
        //int $campaignId
    ) {
        $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
        // Creates a query that retrieves campaign criteria.
        $query = 'SELECT campaign.id, campaign_criterion.campaign, '
                . 'campaign_criterion.criterion_id, campaign_criterion.type, '
                . 'campaign_criterion.negative, campaign_criterion.keyword.text, '
                . 'campaign_criterion.keyword.match_type FROM campaign_criterion'
            . ' WHERE campaign.id = ' . $campaignId;

        $query = "SELECT campaign.name, campaign_budget.amount_micros, campaign.status, campaign.optimization_score, campaign.advertising_channel_type, metrics.clicks, metrics.impressions, metrics.ctr,  metrics.average_cpc,  metrics.cost_micros, campaign.bidding_strategy_type FROM campaign WHERE segments.date DURING LAST_7_DAYS ";
        // Issues a search request by specifying page size.
        $response =
            $googleAdsServiceClient->search($customerId, $query, ['pageSize' => self::PAGE_SIZE]);
        dd($response);
        // Iterates over all rows in all pages and prints the requested field values for
        // the campaign criterion in each row.
        foreach ($response->iterateAllElements() as $googleAdsRow) {
            /** @var GoogleAdsRow $googleAdsRow */
            $campaignCriterion = $googleAdsRow->getCampaignCriterion();
            printf(
                "Campaign criterion with ID %d was found as a %s",
                $campaignCriterion->getCriterionId(),
                $campaignCriterion->getNegative() ? 'negative ' : ''
            );
            if ($campaignCriterion->getType() === CriterionType::KEYWORD) {
                printf(
                    "keyword with text '%s' and match type %s.%s",
                    $campaignCriterion->getKeyword()->getText(),
                    KeywordMatchType::name($campaignCriterion->getKeyword()->getMatchType()),
                    PHP_EOL
                );
            } else {
                print 'non-keyword.' . PHP_EOL;
            }
        }
    }
}

GetCampaignTargetingCriteria::main();

