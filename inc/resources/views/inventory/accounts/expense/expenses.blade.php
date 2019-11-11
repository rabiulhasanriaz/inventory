@extends('layout.master')
@section('inventory_class','menu-open')
@section('accounts_class','menu-open')
@section('expense_class','menu-open')
@section('expenses','active')
@section('content')
<section class="content">
        @if(Session::has('errmsg'))
              <div class="alert alert-danger alert-dismissible" role="alert">
              {{ session('errmsg') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
              </div>
              @endif
              @if(Session::has('msg'))
              <div class="alert alert-success alert-dismissible" role="alert">
              {{ session('msg') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
              </div>
              @endif
                
                   <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Expenses</h3>
                    </div>
                    
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                    <div class="col-sm-4" style="border: 1px solid grey;padding:15px; margin-right: 7px;">
                      <div class="card card-primary">
                        <div class="card-header">
                          <h3 class="card-title" style="font-size:24px; margin-top:-5px;">Add Expense</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('accounts.expenses')}}" method="post">
                          @csrf
                          <div class="card-body">
                            <div class="form-group">
                              <label for="exampleInputEmail1">Category Name</label>

                              <select required name="exp_category_id" class="form-control select2" id="exampleInputEmail1">
                                <option value="">-- Select A Category --</option>
                                @foreach($categories as $category)
                                <option value="{{$category->inv_acc_exp_cat_category_id}}">{{ $category->inv_acc_exp_cat_category_name }}</option>
                                @endforeach
                              </select>
                            </div>
                             <div class="form-group">
                              <label for="exampleInputEmail1">Expense Name</label>
                              <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Write Expense Name Here" required name="exp_name" ">
                            </div>
                          </div>
                          <!-- /.card-body -->
          
                          <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                          </div>
                        </form>
                      </div>
                    </div>
                    <div class="col-sm-1" style="margin-right: -10px; margin-left: -10px;"></div>
                    <div class="col-sm-7" style="border: 1px solid grey;padding:15px;margin-left:5px; ">
                      <div class="box-header with-border">
                      <h3 class="box-title">Expenses List</h3>
                    </div>
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Balance</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>

                    
                        @php($total_balance=0)
                        @if(!empty($expenses))
                            @foreach($expenses as $row)

                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->inv_acc_exp_expense_name }}</td>
                                    <td>
                                      {{ $row->getCategoryByCatID['inv_acc_exp_cat_category_name'] }}
                                    </td>
                                    <td>{{ ($row->inv_acc_exp_cat_status==1)? 'Active':'In-Active' }}</td>
                                    <td class="text-right">
                                      {{0}}
                                    </td>
                                    <td>
                                        <a href=""
                                           class="btn btn-xs btn-custom-xs btn-success">
                                            <i class="ace-icon fa fa-eye bigger-120"></i>
                                        </a>
                                      <!--   <a href=""
                                           class="btn btn-xs btn-custom-xs btn-info">
                                            <i class="ace-icon fa fa-pencil bigger-120"></i>
                                        </a> -->
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th colspan="3" class="text-right">Total Available Balance:</th>
                            <th class="text-right" >{{ number_format($total_balance, 2) }}</th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                    </div>
                  </div>
                  </div>

                 </section>
@endsection
@section('custom_script')
<script type="text/javascript">

$(document).ready(function(){

$( "#opendate" ).datepicker({
       daysOfWeekHighlighted: "7",
        todayHighlight: true,
        autoclose: true,
     });
$( "#to" ).datepicker({
       daysOfWeekHighlighted: "7",
        todayHighlight: true,
     });
});

</script>
@endsection


@section('custom_style')
<style type="text/css">
  .form-control::-webkit-inner-spin-button,
  .form-control::-webkit-calendar-picker-indicator {
    display: none;
    -webkit-appearance: none;
}
</style>
@endsection





