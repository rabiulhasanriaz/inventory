<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inv_supplier_inventory extends Model
{
    //
    protected $fillable = 
    [
        'inv_sup_inv_id',
        'inv_sup_inv_com_id',
        'inv_sup_inv_sup_id',
        'inv_sup_inv_proinv_id',
        'inv_sup_inv_debit',
        'inv_sup_inv_credit',
        'inv_sup_inv_tran_type',
        'inv_sup_inv_tran_ref_id',
        'inv_sup_inv_issue_date',
        'inv_sup_inv_status',
        'inv_sup_inv_submit_by',
        'inv_sup_inv_submit_at',
        'inv_sup_inv_update_by',
        'inv_sup_inv_update_at',
    ];
    protected $table = 'inv_supplier_inventories';
    protected $primaryKey = 'inv_sup_inv_id';
    public $incrementing = true;
    public $timestamps = false;
}
