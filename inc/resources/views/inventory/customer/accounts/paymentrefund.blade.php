@extends('layout.master')
@section('inventory_class','menu-open')
@section('customer_class','menu-open')
@section('inv_customer_acc_class','menu-open')
@section('customer_payment_refund','active')
@section('content')
<section class="content">
        <section class="content-header">
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
              <h1>
             Customer's Payment Refund
              </h1>       
            </section>
                   <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Payment Refund</h3>
                    </div>
                   
                    <!-- /.box-header -->
                    <!-- form start -->

                    
                @if ($errors->any())
                  <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif
                   
                    <form action="{{route('customer.accounts.payment-refund')}}" method="post" class="form-horizontal">
                      @csrf
                      <div class="box-body">


                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Customer:</label>
                          <div class="col-sm-6">
                            <select name="customer_id" required  value="{{old('customer_id')}}"  class="form-control select2" id="inputEmail4" >

                             <option value="">Select A Customer</option>
                              @foreach($inv_customers as $inv_customer)
                              <option value="{{$inv_customer->inv_cus_id}}">
                                {{$inv_customer->inv_cus_name }} 
                              ({{$inv_customer->inv_cus_com_name}})
                              </option>
                              @endforeach
                            </select>
                         
                          </div>
                        </div>

                        

                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Payment Method:</label>
                          <div class="col-sm-6">
                            <select name="bank_id" required  value="{{old('bank_id')}}"  class="form-control select2" id="inputEmail3" >
                              <option value="">Select A Method</option>
                              
                              @foreach($banks as $bank)
                              <option value="{{$bank->inv_abi_bank_id}}">
                                {{$bank->bank_info['bank_name'] }} ({{$bank->inv_abi_account_no}})

                              </option>
                              @endforeach
                            </select>
                         
                          </div>
                        </div>

                         
                   
                        
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Transaction Date:</label>
                          <div class="col-sm-6">
                            <input type="text" name="trans_date" data-date-format="yyyy-mm-dd" autocomplete="off" value="{{ old('trans_date') }}" class="form-control" id="opendate" placeholder="Select A Date" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Reference:</label>
                          <div class="col-sm-6">
                            <input type="text" name="reference" autocomplete="off" value="{{ old('reference') }}" class="form-control" id="inputEmail3" placeholder="Write Reference Here" >
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Amount:</label>
                          <div class="col-sm-6">
                            <input type="number" name="amount" autocomplete="off" value="{{ old('amount') }}" class="form-control" id="inputEmail3" placeholder="Write Amount Here" required>
                          </div>
                        </div>

                        

                      </div>
                      <!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-3">
                            <button type="submit" id="" class="btn btn-info pull-right"> Submit</button>
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

@section('custom_style')
<style type="text/css">
  .form-control::-webkit-inner-spin-button,
  .form-control::-webkit-calendar-picker-indicator {
    display: none;
    -webkit-appearance: none;
}
</style>
@endsection