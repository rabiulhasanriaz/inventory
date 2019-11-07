<?php
namespace App\Http\Controllers\Inventory;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Inv_supplier;
use App\Inv_supplier_inventory;
use Carbon\Carbon;
use Session;
use App\Inv_acc_bank_info;
use App\Inv_acc_bank_statement;

class SupplierAccountsController extends Controller
{
	public function depositWithdrawForm()
	{
		$inv_suppliers=Inv_supplier::where('inv_sup_com_id',Auth::user()->au_company_id)
						->where('inv_sup_status',1)->get();
		return view('inventory.supplier.accounts.depositwithdraw',compact('inv_suppliers'));

	}
	public function depositWithdrawStore(Request $request)
	{
		try{
			
			$request->validate([
			            'trans_date' => 'required|date',
			            'amount'=>'required|number',
			            'supplier_id'=>'required',
			        ]);
			
			$inv_Sup_Invt=new Inv_supplier_inventory();
			$inv_Sup_Invt->inv_sup_inv_com_id=Auth::user()->au_company_id;
			$inv_Sup_Invt->inv_sup_inv_sup_id=$request->supplier_id;
			$inv_Sup_Invt->inv_sup_inv_tran_type=$request->trans_type;
			$inv_Sup_Invt->	inv_sup_inv_description=$request->reference;
			$inv_Sup_Invt->inv_sup_inv_issue_date=$request->trans_date;
			$inv_Sup_Invt->inv_sup_inv_status=1;
			$inv_Sup_Invt->inv_sup_inv_submit_by=Auth::user()->au_id;
			$inv_Sup_Invt->inv_sup_inv_submit_at=Carbon::now();

			if($request->trans_type==2)
			{
				//credit
				$inv_Sup_Invt->inv_sup_inv_debit=0;
				$inv_Sup_Invt->inv_sup_inv_credit=$request->amount;
				
			}
			else
				if($request->trans_type==3)
				{
					//Debit
					$inv_Sup_Invt->inv_sup_inv_debit=$request->amount;
					$inv_Sup_Invt->inv_sup_inv_credit=0;

				}

				if($inv_Sup_Invt->save())
				{
					Session::flash('msg','Transaction Successfull.');
					return redirect()->back();
				}
				else
				{
					Session::flash('errmsg','Transaction Failed.Please Try Again.');
				}

		}
		catch(Exceptionn $err)
		{
			Session::flash('errmsg','Something Went Wrong.');
			return redirect()->back();
		}
	}
	public function supplierPaymentForm()
	{
		$banks=Inv_acc_bank_info::where('inv_abi_company_id',Auth::user()->au_company_id)
				->where('inv_abi_status',1)->get();
		$inv_suppliers=Inv_supplier::where('inv_sup_com_id',Auth::user()->au_company_id)
						->where('inv_sup_status',1)->get();			
		return view('inventory.supplier.accounts.payment',compact('banks','inv_suppliers'));
	}
	public function supplierPaymentStore(Request $request)
	{
		try{

			$request->validate([
			            'trans_date' => 'required|date',
			            'amount'=>'required|number',
			            'supplier_id'=>'required',
			        ]);
			$inv_Sup_Invt=new Inv_supplier_inventory();
			$inv_Sup_Invt->inv_sup_inv_com_id=Auth::user()->au_company_id;
			$inv_Sup_Invt->inv_sup_inv_sup_id=$request->supplier_id;
			$inv_Sup_Invt->inv_sup_inv_debit=$request->amount;
			$inv_Sup_Invt->inv_sup_inv_credit=0;
			$inv_Sup_Invt->inv_sup_inv_tran_type=4;
			$inv_Sup_Invt->	inv_sup_inv_description=$request->reference;
			$inv_Sup_Invt->	inv_sup_inv_issue_date=$request->trans_date;
			$inv_Sup_Invt->inv_sup_inv_status=1;
			$inv_Sup_Invt->inv_sup_inv_submit_by=Auth::user()->au_id;
			$inv_Sup_Invt->inv_sup_inv_submit_at=Carbon::now();
			$inv_Sup_Invt->save();


			
			 $inv_Acc_Bank_Statement = new Inv_acc_bank_statement();
            $inv_Acc_Bank_Statement->inv_abs_company_id = Auth::user()->au_company_id;
            $inv_Acc_Bank_Statement->inv_abs_reference_id = $request->supplier_id;
            $inv_Acc_Bank_Statement->inv_abs_bank_id = $request->bank_id;
            $inv_Acc_Bank_Statement->inv_abs_inventory_id=$inv_Sup_Invt->inv_sup_inv_id;
            $inv_Acc_Bank_Statement->inv_abs_debit = $request->amount;
            $inv_Acc_Bank_Statement->inv_abs_credit = 0;
            $inv_Acc_Bank_Statement->inv_abs_transaction_date = $request->trans_date;
            $inv_Acc_Bank_Statement->inv_abs_description= $request->reference;
            $inv_Acc_Bank_Statement->inv_abs_voucher_no=Carbon::now()->format('YmdHis');
            $inv_Acc_Bank_Statement->inv_abs_status = 1;
            $inv_Acc_Bank_Statement->inv_abs_reference_type = 2; //2= Supplier Payment
            $inv_Acc_Bank_Statement->inv_abs_submit_at = Carbon::now();
            $inv_Acc_Bank_Statement->inv_abs_submit_by = Auth::user()->au_id;
            $inv_Acc_Bank_Statement->save();

            Session::flash('msg','Payment Successfull.');
            return redirect()->back();

		}
		catch(Exceptionn $err)
		{
			Session::flash('errmsg','Something Went Wrong.');
			return redirect()->back();
		}
	}
	public function supplierPaymentCollectionForm()
	{
		$banks=Inv_acc_bank_info::where('inv_abi_company_id',Auth::user()->au_company_id)
				->where('inv_abi_status',1)->get();
		$inv_suppliers=Inv_supplier::where('inv_sup_com_id',Auth::user()->au_company_id)
						->where('inv_sup_status',1)->get();			
		return view('inventory.supplier.accounts.paymentcollection',compact('banks','inv_suppliers'));
	}

