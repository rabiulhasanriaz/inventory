<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Carbon\Carbon;
use App\Inv_supplier;
use App\Inv_product_detail;
use App\Inv_product_inventory;
use App\Inv_product_temporary;

class ProductBuyEditController extends Controller
{
    public function buy_product_edit(Request $request,$id){
        DB::beginTransaction();
        try {
            $user_id = Auth::user()->au_id;
            $com = Auth::user()->au_company_id;
            $suppliers = Inv_supplier::where('inv_sup_com_id',$com)
                                    ->where('inv_sup_status',1)
                                    ->get();
            $sell_pro = Inv_product_detail::where('inv_pro_det_com_id',$com)
            ->where('inv_pro_det_status',1)
            ->get();

                
            $invoice_no = $id;

            $invoice_products = Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                            ->where('inv_pro_inv_invoice_no',$id)
                            ->where('inv_pro_inv_status',1)
                            ->where('inv_pro_inv_deal_type', 1)
                            ->where('inv_pro_inv_tran_type', 1)
                            ->get();

            
            if (count($invoice_products)>0) {
                $issue_date = $invoice_products->first()->inv_pro_inv_issue_date; 

                $buy_supplier = $invoice_products->first()->inv_pro_inv_party_id;
                
                $check_inv_temp = Inv_product_temporary::where('inv_pro_temp_invoice_no', $invoice_no)
                    ->where('inv_pro_temp_deal_type', 3)
                    ->get();
                if(count($check_inv_temp) > 0) {

                } else {
                    Inv_product_temporary::where('inv_pro_temp_user_id', $user_id)->delete();
                    foreach ($invoice_products as $invoice_product) {
                        $edit_pro_temp = new Inv_product_temporary();

                        $edit_pro_temp->inv_pro_temp_user_id = $user_id;
                        $edit_pro_temp->inv_pro_temp_pro_id = $invoice_product->inv_pro_inv_prodet_id;
                        $edit_pro_temp->inv_pro_temp_pro_name = $invoice_product->getProductInfo['inv_pro_det_pro_name'];
                        $edit_pro_temp->inv_pro_temp_type_name = $invoice_product->getProductInfo->type_info['inv_pro_type_name'];
                        $edit_pro_temp->inv_pro_temp_qty = $invoice_product->inv_pro_inv_qty;
                        $edit_pro_temp->inv_pro_temp_invoice_no = $invoice_product->inv_pro_inv_invoice_no;
                        $edit_pro_temp->inv_pro_temp_unit_price = $invoice_product->inv_pro_inv_unit_price;
                        $edit_pro_temp->inv_pro_temp_deal_type = 3;//3=purchase-edit
                        $edit_pro_temp->inv_pro_temp_status = 1;
                        $edit_pro_temp->inv_pro_temp_created_at = Carbon::now();
                        $edit_pro_temp->save();
                    }
                }
            } else {
                // dd("as");
                return redirect()->back()->with(['sub_err', 'Invalid Inventory']);
            }
            

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['sub_err' => 'Something went wrong'.$e->getMessage()]);
        }

