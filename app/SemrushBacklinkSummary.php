<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CampaignData;

class SemrushBacklinkSummary extends Model {

/**
* The database table used by the model.
*
* @var string
*/
protected $table = 'semrush_backlink_summary';     

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
protected $fillable = [
    'user_id','request_id','ascore','total','domains_num','urls_num','ips_num','follows_num','nofollows_num','sponsored_num','ugc_num','texts_num','images_num','status'
];


public static function cron_GetBacklinksCount($request_id){
    $result = SemrushBacklinkSummary::where('request_id',$request_id)->orderBy('id','desc')->first();
    $referringDomains = 0;
    if(!empty($result)){
        $referringDomains =  $result->total;
    } 

    $if_exists = CampaignData::where('request_id',$request_id)->first();

    if(!empty($if_exists)){
        CampaignData::where('request_id', $request_id)->update([
            'backlinks_count'=>$referringDomains
        ]);
    }else{
        CampaignData::create([
            'request_id'=>$request_id,
            'backlinks_count'=>$referringDomains
        ]);
    }
}


public static function buildReport($reportData)
{
  if($reportData === false) return array();

  $lines = explode("\n", $reportData);
  if(sizeof($lines) == 0) return array();

  if(strpos($lines[0], 'ERROR:'))
  {
    throw new Exception('ERROR: ' . $reportData);
}

$grid = SemrushBacklinkSummary::splitCSVFields($lines);
$firstRow = array_shift($grid);
for($i=0;$i<sizeof($firstRow);$i++)
{
    $firstRow[$i] = trim(strtolower(str_ireplace(' ', '_', $firstRow[$i])));
    $firstRow[$i] = str_ireplace('(%)', 'percent', $firstRow[$i]);
} 

$array = array();
foreach($grid as $row)
{

    $r = array();
    for($i=0;$i<sizeof($row);$i++)
    {
      if(isset($firstRow[$i]))
      {
        $r[$firstRow[$i]] = $row[$i];
    }
}
$array[] = $r;
}
return $array;    
}

public static function cURL($request)
{
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $request);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_TIMEOUT, 30);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array ( 'X-Real-IP',  $_SERVER['SERVER_ADDR']));
  $data = curl_exec($curl);

  if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200 )
  {
    curl_close($curl);
    return $data;
}

curl_close($curl);
return $data;
}

public static function splitCSVFields($lines)
{
  $fields = array();
  foreach($lines as $line)
  {
    $newline = SemrushBacklinkSummary::getParsedCSVString($line);
    try
    {
      $values = explode(";", $newline);
      $fields[] = str_replace("!-!", ",",$values);
  }
  catch(Exception $e)
  {

  }
}

return $fields;
}

public static function getParsedCSVString($fullString)
{
  $array = explode('"', $fullString);
  for($i = 1; $i<sizeof($array);$i+=2)
  {
    $array[$i] = str_ireplace(";", "!-!", $array[$i]);
}
return implode($array); 
}

}
