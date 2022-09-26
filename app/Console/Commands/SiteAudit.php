<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SemrushUserAccount;
use App\SiteAuditSummary;
use Illuminate\Http\Request;

/*use App\Http\Controllers\Vendor\SiteAuditReportsController;*/

use App\Traits\SiteAuditTrait;

class SiteAudit extends Command
{ 



    use SiteAuditTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SiteAudit:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Site Audit update pages';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      
      $domainDetails = SemrushUserAccount::
      whereHas('UserInfo', function($q){
        $q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
        ->where('subscription_status', 1);
      })
      ->where('status','0')
      ->where(function ($q) {
        $q->doesntHave('auditSummary')
        ->orWhereHas('auditSummary', function($q){
        $q->whereDate('result',0);
        // $q->whereDate('updated_at', '<' ,date('Y-m-d',strtotime(' -1 month')));
        });
      })
      // ->whereIn('id',[1922])
      ->limit(20)
      ->orderBy('id','ASC')
      ->get();

      file_put_contents(dirname(__FILE__).'/audit/audit.txt',print_r($domainDetails,true));

      $request = Request::capture();
      foreach ($domainDetails as $key => $value) {
          
          $request->request->add(['user_id' => $value->user_id]);
          $request->request->add(['campaign_id' => $value->id]);

          if($value->auditSummary <> null){

            $request->request->add(['url' => $value->auditSummary->url]);
       
            // file_put_contents(dirname(__FILE__).'/audit/request.txt',print_r($request->all(),true),FILE_APPEND);
            // file_put_contents(dirname(__FILE__).'/audit/auditDetail.txt',print_r(['campaign_id',$value->id,'audit_id', @$value->auditSummary->id],true),FILE_APPEND);
            $urlList = $this->updateAuditRefresh($request,$value->UserInfo->company_name,$value->auditSummary->id);
          }else{
            $request->request->add(['url' => $value->host_url]);
            // file_put_contents(dirname(__FILE__).'/audit/request.txt',print_r($request->all(),true),FILE_APPEND);
            $this->siteAuditRun($request);
          }
          file_put_contents(dirname(__FILE__).'/audit/request.txt',print_r($request->all(),true),FILE_APPEND);
      }
      
        
    }



  }
