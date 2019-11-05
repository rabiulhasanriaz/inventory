<?php

namespace App\Http\Controllers\Inventory;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Inv_supplier;
use App\Inv_product_detail;
use App\Inv_supplier_product;

class InvSupplierController extends Controller
{
    //
    public function add_supplier(){
        return view('inventory.supplier.add');
    }

    public function add_supplier_submit(Request $request){
        $submit_by = Auth::user()->au_id;
        $company_id = Auth::user()->au_company_id;
        $submit_at = Carbon::now()->format('Y-m-d H:i:s');
        
        $request->validate([
            'company' => 'required',
            'address' => 'required',
            'person' => 'required',
            'mobile' => 'required',
            'type' => 'required',
        ]);

        $table = new Inv_supplier;
        $table->inv_sup_com_id = $company_id;
        $table->inv_sup_com_name = Input::get('company');
        $table->inv_sup_address = Input::get('address');
        $table->inv_sup_person = Input::get('person');
        $table->inv_sup_mobile = Input::get('mobile');
        $table->inv_sup_phone = Input::get('phone');
        $table->inv_sup_email = Input::get('email');
        $table->inv_sup_website = Input::get('website');
        $table->inv_sup_complain_num = Input::get('complain');
        $table->inv_sup_type = Input::get('type');
        $table->inv_sup_open_due_bal = Input::get('balance');
        $table->inv_sup_open_bal_type = Input::get('bal_type');
        $table->inv_sup_status = 1;
        $table->inv_sup_submit_by = $submit_by;
        $table->inv_sup_submit_at = $submit_at;
        $table->save();
        return redirect()->back()->with(['add_sup' => 'Supplier Added Successfully']);
    }

    public function supplier_list(){
        $company = Auth::user()->au_company_id;
        $suppliers = Inv_supplier::where('inv_sup_com_id',$company)
                                  ->where('inv_sup_status',1)
                                  ->get();
        return view('inventory.supplier.list',compact('suppliers'));
    }

    public function sup_edit_page($id){
        $com = Auth::user()->au_company_id;
        $sup = Inv_supplier::where('inv_sup_id',$id)->first();
        return view('inventory.supplier.supplier_edit',compact('sup'));
    }

    public function sup_edit(Request $request,$id){
        $supplier_up_by = Auth::user()->au_id;
        $supplier_up_at = Carbon::now()->format('Y-m-d H:i:s');
        $supplier_edit = Inv_supplier::find($id);
        $supplier_edit->inv_sup_com_name = $request->input('company');
        $supplier_edit->inv_sup_address = $request->input('address');
        $supplier_edit->inv_sup_person = $request->input('person');
        $supplier_edit->inv_sup_mobile = $request->input('mobile');
        $supplier_edit->inv_sup_email = $request->input('email');
        $supplier_edit->inv_sup_complain_num = $request->input('complain');
        $supplier_edit->inv_sup_type = $request->input('type');
        $supplier_edit->inv_sup_update_by = $supplier_up_by;
        $supplier_edit->inv_sup_update_at = $supplier_up_at;
        $supplier_edit->save();
        return redirect()->route('inventory.list')->with(['update' => 'Updated Successfully']);
    }

    public function sup_del(Request $request,$id){
        $supplier_delete = Inv_supplier::find($id);
        $supplier_delete->inv_sup_status = 0;
        $supplier_delete->save();
        return redirect()->back()->with(['del' => 'Deletd Successfully']);
    }

    public function supplier_list_show($id){
        $company = Auth::user()->au_company_id;
        $supplier_info = Inv_supplier::where('inv_sup_com_id',$company)
                                  ->where('inv_sup_id',$id)
                                  ->first();
        return view('inventory.supplier.supplier_list_show',compact('supplier_info'));
    }

