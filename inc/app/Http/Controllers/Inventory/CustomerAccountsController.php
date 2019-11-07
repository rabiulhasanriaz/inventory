<?php
namespace App\Http\Controllers\Inventory;

use Auth;
use Illuminate\Http\Request;
use Illuminate\cusport\Facades\Input;
use App\Http\Controllers\Controller;
use App\Inv_customer;
use App\Inv_customer_inventory;
use Carbon\Carbon;
use Session;
use App\Inv_acc_bank_info;
use App\Inv_acc_bank_statement;

class CustomerAccountsController extends Controller
{
	public function depositWithdrawForm()
	{
		$inv_customers=Inv_customer::where('inv_cus_com_id',Auth::user()->au_company_id)
						->where('inv_cus_status',1)->get();
		return view('inventory.customer.accounts.depositwithdraw',compact('inv_customers'));

	}
	public function depositWithdrawStore(Request $request)
	{
		try{
			  $request->validate([
			            'trans_date' => 'required|date',
			            'trans_type' => 'required',
			            'customer_id'=>'required',
			        ]);
			$inv_Cus_Invt=new Inv_customer_inventory();
			$inv_Cus_Invt->inv_cus_inv_com_id=Auth::user()->au_company_id;
			$inv_Cus_Invt->inv_cus_inv_cus_id=$request->customer_id;
			$inv_Cus_Invt->inv_cus_inv_tran_type=$request->trans_type;
			$inv_Cus_Invt->inv_cus_inv_description=$request->reference;
			$inv_Cus_Invt->inv_cus_inv_issue_date=$request->trans_date;
			$inv_Cus_Invt->inv_cus_inv_status=1;
			$inv_Cus_Invt->inv_cus_inv_submit_by=Auth::user()->au_id;
			$inv_Cus_Invt->inv_cus_inv_submit_at=Carbon::now();

			if($request->trans_type==2)
			{
				//credit
				$inv_Cus_Invt->inv_cus_inv_debit=0;
				$inv_Cus_Invt->inv_cus_inv_credit=$request->amount;
				
			}
			else
				if($request->trans_type==3)
				{
					//Debit
					$inv_Cus_Invt->inv_cus_inv_debit=$request->amount;
					$inv_Cus_Invt->inv_cus_inv_credit=0;

				}

				if($inv_Cus_Invt->save())
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
	public function customerPaymentForm()
	{
		$banks=Inv_acc_bank_info::where('inv_abi_company_id',Auth::user()->au_company_id)
				->where('inv_abi_status',1)->get();
		$inv_customers=Inv_customer::where('inv_cus_com_id',Auth::user()->au_company_id)
						->where('inv_cus_status',1)->get();			
		return view('inventory.customer.accounts.payment',compact('banks','inv_customers'));
	}

	public function customerPaymentStore(Request $request)
	{
		try{
			$request->validate([
			            'trans_date' => 'required|date',
			            
			            'customer_id'=>'required',
			            	'amount'=>'required|number'
			        ]);

			$inv_Cus_Invt=new Inv_customer_inventory();
			$inv_Cus_Invt->inv_cus_inv_com_id=Auth::user()->au_company_id;
			$inv_Cus_Invt->inv_cus_inv_cus_id=$request->customer_id;
			$inv_Cus_Invt->inv_cus_inv_debit=0;
			$inv_Cus_Invt->inv_cus_inv_credit=$request->amount;
			$inv_Cus_Invt->inv_cus_inv_tran_type=4;
			$inv_Cus_Invt->inv_cus_inv_description=$request->reference;
			$inv_Cus_Invt->	inv_cus_inv_issue_date=$request->trans_date;
			$inv_Cus_Invt->inv_cus_inv_status=1;
			$inv_Cus_Invt->inv_cus_inv_submit_by=Auth::user()->au_id;
			$inv_Cus_Invt->inv_cus_inv_submit_at=Carbon::now();
			$inv_Cus_Invt->save();


			
			 $inv_Acc_Bank_Statement = new Inv_acc_bank_statement();
            $inv_Acc_Bank_Statement->inv_abs_company_id = Auth::user()->au_company_id;
            $inv_Acc_Bank_Statement->inv_abs_reference_id = $request->customer_id;
            $inv_Acc_Bank_Statement->inv_abs_bank_id = $request->bank_id;
            $inv_Acc_Bank_Statement->inv_abs_inventory_id=$inv_Cus_Invt->inv_cus_inv_id;
            $inv_Acc_Bank_Statement->inv_abs_debit = 0;
            $inv_Acc_Bank_Statement->inv_abs_credit =$request->amount ;
            $inv_Acc_Bank_Statement->inv_abs_transaction_date = $request->trans_date;
            $inv_Acc_Bank_Statement->inv_abs_description = $request->reference;
            $inv_Acc_Bank_Statement->inv_abs_voucher_no=Carbon::now()->format('YmdHis');
            $inv_Acc_Bank_Statement->inv_abs_status = 1;
            $inv_Acc_Bank_Statement->inv_abs_reference_type = 1; //1= Customer Payment Collection
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
	public function customerPaymentRedfundForm()
	{
		$banks=Inv_acc_bank_info::where('inv_abi_company_id',Auth::user()->au_company_id)
				->where('inv_abi_status',1)->get();
		$inv_customers=Inv_customer::where('inv_cus_com_id',Auth::user()->au_company_id)
						->where('inv_cus_status',1)->get();			
		return view('inventory.customer.accounts.paymentrefund',compact('banks','inv_customers'));
	}
	public function customerPaymentRedfundStore(Request $request)
	{
		try{

			$request->validate([
			            'trans_date' => 'required|date',
			            'amount'=>'required|number',
			            'customer_id'=>'required',
			        ]);

			$inv_Cus_Invt=new Inv_customer_inventory();
			$inv_Cus_Invt->inv_cus_inv_com_id=Auth::user()->au_company_id;
			$inv_Cus_Invt->inv_cus_inv_cus_id=$request->customer_id;
			$inv_Cus_Invt->inv_cus_inv_debit=$request->amount;
			$inv_Cus_Invt->inv_cus_inv_credit=0;
			$inv_Cus_Invt->inv_cus_inv_tran_type=5; //5=payment refunds
			$inv_Cus_Invt->inv_cus_inv_description=$request->reference;
			$inv_Cus_Invt->inv_cus_inv_issue_date=$request->trans_date;
			$inv_Cus_Invt->inv_cus_inv_status=1;
			$inv_Cus_Invt->inv_cus_inv_submit_by=Auth::user()->au_id;
			$inv_Cus_Invt->inv_cus_inv_submit_at=Carbon::now();
			$inv_Cus_Invt->save();


			
			 $inv_Acc_Bank_Statement = new Inv_acc_bank_statement();
            $inv_Acc_Bank_Statement->inv_abs_company_id = Auth::user()->au_company_id;
            $inv_Acc_Bank_Statement->inv_abs_reference_id = $request->customer_id;
            $inv_Acc_Bank_Statement->inv_abs_bank_id = $request->bank_id;
            $inv_Acc_Bank_Statement->inv_abs_inventory_id=$inv_Cus_Invt->inv_cus_inv_id;
            $inv_Acc_Bank_Statement->inv_abs_debit = $request->amount;
            $inv_Acc_Bank_Statement->inv_abs_credit =0;
            $inv_Acc_Bank_Statement->inv_abs_transaction_date = $request->trans_date;
            $inv_Acc_Bank_Statement->inv_abs_description = $request->reference;
            $inv_Acc_Bank_Statement->inv_abs_voucher_no=Carbon::now()->format('YmdHis');
            $inv_Acc_Bank_Statement->inv_abs_status = 1;
            $inv_Acc_Bank_Statement->inv_abs_reference_type = 1; //1= Customer Payment Collection
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
		$inv_cus_invts=Inv_customer::where('inv_cus_com_id',Auth::user()->au_company_id)
					->where('inv_cus_status',1)->get();
		
		return view('inventory.customer.accounts.accountstatement',compact('inv_cus_invts'));
	}
	public function showAccountStatementDetails(Request $request)
	{
		if ($request->has('searchbtn')) {
				 $request->validate([
			            'start_date' => 'required',
			            'end_date' => 'required',
			        ]);
				$inv_custs=Inv_customer_inventory::where('inv_cus_inv_cus_id',$request->customer_id)
							->where('inv_cus_inv_com_id',Auth::user()->au_company_id)
							->where('inv_cus_inv_issue_date','>=',$request->start_date)
							->where('inv_cus_inv_issue_date','<=',$request->end_date)
							->where('inv_cus_inv_status',1)->get();
			}
			else
			{
				$inv_custs=Inv_customer_inventory::where('inv_cus_inv_cus_id',$request->customer_id)
							->where('inv_cus_inv_com_id',Auth::user()->au_company_id)
							->where('inv_cus_inv_status',1)->get();
				}
		return view('inventory.customer.accounts.accountstatementdetails',compact('inv_custs'));
	}
	public function downloadAccountStatementDetails(Request $request)
	{
		if($request->start_date!=null && $request->end_date!=null)
		{
			
			$inv_custs=Inv_customer_inventory::where('inv_cus_inv_cus_id',$request->customer_id)
							->where('inv_cus_inv_com_id',Auth::user()->au_company_id)
							->where('inv_cus_inv_issue_date','>=',$request->start_date)
							->where('inv_cus_inv_issue_date','<=',$request->end_date)
							->where('inv_cus_inv_status',1)->get();
							
		}
		else
		{
			
			$inv_custs=Inv_customer_inventory::where('inv_cus_inv_cus_id',$request->customer_id)
							->where('inv_cus_inv_com_id',Auth::user()->au_company_id)
							->where('inv_cus_inv_status',1)->get();
		
		}
		
		return view('inventory.customer.accounts.accountstatementdetailsdownload',compact('inv_custs'));
	}
}