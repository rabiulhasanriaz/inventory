<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sds_query_book extends Model
{
    //
    protected $fillable =
    [
    	'qb_company_id',
    	'qb_entry_by',
    	'qb_serial',
    	'qb_mobile',
      'qb_mobile1',
      'qb_mobile2',
    	'qb_phone',
    	'qb_name',
    	'qb_company_name',
    	'qb_find_us',
    	'qb_address',
    	'qb_reason',
      'qb_birth_date',
      'qb_marriage_date',
    	'qb_email',
    	'qb_result',
    	'qb_submit_date',
    	'qb_status',
    	'qb_feedback',
    	'qb_staff_id'
    ];
    protected $table = 'sds_query_book';
    protected $primaryKey = 'qb_id';
    public $incrementing = true;
    public $timestamps = false;

    // public function com_info(){
    // 	return $this->belongTo('App\Oms_inventory','qb_serial','sl_id');
    // }

    public function feedback_info1(){
      return $this->belongsTo('App\Admin_user','qb_entry_by','au_id');
    }
    public function find_info() {
        return $this->belongsTo('App\Sds_find_us', 'qb_find_us', 'sf_slid');
    }
    public function reas_info() {
        return $this->belongsTo('App\Sds_reason', 'qb_reason', 'sr_slid');
    }
    public function entry_info(){
        return $this->belongsTo('App\Admin_user','qb_entry_by','au_id');
    }
    public function entry_staff(){
      return $this->belongsTo('App\Admin_user','qb_staff_id','au_id');
    }
    public function followup(){
      return $this->belongsTo('App\Client_feedback','qb_entry_by','cf_id');
    }

    public static function user_customer($au_id,$company_id){
        return Sds_query_book::where('qb_company_id',$company_id)
                             ->where('qb_entry_by',$au_id)
                             ->get();
    }

    public static function user_today_cus($au_id,$company_id){
        $today = Carbon::now()->format('Y-m-d');
        return Sds_query_book::where('qb_company_id',$company_id)
                             ->where('qb_entry_by',$au_id)
                             ->where('qb_submit_date',$today)
                             ->get();
    }

    public static function user_monthly_cus($au_id,$company_id){
        $month = Carbon::now()->startOfMonth()->format('Y-m-d');
        $todaydate = Carbon::now()->format('Y-m-d');
        return Sds_query_book::where('qb_company_id',$company_id)
                             ->where('qb_entry_by',$au_id)
                             ->where('qb_submit_date','<=',$todaydate)
                             ->where('qb_submit_date','>=',$month)
                             ->get();
    }

    public static function user_weekly_cus($au_id,$company_id, $date){
        return Sds_query_book::where('qb_company_id',$company_id)
                             ->where('qb_entry_by',$au_id)
                             ->where('qb_submit_date', $date)
                             ->get();
    }
    public static function user_weekly_cus_staff($staff_id,$company_id, $date){
        return Sds_query_book::where('qb_company_id',$company_id)
                             ->where('qb_staff_id',$staff_id)
                             ->where('qb_submit_date', $date)
                             ->get();
    }

    public static function total_customer($company_id) {
        return Sds_query_book::where('qb_company_id', $company_id)
                              ->get();
    }

    public function followinfo(){
      return $this->belongsTo('App\Sds_feedback_msg','qb_feedback','fm_id');
    }



}
