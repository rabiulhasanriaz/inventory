<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Cart;
use DB;
use Auth;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Inv_product_detail;
use App\Admin_user;
use App\Inv_supplier;
use App\Inv_product_inventory;
use App\Inv_product_inventory_detail;
use App\Inv_customer_inventory;
use App\Inv_product_temporary;



class InventoryPurchaseCartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Inv_product_detail::where('inv_pro_det_id', $request->pro_id)
            ->where('inv_pro_det_com_id', Auth::user()->au_company_id)
            ->where('inv_pro_det_status', 1)
            ->first();
        
        if(!empty($product)) {
            
            $row = Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_pro_id', $request->pro_id)
                ->where('inv_pro_temp_deal_type',1)
                ->first();
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
                $pro_temp_add->inv_pro_temp_slno = '';
                $pro_temp_add->inv_pro_temp_deal_type = '1';//1=purchase,2=sale
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
        $product = Inv_product_detail::where('inv_pro_det_id', $request->pro_id)
            ->where('inv_pro_det_com_id', Auth::user()->au_company_id)
            ->where('inv_pro_det_status', 1)
            ->where('inv_pro_det_pro_warranty', '!=', 0)
            ->first();
        
        $product_sl_no = array();
        if(!empty($product)) {
            
            $row = Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_pro_id', $request->pro_id)
                ->where('inv_pro_temp_deal_type',1)
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
                $pro_temp_add->inv_pro_temp_deal_type = '1';//1=purchase,2=sale
                $pro_temp_add->inv_pro_temp_status = 1;
                $pro_temp_add->inv_pro_temp_updated_at = Carbon::now();

                
                $pro_temp_add->save();
                

            }
            
        } else {
            return response()->json(['status' => 404]);
        }

        return view('pages.ajax.purchase_product.warrenty_product_get_sl_no_inner_form', compact('product', 'product_sl_no'));
    }

    public function addWarrentyProductSlNo(Request $request)
    {
        $com = Auth::user()->au_company_id;
        $row = Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_pro_id', $request->pro_id)
                ->where('inv_pro_temp_deal_type',1)
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
            $pro_all_sl_no = $pre_sl_no;
            $pro_all_sl_no[] = $request->sl_no;
            $content_quantity = count($pro_all_sl_no);
            
            $row->inv_pro_temp_slno = implode(',', $pro_all_sl_no);
            $row->inv_pro_temp_qty = $content_quantity;
            $row->inv_pro_temp_updated_at = Carbon::now();
            $row->save();
            

            $content = $row;

            return view("pages.ajax.purchase_product.warrenty_product_sl_no_list", compact('content'));
        } else {
            return response()->json(['status' => 406]); //sl no not found
        }
    }

    public function removeWarrentyProductSlNo(Request $request)
    {
        $row = Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_pro_id', $request->pro_id)
                ->where('inv_pro_temp_deal_type',1)
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

            return view("pages.ajax.purchase_product.warrenty_product_sl_no_list", compact('content'));
        } else {
            return response()->json(['status' => 406]); //sl no not found
        }
    }

    public function removecart(Request $request){
        
        Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_pro_id', $request->content_id)
                ->where('inv_pro_temp_deal_type',1)
                ->delete();
                
    }

    public function updatecart(Request $request){
        $row = Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_pro_id', $request->content_id)
                ->where('inv_pro_temp_deal_type',1)
                ->get();
        $row->inv_pro_temp_qty = $request->pro_qty;
        $row->inv_pro_temp_updated_at = Carbon::now();
        $row->save();
    }


    public function getCartContent()
    {
        $cart_content = Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
        ->where('inv_pro_temp_deal_type',1)
        ->get();

        return view('pages.ajax.purchase_product.get_cart_content', compact('cart_content'));
    }




    // Submit cart form for sale
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

            // start check memo no unique
            $check_memo = Inv_product_inventory::where('inv_pro_inv_com_id', $com)
                ->where('inv_pro_inv_invoice_no', $request->memo)
                ->first();
            if(!empty($check_memo)) {
                $msg = "Duplicate memo no";
                return redirect()->back()->with(['sub_err' => $msg]);
            }
            // end check memo no unique
            $cart_content = Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_deal_type',1)
                ->get();
            
            $new_memo_no = $request->memo;
            

            // insert in product inventory table
            $total_purchase_amount = 0;
            
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

                $api_sender = Admin_user::where('au_company_id',$com)
                                        ->where('au_user_type',4)
                                        ->first();

                $pro_sup = Inv_supplier::where('inv_sup_com_id', $com)
                ->where('inv_sup_id', $request->supplier)
                ->first();

                // $message = "Your Invoive has been Confirm!";
                // $message = urlencode($message);
                // $api_key = $api_sender->au_api_key;
                // $sender_id = $api_sender->au_sender_id;
                // $client = new \GuzzleHttp\Client();
                // $api_url = "http://sms.iglweb.com/api/v1/send?api_key=". $api_key ."&contacts=". $pro_sup->inv_sup_mobile ."&senderid=". $sender_id ."&msg=".$message;
                // $response = $client->request('GET', "$api_url");
                // $json_response = $response->getBody()->getContents();
                // $api_response = json_decode($json_response);

                // if ($api_response->code == "445000") {
                //     $type = 'success';
                //     $msg = "SMS Sending Successfully!";
                // } else if ($api_response->code == "445040") {
                //     $type = 'danger';
                //     $msg = "SMS Sending failed because of invalid API key";
                // } else if ($api_response->code == "445080") {
                //     $type = 'danger';
                //     $msg = "SMS Sending failed because of invalid Sender ID";
                // } else if ($api_response->code == "445120") {
                //     $type = 'danger';
                //     $msg = "SMS Sending failed because of your sms balance is low";
                // } else if ($api_response->code == "445110") {
                //     $type = 'danger';
                //     $msg = "SMS Sending failed because of Client number are invalid";
                // } else {
                //     $type = "danger";
                //     $msg = "SMS Sending failed because of ". $api_response->message;
                // }
                // session()->flash('type', $type);
                // session()->flash('message', $msg);

                
                // available quantity update in product detail table
                $after_submit_pro_available_quantity = $check_product->inv_pro_det_available_qty + $available_quantity;
                $check_product->inv_pro_det_available_qty = $after_submit_pro_available_quantity;

                $check_product->save();
            }

                

            Inv_product_temporary::where('inv_pro_temp_user_id', Auth::user()->au_id)
                ->where('inv_pro_temp_deal_type',1)
                ->delete();
            
        } catch (\Exception $e) {
            DB::rollback();
            $msg = "Something went wrong to sell product. error-code: 1010".$e->getMessage();
            return redirect()->back()->with(['sub_err' => $msg]);
        }

        DB::commit();
        $msg = "Sell Products Successfully completed";
        return redirect()->route('buy.buy-product-new')->with(['sub_success' => $msg , 'print_buy_invoice' => $new_memo_no]);
    }

    public function invTemporaryBuy(Request $request){
        $user = Auth::user()->au_id;
        $com = Auth::user()->au_company_id;
        $pro_temps = Inv_product_temporary::where('inv_pro_temp_user_id', $user)
                                            ->where('inv_pro_temp_deal_type',1)
                                            ->get();
        $pro_sup = Inv_supplier::where('inv_sup_com_id', $com)
            ->where('inv_sup_id', $request->supplier)
            ->first();
        return view('inventory.product_inventory.product_buy_invoice',compact('pro_temps','pro_sup'));
    }
}
