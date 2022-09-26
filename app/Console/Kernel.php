<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        
        Commands\LiveKeywordTracking::class,
        Commands\googleSearchConsole::class,
        Commands\GoogleAnalytics::class,
        Commands\DFSApiBalance::class,
        Commands\MozData::class,
        Commands\BackLinks::class,
        Commands\GoogleAdwords::class,
        Commands\DFSExtraOrganicKeywords::class,
        Commands\GoogleMyBusiness::class,
        Commands\ScheduleReportNow::class,
        Commands\ScheduledReport::class,
        Commands\DailyKeywordAlerts::class,
        Commands\KeywordExplorerDeletion::class,
        Commands\StripeLinkExpiration::class,
        Commands\SendStripeInvoice::class,
        Commands\StripeSendReminder::class,
        Commands\StripeCheckInvoiceStatus::class,
        Commands\SiteAudit::class,
        Commands\GoogleAnalytics4::class,
        Commands\FacebookRefresh::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {        
        $schedule->command('Live:keyword')
        ->everyMinute(); 

        $schedule->command('Google:Analytics4')
        ->dailyAt('01:00');
        
        $schedule->command('Search:Console')
        ->dailyAt('2:00');

       // // ->dailyAt('07:10');
       //  ->twiceDaily(2,18);      

        $schedule->command('Google:Analytics')
        ->twiceDaily(3,17);
        
       //  // ->dailyAt('11:30');

        $schedule->command('DFS:Balance')
        ->hourly();

        $schedule->command('Moz:Store')
        ->hourly();
        // ->dailyAt('06:57'); 

        $schedule->command('BackLinks:serpstat')
        ->twiceDaily(5,22);      
        // ->everyMinute(); 

        $schedule->command('Google:Adwords')
        ->twiceDaily(6,20);
        // ->dailyAt('10:26');

        $schedule->command('DFS:ExtraOrganicKeywords')
        ->twiceDaily(4,21);

        $schedule->command('Google:MyBusiness')
         ->twiceDaily(2,19);
        // ->dailyAt('10:46');

        $schedule->command('ScheduleReport:Now')
        ->everyMinute();

        $schedule->command('ScheduledReport:SpecificDate')
        // ->dailyAt('08:32');
        ->hourly();

        $schedule->command('Keyword:Alert')
        ->everyMinute();

        $schedule->command('KeywordExplorer:Deletion')
        ->daily();

        $schedule->command('Stripe:LinkExpiration')
        ->daily();

        $schedule->command('Stripe:SendInvoice')
        // ->dailyAt('05:15');
        ->daily();
        
        $schedule->command('Stripe:SendReminder')
        ->daily();

        $schedule->command('Stripe:CheckInvoiceStatus')
        ->everyMinute();

        $schedule->command('SiteAudit:refresh')
        // ->dailyAt('05:59');
        ->hourly();

        $schedule->command('Facebook:Refresh')
          ->dailyAt('08:02');


    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
