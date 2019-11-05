@extends('layout.master')
@section('custom_style')
  <link rel="stylesheet" href="{{ asset('asset/css/bootstrap-toggle.min.css') }}">
  <style media="screen">
            .list-group-horizontal .list-group-item
          {
          display: inline-block;
          }
          .list-group-horizontal .list-group-item
          {
          margin-bottom: 0;
          margin-left:-4px;
          margin-right: 0;
          border-right-width: 0;
          }
          .list-group-horizontal .list-group-item:first-child
          {
          border-top-right-radius:0;
          border-bottom-left-radius:4px;
          }
          .list-group-horizontal .list-group-item:last-child
          {
          border-top-right-radius:4px;
          border-bottom-left-radius:0;
          border-right-width: 1px;
          }
          .custom_color .btn-primary {
              color: #fff;
              background-color: green;
          }
  </style>
@endsection
@section('content')
  <div class="box-body">
    <div class="alert alert-success alert-dismissible text-center" role="alert">
      {{ $admin_user->au_name }} ({{ $admin_user->au_company_name }})
    </div>
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('message') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif
    <form action="{{ route('permission', $admin_user->au_id)}}" method="POST" class="form-horizontal">
      @csrf
  <div class="form-group" style="padding-top: 10px;">
      <div class="col-sm-12">
        <div class="row custom_color" style="left: 40px; position: relative;">

          @foreach($permissions as $main_menu)
            <h2><i class="fa fa-folder" aria-hidden="true"></i> {{ $main_menu->permission_title }}</h2>
            <ul class="list-group list-group-horizontal">
              @foreach($main_menu->sub_menues as $first_step_sub_menues)
                @if($first_step_sub_menues->permission_type == 2)
                  <li class="list-group-item" style="width: 350px;">
                    <input type="checkbox" name="au_permission[]" data-size="mini" value="{{ $first_step_sub_menues->code }}" {{ (in_array($first_step_sub_menues->code, $exist_permissions))? 'checked':'' }}  data-toggle="toggle" data-on="On" data-off="Off">
                    {{ $first_step_sub_menues->permission_title }}
                  </li>
                @endif
              @endforeach
              @foreach($main_menu->sub_menues as $first_step_sub_menues)
                @if($first_step_sub_menues->permission_type == 1)
                  <h4><i class="fa fa-folder-o" aria-hidden="true"></i> {{ $first_step_sub_menues->permission_title }}</h4>
                  @foreach($first_step_sub_menues->sub_menues as $second_step_sub_menues)
                    <li class="list-group-item" style="width: 350px;">
                      <input type="checkbox" name="au_permission[]" id="checkbox_check_id_{{ $second_step_sub_menues->access_id }}" data-size="mini" value="{{ $second_step_sub_menues->code }}" {{ (in_array($second_step_sub_menues->code, $exist_permissions))? 'checked':'' }}  data-toggle="toggle" data-on="On" data-off="Off">
                      <label for="checkbox_check_id_{{ $second_step_sub_menues->access_id }}">{{ $second_step_sub_menues->permission_title }}</label>
                    </li>
                  @endforeach
                @endif
              @endforeach
            </ul>
          @endforeach
        </div>
      </div>

    </div>
  </div>
  <!-- /.box-body -->
  <div class="box-footer">
    <div class="col-sm-3">
        <button type="submit" id="" class="btn btn-info">Update</button>
     </div>
  </div>
  <!-- /.box-footer -->
</form>
@endsection
@section('custom_script')
<script type="text/javascript" src="{{ asset('asset/js/bootstrap-toggle.min.js') }}"></script>
@endsection
