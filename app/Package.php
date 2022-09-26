<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Package extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'packages';

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
    protected $fillable = ['name', 'description', 'amount', 'image_tag', 'number_of_projects', 'number_of_keywords', 'free_trial', 'duration','monthly_amount','yearly_amount','site_audit_page','stripe_price_yearly_id','status','inr_monthly_amount','inr_yearly_amount','inr_price_monthly_id','inr_price_yearly_id'];


    public  function package_feature(){
        return $this->hasMany('App\PackageFeature');
    }

}
