<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Cart;
use DB;
use Auth;
use Carbon\Carbon;
use App\Inv_product_detail;
use App\Inv_product_inventory;
use App\Inv_product_inventory_detail;
use App\Inv_customer_inventory;
use App\Inv_product_temporary;
use App\Inv_customer;



class InventoryCartController extends Controller
{
    public function addToCart(Request $request)
    {
        // $product = Inv_product_detail::where('inv_pro_det_id', $request->pro_id)
        //     ->where('inv_pro_det_com_id', Auth::user()->au_company_id)
        //     ->where('inv_pro_det_status', 1)
        //     ->first();
        
        // if(!empty($product)) {
        //     if($product->inv_pro_det_available_qty < $request->pro_qty) {
        //         return response()->json(['status' => 400]);
        //     } else {

        //         $rows  = Cart::content();
        //         $row = $rows->where('id', $request->pro_id)->first();
        //         if(!empty($row)) {
        //             $rowId = $row->rowId;
        //             Cart::update($rowId,['qty' => $request->pro_qty, 'price' => $request->pro_price]);
        //         } else {
        //             // Cart::add([
        //             //     'id' => $product->inv_pro_det_id, 
        //             //     'name' => $product->inv_pro_det_pro_name, 
        //             //     'type' => $product->inv_pro_det_type_id,
        //             //     'qty' => $request->pro_qty, 
        //             //     'price' => $request->pro_price,
        //             //     'weight' => 0
        //             // ]);
                
        //         }
        //     }
        // } else {
        //     return response()->json(['status' => 404]);
        // }

        $product = Inv_product_detail::where('inv_pro_det_id', $request->pro_id)
            ->where('inv_pro_det_com_id', Auth::user()->au_company_id)
            ->where('inv_pro_det_status', 1)
            ->first();
        
        if(!empty($product)) {

            if($product->inv_pro_det_available_qty < $request->pro_qty) {
                return response()->json(['status' => 400]);
            }
            
            $row = Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_pro_id', $request->pro_id)
                ->where('inv_pro_temp_deal_type',2)
                ->first();
            if(!empty($row)) {
                $row->inv_pro_temp_pro_name = $product->inv_pro_det_pro_name;
                $row->inv_pro_temp_type_name = Inv_product_detail::get_type_name($product->inv_pro_det_id);
                $row->inv_pro_temp_qty = $request->pro_qty;
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
                $pro_temp_add->inv_pro_temp_qty = $request->pro_qty;
                $pro_temp_add->inv_pro_temp_unit_price = $request->pro_price;
                $pro_temp_add->inv_pro_temp_exp_date = $request->exp_date;
                $pro_temp_add->inv_pro_temp_slno = '';
                $pro_temp_add->inv_pro_temp_deal_type = '2';//1=purchase,2=sale
                $pro_temp_add->inv_pro_temp_status = 1;
                $pro_temp_add->inv_pro_temp_created_at = Carbon::now();

                $pro_temp_add->save();
            }

        } else {
            return response()->json(['status' => 404]);
        }
    }

