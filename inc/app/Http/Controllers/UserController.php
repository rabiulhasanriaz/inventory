<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Sds_team_name;
use App\Admin_user;
use App\Client_feedback;
use App\Sds_query_book;

class UserController extends Controller
{
    public function home(){
        $com = Auth::user()->au_company_id;
        $teamId = Auth::user()->au_team_id;
        $user = Auth::user()->au_id;
        $today = Carbon::now()->format('Y-m-d');

        $total = '';
        $feedback = '';

      $team_m[] = Auth::user()->au_id;

      $admin = Admin_user::where('au_user_type',4)
                         ->where('au_company_id',$com)
                         ->first();

      foreach(Admin_user::team_member($teamId, $com) as $member) {
        $team_m[] = $member->au_id;
      }

      if (Auth::user()->au_user_type == 4) {
        $feedback = Client_feedback::where('cf_date',$today)
                                   ->where('cf_company_id',$com)
                                   ->get();
        $total = Sds_query_book::where('qb_submit_date',$today)
                                 ->where('qb_company_id',$com)
                                 ->get();
         $all_clients = Client_feedback::select('cf_qb_id')
              ->where('cf_company_id',$com)
              ->where('cf_next_date',$today)
              ->pluck('cf_qb_id')
              ->toArray();

         $all_latest_feedback_id = array();
         foreach ($all_clients as $client_id) {
           $client_last_feedback = Client_feedback::select('cf_id')->where('cf_company_id',$com)
                                     ->where('cf_qb_id', $client_id)
                                     ->orderBy('cf_id', 'DESC')
                                     ->first();
           if(!empty($client_last_feedback)) {
             $all_latest_feedback_id[] = $client_last_feedback->cf_id;
           }
         }

         $today = Client_feedback::where('cf_company_id',$com)
             ->whereIn('cf_id', $all_latest_feedback_id)
             ->where('cf_next_date',$today)
             ->get();

      }elseif (Auth::user()->au_user_type == 5) {
        $team_m[] = $admin->au_id;
        $feedback = Client_feedback::where('cf_date',$today)
                                   ->where('cf_company_id',$com)
                                   ->whereIn('cf_entry_by',$team_m)
                                   ->get();
                                   
       $total = Sds_query_book::where('qb_submit_date',$today)
                                ->where('qb_company_id',$com)
                                ->whereIn('qb_entry_by',$team_m)
                                ->get();

        $all_clients = Client_feedback::select('cf_qb_id')
             ->where('cf_company_id',$com)
             ->whereIn('cf_entry_by',$team_m)
             ->where('cf_next_date',$today)
             ->pluck('cf_qb_id')
             ->toArray();

        $all_latest_feedback_id = array();
        foreach ($all_clients as $client_id) {
          $client_last_feedback = Client_feedback::select('cf_id')->where('cf_company_id',$com)
                                    ->whereIn('cf_entry_by',$team_m)
                                    ->where('cf_qb_id', $client_id)
                                    ->orderBy('cf_id', 'DESC')
                                    ->first();
          if(!empty($client_last_feedback)) {
            $all_latest_feedback_id[] = $client_last_feedback->cf_id;
          }
        }

        $today = Client_feedback::where('cf_company_id',$com)
            ->whereIn('cf_entry_by',$team_m)
            ->whereIn('cf_id', $all_latest_feedback_id)
            ->where('cf_next_date',$today)
            ->get();

      }elseif (Auth::user()->au_user_type == 6) {
        $feedback = Client_feedback::where('cf_date',$today)
                                   ->where('cf_company_id',$com)
                                   ->where('cf_entry_by',$user)
                                   ->get();
        $total = Sds_query_book::where('qb_submit_date',$today)
                                 ->where('qb_company_id',$com)
                                 ->where('qb_entry_by',$user)
                                 ->get();

        $my_clients = Sds_query_book::where('qb_company_id',$com)
                                 ->where('qb_entry_by',$user)
                                 ->pluck('qb_id')->toArray();
       $all_clients = Client_feedback::select('cf_qb_id')
            ->where('cf_company_id',$com)
            ->whereIn('cf_qb_id',$my_clients)
            ->where('cf_next_date',$today)
            ->pluck('cf_qb_id')
            ->toArray();

       $all_latest_feedback_id = array();
       foreach ($all_clients as $client_id) {
         $client_last_feedback = Client_feedback::select('cf_id')->where('cf_company_id',$com)
                                    ->whereIn('cf_qb_id',$my_clients)
                                   ->where('cf_qb_id', $client_id)
                                   ->orderBy('cf_id', 'DESC')
                                   ->first();
         if(!empty($client_last_feedback)) {
           $all_latest_feedback_id[] = $client_last_feedback->cf_id;
         }
       }

       $today = Client_feedback::where('cf_company_id',$com)
           ->whereIn('cf_qb_id',$my_clients)
           ->whereIn('cf_id', $all_latest_feedback_id)
           ->where('cf_next_date',$today)
           ->get();
      }
        return view('pages.index',compact('total','feedback','today'));
    }
    public function create_team(){
        $company_id = Auth::user()->au_company_id;
        $teams = Sds_team_name::where('tl_com_id',$company_id)->get();
        return view('pages.create_team',compact('teams'));
    }
    public function create_team_insert(Request $request){
        $table = Input::all();
            $table = new Sds_team_name;
            $errmsg2 =
            [
                'tl_team_name.required' => 'Provide Team Name',
            ];
            $request->validate([
                'tl_team_name' => 'required',
                ]);
            $time = Carbon::now()->format('Y-m-d');
            $com_id = Auth::user()->au_company_id;
            $table ->tl_user_id = 0;
            $table ->tl_com_id = $com_id;
            $table ->tl_team_name = Input::get('tl_team_name');
            $table ->tl_date = $time;
            $table ->tl_status = 1;
            $table -> save();
        return redirect('/create_team')->with(['msg' => 'Created Successfully']);
    }
    public function team_update($id){
        $team = Sds_team_name::where('tl_sl_id', $id)->first();
        return view('pages.team_update',compact('team'));
    }
    public function team_update_submit(Request $request,$id){
        $errmsg =
        [
            'tl_team_name.required' => 'Should Enter Team Name',
        ];
        $request->validate([
            'tl_team_name' => 'required',
            ],$errmsg);
        $team_name = Input::get('tl_team_name');
        $table = Sds_team_name::where('tl_sl_id',$id)->update(['tl_team_name' => $team_name]);
        return redirect('/create_team')->with(['msg1' => 'Updated Successfully']);
    }
    public function user_register(){
        $au_mobile = Admin_user::all();
        $today = Carbon::now()->format('Y-m-d');
        $com_id = Auth::user()->au_company_id;
        $teams = Sds_team_name::where('tl_com_id',$com_id)->get();
        $users = Admin_user::where('au_user_type' ,'6')->whereDate('au_created_date',$today)->get();
        return view('pages.user_register',compact('users','teams','au_mobile'));
    }

