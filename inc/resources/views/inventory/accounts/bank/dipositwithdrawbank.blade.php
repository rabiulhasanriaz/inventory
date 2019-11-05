@extends('layout.master')
@section('inventory_class','menu-open')
@section('accounts_class','menu-open')
@section('bank_class','menu-open')
@section('bank_deposit_withdraw','active')
@section('content')
<section class="content">
        <section class="content-header">
            
            </section>
                   <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Deposit/Withdraw Bank Balance</h3>
                    </div>
                    @if(Session::has('errmsg'))
                      <h4 class="box-title" style="color:red;">{{Session::get('errmsg')}}</h4>
                      @endif
                      @if(Session::has('msg'))
                     <h4 class="box-title" style="color:green;">{{Session::get('msg')}}</h4>
                     @endif
                    <!-- /.box-header -->
                    <!-- form start -->
                    
                    <form action="{{route('accounts.bank-diposit-withdraw')}}" method="post" class="form-horizontal">
                   <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                      <div class="box-body">
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Bank :</label>
                          <div class="col-sm-6">
                            <select name="bank_id" required  class="form-control select2" id="inputEmail3" >
                              <option value="">Select A Bank</option>
                              
                              @foreach($banks as $inv_bank)
                              <option  value="{{$inv_bank->inv_abi_id}}">{{$inv_bank->bank_info['bank_name']}}</option>
                              @endforeach
                            </select>
                         
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Balance Type:</label>
                          <div class="col-sm-6">
                            <select name="balance_type" required  class="form-control" required>
                              <option value="3">Credit</option>
                              <option value="4">Debit</option>
                              
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Transaction Date:</label>
                          <div class="col-sm-6">
                           <input type="text" name="trans_date" data-date-format="yyyy-mm-dd" autocomplete="off"  class="form-control" id="opendate" placeholder="Select A Date" >
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Expenses Category:</label>
                          <div class="col-sm-6">
                            <select name="category_id" required class="form-control select2" id="category" onchange="loadExpense()">
                              <option value="">Select A Category</option>
                              
                              @foreach($categories as $category)
                              <option value="{{$category->inv_acc_exp_cat_category_id}}">{{$category->inv_acc_exp_cat_category_name}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Expense:</label>
                          <div class="col-sm-6 expense_name_div"> 
                             <select name="expense_id" required   class="form-control select2" id="expense_id" >
                              <option value="">Choose A Category First</option>
                            </select>
                          </div>
                        </div>

                         <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Reference:</label>
                          <div class="col-sm-6">
                            <input type="text" name="reference" autocomplete="off" value="{{ old('reference') }}" class="form-control" id="inputEmail3" placeholder="Enter a Reference" required>
                          </div>
                        </div>
                         <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Amount:</label>
                          <div class="col-sm-6">
                            <input type="number" name="paid_amount" autocomplete="off" value="{{ old('paid_amount') }}" class="form-control" id="inputEmail3" placeholder="Enter Amount" >
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

<script type="text/javascript">
  function loadExpense() {  
    let cat_id = $("#category").val();
    var requestUrl="{{route('accounts.ajax-load_expense')}}";
    var _token = $("#_token").val();
    $.ajax({  
      type: "GET",
      url: requestUrl,
      data: { cat_id: cat_id,_token:_token},
      success: function (result) {
       $(".expense_name_div").html(result);
      }
    });
  }
</script>
@endsection