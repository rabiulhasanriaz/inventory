<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Inv_acc_bank_statement extends Model
{
    protected $fillable =
    [
    	'inv_abs_id',
    	'inv_abs_company_id',
    	'inv_abs_inventory_id',
    	'inv_abs_reference_id',
    	'inv_abs_reference_type',
    	'inv_abs_bank_id',
    	'inv_abs_debit',
    	'inv_abs_credit',
    	'inv_abs_transaction_date',
    	'inv_abs_voucher_no',
    	'inv_abs_description',
    	'inv_abs_contra_transaction_id',
    	'inv_abs_status',
    	'inv_abs_submit_by',
    	'inv_abs_submit_at',
    	'inv_abs_updated_by',
    	'inv_abs_updated_at'
    	
    ];

    protected $table = 'inv_acc_bank_statements';
    protected $primaryKey = 'inv_abs_id';
    public $incrementing = true;
    public $timestamps = false;

     public static function get_Bank_Name_By_Bank_ID($id)
    {
        $acc_bank_id=DB::table('inv_acc_bank_infos')->where('inv_abi_id',$id)->pluck('inv_abi_bank_id');
        return DB::table('inv_banks')->select('bank_name')->where('id',$acc_bank_id)->first();

    }
    public static function get_Bank_Name_By_Refer_ID($id)
    {
        $acc_bank_id=DB::table('inv_acc_bank_infos')->where('inv_abi_id',$id)->pluck('inv_abi_bank_id');
      return DB::table('inv_banks')->select('bank_name')->where('id',$acc_bank_id)->first();
       
    }

}
