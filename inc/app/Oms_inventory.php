<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Oms_inventory extends Model
{
    //
    protected $fillable =
    [
    'oms_reason',
    'oms_bill_date',
    'oms_staff_id',
    'oms_payment',
    'oms_person',
    'oms_debit',
    'oms_insert_at',
    'oms_what_for',
    'oms_mode',
    'oms_dto',
    'oms_dfrom',
    'oms_status'
    ];
    protected $table = 'oms_inventory';
    protected $primaryKey = 'sl_id';
    public $incrementing = true;
    public $timestamps = false;

    public function employee_info() {
        return $this->belongsTo('App\Admin_user', 'oms_staff_id', 'au_id');
    }
    public function what_info() {
        return $this->belongsTo('App\Sds_query_book', 'oms_what_for', 'qb_id');
    }

    public function what_info_oms() {
        return $this->belongsTo('App\Sds_query_book', 'oms_what_for', 'qb_serial');
    }
}
