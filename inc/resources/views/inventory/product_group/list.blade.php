@extends('layout.master')
@section('inventory_class','menu-open')
@section('product_class','menu-open')
@section('inv_pro_grp','menu-open')
@section('pro_grp_list','active')
@section('content')
<section class="content">
    @if(session()->has('up_msg'))
    <div class="alert alert-success alert-dismissible" role="alert">
      {{ session('up_msg') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif
    @if(session()->has('del_cat'))
            <div class="alert alert-success alert-dismissible" role="alert">
              {{ session('del_cat') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            @endif
        <section class="content-header">
              <h1>
                Product Group
              </h1>
            </section>
            <div class="box">
                    <div class="box-header">
                      <h3 class="box-title">Group List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th>SL</th>
                          <th>Name</th>
                          <th>Status</th>
                          <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($sl=0)
                        @foreach ($types as $type)
                        <tr>
                            <td>{{ ++$sl }}</td>
                            <td>{{ $type->inv_pro_grp_name }}</td>
                            <td>{{ ($type->inv_pro_grp_status == 1)?'Active':'Inactive' }}</td>
                            <td align="center">
                                <a href="{{ route('inventory.group_show',$type->inv_pro_grp_id) }}" class="action_btn btn_show">
                                  <i class="fa fa-eye"></i>
                                </a>
                                {{--<a href="{{ route('inventory.group_delete',$type->inv_pro_grp_id) }}" onclick="return deleteprocat()" class="action_btn btn_delete">
                                  <i class="fa fa-trash"></i>
                                </a>--}}
                                <a href="{{ route('inventory.group_edit_page',$type->inv_pro_grp_id) }}" class="action_btn btn_edit">
                                  <i class="fa fa-edit"></i>
                                </a>
                            </td>
                          </tr>
                        @endforeach
                        </tbody>
                      </table>
                    </div>
                    <!-- /.box-body -->
                  </div>
                 </section>
@endsection
@section('custom_style')
    <style>
        .action_btn{
            border: 1px solid;
            padding: 5px;
        }
        .btn_show{
            background-color: green;
            color: white;
        }
        .btn_delete{
            background-color: red;
            color: white;
        }
        .btn_edit{
            background-color: cornflowerblue;
            color: white;
        }
    </style>
    @endsection
    @section('custom_script')
<script type="text/javascript">
  function deleteprocat(){
    let clickDel = confirm("Are you sure want to delete this?");
    if (clickDel == true) {
      return true;
    }else{
      return false;
    }
  }
</script>
@endsection