    public function addToCartWarrentyProduct(Request $request)
    {
        // $product = Inv_product_detail::where('inv_pro_det_id', $request->pro_id)
        //     ->where('inv_pro_det_com_id', Auth::user()->au_company_id)
        //     ->where('inv_pro_det_status', 1)
        //     ->where('inv_pro_det_pro_warranty', '!=', 0)
        //     ->first();
        
        // $product_sl_no = array();
        // if(!empty($product)) {
            
        //     $rows  = Cart::content();
        //     $row = $rows->where('id', $request->pro_id)->first();
        //     if(!empty($row)) {
        //         $product_sl_no = $row->options->pro_sl_nl;
        //         // $rowId = $row->rowId;
        //         // Cart::update($rowId,['qty' => $request->pro_qty]);
        //     } else {
        //         // Cart::add([
        //         //     'id' => $product->inv_pro_det_id, 
        //         //     'name' => $product->inv_pro_det_pro_name, 
        //         //     'type' => $product->inv_pro_det_type_id,
        //         //     'qty' => 1,
        //         //     'price' => $request->pro_price,
        //         //     'weight' => 0,
        //         //     'options' => [
        //         //         'pro_sl_nl' => [
                            
        //         //         ]
        //         //     ]
        //         // ]);
        //     }
            
        // } else {
        //     return response()->json(['status' => 404]);
        // }
        $product = Inv_product_detail::where('inv_pro_det_id', $request->pro_id)
        ->where('inv_pro_det_com_id', Auth::user()->au_company_id)
        ->where('inv_pro_det_status', 1)
        ->where('inv_pro_det_pro_warranty', '!=', 0)
        ->first();
        $product_exist_slno = Inv_product_inventory_detail::where('inv_pro_invdet_com_id',Auth::user()->au_company_id)
                                                           ->where('inv_pro_invdet_sell_id',NULL)
                                                           ->where('inv_pro_invdet_pro_id',$product->inv_pro_det_id)
                                                           ->where('inv_pro_invdet_sell_status',0)
                                                           ->get();
            // return $product->inv_pro_det_id;
            //     return $product_exist_slno;
    
        $product_sl_no = array();
        if(!empty($product)) {
            
            $row = Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_pro_id', $request->pro_id)
                ->where('inv_pro_temp_deal_type',2)
                ->first();
            if(!empty($row)) {
                $row->inv_pro_temp_pro_name = $product->inv_pro_det_pro_name;
                $row->inv_pro_temp_type_name = Inv_product_detail::get_type_name($product->inv_pro_det_id);
                
                $row->inv_pro_temp_unit_price = $request->pro_price;
                $row->inv_pro_temp_exp_date = $request->exp_date;
                $row->inv_pro_temp_updated_at = Carbon::now();

                $row->save();
                if($row->inv_pro_temp_slno != '') {
                    $product_sl_no = explode(',', $row->inv_pro_temp_slno);
                }

            } else {
                $pro_temp_add = new Inv_product_temporary;
                $pro_temp_add->inv_pro_temp_user_id = Auth::user()->au_id;
                $pro_temp_add->inv_pro_temp_pro_id = $product->inv_pro_det_id;
                $pro_temp_add->inv_pro_temp_pro_name = $product->inv_pro_det_pro_name;
                $pro_temp_add->inv_pro_temp_type_name = Inv_product_detail::get_type_name($product->inv_pro_det_id);
                $pro_temp_add->inv_pro_temp_qty = 0;
                
                $pro_temp_add->inv_pro_temp_unit_price = $request->pro_price;
                $pro_temp_add->inv_pro_temp_exp_date = $request->exp_date;
                $pro_temp_add->inv_pro_temp_slno = '';        
                $pro_temp_add->inv_pro_temp_deal_type = '2';//1=purchase,2=sale
                $pro_temp_add->inv_pro_temp_status = 1;
                $pro_temp_add->inv_pro_temp_type = 2; //1=non-warranty , 2= warranty
                $pro_temp_add->inv_pro_temp_updated_at = Carbon::now();
                $pro_temp_add->save();
            }
            
        } else {
            return response()->json(['status' => 404]);
        }

        return view('pages.ajax.warrenty_product_get_sl_no_inner_form', compact('product', 'product_sl_no','product_exist_slno'));
    }

    public function addWarrentyProductSlNo(Request $request)
    {
        $com = Auth::user()->au_company_id;
        
        $row = Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
            ->where('inv_pro_temp_pro_id', $request->pro_id)
            ->where('inv_pro_temp_deal_type',2)
            ->first();
        if(($request->sl_no == null) || ($request->sl_no == '')) {
            return false;
        }
        if(!empty($row)) {
            if($row->inv_pro_temp_slno == '') {
                $pre_sl_no = array();
            } else {
                $pre_sl_no = explode(',',$row->inv_pro_temp_slno);
            }
            if(in_array($request->sl_no, $pre_sl_no)) {
                return response()->json(['status' => 402]);//already added
            } 

            // all inventory id of requested product
            $all_inv_ids_of_this_product = Inv_product_inventory::where('inv_pro_inv_com_id', $com)
                            ->where('inv_pro_inv_prodet_id', $request->pro_id)
                            ->where('inv_pro_inv_deal_type', 1)
                            ->where('inv_pro_inv_tran_type', 1)
                            ->pluck('inv_pro_inv_id')
                            ->toArray();//deal_type=1=supplier, tran_type=1=buy-sale

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

            $row->inv_pro_temp_slno = implode(',', $pro_all_sl_no);
            $row->inv_pro_temp_qty = $content_quantity;
            $row->inv_pro_temp_updated_at = Carbon::now();
            $row->save();
            
            $content = $row;

            return view("pages.ajax.warrenty_product_sl_no_list", compact('content'));
        } else {
            return response()->json(['status' => 406]); //sl no not found
        }
    }

