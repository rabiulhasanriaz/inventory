@extends('layout.master')
@section('oms_class','menu-open')
@section('oms_lunch_class','menu-open')
@section('lunch_reject_bill','active')
@section('content')
<section class="content">
<section class="content-header">
      <h1>
        Lunch
      </h1>
    </section>
	<div class="box">
            <div class="box-header">
              <h3 class="box-title">Reject Lunch Bill</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>SL</th>
                  @if( Auth::user()->au_user_type == 4 )
                  <th>Entry Date</th>
                  @endif
                  <th>Bill Date</th>
                  <th>What For</th>
                  <th>Employee</th>
                  <th>Reason</th>
                  <th>Person</th>
                  <th>Debit/খরচ</th>
                  @if( Auth::user()->au_user_type != 4 )
                  <th class="text-center">Action</th>
                  @endif
                </tr>
                </thead>
                <tbody>
                @php($sl=0)
                @php($balance=0)
                @foreach($lunch_rejects as $lunch_reject)
                <tr>
                  <td>{{ ++$sl }}</td>
                  @if( Auth::user()->au_user_type == 4 )
                  <td>{{ $lunch_reject->oms_insert_at }}</td>
                  @endif
                  <td>{{ $lunch_reject->oms_bill_date }}</td>
                  <td>
                    <a href="{{ url('/client_feedback_oms',['id' => $lunch_reject->oms_what_for]) }}" title="{{ $lunch_reject->what_info_oms['qb_company_name'] }}" target="_blank">
                      {{ $lunch_reject->what_info_oms['qb_mobile'] }}
                    </a>
                  </td>
                  <td>{{ $lunch_reject->employee_info['au_name'] }}</td>
                  <td>{{ $lunch_reject->oms_reason }}</td>
                  <td>{{ $lunch_reject->oms_person }}</td>
                  <td align="right">{{ number_format($lunch_reject->oms_debit,2) }}</td>
                  @if( Auth::user()->au_user_type != 4 )
                  <td align="center">
                      <a href="{{ url('/oms_lunch_update_bill',['id' => $lunch_reject->sl_id]) }}" class="btn btn-info btn-sm">Edit</a>
                  </td>
                  @endif
                </tr>
                @php($balance = $balance + $lunch_reject->oms_debit)
                @endforeach
                </tbody>
                <tfoot>
                  @if( Auth::user()->au_user_type == 4 )
                  <td colspan="7" align="right">Total</td>
                  <td align="right">{{ number_format($balance,2) }}</td>
                  @elseif( Auth::user()->au_user_type != 4 )
                  <td colspan="6" align="right">Total</td>
                  <td align="right">{{ number_format($balance,2) }}</td>
                  <td></td>
                  @endif


                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
         </section>
@endsection
