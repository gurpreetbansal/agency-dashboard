<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\ClientAuth;
use Mail;
use App\ApiBalance;

class DFSApiBalance extends Command
{

    use ClientAuth;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DFS:Balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Balance for DFS Api';

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
        $array = array();
        $balance = 0;
        try {
            $client = $this->DFSAuth();
            $result = $client->get('/v3/appendix/user_data');

            if(isset($result['tasks'][0]['result'][0]) && !empty($result['tasks'][0]['result'][0])){
                $balance = $result['tasks'][0]['result'][0]['money']['balance'];
                $status_code = $result['status_code'];
                $array = array('status_code'=>$status_code,'balance'=>$balance);

                if(($status_code == '20000') && ($balance <= 50)){
                    $data = array('balance'=>$balance);
                    Mail::send(['html' => 'mails/dfs_balance'], $data, function($message) {
                         $message->to('shruti.dhiman@imarkinfotech.com', 'Shruti Dhiman')->subject('Balance Alert - Data For Seo');
                         $message->from(\config('app.mail'), 'Agency Dashboard');
                    });

                     Mail::send(['html' => 'mails/dfs_balance_new'], $data, function($message) {
                         $message->to('ishan@imarkinfotech.com', 'Ishan Gupta')->subject('Balance Alert - Data For Seo');
                         $message->from(\config('app.mail'), 'Agency Dashboard');
                    });

                  $email_sent_flag =1;
                  $email_sent_on =now();
                }else{
                    $email_sent_flag =0;
                    $email_sent_on = NULL;
                }

                ApiBalance::where('name','DFS')->update([
                    'balance'=>$balance,
                    'email_sent'=>$email_sent_flag,
                    'email_sent_on' =>$email_sent_on,
                    'status_code'=>$result['tasks'][0]['status_code'],
                    'status_message'=>$result['tasks'][0]['status_message']
                ]);
            }else{
                ApiBalance::where('name','DFS')->update([
                    'status_code'=>$result['tasks'][0]['status_code'],
                    'status_message'=>$result['tasks'][0]['status_message']
                ]);
            }
        }catch(RestClientException $e){
            return json_decode($e->getMessage(), true);
        }
    }
}
