<?php
namespace App\Http\Controllers\Inventory;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Inv_product_categories;
use App\Inv_acc_bank_info;
use App\Inv_bank;
use App\Inv_acc_expense_category;
use App\Inv_acc_bank_statement;
use Session;
use DB;

class BankAccountController extends Controller
{

	public function addBank()
	{
		$banks = Inv_bank::where('status', 1)->get();

		return view('inventory.accounts.bank.addbank', compact('banks'));
	}
	public function storeBank(Request $request)
	{

		try {
			$company_id=Auth::user()->au_company_id;
			$userid=Auth::user()->au_id;

			$new_bank = new Inv_acc_bank_info();
			$new_bank->inv_abi_company_id = $company_id;
			$new_bank->inv_abi_bank_id = $request->bank_id;
			$new_bank->inv_abi_branch_name = $request->branch_name;
			$new_bank->inv_abi_account_name = $request->acc_name;
			$new_bank->inv_abi_account_no = $request->acc_no;
			$new_bank->inv_abi_open_date = $request->opendate;
			$new_bank->inv_abi_account_type = 1; //1==bank
			$new_bank->inv_abi_submit_at = Carbon::now();
			$new_bank->inv_abi_submit_by = $userid;

			$new_bank->save();

			return redirect()->back()->with('msg','Bank Added Successfully Completed');

		} catch(\Exception $e) {
			return redirect()->back()->withInput()->with('errmsg','Something Went wrong! '.$e->getMessage());
		}
	}


	public function showBankList() {
		$com = Auth::user()->au_company_id;

		$bank_infos = Inv_acc_bank_info::where('inv_abi_company_id', $com)
								->where('inv_abi_status', 1)
								->get();

		return view('inventory.accounts.bank.banklist', compact('bank_infos'));
	}

	public function deleteBank($id)
	{
		try{
			$com = Auth::user()->au_company_id;
			Inv_acc_bank_info::where('inv_abi_company_id', $com)
							->where('inv_abi_id', $id)
							->where('inv_abi_status', 1)
							->update(['inv_abi_status' => 0]);
			return redirect()->back()->with('msg','Bank Deleted Successfully');
		} catch(\Exception $e) {
			return redirect()->back()->with('errmsg','Something went wrong!');
		}
	}


	public function editBank($id)
	{
		$banks = Inv_bank::where('status', 1)->get();
		$com = Auth::user()->au_company_id;
		$bank_info = Inv_acc_bank_info::where('inv_abi_company_id', $com)
						->where('inv_abi_id', $id)
						->where('inv_abi_status', 1)
						->first();
		if(!empty($bank_info)) {
			return view('inventory.accounts.bank.editbank', compact('banks', 'bank_info'));

		} else {
			return redirect()->back()->with('errmsg','Invalid Bank!');
		}
	}


	public function updateBank($id, Request $request)
	{
		try {
			$com = Auth::user()->au_company_id;
			$userid=Auth::user()->au_id;
			$bank_info = Inv_acc_bank_info::where('inv_abi_company_id', $com)
						->where('inv_abi_id', $id)
						->where('inv_abi_status', 1)
						->first();
			if(empty($bank_info)) {
				return redirect()->back()->with('errmsg','Invalid Bank!');
			}

			$banks = Inv_bank::where('status', 1)->get();
			

			$bank_info->inv_abi_bank_id = $request->bank_id;
			$bank_info->inv_abi_branch_name = $request->branch_name;
			$bank_info->inv_abi_account_name = $request->acc_name;
			$bank_info->inv_abi_account_no = $request->acc_no;
			$bank_info->inv_abi_open_date = $request->opendate;
			$bank_info->inv_abi_updated_at = Carbon::now();
			$bank_info->inv_abi_updated_by = $userid;

			$bank_info->save();

			return redirect()->back()->with('msg','Bank Updated Successfully Completed');

		} catch (\Exception $e) {
			return redirect()->back()->with('errmsg','Something went wrong. '.$e->getMessage());
		}
	}


	public function bankDepositWithdrawShowForm()
	{
		$banks=Inv_acc_bank_info::where('inv_abi_company_id',Auth::user()->au_company_id)->get();
		$categories=Inv_acc_expense_category::where('inv_acc_exp_cat_company_id',		Auth::user()->au_company_id)
					->where('inv_acc_exp_cat_status',1)
					->orderBy('inv_acc_exp_cat_category_name')->get();

		return view('inventory.accounts.bank.dipositwithdrawbank',compact('banks','categories'));
	}

