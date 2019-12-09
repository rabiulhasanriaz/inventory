<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;
use App\Admin_user;
use App\Sds_reason_category;
use App\Sds_reason;
use App\Sds_query_book;
use App\Sds_find_us;
use App\Client_feedback;
use App\Sds_feedback_msg;
use App\Sds_reason_com;
use App\Sds_temp;
use App\Sds_sms;
use Carbon\Carbon;
use App\Inv_acc_bank_info;
use App\Inv_customer;
use App\Inv_bank;

class SdsController extends Controller
{
    public function company_register(){
        $today = Carbon::now()->format('Y-m-d');

        $companys = Admin_user::where('au_user_type','4')->whereDate('au_created_date',$today)->get();
        return view('pages.company_register',compact('companys'));
    }
    public function company_register_insert(Request $request){
        $pre_max_com_id = Admin_user::where('au_company_id', '!=', '')->max('au_company_id');
        $new_com_id = $pre_max_com_id + 1;
        
        $table = Input::all();
        $todayD = Carbon::now()->format('Y-m-d');
        $table = new Admin_user;
        $table->au_company_name = Input::get('au_company_name');
        $table->au_company_id = $new_com_id;
        $table ->au_name = Input::get('au_name');
        $table->au_email=Input::get('au_email');
        $table->au_mobile=Input::get('au_mobile');
        $table->au_password= md5(Input::get('au_passowrd'));
        $table->au_address=Input::get('au_address');
        $table->au_created_date=$todayD;
        $table->au_user_type=4;
        if ($request->hasfile('au_company_img')) {
                $file = $request->file('au_company_img');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . "." . $extension;

                $path = $request->au_company_img->storeAs('image', $filename);

                $table->au_company_img = $filename;
            }else{
                $table->au_company_img = "";
            }
        $table->save();

        $addCustomer = new Inv_customer;
        $addCustomer->inv_cus_com_id = $new_com_id;
        $addCustomer->inv_cus_name = 'Cash Customer';
        $addCustomer->inv_cus_com_name = 'Cash Customer';
        $addCustomer->inv_cus_mobile = Input::get('au_mobile');
        $addCustomer->inv_cus_email = Input::get('au_email');
        $addCustomer->inv_cus_address = Input::get('au_address');
        $addCustomer->inv_cus_type = 2; //2=cash customer
        $addCustomer->inv_cus_status = 1;
        $addCustomer->inv_cus_submit_by = Auth::user()->au_id;
        $addCustomer->inv_cus_submit_at = Carbon::now();
        $addCustomer->save();

        $cash_bank = Inv_bank::where('status', 0)->orderBy('id', 'desc')->first();
        $addCashBank = new Inv_acc_bank_info;
        $addCashBank->inv_abi_company_id = $new_com_id;
        $addCashBank->inv_abi_bank_id = $cash_bank->id;
        $addCashBank->inv_abi_branch_name = Input::get('au_company_name');
        $addCashBank->inv_abi_account_name = 'Cash Bank';
        $addCashBank->inv_abi_account_no = '123456789';
        $addCashBank->inv_abi_open_date = Carbon::now();
        $addCashBank->inv_abi_account_type = 2; //2=cash
        $addCashBank->inv_abi_status = 1;
        $addCashBank->inv_abi_submit_by = Auth::user()->au_id;
        $addCashBank->inv_abi_submit_at = Carbon::now();
        $addCashBank->save();
        return redirect('/company_register')->with(['msg' => 'Added Successfully']);
    }
    public function admin_list(){
        $admins = Admin_user::all()->where('au_user_type','4');
        return view('pages.admin_list',compact('admins'));
    }
    public function add_reason(){
        $reasons = Sds_reason_category::all();
        return view('pages.add_reason',compact('reasons'));
    }
    public function reason_list(Request $request){
        $reason_list = Sds_reason::where('sr_catagory',$request->cat_id)
                                 ->get();
        return view('pages.ajax.reason_list',compact('reason_list'));
    }
    public function reason_edit(Request $request){
        $reason_edit = Sds_reason::where('sr_slid',$request->id)
                                 ->first();
        $reason_edit->sr_reason = $request->input('edit');
        $reason_edit->save();
        return redirect('/add_reason')->with(['reason_edit' => 'Edited Successfully.']);
    }
    public function reason_delete($id){
      $reason_delete = Sds_reason::find($id);
      $reason_delete->delete();
      return redirect('/add_reason')->with(['reason_delete' => 'Deleted Successfully']);
    }
    public function add_reason_insert(){
        $date = Carbon::now()->format('Y-m-d');
        $table = Input::all();
        $table = new Sds_reason;
        $table->sr_catagory = Input::get('sr_catagory');
        $table->sr_reason = Input::get('sr_reason');
        $table->sr_reg_date = $date;
        $table->sr_status = 1;
        $table->save();

        return redirect('/add_reason')->with(['msg' => 'Reason Added Successfully']);
    }
    public function add_category(){
        $cat = Sds_reason_category::all();
        return view('pages.add_category',compact('cat'));
    }

