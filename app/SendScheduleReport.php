<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SendScheduleReport extends Model {

    protected $table = 'send_schedule_reports';

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
    
    protected $fillable = ['report_id','request_id','file_name','status'];
}
