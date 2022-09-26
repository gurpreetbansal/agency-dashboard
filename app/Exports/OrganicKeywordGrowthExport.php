<?php

namespace App\Exports;

use App\SemrushOrganicSearchData;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class OrganicKeywordGrowthExport implements FromCollection,WithHeadings
{

	private $campaign_id;

	public function __construct($campaign_id) 
	{
        $this->campaign_id = $campaign_id;
	}

	public function headings(): array {
		return [
			"Keyword","Position","Volume","CPC(USD)","Traffic (%)"
		];
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $keywords = SemrushOrganicSearchData::
        select(
    		DB::raw('keywords AS keyword'),
    		'position',
    		DB::raw('search_volume AS volume'),
    		'cpc',
    		'traffic'
    	)
		->where('request_id', $this->campaign_id)
		->orderBy('position','asc')
		->get();
		return $keywords;
    }
}