    public function catagory_edit(Request $request){
      $catagory_edit = Sds_reason_category::where('sc_slid',$request->id)
                                          ->first();
      $catagory_edit->sc_catagory = $request->input('catagory_edit');
      $catagory_edit->save();
      return redirect('/add_category')->with(['catagory_edit' => 'Category Edit Successfully']);
    }

    public function catagory_delete($id){
      $catagory_delete = Sds_reason_category::find($id);
      $catagory_delete->delete();
      return redirect('/add_category')->with(['catagory_delete' => 'Category Deleted Successfully']);
    }

    public function add_category_insert(){
        $table = Input::all();

            $table = new Sds_reason_category;
            $table ->sc_catagory = Input::get('sc_catagory');
            $table -> save();
            return redirect('/add_category')->with(['msg' => 'Category Add Successfully']);
    }
    public function provide_reason(){
        $provide_reasons = Admin_user::where('au_user_type',4)
                                     ->get();
        $reasons = Sds_reason::all();
        $catagories = Sds_reason_category::all();
        return view('pages.provide_reason',compact('provide_reasons','catagories','reasons'));
    }

    public function reason_response(Request $request){
        $reasons = Sds_reason_com::where('src_company_id',$request->com_id)
                                 ->pluck('src_reson_id')
                                 ->toArray();
        return $reasons;
    }

