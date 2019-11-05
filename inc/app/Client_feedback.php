<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Client_feedback extends Model
{
    //
    protected $fillable =
    [
    	'cf_company_id',
    	'cf_qb_id',
    	'cf_entry_by',
    	'cf_call_duration',
    	'cf_client_feedback',
    	'cf_next_date',
    	'cf_result',
    	'cf_client_message',
    	'cf_date',
    	'cf_status',
    	'cf_price',
    	'status'
    ];
    protected $table = 'client_feedback';
    protected $primaryKey = 'cf_id';
    public $incrementing = true;
    public $timestamps = false;



    // public function today_fu(){
    //     return $this->belongsTo('App\')
    // }
    public static function user_today_follow($au_id,$company_id){
        $today = Carbon::now()->format('Y-m-d');
        return Client_feedback::where('cf_date',$today)
                              ->where('cf_entry_by',$au_id)
                              ->where('cf_company_id',$company_id)
                              ->get();
    }

    public function feedback_info(){
      return $this->belongsTo('App\Admin_user','cf_entry_by','au_id');
    }
    
    public function followup_info(){
      return $this->hasMany('App\Sds_query_book','cf_id','qb_id');
    }

    public static function notifycus($cus_feed_id){
      $notify = Sds_sms::where('sms_cf_id',$cus_feed_id)
                       ->first();
                       
      if (!empty($notify)) {
        return $notify->sms_status;
      }else{
        return false;
      }
    }


    public function feedbackinfo(){
      return $this->belongsTo('App\Sds_feedback_msg','cf_client_feedback','fm_id');
    }

    public function cus_mobile(){
      return $this->belongsTo('App\Sds_query_book','cf_qb_id','qb_id');
    }

}
