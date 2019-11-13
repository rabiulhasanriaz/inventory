
<style type="text/css">
   .table {
            width: 100%;
        }

        .table th{
            text-align: center;
            font-weight: bolder;
        }

        .table {
            border-collapse: collapse;
        }

        .table th, .table td {
            border: 1px solid black;
            font-weight: normal;
            padding: 5px 3px;
        }

</style>
              <center>
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

                      <table class="table">
                        <thead>
                        <tr>
                          <th >SL</th>
                          <th >Issue Date</th>
                          <th >Description</th>
                          <th >Credit</th>
                          <th >Debit</th>
                          <th >Balance</th>
                          
                          
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
                          <td >
                            {{ ++$sl }}
                          </td>
                          <td >
                            {{ $inv_cust->inv_pro_inv_issue_date }}
                          </td>
                          <td >
                            {{$inv_cust->inv_pro_inv_tran_desc}}
                          </td>
                          <td style="text-align: right;">
                            {{ App\Inv_product_inventory::getCreditByInvoiceNo($inv_cust->inv_pro_inv_invoice_no)}}
                          </td>
                          <td style="text-align: right;">
                            {{ App\Inv_product_inventory::getDebitByInvoiceNo($inv_cust->inv_pro_inv_invoice_no)}}
                          </td>
                          <td style="text-align: right;">
                            {{ (App\Inv_product_inventory::getCreditByInvoiceNo($inv_cust->inv_pro_inv_invoice_no)) - (App\Inv_product_inventory::getDebitByInvoiceNo($inv_cust->inv_pro_inv_invoice_no)) }}
                          </td>
                        </tr>
                         @endforeach

                        </tbody>
                        <tfoot>
                          <tr>
                            <td style="text-align: center;">#</td>
                            <td colspan="2" style="text-align:right; font-weight: bolder;">Total:</td>
                            <td style="font-weight: bolder; text-align: right;">
                              {{$total_credit}}
                            </td>
                            <td style="font-weight: bolder; text-align: right;">
                              {{$total_debit}}
                            </td>
                            <td style="font-weight: bolder; text-align: right;">
                              {{$total_credit-$total_debit}}
                            </td >
                            
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                    <!-- /.box-body -->
                  </div>
                 </section>
</center>