    public function provide_reason_submit(){

      DB::beginTransaction();
      try {
        $create_date = Carbon::now()->format('Y-m-d');
        $reason_idS = Input::get('src_reason_id');
        $company_id = Input::get('src_company_id');

        foreach ($reason_idS as $reason_id) {
          $reason = Sds_reason_com::where('src_company_id', $company_id)->where('src_reson_id', $reason_id)->first();

          if(empty($reason)) {

            $table = new Sds_reason_com();
            $table->src_company_id = $company_id;
            $table->src_reson_id = $reason_id;
            $table->src_create_date = $create_date;
            $table->save();
          }
        }

      } catch(\Exception $e) {
        DB::rollback();
        return redirect()->back()->withInput();
      }

      DB::commit();
      return redirect()->back()->with(['provide' => 'Provided Selected Reason']);
    }
    public function create_customer(){
        $finds = Sds_find_us::all();
        $com_id = Auth::user()->au_company_id;
        $user_id = Auth::user()->au_id;
        $users = Admin_user::where('au_user_type','!=','4')->where('au_company_id',$com_id)->get();
        $reasons = Sds_reason_category::all();
        $categories = Sds_reason::all();
        $today = Carbon::now()->format('Y-m-d');
        $customers = Sds_query_book::where('qb_submit_date',$today)->where('qb_entry_by',$user_id)->get();
        return view('pages.create_customer',compact('finds','customers','reasons','users','categories'));
    }
    public function create_customer_submit(Request $request){
         $table = Input::all();
         $message =
        [
            'qb_mobile.required' => 'Mobile Number Required',
            'qb_mobile.regex' => 'Invalid Mobile Number',
            'qb_result.required' => 'This Field Must Be Fill Up',
            'qb_staff_id.required' => 'Must be Fill this Field'
        ];
        if (Auth::user()->au_user_type == 4) {
          $request->validate([
            'qb_mobile' => 'required|regex:/01[0-9]\d{8}$/',
            'qb_result' => 'required',
            'qb_staff_id' => 'required',
          ], $message);
        }else {
          $request->validate([
            'qb_mobile' => 'required|regex:/01[0-9]\d{8}$/',
            'qb_result' => 'required',
          ], $message);
        }
        $serial = Sds_query_book::orderBy('qb_serial','desc')->first();
        $entry = Auth::user()->au_id;
        $date = Carbon::now()->format('Y-m-d');
        $com = Auth::user()->au_company_id;

        $table = new Sds_query_book;
        $table->qb_mobile = Input::get('qb_mobile');
        $table->qb_entry_by = $entry;
        $table->qb_company_id = $com;
        $table->qb_submit_date = $date;
        $table->qb_serial = $serial->qb_serial+1;
        $table->qb_status = 1;
        $table->qb_phone = Input::get('qb_phone');
        $table->qb_name = Input::get('qb_name');
        $table->qb_company_name = Input::get('qb_company_name');
        $table->qb_find_us = Input::get('qb_find_us');
        $table->qb_address = Input::get('qb_address');
        $table->qb_birth_date = Input::get('qb_birth_date');
        $table->qb_marriage_date = Input::get('qb_marriage_date');
        $table->qb_reason = Input::get('qb_reason');
        $table->qb_email = Input::get('qb_email');
        $table->qb_result = Input::get('qb_result');
        $table->qb_staff_id = Input::get('qb_staff_id');
        $table->save();
        return redirect('/create_customer')->with(['msgcustomer' => 'Customer Added Successfully']);
    }
    public function mobile_num_exist(Request $request){

        $exist_no = Sds_query_book::where('qb_mobile',$request->qb_mobile)
                                  ->orWhere('qb_mobile1',$request->qb_mobile)
                                  ->orWhere('qb_mobile2',$request->qb_mobile)
                                  ->first();


        if (!empty($exist_no)) {
            return $exist_no->qb_id;
        }else {
            return "false";
        }
    }
    public function show_reason_by_ajax(Request $request) {
        $reasons = Sds_reason::where('sr_catagory', $request->cat_id)->get();
        return view('pages.ajax.show_reasons_by_category', compact('reasons'));
    }
    public function customer_list(){
        $com_id = Auth::user()->au_company_id;
        $user_id = Auth::user()->au_id;
        $my_customers = Sds_query_book::where('qb_entry_by',$user_id)
                                      ->Where('qb_company_id',$com_id)
                                      ->orWhere('qb_staff_id',$user_id)
                                      ->get();
        return view('pages.customer_list',compact('my_customers'));
    }
    public function user_customer_list($id) {
        $com_id = Auth::user()->au_company_id;
        $user_id = $id;
        $my_customers = Sds_query_book::where('qb_entry_by', $user_id)->where('qb_company_id',$com_id)->get();
        return view('pages.customer_list',compact('my_customers'));
    }
    public function client_feedback(Request $request,$id){

        $customer = Sds_query_book::find($id);
        $client_feedbacks = Sds_feedback_msg::all();
        $com_id = Auth::user()->au_company_id;
        $sms_temps = Sds_temp::where('st_com_id',$com_id)
                            ->get();
        $feedbacks = Client_feedback::where('cf_qb_id',$customer->qb_id)->where('cf_company_id',$com_id)
                                    ->get();

        // $sms = Sds_sms::where('sms_cf_id',$feedbacks->cf_id)
        //               ->first();
        return view('pages.client_feedback',compact('customer','feedbacks','client_feedbacks','sms_temps'));
    }

    public function client_feedback_oms(Request $request,$id){
      $customer = Sds_query_book::where('qb_serial',$id)->first();
        $client_feedbacks = Sds_feedback_msg::all();
        $com_id = Auth::user()->au_company_id;
        $sms_temps = Sds_temp::where('st_com_id',$com_id)
                            ->get();
        $feedbacks = Client_feedback::where('cf_qb_id',$customer->qb_serial)->where('cf_company_id',$com_id)
                                    ->get();

        // $sms = Sds_sms::where('sms_cf_id',$feedbacks->cf_id)
        //               ->first();
        return view('pages.client_feedback',compact('customer','feedbacks','client_feedbacks','sms_temps'));
    }

    public function smsNotify(Request $request,$id){
      $sms = Sds_sms::where('sms_cf_id',$id)
                    ->first();
      return view('pages.ajax.sms_notify_partial_table',compact('sms'));
    }

