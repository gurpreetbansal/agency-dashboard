<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SemrushUserAccount;
use App\Moz;

class MozData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Moz:Store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store Moz data';

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
        $request_data = SemrushUserAccount::
        whereHas('UserInfo', function($q){
            $q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
            ->where('subscription_status', 1);
        })  
        ->select('id','user_id','domain_url','moz_date')
        ->where('status',0)
        ->where(function($qq){
            $qq->whereNull('moz_date')
            ->orWhere(function($query){
                $query
                ->whereMonth('moz_date','!=',date('m'))
                ->orWhereYear('moz_date','!=',date('Y'));
            });
        })   
        ->orderBy('id','desc')   
        ->limit(100)
        ->get();
        
        file_put_contents(dirname(__FILE__).'/logs/moz_log-out.txt',print_r($request_data,true));
        if(!empty($request_data) && isset($request_data)){
            file_put_contents(dirname(__FILE__).'/logs/moz_log.txt',print_r($request_data,true));
            foreach($request_data  as $semrush_data){
                $data = Moz::
                whereMonth('created_at','=',date('m'))
                ->whereYear('created_at','=',date('Y'))
                ->where('request_id',$semrush_data->id)
                ->orderBy('id','desc')
                ->first();


                if(empty($data) && $data == null){
                    $domain_url = rtrim($semrush_data->domain_url, '/');
                    $insertMozData = Moz::getMozData($domain_url);
                    if ($insertMozData) {
                        Moz::create([
                            'user_id' => $semrush_data->user_id,
                            'request_id' => $semrush_data->id,
                            'domain_authority' => $insertMozData->DomainAuthority,
                            'page_authority' => $insertMozData->PageAuthority,
                            'status' => 0
                        ]);
                        SemrushUserAccount::where('id',$semrush_data->id)->update(['moz_date'=>date('Y-m-d')]);
                    }
                    sleep(1);
                }   
            }       
        }   
    }
}
