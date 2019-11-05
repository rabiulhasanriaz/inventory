@extends('layout.master')
@section('inventory_class','menu-open')
@section('accounts_class','menu-open')
@section('bank_class','menu-open')
@section('bank_add','active')
@section('content')
<section class="content">
        <section class="content-header">
            
              <h1>
               Bank Addition
              </h1>       
            </section>
                   <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Update Bank</h3>
                    </div>
                    @if(Session::has('errmsg'))
                      <h4 class="box-title" style="color:red;">{{Session::get('errmsg')}}</h4>
                      @endif
                      @if(Session::has('msg'))
                     <h4 class="box-title" style="color:green;">{{Session::get('msg')}}</h4>
                     @endif
                    <!-- /.box-header -->
                    <!-- form start -->
                    
                    <form action="{{route('accounts.update-bank', $bank_info->inv_abi_id)}}" method="post" class="form-horizontal">
                      @csrf
                      <div class="box-body">
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Bank Name:</label>
                          <div class="col-sm-6">
                            <select name="bank_id" required  value="{{old('bank_id')}}"  class="form-control" id="inputEmail3" >
                              <option value="">Select A Bank</option>
                              
                              @foreach($banks as $inv_bank)
                              <option {{ ($inv_bank->id == $bank_info->inv_abi_bank_id)? 'selected':'' }} value="{{$inv_bank->id}}">{{$inv_bank->bank_name}}</option>
                              @endforeach
                            </select>
                         
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Branch Name:</label>
                          <div class="col-sm-6">
                            <input type="text" name="branch_name" autocomplete="off" value="{{ $bank_info->inv_abi_branch_name }}" class="form-control" id="inputEmail3" placeholder="Enter Branch Name" >
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Account Name:</label>
                          <div class="col-sm-6">
                            <input type="text" name="acc_name" autocomplete="off" value="{{ $bank_info->inv_abi_account_name }}" class="form-control" id="inputEmail3" placeholder="Enter Account Name" >
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Account No:</label>
                          <div class="col-sm-6">
                            <input type="text" name="acc_no" autocomplete="off" value="{{ $bank_info->inv_abi_account_no }}" class="form-control" id="inputEmail3" placeholder="Enter Account Number" >
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Open Date:</label>
                          <div class="col-sm-6">
                            <input type="text" name="opendate" data-date-format="yyyy-mm-dd" autocomplete="off" value="{{ $bank_info->inv_abi_open_date }}" class="form-control" id="opendate" placeholder="Select A Date" >
                          </div>
                        </div>

                        <!-- <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Opening Balance:</label>
                          <div class="col-sm-6">
                            <input type="number" name="openbalance"  autocomplete="off" value="{{ old('openbalance') }}" class="form-control"  placeholder="Enter Opening Balance Here" >
                          </div>
                        </div> -->
                        

                      </div>
                      <!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-3">
                            <button type="submit" id="" class="btn btn-info pull-right">Update</button>
                         </div>
                      </div>
                      <!-- /.box-footer -->
                  
                </form>
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