    public function send_notification(Request $request, $id){
      $com_id = Auth::user()->au_company_id;
      $client = Sds_query_book::where('qb_id',$id)->first();

      if(empty($client)) {
        session()->flash('type', 'danger');
        session()->flash('message', 'Invalid Customer !');

        return redirect()->back();
      }

      $api_sender = Admin_user::where('au_company_id',$com_id)
                              ->where('au_user_type',4)
                              ->first();

      $query = Client_feedback::where('cf_qb_id',$client->qb_id)
                              ->orderBy('cf_id', 'DESC')
                              ->first();
      if (empty($query)) {
        session()->flash('followup', 'Provide Follow Up First!');
        return redirect()->back();
      }

      $table = Input::all();
      $sent = Carbon::now()->format('Y-m-d H:i:s');
      $table = new Sds_sms;

      $table->sms_mobileno1 = Input::get('sms_mobileno1');
      $table->sms_mobileno2 = Input::get('sms_mobileno2');
      $table->sms_mobileno3 = Input::get('sms_mobileno3');
      $table->sms_text = Input::get('sms_text');
      $table->sms_cf_id = $query->cf_id;
      $table->sms_cf_next_date = $query->cf_next_date;
      $table->sms_sent_at = $sent;
      $table->sms_status = 1;


      $mobile_number1 = Input::get('sms_mobileno1');
      $mobile_number2 = Input::get('sms_mobileno2');
      $mobile_number3 = Input::get('sms_mobileno3');
      $mobile_number4 = Input::get('sms_mobileno4');

      $message = Input::get('sms_text');
      $message = urlencode($message);
      $api_key = $api_sender->au_api_key;
      $sender_id = $api_sender->au_sender_id;
      $client = new \GuzzleHttp\Client();
      $api_url = "http://sms.iglweb.com/api/v1/send?api_key=". $api_key ."&contacts=". $mobile_number1 ."," . $mobile_number2 .",". $mobile_number3 .",". $mobile_number4 ."&senderid=". $sender_id ."&msg=".$message;
      $response = $client->request('GET', "$api_url");

      $json_response = $response->getBody()->getContents();
      $api_response = json_decode($json_response);

      if ($api_response->code == "445000") {
          $type = 'success';
          $table->save();
          $msg = "SMS Sending Successfully!";
      } else if ($api_response->code == "445040") {
          $type = 'danger';
          $msg = "SMS Sending failed because of invalid API key";
      } else if ($api_response->code == "445080") {
          $type = 'danger';
          $msg = "SMS Sending failed because of invalid Sender ID";
      } else if ($api_response->code == "445120") {
          $type = 'danger';
          $msg = "SMS Sending failed because of your sms balance is low";
      } else if ($api_response->code == "445110") {
          $type = 'danger';
          $msg = "SMS Sending failed because of Client number are invalid";
      } else {
          $type = "danger";
          $msg = "SMS Sending failed because of ". $api_response->message;
      }
      session()->flash('type', $type);
      session()->flash('message', $msg);

      return redirect()->back();
    }

    public function visit_notify(Request $request,$id){
      $com_id = Auth::user()->au_company_id;
      $client = Sds_query_book::where('qb_id',$id)->first();

      if(empty($client)) {
        session()->flash('type', 'danger');
        session()->flash('message', 'Invalid Customer !');

        return redirect()->back();
      }

      $query = Client_feedback::where('cf_qb_id',$client->qb_id)
                              ->orderBy('cf_id', 'DESC')
                              ->first();
      if (empty($query)) {
        session()->flash('followup', 'Provide Follow Up First!');
        return redirect()->back();
      }

      $table = Input::all();
      $sent = Carbon::now()->format('Y-m-d H:i:s');
      $table = new Sds_sms;

      $table->sms_mobileno1 = Input::get('visit');
      $table->sms_text = Input::get('visit_text');
      $table->sms_cf_id = $query->cf_id;
      $table->sms_cf_next_date = $query->cf_next_date;
      $table->sms_sent_at = $sent;
      $table->sms_status = 2;
      $table->save();
      return redirect()->back();
    }

    public function client_feedback_submit(Request $request,$id){
        $table = Input::all();
        $entry_id = Auth::user()->au_id;
        $message =
        [
          'cf_client_feedback.required' => 'This Field must be required',
          'cf_result.required' => 'This field must required',
        ];
        $request->validate([
                'cf_client_feedback' => 'required',
                'cf_result' => 'required',
              ],$message);
            $customer = Sds_query_book::find($id);
            $cf_date = Carbon::now()->format('Y-m-d H-i-s');
            $cf_com_id = Auth::user()->au_company_id;
            $query_update = Sds_query_book::where('qb_company_id',$cf_com_id)
                                          ->where('qb_id',$id)
                                          ->first();

            $table = new Client_feedback;
            $table ->cf_call_duration = Input::get('cf_call_duration');
            $table ->cf_entry_by = $entry_id;
            $table ->cf_company_id = $cf_com_id;
            $table ->cf_result = Input::get('cf_result');
            $table ->cf_qb_id = $customer->qb_id;
            $table ->cf_client_feedback = Input::get('cf_client_feedback');
            $table ->cf_next_date = Input::get('cf_next_date');
            $table ->cf_price = Input::get('cf_price');
            $table ->cf_client_message = Input::get('cf_client_message');
            $table ->cf_date = $cf_date;
            $table ->status = 1;
            $query_update ->qb_feedback = Input::get('cf_client_feedback');
            $query_update-> save();


        $table->save();
        return redirect('/client_feedback/'.$customer->qb_id)->with(['msg_cf' => 'Client Feedback Updated']);
    }
    public function client_feedback_edit($id){
        $customer_edit = Sds_query_book::find($id);
        $com_id = Auth::user()->au_company_id;
        $com_users = Admin_user::where('au_user_type','!=','4')->where('au_company_id',$com_id)->get();
        return view('pages.client_feedback_edit',compact('customer_edit','com_users'));
    }