    public function removeWarrentyProductSlNo(Request $request)
    {
        $row = Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_pro_id', $request->pro_id)
                ->where('inv_pro_temp_deal_type',2)
                ->first();
        if(!empty($row)) {
            $pro_all_sl_no = explode(',',$row->inv_pro_temp_slno);

            if (($key = array_search($request->sl_no, $pro_all_sl_no)) !== false) {
                unset($pro_all_sl_no[$key]);
            }
            $content_quantity = count($pro_all_sl_no);

            $row->inv_pro_temp_slno = implode(',', $pro_all_sl_no);
            $row->inv_pro_temp_qty = $content_quantity;
            $row->inv_pro_temp_updated_at = Carbon::now();
            $row->save();

            $content = $row;

            return view("pages.ajax.warrenty_product_sl_no_list", compact('content'));
        } else {
            return response()->json(['status' => 406]); //sl no not found
        }
    }

    public function removecart(Request $request){
        Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_pro_id', $request->content_id)
                ->where('inv_pro_temp_deal_type',2)
                ->delete();
    }

    public function updatecart(Request $request){
        $row = Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_pro_id', $request->content_id)
                ->where('inv_pro_temp_deal_type',2)
                ->get();
        $row->inv_pro_temp_qty = $request->pro_qty;
        $row->inv_pro_temp_updated_at = Carbon::now();
        $row->save();
    }


    public function getCartContent()
    {
        Inv_product_temporary::where('inv_pro_temp_type',2)
                                ->where('inv_pro_temp_slno','')
                                ->delete();
        $cart_content = Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
        ->where('inv_pro_temp_deal_type',2)
        ->get();

        return view('pages.ajax.get_cart_content', compact('cart_content'));
    }




