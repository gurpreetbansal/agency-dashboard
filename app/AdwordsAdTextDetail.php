<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdwordsAdTextDetail extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'adwords_adText_details';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['client_id','report_date','headline','headline_part1','headline_part2','expanded_text_ad_headline_part3','display_url','description','description1','description2','ad_type','ad_text_id','impressions','clicks','cost','conversions','responsive_search_ad_headlines','responsive_search_ad_descriptions','responsive_search_ad_path1','responsive_search_ad_path2','expanded_text_ad_description2','expanded_dynamic_search_creative_description2','creative_final_urls','path1','path2','long_headline','multi_asset_responsive_display_adHeadlines','multi_asset_responsive_displayAdLongHeadline','multi_asset_responsive_displayAdDescriptions'];


    public static function ad_description($ad_type,$value){
        $description =  $description1 = '';

        if($ad_type == 'Responsive search ad'){
            $description = implode(",",array_map(function($a){
                return $a['assetText'];
            },json_decode($value->responsive_search_ad_descriptions,true)));
        }



        if($ad_type=='Expanded dynamic search ad'){
            $description = '';
        }

        if($ad_type == 'Responsive display ad'){
            if($value->multi_asset_responsive_displayAdLongHeadline!=' --'){
                $multiDesc1 = json_decode($value->multi_asset_responsive_displayAdLongHeadline,true);

                if(count($multiDesc1)==1){
                    $description =  implode(",",array_map(function($a){
                        return $a['assetText'];
                    },$multiDesc1));
                }else{
                    $description =  implode(",",array_map(function($a){
                        return $a;
                    },$multiDesc1));
                }
            }


            if($value->multi_asset_responsive_displayAdDescriptions!=' --'){
                $multiDescDisplayDes = json_decode($value->multi_asset_responsive_displayAdDescriptions,true);

                if(is_array($multiDescDisplayDes) && count($multiDescDisplayDes)==1){
                    $description1 = implode(",",array_map(function($a){
                        return $a;
                    },$multiDescDisplayDes));
                }
                if(is_array($multiDescDisplayDes) && count($multiDescDisplayDes)>1){
                    $description1 =  implode(",",array_map(function($a){
                        return $a['assetText'];
                    },$multiDescDisplayDes));
                }

            }
        }


        if($ad_type=='Expanded text ad'){
            if($value->description !=' --' && $value->description!=null){
                $description = $val->description;
            }
            if($value->expanded_text_ad_description2!=' --' && $value->expanded_text_ad_description2!=null){
                $description1 = $value->expanded_text_ad_description2;
            }
        }



        return $description.$description1;

    }

    public static function ad_display_url($ad_type,$value){
        $url = $path1 = $path2 = '';
        if($ad_type=='Expanded dynamic search ad'){
            $url = "[Dynamically generated display URL]";
        }

        if($ad_type == 'Responsive search ad'){
            $url = json_decode($value->creative_final_urls,true)[0].$value->responsive_search_ad_path1.'/'.$value->responsive_search_ad_path2.'/';
        }

        if($ad_type == 'Responsive display ad'){
         $url = json_decode($value->creative_final_urls,true)[0].$value->responsive_search_ad_path1.'/'.$value->responsive_search_ad_path2.'/';
     }

     if($ad_type=='Expanded text ad'){

        if($value->path1!=' --'){
            $path1 = $value->path1.'/';
        }

        if($value->path2!=' --'){
            $path2 = $value->path2.'/';
        }

        $url = json_decode($value->creative_final_urls,true)[0].$path1.$path2;
    }

    return $url;
}


public static function ad_headline($ad_type,$value){
    $headline = '';
    if($ad_type == 'Expanded dynamic search ad'){
        $headline = "[Dynamically generated headline]";
    }

    if($ad_type == 'Responsive search ad'){
        $headline =    implode(" | ",array_map(function($a){
            return $a['assetText'];
        },json_decode($value->responsive_search_ad_headlines,true)));
    }

    if($ad_type =='Responsive display ad'){
        if($value->multi_asset_responsive_display_adHeadlines != '--'){
            $multidesc = json_decode($value->multi_asset_responsive_display_adHeadlines,true);
            $headline =    implode(",",array_map(function($a){
                return $a['assetText'];
            },$multidesc));
        } 
    }

    if($ad_type=='Expanded text ad'){
        $headline = $value->display_url;
    }

    if($value->headline!=' --' && $value->headline!=null){
        $headline = $value->headline;
    }

    return $headline;
}

}