    public function client_feedback_edit_update(Request $request,$id){

        $customer_edit = Sds_query_book::find($id);
        $customer_edit ->qb_address = $request->input('qb_address');
        $customer_edit ->qb_name = $request->input('qb_name');
        $customer_edit ->qb_email = $request->input('qb_email');
        if (($customer_edit ->qb_mobile1 || $customer_edit ->qb_mobile2) == '') {
          $customer_edit ->qb_mobile1 = $request->input('qb_mobile1');
          $customer_edit ->qb_mobile2 = $request->input('qb_mobile2');
        }elseif ($customer_edit ->qb_mobile2 == '') {
          $customer_edit ->qb_mobile2 = $request->input('qb_mobile2');
        }
        if ($customer_edit->qb_staff_id == '' || $customer_edit->qb_staff_id == 0) {
          $customer_edit ->qb_entry_by = $request->input('qb_entry_by');
        }
        $customer_edit ->qb_company_name = $request->input('company');
        $customer_edit ->qb_birth_date = $request->input('birth');
        $customer_edit ->qb_marriage_date = $request->input('marriage');
        $customer_edit->save();

        return redirect()->route('pages.client_feedback', $id);
    }
    
    public function total_customer_list(){
         $com_id = Auth::user()->au_company_id;
         $customers = Sds_query_book::where('qb_company_id',$com_id)->get();
        return view('pages.total_customer_list',compact('customers'));
    }

    public function admin_created(Request $request,$id){
      $com = Auth::user()->au_company_id;
      $admin_c = Admin_user::where('au_user_type',4)
                           ->where('au_company_id',$com)
                           ->first();
      $admin = Sds_query_book::where('qb_entry_by',$admin_c->au_id)
                             ->where('qb_company_id',$com)
                             ->where('qb_staff_id',$id)
                             ->get();
        return view('pages.admin_customer',compact('admin'));
    }