    public function user_register_insert(Request $request){

        $table = Input::all();
        $message =
        [
            'au_mobile.required' => 'Mobile Number Required',
            'au_mobile.regex' => 'Invalid Mobile Number',
            'au_mobile.unique' => 'Mobile Number Already Exists',
        ];
        $table = $request->validate([
              'au_mobile' => 'required|unique:admin_user,au_mobile||regex:/(01)[0-9]*$/'
            ], $message);
        $com_id = Auth::user()->au_company_id;
        $com_name = Auth::user()->au_company_name;
            $table = new Admin_user;
            $date = Carbon::now()->format('Y-m-d');
            $table ->au_company_id = $com_id;
            $table ->au_company_name = $com_name;
            $table ->au_name = Input::get('au_name');
            $table ->au_user_id = Input::get('au_user_id');
            $table ->au_email = Input::get('au_email');
            $table ->au_mobile = Input::get('au_mobile');
            $table ->au_password = md5(Input::get('au_password'));
            $table ->au_address = Input::get('au_address');
            $table ->au_status = 1;
            $table ->au_user_type = Input::get('au_user_type');
            if ($request->hasfile('au_company_img')) {
                $file = $request->file('au_company_img');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . "." . $extension;

                $path = $request->au_company_img->storeAs('image', $filename);

                $table->au_company_img = $filename;
            }else{
                $table->au_company_img = "";
            }
            if(Input::get('au_user_type') == '6') {
                $table ->au_team_id = Input::get('au_team_id_user');
            } elseif(Input::get('au_user_type') == '5') {
                $table ->au_team_id = Input::get('au_team_id_team');
            }

            $table ->au_created_date = $date;

            $table -> save();
        return redirect('/user_register')->with(['msg' => 'User Created Successfully']);
    }

