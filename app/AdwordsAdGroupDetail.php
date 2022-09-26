<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdwordsAdGroupDetail extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'adwords_adGroup_details';

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
    protected $fillable = ['client_id','report_date','ad_group_id','ad_group_name','impressions','clicks','cost','conversions','account_currency_code'];

}
