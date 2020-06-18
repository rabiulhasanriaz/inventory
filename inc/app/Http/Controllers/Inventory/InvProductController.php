<?php

namespace App\Http\Controllers\Inventory;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Inv_product_groups;
use App\Inv_product_type;
use App\Inv_product_detail;
use App\Inv_supplier;

class InvProductController extends Controller
{
    //
    public function product_add(){
        $com = Auth::user()->au_company_id;
        $pro_grp = Inv_product_groups::where('inv_pro_grp_com_id',$com)
                                         ->where('inv_pro_grp_status',1)
                                         ->get();
        $suppliers = Inv_supplier::where('inv_sup_com_id', $com)
                                        ->where('inv_sup_status', 1)
                                        ->where('inv_sup_type',3)
                                        ->orderBy('inv_sup_com_name','ASC')
                                        ->get();
        return view('inventory.product.add',compact('pro_grp', 'suppliers'));
    }

    public function product_detail_submit(Request $request){
        // dd($request->all());
        $message = [
            'pro_desc.max' => 'Desciption Need To be Maximum 50 Characters Long',
        ];
        $request->validate([
            'type' => 'required',
            'pro_name' => 'required',
            'pro_buy' => 'required',
            'pro_sell' => 'required',
            'pro_desc' => 'max:50',
        ],$message);
            try{
            $submit_by = Auth::user()->au_id;
            $submit_at = Carbon::now()->format('Y-m-d H:i:s');
            $com = Auth::user()->au_company_id;
            $inv_pro_det = new Inv_product_detail;
            $inv_pro_det->inv_pro_det_com_id = $com;
            $inv_pro_det->inv_pro_det_type_id = Input::get('type');
            $inv_pro_det->inv_pro_det_sup = implode('-',Input::get('supplier'));
            $inv_pro_det->inv_pro_det_pro_name = Input::get('pro_name');
            $inv_pro_det->inv_pro_det_buy_price = Input::get('pro_buy');
            $inv_pro_det->inv_pro_det_sell_price = Input::get('pro_sell');
            $inv_pro_det->inv_pro_det_pro_warranty = Input::get('pro_warranty');
            $inv_pro_det->inv_pro_det_pro_description = Input::get('pro_desc');
            $inv_pro_det->inv_pro_det_short_qty = Input::get('pro_short');
            $inv_pro_det->inv_pro_det_status = 1;
            $inv_pro_det->inv_pro_det_submit_by = $submit_by;
            $inv_pro_det->inv_pro_det_submit_at = $submit_at;
            $inv_pro_det->save();
            }catch(\Exception $e){
                return redirect()->back()->with(['err' => $e->getMessage()]);
            }
       
        
        return redirect()->back()->with(['det_add' => 'Product Detail Added Successfully']);
    }

    public function show_pro_grp_by_ajax(Request $request) {
        $types = Inv_product_type::where('inv_pro_type_grp_id', $request->grp_id)
                                ->where('inv_pro_type_status',1)
                                ->get();
        return view('pages.ajax.product_group', compact('types'));
    }

    public function product_list(){
        $com = Auth::user()->au_company_id;
        $pro_det = Inv_product_detail::where('inv_pro_det_com_id',$com)
                                     ->where('inv_pro_det_status',1)
                                     ->get();
        return view('inventory.product.list',compact('pro_det'));
    }

    public function product_detail_show($id){
        $details = Inv_product_detail::where('inv_pro_det_id',$id)->first();
        return view('inventory.product.product_detail',compact('details'));
    }

    public function product_delete(Request $request,$id){
        $pro_del = Inv_product_detail::find($id);
        $pro_del->inv_pro_det_status = 0;
        $pro_del->save();
        return redirect()->back()->with(['pro_del' => 'Product Delete Successfully']);
    }

