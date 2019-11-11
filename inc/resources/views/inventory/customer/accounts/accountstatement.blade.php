@extends('layout.master')
@section('inventory_class','menu-open')
@section('customer_class','menu-open')
@section('inv_customer_acc_class','menu-open')
@section('customer_account_statement','active')
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
                Customer's Account Statements
              </h1>
        
            </section>
            <div class="box">
                    <div class="box-header">
                      <h3 class="box-title">Account Statement</h3>
                    </div>
                   
                    <!-- /.box-header -->
                    <div class="box-body">


                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th>SL</th>
                          <th>Company</th>
                          <th>Name</th>
                          <th>Credit</th>
                          <th>Debit</th>
                          <th>Balance</th>
                          
                          <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                      @php
                        
                          $sl=0;
                          $total_credit=0;
                          $total_debit=0;
                          $total_balance=0;
                          
                        @endphp

                        @foreach($inv_cus_invts as $inv_cus_invt)
                       
                         @php

                            $total_credit+=App\Inv_product_inventory::getCreditByID($inv_cus_invt->inv_cus_id);

                            $total_debit+=App\Inv_product_inventory::getDebitByID($inv_cus_invt->inv_cus_id);

                            $total_balance+=App\Inv_product_inventory::getBalanceByID($inv_cus_invt->inv_cus_id);

                        @endphp

                        <tr>
                          <td>{{ ++$sl }}</td>
                          <td>{{ $inv_cus_invt->inv_cus_com_name }}</td>
                          <td>{{ $inv_cus_invt->inv_cus_name}}</td>
                          <td>{{App\Inv_product_inventory::getCreditByID($inv_cus_invt->inv_cus_id)}}</td>
                          <td>{{App\Inv_product_inventory::getDebitByID($inv_cus_invt->inv_cus_id)}}</td>
                          <td>{{App\Inv_product_inventory::getBalanceByID($inv_cus_invt->inv_cus_id)}}</td>

                          <td style="text-align: center;">
                            <a href="{{route('customer.accounts.account-statement-details',$inv_cus_invt->inv_cus_id)}}">
                              View Details
                            </a>
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
                            <td style="font-weight: bolder; text-align: center;">---</td>

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