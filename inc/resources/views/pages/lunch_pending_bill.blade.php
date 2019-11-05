@extends('layout.master')
@section('oms_class','menu-open')
@section('oms_lunch_class','menu-open')
@section('lunch_pending_bill','active')
@section('content')
<section class="content">
<section class="content-header">
      <h1>
        Lunch
      </h1>
    </section>
	<div class="box">
            <div class="box-header">
              <h3 class="box-title">Pending Lunch Bill</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>SL</th>
                  @if( Auth::user()->au_user_type == 4 || Auth::user()->au_user_type == 5)
                  <th>Entry Date</th>
                  @endif
                  <th>Bill Date</th>
                  <th>What For</th>
                  <th>Reason</th>
                  <th>Employee</th>
                  <th>Person</th>
                  <th>Debit/খরচ</th>
                  <th>Balance</th>
                  @if( Auth::user()->au_user_type == 4 || Auth::user()->au_user_type == 5)
                  <th class="text-center">Action</th>
                  @endif
                </tr>
                </thead>
                <tbody>
                @php($sl=0)
                @php($balance = 0)
                @foreach($lunch_bills as $lunch_bill)
                <tr>
                  <td>{{ ++$sl }}</td>
                  @if( Auth::user()->au_user_type == 4 || Auth::user()->au_user_type == 5)
                  <td>{{ $lunch_bill->oms_insert_at }}</td>
                  @endif
                  <td>{{ $lunch_bill->oms_bill_date }}</td>
                  <td>
                    <a href="{{ url('/client_feedback_oms',['id' => $lunch_bill->oms_what_for]) }}" title="{{ $lunch_bill->what_info_oms['qb_company_name'] }}" class="opener" target="_blank">
                      {{ $lunch_bill->what_info_oms['qb_mobile'] }}
                    </a>
                  </td>
                  <td>{{ $lunch_bill->oms_reason }}</td>
                  <td>{{ $lunch_bill->employee_info['au_name'] }}</td>
                  <td>{{ $lunch_bill->oms_person }}</td>
                  <td align="right">{{ number_format($lunch_bill->oms_debit,2) }}</td>
                  @php($balance = $balance + $lunch_bill->oms_debit)
                  <td align="right">{{ number_format($balance,2) }}</td>
                  @if( Auth::user()->au_user_type == 4 || Auth::user()->au_user_type == 5)
                  <td align="center">
                      <a href="{{ route('pages.lunch_pending_bill_confirm',['id' => $lunch_bill->sl_id]) }}" class="btn btn-success btn-xs" style="color: green;">
                      <span class="glyphicon glyphicon-ok"></span>
                  </a>
                  &nbsp;&nbsp;
                      <a href="{{ route('pages.lunch_pending_bill_reject',['id' => $lunch_bill->sl_id]) }}" class="btn btn-danger btn-xs" style="color: red;">
                      <span class="glyphicon glyphicon-remove"></span>
                  </a>
                  </td>
                  @endif
                </tr>
                @endforeach

                </tbody>
                <tfoot>
                  @if( Auth::user()->au_user_type == 4 || Auth::user()->au_user_type == 5)
                  <td colspan="8" align="right">Total</td>
                  <td align="right">{{ number_format($balance,2) }}</td>
                  <td></td>
                  @else
                  <td colspan="6" ></td>
                  <td align="right">Total</td>
                  <td align="right">{{ number_format($balance,2) }}</td>
                  @endif
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
         </section>
@endsection
@section('custom_script')
<script type="text/javascript">
  window.onload = function(){
  var a = document.querySelectorAll('.opener'), w = [], url, random, i;
  for(i = 0; i < a.length; i++){
    (function(i){
      a[i].onclick = function(e) {
        if (!w[i] || w[i].closed) {
          url = this.href;
          random = Math.floor((Math.random() * 100) + 1);
          w[i] = window.open(url);
        } else {
          console.log('window ' + url + ' is already opened');
        }
        e.preventDefault();
        w[i].focus();
      };
    })(i);
  }
  };
</script>
@endsection
