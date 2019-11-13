<?php

namespace App\Http\Controllers\Inventory;

use PDF;
use Auth;
use Illuminate\Http\Request;
use App\Inv_product_inventory;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    public function buy_invoice_pdf(){
        return view('inventory.reports.buy_invoice');
    }
    // public function sell_invoice_pdf(){
    //     $com = Auth::user()->au_company_id;
    //     $customer = Inv_customer::where('inv_cus_com_id',$com)
    //                             ->where('inv_cus_status',1)
    //                             ->where('inv_cus_id',$id)
    //                             ->first();
    //     return view('inventory.reports.sell_invoice',compact('customer'));
    // }

    public function buy_invoice_pdf_generate(Request $request){
        $com = Auth::user()->au_company_id;
        $invoice = Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                        ->where('inv_pro_inv_deal_type',1)
                                        ->first();
        $pdf = PDF::loadView('inventory.reports.buy_invoice');
        return $pdf->download($invoice->inv_pro_inv_invoice_no.'.pdf');
    }

    public function sell_invoice_pdf_generate(Request $request){
        $com = Auth::user()->au_company_id;
        $invoice = Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                        ->where('inv_pro_inv_deal_type',2)
                                        ->first();
        $pdf = PDF::loadView('inventory.reports.buy_invoice');
        return $pdf->download($invoice->inv_pro_inv_invoice_no.'.pdf');
    }
}
