<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignDashboard extends Model {


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'campaign_dashboards';

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
    protected $fillable = ['user_id','request_id','dashboard_id','dashboard_status','order_status'];

    public static function addCampaignDashboards($user_id, $last_inserted_id,$dashboardType_array){
        // dd($dashboardType_array);
        foreach($dashboardType_array as $id){
            CampaignDashboard::create([
                'user_id'=>$user_id,
                'request_id'=>$last_inserted_id,
                'dashboard_id'=>$id,
                'dashboard_status'=>1
            ]);
        }
        return;

    }

}
