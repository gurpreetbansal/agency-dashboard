<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditErrorList extends Model {

	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'audit_error_lists';

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
      'category','error_key','error_label','short_description','description','status'
  	];
}