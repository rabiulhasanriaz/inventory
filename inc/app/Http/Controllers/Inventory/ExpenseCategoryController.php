<?php
namespace App\Http\Controllers\Inventory;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Inv_acc_expense_category;
use Session;
use App\Inv_acc_expense;
use App\Inv_acc_bank_statement;


class ExpenseCategoryController extends Controller
{
	public function showExpenseCategories()
	{
		$categories = Inv_acc_expense_category::where('inv_acc_exp_cat_status', 1)
			->where('inv_acc_exp_cat_company_id',Auth::user()->au_company_id)
            ->orderBy('inv_acc_exp_cat_category_name')
            ->get();
        return view('inventory.accounts.expense.expensecategories',compact('categories'));
	}
	public function showExpenses()
	{
		$categories = Inv_acc_expense_category::where('inv_acc_exp_cat_status', 1)
            ->where('inv_acc_exp_cat_company_id',Auth::user()->au_company_id)
            ->orderBy('inv_acc_exp_cat_category_name')
            ->get();

		$expenses = Inv_acc_expense::where('inv_acc_exp_status', 1)
			->where('inv_acc_exp_company_id',Auth::user()->au_company_id)
            ->orderBy('inv_acc_exp_expense_name')
            ->get();
        return view('inventory.accounts.expense.expenses',compact('expenses','categories'));
	}
	public function storeExpenseCategory(Request $request)
	{
		$inv_Acc_Cat=new Inv_acc_expense_category();
		$inv_Acc_Cat->inv_acc_exp_cat_company_id=Auth::user()->au_company_id;
		$inv_Acc_Cat->inv_acc_exp_cat_category_name=$request->exp_category;
		$inv_Acc_Cat->inv_acc_exp_cat_created_by=Auth::user()->au_id;
		$inv_Acc_Cat->created_at=Carbon::now();
		$inv_Acc_Cat->save();
		Session::flash('msg','Category Successfully Added.');
		return redirect()->back();
	}
	public function storeExpenses(Request $request)
	{
		$inv_Acc_Exp=new Inv_acc_expense();

		$inv_Acc_Exp->inv_acc_exp_company_id=Auth::user()->au_company_id;
		$inv_Acc_Exp->inv_acc_exp_category_id=$request->exp_category_id;
		$inv_Acc_Exp->inv_acc_exp_expense_name=$request->exp_name;
		$inv_Acc_Exp->inv_acc_exp_created_by=Auth::user()->au_id;
		$inv_Acc_Exp->created_at=Carbon::now();
		$inv_Acc_Exp->save();

		Session::flash('msg',"Expense's Added Successfully.");
		return redirect()->back();
	}
	public function ajaxLoadExpense(Request $request)
	{
		$expenses=Inv_acc_expense::where('inv_acc_exp_category_id',$request->cat_id)->get();
		return view('pages.ajax.expense_list',compact('expenses'));
	}

	// ================= 16-11-19 ============

	public function showAjaxLoadedExpensesCategory(Request $request)
	{
		 $expenses=Inv_acc_expense::where('inv_acc_exp_category_id',$request->cat_id)->where('inv_acc_exp_company_id',Auth::user()->au_company_id)->where('inv_acc_exp_status',1)->get();
        return view('pages.ajax.expenses_by_categories',compact('expenses'));
	}
	public function showAjaxLoadedExpenses(Request $request)
	{
		$expenses=Inv_acc_bank_statement::where('inv_abs_reference_id',$request->exp_id)->where('inv_abs_status',1)->where('inv_abs_reference_type',3)->orWhere('inv_abs_reference_type',4)->where('inv_abs_reference_id',Auth::user()->au_company_id)->get();
        return view('pages.ajax.load_expenses_by_ajax',compact('expenses'));
	}
}