    public function order_customer(){
        $com_id = Auth::user()->au_company_id;
        $orders = Client_feedback::where('cf_client_feedback',6)->where('cf_company_id',$com_id)->get();
        return view('pages.order_customer',compact('orders'));
    }
    public function visited_customer(){
        return view('pages.visited_customer');
    }
    public function members_list($id){
        $company_id = Auth::user()->au_company_id;
        $members = Admin_user::where('au_team_id',$id)
                             ->where('au_company_id',$company_id)
                             ->where('au_user_type',6)
                             ->get();
        return view('pages.members_list',compact('members'));
    }
    public function followup(){
        $company = Auth::user()->au_company_id;
        $entry = Auth::user()->au_id;
        $team_id = Auth::user()->au_team_id;
        $all_cf_qb_id = Client_feedback::where('cf_company_id',$company)
                                      ->where('cf_entry_by',$entry)
                                      ->groupBy('cf_qb_id')
                                      ->pluck('cf_qb_id');
                                      // dd($all_cf_qb_id);
        $team_m = array();
        $user = Auth::user()->au_id;
        $team = Admin_user::where('au_company_id',$company)
                          ->where('au_team_id',$team_id)
                          ->first();
        $team_m[] = Auth::user()->au_id;
        foreach(Admin_user::team_member($team_id, $company) as $member) {
          $team_m[] = $member->au_id;
        }
        if (Auth::user()->au_user_type == 5) {
          $followed_cus = Sds_query_book::where('qb_company_id',$company)
                                      ->whereIn('qb_entry_by',$team_m)
                                      ->whereIn('qb_id',$all_cf_qb_id)
                                      ->get();

        }elseif (Auth::user()->au_user_type == 6) {
          $followed_cus = Sds_query_book::where('qb_company_id',$company)
                                      ->where('qb_entry_by',$entry)
                                      ->whereIn('qb_id',$all_cf_qb_id)
                                      ->get();
        }

      ///////////////////////////////////////
      // ->where(function ($query) use ($all_cf_qb_id) {
      //     $query->whereIn('qb_mobile', $all_cf_qb_id)
      //     ->orWhereIn('qb_mobile1', $all_cf_qb_id)
      //     ->orWhereIn('qb_mobile2', $all_cf_qb_id);
      // })
      ////////////////////////////////////////


        return view('pages.followup',compact('followed_cus'));
    }
    public function non_followup(){
      $company = Auth::user()->au_company_id;
      $entry = Auth::user()->au_id;
      $team_id = Auth::user()->au_team_id;

      $all_cf_qb_id = Client_feedback::where('cf_company_id',$company)
                                    ->where('cf_entry_by',$entry)
                                    ->groupBy('cf_qb_id')
                                    ->pluck('cf_qb_id');
      $team_m = array();
      $user = Auth::user()->au_id;
      $team = Admin_user::where('au_company_id',$company)
                        ->where('au_team_id',$team_id)
                        ->first();
      $team_m[] = Auth::user()->au_id;
      foreach(Admin_user::team_member($team_id, $company) as $member) {
        $team_m[] = $member->au_id;
      }
      if (Auth::user()->au_user_type == 5) {
        $non_followed_cus = Sds_query_book::where('qb_company_id',$company)
                                    ->whereIn('qb_entry_by',$team_m)
                                    ->whereNotIn('qb_id',$all_cf_qb_id)
                                    ->get();

      }elseif (Auth::user()->au_user_type == 6) {
        $non_followed_cus = Sds_query_book::where('qb_company_id',$company)
                                    ->where('qb_entry_by',$entry)
                                    ->whereNotIn('qb_id',$all_cf_qb_id)
                                    ->get();
      }
      // $non_followed_cus = Sds_query_book::where('qb_company_id',$company)
      //                             ->where('qb_entry_by',$entry)
      //                             ->whereNotIn('qb_id',$all_cf_qb_id)
      //                             ->get();

      /////////////////////////////////////
      // ->where(function ($query) use ($all_cf_qb_id) {
      //     $query->whereNotIn('qb_mobile', $all_cf_qb_id)
      //     ->orWhereNotIn('qb_mobile1', $all_cf_qb_id)
      //     ->orWhereNotIn('qb_mobile2', $all_cf_qb_id);
      // })
      //////////////////////////////////////

        return view('pages.non_followup',compact('non_followed_cus'));
    }
    public function search_findus(Request $request){
        $com = Auth::user()->au_company_id;
        $team = Auth::user()->au_team_id;
        $finds = Sds_find_us::all();
        $users = Admin_user::where('au_company_id',$com)
                          ->where('au_team_id',$team)
                          ->get();

        $find_users = array();
        if($request->search_findus == 'Search') {
          if($request->from != '') {
            $start_date =  $request->from;
          } else {
            $start_date =  '2000-01-01';
          }
          if($request->to != '') {
            $end_date =  $request->to;
          } else {
            $end_date =  Carbon::now()->format('Y-m-d');
          }

          $user_ids = array();
          if($request->user != '') {
            $user_ids[] = $request->user;
          } else {
            $user_ids[] = Auth::user()->au_id;
            foreach(Admin_user::team_member($team, $com) as $member) {
              $user_ids[] = $member->au_id;
            }
          }


          if ($request->find != '') {
            $find_users = Sds_query_book::where('qb_find_us', $request->find)
                            ->where('qb_submit_date', '>=', $start_date)
                            ->where('qb_submit_date', '<=', $end_date)
                            ->whereIn('qb_entry_by', $user_ids)
                            ->get();
          } else {
            $find_users = Sds_query_book::where('qb_submit_date', '>=', $start_date)
                            ->where('qb_submit_date', '<=', $end_date)
                            ->whereIn('qb_entry_by', $user_ids)
                            ->get();
          }
        }


        return view('pages.search_findus',compact('finds','users', 'find_users'));
    }
    public function search_reason(Request $request){
      $com = Auth::user()->au_company_id;
      $team = Auth::user()->au_team_id;
      $reasons = Sds_reason::all();
      $users = Admin_user::where('au_company_id',$com)
                        ->where('au_team_id',$team)
                        ->get();

      $find_users = array();
        if($request->search_findus == 'Search') {
          if($request->from != '') {
            $start_date =  $request->from;
          } else {
            $start_date =  '2000-01-01';
          }
          if($request->to != '') {
            $end_date =  $request->to;
          } else {
            $end_date =  Carbon::now()->format('Y-m-d');
          }

          $user_ids = array();
          if($request->user != '') {
            $user_ids[] = $request->user;
          } else {
            $user_ids[] = Auth::user()->au_id;
            foreach(Admin_user::team_member($team, $com) as $member) {
              $user_ids[] = $member->au_id;
            }
          }

          if ($request->reason != '') {
            $find_users = Sds_query_book::where('qb_reason', $request->reason)
                            ->where('qb_submit_date', '>=', $start_date)
                            ->where('qb_submit_date', '<=', $end_date)
                            ->whereIn('qb_entry_by', $user_ids)
                            ->get();
          } else {
            $find_users = Sds_query_book::where('qb_submit_date', '>=', $start_date)
                            ->where('qb_submit_date', '<=', $end_date)
                            ->whereIn('qb_entry_by', $user_ids)
                            ->get();
          }
        }
        return view('pages.search_reason',compact('reasons','users', 'find_users'));
    }

