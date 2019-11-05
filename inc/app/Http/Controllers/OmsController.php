<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Oms_inventory;
use App\Sds_query_book;
use App\Admin_user;

class OmsController extends Controller
{
    //
    public function oms_convence_users(){
        $com_id = Auth::user()->au_company_id;
        $user = Auth::user()->au_id;
        $team_id = Auth::user()->au_team_id;
        $team_m = array();

        foreach(Admin_user::team_member($team_id, $com_id) as $member) {
          $team_m[] = $member->au_id;
        }
        if (Auth::user()->au_user_type == 4) {
          $users = Admin_user::where('au_company_id',$com_id)
                             ->where('au_user_type', '!=',  4)
                             ->get();

         $confirm_bills = Oms_inventory::whereIn('oms_status',[4, 7])
                                       ->get();
        }elseif (Auth::user()->au_user_type == 5) {
          $users = Admin_user::where('au_company_id',$com_id)
                             ->where('au_user_type', '!=',  4)
                             ->whereIn('au_id',$team_m)
                             ->get();

         $confirm_bills = Oms_inventory::whereIn('oms_status',[4, 7])
                                       ->whereIn('oms_staff_id',$team_m)
                                       ->get();
        }

        $confirms = $confirm_bills->groupBy('oms_staff_id');

    	return view('pages.oms_convence_users',compact('confirms','users'));
    }

    public function oms_pay_convence(){
        $table = Input::all();
            $date_pay_convence = Carbon::now()->format('Y-m-d');
            $table = new Oms_inventory;
            $com_convence = Auth::user()->au_company_id;
            $table->oms_payment = Input::get('oms_payment');
            $table->oms_reason = Input::get('oms_reason');
            $table->oms_credit = Input::get('oms_credit');
            $table->oms_com_id = $com_convence;
            $table->oms_staff_id = Input::get('oms_staff_id');
            $table->oms_status = 7;
            $table->oms_transaction_date = $date_pay_convence;
            $table->save();
        return redirect('/oms_convence_users');
    }

    public function oms_convence_details($id){
        $details = Oms_inventory::whereIn('oms_status',[4,7])->where('oms_staff_id', $id)->get();
        return view('pages.oms_convence_details',compact('details'));
    }

    public function convence_add_new_bill(){
        $customers = Sds_query_book::all();
    	return view('pages.convence_add_new_bill',compact('customers'));
    }

    public function convence_add_new_bill_submit(Request $request){
        $table = Input::all();
        $message =
        [
            'oms_what_for.required' => 'This field must be Required',
            'oms_bill_date.required' => 'This Field must be Required',
            'oms_dfrom.required' => 'This Field Must be Required',
            'oms_mode.required' => 'This Field Must be Required',
            'oms_dto.required' => 'This Field Must be Required',
            'oms_debit.required' => 'This Field Must be Required',
        ];
        $request->validate([
            'oms_what_for' => 'required',
            'oms_bill_date' => 'required',
            'oms_dfrom' => 'required',
            'oms_mode' => 'required',
            'oms_dto' => 'required',
            'oms_debit' => 'required',
        ],$message);
            $date_convence = Carbon::now()->format('Y-m-d');
            $table = new Oms_inventory;
            $table->oms_what_for = Input::get('oms_what_for');
            $table->oms_bill_date = Input::get('oms_bill_date');
            $table->oms_dfrom = Input::get('oms_dfrom');
            $table->oms_mode = Input::get('oms_mode');
            $table->oms_dto = Input::get('oms_dto');
            $table->oms_debit = Input::get('oms_debit');
            $table->oms_com_id = Auth::user()->au_company_id;
            $table->oms_staff_id = Auth::user()->au_id;
            $table->oms_status = 1;
            $table->oms_insert_at = $date_convence;
            $table-> save();
        return redirect('/convence_add_new_bill')->with(['msg4' => 'Coneyance Bill Added!']);
    }
    public function convence_pending_bill(){
        $user = Auth::user()->au_id;
        $com = Auth::user()->au_company_id;
        $team_id = Auth::user()->au_team_id;
        $team_m = array();

        foreach(Admin_user::team_member($team_id, $com) as $member) {
          $team_m[] = $member->au_id;
        }


        if (Auth::user()->au_user_type == 4) {
          $pendings = Oms_inventory::where('oms_status','1')
                                   ->where('oms_com_id',$com)
                                    ->get();
        }elseif (Auth::user()->au_user_type == 5) {
          $pendings = Oms_inventory::where('oms_status','1')
                                   ->where('oms_com_id',$com)
                                   ->whereIn('oms_staff_id',$team_m)
                                    ->get();

        }else{
          $pendings = Oms_inventory::where('oms_status','1')
                                   ->where('oms_com_id',$com)
                                   ->where('oms_staff_id',$user)
                                    ->get();
        }
    	return view('pages.convence_pending_bill',compact('pendings'));
    }

    public function my_pending_bill(){
      $com = Auth::user()->au_company_id;
      $leader = Auth::user()->au_id;
      $my_pendings = Oms_inventory::where('oms_com_id',$com)
                                  ->where('oms_staff_id',$leader)
                                  ->where('oms_status',1)
                                  ->get();
      return view('pages.my_pending_bill',compact('my_pendings'));
    }

