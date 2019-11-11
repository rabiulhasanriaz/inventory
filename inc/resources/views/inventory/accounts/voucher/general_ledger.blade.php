@extends('layout.master')
@section('inventory_class','menu-open')
@section('customer_class','menu-open')
@section('inv_customer_acc_class','menu-open')
@section('customer_account_statement','active')
@section('content')
<section class="content">
        <section class="content-header">
          <div class="box">
              <div class="box-header">

                @if ($errors->any())
                  <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif
                   
            <form action="{{route('accounts.general_ledger')}}" method="post">
              <input type="hidden" name="_token" value="{{ csrf_token()   }}" id="_token">
              <div class="col-xs-3">
              <select class="form-control select2" required name="supplier_id">
                <option value="">Select A Supplier</option>
                @foreach($inv_Suppliers as $supplier)
                <option value="{{$supplier->inv_sup_id}}">
                  {{$supplier->inv_sup_person }}
                </option>
                @endforeach
              </select>
             </div>
             <div class="col-xs-3">
              <input type="text" name="start_date" data-date-format="yyyy-mm-dd" autocomplete="off" value="{{ request()->start_date }}" class="form-control" id="start_date" placeholder="Enter Start Date" >
             </div>
              <div class="col-xs-3">
              <input type="text" name="end_date" data-date-format="yyyy-mm-dd" autocomplete="off" value="{{ request()->end_date }}" class="form-control" id="end_date" placeholder="Enter End Date" >
             </div>
                <div class="col-xs-3">
                  <button type="submit" class="btn btn-info" name="searchbtn">Search</button>
                   
                </div>
             
                </form>

                        
                    </div>
                   
                    <!-- /.box-header -->
                    <div class="box-body">
                 </div>
             </div>
            </section>
            
            @if(request()->has('searchbtn'))
              
              <div class="box">
                    <div class="box-header">
                      <h3 class="box-title">
                       Generate General Ledger

                       </h3>
                       <a href="" style="float: right;">
                         <button class="btn btn-primary"><i class="fa fa-download"></i> Download</button>
                       </a>
                        <hr>
                    </div>
                   
                    <!-- /.box-header -->
                    <div class="box-body">

                      <div class="col-sm-4">
                       <b> Company Name: </b>
                        <span>
                         {{ App\Inv_supplier::getSupplierCompany(request()->supplier_id)->inv_sup_com_name}}
                       </span><br>
                       <b>Period: </b>
                       <span>
                         {{request()->start_date}} To {{request()->end_date}}
                       </span>
                      </div>
                      <div class="col-sm-4">
                        <b>General Ledger<br>(Account Wise)</b>
                      </div>

                      <div class="col-sm-4">
                        <b>Date: </b>
                        <span>
                          {{date('l, d-m-Y')}}
                        </span><br>
                        <b>Time: </b>
                        <span>
                          {{date('h:i:s A')}}
                        </span>
                      </div>

                      <div class="col-sm-12" style="margin-top: 50px;">
                        
                        @if($ledgers->count()>0 )
                       
                        <table class="table table-bordered table-striped" style="border-style: none !important;">
                          <tr>
                            <th>Voucher Date</th>
                            <th>Narration</th>
                            <th>Cheque No</th>
                          
                            <th>Credit</th>
                            <th>Debit</th>
                            <th>Running Balance</th>
                          </tr>
       <!--=========== Openning Balance================-->
                          <tr>
                            <td colspan="2">
                            <b>Account Name:</b>
                             
                            
                               {{App\Inv_supplier::getSupplierNameByID(request()->supplier_id)->inv_sup_person}}
                            </td>
                            <td colspan="3">
                              Openning Balance as on 
                            
                              {{request()->start_date}}
                            </td>
                            <td style="text-align: right;">
                              {{App\Inv_product_inventory::getOpenningBalance(request()->start_date,request()->supplier_id)}}
                            </td>
                          </tr>
  <!--=========== Openning Balance================-->
                          @foreach($ledgers as $ledger)
                          <tr>
                            <td>
                              {{$ledger->inv_pro_inv_issue_date}}
                            </td>
                            <td>
                              {{$ledger->inv_pro_inv_tran_desc}}
                            </td>
                            <td>
                              {{$ledger->inv_pro_inv_invoice_no}}
                            </td>
                            <td style="text-align: right;">
                              {{App\Inv_product_inventory::getCreditForLedgerByInvoice($ledger->inv_pro_inv_invoice_no)}} CR
                            </td>
                            <td style="text-align: right;">
                              {{App\Inv_product_inventory::getDebitForLedgerByInvoice($ledger->inv_pro_inv_invoice_no)}} DR
                            </td>
                            <td style="text-align: right;">
                              {{ number_format(App\Inv_product_inventory::getRunningBalanceByDate($ledger->inv_pro_inv_issue_date,request()->supplier_id),2 )}}
                            </td>
                           
                          </tr>
                          @endforeach
                        </table>
                        @else
                           <h2> No Data Found In Between {{request()->start_date}} and {{request()->end_date}}</h2>
                        @endif
                      </div>
                      
                    </div>
                    <!-- /.box-body -->
                  </div>
              @endif
            
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

$(document).ready(function(){

$( "#start_date" ).datepicker({
       daysOfWeekHighlighted: "7",
        todayHighlight: true,
        autoclose: true,
     });
$( "#to" ).datepicker({
       daysOfWeekHighlighted: "7",
        todayHighlight: true,
     });
});

$(document).ready(function(){

$( "#end_date" ).datepicker({
       daysOfWeekHighlighted: "7",
        todayHighlight: true,
        autoclose: true,
     });
$( "#to" ).datepicker({
       daysOfWeekHighlighted: "7",
        todayHighlight: true,
     });
});
 $("#download_statement_btn").click(function () {
            let start_date = $("#start_date").val();
            let end_date = $("#end_date").val();
            let _toekn=$("#_toekn").val();
            
        });

</script>
@endsection