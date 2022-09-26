<?php


namespace App\Traits;

use App\Report;
use App\SiteAudit;
use App\SiteAuditSummary;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\TransferStats;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Requests\StoreReportRequest;
use Illuminate\Support\Carbon;


trait ReportTrait
{
    /**
     * The analyzed URL.
     *
     * @var
     */
    private $url;

    /**
     * The cached Not Found Page result.
     *
     * @var
     */
    private $cachedNotFoundPage;

    /**
     * The cached Robots Request result.
     *
     * @var
     */
    private $cachedRobotsRequest;

    /**
     * Store a new Report.
     *
     * @param Request $request
     * @return Report
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function reportStoreCron(Request $request)
    {  
        $client = new HttpClient();
        try{       
            $reportRequestTransferStats = null;
            usleep(rand(750000, 1250000));

            $reportRequest = $client->request('GET', str_replace('https://', 'http://', $request->input('url')), [
                // 'proxy' => [
                //     'http' => getRequestProxy(),
                //     'https' => getRequestProxy()
                // ],

                'connect_timeout' => config('info.settings.request_connection_timeout'),
                'allow_redirects' => [
                    'max' => 10,
                    'strict' => true,
                    'referer' => true,
                    'protocols' => ['http', 'https'],
                    'track_redirects' => true
                ],
                'headers' => [
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => config('info.settings.request_user_agent')
                ],
                'on_stats' => function (TransferStats $stats) use (&$reportRequestTransferStats) {
                    if ($stats->hasResponse()) {
                        $reportRequestTransferStats = $stats;
                    }else{
                        $reportRequestTransferStats = null;
                    }
                }
            ]);


           
            $this->model($request, new SiteAudit, $reportRequest, $reportRequestTransferStats);
        } catch (\Exception $e) {

        }
        
    }

    protected function reportStore(Request $request)
    {  
        $client = new HttpClient();
        // file_put_contents(dirname(__FILE__).'/logs/crowledPages.txt',print_r($request->all(),true),FILE_APPEND);
        try{       
            $reportRequestTransferStats = null;
            usleep(rand(750000, 1250000));

            $reportRequest = $client->request('GET', str_replace('https://', 'http://', $request->input('url')), [
                // 'proxy' => [
                //     'http' => getRequestProxy(),
                //     'https' => getRequestProxy()
                // ],

                'connect_timeout' => config('info.settings.request_connection_timeout'),
                'allow_redirects' => [
                    'max' => 10,
                    'strict' => true,
                    'referer' => true,
                    'protocols' => ['http', 'https'],
                    'track_redirects' => true
                ],
                'headers' => [
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => config('info.settings.request_user_agent')
                ],
                'on_stats' => function (TransferStats $stats) use (&$reportRequestTransferStats) {
                    if ($stats->hasResponse()) {
                        $reportRequestTransferStats = $stats;
                    }
                }
            ]);


           
            $this->model($request, new SiteAudit, $reportRequest, $reportRequestTransferStats);
        } catch (\Exception $e) {

            // continue;
        }
        
    }

    protected function reportsStore(Request $request)
    {
        // Increase the maximum connection time
        if (ini_get('max_execution_time') > 0) {
            ini_set('max_execution_time', ini_get('max_execution_time') + (config('settings.sitemap_links') * config('settings.request_connection_timeout')));
        }

        $data = [];

        // If the request contains a report request
        if (isset($request->reportRequest)) {
            $sitemapResponse = $request->reportRequest->getBody()->getContents();

            $domDocument = new \DOMDocument();
            libxml_use_internal_errors(true);

            $domDocument->loadHTML('<?xml encoding="utf-8" ?>' . $sitemapResponse ?? null);

            foreach ($domDocument->getElementsByTagName('urlset') as $urlsetNode) {
                foreach ($urlsetNode->getElementsByTagName('loc') as $node) {
                    // Reset the internal URL to the original URL
                    $this->url = $request->input('url');

                    if ($this->isInternalUrl($this->url($node->nodeValue))) {
                        // If the user exceeded the reports limit
                        // or if the crawl maximum allowed links limit is reached
                        if (!$request->user()->can('create', ['App\Report']) || (config('settings.sitemap_links') > 0 && $i > config('settings.sitemap_links'))) {
                            break 2;
                        }

                        $client = new HttpClient();
                        try {
                            // Add a random delay before making the request
                            usleep(rand(750000, 1250000));

                            $reportRequestTransferStats = null;
                            $reportRequest = $client->request('GET', str_replace('https://', 'http://', $node->nodeValue), [
                                'proxy' => [
                                    'http' => getRequestProxy(),
                                    'https' => getRequestProxy()
                                ],
                                'connect_timeout' => config('settings.request_connection_timeout'),
                                'allow_redirects' => [
                                    'max' => 10,
                                    'strict' => true,
                                    'referer' => true,
                                    'protocols' => ['http', 'https'],
                                    'track_redirects' => true
                                ],
                                'headers' => [
                                    'Accept-Encoding' => 'gzip, deflate',
                                    'User-Agent' => config('settings.request_user_agent')
                                ],
                                'on_stats' => function (TransferStats $stats) use (&$reportRequestTransferStats) {
                                    if ($stats->hasResponse()) {
                                        $reportRequestTransferStats = $stats;
                                    }
                                }
                            ]);
                        } catch (\Exception $e) {
                            continue;
                        }

                        $data[] = $this->model($request, new Report, $reportRequest, $reportRequestTransferStats);
                    }

                    $i++;
                }
            }
        }

        return $data;
    }

    /**
     * Update the Report.
     *
     * @param Request $request
     * @param Report $report
     * @return Report
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function reportUpdate(Request $request, SiteAudit $report)
    {
        $client = new HttpClient();
     
        try{       
            $reportRequestTransferStats = null;
            usleep(rand(750000, 1250000));

            $reportRequest = $client->request('GET', str_replace('https://', 'http://', $report->url), [
                // 'proxy' => [
                //     'http' => getRequestProxy(),
                //     'https' => getRequestProxy()
                // ],

                'connect_timeout' => config('info.settings.request_connection_timeout'),
                'allow_redirects' => [
                    'max' => 10,
                    'strict' => true,
                    'referer' => true,
                    'protocols' => ['http', 'https'],
                    'track_redirects' => true
                ],
                'headers' => [
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => config('info.settings.request_user_agent')
                ],
                'on_stats' => function (TransferStats $stats) use (&$reportRequestTransferStats) {
                    if ($stats->hasResponse()) {
                        $reportRequestTransferStats = $stats;
                    }
                }
            ]);

        } catch (\Exception $e) {
            // continue;
        }

        return $this->modelUpdate($request, $report, $reportRequest, $reportRequestTransferStats);
        

    }

            
    public function model($request, $report, $reportRequest, $reportRequestStats)
    {   

        ini_set('max_execution_time', '-1');
        ini_set('memory_limit', '-1');
        $summaryData = SiteAuditSummary::where('id',$request->audit_id)->first();
        
        // If the request contains a report request
        $totalTests = 0;
       

        if ($reportRequest) {

            // $summaryData['criticals'] =  $summaryData['warnings'] =  $summaryData['notices'] = 0;

            $reportResponse = $reportRequest->getBody()->getContents();
            $reportRequestStats = $reportRequestStats->getHandlerStats();
            
            $this->url = $request->input('url');
            // file_put_contents(dirname(__FILE__).'/logs/crowledPages.txt',print_r($request->input('url'),true),FILE_APPEND);
            $domDocument = new \DOMDocument();
            libxml_use_internal_errors(true);

            $domDocument->loadHTML('<?xml encoding="utf-8" ?>' . $reportResponse ?? null);

            // Get the page text
            $pageText = $this->text($domDocument->getElementsByTagName('body')->item(0)->textContent ?? null);

            // Filter the words
            $bodyKeywords = array_filter(explode(' ', preg_replace('/[^\w]/ui', ' ', mb_strtolower($pageText))));

            
            $title = $charset = $metaDescription = $favicon = $noIndex = $metaViewport = null;
            $structuredData = [];
            $titleTagsCount = 0;
            foreach ($domDocument->getElementsByTagName('head') as $headNode) {
                // Title

                // $title = $charset = $metaDescription = $favicon = $noIndex = $metaViewport = null;
                // $structuredData = [];
                // $titleTagsCount = 0;
                foreach ($headNode->getElementsByTagName('title') as $titleNode) {
                    $title .= $this->text($titleNode->textContent);
                    $titleTagsCount++;
                }
                      
                // Meta description
                foreach ($headNode->getElementsByTagName('meta') as $node) {
                    if (strtolower($node->getAttribute('name')) == 'description' && $this->text($node->getAttribute('content'))) {
                        $metaDescription = $this->text($node->getAttribute('content'));
                    }
                }
            
                // Favicon
                foreach ($headNode->getElementsByTagName('link') as $node) {
                    if (preg_match('/\bicon\b/i', $node->getAttribute('rel'))) {
                        $favicon = $this->url($node->getAttribute('href'));
                    }
                }
            

                // Noindex
                foreach ($headNode->getElementsByTagName('meta') as $node) {
                    if (strtolower($node->getAttribute('name')) == 'robots' || strtolower($node->getAttribute('name')) == 'googlebot') {
                        if (preg_match('/\bnoindex\b/', $node->getAttribute('content'))) {
                            $noIndex = $node->getAttribute('content');
                        }
                    }
                }
            
                // Structured data
                foreach ($headNode->getElementsByTagName('meta') as $node) {
                    if (preg_match('/\bog:\b/', $node->getAttribute('property')) && $node->getAttribute('content')) {
                        $structuredData['Open Graph'][$node->getAttribute('property')] = $this->text($node->getAttribute('content'));
                    }

                    if (preg_match('/\btwitter:\b/', $node->getAttribute('name')) && $node->getAttribute('content')) {
                        $structuredData['Twitter'][$node->getAttribute('name')] = $this->text($node->getAttribute('content'));
                    }
                }

                foreach ($domDocument->getElementsByTagName('script') as $node) {
                    if (strtolower($node->getAttribute('type')) == 'application/ld+json') {
                        $data = json_decode($node->nodeValue, true);
                       
                        if (isset($data['@context']) && !is_array($data['@context'])) {
                            if(in_array(strtolower($data['@context']), ['https://schema.org', 'http://schema.org'])){
                                $structuredData['Schema.org'] = $data;
                            }
                        }
                    }
                }
                
                // Meta viewport
                foreach ($headNode->getElementsByTagName('meta') as $node) {
                    if (strtolower($node->getAttribute('name')) == 'viewport') {
                        $metaViewport = $this->text($node->getAttribute('content'));
                    }
                }
            
                // Charset
                foreach ($headNode->getElementsByTagName('meta') as $node) {
                    if ($node->getAttribute('charset')) {
                        $charset = $this->text($node->getAttribute('charset'));
                    }
                }
            }

            // Headings
            $headings = [];
            foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $heading) {
                foreach ($domDocument->getElementsByTagName($heading) as $node) {
                    $headings[$heading][] = $this->text($node->textContent);
                }
            }

            // Content keywords
            $titleKeywords = array_filter(explode(' ', preg_replace('/[^\w]/ui', ' ', mb_strtolower($title))));

            // Image keywords
            $imageAlts = [];
            foreach ($domDocument->getElementsByTagName('img') as $node) {
                // If the node contains an href attribute
                if (!empty($node->getAttribute('src'))) {
                    // If the image does not have an alt attribute set
                    if (empty($node->getAttribute('alt'))) {
                        $imageAlts[] = [
                            'url' => $this->url($node->getAttribute('src')),
                            'text' => $this->text($node->getAttribute('alt'))
                        ];
                    }
                }
            }

            // Get all the link nodes
            $pageLinks = [];
            foreach ($domDocument->getElementsByTagName('a') as $node) {
                if (!empty($node->getAttribute('href')) && mb_substr($node->getAttribute('href'), 0, 1) != '#') {
                    if ($this->isInternalUrl($this->url($node->getAttribute('href')))) {
                        $pageLinks['Internals'][] = [
                            'url' => $this->url($node->getAttribute('href')),
                            'text' => $this->text($node->textContent),
                        ];
                    } else {
                        $pageLinks['Externals'][] = [
                            'url' => $this->url($node->getAttribute('href')),
                            'text' => $this->text($node->textContent),
                        ];
                    }
                }
            }

            // echo "<pre/>"; print_r($pageLinks); die;
            // HTTPS encryption
            $httpScheme = parse_url($this->url, PHP_URL_SCHEME);

            // 404 page
            if (!isset($this->cachedNotFoundPage)) {
                $notFoundPage = false;
                $notFoundUrl = parse_url($this->url, PHP_URL_SCHEME) . '://' . parse_url($this->url, PHP_URL_HOST) . '/404-' . md5(uniqid());
                try {
                    $httpClient = new HttpClient();
                    $httpClient->get($notFoundUrl, [
                        // 'proxy' => [
                        //     'http' => getRequestProxy(),
                        //     'https' => getRequestProxy()
                        // ],
                        'connect_timeout' => config('info.settings.request_connection_timeout'),
                        'headers' => [
                            'User-Agent' => config('info.settings.request_user_agent')
                        ]
                    ]);
                } catch (RequestException $e) {
                    if ($e->hasResponse()) {
                        if ($e->getResponse()->getStatusCode() == '404') {
                            $notFoundPage = $notFoundUrl;
                        }
                    }
                } catch (\Exception $e) {}

                $this->cachedNotFoundPage = $notFoundPage;
            } else {
                $notFoundPage = $this->cachedNotFoundPage;
            }

            // Robots & Sitemaps
            $sitemaps = [];
            $robotsRulesFailed = [];
            $robots = true;
           
            if (!isset($this->cachedRobotsRequest)) {
                $robotsUrl = parse_url($this->url, PHP_URL_SCHEME) . '://' . parse_url($this->url, PHP_URL_HOST) . '/robots.txt';

                try {
                    $httpClient = new HttpClient();
                    $this->cachedRobotsRequest = $httpClient->get($robotsUrl, [
                        // 'proxy' => [
                        //     'http' => getRequestProxy(),
                        //     'https' => getRequestProxy()
                        // ],
                        'connect_timeout' => config('info.settings.request_connection_timeout'),
                        'headers' => [
                            'User-Agent' => config('info.settings.request_user_agent')
                        ]
                    ]);
                } catch (\Exception $e) {}

                $robotsRequest = $this->cachedRobotsRequest;
            } else {
                $robotsRequest = $this->cachedRobotsRequest;
            }
            
            if($robotsRequest !== null){
               $robotsRules = preg_split('/\n|\r/', $robotsRequest->getBody()->getContents(), -1, PREG_SPLIT_NO_EMPTY);

                foreach ($robotsRules as $robotsRule) {
                    $rule = explode(':', $robotsRule, 2);

                    $directive = trim(strtolower($rule[0] ?? null));
                    $value = trim($rule[1] ?? null);

                    if ($directive == 'disallow' && $value) {
                        if (preg_match($this->formatRobotsRule($value), $this->url)) {
                            $robotsRulesFailed[] = $value;
                            $robots = false;
                        }
                    }

                    if ($directive == 'sitemap') {
                        // If the sitemap rule has a value
                        if ($value) {
                            $sitemaps[] = $value;
                        }
                    }
                } 
            }
            

            

            // Language
            $language = null;
            foreach ($domDocument->getElementsByTagName('html') as $node) {
                if ($node->getAttribute('lang')) {
                    $language = $node->getAttribute('lang');
                }
            }

           

            // Plaintext email
            $plaintextEmails = [];
            preg_match_all('/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9_-]+)/i', $reportResponse, $plaintextEmails, PREG_UNMATCHED_AS_NULL);

            if (isset($plaintextEmails[0])) {
                // Validate the email address
                $plaintextEmails[0] = array_filter($plaintextEmails[0], function($email) { return filter_var($email, FILTER_VALIDATE_EMAIL); });
            }

            // Google Safe Browsing
            if (config('info.software.gsb') && config('info.software.gsb_key')) {
                $gsb = false;
                try {
                    $httpClient = new HttpClient();
                    $gsbRequest = $httpClient->request('POST', 'https://safebrowsing.googleapis.com/v4/threatMatches:find?key=' . config('info.software.gsb_key'), [
                            'headers' => [
                                'Content-Type' => 'application/json'
                            ],
                            'body' => json_encode([
                                'client' => [
                                    'clientId' => mb_strtolower(config('info.software.name')),
                                    'clientVersion' => config('info.software.version'),
                                ],
                                'threatInfo' => [
                                    'threatTypes' => [
                                        'MALWARE', 'SOCIAL_ENGINEERING', 'UNWANTED_SOFTWARE', 'POTENTIALLY_HARMFUL_APPLICATION'
                                    ],
                                    'platformTypes' => [
                                        'ALL_PLATFORMS',
                                    ],
                                    'threatEntryTypes' => [
                                        'URL', 'EXECUTABLE'
                                    ],
                                    'threatEntries' => [
                                        ['url' => $reportRequestStats['url']]
                                    ],
                                ],
                            ])
                        ]
                    );

                    $gsbResponse = json_decode($gsbRequest->getBody()->getContents(), true);

                    // If no threats found
                    if (empty($gsbResponse)) {
                        $gsb = true;
                    }
                } catch (\Exception $e) {}
            }

            // Http requests
            $httpRequests = [];
            foreach ($domDocument->getElementsByTagName('script') as $node) {
                // If the script has a source
                if ($node->getAttribute('src')) {
                    $httpRequests['JavaScripts'][] = $this->url($node->getAttribute('src'));
                }
            }
            foreach ($domDocument->getElementsByTagName('link') as $node) {
                // If the link is a stylesheet
                if (preg_match('/\bstylesheet\b/', $node->getAttribute('rel'))) {
                    $httpRequests['CSS'][] = $this->url($node->getAttribute('href'));
                }
            }
            foreach ($domDocument->getElementsByTagName('img') as $node) {
                if (!empty($node->getAttribute('src'))) {
                    if (!preg_match('/\blazy\b/', $node->getAttribute('loading')) && $node->getAttribute('src')) {
                        $httpRequests['Images'][] = $this->url($node->getAttribute('src'));
                    }
                }
            }
            foreach ($domDocument->getElementsByTagName('iframe') as $node) {
                if (!empty($node->getAttribute('src'))) {
                    if (!preg_match('/\blazy\b/', $node->getAttribute('loading')) && $node->getAttribute('src')) {
                        $httpRequests['Iframes'][] = $this->url($node->getAttribute('src'));
                    }
                }
            }

            // Image format
            $imageFormats = [];
            foreach ($domDocument->getElementsByTagName('img') as $node) {
                // If the node contains an href attribute
                if (!empty($node->getAttribute('src'))) {
                    // If the image is not in a next-gen format or an SVG
                    if (!in_array(mb_strtolower(pathinfo($this->url, PATHINFO_EXTENSION)), array_map('strtolower', preg_split('/\n|\r/', config('settings.report_limit_image_formats'), -1, PREG_SPLIT_NO_EMPTY))) && mb_strtolower(pathinfo($this->url($node->getAttribute('src')), PATHINFO_EXTENSION)) != 'svg') {
                        // Work-around for: https://bugs.php.net/bug.php?id=73175
                        $search = '\/';
                        foreach (preg_split('/\n|\r/', config('info.settings.report_limit_image_formats'), -1, PREG_SPLIT_NO_EMPTY) as $format) {
                            $search .= preg_quote(pathinfo($node->getAttribute('src'), PATHINFO_FILENAME)) . '\.' . strtolower(preg_quote($format)) . '\"|\/';
                        }
                        if (!preg_match('/' . mb_substr($search, 0, -3) . '/', $reportResponse)) {
                            $imageFormats[] = [
                                'url' => $this->url($node->getAttribute('src')),
                                'text' => $this->text($node->getAttribute('alt'))
                            ];
                        }
                    }
                }
            }

            // Defer JavaScript
            $deferJavaScript = [];
            foreach ($domDocument->getElementsByTagName('script') as $node) {
                // If the script has a source
                if ($node->getAttribute('src') && !$node->hasAttribute('defer')) {
                    $deferJavaScript[] = $this->url($node->getAttribute('src'));
                }
            }

            // DOM size
            $domNodesCount = count($domDocument->getElementsByTagName('*'));

            

            // SEO friendly URL
            $urlKeywords = array_filter(explode(' ', preg_replace('/[^\w]/ui', ' ', mb_strtolower(str_replace(['https://www.', 'http://www.', 'https://', 'http://'], '', $this->url)))));

            

            // Text to HTML ratio
            $textRatio = round(((!empty($reportResponse) && !empty($pageText)) ? (mb_strlen($pageText) / mb_strlen($reportResponse) * 100) : 0));

            // Deprecated HTML
            $deprecatedHtmlTags = [];
            foreach (preg_split('/\n|\r/', config('settings.report_limit_deprecated_html_tags'), -1, PREG_SPLIT_NO_EMPTY) as $tagName) {
                foreach ($domDocument->getElementsByTagName($tagName) as $node) {
                    if (isset($deprecatedHtmlTags[$node->nodeName])) {
                        $deprecatedHtmlTags[$node->nodeName] += 1;
                    } else {
                        $deprecatedHtmlTags[$node->nodeName] = 1;
                    }
                }
            }

            // Social
            $social = [];
            foreach ($domDocument->getElementsByTagName('a') as $node) {
                if (!empty($node->getAttribute('href')) && mb_substr($node->getAttribute('href'), 0, 1) != '#') {
                    if (!$this->isInternalUrl($this->url($node->getAttribute('href')))) {
                        $socials = ['twitter.com' => 'Twitter', 'www.twitter.com' => 'Twitter', 'facebook.com' => 'Facebook', 'www.facebook.com' => 'Facebook', 'instagram.com' => 'Instagram', 'www.instagram.com' => 'Instagram', 'youtube.com' => 'YouTube', 'www.youtube.com' => 'YouTube', 'linkedin.com' => 'LinkedIn', 'www.linkedin.com' => 'LinkedIn'];

                        $host = parse_url($this->url($node->getAttribute('href')), PHP_URL_HOST);

                        if (!empty($host) && array_key_exists($host, $socials)) {
                            $social[$socials[$host]][] = [
                                'url' => $this->url($node->getAttribute('href')),
                                'text' => $this->text($node->textContent),
                            ];
                        }
                    }
                }
            }

            // Inline CSS
            $inlineCss = [];
            foreach ($domDocument->getElementsByTagName('*') as $node) {
                if ($node->nodeName != 'svg' && !empty($node->getAttribute('style'))) {
                    $inlineCss[] = $node->getAttribute('style');
                }
            }

            // Title
            $data['results']['title'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => $title
            ];

            // If there's no title
            if (!$title) {
                $summaryData->title += 1;
                $summaryData->criticals += 1;
                $data['results']['title']['passed'] = false;
                $data['results']['title']['errors']['missing'] = null;
            }
            
            // If the title length is incorrect
            if (mb_strlen($title) < config('info.global.reportLimitMinTitle') || mb_strlen($title) > config('info.global.reportLimitMaxTitle')) {
                $summaryData->title += 1;
                $summaryData->criticals += 1;
                $data['results']['title']['passed'] = false;
                $data['results']['title']['errors']['length'] = ['min' => config('info.global.reportLimitMinTitle'), 'max' => config('info.global.reportLimitMaxTitle')];
            }

            if ($titleTagsCount > 1) {
                $summaryData->title += 1;
                $summaryData->criticals += 1;
                $data['results']['title']['passed'] = false;
                $data['results']['title']['errors']['too_many'] = null;
            }

            // Meta description
            $data['results']['meta_description'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => $metaDescription
            ];

            // If there's no meta description
            if (!$metaDescription) {
                $summaryData->criticals += 1;
                $summaryData->meta_description += 1;
                $data['results']['meta_description']['passed'] = false;
                $data['results']['meta_description']['errors']['missing'] = null;
            }

            // Headings
            $data['results']['headings'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => $headings
            ];

            // If there's no h1 tag
            if (!isset($headings['h1'])) {
                $summaryData->headings += 1;
                $summaryData->criticals += 1;
                $data['results']['headings']['passed'] = false;
                $data['results']['headings']['errors']['missing'] = null;
            }

            // If there's more than one h1 tag on the page
            if (isset($headings['h1']) && count($headings['h1']) > 1) {
                $summaryData->headings += 1;
                $summaryData->criticals += 1;
                $data['results']['headings']['passed'] = false;
                $data['results']['headings']['errors']['too_many'] = null;
            }

            // If the h1 tag is the same with the title tag
            if (isset($headings['h1'][0]) && $headings['h1'][0] == $title) {
                $data['results']['headings']['passed'] = false;
                $data['results']['headings']['errors']['duplicate'] = null;
            }

            // Content keywords
            $data['results']['content_keywords'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => array_intersect($titleKeywords, $bodyKeywords)
            ];

            // If the content keywords are not found in the title
            if (!array_intersect($titleKeywords, $bodyKeywords)) {
                $summaryData->content_keywords += 1;
                $summaryData->criticals += 1;
                $data['results']['content_keywords']['passed'] = false;
                $data['results']['content_keywords']['errors']['missing'] = $titleKeywords;
            }

            // Image keywords
            $data['results']['image_keywords'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => null
            ];

            // If there are images with no alt attribute set
            if (count($imageAlts) > 0) {
                $summaryData->image_keywords += 1;
                $summaryData->criticals += 1;
                $data['results']['image_keywords']['passed'] = false;
                $data['results']['image_keywords']['errors']['missing'] = $imageAlts;
            }

            // Image format
            $data['results']['image_format'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => preg_split('/\n|\r/', config('info.settings.report_limit_image_formats'), -1, PREG_SPLIT_NO_EMPTY)
            ];

            // If there are images that are not in WebP format
            if (count($imageFormats) > 0) {
                $summaryData->image_format += 1;
                $summaryData->warnings += 1;
                $data['results']['image_format']['passed'] = false;
                $data['results']['image_format']['errors']['bad_format'] = $imageFormats;
            }

            // In-page links
            $data['results']['in_page_links'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => $pageLinks
            ];

            // If there are too many links
            if (array_sum(array_map('count', $pageLinks)) > config('info.global.reportLimitMaxlinks')) {
                $summaryData->in_page_links += 1;
                $summaryData->warnings += 1;
                $data['results']['in_page_links']['passed'] = false;
                $data['results']['in_page_links']['errors']['too_many'] = ['max' => config('info.global.reportLimitMaxlinks')];
            }

            // Load time
            $data['results']['load_time'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => $reportRequestStats['total_time']
            ];

            // If the load time exceeds the limit
            if ($reportRequestStats['total_time'] > config('info.global.reportLimitLoadTime')) {
                $summaryData->load_time += 1;
                $summaryData->warnings += 1;
                $data['results']['load_time']['passed'] = false;
                $data['results']['load_time']['errors']['too_slow'] = ['max' => config('info.global.reportLimitLoadTime')];
            }

            // Page size
            $data['results']['page_size'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => $reportRequestStats['size_download']
            ];

            // If the page size exceeds the limit
            if ($reportRequestStats['size_download'] > config('info.global.reportLimitPageSize')) {
                $summaryData->page_size += 1;
                $summaryData->warnings += 1;
                $data['results']['page_size']['passed'] = false;
                $data['results']['page_size']['errors']['too_large'] = ['max' => config('info.global.reportLimitPageSize')];
            }

            // HTTP requests
            $data['results']['http_requests'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => $httpRequests
            ];

            // If there are too many HTTP requests
            if (array_sum(array_map('count', $httpRequests)) > config('info.global.reportLimitHttpRequests')) {
                $summaryData->http_requests += 1;
                $summaryData->warnings += 1;
                $data['results']['http_requests']['passed'] = false;
                $data['results']['http_requests']['errors']['too_many'] = ['max' => config('info.global.reportLimitHttpRequests')];
            }

            // Defer JavaScript
            $data['results']['defer_javascript'] = [
                'passed' => true,
                'importance' => 'low',
                'value' => null
            ];

            // If there are resources without the defer attribute
            if (count($deferJavaScript) > 0) {
                $summaryData->defer_javascript += 1;
                $summaryData->notices += 1;
                $data['results']['defer_javascript']['passed'] = false;
                $data['results']['defer_javascript']['errors']['missing'] = $deferJavaScript;
            }

            // DOM size
            $data['results']['dom_size'] = [
                'passed' => true,
                'importance' => 'low',
                'value' => $domNodesCount
            ];

            // DOM size
            if ($domNodesCount > config('info.global.reportLimitMaxDomNodes')) {
                $summaryData->dom_size += 1;
                $summaryData->notices += 1;
                $data['results']['dom_size']['passed'] = false;
                $data['results']['dom_size']['errors']['too_many'] = ['max' => config('info.global.reportLimitMaxDomNodes')];
            }

            // Text compression
            $data['results']['text_compression'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => $reportRequestStats['size_download'],
            ];

            // If the page is not text_compression compressed
            if (!in_array('gzip', $reportRequest->getHeader('x-encoded-content-encoding'))) {
                $summaryData->text_compression += 1;
                $summaryData->criticals += 1;
                $data['results']['text_compression']['passed'] = false;
                $data['results']['text_compression']['errors']['missing'] = null;
            }

            // Structured data
            $data['results']['structured_data'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => $structuredData
            ];

            // If there's no structured data
            if (empty($structuredData)) {
                $summaryData->structured_data += 1;
                $summaryData->warnings += 1;
                $data['results']['structured_data']['passed'] = false;
                $data['results']['structured_data']['errors']['missing'] = null;
            }

            // Meta viewport
            $data['results']['meta_viewport'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => $metaViewport
            ];

            // If the page does not have a meta viewport set
            if (!$metaViewport) {
                $summaryData->meta_viewport += 1;
                $summaryData->warnings += 1;
                $data['results']['meta_viewport']['passed'] = false;
                $data['results']['meta_viewport']['errors']['missing'] = null;
            }

            // HTTPS encryption
            $data['results']['https_encryption'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => $reportRequestStats['url']
            ];

            // If the page is not served over HTTPS
            if (strtolower($httpScheme) != 'https') {
                $summaryData->https_encryption += 1;
                $summaryData->criticals += 1;
                $data['results']['https_encryption']['passed'] = false;
                $data['results']['https_encryption']['errors']['missing'] = 'https';
            }

            // SEO friendly URL
            $data['results']['seo_friendly_url'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => $reportRequestStats['url']
            ];

            // If the URL contains characters that are not considered friendly (?=_%, )
            if (preg_match('/[\?\=\_\%\,\ ]/ui', $reportRequestStats['url'])) {
                $summaryData->seo_friendly_url += 1;
                $summaryData->criticals += 1;
                $data['results']['seo_friendly_url']['passed'] = false;
                $data['results']['seo_friendly_url']['errors']['bad_format'] = null;
            }

            // If the title keywords are not found in URL keywords
            if (empty(array_filter($titleKeywords, function ($keyword) { if (strpos(mb_strtolower($this->url),mb_strtolower($keyword)) !== false) { return true; } return false; }))) {
                $summaryData->seo_friendly_url += 1;
                $summaryData->criticals += 1;
                $data['results']['seo_friendly_url']['passed'] = false;
                $data['results']['seo_friendly_url']['errors']['missing'] = null;
            }

            // Language
            $data['results']['language'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => $language
            ];

            // If the page doesn't have a language set
            if (!$language) {
                $summaryData->language += 1;
                $summaryData->warnings += 1;
                $data['results']['language']['passed'] = false;
                $data['results']['language']['errors']['missing'] = null;
            }

            // Favicon
            $data['results']['favicon'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => $favicon
            ];

            // If the page doesn't have a favicon
            if (!$favicon) {
                $summaryData->favicon += 1;
                $summaryData->warnings += 1;
                $data['results']['favicon']['passed'] = false;
                $data['results']['favicon']['errors']['missing'] = null;
            }

            // Google Safe Browsing
            if (config('info.software.gsb') && config('info.software.gsb_key')) {
                $data['results']['gsb'] = [
                    'passed' => true,
                    'importance' => 'high',
                    'value' => $title
                ];

                // If threats were found
                if (!$gsb) {
                    $summaryData->gsb += 1;
                    $summaryData->criticals += 1;
                    $data['results']['gsb']['passed'] = false;
                    $data['results']['gsb']['errors']['failed'] = null;
                }
            }

            // Content length
            $data['results']['content_length'] = [
                'passed' => true,
                'importance' => 'low',
                'value' => count($bodyKeywords)
            ];

            // If there are not enough words on the page
            if (count($bodyKeywords) < config('info.global.reportLimitMinWords')) {
                $summaryData->content_length += 1;
                $summaryData->notices += 1;
                $data['results']['content_length']['passed'] = false;
                $data['results']['content_length']['errors']['too_few'] = ['min' => config('info.global.reportLimitMinWords')];
            }

            // Text to HTML ratio
            $data['results']['text_html_ratio'] = [
                'passed' => true,
                'importance' => 'low',
                'value' => $textRatio
            ];

            // If the text ratio is less than the minimum ratio
            if ($textRatio < config('info.global.reportLimitMinTextRatio')) {
                $summaryData->text_html_ratio += 1;
                $summaryData->notices += 1;
                $data['results']['text_html_ratio']['passed'] = false;
                $data['results']['text_html_ratio']['errors']['too_small'] = ['min' => config('info.global.reportLimitMinTextRatio')];
            }

            // Charset
            $data['results']['charset'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => $charset
            ];

            // If the page doesn't have a charset set
            if (!$charset) {
                $summaryData->content_length += 1;
                $summaryData->notices += 1;
                $data['results']['charset']['passed'] = false;
                $data['results']['charset']['errors']['missing'] = null;
            }

            // Deprecated HTML tags
            $data['results']['deprecated_html_tags'] = [
                'passed' => true,
                'importance' => 'low',
                'value' => null
            ];

            // If the page has deprecated HTML tags
            if (count($deprecatedHtmlTags) > 1) {
                $summaryData->deprecated_html_tags += 1;
                $summaryData->notices += 1;
                $data['results']['deprecated_html_tags']['passed'] = false;
                $data['results']['deprecated_html_tags']['errors']['bad_tags'] = $deprecatedHtmlTags;
            }

            // 404 page
            $data['results']['404_page'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => $notFoundPage
            ];

            // If the website does not have a 404 page
            if (!$notFoundPage) {
                //$summaryData->404_page += 1;
                //$summaryData->criticals += 1;
                $data['results']['404_page']['passed'] = false;
                $data['results']['404_page']['errors']['missing'] = null;
            }

            // Noindex
            $data['results']['noindex'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => $noIndex
            ];

            // If the website has noindex
            if ($noIndex) {
                $summaryData->criticals += 1;
                $data['results']['noindex']['passed'] = false;
                $data['results']['noindex']['errors']['missing'] = null;
            }else{
                $summaryData->noindex += 1;
                
            }

            // Robots
            $data['results']['robots'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => null
            ];

            // If the page is blocked by robots.txt rule
            if (!$robots) {
                $summaryData->robots += 1;
                $summaryData->criticals += 1;
                $data['results']['robots']['passed'] = false;
                $data['results']['robots']['errors']['failed'] = $robotsRulesFailed;
            }

            // Sitemap
            $data['results']['sitemap'] = [
                'passed' => true,
                'importance' => 'low',
                'value' => $sitemaps
            ];

            // If the website does not have sitemaps
            if (empty($sitemaps)) {
                $summaryData->sitemap += 1;
                $summaryData->notices += 1;
                $data['results']['sitemap']['passed'] = false;
                $data['results']['sitemap']['errors']['failed'] = null;
            }

            // Plaintext email
            $data['results']['plaintext_email'] = [
                'passed' => true,
                'importance' => 'low',
                'value' => null
            ];

            // If there are plaintext emails
            if (isset($plaintextEmails[0]) && !empty($plaintextEmails[0])) {
                $summaryData->plaintext_email += 1;
                $summaryData->notices += 1;
                $data['results']['plaintext_email']['passed'] = false;
                $data['results']['plaintext_email']['errors']['failed'] = $plaintextEmails[0];
            }

            // Structured data
            $data['results']['social'] = [
                'passed' => true,
                'importance' => 'low',
                'value' => $social
            ];

            // If there's no social links
            if (empty($social)) {
                $summaryData->social += 1;
                $summaryData->notices += 1;
                $data['results']['social']['passed'] = false;
                $data['results']['social']['errors']['missing'] = null;
            }

            // Inline CSS
            $data['results']['inline_css'] = [
                'passed' => true,
                'importance' => 'low',
                'value' => null
            ];

            // If the page has inline CSS
            if (count($inlineCss) > 1) {
                $summaryData->inline_css += 1;
                $summaryData->notices += 1;
                $data['results']['inline_css']['passed'] = false;
                $data['results']['inline_css']['errors']['failed'] = $inlineCss;
            }

            $totalPoints = 0;
            $resultPoints = 0;
            foreach ($data['results'] as $key => $value) {
                $totalPoints = $totalPoints + config('settings.report_score_' . $value['importance']);

                if ($value['passed']) {
                    $resultPoints += config('settings.report_score_' . $value['importance']);
                }
            }

            // If the request is to store the model
            if (!$report->url) {
                $report->url = $this->url;
                $report->user_id = $request->user_id;
            }

            $report->results = mb_convert_encoding($data['results'], 'UTF-8', 'UTF-8');
            $report->project = $report->fullUrl;
            $report->audit_id = $request->audit_id;
            $report->result = (($report->score / $report->totalScore) * 100);
            $report->generated_at = Carbon::now();
            $summaryData->total_tests += count($data['results']);
        }

        
       $summaryData->save();
       $report->save();
      
        SiteAuditSummary::updateSummaryScore($request->audit_id);
        return $report;
    }

    public function modelUpdate($request, $report, $reportRequest, $reportRequestStats)
    {   

        $summaryData = SiteAuditSummary::where('id',$report->audit_id)->first();
        
       
        // If the request contains a report request
        $totalTests = 0;
        if ($reportRequest) {

            // $summaryData['criticals'] =  $summaryData['warnings'] =  $summaryData['notices'] = 0;

            $reportResponse = $reportRequest->getBody()->getContents();
            $reportRequestStats = $reportRequestStats->getHandlerStats();
            // $request->input('url');

            $this->url = $$request->input('url');

            $domDocument = new \DOMDocument();
            libxml_use_internal_errors(true);

            $domDocument->loadHTML('<?xml encoding="utf-8" ?>' . $reportResponse ?? null);

            // Get the page text
            $pageText = $this->text($domDocument->getElementsByTagName('body')->item(0)->textContent ?? null);

            // Filter the words
            $bodyKeywords = array_filter(explode(' ', preg_replace('/[^\w]/ui', ' ', mb_strtolower($pageText))));

            
            $title = $charset = $metaDescription = $favicon = $noIndex = $metaViewport = null;
            $structuredData = [];
            $titleTagsCount = 0;
            foreach ($domDocument->getElementsByTagName('head') as $headNode) {
                // Title

                // $title = $charset = $metaDescription = $favicon = $noIndex = $metaViewport = null;
                // $structuredData = [];
                // $titleTagsCount = 0;
                foreach ($headNode->getElementsByTagName('title') as $titleNode) {
                    $title .= $this->text($titleNode->textContent);
                    $titleTagsCount++;
                }
                      
                // Meta description
                foreach ($headNode->getElementsByTagName('meta') as $node) {
                    if (strtolower($node->getAttribute('name')) == 'description' && $this->text($node->getAttribute('content'))) {
                        $metaDescription = $this->text($node->getAttribute('content'));
                    }
                }
            
                // Favicon
                foreach ($headNode->getElementsByTagName('link') as $node) {
                    if (preg_match('/\bicon\b/i', $node->getAttribute('rel'))) {
                        $favicon = $this->url($node->getAttribute('href'));
                    }
                }
            

                // Noindex
                foreach ($headNode->getElementsByTagName('meta') as $node) {
                    if (strtolower($node->getAttribute('name')) == 'robots' || strtolower($node->getAttribute('name')) == 'googlebot') {
                        if (preg_match('/\bnoindex\b/', $node->getAttribute('content'))) {
                            $noIndex = $node->getAttribute('content');
                        }
                    }
                }
            
                // Structured data
                foreach ($headNode->getElementsByTagName('meta') as $node) {
                    if (preg_match('/\bog:\b/', $node->getAttribute('property')) && $node->getAttribute('content')) {
                        $structuredData['Open Graph'][$node->getAttribute('property')] = $this->text($node->getAttribute('content'));
                    }

                    if (preg_match('/\btwitter:\b/', $node->getAttribute('name')) && $node->getAttribute('content')) {
                        $structuredData['Twitter'][$node->getAttribute('name')] = $this->text($node->getAttribute('content'));
                    }
                }

                foreach ($domDocument->getElementsByTagName('script') as $node) {
                    if (strtolower($node->getAttribute('type')) == 'application/ld+json') {
                        $data = json_decode($node->nodeValue, true);

                        if (isset($data['@context']) && in_array(mb_strtolower($data['@context']), ['https://schema.org', 'http://schema.org'])) {
                            $structuredData['Schema.org'] = $data;
                        }
                    }
                }
            
                // Meta viewport
                foreach ($headNode->getElementsByTagName('meta') as $node) {
                    if (strtolower($node->getAttribute('name')) == 'viewport') {
                        $metaViewport = $this->text($node->getAttribute('content'));
                    }
                }
            
                // Charset
                foreach ($headNode->getElementsByTagName('meta') as $node) {
                    if ($node->getAttribute('charset')) {
                        $charset = $this->text($node->getAttribute('charset'));
                    }
                }
            }

            // Headings
            $headings = [];
            foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $heading) {
                foreach ($domDocument->getElementsByTagName($heading) as $node) {
                    $headings[$heading][] = $this->text($node->textContent);
                }
            }

            // Content keywords
            $titleKeywords = array_filter(explode(' ', preg_replace('/[^\w]/ui', ' ', mb_strtolower($title))));

            // Image keywords
            $imageAlts = [];
            foreach ($domDocument->getElementsByTagName('img') as $node) {
                // If the node contains an href attribute
                if (!empty($node->getAttribute('src'))) {
                    // If the image does not have an alt attribute set
                    if (empty($node->getAttribute('alt'))) {
                        $imageAlts[] = [
                            'url' => $this->url($node->getAttribute('src')),
                            'text' => $this->text($node->getAttribute('alt'))
                        ];
                    }
                }
            }

            // Get all the link nodes
            $pageLinks = [];
            foreach ($domDocument->getElementsByTagName('a') as $node) {
                if (!empty($node->getAttribute('href')) && mb_substr($node->getAttribute('href'), 0, 1) != '#') {
                    if ($this->isInternalUrl($this->url($node->getAttribute('href')))) {
                        $pageLinks['Internals'][] = [
                            'url' => $this->url($node->getAttribute('href')),
                            'text' => $this->text($node->textContent),
                        ];
                    } else {
                        $pageLinks['Externals'][] = [
                            'url' => $this->url($node->getAttribute('href')),
                            'text' => $this->text($node->textContent),
                        ];
                    }
                }
            }

            // echo "<pre/>"; print_r($pageLinks); die;
            // HTTPS encryption

            $httpScheme = parse_url($report->url, PHP_URL_SCHEME);

            // 404 page
            if (!isset($this->cachedNotFoundPage)) {
                $notFoundPage = false;
                $notFoundUrl = parse_url($report->url, PHP_URL_SCHEME) . '://' . parse_url($report->url, PHP_URL_HOST) . '/404-' . md5(uniqid());
                try {
                    $httpClient = new HttpClient();
                    $httpClient->get($notFoundUrl, [
                        // 'proxy' => [
                        //     'http' => getRequestProxy(),
                        //     'https' => getRequestProxy()
                        // ],
                        'connect_timeout' => config('info.settings.request_connection_timeout'),
                        'headers' => [
                            'User-Agent' => config('info.settings.request_user_agent')
                        ]
                    ]);

                } catch (RequestException $e) {
                    if ($e->hasResponse()) {
                        if ($e->getResponse()->getStatusCode() == '404') {
                            $notFoundPage = $notFoundUrl;
                        }
                    }
                } catch (\Exception $e) {}

                $this->cachedNotFoundPage = $notFoundPage;
            } else {
                $notFoundPage = $this->cachedNotFoundPage;
            }

            // Robots & Sitemaps
            $sitemaps = [];
            $robotsRulesFailed = [];
            $robots = true;

            if (!isset($this->cachedRobotsRequest)) {
                $robotsUrl = parse_url($report->url, PHP_URL_SCHEME) . '://' . parse_url($report->url, PHP_URL_HOST) . '/robots.txt';
                
                try {
                    $httpClient = new HttpClient();
                    $this->cachedRobotsRequest = $httpClient->get($robotsUrl, [
                        // 'proxy' => [
                        //     'http' => getRequestProxy(),
                        //     'https' => getRequestProxy()
                        // ],
                        'connect_timeout' => config('info.settings.request_connection_timeout'),
                        'headers' => [
                            'User-Agent' => config('info.settings.request_user_agent')
                        ]
                    ]);
                } catch (\Exception $e) {}

                $robotsRequest = $this->cachedRobotsRequest;
            } else {
                $robotsRequest = $this->cachedRobotsRequest;
            }

            $robotsRules = preg_split('/\n|\r/', $robotsRequest->getBody()->getContents(), -1, PREG_SPLIT_NO_EMPTY);

            foreach ($robotsRules as $robotsRule) {
                $rule = explode(':', $robotsRule, 2);

                $directive = trim(strtolower($rule[0] ?? null));
                $value = trim($rule[1] ?? null);

                if ($directive == 'disallow' && $value) {
                    if (preg_match($this->formatRobotsRule($value), $this->url)) {
                        $robotsRulesFailed[] = $value;
                        $robots = false;
                    }
                }

                if ($directive == 'sitemap') {
                    // If the sitemap rule has a value
                    if ($value) {
                        $sitemaps[] = $value;
                    }
                }
            }

            

            // Language
            $language = null;
            foreach ($domDocument->getElementsByTagName('html') as $node) {
                if ($node->getAttribute('lang')) {
                    $language = $node->getAttribute('lang');
                }
            }

           

            // Plaintext email
            $plaintextEmails = [];
            preg_match_all('/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9_-]+)/i', $reportResponse, $plaintextEmails, PREG_UNMATCHED_AS_NULL);

            if (isset($plaintextEmails[0])) {
                // Validate the email address
                $plaintextEmails[0] = array_filter($plaintextEmails[0], function($email) { return filter_var($email, FILTER_VALIDATE_EMAIL); });
            }

            // Google Safe Browsing
            if (config('info.software.gsb') && config('info.software.gsb_key')) {
                $gsb = false;
                try {
                    $httpClient = new HttpClient();
                    $gsbRequest = $httpClient->request('POST', 'https://safebrowsing.googleapis.com/v4/threatMatches:find?key=' . config('info.software.gsb_key'), [
                            'headers' => [
                                'Content-Type' => 'application/json'
                            ],
                            'body' => json_encode([
                                'client' => [
                                    'clientId' => mb_strtolower(config('info.software.name')),
                                    'clientVersion' => config('info.software.version'),
                                ],
                                'threatInfo' => [
                                    'threatTypes' => [
                                        'MALWARE', 'SOCIAL_ENGINEERING', 'UNWANTED_SOFTWARE', 'POTENTIALLY_HARMFUL_APPLICATION'
                                    ],
                                    'platformTypes' => [
                                        'ALL_PLATFORMS',
                                    ],
                                    'threatEntryTypes' => [
                                        'URL', 'EXECUTABLE'
                                    ],
                                    'threatEntries' => [
                                        ['url' => $reportRequestStats['url']]
                                    ],
                                ],
                            ])
                        ]
                    );

                    $gsbResponse = json_decode($gsbRequest->getBody()->getContents(), true);

                    // If no threats found
                    if (empty($gsbResponse)) {
                        $gsb = true;
                    }
                } catch (\Exception $e) {}
            }

            // Http requests
            $httpRequests = [];
            foreach ($domDocument->getElementsByTagName('script') as $node) {
                // If the script has a source
                if ($node->getAttribute('src')) {
                    $httpRequests['JavaScripts'][] = $this->url($node->getAttribute('src'));
                }
            }
            foreach ($domDocument->getElementsByTagName('link') as $node) {
                // If the link is a stylesheet
                if (preg_match('/\bstylesheet\b/', $node->getAttribute('rel'))) {
                    $httpRequests['CSS'][] = $this->url($node->getAttribute('href'));
                }
            }
            foreach ($domDocument->getElementsByTagName('img') as $node) {
                if (!empty($node->getAttribute('src'))) {
                    if (!preg_match('/\blazy\b/', $node->getAttribute('loading')) && $node->getAttribute('src')) {
                        $httpRequests['Images'][] = $this->url($node->getAttribute('src'));
                    }
                }
            }
            foreach ($domDocument->getElementsByTagName('iframe') as $node) {
                if (!empty($node->getAttribute('src'))) {
                    if (!preg_match('/\blazy\b/', $node->getAttribute('loading')) && $node->getAttribute('src')) {
                        $httpRequests['Iframes'][] = $this->url($node->getAttribute('src'));
                    }
                }
            }

            // Image format
            $imageFormats = [];
            foreach ($domDocument->getElementsByTagName('img') as $node) {
                // If the node contains an href attribute
                if (!empty($node->getAttribute('src'))) {
                    // If the image is not in a next-gen format or an SVG
                    if (!in_array(mb_strtolower(pathinfo($this->url, PATHINFO_EXTENSION)), array_map('strtolower', preg_split('/\n|\r/', config('settings.report_limit_image_formats'), -1, PREG_SPLIT_NO_EMPTY))) && mb_strtolower(pathinfo($this->url($node->getAttribute('src')), PATHINFO_EXTENSION)) != 'svg') {
                        // Work-around for: https://bugs.php.net/bug.php?id=73175
                        $search = '\/';
                        foreach (preg_split('/\n|\r/', config('settings.report_limit_image_formats'), -1, PREG_SPLIT_NO_EMPTY) as $format) {
                            $search .= preg_quote(pathinfo($node->getAttribute('src'), PATHINFO_FILENAME)) . '\.' . strtolower(preg_quote($format)) . '\"|\/';
                        }
                        if (!preg_match('/' . mb_substr($search, 0, -3) . '/', $reportResponse)) {
                            $imageFormats[] = [
                                'url' => $this->url($node->getAttribute('src')),
                                'text' => $this->text($node->getAttribute('alt'))
                            ];
                        }
                    }
                }
            }

            // Defer JavaScript
            $deferJavaScript = [];
            foreach ($domDocument->getElementsByTagName('script') as $node) {
                // If the script has a source
                if ($node->getAttribute('src') && !$node->hasAttribute('defer')) {
                    $deferJavaScript[] = $this->url($node->getAttribute('src'));
                }
            }

            // DOM size
            $domNodesCount = count($domDocument->getElementsByTagName('*'));

            

            // SEO friendly URL
            $urlKeywords = array_filter(explode(' ', preg_replace('/[^\w]/ui', ' ', mb_strtolower(str_replace(['https://www.', 'http://www.', 'https://', 'http://'], '', $report->url)))));

            

            // Text to HTML ratio
            $textRatio = round(((!empty($reportResponse) && !empty($pageText)) ? (mb_strlen($pageText) / mb_strlen($reportResponse) * 100) : 0));

            // Deprecated HTML
            $deprecatedHtmlTags = [];
            foreach (preg_split('/\n|\r/', config('settings.report_limit_deprecated_html_tags'), -1, PREG_SPLIT_NO_EMPTY) as $tagName) {
                foreach ($domDocument->getElementsByTagName($tagName) as $node) {
                    if (isset($deprecatedHtmlTags[$node->nodeName])) {
                        $deprecatedHtmlTags[$node->nodeName] += 1;
                    } else {
                        $deprecatedHtmlTags[$node->nodeName] = 1;
                    }
                }
            }

            // Social
            $social = [];
            foreach ($domDocument->getElementsByTagName('a') as $node) {
                if (!empty($node->getAttribute('href')) && mb_substr($node->getAttribute('href'), 0, 1) != '#') {
                    if (!$this->isInternalUrl($this->url($node->getAttribute('href')))) {
                        $socials = ['twitter.com' => 'Twitter', 'www.twitter.com' => 'Twitter', 'facebook.com' => 'Facebook', 'www.facebook.com' => 'Facebook', 'instagram.com' => 'Instagram', 'www.instagram.com' => 'Instagram', 'youtube.com' => 'YouTube', 'www.youtube.com' => 'YouTube', 'linkedin.com' => 'LinkedIn', 'www.linkedin.com' => 'LinkedIn'];

                        $host = parse_url($this->url($node->getAttribute('href')), PHP_URL_HOST);

                        if (!empty($host) && array_key_exists($host, $socials)) {
                            $social[$socials[$host]][] = [
                                'url' => $this->url($node->getAttribute('href')),
                                'text' => $this->text($node->textContent),
                            ];
                        }
                    }
                }
            }

            // Inline CSS
            $inlineCss = [];
            foreach ($domDocument->getElementsByTagName('*') as $node) {
                if ($node->nodeName != 'svg' && !empty($node->getAttribute('style'))) {
                    $inlineCss[] = $node->getAttribute('style');
                }
            }

            // Title
            $data['results']['title'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => $title
            ];

            // If there's no title
            if (!$title) {
                $summaryData->title += 1;
                $summaryData->criticals += 1;
                $data['results']['title']['passed'] = false;
                $data['results']['title']['errors']['missing'] = null;
            }
           
            // If the title length is incorrect
            if (mb_strlen($title) < config('info.global.reportLimitMinTitle') || mb_strlen($title) > config('info.global.reportLimitMaxTitle')) {
                $summaryData->title += 1;
                $summaryData->criticals += 1;
                $data['results']['title']['passed'] = false;
                $data['results']['title']['errors']['length'] = ['min' => config('info.global.reportLimitMinTitle'), 'max' => config('info.global.reportLimitMaxTitle')];
            }

            if ($titleTagsCount > 1) {
                $summaryData->title += 1;
                $summaryData->criticals += 1;
                $data['results']['title']['passed'] = false;
                $data['results']['title']['errors']['too_many'] = null;
            }

            // Meta description
            $data['results']['meta_description'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => $metaDescription
            ];

            // If there's no meta description
            if (!$metaDescription) {
                $summaryData->criticals += 1;
                $summaryData->meta_description += 1;
                $data['results']['meta_description']['passed'] = false;
                $data['results']['meta_description']['errors']['missing'] = null;
            }

            // Headings
            $data['results']['headings'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => $headings
            ];

            // If there's no h1 tag
            if (!isset($headings['h1'])) {
                $summaryData->headings += 1;
                $summaryData->criticals += 1;
                $data['results']['headings']['passed'] = false;
                $data['results']['headings']['errors']['missing'] = null;
            }

            // If there's more than one h1 tag on the page
            if (isset($headings['h1']) && count($headings['h1']) > 1) {
                $summaryData->headings += 1;
                $summaryData->criticals += 1;
                $data['results']['headings']['passed'] = false;
                $data['results']['headings']['errors']['too_many'] = null;
            }

            // If the h1 tag is the same with the title tag
            if (isset($headings['h1'][0]) && $headings['h1'][0] == $title) {
                $data['results']['headings']['passed'] = false;
                $data['results']['headings']['errors']['duplicate'] = null;
            }

            // Content keywords
            $data['results']['content_keywords'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => array_intersect($titleKeywords, $bodyKeywords)
            ];

            // If the content keywords are not found in the title
            if (!array_intersect($titleKeywords, $bodyKeywords)) {
                $summaryData->content_keywords += 1;
                $summaryData->criticals += 1;
                $data['results']['content_keywords']['passed'] = false;
                $data['results']['content_keywords']['errors']['missing'] = $titleKeywords;
            }

            // Image keywords
            $data['results']['image_keywords'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => null
            ];

            // If there are images with no alt attribute set
            if (count($imageAlts) > 0) {
                $summaryData->image_keywords += 1;
                $summaryData->criticals += 1;
                $data['results']['image_keywords']['passed'] = false;
                $data['results']['image_keywords']['errors']['missing'] = $imageAlts;
            }

            // Image format
            $data['results']['image_format'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => preg_split('/\n|\r/', config('settings.report_limit_image_formats'), -1, PREG_SPLIT_NO_EMPTY)
            ];

            // If there are images that are not in WebP format
            if (count($imageFormats) > 0) {
                $summaryData->image_format += 1;
                $summaryData->warnings += 1;
                $data['results']['image_format']['passed'] = false;
                $data['results']['image_format']['errors']['bad_format'] = $imageFormats;
            }

            // In-page links
            $data['results']['in_page_links'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => $pageLinks
            ];

            // If there are too many links
            if (array_sum(array_map('count', $pageLinks)) > config('info.global.reportLimitMaxlinks')) {
                $summaryData->in_page_links += 1;
                $summaryData->warnings += 1;
                $data['results']['in_page_links']['passed'] = false;
                $data['results']['in_page_links']['errors']['too_many'] = ['max' => config('info.global.reportLimitMaxlinks')];
            }

            // Load time
            $data['results']['load_time'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => $reportRequestStats['total_time']
            ];

            // If the load time exceeds the limit
            if ($reportRequestStats['total_time'] > config('info.global.reportLimitLoadTime')) {
                $summaryData->load_time += 1;
                $summaryData->warnings += 1;
                $data['results']['load_time']['passed'] = false;
                $data['results']['load_time']['errors']['too_slow'] = ['max' => config('info.global.reportLimitLoadTime')];
            }

            // Page size
            $data['results']['page_size'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => $reportRequestStats['size_download']
            ];

            // If the page size exceeds the limit
            if ($reportRequestStats['size_download'] > config('info.global.reportLimitPageSize')) {
                $summaryData->page_size += 1;
                $summaryData->warnings += 1;
                $data['results']['page_size']['passed'] = false;
                $data['results']['page_size']['errors']['too_large'] = ['max' => config('info.global.reportLimitPageSize')];
            }

            // HTTP requests
            $data['results']['http_requests'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => $httpRequests
            ];

            // If there are too many HTTP requests
            if (array_sum(array_map('count', $httpRequests)) > config('info.global.reportLimitHttpRequests')) {
                $summaryData->http_requests += 1;
                $summaryData->warnings += 1;
                $data['results']['http_requests']['passed'] = false;
                $data['results']['http_requests']['errors']['too_many'] = ['max' => config('info.global.reportLimitHttpRequests')];
            }

            // Defer JavaScript
            $data['results']['defer_javascript'] = [
                'passed' => true,
                'importance' => 'low',
                'value' => null
            ];

            // If there are resources without the defer attribute
            if (count($deferJavaScript) > 0) {
                $summaryData->defer_javascript += 1;
                $summaryData->notices += 1;
                $data['results']['defer_javascript']['passed'] = false;
                $data['results']['defer_javascript']['errors']['missing'] = $deferJavaScript;
            }

            // DOM size
            $data['results']['dom_size'] = [
                'passed' => true,
                'importance' => 'low',
                'value' => $domNodesCount
            ];

            // DOM size
            if ($domNodesCount > config('info.global.reportLimitMaxDomNodes')) {
                $summaryData->dom_size += 1;
                $summaryData->notices += 1;
                $data['results']['dom_size']['passed'] = false;
                $data['results']['dom_size']['errors']['too_many'] = ['max' => config('info.global.reportLimitMaxDomNodes')];
            }

            // Text compression
            $data['results']['text_compression'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => $reportRequestStats['size_download'],
            ];

            // If the page is not text_compression compressed
            if (!in_array('gzip', $reportRequest->getHeader('x-encoded-content-encoding'))) {
                $summaryData->text_compression += 1;
                $summaryData->criticals += 1;
                $data['results']['text_compression']['passed'] = false;
                $data['results']['text_compression']['errors']['missing'] = null;
            }

            // Structured data
            $data['results']['structured_data'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => $structuredData
            ];

            // If there's no structured data
            if (empty($structuredData)) {
                $summaryData->structured_data += 1;
                $summaryData->warnings += 1;
                $data['results']['structured_data']['passed'] = false;
                $data['results']['structured_data']['errors']['missing'] = null;
            }

            // Meta viewport
            $data['results']['meta_viewport'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => $metaViewport
            ];

            // If the page does not have a meta viewport set
            if (!$metaViewport) {
                $summaryData->meta_viewport += 1;
                $summaryData->warnings += 1;
                $data['results']['meta_viewport']['passed'] = false;
                $data['results']['meta_viewport']['errors']['missing'] = null;
            }

            // HTTPS encryption
            $data['results']['https_encryption'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => $reportRequestStats['url']
            ];

            // If the page is not served over HTTPS
            if (strtolower($httpScheme) != 'https') {
                $summaryData->https_encryption += 1;
                $summaryData->criticals += 1;
                $data['results']['https_encryption']['passed'] = false;
                $data['results']['https_encryption']['errors']['missing'] = 'https';
            }

            // SEO friendly URL
            $data['results']['seo_friendly_url'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => $reportRequestStats['url']
            ];

            // If the URL contains characters that are not considered friendly (?=_%, )
            if (preg_match('/[\?\=\_\%\,\ ]/ui', $reportRequestStats['url'])) {
                $summaryData->seo_friendly_url += 1;
                $summaryData->criticals += 1;
                $data['results']['seo_friendly_url']['passed'] = false;
                $data['results']['seo_friendly_url']['errors']['bad_format'] = null;
            }

            // If the title keywords are not found in URL keywords
            
            if (empty(array_filter($titleKeywords, function ($keyword) use($report) { if (strpos(mb_strtolower($report->url),mb_strtolower($keyword)) !== false) { return true; } return false; }))) {
                $summaryData->seo_friendly_url += 1;
                $summaryData->criticals += 1;
                $data['results']['seo_friendly_url']['passed'] = false;
                $data['results']['seo_friendly_url']['errors']['missing'] = null;
            }

            // Language
            $data['results']['language'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => $language
            ];

            // If the page doesn't have a language set
            if (!$language) {
                $summaryData->language += 1;
                $summaryData->warnings += 1;
                $data['results']['language']['passed'] = false;
                $data['results']['language']['errors']['missing'] = null;
            }

            // Favicon
            $data['results']['favicon'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => $favicon
            ];

            // If the page doesn't have a favicon
            if (!$favicon) {
                $summaryData->favicon += 1;
                $summaryData->warnings += 1;
                $data['results']['favicon']['passed'] = false;
                $data['results']['favicon']['errors']['missing'] = null;
            }

            // Google Safe Browsing

            if (config('info.software.gsb') && config('info.software.gsb_key')) {

                $data['results']['gsb'] = [
                    'passed' => true,
                    'importance' => 'high',
                    'value' => $title
                ];

                // If threats were found
                if (!$gsb) {
                    $summaryData->gsb += 1;
                    $summaryData->criticals += 1;
                    $data['results']['gsb']['passed'] = false;
                    $data['results']['gsb']['errors']['failed'] = null;
                }
            }
            
            // Content length
            $data['results']['content_length'] = [
                'passed' => true,
                'importance' => 'low',
                'value' => count($bodyKeywords)
            ];

            // If there are not enough words on the page
            if (count($bodyKeywords) < config('info.global.reportLimitMinWords')) {
                $summaryData->content_length += 1;
                $summaryData->notices += 1;
                $data['results']['content_length']['passed'] = false;
                $data['results']['content_length']['errors']['too_few'] = ['min' => config('info.global.reportLimitMinWords')];
            }

            // Text to HTML ratio
            $data['results']['text_html_ratio'] = [
                'passed' => true,
                'importance' => 'low',
                'value' => $textRatio
            ];

            // If the text ratio is less than the minimum ratio
            if ($textRatio < config('info.global.reportLimitMinTextRatio')) {
                $summaryData->text_html_ratio += 1;
                $summaryData->notices += 1;
                $data['results']['text_html_ratio']['passed'] = false;
                $data['results']['text_html_ratio']['errors']['too_small'] = ['min' => config('info.global.reportLimitMinTextRatio')];
            }

            // Charset
            $data['results']['charset'] = [
                'passed' => true,
                'importance' => 'medium',
                'value' => $charset
            ];

            // If the page doesn't have a charset set
            if (!$charset) {
                $summaryData->content_length += 1;
                $summaryData->notices += 1;
                $data['results']['charset']['passed'] = false;
                $data['results']['charset']['errors']['missing'] = null;
            }

            // Deprecated HTML tags
            $data['results']['deprecated_html_tags'] = [
                'passed' => true,
                'importance' => 'low',
                'value' => null
            ];

            // If the page has deprecated HTML tags
            if (count($deprecatedHtmlTags) > 1) {
                $summaryData->deprecated_html_tags += 1;
                $summaryData->notices += 1;
                $data['results']['deprecated_html_tags']['passed'] = false;
                $data['results']['deprecated_html_tags']['errors']['bad_tags'] = $deprecatedHtmlTags;
            }

            // 404 page
            $data['results']['404_page'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => $notFoundPage
            ];

            // If the website does not have a 404 page
            if (!$notFoundPage) {
                //$summaryData->404_page += 1;
                //$summaryData->criticals += 1;
                $data['results']['404_page']['passed'] = false;
                $data['results']['404_page']['errors']['missing'] = null;
            }

            // Noindex
            $data['results']['noindex'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => $noIndex
            ];

            // If the website has noindex
            if ($noIndex) {
                $data['results']['noindex']['passed'] = false;
                $data['results']['noindex']['errors']['missing'] = null;
            }else{
                $summaryData->noindex += 1;
                $summaryData->criticals += 1;
            }

            // Robots
            $data['results']['robots'] = [
                'passed' => true,
                'importance' => 'high',
                'value' => null
            ];

            // If the page is blocked by robots.txt rule
            if (!$robots) {
                $summaryData->robots += 1;
                $summaryData->criticals += 1;
                $data['results']['robots']['passed'] = false;
                $data['results']['robots']['errors']['failed'] = $robotsRulesFailed;
            }

            // Sitemap
            $data['results']['sitemap'] = [
                'passed' => true,
                'importance' => 'low',
                'value' => $sitemaps
            ];

            // If the website does not have sitemaps
            if (empty($sitemaps)) {
                $summaryData->sitemap += 1;
                $summaryData->notices += 1;
                $data['results']['sitemap']['passed'] = false;
                $data['results']['sitemap']['errors']['failed'] = null;
            }

            // Plaintext email
            $data['results']['plaintext_email'] = [
                'passed' => true,
                'importance' => 'low',
                'value' => null
            ];

            // If there are plaintext emails
            if (isset($plaintextEmails[0]) && !empty($plaintextEmails[0])) {
                $summaryData->plaintext_email += 1;
                $summaryData->notices += 1;
                $data['results']['plaintext_email']['passed'] = false;
                $data['results']['plaintext_email']['errors']['failed'] = $plaintextEmails[0];
            }

            // Structured data
            $data['results']['social'] = [
                'passed' => true,
                'importance' => 'low',
                'value' => $social
            ];

            // If there's no social links
            if (empty($social)) {
                $summaryData->social += 1;
                $summaryData->notices += 1;
                $data['results']['social']['passed'] = false;
                $data['results']['social']['errors']['missing'] = null;
            }

            // Inline CSS
            $data['results']['inline_css'] = [
                'passed' => true,
                'importance' => 'low',
                'value' => null
            ];

            // If the page has inline CSS
            if (count($inlineCss) > 1) {
                $summaryData->inline_css += 1;
                $summaryData->notices += 1;
                $data['results']['inline_css']['passed'] = false;
                $data['results']['inline_css']['errors']['failed'] = $inlineCss;
            }

            $totalPoints = 0;
            $resultPoints = 0;
            foreach ($data['results'] as $key => $value) {
                $totalPoints = $totalPoints + config('settings.report_score_' . $value['importance']);

                if ($value['passed']) {
                    $resultPoints += config('settings.report_score_' . $value['importance']);
                }
            }

            // If the request is to store the model
            if (!$report->url) {
                $report->url = $this->url;
                // $report->user_id = $request->user()->id;
            }

            $report->results = mb_convert_encoding($data['results'], 'UTF-8', 'UTF-8');
            $report->project = $report->fullUrl;
           
            $report->result = (($report->score / $report->totalScore) * 100);
            $report->generated_at = Carbon::now();
            $summaryData->total_tests += count($data['results']);
            $summaryData->updated_at = date('Y-m-d H:i:s');
        }

        
        $report->save();
        SiteAuditSummary::updateSummaryScore($report->audit_id,$report->audit_status);
        return $report;
    }

    /**
     * Returns whether the URL is internal or not.
     *
     * @param $url
     * @return bool
     */
    private function isInternalUrl($url)
    {
        if (mb_strpos($url, parse_url($this->url, PHP_URL_SCHEME).'://'.parse_url($this->url, PHP_URL_HOST)) === 0) {
            return true;
        }

        return false;
    }