	public function supplierPaymentCollectionStore(Request $request)
	{
		try{

			$request->validate([
			            'trans_date' => 'required|date',
			            'amount'=>'required|number',
			            'supplier_id'=>'required',
			        ]);
			
			$inv_Sup_Invt=new Inv_supplier_inventory();
			$inv_Sup_Invt->inv_sup_inv_com_id=Auth::user()->au_company_id;
			$inv_Sup_Invt->inv_sup_inv_sup_id=$request->supplier_id;
			$inv_Sup_Invt->inv_sup_inv_debit=0;
			$inv_Sup_Invt->inv_sup_inv_credit=$request->amount;
			$inv_Sup_Invt->inv_sup_inv_tran_type=5; // 5=payment collection 
			$inv_Sup_Invt->	inv_sup_inv_description=$request->reference;
			$inv_Sup_Invt->	inv_sup_inv_issue_date=$request->trans_date;
			$inv_Sup_Invt->inv_sup_inv_status=1;
			$inv_Sup_Invt->inv_sup_inv_submit_by=Auth::user()->au_id;
			$inv_Sup_Invt->inv_sup_inv_submit_at=Carbon::now();
			$inv_Sup_Invt->save();


			
			 $inv_Acc_Bank_Statement = new Inv_acc_bank_statement();
            $inv_Acc_Bank_Statement->inv_abs_company_id = Auth::user()->au_company_id;
            $inv_Acc_Bank_Statement->inv_abs_reference_id = $request->supplier_id;
            $inv_Acc_Bank_Statement->inv_abs_bank_id = $request->bank_id;
            $inv_Acc_Bank_Statement->inv_abs_inventory_id=$inv_Sup_Invt->inv_sup_inv_id;
            $inv_Acc_Bank_Statement->inv_abs_debit = 0;
            $inv_Acc_Bank_Statement->inv_abs_credit = $request->amount;
            $inv_Acc_Bank_Statement->inv_abs_transaction_date = $request->trans_date;
            $inv_Acc_Bank_Statement->inv_abs_description= $request->reference;
            $inv_Acc_Bank_Statement->inv_abs_voucher_no=Carbon::now()->format('YmdHis');
            $inv_Acc_Bank_Statement->inv_abs_status = 1;
            $inv_Acc_Bank_Statement->inv_abs_reference_type = 2; //2= Supplier Payment
            $inv_Acc_Bank_Statement->inv_abs_submit_at = Carbon::now();
            $inv_Acc_Bank_Statement->inv_abs_submit_by = Auth::user()->au_id;
            $inv_Acc_Bank_Statement->save();

            Session::flash('msg','Payment Successfull.');
            return redirect()->back();

		}
		catch(Exceptionn $err)
		{
			Session::flash('errmsg','Something Went Wrong.');
			return redirect()->back();
		}
	}
	public function showAccountStatement()
	{
		$inv_sups=Inv_supplier::where('inv_sup_com_id',Auth::user()->au_company_id)
					->where('inv_sup_status',1)->get();
		
		return view('inventory.supplier.accounts.accountstatement',compact('inv_sups'));
	}
	public function showAccountStatementDetails(Request $request)
	{
	
			if ($request->has('searchbtn')) {
				 $request->validate([
			            'start_date' => 'required',
			            'end_date' => 'required',
			        ]);
				$inv_sups=Inv_supplier_inventory::where('inv_sup_inv_sup_id',$request->supplier_id)
							->where('inv_sup_inv_com_id',Auth::user()->au_company_id)
							->where('inv_sup_inv_issue_date','>=',$request->start_date)
							->where('inv_sup_inv_issue_date','<=',$request->end_date)
							->where('inv_sup_inv_status',1)->get();
			}
			else
			{
				$inv_sups=Inv_supplier_inventory::where('inv_sup_inv_sup_id',$request->supplier_id)
							->where('inv_sup_inv_com_id',Auth::user()->au_company_id)
							->where('inv_sup_inv_status',1)->get();
				}

		return view('inventory.supplier.accounts.accountstatementdetails',compact('inv_sups'));
	}
	public function downloadAccountStatementDetails(Request $request)
	{

		if($request->start_date!=null && $request->end_date!=null)
		{
			
			$inv_sups=Inv_supplier_inventory::where('inv_sup_inv_sup_id',$request->supplier_id)
							->where('inv_sup_inv_com_id',Auth::user()->au_company_id)
							->where('inv_sup_inv_issue_date','>=',$request->start_date)
							->where('inv_sup_inv_issue_date','<=',$request->end_date)
							->where('inv_sup_inv_status',1)->get();
							//dd($inv_sups);
		}
		else
		{
			
			$inv_sups=Inv_supplier_inventory::where('inv_sup_inv_sup_id',$request->supplier_id)
							->where('inv_sup_inv_com_id',Auth::user()->au_company_id)
							->where('inv_sup_inv_status',1)->get();
		
		}
		
		return view('inventory.supplier.accounts.accountstatementdetailsdownload',compact('inv_sups'));
	}
}