        DB::commit();
        return view('inventory.product_return.buy_product_edit',compact('suppliers','sell_pro', 'invoice_no', 'buy_supplier', 'issue_date'));
    }


    public function addToCart(Request $request)
    {
        $com = Auth::user()->au_company_id;
        $product = Inv_product_detail::where('inv_pro_det_id', $request->pro_id)
            ->where('inv_pro_det_com_id', Auth::user()->au_company_id)
            ->where('inv_pro_det_status', 1)
            ->first();
        
        if(!empty($product)) {
            
            $row = Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_pro_id', $request->pro_id)
                ->where('inv_pro_temp_deal_type',3)
                ->first();
            // return $request->all();
            $pre_pro_inv = Inv_product_inventory::where('inv_pro_inv_com_id', $com)
                ->where('inv_pro_inv_prodet_id', $request->pro_id)
                ->where('inv_pro_inv_invoice_no', $request->invoice_no)
                ->where('inv_pro_inv_deal_type',1)
                ->where('inv_pro_inv_tran_type',1)
                ->first();
            
            if (!empty($pre_pro_inv)) {
                
                if(($pre_pro_inv->inv_pro_inv_qty - $product->inv_pro_det_available_qty) > ($request->pro_qty - (int)$request->short_qty)) {
                    return response()->json(['status' => 408]);
                }
            }
            
            if(!empty($row)) {
                $row->inv_pro_temp_pro_name = $product->inv_pro_det_pro_name;
                $row->inv_pro_temp_type_name = Inv_product_detail::get_type_name($product->inv_pro_det_id);
                $row->inv_pro_temp_short_qty = $request->short_qty;
                $row->inv_pro_temp_qty = $request->pro_qty;
                $row->inv_pro_temp_short_remarks = $request->remarks;
                $row->inv_pro_temp_unit_price = $request->pro_price;
                $row->inv_pro_temp_exp_date = $request->exp_date;
                $row->inv_pro_temp_updated_at = Carbon::now();

                $row->save();

            } else {
                $pro_temp_add = new Inv_product_temporary;
                $pro_temp_add->inv_pro_temp_user_id = Auth::user()->au_id;
                $pro_temp_add->inv_pro_temp_pro_id = $product->inv_pro_det_id;
                $pro_temp_add->inv_pro_temp_pro_name = $product->inv_pro_det_pro_name;
                $pro_temp_add->inv_pro_temp_type_name = Inv_product_detail::get_type_name($product->inv_pro_det_id);
                $pro_temp_add->inv_pro_temp_short_qty = $request->short_qty;
                $pro_temp_add->inv_pro_temp_qty = $request->pro_qty;
                $pro_temp_add->inv_pro_temp_short_remarks = $request->remarks;
                $pro_temp_add->inv_pro_temp_unit_price = $request->pro_price;
                $pro_temp_add->inv_pro_temp_exp_date = $request->exp_date;
                $pro_temp_add->inv_pro_temp_invoice_no = $request->invoice_no;
                $pro_temp_add->inv_pro_temp_slno = '';
                $pro_temp_add->inv_pro_temp_deal_type = '3';//1=purchase,2=sale,3=purchase-edit
                $pro_temp_add->inv_pro_temp_status = 1;
                $pro_temp_add->inv_pro_temp_created_at = Carbon::now();

                $pro_temp_add->save();
            }

        } else {
            return response()->json(['status' => 404]);
        }
    }


    public function cartSubmit(Request $request){
        $request->validate([
            'supplier' => 'required',
            'memo' => 'required',
            'issue_date' => 'required'
        ]);
        
        DB::beginTransaction();
        try {
            $com = Auth::user()->au_company_id;
            $user_id = Auth::user()->au_id;
            $cart_content = Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_deal_type',3)
                ->get();
            
            $new_memo_no = $cart_content->first()->inv_pro_temp_invoice_no;
            
            // check and update previous product available quantity, inventory //
            $pre_sell_products = Inv_product_inventory::where('inv_pro_inv_invoice_no', $new_memo_no)
                ->where('inv_pro_inv_com_id', $com)
                ->where('inv_pro_inv_deal_type', 1)
                ->where('inv_pro_inv_tran_type', 1)
                ->get();

            $pre_inv_edit_count = $pre_sell_products->first()->inv_pro_inv_edit_count;
            $pre_inv_confirm_status = $pre_sell_products->first()->inv_pro_inv_confirm;

            foreach ($pre_sell_products as $pre_sell_product) {
                $pre_pro_det = Inv_product_detail::where('inv_pro_det_com_id', $com)
                    ->where('inv_pro_det_id', $pre_sell_product->inv_pro_inv_prodet_id)
                    ->first();
                if (!empty($pre_pro_det)) {
                    $pre_pro_det->inv_pro_det_available_qty = $pre_pro_det->inv_pro_det_available_qty - $pre_sell_product->inv_pro_inv_qty;
                    $pre_pro_det->save();
                }
            }

            // insert in product inventory table
            $total_purchase_amount = 0;
            
            Inv_product_inventory::where('inv_pro_inv_invoice_no', $new_memo_no)
                ->where('inv_pro_inv_com_id', $com)
                ->where('inv_pro_inv_deal_type', 1)
                ->where('inv_pro_inv_tran_type', 1)
                ->delete();

            foreach ($cart_content as $content) {
                $product_id = $content->inv_pro_temp_pro_id;

                $check_product = Inv_product_detail::where('inv_pro_det_id', $product_id)
                    ->where('inv_pro_det_com_id', $com)
                    ->where('inv_pro_det_status', 1)
                    ->first();


                $req_sl_ids = explode(',', $content->inv_pro_temp_slno);
                $k = 0;
                
                foreach ($req_sl_ids as $sl_id) {
                    if($sl_id == '') {
                        unset($req_sl_ids[$k]);
                    } else {
                        $new_req_sl_ids[] = $req_sl_ids[$k];
                    }
                    $k++;
                }
                
                if($check_product->inv_pro_det_pro_warranty == 0) {
                    $req_qty = $content->inv_pro_temp_qty;

                } else {
                    if (!isset($new_req_sl_ids)) {
                        $new_req_sl_ids = array();
                    }
                    $req_sl_ids = $new_req_sl_ids;
                    $req_qty = count($req_sl_ids);
                }



                
                $product_price = $content->inv_pro_temp_unit_price;
                $sub_total = $req_qty * $product_price;
                $total_purchase_amount += $sub_total;
                $exp_date = $content->inv_pro_temp_exp_date;
                $available_quantity = $req_qty - $content->inv_pro_temp_short_qty;

                
                
                $product_inventory = new Inv_product_inventory();
                $product_inventory->inv_pro_inv_com_id = $com;
                $product_inventory->inv_pro_inv_prodet_id = $product_id;
                $product_inventory->inv_pro_inv_party_id = $request->supplier;
                $product_inventory->inv_pro_inv_invoice_no = $new_memo_no;
                $product_inventory->inv_pro_inv_total_qty = $req_qty;
                $product_inventory->inv_pro_inv_short_qty = $content->inv_pro_temp_short_qty;
                $product_inventory->inv_pro_inv_short_remarks = $content->inv_pro_temp_short_remarks;
                $product_inventory->inv_pro_inv_qty = $available_quantity;
                $product_inventory->inv_pro_inv_unit_price = $product_price;
                $product_inventory->inv_pro_inv_debit = $sub_total;
                $product_inventory->inv_pro_inv_credit = 0;
                $product_inventory->inv_pro_inv_issue_date = Carbon::now();
                $product_inventory->inv_pro_inv_exp_date = $exp_date;
                $product_inventory->inv_pro_inv_tran_desc = "Purchase Product";
                $product_inventory->inv_pro_inv_deal_type =  1;//1=supplier
                $product_inventory->inv_pro_inv_tran_type =  1;//1=buy/sell product-buy
                $product_inventory->inv_pro_inv_status = 1;
                $product_inventory->inv_pro_inv_confirm = $pre_inv_confirm_status;
                $product_inventory->inv_pro_inv_edit_count = $pre_inv_edit_count + 1;
                $product_inventory->inv_pro_inv_submit_by = $user_id;
                $product_inventory->inv_pro_inv_submit_at = Carbon::now();

                $product_inventory->save();

                for ($i=0; $i < $req_qty; $i++) { 
                    
                    if($check_product->inv_pro_det_pro_warranty == 0) {

                    } else {
                        
                        // add warrenty product details in inv_product_inventory_details table
                        $pro_inv_details = new Inv_product_inventory_detail();
                        $pro_inv_details->inv_pro_invdet_com_id = $com;
                        $pro_inv_details->inv_pro_invdet_proinv_id = $product_inventory->inv_pro_inv_id;
                        $pro_inv_details->inv_pro_invdet_pro_id = $check_product->inv_pro_det_id;
                        $pro_inv_details->inv_pro_invdet_buy_id = $request->supplier;
                        $pro_inv_details->inv_pro_invdet_slno = $req_sl_ids[$i];
                        $pro_inv_details->inv_pro_invdet_sell_status = 0;
                        $pro_inv_details->inv_pro_invdet_status = 1;
                        $pro_inv_details->inv_pro_invdet_submit_by = $user_id;
                        $pro_inv_details->inv_pro_invdet_submit_at = Carbon::now();
                        $pro_inv_details->save();
                        
                    }
                }


                $pro_sup = Inv_supplier::where('inv_sup_com_id', $com)
                ->where('inv_sup_id', $request->supplier)
                ->first();

                
                // available quantity update in product detail table
                $after_submit_pro_available_quantity = $check_product->inv_pro_det_available_qty + $available_quantity;
                $check_product->inv_pro_det_available_qty = $after_submit_pro_available_quantity;

                $check_product->save();
            }

            Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_deal_type',3)
                ->delete();
            
        } catch (\Exception $e) {
            DB::rollback();
            $msg = "Something went wrong to sell product. error-code: 1010".$e->getMessage();
            return redirect()->back()->with(['sub_err' => $msg]);
        }

        DB::commit();
        $msg = "Buy Products Successfully completed";
        return redirect()->route('buy.buy-product-new')->with(['sub_success' => $msg , 'print_buy_invoice' => $new_memo_no]);
    }

    public function removecart(Request $request){
        Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_pro_id', $request->content_id)
                ->where('inv_pro_temp_deal_type',3)
                ->delete();
                
    }

    public function updatecart(Request $request){
        $row = Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_pro_id', $request->content_id)
                ->where('inv_pro_temp_deal_type',3)
                ->get();
        $row->inv_pro_temp_qty = $request->pro_qty;
        $row->inv_pro_temp_updated_at = Carbon::now();
        $row->save();
    }

    public function getCartContent()
    {
        $cart_content = Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
            ->where('inv_pro_temp_deal_type',3)
            ->get();

        return view('pages.ajax.purchase_edit.get_cart_content', compact('cart_content'));
    }

    public function invTemporaryBuy(Request $request){
        $user = Auth::user()->au_id;
        $com = Auth::user()->au_company_id;
        $pro_temps = Inv_product_temporary::where('inv_pro_temp_user_id', $user)
                                            ->where('inv_pro_temp_deal_type',3)
                                            ->get();
        $pro_sup = Inv_supplier::where('inv_sup_com_id', $com)
            ->where('inv_sup_id', $request->supplier)
            ->first();
        return view('inventory.product_inventory.product_buy_invoice',compact('pro_temps','pro_sup'));
    }


    public function buyEditConfirmFormShow(Request $request)
    {
        $user = Auth::user()->au_id;
        $com = Auth::user()->au_company_id;
        $pro_temps = Inv_product_temporary::where('inv_pro_temp_user_id', $user)
                                            ->where('inv_pro_temp_deal_type',3)
                                            ->get();
        $pro_sup = Inv_supplier::where('inv_sup_com_id', $com)
            ->where('inv_sup_id', $request->supplier)
            ->first();
        return view('inventory.product_return.product_buy_edit_invoice',compact('pro_temps','pro_sup'));
    }
}
