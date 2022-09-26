<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceChange extends Model {

    protected $table = 'invoice_changes';

    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'invoice_id'];
}