    public function convence_confirm_update(Request $request,$id){
        $pending = Oms_inventory::find($id);
        $pending ->oms_status = 4;
        $pending->save();

        return redirect('/convence_pending_bill');
    }
    public function convence_reject_update(Request $request, $id){
        $pending = Oms_inventory::find($id);
        $pending->oms_status = 3;
        $pending->save();

        return redirect('/convence_pending_bill');
    }
    public function convence_reject_bill_update(Request $request,$id){
        $reject =Oms_inventory::find($id);
        $reject ->oms_what_for = $request->input('reason');
        $reject ->oms_dfrom = $request->input('from');
        $reject ->oms_mode = $request->input('via');
        $reject ->oms_dto = $request->input('to');
        $reject ->oms_debit =$request->input('oms_debit');
        $reject ->oms_status= 1;
        $reject ->save();
        return redirect('/convence_reject_bill');
    }
    public function oms_update_bill($id){
        $bill = Oms_inventory::find($id);
        $qb = Sds_query_book::all();
        return view('pages.oms_update_bill', compact('bill','qb'));
    }
    public function convence_reject_bill(){
        $com_id = Auth::user()->au_company_id;
        $user = Auth::user()->au_id;

        if (Auth::user()->au_user_type == 4) {
          $rejects = Oms_inventory::where('oms_status','3')
                                  ->where('oms_com_id',$com_id)
                                  ->get();
        }else {
          $rejects = Oms_inventory::where('oms_status','3')
                                  ->where('oms_com_id',$com_id)
                                  ->where('oms_staff_id',$user)
                                  ->get();
        }

    	return view('pages.convence_reject_bill',compact('rejects'));
    }
    public function convence_paid_bill(){
        $com_id = Auth::user()->au_company_id;
        $user = Auth::user()->au_id;
        $paid = Oms_inventory::whereIn('oms_status',[7,1])
                             ->where('oms_com_id',$com_id)
                             ->where('oms_staff_id',$user)
                             ->get();
    	return view('pages.convence_paid_bill',compact('paid'));
    }
    public function oms_lunch_users(){
        $company = Auth::user()->au_company_id;
        $team_id = Auth::user()->au_team_id;
        $team_m = array();

        foreach(Admin_user::team_member($team_id, $company) as $member) {
          $team_m[] = $member->au_id;
        }

        if (Auth::user()->au_user_type == 4) {
          $users = Admin_user::where('au_company_id',$company)
                             ->where('au_user_type','!=','4')
                             ->get();
          $confirm_lunch = Oms_inventory::whereIn('oms_status',[6, 8])
                                        ->get();
        }elseif (Auth::user()->au_user_type == 5) {
          $users = Admin_user::where('au_company_id',$company)
                             ->where('au_user_type','!=','4')
                             ->whereIn('au_id',$team_m)
                             ->get();
          $confirm_lunch = Oms_inventory::whereIn('oms_status',[6, 8])
                                        ->whereIn('oms_staff_id',$team_m)
                                        ->get();
        }

        $lunch = $confirm_lunch->groupBy('oms_staff_id');

    	return view('pages.oms_lunch_users',compact('users','lunch'));
    }
    public function oms_lunch_details($id){
        $lunch_details = Oms_inventory::whereIn('oms_status',[6,8])
                                      ->where('oms_staff_id',$id)
                                      ->get();
        return view('pages.oms_lunch_details',compact('lunch_details'));
    }
    public function oms_pay_lunch(){
        $date_pay_lunch = Carbon::now()->format('Y-m-d');
        $com_lunch = Auth::user()->au_company_id;
        $table = Input::all();
        $table = new Oms_inventory;
            $table->oms_staff_id = Input::get('oms_staff_id');
            $table->oms_payment = Input::get('oms_payment');
            $table->oms_reason = Input::get('oms_reason');
            $table->oms_credit = Input::get('oms_credit');
            $table->oms_transaction_date = $date_pay_lunch;
            $table->oms_com_id = $com_lunch;
            $table->oms_status = 8;
            $table->save();
        return redirect('/oms_lunch_users');
    }
    public function lunch_add_new_bill(){
      $q = Sds_query_book::all();
    	return view('pages.lunch_add_new_bill',compact('q'));
    }
    public function lunch_add_new_bill_submit(Request $request){
        $table = Input::all();
        $message =
        [
          'oms_reason.required' => 'Reason is Required',
          'oms_bill_date.required' => 'Fill The Bill Date',
          'oms_person.required' => 'Provide Number of Person',
          'oms_debit.required' => 'Amount Must be Included',
        ];
        $request->validate([
          'oms_reason' => 'required',
          'oms_bill_date' => 'required',
          'oms_person' => 'required',
          'oms_debit' => 'required',
        ],$message);
            $date = Carbon::now()->format('Y-m-d');
            $table = new Oms_inventory;
            $table->oms_what_for = Input::get('reason');
            $table->oms_reason = Input::get('oms_reason');
            $table->oms_bill_date = Input::get('oms_bill_date');
            $table->oms_person = Input::get('oms_person');
            $table->oms_debit = Input::get('oms_debit');
            $table->oms_com_id = Auth::user()->au_company_id;
            $table->oms_staff_id = Auth::user()->au_id;
            $table->oms_insert_at = $date;
            $table->oms_status =2;
            $table->save();
        return redirect('/lunch_add_new_bill')->with(['msg3' => 'Lunch Bill Added!']);
    }
    public function lunch_pending_bill(){
        $u = Auth::user()->au_id;
        $com = Auth::user()->au_company_id;
        $team_id = Auth::user()->au_team_id;
        $team_m = array();

        foreach(Admin_user::team_member($team_id, $com) as $member) {
          $team_m[] = $member->au_id;
        }

        if (Auth::user()->au_user_type == 4) {
          $lunch_bills = Oms_inventory::where('oms_status' , '2')
                                      ->where('oms_com_id',$com)
                                      ->get();
        }elseif (Auth::user()->au_user_type == 5) {
          $lunch_bills = Oms_inventory::where('oms_status' , '2')
                                      ->where('oms_com_id',$com)
                                      ->whereIn('oms_staff_id',$team_m)
                                      ->get();
        }else{
          $lunch_bills = Oms_inventory::where('oms_status' , '2')
                                      ->where('oms_com_id',$com)
                                      ->where('oms_staff_id',$u)
                                      ->get();
        }

    	return view('pages.lunch_pending_bill',compact('lunch_bills'));
    }