    public function supplier_product_add(){
        $com = Auth::user()->au_company_id;
        $suppliers = Inv_supplier::where('inv_sup_com_id',$com)
                                 ->where('inv_sup_status',1)
                                 ->get();
        $products = Inv_product_detail::where('inv_pro_det_com_id',$com)
                                      ->where('inv_pro_det_status',1)
                                      ->get(); 
        return view('inventory.supplier_product.add',compact('suppliers','products'));
    }

    public function supplier_product_submit(Request $request){
        $company = Auth::user()->au_company_id;
        $dup = Inv_supplier_product::where('inv_sup_pro_com_id',$company)
                                 ->where('inv_sup_pro_pro_id',Input::get('product'))
                                 ->where('inv_sup_pro_sup_id',Input::get('supplier'))
                                 ->first();
        $submit_by = Auth::user()->au_id;
        $submit_at = Carbon::now()->format('Y-m-d H:i:s');
        $table = new Inv_supplier_product;
        $request->validate([
            'supplier' => 'required',
            'product' => 'required',
        ]);
        if (empty($dup)) {
            $table->inv_sup_pro_com_id = $company;
            $table->inv_sup_pro_sup_id = Input::get('supplier');
            $table->inv_sup_pro_pro_id = Input::get('product');
            $table->inv_sup_pro_status = 1;
            $table->inv_sup_pro_submit_by = $submit_by;
            $table->inv_sup_pro_submit_at = $submit_at;
            $table->save();
        }else{
            return redirect()->back()->with(['dup' => 'Duplicate Product!'])->withInput();
        }
        return redirect()->back()->with(['sup_pro' => 'Supplier Product Added Successfully']);
    }

    public function supplier_product_list(){
        $company = Auth::user()->au_company_id;
        $sup_pro = Inv_supplier_product::where('inv_sup_pro_com_id',$company)
                                       ->where('inv_sup_pro_status',1)
                                       ->get();
        return view('inventory.supplier_product.list',compact('sup_pro'));
    }

    public function supplier_product_detail($id){
        $sup_pro_detail = Inv_supplier_product::where('inv_sup_pro_id',$id)->first();
        return view('inventory.supplier_product.sup_pro_detail',compact('sup_pro_detail'));
    }

    public function supplier_product_delete($id){
        $sup_pro_del = Inv_supplier_product::find($id);
        $sup_pro_del->delete();
        return redirect()->back()->with(['sup_pro_del' => 'Supplier Product Deleted Successfully']);
    }

    public function supplier_pro_edit_page($id){
        $com = Auth::user()->au_company_id;
        $sup_edit = Inv_supplier_product::where('inv_sup_pro_id',$id)->first();
        $suppliers = Inv_supplier::where('inv_sup_com_id',$com)
                                 ->where('inv_sup_status',1)
                                 ->get();
        $products = Inv_product_detail::where('inv_pro_det_com_id',$com)
                                    ->where('inv_pro_det_status',1)
                                    ->get();                          
        return view('inventory.supplier_product.supplier_pro_edit',compact('sup_edit','suppliers','products'));
    }

    public function supplier_pro_edit(Request $request,$id){
        $update_by = Auth::user()->au_id;
        $update_at = Carbon::now()->format('Y-m-d H:i:s');
        $sup_pro_edit = Inv_supplier_product::find($id);
        $request->validate([
            'supplier' => 'required',
            'product' => 'required',
            'status' => 'required',
        ]);
        $sup_pro_edit->inv_sup_pro_sup_id = $request->supplier;
        $sup_pro_edit->inv_sup_pro_pro_id = $request->product;
        $sup_pro_edit->inv_sup_pro_status = $request->status;
        $sup_pro_edit->inv_sup_pro_update_by = $update_by;
        $sup_pro_edit->inv_sup_pro_update_at = $update_at;
        $sup_pro_edit->save();
        return redirect()->route('inventory.pro_list')->with(['sup_pro_edit' => 'Supplier Product Updated Successfully']);
    }
}
