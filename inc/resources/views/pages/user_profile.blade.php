@extends('layout.master')
@section('content')
<section class="content">
<section class="content-header">
  <div class="row">
    <div class="pull-left">
      <h3 style="margin: 4px;">
        User
      </h3>
    </div>
    <div class="pull-right">
      <button data-toggle="modal" data-target="#userSettings" class="btn btn-info">Settings</button>
    </div>
  </div>

    </section>
	<div class="box">
            <div class="box-header">
              <h3 class="box-title">User Details</h3>
              <div class="box-tools">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <h2 class="text-center">Profile Picture</h2>
            <img src="{{ asset('/asset/image/'.$user_infos->au_company_img) }}" style="height: 150px; width: 150px;" class="center-block">
              <table class="table table-bordered table-striped">
                <tr>
                  	<th>Name</th>
                  	<td>{{ $user_infos->au_name }}</td>
                </tr>
                <tr>
                	<th>User ID</th>
                	<td>{{ $user_infos->au_user_id }}</td>
                </tr>
                <tr>
                	<th>Email</th>
                	<td>{{ $user_infos->au_email }}</td>
                </tr>
                <tr>
                	<th>Mobile</th>
                	<td>{{ $user_infos->au_mobile }}</td>
                </tr>
                <tr>
                	<th>Company ID</th>
                	<td>{{ $user_infos->au_company_id }}</td>
                </tr>
                <tr>
                	<th>User Type</th>
                	<td>
                   @if(  $user_infos->au_user_type == 4  )
                   Admin
                   @elseif(  $user_infos->au_user_type == 5  )
                   Team Leader
                   @elseif(  $user_infos->au_user_type == 6  )
                   Team Member
                   @else
                   Root
                   @endif
                  </td>
                </tr>
                <tr>
                	<th>Joining Date</th>
                	<td>{{ $user_infos->au_created_date }}</td>
                </tr>
                <tr>
                	<th>Address</th>
                	<td>{{ $user_infos->au_address }}</td>
                </tr>
                <tr>
                	<th>Status</th>
                	<td>
                    @if($user_infos->au_status == 1)
                    Activate
                    @elseif($user_infos->au_status == 0)
                    Deactivate
                    @endif
                  </td>
                </tr>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <div class="modal fade" id="userSettings">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Create Template</h4>
                  </div>
                  <div class="modal-body">
                    {{Form::open(['action' => ['UserController@userSettings', 'id' => $user_infos->au_id] , 'method' => 'post' , 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data'])}}
                      <div class="box-body">
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
                          <div class="col-sm-9">
                            <input type="text" name="user" class="form-control" id="inputEmail3" value="{{ $user_infos->au_name }}">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Mobile</label>
                          <div class="col-sm-9">
                            <input type="text" name="cell" class="form-control" id="inputEmail3" value="{{ $user_infos->au_mobile }}">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Password</label>
                          <div class="col-sm-9">
                            <input type="text" name="entry" class="form-control" id="inputEmail3" placeholder="Provide New Password">
                          </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">E-mail</label>
                            <div class="col-sm-9">
                              <input type="text" name="email" value="{{ $user_infos->au_email }}" class="form-control" id="inputEmail3" placeholder="Provide New Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Address</label>
                            <div class="col-sm-9">
                              <input type="text" name="address" value="{{ $user_infos->au_address }}" class="form-control" id="inputEmail3" placeholder="Provide New Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Logo</label>
                            <div class="col-sm-9">
                              <input type="file" name="logo" class="form-control" id="inputEmail3">
                            </div>
                          </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Image</label>
                          <div class="col-sm-9">
                            <input type="file" name="au_company_img" class="form-control" id="inputEmail3">
                          </div>
                        </div>
                    </div>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                  </div>
                </div>
                {{Form::close()}}
                <!-- /.modal-content -->
              </div>
      <!-- /.modal-dialog -->
      </div>
         </section>
@endsection
