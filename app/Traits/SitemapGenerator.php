<?php

namespace App\Traits;
use Illuminate\Http\Request;
use App\Http\Requests\StoreReportRequest;
use App\SiteAuditSummary;
use App\SiteAudit;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\TransferStats;

trait SitemapGenerator {


	// Config file with crawler/sitemap options
	private $config;

	// Array containing all scanned pages
	private $scanned = [];

	// Array containing all scanned pages Matches
	private $matched = [];

	// Array containing all scanned pages Matches
	private $crawled = [];

	// The base of the given site url
	// EXAMPLE: https://student-laptop.nl
	private $site_url_base;

	// File where sitemap is written to.
	private $sitemap_file;


	private $site_url;

	// Constructor sets the given file for internal use
	public function __construct(Request $request)
	{	
		ini_set('max_execution_time', '-1');
        ini_set('memory_limit', '-1');
		// Setup class variables using the config

		$conf = $this->config($request->input('url'));
		$this->config = $conf;
		
		$this->scanned = [];
		$this->matched = [];
		$this->crawled = [];

		if($request->input('url')){

			$httpStatus = SiteAudit::getIp($request->input('url'));
			if($httpStatus['http_code'] == 301 || $httpStatus['http_code'] == 302){
				$this->site_url_base = rtrim($httpStatus['redirect_url'],'/');
			}else{
				$this->site_url_base = rtrim($httpStatus['url'],'/');
			}

			// dd($httpStatus);
			// echo $this->site_url_base = parse_url($this->config['SITE_URL'])['scheme'] . "://" . parse_url($this->config['SITE_URL'])['host'];
		}
		
	}

	public function config($site_url){

		return array(
		    // Site to crawl and create a sitemap for.
		    // <Syntax> https://www.your-domain-name.com/ or http://www.your-domain-name.com/
		    "SITE_URL" => $site_url,

		    // Boolean for crawling external links.
		    // <Example> *Domain = https://www.student-laptop.nl* , *Link = https://www.google.com* <When false google will not be crawled>
		    "ALLOW_EXTERNAL_LINKS" => false,

		    // Boolean for crawling element id links.
		    // <Example> <a href="#section"></a> will not be crawled when this option is set to false
		    "ALLOW_ELEMENT_LINKS" => false,

		    // If set the crawler will only index the anchor tags with the given id.
		    // If you wish to crawl all links set the value to ""
		    // <Example> <a id="internal-link" href="/info"></a> When CRAWL_ANCHORS_WITH_ID is set to "internal-link" this link will be crawled
		    // but <a id="external-link" href="https://www.google.com"></a> will not be crawled.
		    "CRAWL_ANCHORS_WITH_ID" => "",

		    // Array with absolute links or keywords for the pages to skip when crawling the given SITE_URL.
		    // <Example> https://student-laptop.nl/info/laptops or you can just input student-laptop.nl/info/ and it will not crawl anything in that directory
		    // Try to be as specific as you can so you dont skip 300 pages
		    "KEYWORDS_TO_SKIP" => array('mailto','skype','tel','email-protectio','cdn-cgi','void(0)','.jpg','.jpeg','.png','.pdf','.xlsx','.docx','javascript','#'),

		    // Location + filename where the sitemap will be saved.
		    "SAVE_LOC" => "sitemap.xml",

		    // Static priority value for sitemap
		    "PRIORITY" => 1,

		    // Static update frequency
		    "CHANGE_FREQUENCY" => "daily",

		    // Date changed (today's date)
		    "LAST_UPDATED" => date('Y-m-d'),
		);

	}


	public function GenerateSitemap(Request $request,$limit,$crawledList = [])
	{	
		
		// Call the recursive crawl function with the start url.
		$this->matched = $crawledList;
		$conf = $this->config($request->input('url'));
		$this->config = $conf;
		$list = $this->crawlPage($request,$limit);
		if($list == true){
			SiteAuditSummary::updateSummaryScore($request->audit_id,'completed');
		}
		
		return $list;
		
	}

	// Get the html content of a page and return it as a dom object
	private function BkgetHtml($url)
	{
		// Get html from the given page
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$html = curl_exec($curl);
		curl_close($curl);

		//Load the html and store it into a DOM object

		$dom = new \DOMDocument();
		@$dom->loadHTML($html);

		return $dom;
	}

