<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Inv_ledger extends Model
{
    protected $fillable = 
    [
        'inv_ledg_id',
        'inv_ledg_company_id',
        'inv_ledg_category_id',
        'inv_ledg_ledger_name',
        'inv_ledg_status',
        'inv_ledg_created_by',
        'inv_ledg_created_at',
        'inv_ledg_updated_at',
        'inv_ledg_updated_by',
    ];
    protected $table = 'inv_ledgers';
    protected $primaryKey = 'inv_ledg_id';
    public $incrementing = true;
    public $timestamps = false;

    public function getLedgersname(){
        return $this->belongsTo('App\Inv_ledger_category','inv_ledg_category_id','inv_ledg_cat_cat_id');
    }

    public static function getTotalLedgerIdByCategoryId($ledgcat)
    {
        return Inv_ledger::where('inv_ledg_category_id',$ledgcat)
                        ->where('inv_ledg_company_id',Auth::user()->au_company_id)
                        ->where('inv_ledg_status',1)
                        ->pluck('inv_ledg_id')
                        ->toArray();
    }
}
