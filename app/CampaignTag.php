<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignTag extends Model {


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'campaign_tags';

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
    protected $fillable = ['user_id','request_id','tag'];

}