    public function product_edit_page($id){
        $product = Inv_product_detail::where('inv_pro_det_id',$id)->first();
        $com = Auth::user()->au_company_id;
        $pro_det_grp = Inv_product_groups::where('inv_pro_grp_com_id',$com)
                                         ->where('inv_pro_grp_status',1)
                                         ->get();    
        $types = Inv_product_type::where('inv_pro_type_com_id',$com)
                                   ->where('inv_pro_type_grp_id',$product->type_info['inv_pro_type_grp_id'])
                                   ->where('inv_pro_type_status',1)
                                   ->get();
        $suppliers = Inv_supplier::where('inv_sup_com_id', $com)
                                    ->where('inv_sup_status', 1)
                                    ->get();
        return view('inventory.product.pro_edit',compact('pro_det_grp','types','product','suppliers'));
    }

    public function pro_edit(Request $request,$id){
        $pro_edit = Inv_product_detail::find($id);
        $update_by = Auth::user()->au_id;
        $update_at = Carbon::now()->format('Y-m-d H:i:s');
        $request->validate([
            'type' => 'required',
            'pro_name' => 'required',
            'pro_sell' => 'required',
            'pro_buy' => 'required',
        ]);
        $pro_edit->inv_pro_det_type_id = $request->type;
        $pro_edit->inv_pro_det_sup = implode('-',$request->supplier);
        $pro_edit->inv_pro_det_pro_name = $request->pro_name;
        $pro_edit->inv_pro_det_pro_description = $request->pro_desc;
        $pro_edit->inv_pro_det_buy_price = $request->pro_buy;
        $pro_edit->inv_pro_det_sell_price = $request->pro_sell;
        $pro_edit->inv_pro_det_pro_warranty = $request->warranty_change;
        $pro_edit->inv_pro_det_short_qty = $request->pro_short;
        $pro_edit->inv_pro_det_update_by = $update_by;
        $pro_edit->inv_pro_det_update_at = $update_at;

        $pro_edit->save();
        return redirect()->route('inventory.product_list')->with(['det_up' => 'Product Detail Updated Successfully']);
    }

    public function product_group(){
        return view('inventory.product_group.add');
    }

    public function product_group_submit(Request $request){
        $company = Auth::user()->au_company_id;
        $submit_by = Auth::user()->au_id;
        $submit_at = Carbon::now()->format('Y-m-d H:i:s');

        $request->validate([
            'type' => 'required',
        ]);

        $table = new Inv_product_groups;
        $table->inv_pro_grp_com_id = $company;
        $table->inv_pro_grp_name = Input::get('type');
        $table->inv_pro_grp_status = 1;
        $table->inv_pro_grp_submit_by = $submit_by;
        $table->inv_pro_grp_submit_at = $submit_at;
        $table->save();

        return redirect()->back()->with(['type' => 'Product Type Added Successfully.']);
    }

    public function product_group_list(){
        $company_id = Auth::user()->au_company_id;
        $types = Inv_product_groups::where('inv_pro_grp_com_id',$company_id)
                                        ->get();
        return view('inventory.product_group.list',compact('types'));
    }

    public function product_group_show($id){
        $company = Auth::user()->au_company_id;
        $pro_grp_info = Inv_product_groups::where('inv_pro_grp_com_id',$company)
                                              ->where('inv_pro_grp_id',$id)
                                              ->first();
        return view('inventory.product_group.pro_grp_show',compact('pro_grp_info'));
    }

    public function pro_grp_del(Request $request,$id){
        $pro_grp_delete = Inv_product_groups::find($id);
        $pro_grp_delete->inv_pro_grp_status = 0;
        $pro_grp_delete->save();
        return redirect()->back()->with(['del_grp' => 'Deletd Successfully']);
    }

    public function pro_grp_edit_page($id){
        $com = Auth::user()->au_company_id;
        $pro_grp = Inv_product_groups::where('inv_pro_grp_id',$id)->first();
        return view('inventory.product_group.pro_grp_edit',compact('pro_grp'));
    }

