<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inv_product_inventory_detail extends Model
{
    //
    protected $fillable =
    [
        'inv_pro_invdet_id',
        'inv_pro_invdet_com_id',
        'inv_pro_invdet_proinv_id',
        'inv_pro_invdet_slno',
        'inv_pro_invdet_sell_status',
        'inv_pro_invdet_status',
        'inv_pro_invdet_submit_by',
        'inv_pro_invdet_submit_at',
        'inv_pro_invdet_update_by',
        'inv_pro_invdet_update_at',
    ];
    protected $table = 'inv_product_inventory_details';
    protected $primaryKey = 'inv_pro_invdet_id';
    public $incrementing = true;
    public $timestamps = false;
}
