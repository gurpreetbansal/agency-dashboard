<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model {

    protected $table = 'invoice_items';

    protected $primaryKey = 'id';

    protected $fillable = [
        'invoice_master_id', 'description', 'amount', 'currency', 'quantity'
    ];
    
   
}