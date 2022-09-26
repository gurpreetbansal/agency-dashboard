<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignNote extends Model {

   // use Sortable;

    protected $table = 'campaign_notes';

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
    protected $fillable = ['request_id','note_date','note'];


}