@extends('layout.master')
@section('inventory_class','menu-open')
@section('customer_class','menu-open')
@section('inv_customer_class','menu-open')
@section('customer_list','active')
@section('content')
<section class="content">
    <section class="content-header">
        @if(session()->has('cus_del'))
        <div class="alert alert-success alert-dismissible" role="alert">
          {{ session('cus_del') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @endif
        @if(session()->has('cus_up'))
        <div class="alert alert-success alert-dismissible" role="alert">
          {{ session('cus_up') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @endif
          <h1>
            Customer
          </h1>
        </section>
        <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Customer List</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>SL</th>
                      <th>Cus Name</th>
                      <th>Company Name</th>
                      <th>Mobile</th>
                      <th>Address</th>
                      <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($sl=0)
                    @foreach ($inv_cuses as $cus)
                    <tr>
                        <td>{{ ++$sl }}</td>
                        <td>{{ $cus->inv_cus_name }}</td>
                        <td>{{ $cus->inv_cus_com_name }}</td>
                        <td>{{ $cus->inv_cus_mobile }}</td>
                        <td>{{ $cus->inv_cus_address }}</td>
                        <td align="center">
                            <a href="{{ route('customer.customer_detail',['id' => $cus->inv_cus_id]) }}" class="action_btn btn_show">
                              <i class="fa fa-eye"></i>
                            </a>
                            <a href="{{ route('customer.customer_delete',['id' => $cus->inv_cus_id]) }}" class="action_btn btn_delete" onclick="return deletesup()">
                              <i class="fa fa-trash"></i>
                            </a>
                            <a href="{{ route('customer.customer_edit_page',['id' => $cus->inv_cus_id]) }}" class="action_btn btn_edit">
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
  function deletesup(){
    let clickDel = confirm("Are you sure want to delete this?");
    if (clickDel == true) {
      return true;
    }else{
      return false;
    }
  }
</script>
@endsection