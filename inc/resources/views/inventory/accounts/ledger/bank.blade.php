@extends('layout.master')
@section('inventory_class','menu-open')
@section('accounts_class','menu-open')
@section('ledger_class','menu-open')
@section('ledger_bank','active')
@section('content')
<section class="content-header">
    @if(session()->has('err_bank'))
    <div class="alert alert-danger alert-dismissible" role="alert">
    {{ session('err_bank') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>
    @endif
    @if(session()->has('suc_bank'))
    <div class="alert alert-success alert-dismissible" role="alert">
    {{ session('suc_bank') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>
    @endif
    <div class="box">
        <div class="box-header">
             
      <form action="" method="get">
        <input type="hidden" name="_token" value="" id="_token">
       <div class="col-xs-3">
        <input type="text" name="dateinput" data-date-format="yyyy-mm-dd" autocomplete="off" value="" class="form-control" id="date" placeholder="Enter Start Date" >
       </div>
          <div class="col-xs-3">
            <button type="submit" class="btn btn-info" name="date">CLICK</button>
          </div>
          </form>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
           </div>
       </div>
        <h1>
         Bank Income & Expense  
        </h1>
  
      </section>
      <div class="box" id="bank">
          @if (request()->has('dateinput'))
              <!-- /.box-header -->
            {{ Form::open(['action' => 'Inventory\InvLedgerController@bank_ledger_submit' , 'method' => 'post']) }}
              <input type="text" name="issue_date" hidden value="{{ request()->dateinput }}">
                @foreach($old_data_group_by as $old_group)
              <div class="box-body"  style="margin-left: 10px; border: 1px solid; margin-top: 5px;">
                <div class="col-md-6 bank-class">
                  <div class="row">
                    <div class="col-md-8">
                      <select name="" id="" class="form-control js-example-basic-single ijklmnop bank-id bank_id" onchange="loadAvailableBalanceOfBank()" required>
                        <option  value="">Select Bank</option>
                        <option v-for="bank_det in banks_info" :value="bank_det.inv_abi_id">
                          @{{ bank_det.inv_abi_account_name }}(@{{ bank_det.inv_abi_account_no }})
                        </option>
                      </select>
                    </div>
                    <div class="col-md-4 bank_balance_div" id="bank_balance" v-model="bank_balance">

                    </div>
                  </div>
                </div>
                <div class="col-md-6 text-right">
                  <button type="button" class="btn btn-success btn-sm" @click="addNewBankDetails()">
                    <i class="fa fa-plus"></i>
                  </button>
                </div>
                <div class="col-md-12">
                <table id="example6" class="table table-bordered table-striped abcd" style="margin-top: 20px;">
                  <thead>
                  <tr>
                    <th style="width: 55px;">A/C Name</th>
                    <th style="width: 225px;">Payment By</th>
                    <th>Particulars</th>
                    <th style="width: 116px;">Debit</th>
                    <th style="width: 116px;">Credit</th>
                    <th style="width: 127px;">Balance Amount</th>
                    <th>Add</th>
                  </tr>
                  </thead>
                  <tbody id="bank_tbody">
                    @foreach($old_group as $old)
                  <tr>
                      <td></td>
                      <td>
                        <select name="cus_sup[]" id="" class="form-control js-example-basic-single" required disabled>
                          <option value="">Select One</option>
                          
                        @foreach ($customers as $cus)
                        <option value="{{ $cus->inv_cus_id }}">
                          C - {{ $cus->inv_cus_name }}
                        </option>
                        @endforeach
                        @foreach ($suppliers as $sup)
                        <option value="{{ $sup->inv_sup_id }}">
                            S - {{ $sup->inv_sup_person }}
                        </option>
                        @endforeach
                        </select>
                      </td>
                      <td>
                        <input type="text" class="form-control" style="padding:1px;" value="{{ $old->bank_ledger_detail['inv_pro_inv_tran_desc'] }}" name="desc[]" required disabled>
                      </td>
                      <td>
                          <input type="text" class="form-control text-right" id="debit" value="{{ $old->bank_ledger_detail['inv_pro_inv_debit'] }}" name="debit[]" value="{{ old('debit') }}" style="width: 100px; padding:1px;" required disabled>
                      </td>
                      <td>
                          <input type="text" class="form-control text-right" id="credit" value="{{ $old->bank_ledger_detail['inv_pro_inv_credit'] }}" name="credit[]" value="{{ old('credit') }}" style="width: 100px; padding:1px;" required disabled>
                      </td>
                      <td class="text-right">
                          @{{ total_bank_have }}
                      </td>
                      <td style="text-align: center;">
                        <input type="text" name="bank_id[]" class="qrstuv" hidden>
                        <button class="btn btn-success btn-xs" type="button" onclick="add_bank_table(this)">
                          <i class="fa fa-edit"></i>
                        </button> 
                      </td>
                  </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td>
                          <select name="cus_sup[]" id="" class="form-control js-example-basic-single" required>
                            <option value="">Select One</option>
                            
                          @foreach ($customers as $cus)
                          <option value="{{ $cus->inv_cus_id }}">
                            C - {{ $cus->inv_cus_name }}
                          </option>
                          @endforeach
                          @foreach ($suppliers as $sup)
                          <option value="{{ $sup->inv_sup_id }}">
                              S - {{ $sup->inv_sup_person }}
                          </option>
                          @endforeach
                          </select>
                        </td>
                        <td>
                          <input type="text" class="form-control" style="padding:1px;" value="" name="desc[]" required>
                        </td>
                        <td>
                            <input type="text" class="form-control text-right" id="debit" value="" name="debit[]" value="{{ old('debit') }}" style="width: 100px; padding:1px;" required>
                        </td>
                        <td>
                            <input type="text" class="form-control text-right" id="credit" value="" name="credit[]" value="{{ old('credit') }}" style="width: 100px; padding:1px;" required>
                        </td>
                        <td class="text-right">
                            @{{ total_bank_have }}
                        </td>
                        <td style="text-align: center;">
                          <input type="text" name="bank_id[]" class="qrstuv" hidden>
                          <button class="btn btn-success btn-xs" type="button" onclick="add_bank_table(this)">
                            <i class="fa fa-plus"></i>
                          </button> 
                        </td>
                    </tr>
                  </tbody>
                </table>
                </div>
              </div>
              @endforeach
              <div v-for="(banks,k) in bank_summery" :key="k" class="abcdefgh">
              <div class="box-body" v-model="banks.new_addition" style="margin-left: 10px; border: 1px solid; margin-top: 5px;">
                  <div class="col-md-6 bank-class">
                    <div class="row">
                      <div class="col-md-8">
                        <select name="" id="" class="form-control js-example-basic-single ijklmnop bank-id bank_id" onchange="loadAvailableBalanceOfBank()" required>
                          <option  value="">Select Bank</option>
                          <option v-for="bank_det in banks_info" :value="bank_det.inv_abi_id">
                            @{{ bank_det.inv_abi_account_name }}(@{{ bank_det.inv_abi_account_no }})
                          </option>
                        </select>
                      </div>
                      <div class="col-md-4 bank_balance_div" id="bank_balance" v-model="bank_balance">
  
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 text-right">
                    <button type="button" class="btn btn-success btn-sm" @click="addNewBankDetails()">
                      <i class="fa fa-plus"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" @click="deleteBankDetails(k,banks)">
                        <i class="fa fa-minus"></i>
                    </button>
                  </div>
                  <div class="col-md-12">
                  <table id="example6" class="table table-bordered table-striped abcd" style="margin-top: 20px;">
                    <thead>
                    <tr>
                      <th style="width: 55px;">A/C Name</th>
                      <th style="width: 225px;">Payment By</th>
                      <th>Particulars</th>
                      <th style="width: 116px;">Debit</th>
                      <th style="width: 116px;">Credit</th>
                      <th style="width: 127px;">Balance Amount</th>
                      <th>Add</th>
                    </tr>
                    </thead>
                    <tbody id="bank_tbody">
                      
            
                      <tr>
                          <td></td>
                          <td>
                            <select name="cus_sup[]" id="" class="form-control js-example-basic-single" required>
                              <option value="">Select One</option>
                              
                            @foreach ($customers as $cus)
                            <option value="{{ $cus->inv_cus_id }}">
                              C - {{ $cus->inv_cus_name }}
                            </option>
                            @endforeach
                            @foreach ($suppliers as $sup)
                            <option value="{{ $sup->inv_sup_id }}">
                                S - {{ $sup->inv_sup_person }}
                            </option>
                            @endforeach
                            </select>
                          </td>
                          <td>
                            <input type="text" class="form-control" style="padding:1px;" value="" name="desc[]" required>
                          </td>
                          <td>
                              <input type="text" class="form-control text-right" id="debit" value="" name="debit[]" value="{{ old('debit') }}" style="width: 100px; padding:1px;" required>
                          </td>
                          <td>
                              <input type="text" class="form-control text-right" id="credit" value="" name="credit[]" value="{{ old('credit') }}" style="width: 100px; padding:1px;" required>
                          </td>
                          <td class="text-right">
                              @{{ total_bank_have }}
                          </td>
                          <td style="text-align: center;">
                            <input type="text" name="bank_id[]" class="qrstuv" hidden>
                            <button class="btn btn-success btn-xs" type="button" onclick="add_bank_table(this)">
                              <i class="fa fa-plus"></i>
                            </button> 
                          </td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
                </div>
            </div>
            <div class="col-md-12 text-right">
                <button type="submit" hidden id="submit_bank_transaction"></button>
                <button type="button" class="btn btn-success" style="" onclick="set_bank_id()">SUBMIT</button>
            </div>
             @endif
            {{ Form::close() }}
            
              <!-- /.box-body -->
              <div hidden id="bank-table-body">
                <table>
                  <tbody class="bank_tbody" id="copy_tr_element">  
                  <tr>
                      <td></td>
                      <td>
                        <select name="cus_sup[]" id="" class="form-control ">
                          <option value="">Select One</option>
                          @foreach ($customers as $cus)
                          <option value="{{ $cus->inv_cus_id }}">
                            C - {{ $cus->inv_cus_name }}
                          </option>
                          @endforeach
                          @foreach ($suppliers as $sup)
                          <option value="{{ $sup->inv_sup_id }}">
                              S - {{ $sup->inv_sup_person }}
                          </option>
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <input type="text" class="form-control" style="padding:1px;" name="desc[]">
                      </td>
                      <td>
                          <input type="text" class="form-control text-right" id="debit" name="debit[]" value="{{ old('debit') }}"  style="width: 100px; padding:1px;">
                      </td>
                      <td>
                          <input type="text" class="form-control text-right" id="credit" name="credit[]" value="{{ old('credit') }}" style="width: 100px; padding:1px;">
                      </td>
                      <td class="text-right">
                        
                      </td>
                      <td style="text-align: center;">
                        <input type="text" name="bank_id[]" class="qrstuv" hidden>
                        <button class="btn btn-success btn-xs" type="button" onclick="add_bank_table(this)">
                          <i class="fa fa-plus"></i>
                        </button>
                        <button class="btn btn-danger btn-xs" type="button" onclick="remove_bank_table(this)">
                            <i class="fa fa-minus"></i>
                        </button> 
                      </td>
                  </tr>
                </tbody>
              </table>
              </div>
            </div>
           </section>
@endsection
@section('custom_script')
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
<script>

// $(document).ready(function (){
// $(".add_new_bank_table_btn").click(function () {
  
// });
// });

function bank_balance(){
  let total_bank_have = document.getElementById('bank_balance').value; 
  let debit = document.getElementById('debit').value; 
  console.log(debit);
  let credit = document.getElementById('credit').value; 
  let total = parseInt(total_bank_have) - parseInt(debit) + parseInt(credit);
  if (!isNaN(total)) {
    document.getElementById('total_balance').value = total;
  }
}
function set_table_serial() {
            let cs = 1;
            $('#abcd td:first-child').each(function () {
                $(this).html(cs++);
            });
            let as = 1;
            $('#agency-payment-table td:first-child').each(function () {
                $(this).html(as++);
            });
        }

let click_submit_button = false;
  function set_bank_id() {
      if(click_submit_button == true) {
          return 0;
      } else {
          click_submit_button = true;

          $(".abcdefgh").each(function () {
              //daily-bank-transaction-total-form
              let element = this;
              let bank_id = $(element).find(".ijklmnop").val();
              if (bank_id != null) {
                  $(element).find('.qrstuv').each(function () {
                    $(this).val(bank_id);
                  });
              }
          });
          $("#submit_bank_transaction").click();
          setTimeout( function(){
              click_submit_button = false;
          }  , 5000 );
      }
  }

function add_bank_table(btn){
  let new_row = $("#copy_tr_element").html();
  $(btn).parent().parent().parent().append(new_row);
}

function remove_bank_table(btn) {
  $(btn).parent().parent().remove();
}

function loadAvailableBalanceOfBank() {
        let bank_id = $("#bank_id").val();
        var requestUrl="{{route('accounts.ledger-bank-balance')}}";
        var _token = $("#_token").val();
        $("#_token").val();
        $.ajax({  
          type: "GET",
          url: requestUrl,
          data: { bank_id: bank_id,_token:_token},
          success: function (result) {
          $(".bank_balance_div").html(result);
          }
        });
  }

  function getCartProduct() {
        let route_url = "{{ route('buy.get-cart') }}";
        $.ajax({
            type: "GET",
            url: route_url,
            data: {},
            success: function (result) {
                
                $("#show-cart-conten").html(result);
                total_sell_amount();
            }
        });
    }

  $(document).ready(function() {
    $('.js-example-basic-single').select2();
});
  $(document).ready(function(){

$( "#date" ).datepicker({
       daysOfWeekHighlighted: "7",
        todayHighlight: true,
        autoclose: true,
     });
$( "#to" ).datepicker({
       daysOfWeekHighlighted: "7",
        todayHighlight: true,
     });
});
  var app = new Vue({
    el: '#bank',
    data: {
      credit: [],
      debit: [],
      bank_balance: '',
      serial: 1,
      bank_summery: [],
      // bank_detail_table: [],
      banks_info: {!! json_encode(@$bank_infos) !!},
      custo: {!! json_encode(@$customers) !!},
      supl: {!! json_encode(@$suppliers) !!},
    },
    methods:{
      addNewBankDetails() {
      this.bank_summery.push({
        new_addition: [],
          });
      },
      // addNewBankDetailsTables() {
      //     this.bank_detail_table.push({
      //       cus_sup: '',
      //       desc: '',
      //       debit: 0,
      //       credit: 0,
      //       balance: 0, 
      //       });
      // },
      deleteBankDetails(index, banks) {
            var idx = this.bank_summery.indexOf(banks);
            
            if (idx > -1) {
                this.bank_summery.splice(idx, 1);
            }
      },
      // deleteBankDetailsTables(index, bank_table) {
      //       var indx = this.bank_detail_table.indexOf(bank_table);
      //       console.log(indx, index);
      //       if (indx > -1) {
      //           this.bank_detail_table.splice(indx, 1);
      //       }
      // },
    },
    computed:{
      total_bank_have: function(){
                let amount = 0;
                amount = parseInt(this.credit) - parseInt(this.debit);
                if (!isNaN(amount)) {
                  return amount;
                }
            },
    },
    beforeMount(){
      this.addNewBankDetails();
      // this.addNewBankDetailsTables();
    }
  });
</script>    
@endsection


