<?php

namespace App\Http\Controllers\Inventory;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Inv_customer;
use App\Sds_query_book;

class InvCustomerController extends Controller
{
    //
    public function customer_add_page(){
        return view('inventory.customer.add');
    }

    public function customer_add_submit(Request $request){
        $com = Auth::user()->au_company_id;
        $submit_by = Auth::user()->au_id;
        $submit_at = Carbon::now()->format('Y-m-d H:i:s');
        $table = new Inv_customer;
        $request->validate([
            'name' => 'required',
            'com_name' => 'required',
            'mobile' => 'required',
        ]);
        $table->inv_cus_com_id = $com;
        $table->inv_cus_name = Input::get('name');
        $table->inv_cus_com_name = Input::get('com_name');
        $table->inv_cus_mobile = Input::get('mobile');
        $table->inv_cus_email = Input::get('email');
        $table->inv_cus_address = Input::get('address');
        $table->inv_cus_website = Input::get('website');
        $table->inv_cus_status = 1;
        $table->inv_cus_submit_by = $submit_by;
        $table->inv_cus_submit_at = $submit_at;
        $table->save();
        return redirect()->back()->with(['cus_add' => 'Customer Added Successfully']);
    }

    public function customer_exist(Request $request){
        $exist = Inv_customer::where('inv_cus_mobile',$request->mobile)->first();
        
        if (!empty($exist)) {
            return 405;
        }else{
            $exist_cus = Sds_query_book::where('qb_mobile',$request->mobile)
                                    ->orWhere('qb_mobile1',$request->mobile)
                                    ->orWhere('qb_mobile2',$request->mobile)
                                    ->first();

            if(!empty($exist_cus)) {
                return $exist_cus;
            } else {
                return 200;
            }
        }
    }

    public function customer_list(){
        $com = Auth::user()->au_company_id;
        $inv_cuses = Inv_customer::where('inv_cus_com_id',$com)
                               ->where('inv_cus_status',1)
                               ->get();
        return view('inventory.customer.list',compact('inv_cuses'));
    }

    public function customer_detail($id){
        $cus_det = Inv_customer::where('inv_cus_id',$id)->first();
        return view('inventory.customer.customer_detail',compact('cus_det'));
    }

    public function customer_delete(Request $request,$id){
        $cus_del = Inv_customer::find($id);
        $cus_del->inv_cus_status = 0;
        $cus_del->save();
        return redirect()->back()->with(['cus_del' => 'Customer Deleted Successfully']);
    }

    public function customer_edit_page($id){
        $cus_info = Inv_customer::where('inv_cus_id',$id)->first();
        return view('inventory.customer.customer_edit',compact('cus_info'));
    }

    public function customer_edit(Request $request,$id){
        $update_by = Auth::user()->au_id;
        $update_at = Carbon::now()->format('Y-m-d H:i:s');
        $cus_edit = Inv_customer::find($id);
        $request->validate([
            'name' => 'required',
            'com_name' => 'required',
            'mobile' => 'required',
        ]);
        $cus_edit->inv_cus_name = $request->name;
        $cus_edit->inv_cus_com_name = $request->com_name;
        $cus_edit->inv_cus_mobile = $request->mobile;
        $cus_edit->inv_cus_email = $request->email;
        $cus_edit->inv_cus_address = $request->address;
        $cus_edit->inv_cus_website = $request->website;
        $cus_edit->inv_cus_update_by = $update_by;
        $cus_edit->inv_cus_update_at = $update_at;
        $cus_edit->save();
        return redirect()->route('customer.customer_list')->with(['cus_up' => 'Customer Updated Successfully']);
    }
}
