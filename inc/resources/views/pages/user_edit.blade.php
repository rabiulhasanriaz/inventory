@extends('layout.master')
@section('content')
	<section class="content">
<section class="content-header">
      <h1>
        User Details
      </h1>
    </section>

          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Update User</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {{ Form::open([ 'action' => ['UserController@user_update', $user_edits->au_id] , 'method' => 'post' , 'class' => 'form-horizontal' , 'files' => true]) }}

              <div class="box-body">
								@if( Auth::user()->au_user_type ==1 )
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">Company Name</label>
									<div class="col-sm-6">
										<input type="text" name="au_company_name" value="{{ $user_edits->au_company_name }}" class="form-control" id="inputEmail3" placeholder="Enter Authorized Name" value="{{ $user_edits->au_name }}" required>
									</div>
								</div>
								@endif
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
                  <div class="col-sm-6">
                    <input type="text" name="au_name" class="form-control" id="inputEmail3" placeholder="Enter Authorized Name" value="{{ $user_edits->au_name }}" required>
                  </div>
                </div>
								@if( Auth::user()->au_user_type != 1 )
                <div class="form-group">
                	<label for="userType" class="col-sm-2 control-label">User Type</label>
                	<div class="col-sm-6">
                		<input type="radio" id="au_user_type_user" name="au_user_type"
                     onchange="show_terget(this.value)" value="6" required
                     {{ ($user_edits->au_user_type == 6)?'checked':'' }}>
                    <label for="au_user_type_user">User</label>
                		<input type="radio" id="au_user_type_team_leader" name="au_user_type" onchange="show_terget(this.value)" value="5" required
                    {{ ($user_edits->au_user_type == 5)?'checked':'' }}>
                    <label for="au_user_type_team_leader">Team Leader</label>
                	</div>
                </div>
                <div class="form-group" id="user" style="display: none;">
                  <label for="inputEmail3" class="col-sm-2 control-label">Team Name</label>
                  <div class="col-sm-6">
                    <select class="form-control" name="au_team_id_user">
                      <option>Select</option>
                      @foreach($teams as $team)
                          <option {{ ($user_edits->au_user_type == 6)? (($user_edits->au_team_id == $team->tl_sl_id)? 'selected':''):'' }}
                           value="{{ $team->tl_sl_id }}">{{ $team->tl_team_name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group" id="team_leader" style="display: none;">
                  <label for="inputEmail3" class="col-sm-2 control-label">Team Leader</label>
                  <div class="col-sm-6">
                    <select class="form-control" name="au_team_id_team">
                      <option value="">Select</option>
                      @foreach($teams as $team)
                          <option {{ ($user_edits->au_user_type == 5)? (($user_edits->au_team_id == $team->tl_sl_id)? 'selected':''):'' }}
                           value="{{ $team->tl_sl_id }}">{{ $team->tl_team_name }}</option>

                      @endforeach
                    </select>
                  </div>
                </div>
								@endif
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-6">
                    <input type="email" name="au_email" class="form-control" id="inputEmail3" placeholder="Place Email..." value="{{ $user_edits->au_email }}" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Mobile</label>
                  <div class="col-sm-6">
                    <input type="text" name="au_mobile" class="form-control" id="inputEmail3" placeholder="Enter Mobile Number" value="{{ $user_edits->au_mobile }}" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                  <div class="col-sm-6">
                    <input type="password" name="au_password" class="form-control" id="inputPassword3" placeholder="Create Password...">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Address</label>
                  <div class="col-sm-6">
                    <input type="text" name="au_address" class="form-control" id="inputEmail3" placeholder="Put Detail Address.." value="{{ $user_edits->au_address }}" required>
                  </div>
                </div>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-6">
                    <input type="radio" name="status" value="1" {{ ($user_edits->au_status == 1)? 'checked':'' }}  required><b style="color: green;">Activate</b>
                    <input type="radio" name="status" value="0" {{ ($user_edits->au_status == 0)? 'checked':'' }} required><b style="color: red;">Deactivate</b>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Image</label>
                  <div class="col-sm-6">
                    <input type="file" name="au_company_img" class="form-control" id="inputEmail3" placeholder="">
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-info pull-right">Update</button>
                 </div>
              </div>
              <!-- /.box-footer -->
            {{ Form::close() }}
          </div>
         </section>
@endsection

@section('custom_script')
  <script type="text/javascript">
    $(function() {
      show_terget('{{ $user_edits->au_user_type }}');
    });
  </script>
@endsection
