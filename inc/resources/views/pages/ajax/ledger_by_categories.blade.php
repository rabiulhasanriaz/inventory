
<div class="row">
	<div class="col-sm-12">
		<b>Ledger Category Name: </b> 
		{{-- {{ App\Inv_ledger_category::getCategoryNameById(request()->cat_id)}} --}}
		 <br>
		
		
	</div>
</div>

<div class="row" style="margin-top: 20px;">
	<div class="col-sm-12">

	

		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th style="text-align: center;">SL</th>
                    <th style="text-align: center;">Expense Name</th>
                    
                    
                    <th style="text-align: center;">Amount</th>
                   
                </tr>
               
			</thead>
			<tbody>
				@php($total=0)

				@foreach($ledgers as $expense)
				{{-- @php($total+=App\Inv_acc_bank_statement::getTotalExpensesByExpenses($expense->inv_led_id)) --}}
				<tr>
					<td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td> 
                    	{{ $expense->inv_ledg_ledger_name}}</td>
                  
                    <td class="text-right">
                      {{-- {{ number_format(App\Inv_acc_bank_statement::getTotalExpensesByExpenses($expense->inv_acc_exp_id),2)}} --}}
                    </td>
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td style="text-align: center; font-weight: bolder;">#</td>
					<td style="text-align: right; font-weight: bolder;">Total:</td>
					<td style="text-align: right; font-weight: bolder;">
						{{number_format($total,2)}}
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