    /**
     * Parse and format the URL.
     *
     * @param $url
     * @return array|false|string|string[]
     */
    private function url($url)
    {
        $url = str_replace(['\\?', '\\&', '\\#', '\\~', '\\;'], ['?', '&', '#', '~', ';'], $url);

        if (mb_strpos($url, '#') !== false) {
            $url = mb_substr($url, 0, mb_strpos($url, '#'));
        }

        if (mb_strpos($url, 'http://') === 0) {
            return $url;
        }

        if (mb_strpos($url, 'https://') === 0) {
            return $url;
        }

        if (mb_strpos($url, '/') === 0) {
            return rtrim(parse_url($this->url, PHP_URL_SCHEME).'://'.parse_url($this->url, PHP_URL_HOST), '/').'/'.ltrim($url, '/');
        }

        if (mb_strpos($url, 'data:image') === 0) {
            return $url;
        }

        if (mb_strpos($url, 'tel') === 0) {
            return $url;
        }

        if (mb_strpos($url, 'mailto') === 0) {
            return $url;
        }

        return rtrim(parse_url($this->url, PHP_URL_SCHEME).'://'.parse_url($this->url, PHP_URL_HOST), '/').'/'.ltrim($url, '/');
    }

    /**
     * Parse and format a text string.
     *
     * @param $string
     * @return string
     */
    private function text($string)
    {
        return trim(preg_replace('/(?:\s{2,}+|[^\S ])/', ' ', $string));
    }

    /**
     * Format the robots rule into regexp rule.
     *
     * @param $value
     * @return string
     */
    private function formatRobotsRule($value)
    {
        $replacementsBeforeQuote = ['*' => '_ASTERISK_WILDCARD_', '$' => '_DOLLAR_WILDCARD_'];

        $replacementsAfterQuote = ['_ASTERISK_WILDCARD_' => '.*', '_DOLLAR_WILDCARD_' => '$'];

        return '/^' . str_replace(array_keys($replacementsAfterQuote), array_values($replacementsAfterQuote), preg_quote(str_replace(array_keys($replacementsBeforeQuote), array_values($replacementsBeforeQuote), $value), '/')) . '/';
    }
}