    public function search_result(Request $request){
      $com = Auth::user()->au_company_id;
      $team = Auth::user()->au_team_id;
      $users = Admin_user::where('au_company_id',$com)
                        ->where('au_team_id',$team)
                        ->get();
            $find_users = array();
              if($request->search_findus == 'Search') {
                if($request->from != '') {
                  $start_date =  $request->from;
                } else {
                  $start_date =  '2000-01-01';
                }
                if($request->to != '') {
                  $end_date =  $request->to;
                } else {
                  $end_date =  Carbon::now()->format('Y-m-d');
                }

                $user_ids = array();
                if($request->user != '') {
                  $user_ids[] = $request->user;
                } else {
                  $user_ids[] = Auth::user()->au_id;
                  foreach(Admin_user::team_member($team, $com) as $member) {
                    $user_ids[] = $member->au_id;
                  }
                }

                if ($request->result != '') {
                  $find_users = Sds_query_book::where('qb_result', $request->result)
                                  ->where('qb_submit_date', '>=', $start_date)
                                  ->where('qb_submit_date', '<=', $end_date)
                                  ->whereIn('qb_entry_by', $user_ids)
                                  ->get();
                }
                else {
                  $find_users = Sds_query_book::where('qb_submit_date', '>=', $start_date)
                                  ->where('qb_submit_date', '<=', $end_date)
                                  ->whereIn('qb_entry_by', $user_ids)
                                  ->get();
                }
              }
        return view('pages.search_result',compact('users', 'find_users'));
    }

    public function search_feedback(Request $request){
      $com = Auth::user()->au_company_id;
      $team = Auth::user()->au_team_id;
        $feedbacks = Sds_feedback_msg::all();
        $users = Admin_user::where('au_company_id',$com)
                          ->where('au_team_id',$team)
                          ->get();
          $find_users = array();
            if($request->search_findus == 'Search') {
              if($request->from != '') {
                $start_date =  $request->from;
              } else {
                $start_date =  '2000-01-01';
              }
              if($request->to != '') {
                $end_date =  $request->to;
              } else {
                $end_date =  Carbon::now()->format('Y-m-d');
              }

              $user_ids = array();
              if($request->user != '') {
                $user_ids[] = $request->user;
              } else {
                $user_ids[] = Auth::user()->au_id;
                foreach(Admin_user::team_member($team, $com) as $member) {
                  $user_ids[] = $member->au_id;
                }
              }

              if ($request->feedback != '') {
                $find_users = Sds_query_book::where('qb_feedback', $request->feedback)
                                ->where('qb_submit_date', '>=', $start_date)
                                ->where('qb_submit_date', '<=', $end_date)
                                ->whereIn('qb_entry_by', $user_ids)
                                ->get();
              } else {
                $find_users = Sds_query_book::where('qb_submit_date', '>=', $start_date)
                                ->where('qb_submit_date', '<=', $end_date)
                                ->whereIn('qb_entry_by', $user_ids)
                                ->get();
              }
            }
        return view('pages.search_feedback',compact('feedbacks','users', 'find_users'));
    }
    public function todays_customer_list(){
        $com_id = Auth::user()->au_company_id;
        $user_customers = Admin_user::where('au_user_type','!=','4')->where('au_company_id',$com_id)->get();
        return view('pages.todays_customer_list',compact('user_customers'));
    }
    public function customer_list_monthly($id){
        $com_id = Auth::user()->au_company_id;
        $todayDate = Carbon::now()->format('Y-m-d');
        $monthly = Carbon::now()->startOfMonth()->format('Y-m-d');
        $monthly_cus = Sds_query_book::where('qb_company_id',$com_id)
                                     ->where('qb_entry_by',$id)
                                     ->where('qb_submit_date','<=',$todayDate)
                                     ->where('qb_submit_date','>=',$monthly)
                                     ->get();
        return view('pages.customer_list_monthly',compact('monthly_cus'));
    }
    public function customer_list_today($id){
        $com_id = Auth::user()->au_company_id;
        $today = Carbon::now()->format('Y-m-d');
        $today_cus = Sds_query_book::where('qb_company_id',$com_id)
                                   ->where('qb_entry_by',$id)
                                   ->where('qb_submit_date',$today)
                                   ->get();
        return view('pages.customer_list_today',compact('today_cus'));
    }
    public function find_us(){
        $find_us = Sds_find_us::where('sf_status',1)->get();
        return view('pages.find_us',compact('find_us'));
    }

