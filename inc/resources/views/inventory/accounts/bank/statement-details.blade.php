@extends('layout.master')
@section('inventory_class','menu-open')
@section('accounts_class','menu-open')
@section('bank_class','menu-open')
@section('bank_list','active')
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
                {{ $bank_info->bank_info->bank_name }} ({{ $bank_info->inv_abi_account_no }})
              </h1>
        
            </section>
            <div class="box">
                    <div class="box-header">
                      <h3 class="box-title">Bank List</h3>
                    </div>
                   
                    <!-- /.box-header -->
                    <div class="box-body">


                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th>SL</th>
                          <th>Transaction Date</th>
                          <th>Reference</th>
                          <th>Description</th>
                          <th>Credit</th>
                          <th>Debit</th>
                          <th>Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($sl=0)
                        @php($total_balance = 0)
                        @php($total_credit = 0)
                        @php($total_debit = 0)
                        @foreach ($statements as $statement)
                            @php($debit = $statement->inv_abs_debit)
                            @php($credit = $statement->inv_abs_credit)
                            @php($total_debit = $total_debit + $debit)
                            @php($total_credit = $total_credit + $credit)
                            @php($total_balance = $total_balance + $credit - $debit)
                            <tr>
                                <td>{{ ++$sl }}</td>
                                <td>{{ $statement->inv_abs_transaction_date }}</td>
                                <td>{{ $statement->inv_abs_reference_type }}</td>
                                <td>{{ $statement->inv_abs_description }}</td>
                                <td>{{ $credit }}</td>
                                <td>{{ $debit }}</td>
                                <td>{{ $total_balance }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">Total:</td>
                                <td>{{ $total_credit }}</td>
                                <td>{{ $total_debit }}</td>
                                <td>{{ $total_balance }}</td>
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