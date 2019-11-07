<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inv_customer extends Model
{
    //
    protected $table = 'inv_customers';
    protected $fillable = 
    [
        'inv_cus_id',
        'inv_cus_com_id',
        'inv_cus_name',
        'inv_cus_com_name',
        'inv_cus_mobile',
        'inv_cus_email',
        'inv_cus_address',
        'inv_cus_website',
        'inv_cus_status',
        'inv_cus_submit_by',
        'inv_cus_submit_at',
        'inv_cus_update_by',
        'inv_cus_update_at',
    ];
    protected $primaryKey = 'inv_cus_id';
    public $incrementing = true;
    public $timestamps = false;

    public function cus_submit(){
        return $this->belongsTo('App\Admin_user','inv_cus_submit_by','au_id');
    }
    
    public function getCustomerInventoryInfo()
    {
        return $this->belongsTo('App\Inv_customer_inventrory','inv_cus_inv_cus_id','inv_cus_id');
    }
    public static function getCustomerCompany($customer_id)
    {

        return Inv_customer::where('inv_cus_id',$customer_id)->first();

    }
}