    public function find_us_edit(Request $request){
      $find_edit = Sds_find_us::where('sf_slid',$request->id)->first();
      $find_edit->sf_howto = $request->input('how');
      $find_edit->save();
      return redirect('/find_us')->with(['find_edit' => 'Find Us Edited Successfully']);
    }

    public function find_up_delete(Request $request,$id){
      $find_up_del = Sds_find_us::where('sf_slid',$id)->first();
      $find_up_del->sf_status = 0;
      $find_up_del->save();
      return redirect('/find_us')->with(['find_up_del' => 'Find Us Deleted Successfully']);
    }
    public function find_us_submit(){
        $table = Input::all();
            $table = new Sds_find_us;
            $date = Carbon::now()->format('Y-m-d');
            $table ->sf_howto = Input::get('sf_howto');
            $table ->sf_create_date = $date;
            $table ->sf_status = 1;
            $table ->save();
        return redirect('/find_us')->with(['msgfind' => 'Find us Added Successfully']);
    }
    public function weekly_summery(Request $request){
        $com_id = Auth::user()->au_company_id;
        $team_id = Auth::user()->au_team_id;
        $team_m = array();
        $user = Auth::user()->au_id;
        foreach(Admin_user::team_member($team_id, $com_id) as $member) {
          $team_m[] = $member->au_id;
        }

        if (Auth::user()->au_user_type == 4) {
          $week_customers = Admin_user::where('au_company_id',$com_id)
                                      ->where('au_user_type','!=','4')
                                      ->get();
        }elseif (Auth::user()->au_user_type == 5) {
          $week_customers = Admin_user::where('au_company_id',$com_id)
                                      ->where('au_user_type','!=','4')
                                      ->whereIn('au_id',$team_m)
                                      ->get();
        }

        if($request->from != '') {

            $start_date = $request->from;
            $weekly_dates = array();
            for($i = 0; $i<6; $i++) {
                if($i == 0) {
                    $weekly_dates[$i] = $start_date;
                } else {
                    $weekly_dates[$i] = Carbon::make($start_date)->addDays($i)->format('Y-m-d');
                }
            }
            $show_data = true;

            return view('pages.weekly_summery', compact('show_data', 'weekly_dates','week_customers'));
        } else {
            return view('pages.weekly_summery');
        }
    }
    public function monthly_summery(Request $request){
        $com_id = Auth::user()->au_company_id;
        $team_id = Auth::user()->au_team_id;
        $team_m = array();
        $user = Auth::user()->au_id;
        foreach(Admin_user::team_member($team_id, $com_id) as $member) {
          $team_m[] = $member->au_id;
        }
        if (Auth::user()->au_user_type == 4) {
          $mon_users = Admin_user::where('au_user_type','!=','4')
                                  ->where('au_company_id',$com_id)
                                  ->get();
        }elseif (Auth::user()->au_user_type == 5) {
          $mon_users = Admin_user::where('au_user_type','!=','4')
                                  ->where('au_company_id',$com_id)
                                  ->whereIn('au_id',$team_m)
                                  ->get();
        }


         if($request->year != '' || $request->month != '' || $request->user != '') {

            $user = Admin_user::where('au_user_type', '!=', '4')
                                ->where('au_user_type', '!=', '1')
                                ->where('au_company_id', $com_id)
                                ->where('au_id', $request->user)
                                ->first();
            if(!empty($user)) {
                $request_month = $request->month;
                $request_year = $request->year;
                $start_date = $request_year.'-'.$request_month.'-'.'1';
                $days_in_month = Carbon::make($start_date)->endOfMonth()->format('d');
                $show_data = true;

                return view('pages.monthly_summery', compact('show_data','mon_users', 'days_in_month', 'request_year', 'request_month', 'user'));
            } else {
                return view('pages.monthly_summery',compact('mon_users'))->with('invalid_user', 'true');
            }
        } else {
            return view('pages.monthly_summery',compact('mon_users'));
        }
    }

}
