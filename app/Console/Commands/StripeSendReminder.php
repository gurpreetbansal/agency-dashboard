<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Stripe;
use App\Subscription;

class StripeSendReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Stripe:SendReminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder for the unpaid invoice';

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
        $subscription_data = Subscription::with('userDetail')->where('reminder_on',date('Y-m-d'))->where('stripe_status','!=','canceled')->get();
        if(isset($subscription_data) && !empty($subscription_data)){
            foreach($subscription_data as $key => $value){
                Subscription::where('id',$value->id)->update([
                    'reminder_on' => date('Y-m-d',strtotime('+2 day',strtotime($value->reminder_on))),
                ]);
                self::sendReminder($value);
            }
        }
    }

    private function sendReminder($value){
        $data = ['name'=> $value->userDetail->name,'email'=> $value->userDetail->email];
        \Mail::send(['html' => 'mails/vendor/reminder_emails'], $data, function($message) use($data) {
            $message->to($data['email'], $data['name'])->subject('Reminder Payment Due - Agency Dashboard!');
            $message->from(\config('app.mail'), 'Agency Dashboard');
        });
    }
}