    public function lunch_my_pending_bill(){
      $com = Auth::user()->au_company_id;
      $leader = Auth::user()->au_id;
      $my_pendings = Oms_inventory::where('oms_com_id',$com)
                                  ->where('oms_staff_id',$leader)
                                  ->where('oms_status',2)
                                  ->get();
      return view('pages.lunch_my_pending_bill',compact('my_pendings'));
    }

    public function lunch_pending_confirm(Request $request,$id){
            $lunch_bills = Oms_inventory::find($id);
            $lunch_bills->oms_status = 6;
            $lunch_bills->save();
            return redirect('/lunch_pending_bill');
    }
    public function lunch_pending_reject(Request $request,$id){
            $lunch_bills = Oms_inventory::find($id);
            $lunch_bills->oms_status = 5;
            $lunch_bills->save();
            return redirect('lunch_pending_bill');
    }
    public function lunch_reject_bill(){
        $com = Auth::user()->au_company_id;
        $u = Auth::user()->au_id;

        if (Auth::user()->au_user_type == 4) {
          $lunch_rejects = Oms_inventory::where('oms_status','5')
                                        ->where('oms_com_id',$com)
                                        ->get();
        }else {
          $lunch_rejects = Oms_inventory::where('oms_status','5')
                                        ->where('oms_staff_id',$u)
                                        ->get();
        }
    	return view('pages.lunch_reject_bill',compact('lunch_rejects'));
    }
    public function lunch_reject_update(Request $request,$id){
            $lunch_rejects = Oms_inventory::find($id);
            $lunch_rejects->oms_what_for = $request->input('lunch_customer');
            $lunch_rejects->oms_reason = $request->input('lunch_reason');
            $lunch_rejects->oms_person = $request->input('lunch_person');
            $lunch_rejects->oms_debit = $request->input('lunch_cost');
            $lunch_rejects->oms_status = 2;
            $lunch_rejects->save();
            return redirect('/lunch_reject_bill');
    }
    public function oms_lunch_update_bill($id){
        $lunch_up_bills = Oms_inventory::find($id);
        $com = Auth::user()->au_company_id;
        $lunch_c = Sds_query_book::where('qb_company_id',$com)->get();
        return view('pages.oms_lunch_update_bill',compact('lunch_up_bills','lunch_c'));
    }
    public function lunch_paid_bill(){
      $com_id = Auth::user()->au_company_id;
      $staff = Auth::user()->au_id;
      $lunch_pays = Oms_inventory::whereIn('oms_status',[2,8])
                                 ->where('oms_com_id',$com_id)
                                 ->where('oms_staff_id',$staff)
                                 ->get();
    	return view('pages.lunch_paid_bill',compact('lunch_pays'));
    }

    public function oms_convence_bill_users(Request $request){
      $id = $request->id;
       $user_debit = Oms_inventory::where('oms_staff_id',$id)
                                ->where('oms_status',4)
                                ->sum('oms_debit');

        $user_credit = Oms_inventory::where('oms_staff_id',$id)
                                 ->where('oms_status',7)
                                 ->sum('oms_credit');

        $amount = $user_credit - $user_debit;
        return $amount;
    }

    public function oms_lunch_bill_users(Request $request){
      $id = $request->id;
       $user_debit = Oms_inventory::where('oms_staff_id',$id)
                                ->where('oms_status',6)
                                ->sum('oms_debit');

        $user_credit = Oms_inventory::where('oms_staff_id',$id)
                                 ->where('oms_status',8)
                                 ->sum('oms_credit');

        $amount = $user_credit - $user_debit;
        return $amount;
    }
}