	public function bankDepositWithdrawStore(Request $request)
	{
		try{
			if(Inv_acc_bank_info::where('inv_abi_id',$request->bank_id)
				->where('inv_abi_company_id',Auth::user()->au_company_id)->first())
			{
				$inv_Acc_Statement=new Inv_acc_bank_statement();
				$inv_Acc_Statement->inv_abs_company_id=Auth::user()->au_company_id;
				//$inv_Acc_Statement->inv_abs_inventory_id=
				$inv_Acc_Statement->inv_abs_reference_id=$request->expense_id;// balance 3 for credit and 4 for debit
				$inv_Acc_Statement->inv_abs_reference_type= $request->balance_type;
				$inv_Acc_Statement->inv_abs_bank_id=$request->bank_id;
				
				$inv_Acc_Statement->inv_abs_transaction_date=$request->trans_date;
				$inv_Acc_Statement->inv_abs_voucher_no=Carbon::now()->format('YmdHis');
				$inv_Acc_Statement->inv_abs_description=$request->reference;
				$inv_Acc_Statement->inv_abs_submit_by=Auth::user()->au_id;
				$inv_Acc_Statement->inv_abs_submit_at=Carbon::now();
				
				if($request->balance_type==3)
				{
					$inv_Acc_Statement->inv_abs_credit=$request->paid_amount;
					$inv_Acc_Statement->inv_abs_debit=0;
				}
				else
				{
					
					$inv_Acc_Statement->inv_abs_credit=0;
					$inv_Acc_Statement->inv_abs_debit=$request->paid_amount;
				}

				if($inv_Acc_Statement->save())
				{
					Session::flash('msg','Successfully Added.');
					return redirect()->back();
				}
				else
				{
					Session::flash('errmsg','Failed To Add.Please Try Again.');
					return redirect()->back();
				}


			}
			else
			{
				Session::flash('errmsg','The Bank You Selected is Not Belongs to Your Company.');
				return redirect()->back();
			}
		}
		catch(Exception $err)
		{
			Session::flash('errmsg','Something Goes Wrong.Please Try Again Later');
			return redirect()->back();
		}
	}
	public function showContraForm()
	{
		$banks=Inv_acc_bank_info::where('inv_abi_company_id',Auth::user()->au_company_id)->get();
		return view('inventory.accounts.voucher.createcontra',compact('banks'));
	}

