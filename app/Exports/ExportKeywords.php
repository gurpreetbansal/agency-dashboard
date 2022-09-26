<?php

namespace App\Exports;

use App\KeywordSearch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;


class ExportKeywords implements FromCollection,WithHeadings
{

	private $ids,$request_id;

	public function __construct($ids,$request_id) 
	{
        $this->ids = $ids;
        $this->request_id = $request_id;
	}

	public function headings(): array {
		return [
			"Keyword","Start","Page","Rank","1 Day","7 Days","30 Days","Life","Comp","Search Volume","Date Added","Url"
		];
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$data = KeywordSearch::select(
    		'keyword',
    		DB::raw('(CASE WHEN start_ranking > 0  OR start_ranking != null THEN start_ranking ELSE "N/A" END) AS startPosition'),
            DB::raw('CEIL((CASE WHEN position > 0  OR position != null THEN position ELSE "N/A" END)/10) AS page'),
            DB::raw('(CASE WHEN position > 0  OR position != null THEN position ELSE "N/A" END) AS currentPosition'),
            DB::raw('(CASE WHEN oneday_position <> 0  OR oneday_position != null THEN oneday_position ELSE 0 END) AS oneDayPostion'),
            DB::raw('(CASE WHEN one_week_ranking <> 0  OR one_week_ranking != null THEN one_week_ranking ELSE 0 END) AS weekPostion'),
            DB::raw('(CASE WHEN monthly_ranking <> 0  OR monthly_ranking != null THEN monthly_ranking ELSE 0 END) AS monthPostion'),
            DB::raw('(CASE WHEN life_ranking <> 0  OR life_ranking != null THEN life_ranking ELSE 0 END) AS lifeTime'),
    		'cmp',
    		'sv',
    		'created_at',
    		'result_url'
    	);
        if(!empty($this->ids)){
            $data->whereIn('id',$this->ids);
        }
        $data->where('request_id',$this->request_id);
    	$data->orderBy('is_favorite','desc');
		$datas = $data->get();      
    	return $datas;
    }
}