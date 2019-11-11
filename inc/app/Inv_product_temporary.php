<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inv_product_temporary extends Model
{
    protected $fillable = 
    [
        'inv_pro_temp_id',
        'inv_pro_temp_user_id',
        'inv_pro_temp_pro_id',
        'inv_pro_temp_pro_name',
        'inv_pro_temp_type_name',
        'inv_pro_temp_qty',
        'inv_pro_temp_unit_price',
        'inv_pro_temp_exp_date',
        'inv_pro_temp_slno',
        'inv_pro_temp_deal_type',
        'inv_pro_temp_status',
        'inv_pro_temp_created_at',
        'inv_pro_temp_updated_at',
    ];
    protected $table = 'inv_product_temporaries';
    protected $primaryKey = 'inv_pro_temp_id';
    public $incrementing = true;
    public $timestamps = false;
}