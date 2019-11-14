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
                   
            <form action="" method="get">
              <input type="hidden" name="_token" value="{{ csrf_token()   }}" id="_token">
             <div class="col-xs-3">
              <input type="text" name="start_date" data-date-format="yyyy-mm-dd" autocomplete="off" value="{{ request()->start_date }}" class="form-control" id="start_date" placeholder="Enter Start Date" >
             </div>
              <div class="col-xs-3">
              <input type="text" name="end_date" data-date-format="yyyy-mm-dd" autocomplete="off" value="{{ request()->end_date }}" class="form-control" id="end_date" placeholder="Enter End Date" >
             </div>
                <div class="col-xs-3">
                  <button type="submit" class="btn btn-info" name="searchbtn">Search</button>
                   <button type="submit" class="btn btn-warning" name="printdfbtn" id="download_statement_btn">Download</button>
                </div>
             
                </form>

                        
                    </div>
                   
                    <!-- /.box-header -->
                    <div class="box-body">
                 </div>
             </div>

              
              <h1>
               Account Statement Details of  {{ App\Inv_customer::getCustomerCompany(request()->customer_id)->inv_cus_com_name }} 
              </h1>
        
            </section>
            <div class="box">
                    <div class="box-header">
                      <h3 class="box-title">
                        {{ App\Inv_customer::getCustomerCompany(request()->customer_id)->inv_cus_name }}'s
                       Statement Details</h3>
                        
                    </div>
                   
                    <!-- /.box-header -->
                    <div class="box-body">

                      <div class="col-xs-12">
                        <h4><b>
                           {{ App\Inv_customer::getCustomerCompany(request()->customer_id)->inv_cus_name }}'s
                        </b> Total Discount is {{App\Inv_product_inventory::getTotalDiscountByCustomerID(request()->customer_id)}} Tk.
                      </h4>
                      </div>
                      <table id="example1" class="table table-bordered table-striped" style="margin-top: 20px;">
                        <thead>
                        <tr>
                          <th>SL</th>
                          <th>Issue Date</th>
                          <th>Description</th>
                          <th>Credit</th>
                          <th>Debit</th>
                          <th>Balance</th>
                          <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                       
                        @php
                        
                          $sl=0;
                          $total_credit=0;
                          $total_debit=0;
                          $total_balance=0;
                          
                        @endphp

                        @foreach($inv_custs as $inv_cust)
                        
                        @php

                            $total_credit+=App\Inv_product_inventory::getCreditByInvoiceNo($inv_cust->inv_pro_inv_invoice_no);

                            $total_debit+=App\Inv_product_inventory::getDebitByInvoiceNo($inv_cust->inv_pro_inv_invoice_no);

                           

                        @endphp

                        <tr>
                          <td>
                            {{ ++$sl }}
                          </td>
                          <td>
                            {{ $inv_cust->inv_pro_inv_issue_date }}
                          </td>
                          <td>
                            {{$inv_cust->inv_pro_inv_tran_desc}}
                          </td>
                          <td style="text-align: right;">
                            {{ number_format(App\Inv_product_inventory::getCreditByInvoiceNo($inv_cust->inv_pro_inv_invoice_no),2)}}
                          </td>
                          <td style="text-align: right;">
                            {{ number_format(App\Inv_product_inventory::getDebitByInvoiceNo($inv_cust->inv_pro_inv_invoice_no),2)}}
                          </td>
                          <td style="text-align: right;">
                            {{number_format((App\Inv_product_inventory::getCreditByInvoiceNo($inv_cust->inv_pro_inv_invoice_no)) - (App\Inv_product_inventory::getDebitByInvoiceNo($inv_cust->inv_pro_inv_invoice_no)),2) }}
                          </td>
                          <td style="text-align: center;">
                              <a href="#" onclick="show_invoice_details('{{ $inv_cust->inv_pro_inv_invoice_no }}')" data-toggle="modal" data-target="#invoice_details">

                              <i class="fa fa-eye"></i>
                            </a>
                            </td>
                        </tr>
                         @endforeach

                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="3" style="text-align:right; font-weight: bolder;">Total:</td>
                            <td style="font-weight: bolder; text-align: right;">
                              {{number_format($total_credit,2)}}
                            </td>
                            <td style="font-weight: bolder; text-align: right;">
                              {{number_format($total_debit,2)}}
                            </td>
                            <td style="font-weight: bolder; text-align: right;">
                              {{number_format(($total_credit-$total_debit),2)}}
                            </td >
                            <td style="text-align: center;font-weight: bolder;">---</td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                    <!-- /.box-body -->
                  </div>
                 </section>


<!-- Modal -->
<div class="modal fade" id="showInvoiceDetailsModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Invoice Details</h4>
      </div>
      <div class="modal-body">
        <div class="load-details"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    
  </div>
</div>



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

//InitializeDate();
/*
function InitializeDate() {
    var date = new Date();
    var dd = date.getDate();             
    var mm = date.getMonth() + 1;
    var yyyy = date.getFullYear();

    var ToDate = yyyy+'-'+mm+'-'+dd;
    var FromDate = yyyy+'-'+(mm-1)+'-'+dd;
    $('#start_date').datepicker('setDate', FromDate);
    $('#end_date').datepicker('setDate', ToDate);
}*/

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
            let route = "{{ route('customer.accounts.download-customer-account-statement-details', request()->customer_id) }}?start_date="+ start_date +"&end_date="+end_date;
            window.open(route, '_blank');
        });

</script>
<script type="text/javascript">

  function show_invoice_details(invoice_id) {

    var requestUrl="{{route('customer.accounts.cusotmer-voucher-details')}}";
    var _token=$("#_token").val();
    $.ajax({  
      type: "GET",
      url: requestUrl,
      data: { invoice_id: invoice_id,_token:_token},
      success: function (result) {
       $(".load-details").html(result);
       $("#showInvoiceDetailsModal").modal("show");
      }
    });
  }
</script>
@endsection