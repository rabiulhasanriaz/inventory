<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin_user extends Authenticatable
{
    //
    protected $table = 'admin_user';
    protected $fillable =
    [
    'au_id',
    'au_company_id',
    'au_company_name',
    'au_name',
    'au_user_id',
    'au_email',
    'au_mobile',
    'au_password',
    'au_address',
    'au_user_type',
    'au_api_key',
    'au_status',
    'au_permission_status',
    'au_sender_id',
    'au_team_id',
    'au_company_img'
    ];

    protected $primaryKey = 'au_id';
    public $incrementing = true;
    public $timestamps = false;

    public function team_info() {
    	return $this->belongsTo('App\Sds_team_name', 'au_team_id', 'tl_sl_id');
    }

    public function customers() {
        return $this->hasMany('App\Sds_query_book', 'qb_entry_by', 'au_id');
    }

    public static function team_member($team_id, $company_id) {
        return Admin_user::where('au_company_id', $company_id)
                ->where('au_team_id', $team_id)
                ->where('au_user_type', 6)
                ->get();
    }

    public static function teams($company_id) {
        return Admin_user::where('au_company_id', $company_id)
                ->where('au_user_type', 5)
                ->get();
    }

    public static function users($company_id) {
        return Admin_user::where('au_company_id', $company_id)
                ->where('au_user_type', 6)
                ->get();
    }

    public static function tl_name($team_id,$company_id){
        return Admin_user::where('au_company_id',$company_id)
                         ->where('au_team_id',$team_id)
                         ->where('au_user_type',5)
                         ->first();
    }

    public static function company_info($com_id){
        $admin_query = Admin_user::where('au_user_type',4)
                                ->where('au_company_id',$com_id)
                                ->first();
        return $admin_query;
    }

}
