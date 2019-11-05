<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inv_product_inventory extends Model
{
    //
    protected $fillable =
    [
        'inv_pro_inv_id',
        'inv_pro_inv_com_id',
        'inv_pro_inv_prodet_id',
        'inv_pro_inv_sup_id',
        'inv_pro_inv_issue_date',
        'inv_pro_inv_qty',
        'inv_pro_inv_avlble_qty',
        'inv_pro_inv_pur_price',
        'inv_pro_inv_memo_no',
        'inv_pro_inv_status',
        'inv_pro_inv_barcode',
        'inv_pro_inv_exp_date',
        'inv_pro_inv_submit_by',
        'inv_pro_inv_submit_at',
        'inv_pro_inv_update_by',
        'inv_pro_inv_update_at',
    ];
    protected $table = 'inv_product_inventories';
    protected $primaryKey = 'inv_pro_inv_id';
    public $incrementing = true;
    public $timestamps = false;
}