	public function createContra(Request $request)
	{
		  DB::beginTransaction();
		try {
            $cashBank = Inv_acc_bank_info::where('inv_abi_status', 1)
                ->where('inv_abi_company_id', Auth::user()->au_company_id)
                ->where('inv_abi_account_type', 2) //accouunt type 2=cash
                ->first();
            if ($request->pay_type == 1) {
                /*CASH TO BANK*/
                $available_cash_balance = Inv_acc_bank_info::singleBankTotalBalance($cashBank->inv_abi_id);
                
                if ($available_cash_balance > $request->amount) {

                    $cashDebit = new Inv_acc_bank_statement();
                    $cashDebit->inv_abs_company_id = Auth::user()->au_company_id;
                    $cashDebit->inv_abs_reference_id = $request->bank_id;
                    $cashDebit->inv_abs_bank_id = $cashBank->inv_abi_id;
                    $cashDebit->inv_abs_debit = $request->amount;
                    $cashDebit->inv_abs_credit = 0;
                    $cashDebit->inv_abs_transaction_date = $request->trans_date;
                    $cashDebit->inv_abs_description = $request->description;
                    $cashDebit->inv_abs_voucher_no=Carbon::now()->format('YmdHis');
                    $cashDebit->inv_abs_status = 1;
                    $cashDebit->inv_abs_reference_type = 5; //5=contra
                    $cashDebit->inv_abs_submit_at = Carbon::now();
                    $cashDebit->inv_abs_submit_by = Auth::user()->au_id;
                    $cashDebit->save();

                    $bankCredit = new Inv_acc_bank_statement();
                    $bankCredit->inv_abs_company_id = Auth::user()->au_company_id;
                    $bankCredit->inv_abs_reference_id = $cashBank->inv_abi_id;
                    $bankCredit->inv_abs_bank_id = $request->bank_id;
                    $bankCredit->inv_abs_debit = 0;
                    $bankCredit->inv_abs_credit = $request->amount;
                    $bankCredit->inv_abs_transaction_date = $request->trans_date;
                    $bankCredit->inv_abs_description = $request->description;
                    $bankCredit->inv_abs_status = 1;
                    $bankCredit->inv_abs_reference_type = 5; //3=bank
                    $bankCredit->inv_abs_submit_at = Carbon::now();
                    $bankCredit->inv_abs_submit_by = Auth::user()->au_id;
                    $bankCredit->save();

                    $cashDebit->inv_abs_contra_transaction_id = $bankCredit->inv_abs_id;
                    $cashDebit->save();

                    
                    $bankCredit->inv_abs_contra_transaction_id = $cashDebit->inv_abs_id;
                    $bankCredit->save();

                    

                } else {
                    
                    Session::flash('errmsg', 'Amount Can\'t be more then available cash amount');
                    return redirect()->route('accounts.create-contra')->withInput($request->all());
                }


            } elseif ($request->pay_type == 2) {
                /*BANK TO CASH*/
                $available_bank_balance = Inv_acc_bank_info::singleBankTotalBalance($request->bank_id);
                if ($available_bank_balance > $request->amount) {

                    $bankDebit = new Inv_acc_bank_statement();
                    $bankDebit->inv_abs_company_id = Auth::user()->au_company_id;
                    $bankDebit->inv_abs_reference_id = $cashBank->inv_abi_id;
                    $bankDebit->inv_abs_bank_id = $request->bank_id;
                    $bankDebit->inv_abs_debit = $request->amount;
                    $bankDebit->inv_abs_credit = 0;
                    $bankDebit->inv_abs_transaction_date = $request->trans_date;
                    $bankDebit->inv_abs_description = $request->description;
                    $bankDebit->inv_abs_status = 1;
                    $bankDebit->inv_abs_reference_type = 5;//3=bank
                    $bankDebit->inv_abs_submit_at = Carbon::now();
                    $bankDebit->inv_abs_submit_by = Auth::user()->au_id();
                    $bankDebit->save();

                    $cashCredit = new Inv_acc_bank_statement();
                    $cashCredit->inv_abs_company_id = Auth::user()->au_company_id;
                    $cashCredit->inv_abs_reference_id= $request->bank_id;
                    $cashCredit->inv_abs_bank_id = $cashBank->inv_abi_id;
                    $cashCredit->inv_abs_debit = 0;
                    $cashCredit->inv_abs_credit = $request->amount;
                    $cashCredit->inv_abs_transaction_date = $request->trans_date;
                    $cashCredit->inv_abs_description = $request->description;
                    $cashCredit->inv_abs_status = 1;
                    $cashCredit->inv_abs_reference = 5;//3=bank
                    $cashCredit->inv_abs_submit_at = Carbon::now();
                    $cashCredit->inv_abs_submit_by = Auth::user()->au_id;
                    $cashCredit->save();

                    $bankDebit->inv_abs_contra_transaction_id = $cashCredit->inv_abs_id;
                    $bankDebit->save();
                    $cashCredit->inv_abs_contra_transaction_id = $bankDebit->inv_abs_id;
                    $cashCredit->save();



                } else {
                    
                    Session::flash('errmsg', 'Amount Can\'t be more then available Bank amount');
                    return redirect()->route('accounts.create-contra')->withInput($request->all());
                }


            } else {
                
                session()->flash('errmsg', 'Invalid Payment Method');
                return redirect()->route('accounts.create-contra')->withInput($request->all());
            }
        } catch (\Exception $e) {

            DB::rollBack();
            session()->flash('errmsg', 'Something Went Wrong'.$e->getMessage());
            return redirect()->route('accounts.create-contra')->withInput($request->all());
        }

        DB::commit();

       
        session()->flash('msg', 'Successfully Added Contra Voucher');
        return redirect()->back();
	}
	public function showContraList()
	{
		$contras=Inv_acc_bank_statement::where('inv_abs_company_id',Auth::user()->au_company_id)
				->where('inv_abs_reference_type',5)->get();
				return view('inventory.accounts.voucher.contralist',compact('contras'));
	}

}