    public function pro_grp_edit(Request $request,$id){
        $pro_grp_edit = Inv_product_groups::find($id);
        $update_by = Auth::user()->au_id;
        $update_at = Carbon::now()->format('Y-m-d H:i:s');
        $request->validate([
            'type' => 'required',
        ]);
        $pro_grp_edit->inv_pro_grp_name = $request->input('type');
        $pro_grp_edit->inv_pro_grp_status = $request->input('status');
        $pro_grp_edit->inv_pro_grp_updated_at = $update_at;
        $pro_grp_edit->inv_pro_grp_updated_by = $update_by;
        $pro_grp_edit->save();
        return redirect()->route('inventory.group_list')->with(['up_msg' => 'Updated Successfully']);
    }

    public function product_type_add(){
        $com = Auth::user()->au_company_id;
        $pro_grp = Inv_product_groups::where('inv_pro_grp_com_id',$com)
                                         ->where('inv_pro_grp_status',1)
                                         ->get();
        return view('inventory.type.add',compact('pro_grp'));
    }

    public function product_type_submit(Request $request){
        $company = Auth::user()->au_company_id;
        $submit_by = Auth::user()->au_id;
        $submit_at = Carbon::now()->format('Y-m-d H:i:s');
        $table = new Inv_product_type;
        $request->validate([
            'group' => 'required',
            'type' => 'required|unique:inv_product_types,inv_pro_type_name',
        ]);
        $table->inv_pro_type_com_id = $company;
        $table->inv_pro_type_grp_id = Input::get('group');
        $table->inv_pro_type_name = Input::get('type');
        $table->inv_pro_type_status = 1;
        $table->inv_pro_type_submit_by = $submit_by;
        $table->inv_pro_type_submit_at = $submit_at;
        $table->save();

        return redirect()->back()->with(['pro_type' => 'Product Type Added Successfully']);
    }

    public function product_type_list(){
        $com = Auth::user()->au_company_id;
        $pro_type = Inv_product_type::where('inv_pro_type_com_id',$com)
                                      ->where('inv_pro_type_status',1)
                                      ->get();
        return view('inventory.type.list',compact('pro_type'));
    }

    public function product_type_show($id){
        $type_info = Inv_product_type::where('inv_pro_type_id',$id)->first();
        return view('inventory.type.type_detail',compact('type_info'));
    }

    public function product_type_delete(Request $request,$id){
        $type_del = Inv_product_type::find($id);
        $type_del->inv_pro_type_status = 0;
        $type_del->save();
        return redirect()->route('inventory.type_list')->with(['delete_msg' => 'Product type Deleted Successfully']);
    }

    public function pro_type_edit_page($id){
        $com = Auth::user()->au_company_id;
        $mod_edit = Inv_product_type::where('inv_pro_type_id',$id)->first();
        $pro_grp = Inv_product_groups::where('inv_pro_grp_com_id',$com)
                                         ->where('inv_pro_grp_status',1)
                                         ->get();
        return view('inventory.type.type_edit_page',compact('mod_edit','pro_grp'));
    }

    public function pro_type_edit(Request $request,$id){
        $update_by = Auth::user()->au_id;
        $update_at = Carbon::now()->format('Y-m-d H:i:s');
        $type_edit = Inv_product_type::find($id);
        $request->validate([
            'group' => 'required',
            'type' => 'required',
        ]);
        $type_edit->inv_pro_type_grp_id = $request->input('group');
        $type_edit->inv_pro_type_name = $request->input('type');
        $type_edit->inv_pro_type_updated_by = $update_by;
        $type_edit->inv_pro_type_updated_at = $update_at;
        $type_edit->save();
        return redirect()->route('inventory.type_list')->with(['mod_up' => 'Product type Updated Successfully']);
    }


    public function product_short_notify(Request $request){
        $com = Auth::user()->au_company_id;
        
            $pro_det = Inv_product_detail::where('inv_pro_det_com_id',$com)
                                        ->where('inv_pro_det_status',1)
                                        ->whereRaw('inv_pro_det_available_qty < inv_pro_det_short_qty')
                                        ->where('inv_pro_det_short_qty','!=','')
                                        ->get();
            // dd($pro_det);
        
                                
        return view('inventory.product.short_quantity',compact('pro_det'));
    }
}
