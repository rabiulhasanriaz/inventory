@extends('layout.master')
@section('inventory_class','menu-open')
@section('accounts_class','menu-open')
@section('voucher_class','menu-open')
@section('contra_create','active')
@section('content')
<section class="content">
        <section class="content-header">
            
              <h1>
               Contra Voucher
              </h1>       
            </section>
                   <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Add Contra Voucher</h3>
                    </div>
                    @if(Session::has('errmsg'))
                      <h4 class="box-title" style="color:red;">{{Session::get('errmsg')}}</h4>
                      @endif
                      @if(Session::has('msg'))
                     <h4 class="box-title" style="color:green;">{{Session::get('msg')}}</h4>
                     @endif
                    <!-- /.box-header -->
                    <!-- form start -->
                   
                    <form action="{{route('accounts.create-contra')}}" method="post" class="form-horizontal">
                      @csrf
                      <div class="box-body">
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Bank:</label>
                          <div class="col-sm-6">
                            <select name="bank_id" required  value="{{old('bank_id')}}"  class="form-control" id="inputEmail3" >
                              <option value="">Select A Bank</option>
                              
                              @foreach($banks as $bank)
                              <option value="{{$bank->inv_abi_bank_id}}">
                                {{$bank->bank_info['bank_name'] }} ({{$bank->inv_abi_account_no}})

                              </option>
                              @endforeach
                            </select>
                         
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Descriptions:</label>
                          <div class="col-sm-6">
                            <input type="text" name="description" autocomplete="off" value="{{ old('description') }}" class="form-control" id="inputEmail3" placeholder="Write Description Here" >
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Payment Type:</label>
                          <div class="col-sm-6">
                         <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="customRadio1" name="pay_type" value="1">
                          <label for="customRadio1" class="custom-control-label">Cash To Bank</label>
                        </div>

                         <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="customRadio1" name="pay_type" value="2">
                          <label for="customRadio1" class="custom-control-label"> Bank To Cash</label>
                        </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Transaction Date:</label>
                          <div class="col-sm-6">
                            <input type="text" name="trans_date" data-date-format="yyyy-mm-dd" autocomplete="off" value="{{ old('trans_date') }}" class="form-control" id="opendate" placeholder="Select A Date" >
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Amount:</label>
                          <div class="col-sm-6">
                            <input type="number" name="amount" autocomplete="off" value="{{ old('amount') }}" class="form-control" id="inputEmail3" placeholder="Write Amount Here" >
                          </div>
                        </div>

                        

                      </div>
                      <!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-3">
                            <button type="submit" id="" class="btn btn-info pull-right">Add</button>
                         </div>
                      </div>
                      <!-- /.box-footer -->
                  <!--   {{ Form::close() }} -->
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