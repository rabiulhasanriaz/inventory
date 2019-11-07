<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inv_supplier extends Model
{
    //
    protected $table = 'inv_suppliers';
    protected $fillable = 
    [
        'inv_sup_id',
        'inv_sup_com_id',
        'inv_sup_com_name',
        'inv_sup_address',
        'inv_sup_person',
        'inv_sup_mobile',
        'inv_sup_phone',
        'inv_sup_email',
        'inv_sup_website',
        'inv_sup_complain_num',
        'inv_sup_type',
        'inv_sup_open_due_bal',
        'inv_sup_status',
        'inv_sup_submit_by',
        'inv_sup_submit_at',
        'inv_sup_update_by',
    ];
    protected $primaryKey = 'inv_sup_id';
    public $incrementing = true;
    public $timestamps = false;

    public function sup_submit_by(){
        return $this->belongsTo('App\Admin_user','inv_sup_submit_by','au_id');
    }

    public static function getSupplierCompany($supplier_id)
    {
        return Inv_supplier::where('inv_sup_id',$supplier_id)->first();
    }

}
