@extends('layout.master')
@section('inventory_class','menu-open')
@section('supplier_class','menu-open')
@section('inv_supplier_acc_class','menu-open')
@section('supplier_account_statement','active')
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
               Account Statement Details of  {{ App\Inv_supplier::getSupplierCompany(request()->supplier_id)->inv_sup_com_name }} 
              </h1>
        
            </section>
            <div class="box">
                    <div class="box-header">
                      <h3 class="box-title">
                        {{ App\Inv_supplier::getSupplierCompany(request()->supplier_id)->inv_sup_person }}'s
                       Statement Details</h3>
                        
                    </div>
                   
                    <!-- /.box-header -->
                    <div class="box-body">


                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th>SL</th>
                          <th>Issue Date</th>
                          <th>Description</th>
                          <th>Credit</th>
                          <th>Debit</th>
                          <th>Balance</th>
                          
                          
                        </tr>
                        </thead>
                        <tbody>
                       
                        @php
                        
                          $sl=0;
                          $total_credit=0;
                          $total_debit=0;
                          $total_balance=0;
                          
                        @endphp

                        @foreach($inv_sups as $inv_sup)
                        
                        @php

                            $total_credit+=$inv_sup->inv_sup_inv_credit;

                            $total_debit+=$inv_sup->inv_sup_inv_debit;

                            $total_balance+=$inv_sup->inv_sup_inv_credit-$inv_sup->inv_sup_inv_debit;

                        @endphp

                        <tr>
                          <td>
                            {{ ++$sl }}
                          </td>
                          <td>
                            {{ $inv_sup->inv_sup_inv_issue_date }}
                          </td>
                          <td>
                            {{$inv_sup->inv_sup_inv_description}}
                          </td>
                          <td>
                            {{ $inv_sup->inv_sup_inv_credit }}
                          </td>
                          <td>
                            {{$inv_sup->inv_sup_inv_debit }}
                          </td>
                          <td>
                            {{ $inv_sup->inv_sup_inv_credit-$inv_sup->inv_sup_inv_debit }}
                          </td>
                        </tr>
                         @endforeach

                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="3" style="text-align:right; font-weight: bolder;">Total:</td>
                            <td style="font-weight: bolder; text-align: right;">
                              {{$total_credit}}
                            </td>
                            <td style="font-weight: bolder; text-align: right;">
                              {{$total_debit}}
                            </td>
                            <td style="font-weight: bolder; text-align: right;">
                              {{$total_balance}}
                            </td >
                            
                          </tr>
                        </tfoot>
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
            let route = "{{ route('inventory.supplier.accounts.download-supplier-account-statement-details', request()->supplier_id) }}?start_date="+ start_date +"&end_date="+end_date;
            window.open(route, '_blank');
        });

</script>
@endsection