    // Submit cart form for sale
    public function cartSubmit(Request $request){
        // dd(request()->all());
        $request->validate([
            'customer' => 'required'
        ]);
        
        DB::beginTransaction();
        try {
            $com = Auth::user()->au_company_id;
            $user_id = Auth::user()->au_id;
            $cart_content = Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_deal_type',2)
                ->get();
            
            $last_pro_inv = Inv_product_inventory::where('inv_pro_inv_com_id', $com)
                ->where('inv_pro_inv_deal_type', 2)
                ->where('inv_pro_inv_tran_type', 1)
                ->orderBy('inv_pro_inv_id', 'DESC')
                ->first();
            if(!empty($last_pro_inv)) {
                $last_pro_inv_memo_no = $last_pro_inv->inv_pro_inv_invoice_no;                
                $last_data = substr($last_pro_inv_memo_no, 13);
                if(is_numeric($last_data)) {
                    $last_number = $last_data + 1;
                    $last_number_length = strlen($last_number);
                    if ($last_number_length < 6) {
                        $less_number = 6-$last_number_length;
                        $sl_prefix = "";
                        for ($x=0; $x <$less_number ; $x++) { 
                            $sl_prefix = $sl_prefix . "0";
                        }
                        $last_number = $sl_prefix . $last_number;
                    }
                    
                    $new_memo_no = "INV".$com.date('Y').($last_number);
                } else {
                    $new_memo_no = "INV".$com.date('Y')."000001";
                }
            } else {
                $new_memo_no = "INV".$com.date('Y')."000001";
            }
            
            foreach ($cart_content as $content) {
                $product_id = $content->inv_pro_temp_pro_id;
                $req_qty = $content->inv_pro_temp_qty;

                $check_product = Inv_product_detail::where('inv_pro_det_id', $product_id)
                    ->where('inv_pro_det_com_id', $com)
                    ->where('inv_pro_det_status', 1)
                    ->where('inv_pro_det_available_qty', '>=', $req_qty)
                    ->first();

                if(empty($check_product)) {
                    DB::rollback();
                    $msg = "Invalid/Insufficient ". $content->inv_pro_temp_pro_name ." Product. error-code: 1006";
                    return redirect()->back()->with(['sub_err' => $msg]);
                }
            }

            // insert in product inventory table
            $total_purchase_amount = 0;
            
            foreach ($cart_content as $content) {
                $product_id = $content->inv_pro_temp_pro_id;
                $req_qty = $content->inv_pro_temp_qty;
                $product_price = $content->inv_pro_temp_unit_price;
                $sub_total = $req_qty * $product_price;
                $total_purchase_amount += $sub_total;

                $check_product = Inv_product_detail::where('inv_pro_det_id', $product_id)
                    ->where('inv_pro_det_com_id', $com)
                    ->where('inv_pro_det_status', 1)
                    ->first();

                if($check_product->inv_pro_det_pro_warranty == 0) {
                    $req_qty = $content->inv_pro_temp_qty;
                }  else {
                    $req_sl_ids = explode(',', $content->inv_pro_temp_slno);
                    $k = 0;
                    $new_req_sl_ids =array();
                    foreach ($req_sl_ids as $sl_id) {
                        if($sl_id == '') {
                            unset($req_sl_ids[$k]);
                        } else {
                            $new_req_sl_ids[] = $req_sl_ids[$k];
                        }
                        $k++;
                    }

                    if (!isset($new_req_sl_ids)) {
                        $new_req_sl_ids = array();
                    }
                    $req_sl_ids = $new_req_sl_ids;
                    $req_qty = count($req_sl_ids);
                }
                

                $product_inventory = new Inv_product_inventory();
                $product_inventory->inv_pro_inv_com_id = $com;
                $product_inventory->inv_pro_inv_prodet_id = $content->inv_pro_temp_pro_id;
                $product_inventory->inv_pro_inv_party_id = $request->customer;
                $product_inventory->inv_pro_inv_invoice_no = $new_memo_no;
                $product_inventory->inv_pro_inv_qty = $req_qty;
                $product_inventory->inv_pro_inv_unit_price = $product_price;
                $product_inventory->inv_pro_inv_debit = $sub_total;
                $product_inventory->inv_pro_inv_credit = 0;
                $product_inventory->inv_pro_inv_issue_date = Carbon::now();
                $product_inventory->inv_pro_inv_tran_desc = "Sell Product";
                $product_inventory->inv_pro_inv_deal_type =  2;//2=customer
                $product_inventory->inv_pro_inv_tran_type =  1;//1=buy/sell product-buy
                $product_inventory->inv_pro_inv_status = 1;
                $product_inventory->inv_pro_inv_submit_by = $user_id;
                $product_inventory->inv_pro_inv_submit_at = Carbon::now();

                $product_inventory->save();

                for ($i=0; $i < $req_qty; $i++) { 
                    
                    if($check_product->inv_pro_det_pro_warranty == 0) {
                        
                    } else {
                        
                        
                        $request_sl_no = $req_sl_ids[$i];
                        if($request_sl_no == '') {
                            DB::rollback();
                            $msg = "Something went wrong to sell product. product name: ". $content->inv_pro_temp_pro_name .". error-code: 1003";
                            return redirect()->back()->with(['sub_err' => $msg]);
                        }

                        // search and update previous product details sales_status 
                        $all_inv_ids_of_this_product = Inv_product_inventory::where('inv_pro_inv_com_id', $com)
                            ->where('inv_pro_inv_prodet_id', $check_product->inv_pro_det_id)
                            ->where('inv_pro_inv_deal_type', 1)
                            ->where('inv_pro_inv_tran_type', 1)
                            ->pluck('inv_pro_inv_id')
                            ->toArray();
                            
                        $pre_pro_inv_details = Inv_product_inventory_detail::where('inv_pro_invdet_com_id', $com)
                            ->whereIn('inv_pro_invdet_proinv_id', $all_inv_ids_of_this_product)
                            ->where('inv_pro_invdet_sell_status', 0)
                            ->where('inv_pro_invdet_slno', $request_sl_no)
                            ->first();
                        
                        if(!empty($pre_pro_inv_details)) {
                            $pre_pro_inv_details->inv_pro_invdet_sell_status = 1;
                            $pre_pro_inv_details->inv_pro_invdet_proinv_sell_id = $product_inventory->inv_pro_inv_id;
                            $pre_pro_inv_details->inv_pro_invdet_sell_id = $request->customer;
                            
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
                if ($request->service > 0) {
                    $product_inventory = new Inv_product_inventory();
                    $product_inventory->inv_pro_inv_com_id = $com;
                    $product_inventory->inv_pro_inv_prodet_id = $content->inv_pro_temp_pro_id;
                    $product_inventory->inv_pro_inv_party_id = $request->customer;
                    $product_inventory->inv_pro_inv_invoice_no = $new_memo_no;
                    $product_inventory->inv_pro_inv_qty = $req_qty;
                    $product_inventory->inv_pro_inv_unit_price = $product_price;
                    $product_inventory->inv_pro_inv_debit = $request->service;
                    $product_inventory->inv_pro_inv_credit = 0;
                    $product_inventory->inv_pro_inv_issue_date = Carbon::now();
                    $product_inventory->inv_pro_inv_tran_desc = "Sell Product Service Charge";
                    $product_inventory->inv_pro_inv_deal_type =  2;//2=customer
                    $product_inventory->inv_pro_inv_tran_type =  10;//10=serviceCharges,11=deliveryCharges
                    $product_inventory->inv_pro_inv_status = 1;
                    $product_inventory->inv_pro_inv_submit_by = $user_id;
                    $product_inventory->inv_pro_inv_submit_at = Carbon::now();

                    $product_inventory->save();
                }
            if ($request->delivery > 0) {
                $product_inventory = new Inv_product_inventory();
                $product_inventory->inv_pro_inv_com_id = $com;
                $product_inventory->inv_pro_inv_prodet_id = $content->inv_pro_temp_pro_id;
                $product_inventory->inv_pro_inv_party_id = $request->customer;
                $product_inventory->inv_pro_inv_invoice_no = $new_memo_no;
                $product_inventory->inv_pro_inv_qty = $req_qty;
                $product_inventory->inv_pro_inv_unit_price = $product_price;
                $product_inventory->inv_pro_inv_debit = $request->delivery;
                $product_inventory->inv_pro_inv_credit = 0;
                $product_inventory->inv_pro_inv_issue_date = Carbon::now();
                $product_inventory->inv_pro_inv_tran_desc = "Sell Product Delivery Charge";
                $product_inventory->inv_pro_inv_deal_type =  2;//2=customer
                $product_inventory->inv_pro_inv_tran_type =  11;//10=serviceCharges,11=deliveryCharges
                $product_inventory->inv_pro_inv_status = 1;
                $product_inventory->inv_pro_inv_submit_by = $user_id;
                $product_inventory->inv_pro_inv_submit_at = Carbon::now();

                $product_inventory->save();
            }

            
            Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_deal_type',2)
                ->delete();

            
        } catch (\Exception $e) {
            DB::rollback();
            $msg = "Something went wrong to sell product. error-code: 1010".$e->getMessage();
            return redirect()->back()->with(['sub_err' => $msg]);
        }

        DB::commit();
        $msg = "Sell Products Successfully completed";
        return redirect()->route('buy.pro_sell')->with(['sub_success' => $msg,'print_invoice' => $new_memo_no]);
    }

    public function invTemporaryProduct(Request $request){
        $user = Auth::user()->au_id;
        $com = Auth::user()->au_company_id;
        $pro_temps = Inv_product_temporary::where('inv_pro_temp_user_id', $user)
                                            ->where('inv_pro_temp_deal_type',2)
                                            ->get();
        $service = $request->service;
        $delivery = $request->delivery;
        $pro_cus = Inv_customer::where('inv_cus_com_id', $com)
            ->where('inv_cus_id', $request->customer)
            ->first();
        return view('inventory.product_inventory.product_sell_invoice',compact('pro_temps','pro_cus','service','delivery'));
    }

}
