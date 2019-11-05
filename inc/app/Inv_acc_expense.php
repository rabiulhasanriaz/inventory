<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inv_acc_expense extends Model
{
	protected $table = 'inv_acc_expenses';
   // protected $primaryKey = 'inv_abi_id';
    public $incrementing = true;
    public $timestamps = false;

    public function getCategoryByCatID()
    {
    	return $this->belongsTo('App\Inv_acc_expense_category','inv_acc_exp_category_id','inv_acc_exp_cat_category_id');
    }
}