    public function userSettings(Request $request,$id){
      $settings = Admin_user::find($id);
      $settings->au_name = $request->input('user');
      $settings->au_mobile = $request->input('cell');

      if ($request->input('entry') != '') {
        $settings->au_password = md5($request->input('entry'));
      }
      $settings->au_email = $request->input('email');
      $settings->au_address = $request->input('address');
   
        if ($request->hasfile('logo') != '') {
            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension();
            $filename = time().random_int(10,99) . "." . $extension;
    
            $path = $request->logo->storeAs('image', $filename);
            
                $settings->au_company_logo = $filename;
            
          }
        if ($request->hasfile('au_company_img')) {
            $file = $request->file('au_company_img');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . "." . $extension;

            $request->au_company_img->storeAs('image', $filename);

            $settings->au_company_img = $filename;
        }
      
      $settings->save();
      return redirect()->back();
    }

    public function mobileno_exists(Request $request){
        $exist = Admin_user::where('au_mobile', $request->au_mobile)->first();
        if(!empty($exist)) {
            return "true";
        } else {
            return "false";
        }
    }
    public function team_leaders(){
        $com_id = Auth::user()->au_company_id;
        $team_leaders = Admin_user::where('au_user_type','5')->where('au_company_id',$com_id)->get();
        return view('pages.team_leaders',compact('team_leaders'));
    }
    public function team_leader_suspend($id){
        $team_leader = Admin_user::find($id);
        $team_leader ->au_status = 0;
        $team_leader ->save();
        return redirect('/team_leaders');
    }
    public function team_leader_active($id){
        $team_leader = Admin_user::find($id);
        $team_leader ->au_status = 1;
        $team_leader ->save();
        return redirect('/team_leaders');
    }
    public function user_list(){
        $com_id = Auth::user()->au_company_id;
        $team_id = Auth::user()->au_team_id;
        $users = Admin_user::where('au_user_type','6')->where('au_company_id',$com_id)->get();
        return view('pages.user_list',compact('users'));
    }
    public function user_profile($id){
        $user_infos = Admin_user::find($id);
        return view('pages.user_profile',compact('user_infos'));
    }
    public function user_profile_status(Request $request,$id){
        $admin = Admin_user::find($id);
        $admin->au_status = 0;
        $admin->save();
        return redirect('/admin_list');
    }
    public function user_profile_status_activate(Request $request,$id){
        $admin = Admin_user::find($id);
        $admin->au_status = 1;
        $admin->save();
        return redirect('/admin_list');
    }
    public function user_edit($id){
        $user_edits = Admin_user::find($id);
        $com_id = Auth::user()->au_company_id;
        $teams = Sds_team_name::where('tl_com_id',$com_id)->get();
        return view('pages.user_edit',compact('user_edits','teams'));
    }
    public function user_list_activate(Request $request,$id){
        $user = Admin_user::find($id);
        $user->au_status = 1;
        $user-> save();
        return redirect('/user_list');
    }
    public function user_list_deactivate(Request $request,$id){
        $user = Admin_user::find($id);
        $user->au_status = 0;
        $user-> save();
        return redirect('/user_list');
    }
    public function user_update(Request $request,$id){
        $user = Admin_user::find($id);
        $user->au_name = $request->input('au_name');
        if( Auth::user()->au_user_type == 1 ){
          $user->au_user_type = 4;
        }else {
          $user->au_user_type = $request->input('au_user_type');
        }
        $user->au_email = $request->input('au_email');
        $user->au_mobile = $request->input('au_mobile');
        if ($request->input('au_password') != '') {
            $user->au_password = md5($request->input('au_password'));
          }
        $user->au_status = $request->status;
        $user->au_address = $request->input('au_address');
        /*dump(app()->make('path.public')."/../..");
        dd(public_path('/'));*/
        if ($request->hasfile('au_company_img')) {
                $file = $request->file('au_company_img');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . "." . $extension;

                $request->au_company_img->storeAs('image', $filename);

                $user->au_company_img = $filename;
            }else{
                $user->au_company_img = "";
            }
        if(Input::get('au_user_type') == '6') {
                $user ->au_team_id = Input::get('au_team_id_user');
            } elseif(Input::get('au_user_type') == '5') {
                $user ->au_team_id = Input::get('au_team_id_team');
            }
        $user->save();
        return redirect()->back();
    }
  }
