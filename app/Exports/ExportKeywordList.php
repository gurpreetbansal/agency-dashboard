<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use App\KwSearchIdea;
use App\DfsLocation;
use App\DfsLanguage;


class ExportKeywordList implements FromCollection,WithHeadings
{

	private $ids;

	public function __construct($ids) 
	{
        $this->ids = $ids;
    }

    public function headings(): array {

      return [
         "Keyword","Avg. Search Volume (Last 12 months)","Competiton Index","Location","Language","Page Bid (Low)","Page Bid (High)","Search Volume (".date('m/Y',strtotime('-11 month')).')',"Search Volume (".date('m/Y',strtotime('-10 month')).')',"Search Volume (".date('m/Y',strtotime('-9 month')).')',"Search Volume (".date('m/Y',strtotime('-8 month')).')',"Search Volume (".date('m/Y',strtotime('-7 month')).')',"Search Volume (".date('m/Y',strtotime('-6 month')).')',"Search Volume (".date('m/Y',strtotime('-5 month')).')',"Search Volume (".date('m/Y',strtotime('-4 month')).')',"Search Volume (".date('m/Y',strtotime('-3 month')).')',"Search Volume (".date('m/Y',strtotime('-2 month')).')',"Search Volume (".date('m/Y',strtotime('-1 month')).')',"Search Volume (".date('m/Y').')'
     ];
 }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $array = array();
        $data = KwSearchIdea::whereIn('id',$this->ids)->get();   
        foreach($data as $key=>$value){
            $json = json_decode($value->sv_trend,true);
            if($value->location_id == 0){
                $location = 'Anywhere';
            }else{
                $location = DfsLocation::get_location_name($value->location_id);
            }

            if($value->language_id == 0){
                $language = 'Any';
            }else{
                $language = DfsLanguage::get_language($value->language_id);
            }

            if(!empty($json)){
                for($i=0;$i<count($json);$i++){
                    $sv_trend[$key][] = $json[$i]['monthly_search'];
                }
            }else{
                for($i=0;$i<12;$i++){
                    $sv_trend[$key][] = 0;
                }
            }


            $array[] = [
                'keyword'=> $value->search_term,
                'sv'=> $value->sv,
                'competition_index'=> $value->competition_index,
                'location'=> $location,
                'language'=> $language,
                'page_bid_low'=> $value->page_bid_low,
                'page_bid_high'=> $value->page_bid_high,
                'sv_1'=> $sv_trend[$key][0],
                'sv_2'=> $sv_trend[$key][1],
                'sv_3'=> $sv_trend[$key][2],
                'sv_4'=> $sv_trend[$key][3],
                'sv_5'=> $sv_trend[$key][4],
                'sv_6'=> $sv_trend[$key][5],
                'sv_7'=> $sv_trend[$key][6],
                'sv_8'=> $sv_trend[$key][7],
                'sv_9'=> $sv_trend[$key][8],
                'sv_10'=> $sv_trend[$key][9],
                'sv_11'=> $sv_trend[$key][10],
                'sv_12'=> $sv_trend[$key][11]
            ];
        }

        return collect($array);
    }


}