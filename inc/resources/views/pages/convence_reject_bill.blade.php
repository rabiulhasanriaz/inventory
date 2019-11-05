@extends('layout.master')
@section('oms_class','menu-open')
@section('oms_convence_class','menu-open')
@section('convence_reject_bill','active')
@section('content')
<section class="content">
<section class="content-header">
      <h1>
        Convence
      </h1>
    </section>
	<div class="box">
            <div class="box-header">
              <h3 class="box-title">Rejected Convence Bill</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>SL</th>
                  @if(Auth::user()->au_user_type == 4)
                  <th>Entry Date</th>
                  @endif
                  <th>Bill Date</th>
                  <th>Employee</th>
                  <th>What For</th>
                  <th>From</th>
                  <th>Mode</th>
                  <th>To</th>
                  <th>Debit/খরচ</th>
                  <th>Balance</th>
                  @if( Auth::user()->au_user_type != 4 )
                  <th class="text-center">Action</th>
                  @endif
                </tr>
                </thead>
                <tbody>
                @php($balance=0)
                @php($sl=0)
                @foreach($rejects as $reject)
                <tr>
                  <td>{{ ++$sl }}</td>
                  @if(Auth::user()->au_user_type == 4)
                  <td>{{ $reject->oms_insert_at }}</td>
                  @endif
                  <td>{{ $reject->oms_bill_date }}</td>
                  <td>{{ $reject->employee_info['au_name'] }}</td>
                  <td>
                    <a href="{{ url('/client_feedback_oms',['id' => $reject->oms_what_for]) }}" title="{{ $reject->what_info_oms['qb_company_name'] }}" class="opener" target="_blank">
                      {{ $reject->what_info_oms['qb_mobile'] }}
                    </a>
                  </td>
                  <td>{{ $reject->oms_dfrom }}</td>
                  <td>{{ $reject->oms_mode }}</td>
                  <td>{{ $reject->oms_dto }}</td>
                  <td align="right">{{ number_format($reject->oms_debit,2) }}</td>
                  @php($balance = $balance - $reject->oms_debit)
                  <td align="right">{{ number_format($balance,2) }}</td>
                  @if( Auth::user()->au_user_type != 4 )
                  <td align="center"><a href="{{ url('/oms_update_bill',['id' => $reject->sl_id]) }}" class="btn btn-info btn-sm">Edit</a></td>
                  @endif
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                  @if( Auth::user()->au_user_type == 4 )
                  <td colspan="9" align="right">Total</td>
                  <td align="right" >{{ number_format($balance,2) }}</td>
                  @elseif(  Auth::user()->au_user_type != 4  )
                  <td colspan="8" align="right">Total</td>
                  <td align="right" >{{ number_format($balance,2) }}</td>
                  <td></td>
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
