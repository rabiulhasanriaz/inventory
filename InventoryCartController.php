<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cart;
use DB;
use Auth;
use Carbon\Carbon;
use App\Inv_product_detail;
use App\Inv_product_inventory;
use App\Inv_product_inventory_detail;
use App\Inv_customer_inventory;



class InventoryCartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Inv_product_detail::where('inv_pro_det_id', $request->pro_id)
            ->where('inv_pro_det_com_id', Auth::user()->au_company_id)
            ->where('inv_pro_det_status', 1)
            ->first();
        
        if(!empty($product)) {
            if($product->inv_pro_det_available_qty < $request->pro_qty) {
                return response()->json(['status' => 400]);
            } else {

                $rows  = Cart::content();
                $row = $rows->where('id', $request->pro_id)->first();
                if(!empty($row)) {
                    $rowId = $row->rowId;
                    Cart::update($rowId,['qty' => $request->pro_qty, 'price' => $request->pro_price]);
                } else {
                    Cart::add([
                        'id' => $product->inv_pro_det_id, 
                        'name' => $product->inv_pro_det_pro_name, 
                        'type' => $product->inv_pro_det_type_id,
                        'qty' => $request->pro_qty, 
                        'price' => $request->pro_price,
                        'weight' => 0
                    ]);
                }
            }
        } else {
            return response()->json(['status' => 404]);
        }
    }

    public function addToCartWarrentyProduct(Request $request)
    {
        $product = Inv_product_detail::where('inv_pro_det_id', $request->pro_id)
            ->where('inv_pro_det_com_id', Auth::user()->au_company_id)
            ->where('inv_pro_det_status', 1)
            ->where('inv_pro_det_pro_warranty', '!=', 0)
            ->first();
        
        $product_sl_no = array();
        if(!empty($product)) {
            
            $rows  = Cart::content();
            $row = $rows->where('id', $request->pro_id)->first();
            if(!empty($row)) {
                $product_sl_no = $row->options->pro_sl_nl;
                // $rowId = $row->rowId;
                // Cart::update($rowId,['qty' => $request->pro_qty]);
            } else {
                Cart::add([
                    'id' => $product->inv_pro_det_id, 
                    'name' => $product->inv_pro_det_pro_name, 
                    'type' => $product->inv_pro_det_type_id,
                    'qty' => 1,
                    'price' => $request->pro_price,
                    'weight' => 0,
                    'options' => [
                        'pro_sl_nl' => [
                            
                        ]
                    ]
                ]);
            }
            
        } else {
            return response()->json(['status' => 404]);
        }

        return view('pages.ajax.warrenty_product_get_sl_no_inner_form', compact('product', 'product_sl_no'));
    }

    public function addWarrentyProductSlNo(Request $request)
    {
        $com = Auth::user()->au_company_id;
        $rows  = Cart::content();
        $row = $rows->where('id', $request->pro_id)->first();
        // dd($row);
        if(!empty($row)) {
            $pre_sl_no = $row->options->pro_sl_nl;
            if(in_array($request->sl_no, $pre_sl_no)) {
                return response()->json(['status' => 402]);//already added
            } 

            // all inventory id of requested product
            $all_inv_ids_of_this_product = Inv_product_inventory::where('inv_pro_inv_com_id', $com)
                            ->where('inv_pro_inv_prodet_id', $request->pro_id)
                            ->where('inv_pro_inv_ref_type', 1)
                            ->pluck('inv_pro_inv_id')
                            ->toArray();
            // search this sl no products
            $pro_inv_det = Inv_product_inventory_detail::where('inv_pro_invdet_com_id', $com)
                ->where('inv_pro_invdet_slno', '!=', null)
                ->where('inv_pro_invdet_sell_status', 0)
                ->whereIn('inv_pro_invdet_proinv_id', $all_inv_ids_of_this_product)
                ->where('inv_pro_invdet_slno', $request->sl_no)
                ->first();

            if(empty($pro_inv_det)) {
                return response()->json(['status' => 404]); //sl no not found
            }
            $pro_all_sl_no = $pre_sl_no;
            $pro_all_sl_no[] = $request->sl_no;
            $content_quantity = count($pro_all_sl_no);
            Cart::update($row->rowId, ['qty' => $content_quantity,'options' => ['pro_sl_nl' => $pro_all_sl_no]]);

            $all_content = Cart::content();
            $content = $all_content->where('id', $request->pro_id)->first();

            return view("pages.ajax.warrenty_product_sl_no_list", compact('content'));
        } else {
            return response()->json(['status' => 406]); //sl no not found
        }
    }

    public function removeWarrentyProductSlNo(Request $request)
    {
        $rows  = Cart::content();
        $row = $rows->where('id', $request->pro_id)->first();
        // dd($row);
        if(!empty($row)) {
            $pro_all_sl_no = $row->options->pro_sl_nl;

            if (($key = array_search($request->sl_no, $pro_all_sl_no)) !== false) {
                unset($pro_all_sl_no[$key]);
            }
            $content_quantity = count($pro_all_sl_no);
            Cart::update($row->rowId, ['qty' => $content_quantity,'options' => ['pro_sl_nl' => $pro_all_sl_no]]);

            $all_content = Cart::content();
            $content = $all_content->where('id', $request->pro_id)->first();

            return view("pages.ajax.warrenty_product_sl_no_list", compact('content'));
        } else {
            return response()->json(['status' => 406]); //sl no not found
        }
    }

    public function removecart(Request $request){
        $rows  = Cart::content();
        $rowId = $rows->where('id', $request->content_id)->first()->rowId;
        Cart::remove($rowId);
    }

    public function updatecart(Request $request){
        $rows  = Cart::content();
        $rowId = $rows->where('id', $request->content_id)->first()->rowId;
        Cart::update($rowId,['qty' => $request->pro_qty]);
    }


    public function getCartContent()
    {
        $cart_content = Cart::content();

        return view('pages.ajax.get_cart_content', compact('cart_content'));
    }




    // Submit cart form for sale
    public function cartSubmit(Request $request){

        $request->validate([
            'customer' => 'required'
        ]);
        
        DB::beginTransaction();
        try {
            $com = Auth::user()->au_company_id;
            $user_id = Auth::user()->au_id;
            $cart_content = Cart::content();
            
            $last_pro_inv = Inv_product_inventory::where('inv_pro_inv_com_id', $com)
                ->where('inv_pro_inv_ref_type', 2)
                ->orderBy('inv_pro_inv_id', 'DESC')
                ->first();
            if(!empty($last_pro_inv)) {
                $last_pro_inv_memo_no = $last_pro_inv->inv_pro_inv_memo_no;
                $last_data = substr($last_pro_inv_memo_no, 13);
                if(is_integer($last_data)) {
                    $new_memo_no = "INV".$com.date('Y').($last_data + 1);
                } else {
                    $new_memo_no = "INV".$com.date('Y')."000001";
                }
            } else {
                $new_memo_no = "INV".$com.date('Y')."000001";
            }
            
            foreach ($cart_content as $content) {
                $product_id = $content->id;
                $req_qty = $content->qty;

                $check_product = Inv_product_detail::where('inv_pro_det_id', $product_id)
                    ->where('inv_pro_det_com_id', $com)
                    ->where('inv_pro_det_status', 1)
                    ->where('inv_pro_det_available_qty', '>=', $req_qty)
                    ->first();

                if(empty($check_product)) {
                    DB::rollback();
                    $msg = "Invalid/Insufficient ". $content->name ." Product. error-code: 1006";
                    return redirect()->back()->with(['sub_err' => $msg]);
                }
            }

            // insert in product inventory table
            $total_purchase_amount = 0;
            
            foreach ($cart_content as $content) {
                $product_id = $content->id;
                $req_qty = $content->qty;
                $product_price = $content->price;
                $sub_total = $req_qty * $product_price;
                $total_purchase_amount += $sub_total;

                $check_product = Inv_product_detail::where('inv_pro_det_id', $product_id)
                    ->where('inv_pro_det_com_id', $com)
                    ->where('inv_pro_det_status', 1)
                    ->first();

                if($check_product->inv_pro_det_pro_warranty != 0) {
                    $request_sl_no_s = $content->options->pro_sl_nl;
                }

                $product_inventory = new Inv_product_inventory();
                $product_inventory->inv_pro_inv_com_id = $com;
                $product_inventory->inv_pro_inv_prodet_id = $content->id;
                $product_inventory->inv_pro_inv_ref_id = $request->customer;
                $product_inventory->inv_pro_inv_ref_type =  2;//2=customer
                $product_inventory->inv_pro_inv_issue_date = Carbon::now();
                $product_inventory->inv_pro_inv_qty = $req_qty;
                $product_inventory->inv_pro_inv_pur_price = $sub_total;
                $product_inventory->inv_pro_inv_memo_no = $new_memo_no;
                $product_inventory->inv_pro_inv_status = 1;
                $product_inventory->inv_pro_inv_barcode = null;
                $product_inventory->inv_pro_inv_exp_date = null;
                $product_inventory->inv_pro_inv_submit_by = $user_id;
                $product_inventory->inv_pro_inv_submit_at = Carbon::now();

                $product_inventory->save();

                for ($i=0; $i < $req_qty; $i++) { 
                    
                    if($check_product->inv_pro_det_pro_warranty == 0) {
                        // add non-warrenty product details in inv_product_inventory_details table
                        $pro_inv_details = new Inv_product_inventory_detail();
                        $pro_inv_details->inv_pro_invdet_com_id = $com;
                        $pro_inv_details->inv_pro_invdet_proinv_id = $product_inventory->inv_pro_inv_id;
                        $pro_inv_details->inv_pro_invdet_slno = null;
                        $pro_inv_details->inv_pro_invdet_sell_status = 11;
                        $pro_inv_details->inv_pro_invdet_status = 1;
                        $pro_inv_details->inv_pro_invdet_submit_by = $user_id;
                        $pro_inv_details->inv_pro_invdet_submit_at = Carbon::now();
                        $pro_inv_details->save();

                        // search and update previous product details sales_status 
                        $all_inv_ids_of_this_product = Inv_product_inventory::where('inv_pro_inv_com_id', $com)
                            ->where('inv_pro_inv_prodet_id', $check_product->inv_pro_det_id)
                            ->where('inv_pro_inv_ref_type', 1)
                            ->pluck('inv_pro_inv_id')
                            ->toArray();
                        $pre_pro_inv_details = Inv_product_inventory_detail::where('inv_pro_invdet_com_id', $com)
                            ->whereIn('inv_pro_invdet_proinv_id', $all_inv_ids_of_this_product)
                            ->where('inv_pro_invdet_sell_status', 0)
                            ->first();
                        
                        if(!empty($pre_pro_inv_details)) {
                            $pre_pro_inv_details->inv_pro_invdet_sell_status = 1;
                            $pre_pro_inv_details->save();
                        } else {
                            DB::rollback();
                            $msg = "Something went wrong to sell product. product name: ". $content->name .". error-code: 1001";
                            return redirect()->back()->with(['sub_err' => $msg]);
                        }

                        

                    } else {
                        
                        $request_sl_no = $request_sl_no_s[$i];
                        if($request_sl_no == '') {
                            DB::rollback();
                            $msg = "Something went wrong to sell product. product name: ". $content->name .". error-code: 1003";
                            return redirect()->back()->with(['sub_err' => $msg]);
                        }
                        // add warrenty product details in inv_product_inventory_details table
                        $pro_inv_details = new Inv_product_inventory_detail();
                        $pro_inv_details->inv_pro_invdet_com_id = $com;
                        $pro_inv_details->inv_pro_invdet_proinv_id = $product_inventory->inv_pro_inv_id;
                        $pro_inv_details->inv_pro_invdet_slno = $request_sl_no;
                        $pro_inv_details->inv_pro_invdet_sell_status = 11;
                        $pro_inv_details->inv_pro_invdet_status = 1;
                        $pro_inv_details->inv_pro_invdet_submit_by = $user_id;
                        $pro_inv_details->inv_pro_invdet_submit_at = Carbon::now();
                        $pro_inv_details->save();
                        

                        // search and update previous product details sales_status 
                        $all_inv_ids_of_this_product = Inv_product_inventory::where('inv_pro_inv_com_id', $com)
                            ->where('inv_pro_inv_prodet_id', $check_product->inv_pro_det_id)
                            ->where('inv_pro_inv_ref_type', 1)
                            ->pluck('inv_pro_inv_id')
                            ->toArray();
                            
                        $pre_pro_inv_details = Inv_product_inventory_detail::where('inv_pro_invdet_com_id', $com)
                            ->whereIn('inv_pro_invdet_proinv_id', $all_inv_ids_of_this_product)
                            ->where('inv_pro_invdet_sell_status', 0)
                            ->where('inv_pro_invdet_slno', $request_sl_no)
                            ->first();
                        
                        if(!empty($pre_pro_inv_details)) {
                            $pre_pro_inv_details->inv_pro_invdet_sell_status = 1;
                            $pre_pro_inv_details->save();
                        } else {
                            DB::rollback();
                            $msg = "Something went wrong to sell product. product name: ". $content->name .". error-code: 1002";
                            return redirect()->back()->with(['sub_err' => $msg]);
                        }
                        
                    }
                }

                
                // available quantity update in product detail table
                $after_submit_pro_available_quantity = $check_product->inv_pro_det_available_qty - $req_qty;
                $check_product->inv_pro_det_available_qty = $after_submit_pro_available_quantity;

                $check_product->save();


            }

            
            // insert in customer inventory tabe 
            $customer_inventory = new Inv_customer_inventory();
            
            $customer_inventory->inv_cus_inv_com_id = $com;
            $customer_inventory->inv_cus_inv_cus_id = $request->customer;
            $customer_inventory->inv_cus_inv_proinv_memo_no = $new_memo_no;
            $customer_inventory->inv_cus_inv_debit = $total_purchase_amount;
            $customer_inventory->inv_cus_inv_credit = 0;
            $customer_inventory->inv_cus_inv_tran_type = 1; //sell product
            $customer_inventory->inv_cus_inv_issue_date = Carbon::now();
            $customer_inventory->inv_cus_inv_status = 1;
            $customer_inventory->inv_cus_inv_submit_by = $user_id;
            $customer_inventory->inv_cus_inv_submit_at = Carbon::now();
            $customer_inventory->save();


            Cart::destroy();

            
        } catch (\Exception $e) {
            DB::rollback();
            $msg = "Something went wrong to sell product. error-code: 1010".$e->getMessage();
            return redirect()->back()->with(['sub_err' => $msg]);
        }

        DB::commit();
        $msg = "Sell Products Successfully completed";
        return redirect()->back()->with(['sub_success' => $msg]);
    }
}
