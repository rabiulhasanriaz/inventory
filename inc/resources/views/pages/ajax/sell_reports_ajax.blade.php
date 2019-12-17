              <div class="load-details">
                  <table class="table table-bordered table-striped">
                      <thead>
                          <tr>
                              <th>SL</th>
                              <th>Product Name</th>
                              <th>Sold Qty</th>
                              <th>Unit Price</th>
                              <th>Amount</th>
                          </tr>
                      </thead>
                      @php($sl=0)
                      @php($balance=0)
                      @php($total = 0)
                      <tbody>
                          @foreach ($detail_ajax as $detail)
                          <tr>
                                <td>{{ ++$sl }}</td>
                                <td>
                                    {{ $detail->getProductWarranty['inv_pro_det_pro_name'] }}<br>
                                    @if ($detail->getProductWarranty['inv_pro_det_pro_warranty'] != 0)
                                        {{ $detail->getProductWarranty['inv_pro_det_pro_warranty'] }} Days
                                    @endif
                                    ({{ str_limit($detail->getProductWarranty['inv_pro_det_pro_description'],40) }})
                                </td>                         
                                <td class="text-right">{{ $detail->inv_pro_inv_qty }}</td>
                                <td class="text-right">{{ number_format($detail->inv_pro_inv_unit_price,2) }}</td>
                                @php($balance = $balance + $detail->inv_pro_inv_debit)
                                <td class="text-right">{{ number_format($detail->inv_pro_inv_debit,2) }}</td>
                            </tr>
                            @php($total = $total + $detail->inv_pro_inv_debit)
                          @endforeach
                      </tbody>
                      <tfoot>
                          <tr>
                              <td colspan="4" class="text-right">Total:</td>
                              <td class="text-right">{{ number_format($total,2) }}</td>
                          </tr>
                      </tfoot>
                  </table>
              </div>