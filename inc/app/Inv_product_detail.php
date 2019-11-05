<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inv_product_detail extends Model
{
    //
    protected $table = 'inv_product_details';
    protected $fillable = 
    [
        'inv_pro_det_id',
        'inv_pro_det_com_id',
        'inv_pro_det_type_id',
        'inv_pro_det_sup',
        'inv_pro_det_pro_name',
        'inv_pro_det_buy_price',
        'inv_pro_det_sell_price',
        'inv_pro_det_pro_description',
        'inv_pro_det_available_qty',
        'inv_pro_det_short_qty',
        'inv_pro_det_status',
        'inv_pro_det_submit_by',
        'inv_pro_det_submit_at',
        'inv_pro_det_update_by',
        'inv_pro_det_update_at',
    ];
    protected $primaryKey = 'inv_pro_det_id';
    public $incrementing = true;
    public $timestamps = false;

    public function type_info(){
        return $this->belongsTo('App\Inv_product_type','inv_pro_det_type_id','inv_pro_type_id');
    }

    public function submit_type(){
        return $this->belongsTo('App\Admin_user','inv_pro_det_submit_by','au_id');
    }

     public static function suppliers_info($id){
        $product = Inv_product_detail::where('inv_pro_det_id',$id)
                                     ->first();
        $supplier_id = explode('-',$product->inv_pro_det_sup);
        if (!empty($supplier_id)) {
            $suppliers = Inv_supplier::whereIn('inv_sup_id',$supplier_id)->get();
            return $suppliers;
        }else {
            return null;
        }
    }
}
