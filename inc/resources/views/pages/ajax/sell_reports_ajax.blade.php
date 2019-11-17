              <div class="load-details">
                  <table class="table table-bordered table-striped">
                      <thead>
                          <tr>
                              <th>SL</th>
                              <th>Product Name</th>
                              <th>Issue Date</th>
                              <th>Description</th>
                              <th>Warranty</th>
                              <th>Sold Qty</th>
                              <th>Unit Price</th>
                              <th>Amount</th>
                          </tr>
                      </thead>
                      @php($sl=0)
                      @php($balance=0)
                      <tbody>
                          @foreach ($detail_ajax as $detail)
                          <tr>
                                <td>{{ ++$sl }}</td>
                                <td>{{ $detail->getProductWarranty['inv_pro_det_pro_name'] }}</td>
                                <td>{{ $detail->inv_pro_inv_issue_date }}</td>
                                <td>{{ $detail->inv_pro_inv_tran_desc }}</td>
                                <td>
                                    @if ($detail->getProductWarranty['inv_pro_det_pro_warranty'] == 0)
                                        No Warranty
                                    @else
                                        {{ $detail->getProductWarranty['inv_pro_det_pro_warranty'] }} Days
                                    @endif
                                  </td>
                                <td>{{ $detail->inv_pro_inv_qty }}</td>
                                <td class="text-right">{{ number_format($detail->inv_pro_inv_unit_price,2) }}</td>
                                @php($balance = $balance + $detail->inv_pro_inv_debit)
                                <td class="text-right">{{ number_format($detail->inv_pro_inv_debit,2) }}</td>
                            </tr>
                          @endforeach
                      </tbody>
                  </table>
              </div>