<?php
namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class SiteAudit extends Model {

	   // use SoftDeletes;

     protected $report_score_label = 'report_score_';
     protected $report_score_high = 10;
     protected $report_score_medium = 5;
     protected $report_score_low = 0;

     

    protected $dates = ['generated_at', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'results' => 'array'
    ];

    public $categories = [
        'seo' => ['title', 'meta_description', 'headings', 'content_keywords', 'image_keywords', 'seo_friendly_url', '404_page', 'robots', 'noindex', 'in_page_links', 'language', 'favicon'],
        'performance' => ['text_compression', 'load_time', 'page_size', 'http_requests', 'image_format', 'defer_javascript', 'dom_size'],
        'security' => ['https_encryption', 'gsb', 'plaintext_email'],
        'miscellaneous' => ['structured_data', 'meta_viewport', 'charset', 'sitemap', 'social', 'content_length', 'text_html_ratio', 'inline_css', 'deprecated_html_tags']
    ];
	 /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'audit_details';

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
    protected $fillable = [
      'user_id','audit_id','url','project','results','result','generated_at'
  	];

    public function summary(){
        return $this->hasOne('App\SiteAuditSummary', 'id', 'audit_id');
    }

    public function filterError($errArr,$type){

            $newArr = array_filter($errArr, function ($varOuter,$keyOuter) {
                 
                if($varOuter['importance'] === 'high' &&$varOuter['passed'] === false){
                   return true;
                }
            },ARRAY_FILTER_USE_BOTH);
        
        return $newArr;

    }

  	public static function getSslStatus($url){
        
        try{
            $orignal_parse = parse_url($url, PHP_URL_HOST);
            $get = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
            $read = stream_socket_client("ssl://" . $orignal_parse . ":443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $get);
            $cert = stream_context_get_params($read);
            return $result = (!is_null($cert)) ? true : false;

        }catch (\Exception  $exception) {
            return false;
        }
    }

    public static function getIp($url)
    {
    	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_exec($ch);
        $ip = curl_getinfo($ch);
        curl_close($ch);
        // dd($ip);
        return $ip;
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeSearchUrl(Builder $query, $value)
    {   
        $url = $this->cleanUrl($value);

        return $query->where('url', 'like', '%' . $url . '%');
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeSearchProject(Builder $query, $value)
    {
        return $query->where('project', 'like', '%' . $value . '%');
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeOfUser(Builder $query, $value)
    {
        return $query->where('user_id', '=', $value);
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeOfProject(Builder $query, $value)
    {
        return $query->where('project', '=', $value);
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeOfResult(Builder $query, $value)
    {
        if ($value == 'good') {
            return $query->where('result', '>', 79);
        } elseif ($value == 'decent') {
            return $query->where([['result', '>=', 49], ['result', '<=', 79]]);
        }

        return $query->where('result', '<', 49);
    }

    /**
     * Get the user that owns the Link.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }

    /**
     * Get the total score possible.
     */
    public function getTotalScoreAttribute()
    {   
       
        $points = 0;
        foreach ($this->results as $key => $value) {
            $reportlabel = $this->report_score_label . $value['importance'];
            $points += $this->$reportlabel;
        }

        return $points;
    }

    /**
     * Get the current score.
     */
    public function getScoreAttribute()
    {   

        $points = 0;
        foreach ($this->results as $key => $value) {
            if ($value['passed']) {
                $reportlabel = $this->report_score_label . $value['importance'];
                $points += $this->$reportlabel;
            }
        }

        return $points;
    }

    /**
     * Get the total high issues count.
     */
    public function getHighIssuesCountAttribute()
    {
        $count = 0;
        foreach ($this->results as $key => $value) {
            if (!$value['passed'] && $value['importance'] == 'high') {
                $count += 1;
            }
        }

        return $count;
    }

    /**
     * Get the total medium issues count.
     */
    public function getMediumIssuesCountAttribute()
    {
        $count = 0;
        foreach ($this->results as $key => $value) {
            if (!$value['passed'] && $value['importance'] == 'medium') {
                $count += 1;
            }
        }

        return $count;
    }

    /**
     * Get the total low issues count.
     */
    public function getLowIssuesCountAttribute()
    {
        $count = 0;
        foreach ($this->results as $key => $value) {
            if (!$value['passed'] && $value['importance'] == 'low') {
                $count += 1;
            }
        }

        return $count;
    }

    /**
     * Get the total non-issues count.
     */
    public function getNonIssuesCountAttribute()
    {
        $count = 0;
        foreach ($this->results as $key => $value) {
            if ($value['passed']) {
                $count += 1;
            }
        }

        return $count;
    }

    /**
     * Get the total Tests count.
     */
    public function getTotalTestsCountAttribute()
    {
        return count($this->results);
    }

    /**
     * Get the high issues SEO count.
     */
    public function getHighIssuesSeoCountAttribute()
    {
        $count = 0;
        foreach ($this->results as $key => $value) {
            // If the result key exists under a category
            if (in_array($key, $this->categories['seo'])) {
                if (!$value['passed'] && $value['importance'] == 'high') {
                    $count += 1;
                }
            }
        }

        return $count;
    }

    /**
     * Get the high issues Performance count.
     */
    public function getHighIssuesPerformanceCountAttribute()
    {
        $count = 0;
        foreach ($this->results as $key => $value) {
            // If the result key exists under a category
            if (in_array($key, $this->categories['performance'])) {
                if (!$value['passed'] && $value['importance'] == 'high') {
                    $count += 1;
                }
            }
        }

        return $count;
    }

    /**
     * Get the high issues Security count.
     */
    public function getHighIssuesSecurityCountAttribute()
    {
        $count = 0;
        foreach ($this->results as $key => $value) {
            // If the result key exists under a category
            if (in_array($key, $this->categories['security'])) {
                if (!$value['passed'] && $value['importance'] == 'high') {
                    $count += 1;
                }
            }
        }

        return $count;
    }

    /**
     * Get the high issues Miscellaneous count.
     */
    public function getHighIssuesMiscellaneousCountAttribute()
    {
        $count = 0;
        foreach ($this->results as $key => $value) {
            // If the result key exists under a category
            if (in_array($key, $this->categories['miscellaneous'])) {
                if (!$value['passed'] && $value['importance'] == 'high') {
                    $count += 1;
                }
            }
        }

        return $count;
    }

    /**
     * Get the medium issues SEO count.
     */
    public function getMediumIssuesSeoCountAttribute()
    {
        $count = 0;
        foreach ($this->results as $key => $value) {
            // If the result key exists under a category
            if (in_array($key, $this->categories['seo'])) {
                if (!$value['passed'] && $value['importance'] == 'medium') {
                    $count += 1;
                }
            }
        }

        return $count;
    }

    /**
     * Get the medium issues Performance count.
     */
    public function getMediumIssuesPerformanceCountAttribute()
    {
        $count = 0;
        foreach ($this->results as $key => $value) {
            // If the result key exists under a category
            if (in_array($key, $this->categories['performance'])) {
                if (!$value['passed'] && $value['importance'] == 'medium') {
                    $count += 1;
                }
            }
        }

        return $count;
    }

    /**
     * Get the medium issues Security count.
     */
    public function getMediumIssuesSecurityCountAttribute()
    {
        $count = 0;
        foreach ($this->results as $key => $value) {
            // If the result key exists under a category
            if (in_array($key, $this->categories['security'])) {
                if (!$value['passed'] && $value['importance'] == 'medium') {
                    $count += 1;
                }
            }
        }

        return $count;
    }

    /**
     * Get the medium issues Miscellaneous count.
     */
    public function getMediumIssuesMiscellaneousCountAttribute()
    {
        $count = 0;
        foreach ($this->results as $key => $value) {
            // If the result key exists under a category
            if (in_array($key, $this->categories['miscellaneous'])) {
                if (!$value['passed'] && $value['importance'] == 'medium') {
                    $count += 1;
                }
            }
        }

        return $count;
    }

    /**
     * Get the low issues SEO count.
     */
    public function getLowIssuesSeoCountAttribute()
    {
        $count = 0;
        foreach ($this->results as $key => $value) {
            // If the result key exists under a category
            if (in_array($key, $this->categories['seo'])) {
                if (!$value['passed'] && $value['importance'] == 'low') {
                    $count += 1;
                }
            }
        }

        return $count;
    }

    /**
     * Get the low issues Performance count.
     */
    public function getLowIssuesPerformanceCountAttribute()
    {
        $count = 0;
        foreach ($this->results as $key => $value) {
            // If the result key exists under a category
            if (in_array($key, $this->categories['performance'])) {
                if (!$value['passed'] && $value['importance'] == 'low') {
                    $count += 1;
                }
            }
        }

        return $count;
    }

    /**
     * Get the low issues Security count.
     */
    public function getLowIssuesSecurityCountAttribute()
    {
        $count = 0;
        foreach ($this->results as $key => $value) {
            // If the result key exists under a category
            if (in_array($key, $this->categories['security'])) {
                if (!$value['passed'] && $value['importance'] == 'low') {
                    $count += 1;
                }
            }
        }

        return $count;
    }

    /**
     * Get the low issues Miscellaneous count.
     */
    public function getLowIssuesMiscellaneousCountAttribute()
    {
        $count = 0;
        foreach ($this->results as $key => $value) {
            // If the result key exists under a category
            if (in_array($key, $this->categories['miscellaneous'])) {
                if (!$value['passed'] && $value['importance'] == 'low') {
                    $count += 1;
                }
            }
        }

        return $count;
    }

    /**
     * Get the categories
     */
    public function getCategoriesAttribute()
    {
        return $this->categories;
    }

    /**
     * Get the URL in full.
     */
    public function getFullUrlAttribute()
    {
        return $this['results']['seo_friendly_url']['value'];
    }

    /**
     * Set the url attribute.
     */
    public function setUrlAttribute($value)
    {
        $this->attributes['url'] = $this->cleanUrl($value);
    }

    /**
     * Set the project attribute
     */
    public function setProjectAttribute($value)
    {
        $this->attributes['project'] = parse_url(str_replace(['https://www.', 'http://www.'], ['https://', 'http://'], $value), PHP_URL_HOST);
    }

    /**
     * Get the host attribute
     */
    public function getHostAttribute()
    {
        return parse_url('http://' . $this->url, PHP_URL_HOST);
    }

    /**
     * Encrypt the report's password.
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }

    /**
     * Decrypt the report's password.
     *
     * @param $value
     * @return string
     */
    public function getPasswordAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function cleanUrl($value)
    {
        return str_replace(['https://www.', 'http://www.'], ['https://', 'http://'], $value);
    }

}