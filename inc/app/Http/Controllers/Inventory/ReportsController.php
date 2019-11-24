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

    public function sell_reports(Request $request){    
        $com = Auth::user()->au_company_id;

        if ($request->has('searchbtn')) {
            $request->validate([
                    'start_date' => 'required',
                    'end_date' => 'required',
                ]);
            $sell_reports=Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                            ->where('inv_pro_inv_issue_date','>=',$request->start_date)
                                            ->where('inv_pro_inv_issue_date','<=',$request->end_date)
                                            ->where('inv_pro_inv_status',1)
                                            ->where('inv_pro_inv_deal_type',2)
                                            ->where('inv_pro_inv_tran_type',1)
                                            ->where('inv_pro_inv_confirm',0)
                                            ->groupBy('inv_pro_inv_invoice_no')
                                            ->get();
        }else{
            $sell_reports = Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                                ->where('inv_pro_inv_deal_type',2)
                                                ->where('inv_pro_inv_tran_type',1)
                                                ->where('inv_pro_inv_confirm',0)
                                                ->groupBy('inv_pro_inv_invoice_no')
                                                ->get();
        }
        return view('inventory.product_inventory.sell_reports',compact('sell_reports'));
    }

    public function sell_confirm_reports(Request $request){
        $com = Auth::user()->au_company_id;

        if ($request->has('searchbtn')) {
            $request->validate([
                    'start_date' => 'required',
                    'end_date' => 'required',
                ]);
            $sell_reports=Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                            ->where('inv_pro_inv_issue_date','>=',$request->start_date)
                                            ->where('inv_pro_inv_issue_date','<=',$request->end_date)
                                            ->where('inv_pro_inv_status',1)
                                            ->where('inv_pro_inv_deal_type',2)
                                            ->where('inv_pro_inv_tran_type',1)
                                            ->where('inv_pro_inv_confirm',1)
                                            ->groupBy('inv_pro_inv_invoice_no')
                                            ->get();
        }else{
            $sell_reports = Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                                ->where('inv_pro_inv_deal_type',2)
                                                ->where('inv_pro_inv_tran_type',1)
                                                ->where('inv_pro_inv_confirm',1)
                                                ->groupBy('inv_pro_inv_invoice_no')
                                                ->get();
        }
        return view('inventory.reports.sell_reports_confirm',compact('sell_reports'));
    }

    public function sell_report_ajax(Request $request){
        $detail_ajax = Inv_product_inventory::where('inv_pro_inv_invoice_no',$request->sell_id)->get();
        return view('pages.ajax.sell_reports_ajax',compact('detail_ajax'));

    }

    public function buy_reports(Request $request){
     
      
        $com = Auth::user()->au_company_id;
       
        if ($request->has('searchbtn')) {
            $request->validate([
                   'start_date' => 'required',
                   'end_date' => 'required',
               ]);
           $buy_reports=Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                            ->where('inv_pro_inv_issue_date','>=',$request->start_date)
                                            ->where('inv_pro_inv_issue_date','<=',$request->end_date)
                                            ->where('inv_pro_inv_status',1)
                                            ->where('inv_pro_inv_deal_type',1)
                                            ->where('inv_pro_inv_tran_type',1)
                                            ->where('inv_pro_inv_confirm',0)
                                            ->groupBy('inv_pro_inv_invoice_no')
                                            ->get();
       }else{
        $buy_reports = Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                            ->where('inv_pro_inv_deal_type',1)
                                            ->where('inv_pro_inv_tran_type',1)
                                            ->where('inv_pro_inv_confirm',0)
                                            ->groupBy('inv_pro_inv_invoice_no')
                                            ->get();
       }
        return view('inventory.product_inventory.buy_reports',compact('buy_reports'));
        
    }

    public function buy_reports_confirm(Request $request){

        $com = Auth::user()->au_company_id;
       
        if ($request->has('searchbtn')) {
            $request->validate([
                   'start_date' => 'required',
                   'end_date' => 'required',
               ]);
           $buy_reports=Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                            ->where('inv_pro_inv_issue_date','>=',$request->start_date)
                                            ->where('inv_pro_inv_issue_date','<=',$request->end_date)
                                            ->where('inv_pro_inv_status',1)
                                            ->where('inv_pro_inv_deal_type',1)
                                            ->where('inv_pro_inv_tran_type',1)
                                            ->where('inv_pro_inv_confirm',1)
                                            ->groupBy('inv_pro_inv_invoice_no')
                                            ->get();
       }else{
        $buy_reports = Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                            ->where('inv_pro_inv_deal_type',1)
                                            ->where('inv_pro_inv_tran_type',1)
                                            ->where('inv_pro_inv_confirm',1)
                                            ->groupBy('inv_pro_inv_invoice_no')
                                            ->get();
       }

        return view('inventory.reports.buy_reports_confirm',compact('buy_reports'));
    }

    public function buy_reports_ajax(Request $request){
        $detail_buy = Inv_product_inventory::where('inv_pro_inv_invoice_no',$request->buy_id)->get();
        return view('pages.ajax.purchase_product.buy_report_ajax',compact('detail_buy'));
    }

    public function buy_reports_pdf(Request $request,$invoice){
        $com = Auth::user()->au_company_id;

        $invoice_detail = Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                        ->where('inv_pro_inv_deal_type',1)
                                        ->where('inv_pro_inv_tran_type',1)
                                        ->where('inv_pro_inv_invoice_no',$invoice)
                                        ->first();

        $invoice = Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                        ->where('inv_pro_inv_deal_type',1)
                                        ->where('inv_pro_inv_tran_type',1)
                                        ->where('inv_pro_inv_invoice_no',$invoice)
                                        ->get();

        return view('inventory.reports.buy_print',compact('invoice','invoice_detail'));
        $pdf = PDF::loadView('inventory.reports.BuyIndividualInvoicePdf',compact('invoice','invoice_detail'));
        return $pdf->download($invoice_detail->inv_pro_inv_invoice_no.'.pdf');
    }

    public function sell_reports_pdf(Request $request,$invoice){
        $com = Auth::user()->au_company_id;

        $invoice_detail = Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                        ->where('inv_pro_inv_deal_type',2)
                                        ->where('inv_pro_inv_tran_type',1)
                                        ->where('inv_pro_inv_invoice_no',$invoice)
                                        ->first();

        $invoice = Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                        ->where('inv_pro_inv_deal_type',2)
                                        ->where('inv_pro_inv_tran_type',1)
                                        ->where('inv_pro_inv_invoice_no',$invoice)
                                        ->get();
        return view('inventory.reports.sell_print',compact('invoice','invoice_detail'));

        $pdf = PDF::loadView('inventory.reports.SellIndividualInvoicePdf',compact('invoice','invoice_detail'));
        return $pdf->download($invoice_detail->inv_pro_inv_invoice_no.'.pdf');
    }

    public function sell_confirm(Request $request,$invoice){
        Inv_product_inventory::where('inv_pro_inv_invoice_no',$invoice)
            ->update(['inv_pro_inv_confirm' => 1]);

        return redirect()->back()->with(['confirm' => 'Product Confirm Successfully']);
    }

    public function buy_confirm(Request $request,$invoice){
        Inv_product_inventory::where('inv_pro_inv_invoice_no',$invoice)
        ->update(['inv_pro_inv_confirm' => 1]);
        return redirect()->back()->with(['buy_confirm' => 'Buy Confirm Successfully']);
    }

    public function sellReportsDownload(Request $request){
        if($request->start_date!=null && $request->end_date!=null)
		{
			
			$SellStatments=Inv_product_inventory::where('inv_pro_inv_com_id',Auth::user()->au_company_id)
                                                ->where('inv_pro_inv_issue_date','>=',$request->start_date)
                                                ->where('inv_pro_inv_issue_date','<=',$request->end_date)
                                                ->where('inv_pro_inv_status',1)
                                                ->where('inv_pro_inv_deal_type',2)
                                                ->where('inv_pro_inv_tran_type',1)
                                                ->groupBy('inv_pro_inv_invoice_no')
                                                ->get();
							
		}
		else
		{
			
			$SellStatments=Inv_product_inventory::where('inv_pro_inv_com_id',Auth::user()->au_company_id)
                                                ->where('inv_pro_inv_status',1)
                                                ->where('inv_pro_inv_deal_type',2)
                                                ->where('inv_pro_inv_tran_type',1)
                                                ->groupBy('inv_pro_inv_invoice_no')
                                                ->get();
		
		}
		
		//return view('inventory.customer.accounts.accountstatementdetailsdownload',compact('inv_custs'));
		$pdf = PDF::loadView('inventory.reports.sell_statement_download',compact('SellStatments'));
		return $pdf->download('sell_statements.pdf');
    }

    public function BuyReportsDownload(Request $request){
        if($request->start_date != null && $request->end_date != null)
		{
			
			$buyStatments=Inv_product_inventory::where('inv_pro_inv_com_id',Auth::user()->au_company_id)
                                                ->where('inv_pro_inv_issue_date','>=',$request->start_date)
                                                ->where('inv_pro_inv_issue_date','<=',$request->end_date)
                                                ->where('inv_pro_inv_status',1)
                                                ->where('inv_pro_inv_deal_type',1)
                                                ->where('inv_pro_inv_tran_type',1)
                                                ->groupBy('inv_pro_inv_invoice_no')
                                                ->get();
		}
		else
		{
			$buyStatments=Inv_product_inventory::where('inv_pro_inv_com_id',Auth::user()->au_company_id)
                                                ->where('inv_pro_inv_status',1)
                                                ->where('inv_pro_inv_deal_type',1)
                                                ->where('inv_pro_inv_tran_type',1)
                                                ->groupBy('inv_pro_inv_invoice_no')
                                                ->get();
		}
		
		//return view('inventory.customer.accounts.accountstatementdetailsdownload',compact('inv_custs'));
		$pdf = PDF::loadView('inventory.reports.buy_statement_download',compact('buyStatments'));
		return $pdf->download('buy_statements.pdf');
    }

    public function buyReturnInvoicePrint(Request $request,$invoice){
        $com = Auth::user()->au_company_id;

        $invoice_detail = Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                        ->where('inv_pro_inv_deal_type',1)
                                        ->where('inv_pro_inv_tran_type',2)
                                        ->where('inv_pro_inv_invoice_no',$invoice)
                                        ->first();

        $invoice = Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                        ->where('inv_pro_inv_deal_type',1)
                                        ->where('inv_pro_inv_tran_type',2)
                                        ->where('inv_pro_inv_invoice_no',$invoice)
                                        ->get();

        return view('inventory.reports.buy_return_print',compact('invoice','invoice_detail'));
        $pdf = PDF::loadView('inventory.reports.BuyIndividualInvoicePdf',compact('invoice','invoice_detail'));
        return $pdf->download($invoice_detail->inv_pro_inv_invoice_no.'.pdf');
    }

    public function sellReturnInvoicePrint(Request $request,$invoice){
        $com = Auth::user()->au_company_id;

        $invoice_detail = Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                        ->where('inv_pro_inv_deal_type',2)
                                        ->where('inv_pro_inv_tran_type',2)
                                        ->where('inv_pro_inv_invoice_no',$invoice)
                                        ->first();

        $invoice = Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                        ->where('inv_pro_inv_deal_type',2)
                                        ->where('inv_pro_inv_tran_type',2)
                                        ->where('inv_pro_inv_invoice_no',$invoice)
                                        ->get();
        return view('inventory.reports.sell_return_print',compact('invoice','invoice_detail'));

        $pdf = PDF::loadView('inventory.reports.SellIndividualInvoicePdf',compact('invoice','invoice_detail'));
        return $pdf->download($invoice_detail->inv_pro_inv_invoice_no.'.pdf');
    }

}
