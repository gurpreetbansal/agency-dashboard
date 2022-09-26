<?php
namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\SiteAudit;


class SiteAuditSummary extends Model {

	use SoftDeletes;
	protected $dates = ['generated_at', 'created_at', 'updated_at', 'deleted_at'];

	/**
     * The database table used by the model.
     *
     * @var string
     */
	protected $table = 'audit_summaries';

	/**
     * The database primary key value.
     *
     * @var string
     */
	protected $primaryKey = 'id';
	
  protected $report_limit_min_title = 1;
  protected $report_limit_max_title = 60;

  protected $requestuseragent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36';
  protected $request_connection_timeout = 5;
  protected $report_limit_deprecated_html_tags = 'acronym
  applet
  basefont
  big
  center
  dir
  font
  frame
  frameset
  isindex
  noframes
  s
  strike
  tt
  u';
  
  protected $report_limit_image_formats = 'AVIF
  WebP';

  protected $report_limit_max_links = 150;
  protected $report_limit_load_time = 2;
  protected $report_limit_page_size = 330000;
  protected $report_limit_http_requests = 50;
  protected $report_limit_max_dom_nodes = 1500;
  protected $report_limit_min_words = 500;
  protected $report_limit_min_text_ratio = 10;
	/**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
  protected $fillable = [
    'user_id', 'campaign_id', 'audit_id', 'url', 'project', 'score',  'criticals',  'warnings', 'notices',  'ip', 'is_ssl',  'is_www', 'site_map', 'title',  'meta_description', 'headings', 'content_keywords', 'image_keywords', 'seo_friendly_url', '404_page', 'robots', 'noindex',  'in_page_links',  'language', 'favicon',  'text_compression', 'load_time',  'page_size',  'http_requests',  'image_format', 'defer_javascript', 'dom_size', 'https_encryption', 'gsb',  'plaintext_email',  'structured_data',  'meta_viewport',  'charset',  'sitemap',  'social', 'content_length', 'text_html_ratio',  'inline_css', 'deprecated_html_tags','crowl_pages','share_key'
  ];

  protected $appends = array('crowled_pages');

  public function getCrowledPagesAttribute()
  {
     return $this->hasMany('App\SiteAudit', 'audit_id')->count();

  }

  public function pages(){

    return $this->hasMany('App\SiteAudit', 'audit_id', 'id');
        // return $this->hasOne('App\SiteAudit', 'id', 'audit_id');
  }

  public function reportLimitMinTitle(){
    return $this->report_limit_min_title;
  }

  public function reportLimitMaxTitle(){
    return $this->report_limit_max_title;
  }

  public function requestUserAgent(){
    return $this->requestuseragent;
  }

  public function requestConnectionTimeout(){
    return $this->request_connection_timeout;
  }
  public function reportLimitMaxlinks(){
    return $this->report_limit_max_links;
  }
  public function reportLimitLoadTime(){
    return $this->report_limit_load_time;
  }
  public function reportLimitPageSize(){
    return $this->report_limit_page_size;
  }
  public function reportLimitHttpRequests(){
    return $this->report_limit_http_requests;
  }
  public function reportLimitMaxDomNodes(){
    return $this->report_limit_max_dom_nodes;
  }
  public function reportLimitMinWords(){
    return $this->report_limit_min_words;
  }
  public function reportLimitMinTextRatio(){
    return $this->report_limit_min_text_ratio;
  }

  public static function updateSummaryScore($id,$status = 'process'){

    $siteScore = SiteAudit::where('audit_id',$id);

    $scoreSum = $siteScore->sum('result');
    $scoreCount = $siteScore->count();

    $totalScore = 0;
    if($scoreSum <> 0 && $scoreCount <> 0){
      $totalScore = $scoreSum/$scoreCount;
    }
    
    $updateSummary =  SiteAuditSummary::where('id',$id);
    if($status <> null){
      $updateSummary->update(['result'=>$totalScore,'audit_status'=>$status]);
    }else{
      $updateSummary->update(['result'=>$totalScore]);
    } 
    
  }

}