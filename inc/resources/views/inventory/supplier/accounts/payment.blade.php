@extends('layout.master')
@section('inventory_class','menu-open')
@section('supplier_class','menu-open')
@section('inv_supplier_acc_class','menu-open')
@section('supplier_payment','active')
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
             Supplier's Payment 
              </h1>       
            </section>
                   <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Payment</h3>
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
                   
                    <form action="{{route('inventory.supplier.accounts.payment')}}" method="post" class="form-horizontal" id="form-id">
                      <input type="hidden" name="_token" value="{{csrf_token()}}" id="_tokens">
                      <div class="box-body">


                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Supplier:</label>
                          <div class="col-sm-6">
                            <select name="supplier_id" required  value="{{old('supplier_id')}}"  class="form-control select2" id="supplier_id" onchange="loadAvailableBalanceOfSupplier()">

                             <option value="">Select A Supplier</option>
                              @foreach($inv_suppliers as $inv_supplier)
                              <option value="{{$inv_supplier->inv_sup_id}}">
                                {{$inv_supplier->inv_sup_person }} 
                              ({{$inv_supplier->inv_sup_com_name}})
                              </option>
                              @endforeach
                            </select>
                         
                          </div>
                          <div class="col-sm-3 supplier_balance_div"></div>
                        </div>

                        

                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Payment Method:</label>
                          <div class="col-sm-6">
                            <select name="bank_id" required  value="{{old('bank_id')}}"  class="form-control select2" id="bank_id" onchange="loadAvailableBalanceOfBank()">
                              <option value="">Select A Method</option>
                              
                              @foreach($banks as $bank)
                              <option value="{{$bank->inv_abi_id}}">
                                {{$bank->bank_info['bank_name'] }} ({{$bank->inv_abi_account_no}})

                              </option>
                              @endforeach
                            </select>
                         
                          </div>
                          <div class="bank_balance_div"></div>
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
                            <input type="number" name="amount" autocomplete="off" value="{{ old('amount') }}" class="form-control" id="inputEmail3" placeholder="Write Amount Here" required min="0">
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Discount:</label>
                          <div class="col-sm-6">
                            <input type="number" name="discount" autocomplete="off" value="{{ old('discount') }}" class="form-control" id="inputEmail3" placeholder="Write Discount Amount Here" min="0">
                          </div>
                        </div>

                        

                      </div>
                      <!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-3">
                            <button type="submit" id="submit-button-id" class="btn btn-info pull-right"> Submit</button>
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
        endDate: "today",
     });
$( "#to" ).datepicker({
       daysOfWeekHighlighted: "7",
        todayHighlight: true,
     });
});

</script>
<script type="text/javascript">
  
  function loadAvailableBalanceOfBank() {
    let bank_id = $("#bank_id").val();
    var requestUrl="{{route('accounts.ajax-load-bank-balance')}}";
    var _token = $("#_token").val();
    //$("#_token").val();
    $.ajax({  
      type: "GET",
      url: requestUrl,
      data: { bank_id: bank_id,_token:_token},
      success: function (result) {
       $(".bank_balance_div").html(result);
      }
    });
  }
  function loadAvailableBalanceOfSupplier() {
    let supplier_id = $("#supplier_id").val();
  
    
    var requestUrl="{{route('accounts.ajax-load-supplier-balance')}}";
  
   var _token=$("#_token").val();

    $.ajax({  
      type: "GET",
      url: requestUrl,
      data: { supplier_id: supplier_id,_token:_token},
      success: function (result) {
       $(".supplier_balance_div").html(result);
      }
    });
  }
  $("#form-id").submit(function (event) {
let btn = $("#submit-button-id");
btn.prop('disabled', true);
setTimeout(function(){
btn.prop('disabled', false);
}, 5000);
return true;
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