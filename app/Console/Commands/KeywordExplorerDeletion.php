<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\KwSearch;
use App\KwSearchIdea;
use App\KwHistory;

class KeywordExplorerDeletion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'KeywordExplorer:Deletion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove data after 30 day';

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
     $data = KwSearch::
     whereDate('updated_at', '<=', date('Y-m-d',strtotime('-30 day')))
     ->pluck('id');

     if(isset($data) && count($data) > 0){
        KwSearchIdea::with('kwListData')->whereIn('kw_search_id',$data)->delete();
        KwHistory::whereIn('kw_search_id',$data)->delete();
        KwSearch::whereIn('id',$data)->delete();
    } 
}
}
