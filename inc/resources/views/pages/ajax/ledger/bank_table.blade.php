<tr v-for="(bank_table,j) in bank_detail_table" :key="j">
    <td></td>
    <td>
      <select name="cus_sup" id="" class="form-control js-example-basic-single" v-model="bank_table.cus_sup">
        <option value="">Select One</option>
        <option v-for="cus in custo" :value="cus.inv_cus_id">
          C - @{{ cus.inv_cus_name }}
        </option>
        <option v-for="sup in supl" :value="sup.inv_sup_id">
          S - @{{ sup.inv_sup_person }}
        </option>
      </select>
    </td>
    <td>
      <input type="text" class="form-control" style="padding:1px;" name="desc" v-model="bank_table.desc">
    </td>
    <td>
        <input type="text" class="form-control" name="debit" style="width: 100px; padding:1px;" v-model="bank_table.debit">
    </td>
    <td>
        <input type="text" class="form-control" name="credit" style="width: 100px; padding:1px;" v-model="bank_table.credit">
    </td>
    <td class="text-right">
        <input type="text" class="form-control" name="balance" style="width: 100px;" v-model="bank_table.balance">
    </td>
    <td style="text-align: center;">
      <button class="btn btn-success btn-xs" @click="addNewBankDetailsTables()">
        <i class="fa fa-plus"></i>
      </button> 
      <button class="btn btn-danger btn-xs" @click="deleteBankDetailsTables(j,bank_table)">
          <i class="fa fa-minus"></i>
      </button> 
    </td>
</tr>