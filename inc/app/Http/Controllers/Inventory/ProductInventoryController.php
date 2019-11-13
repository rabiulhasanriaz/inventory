<?php

namespace App\Http\Controllers\Inventory;

use Auth;
use Cart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Inv_supplier;
use App\Inv_product_detail;
use App\Inv_customer;
use App\Inv_product_inventory;
use App\Inv_supplier_inventory;
use App\Inv_product_inventory_detail;
use Illiminate\Support\Facades\DB;

class ProductInventoryController extends Controller
{
    //
    public function buy_product_add(){
        $com = Auth::user()->au_company_id;
        $suppliers = Inv_supplier::where('inv_sup_com_id',$com)
                                 ->where('inv_sup_status',1)
                                 ->orderBy('inv_sup_com_name','ASC')
                                 ->get();
        $products = Inv_product_detail::where('inv_pro_det_com_id',$com)
                                            ->where('inv_pro_det_status',1)
                                            ->orderBy('inv_pro_det_pro_name','ASC')                                           
                                            ->get();
        return view('inventory.product_inventory.add',compact('suppliers','products'));
    }

    public function buy_product_new(){
        $com = Auth::user()->au_company_id;
        $suppliers = Inv_supplier::where('inv_sup_com_id',$com)
                                 ->where('inv_sup_status',1)
                                 ->orderBy('inv_sup_com_name','ASC')
                                 ->get();
        $sell_pro = Inv_product_detail::where('inv_pro_det_com_id',$com)
                                      ->where('inv_pro_det_status',1)
                                      ->get();
        $customers = Inv_customer::where('inv_cus_com_id',$com)
                                  ->where('inv_cus_status',1)
                                  ->get();
        return view('inventory.product_inventory.add_new',compact('sell_pro','customers','suppliers'));
    }

    public function buy_product_submit(Request $request){
        $com = Auth::user()->au_company_id;
        $submit_by = Auth::user()->au_id;
        $submit_at = Carbon::now()->format('Y-m-d H:i:s');

        $request->validate([
            'supplier' => 'required',
            'product' => 'required',
            'issue' => 'required',
            'qty' => 'required|integer|min:1',
            'memo' => 'required|max:15',
            'warrenty' => 'required',
        ]);
        $pro_det = Inv_product_detail::where('inv_pro_det_com_id',$com)
                                    ->where('inv_pro_det_id',Input::get('product'))
                                    ->first();
        if (!empty($pro_det)) {
            $pro_det->inv_pro_det_available_qty = $pro_det->inv_pro_det_available_qty + Input::get('qty');
            $pro_det->save();
            $pro_buy_price = $pro_det->inv_pro_det_buy_price;
            $total_purchase_price = $pro_buy_price * Input::get('qty');
           
            $pro_inv = new Inv_product_inventory;
            $pro_inv->inv_pro_inv_com_id = $com;
            $pro_inv->inv_pro_inv_prodet_id = Input::get('product');
            $pro_inv->inv_pro_inv_ref_id = Input::get('supplier');
            $pro_inv->inv_pro_inv_ref_type = 1; //1=suppliers
            $pro_inv->inv_pro_inv_issue_date = Input::get('issue');
            $pro_inv->inv_pro_inv_qty = Input::get('qty');
            $pro_inv->inv_pro_inv_pur_price = $total_purchase_price;
            $pro_inv->inv_pro_inv_memo_no = Input::get('memo');
            $pro_inv->inv_pro_inv_exp_date = Input::get('expired');
            $pro_inv->inv_pro_inv_submit_by = $submit_by;
            $pro_inv->inv_pro_inv_submit_at = $submit_at;
            $pro_inv->save();
            
            $pro_sup_inv = new Inv_supplier_inventory;
            $pro_sup_inv->inv_sup_inv_com_id = $com;
            $pro_sup_inv->inv_sup_inv_sup_id = Input::get('supplier');
            $pro_sup_inv->inv_sup_inv_proinv_id = $pro_inv->inv_pro_inv_id;
            $pro_sup_inv->inv_sup_inv_debit = 0;
            $pro_sup_inv->inv_sup_inv_credit = $total_purchase_price;
            $pro_sup_inv->inv_sup_inv_tran_type = 1;//1=buy product 
            $pro_sup_inv->inv_sup_inv_issue_date = Input::get('issue');
            $pro_sup_inv->inv_sup_inv_status = 1;
            $pro_sup_inv->inv_sup_inv_submit_by = $submit_by;
            $pro_sup_inv->inv_sup_inv_submit_at = $submit_at;
            $pro_sup_inv->save();

            // if (Input::get('warrenty') == 1) {
            for ($i=0; $i < Input::get('qty'); $i++) { 
                $pro_inv_det = new Inv_product_inventory_detail;
                $pro_inv_det->inv_pro_invdet_com_id = $com;
                $pro_inv_det->inv_pro_invdet_proinv_id = $pro_inv->inv_pro_inv_id;
                $pro_inv_det->inv_pro_invdet_slno = @Input::get('pro_sl_no')[$i];
                $pro_inv_det->inv_pro_invdet_sell_status = 0;
                $pro_inv_det->inv_pro_invdet_status = 1;
                $pro_inv_det->inv_pro_invdet_submit_by = $submit_by;
                $pro_inv_det->inv_pro_invdet_submit_at = $submit_at;
                $pro_inv_det->save();
            }
            // }else {
            //         $pro_inv_det = new Inv_product_inventory_detail;
            //         $pro_inv_det->inv_pro_invdet_com_id = $com;
            //         $pro_inv_det->inv_pro_invdet_proinv_id = Input::get('product');
            //         $pro_inv_det->inv_pro_invdet_slno = Input::get('pro_sl_no');
            //         $pro_inv_det->inv_pro_invdet_sell_status = 0;
            //         $pro_inv_det->inv_pro_invdet_status = 1;
            //         $pro_inv_det->inv_pro_invdet_submit_by = $submit_by;
            //         $pro_inv_det->inv_pro_invdet_submit_at = $submit_at;
            //         $pro_inv_det->save();
            // }
        }else {
            return redirect()->back()->with(['invalid' => 'Invalid Product']);
        }

       
        return redirect()->back()->with(['pur_pro' => 'Product Purchases Successfully']);        
    }

    public function available_list(){
        $com = Auth::user()->au_company_id;
        $available = Inv_product_inventory_detail::where('inv_pro_invdet_com_id',$com)
                                                 ->get();
        return view('inventory.product_inventory.available');
    }

    public function sell_product(){
        $com = Auth::user()->au_company_id;
        $sell_pro = Inv_product_detail::where('inv_pro_det_com_id',$com)
                                      ->where('inv_pro_det_status',1)
                                      ->get();
        $customers = Inv_customer::where('inv_cus_com_id',$com)
                                  ->where('inv_cus_status',1)
                                  ->get();
        
        return view('inventory.product_inventory.sell_product',compact('sell_pro','customers'));
    }

}
