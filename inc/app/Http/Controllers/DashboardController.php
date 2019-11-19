<?php

namespace App\Http\Controllers;

use Auth;
use App\Client_feedback;
use App\Sds_query_book;
use App\Admin_user;
use App\Au_access;
use App\Au_access_company;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    //
    public function todays_followup(){
        $today = Carbon::now()->format('Y-m-d');
        $com = Auth::user()->au_company_id;
        $teamId = Auth::user()->au_team_id;
        $team_m_f = array();
        $user = Auth::user()->au_id;
        $team = Admin_user::where('au_company_id',$com)
                          ->where('au_team_id',$teamId)
                          ->first();
        $admin = Admin_user::where('au_user_type',4)
                           ->where('au_company_id',$com)
                           ->first();
        $team_m_f[] = Auth::user()->au_id;
        foreach(Admin_user::team_member($teamId, $com) as $member) {
          $team_m_f[] = $member->au_id;
        }

        $client = Sds_query_book::where('qb_company_id',$com)->first();

        // $all_clients = Sds_query_book::select('qb_id')
        //     ->where('qb_company_id', $com)
        //     ->whereIn('qb_entry_by', $team_m_f)
        //     ->pluck('qb_id')
        //     ->toArray();

        if (Auth::user()->au_user_type == 6) {
         $all_clients = Client_feedback::select('cf_qb_id')
              ->where('cf_company_id',$com)
              ->where('cf_entry_by',$user)
              ->where('cf_next_date',$today)
              ->pluck('cf_qb_id')
              ->toArray();

         $all_latest_feedback_id = array();
         foreach ($all_clients as $client_id) {
           $client_last_feedback = Client_feedback::select('cf_id')->where('cf_company_id',$com)
                                     ->where('cf_entry_by',$user)
                                     ->where('cf_qb_id', $client_id)
                                     ->orderBy('cf_id', 'DESC')
                                     ->first();
           if(!empty($client_last_feedback)) {
             $all_latest_feedback_id[] = $client_last_feedback->cf_id;
           }
         }

         $todays = Client_feedback::where('cf_company_id',$com)
             ->where('cf_entry_by',$user)
             ->whereIn('cf_id', $all_latest_feedback_id)
             ->where('cf_next_date',$today)
             ->get();
                                     // where('cf_next_date',$today)
        }elseif (Auth::user()->au_user_type == 5) {
          $team_m_f[] = $admin->au_id;
          $all_clients = Client_feedback::select('cf_qb_id')
               ->where('cf_company_id',$com)
               ->whereIn('cf_entry_by',$team_m_f)
               ->where('cf_next_date',$today)
               ->pluck('cf_qb_id')
               ->toArray();

          $all_latest_feedback_id = array();
          foreach ($all_clients as $client_id) {
            $client_last_feedback = Client_feedback::select('cf_id')->where('cf_company_id',$com)
                                      ->whereIn('cf_entry_by',$team_m_f)
                                      ->where('cf_qb_id', $client_id)
                                      ->orderBy('cf_id', 'DESC')
                                      ->first();
            if(!empty($client_last_feedback)) {
              $all_latest_feedback_id[] = $client_last_feedback->cf_id;
            }
          }

          $todays = Client_feedback::where('cf_company_id',$com)
              ->whereIn('cf_entry_by',$team_m_f)
              ->whereIn('cf_id', $all_latest_feedback_id)
              ->where('cf_next_date',$today)
              ->get();

          // $todays = Client_feedback::where('cf_next_date',$today)
          //                            ->where('cf_company_id',$com)
          //                            ->whereIn('cf_entry_by',$team_m_f)
          //                            ->get();

        }elseif (Auth::user()->au_user_type == 4) {
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

          $todays = Client_feedback::where('cf_company_id',$com)
              ->whereIn('cf_id', $all_latest_feedback_id)
              ->where('cf_next_date',$today)
              ->get();
        }
    	return view('pages.todays_followup',compact('todays'));
    }

    public function given_followup(){
      $today = Carbon::now()->format('Y-m-d');
      $com = Auth::user()->au_company_id;
      $teamId = Auth::user()->au_team_id;
      $user = Auth::user()->au_id;

      $user_cus = Sds_query_book::where('qb_company_id',$com)
                                ->where('qb_staff_id',$user)
                                ->first();
      $admin = Admin_user::where('au_user_type',4)
                         ->where('au_company_id',$com)
                         ->first();

      $team_m = array();
      $team = Admin_user::where('au_company_id',$com)
                        ->where('au_team_id',$teamId)
                        ->first();
      $team_m[] = Auth::user()->au_id;
      foreach(Admin_user::team_member($teamId, $com) as $member) {
        $team_m[] = $member->au_id;
      }
      if (Auth::user()->au_user_type == 6) {
        $given_fu = Client_feedback::where('cf_date',$today)
                                   ->where('cf_company_id',$com)
                                   ->where('cf_entry_by',$user)
                                   ->get();
      }elseif (Auth::user()->au_user_type == 5) {
        $team_m[] = $admin->au_id;

        $given_fu = Client_feedback::where('cf_date',$today)
                                   ->where('cf_company_id',$com)
                                   ->whereIn('cf_entry_by',$team_m)
                                   // ->where('cf_entry_by',$admin->au_id)
                                   ->get();


      }elseif (Auth::user()->au_user_type == 4) {
        $given_fu = Client_feedback::where('cf_date',$today)
                                   ->where('cf_company_id',$com)
                                   ->get();
      }

    	return view('pages.given_followup',compact('given_fu'));
    }

    public function followup_list(){
      $com = Auth::user()->au_company_id;
      $teamId = Auth::user()->au_team_id;
      $today = Carbon::now()->format('Y-m-d');
      $user = Auth::user()->au_id;
      $admin = Admin_user::where('au_user_type',4)
                         ->where('au_company_id',$com)
                         ->first();
      $team_m_f_l = array();
      $team = Admin_user::where('au_company_id',$com)
                        ->where('au_team_id',$teamId)
                        ->first();
      $team_m_f_l[] = Auth::user()->au_id;
      foreach(Admin_user::team_member($teamId, $com) as $member) {
        $team_m_f_l[] = $member->au_id;
      }
      if (Auth::user()->au_user_type == 4) {
        $tc_list = Sds_query_book::where('qb_submit_date',$today)
                                 ->where('qb_company_id',$com)
                                 ->get();
      }elseif (Auth::user()->au_user_type == 5) {
        $team_m_f_l[] = $admin->au_id;
        $tc_list = Sds_query_book::where('qb_submit_date',$today)
                                 ->where('qb_company_id',$com)
                                 ->whereIn('qb_entry_by',$team_m_f_l)
                                 ->get();

      }elseif (Auth::user()->au_user_type == 6) {
        $tc_list = Sds_query_book::where('qb_submit_date',$today)
                                 ->where('qb_company_id',$com)
                                 ->where('qb_entry_by',$user)
                                 ->get();
      }

    	return view('pages.followup_list',compact('tc_list'));
    }

    public function page_permission($au_id){
      $com = Auth::user()->au_company_id;
      $admin_user = Admin_user::where('au_id', $au_id)
                              ->where('au_company_id',$com)
                              ->first();

      if(!empty($admin_user)) {
        $company_access = explode('-', Auth::user()->au_permission_status);
        $exist_permissions = explode('-', $admin_user->au_permission_status);
        $permissions = Au_access::where('parent_menu', null)
                                ->where('status', 1)
                                ->whereIn('au_access_company_id', $company_access)
                                ->get();
        return view('pages.page_permission', compact('permissions', 'exist_permissions', 'admin_user'));

      } else {
        session()->flash('type', 'danger');
        session()->flash('message', 'Invalid User');
        return redirect()->back();
      }

    }

    public function permission_user_type(){
      $permissions = Au_access::all();
      return view('pages.permission_user_set',compact('permissions'));
    }

    public function page_permission_submit(Request $request, $au_id)
    {
      $admin_user = Admin_user::where('au_id', $au_id)->first();
      if(!empty($admin_user)) {
        if (!empty($request->au_permission)) {
          $new_permission = implode('-', $request->au_permission);
        } else {
          $new_permission = null;
        }
        $admin_user->au_permission_status = $new_permission;
        $admin_user->save();

        session()->flash('type', 'success');
        session()->flash('message', 'Permission Updated Successfully');
        return redirect()->back();

      } else {
        session()->flash('type', 'danger');
        session()->flash('message', 'Invalid User');
        return redirect()->back();
      }
    }

    public function page_permission_com($au_com_id){
      $admin_user = Admin_user::where('au_company_id', $au_com_id)
        ->where('au_user_type', 4)
        ->first();

      if(!empty($admin_user)) {
        $exist_permissions = explode('-', $admin_user->au_permission_status);
        $permissions = Au_access_company::where('status', 1)->get();
        
        return view('pages.page_permission_com', compact('permissions', 'exist_permissions', 'admin_user'));

      } else {
        session()->flash('type', 'danger');
        session()->flash('message', 'Invalid Company');
        return redirect()->back();
      }
      return view('pages.page_permission_com',compact('admin_user','permissions'));
    }

    public function page_permission_com_submit(Request $request, $au_id)
    {
      
      $admin_user = Admin_user::where('au_company_id', $au_id)
        ->where('au_user_type',4)
        ->first();
      if(!empty($admin_user)) {
        if (!empty($request->au_permission)) {
          $new_permission = implode('-', $request->au_permission);
        } else {
          $new_permission = null;
        }
        $admin_user->au_permission_status = $new_permission;
        $admin_user->save();

        session()->flash('type', 'success');
        session()->flash('message', 'Permission Updated Successfully');
        return redirect()->back();

      } else {
        session()->flash('type', 'danger');
        session()->flash('message', 'Invalid Company');
        return redirect()->back();
      }
    }
}