	private function getHtml($url)
	{
		$client = new HttpClient();
		$reportResponse = null;
		try{       
            $reportRequestTransferStats = null;
            usleep(rand(750000, 1250000));

            $reportRequest = $client->request('GET', str_replace('https://', 'http://', $url), [
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
            $reportResponse = $reportRequest->getBody()->getContents();
			
	        libxml_use_internal_errors(true);
        } catch (\Exception $e) {
            // continue;
        }
        $domDocument = new \DOMDocument();
        $domDocument->loadHTML('<?xml encoding="utf-8" ?>' . $reportResponse ?? null);
		return $domDocument;
	}


	private function crawlPage(Request $request,$limit = 50)
	{	
		if($this->scanned == null){
			$this->scanned = [];
		}
		if($this->matched == null){
			$this->matched = [];
		}
		if($this->crawled == null){
			$this->crawled = [];
		}
		
		$page_url = $request->input('url');
		$url = filter_var($page_url, FILTER_SANITIZE_URL);

		// Check if the url is invalid or if the page is already scanned;
		if (in_array($url, $this->scanned) || !filter_var($page_url, FILTER_VALIDATE_URL)) {
			return;
		}

		// if (in_array(rtrim($url,'/'), $this->matched)){
		// 	return;
		// }
			
		array_push($this->crawled, rtrim($url,'/'));

		$html = $this->getHtml($url);
		$anchors = $html->getElementsByTagName('a');
		
		$urlArrList = [];

		// Loop through all anchor tags on the page
		foreach ($anchors as $a) {
			$next_url = $a->getAttribute('href');
			
			$urlCheck = parse_url($next_url, PHP_URL_HOST); 
			if($urlCheck == null){
				$next_url = $this->config['SITE_URL'].$next_url;
			}

			if (in_array($next_url, $urlArrList)){
				continue;
			}

			// Check if there is a anchor ID set in the config.
			/*if ($this->config['CRAWL_ANCHORS_WITH_ID'] != "") {
				// Check if the id is set and matches the config setting, else it will move on to the next anchor
				if ($a->getAttribute('id') != "" || $a->getAttribute('id') == $this->config['CRAWL_ANCHORS_WITH_ID']) {
					continue;
				}
			}*/

			// Split page url into base and extra parameters
			$base_page_url = explode("?", $page_url)[0];
			if (!$this->config['ALLOW_ELEMENT_LINKS']) {
				// Skip the url if it starts with a # or is equal to root.
				if (substr($next_url, 0, 1) == "#" || $next_url == "/") {
					continue;
				}
			}

			// Check if the given url is external, if yes it will skip the iteration
			// This code will only run if you set ALLOW_EXTERNAL_LINKS to false in the config.
			if (!$this->config['ALLOW_EXTERNAL_LINKS']) {
				// echo $next_url;
				$parsed_url = parse_url($next_url);
				$config_url = parse_url($this->config['SITE_URL']);
				if (isset($parsed_url['host']) && isset($config_url['host'])) {
					if ($parsed_url['host'] !== $config_url['host']) {
						continue;
					}
				}
			}

			// Check if the link is absolute or relative.
			if (substr($next_url, 0, 7) != "http://" && substr($next_url, 0, 8) != "https://") {
				$next_url = $this->convertRelativeToAbsolute($base_page_url, $next_url);
			}

			// Check if the next link contains any of the pages to skip. If true, the loop will move on to the next iteration.
			$found = false;
			foreach ($this->config['KEYWORDS_TO_SKIP'] as $skip) {
				if (strpos($next_url, $skip) || $next_url === $skip) {
					$found = true;
				}	
			}

			// Call the function again with the new URL
			if (!$found) {
				if (!in_array(rtrim($next_url,'/'), $this->matched)){

					$urlArrList[] =  rtrim($next_url,'/');
					array_push($this->matched, rtrim($next_url,'/'));
					$request['url'] = $next_url;
					$this->reportStore($request);
					
					if(count($this->matched) >= $limit){

						$this->scanned = [];
						$this->matched = [];
						$this->crawled = [];
						
						return [
							'status'=>true,
							'list' => $this->matched
						];
					}
				}
			}
		}

		$result=array_diff($this->matched,$this->crawled);
		if(count($result) > 0){
			$request->url = next($result);
			$this->crawlPage($request,$limit);
		}
		file_put_contents(dirname(__FILE__).'/logs/sitemap.txt',print_r($request->all(),true));
		return [
			'status'=>true,
			'list' => $this->matched
		];
	}
	
	// Recursive function that crawls a page's anchor tags and store them in the scanned array.
	private function crawlPageBK(Request $request,$limit = 50)
	{	
		if($this->scanned == null || $this->matched == null){
			$this->scanned = [];
			$this->matched = [];
			$this->crawled = [];
		}
		$page_url = $request->input('url');
		$url = filter_var($page_url, FILTER_SANITIZE_URL);

		// Check if the url is invalid or if the page is already scanned;
		if (in_array($url, $this->scanned) || !filter_var($page_url, FILTER_VALIDATE_URL)) {
			return;
		}

		if (in_array(rtrim($url,'/'), $this->matched)){
			return;
		}
			
		array_push($this->scanned, $url);
		array_push($this->matched, rtrim($url,'/'));
		
		$urlList = $this->reportStore($request);
		
		// Add the page url to the scanned array
		
		// Get the html content from the 
		$html = $this->getHtml($url);
		$anchors = $html->getElementsByTagName('a');
		
		// Loop through all anchor tags on the page
		foreach ($anchors as $a) {

			$next_url = $a->getAttribute('href');
			if (in_array(rtrim($next_url,'/'), $this->matched)){
				continue;
			}

			// Check if there is a anchor ID set in the config.
			if ($this->config['CRAWL_ANCHORS_WITH_ID'] != "") {
				// Check if the id is set and matches the config setting, else it will move on to the next anchor
				if ($a->getAttribute('id') != "" || $a->getAttribute('id') == $this->config['CRAWL_ANCHORS_WITH_ID']) {
					continue;
				}
			}

			// Split page url into base and extra parameters
			$base_page_url = explode("?", $page_url)[0];

			if (!$this->config['ALLOW_ELEMENT_LINKS']) {
				// Skip the url if it starts with a # or is equal to root.
				if (substr($next_url, 0, 1) == "#" || $next_url == "/") {
					continue;
				}
			}

			// Check if the given url is external, if yes it will skip the iteration
			// This code will only run if you set ALLOW_EXTERNAL_LINKS to false in the config.
			if (!$this->config['ALLOW_EXTERNAL_LINKS']) {
				$parsed_url = parse_url($next_url);
				if (isset($parsed_url['host'])) {
					if ($parsed_url['host'] != parse_url($this->config['SITE_URL'])['host']) {
						continue;
					}
				}
			}

			// Check if the link is absolute or relative.
			if (substr($next_url, 0, 7) != "http://" && substr($next_url, 0, 8) != "https://") {
				$next_url = $this->convertRelativeToAbsolute($base_page_url, $next_url);
			}

			// Check if the next link contains any of the pages to skip. If true, the loop will move on to the next iteration.
			$found = false;
			foreach ($this->config['KEYWORDS_TO_SKIP'] as $skip) {
				if (strpos($next_url, $skip) || $next_url === $skip) {
					$found = true;
				}
			}

			// Call the function again with the new URL
			if (!$found) {
				// $httpStatus = SiteAudit::getIp($next_url);
				// if($httpStatus['http_code'] == 200){
					$request->url = $next_url;
					$this->crawlPage($request);
				// }
			}

			if(count($this->scanned) >= $limit){
				
				return [
					'status'=>true,
				];
			}
		}
		// echo '<pre/>';
		// echo $next_url;
		// echo '<pre/>';
		// print_r($this->scanned);
		// dd(in_array(rtrim($next_url,'/'), $this->scanned));
		return [
			'status'=>true,
		];
	}

	// Convert a relative link to a absolute link
	// Example: Relative /articles
	//			Absolute https://student-laptop.nl/articles
	private function convertRelativeToAbsolute($page_base_url, $link)
	{
		$first_character = substr($link, 0, 1);
		
		if ($first_character == "?" || $first_character == "#") {
			return $page_base_url . $link;
		} else if ($first_character != "/") {
			return $this->site_url_base . "/" . $link;
		} else {
			return $this->site_url_base . $link;
		}
	}

	// Function to generate a Sitemap with the given pages array where the script has run through
	private function generateFile($pages)
	{
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
		<!-- ' . count($pages) . ' total pages-->
		<!-- PHP-sitemap-generator by https://github.com/tristangoossens -->';


		// Print the amount of pages
		 echo count($pages);

		foreach ($pages as $page) {
			$xml .= "<url><loc>" . $page . "</loc>
            <lastmod>" . $this->config['LAST_UPDATED'] . "</lastmod>
            <changefreq>" . $this->config['CHANGE_FREQUENCY'] . "</changefreq>
            <priority>" . $this->config['PRIORITY'] . "</priority></url>";
		}

		$xml .= "</urlset>";
		$xml = str_replace('&', '&amp;', $xml);

		// Format string to XML
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		$dom->loadXML($xml);
		$dom->formatOutput = TRUE;

		// Write XML to file and close it
		fwrite($this->sitemap_file, $dom->saveXML());
		fclose($this->sitemap_file);
	}

}