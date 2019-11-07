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
                Bank
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
                          <th>Bank Name</th>
                          <th>Branch Name</th>
                          <th>Account Name</th>
                          <th>Account No</th>
                          <th>Open Date</th>
                          <th>Account Type</th>
                          <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($sl=0)
                        @foreach ($bank_infos as $bank)
                        <tr>
                            <td>{{ ++$sl }}</td>
                            <td>{{ $bank->bank_info->bank_name }}</td>
                            <td>{{ $bank->inv_abi_branch_name }}</td>
                            <td>{{ $bank->inv_abi_account_name }}</td>
                            <td>{{ $bank->inv_abi_account_no }}</td>
                            <td>{{ $bank->inv_abi_open_date }}</td>
                            <td>{{ ($bank->inv_abi_account_type == 1)?'Bank':'Cash' }}</td>

                            <td align="center">
                                <a href="{{ route('accounts.update-bank', $bank->inv_abi_id) }}" class="action_btn btn_edit"><i class="fa fa-edit"></i></a>
                                <a href="{{ route('accounts.delete-bank', $bank->inv_abi_id) }}" onclick="return confirm('Are you sure?')" class="action_btn btn_delete"><i class="fa fa-trash"></i></a>
                            </td>
                          </tr>
                        @endforeach
                        </tbody>
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