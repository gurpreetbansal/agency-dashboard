<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KwHistory extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'kw_histories';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['kw_search_idea_id','kw_search_id','campaign_id','user_id','search_term','category','location_id','language_id','competition','competition_index','favicon','status'];	

    public static function scrape_favicon_bkp($url){
        $host_url = KwHistory::filter_domain_url($url);
        $file_headers = @get_headers($url);
        $found = FALSE;
        if($file_headers !== false){
            if($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                $dom = new \DOMDocument();
                $dom->strictErrorChecking = FALSE;
                @$dom->loadHTMLfile($url);  
                if (!$dom) {
                    $error[]='Error parsing the DOM of the file';
                } else {
                    $domxml = simplexml_import_dom($dom);
                    if ($domxml->xpath('//link[@rel="shortcut icon"]')) {
                        $path = $domxml->xpath('//link[@rel="shortcut icon"]');
                        $faviconURL = $path[0]['href'];
                        $found == TRUE;
                        return $faviconURL;
                    } else if ($domxml->xpath('//link[@rel="icon"]')) {
                        $path = $domxml->xpath('//link[@rel="icon"]');
                        $faviconURL = $path[0]['href'];
                        $found == TRUE;
                        return $faviconURL;
                    } else {
                        $error[]="The URL does not contain a favicon <link> tag.";
                    }
                }

                if ($found == FALSE) {
                    $parse = parse_url($url);
                    $favicon_headers = @get_headers("http://".$parse['host']."/favicon.ico");
                    if($favicon_headers[0] != 'HTTP/1.1 404 Not Found') {
                        $faviconURL = "/favicon.ico";
                        $found == TRUE;
                        return $faviconURL;
                    }
                    $favicon_headers = @get_headers("http://".$parse['host']."/favicon.png");
                    if($favicon_headers[0] != 'HTTP/1.1 404 Not Found') {
                        $faviconURL = "/favicon.png";
                        $found == TRUE;
                        return $faviconURL;
                    }
                    if ($found == FALSE) {
                        $error[]= "Files favicon.ico and .png do not exist on the server's root.";
                    }
                }
            } else {
                $error[]="URL does not exist";
            }
        }

        if ($found == FALSE && isset($error) ) {
            return $error;
        }
    }

    public static function scrape_favicon($sUrl){
        $sApiUrl = 'https://www.google.com/s2/favicons?domain='.$sUrl;
        return $sApiUrl;
    }

    public static function xml2array ( $xmlObject, $out = array ()){
        foreach ( (array) $xmlObject as $index => $node )
            $out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;
        return $out;
    }


  public static function filter_domain_url($url){
    $host = strtolower(trim($url));
    $host = ltrim(str_replace("http://","",str_replace("https://","",$host)),"www.");
    $count = substr_count($host, '.');
    if($count === 2){
        if(strlen(explode('.', $host)[1]) > 3) $host = explode('.', $host, 2)[1];
    } else if($count > 2){
        $host = getDomainOnly(explode('.', $host, 2)[1]);
    }
    $host = explode('/',$host);
    return $host[0];
}

}