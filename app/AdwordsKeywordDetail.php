<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdwordsKeywordDetail extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'adwords_keyword_details';

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
    protected $fillable = ['client_id','report_date','keyword_text','adkeyword_id','impressions','clicks','cost','conversions'];

}
