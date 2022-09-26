<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdwordsCampaignDetail extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'adwords_campaign_details';

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
    protected $fillable = ['client_id','report_date','campaign_id','campaign_name','impressions','clicks','cost','conversions','day','device','adNetworkType1','slot','accountCurrencyCode','cost_per_conversion','ctr','average_cpc','conversion_rate','conversion_value','average_cost','average_cpm','adNetworkType2'];

}
