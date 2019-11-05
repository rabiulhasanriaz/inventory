@extends('layout.master')
@section('user_register_class','menu-open')
@section('user_register_user_list','active')
@section('content')
<section class="content">
<section class="content-header">
      <h1>
        All Users
      </h1>
    </section>
	<div class="box">
            <div class="box-header">
              <h3 class="box-title">User List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>SL</th>
                  <th>User ID</th>
                  <th>Name</th>
                  <th>Mobile</th>
                  <th>Email</th>
                  <th>Team Name</th>
                  <th>TL Name</th>
                  <th>Customers</th>
                  <th class="text-center" style="width: 75px !important;">Action</th>
                </tr>
                </thead>
                <tbody>
                @php($sl=0)
                @foreach($users as $user)
                <tr>
                  <td>{{ ++$sl }}</td>
                  <td>{{ $user->au_user_id }}</td>
                  <td>{{ $user->au_name }}</td>
                  <td>{{ $user->au_mobile }}</td>
                  <td>{{ $user->au_email }}</td>
                  <td>{{ $user->team_info['tl_team_name'] }}</td>
                  <td>{{ @App\Admin_user::tl_name($user->au_team_id, $user->au_company_id)->au_name }}</td>
                  <td align="center"><a href="{{ url('user/customer_list',['id' => $user->au_id]) }}" class="btn btn-success btn-sm" style="width: 50px;">{{ $user->customers->count() }}</a></td>
                  <td align="center">
                    <div class="btn-group">
                      <button type="button" class="btn btn-info btn-xs">Action</button>
                      <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <ul class="dropdown-menu" role="menu" style="margin-left: -40px;">
                        <li><a href="{{ url('/user_profile',['id' => $user->au_id]) }}">Show</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ url('/user_edit',['id' => $user->au_id]) }}">Update</a></li>

                        <li class="divider">{{ @App\Admin_user::tl_name($user->au_team_id, $user->au_company_id)->au_name }}</li>
                        @if($user->au_status == 0)
                        <li><a href="{{ route('pages.user_list_activate',['id' => $user->au_id]) }}">Activate User</a></li>
                        @elseif($user->au_status == 1)
                        <li><a href="{{ route('pages.user_list_deactivate',['id' => $user->au_id]) }}">Deactivate User</a></li>
                        @endif
                        <li class="divider"></li>
                        <li><a href="{{ route('permission',['id' => $user->au_id]) }}">Permission</a></li>
                      </ul>
                </div>
                  </td>
                </tr>
                @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
         @endsection
