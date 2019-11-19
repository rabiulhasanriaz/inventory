<?php

namespace App;
use Auth;

use Illuminate\Database\Eloquent\Model;

class Inv_product_inventory extends Model
{
    //
    protected $fillable =
    [
        'inv_pro_inv_id',
        'inv_pro_inv_com_id',
        'inv_pro_inv_party_id',
        'inv_pro_inv_prodet_id',
        'inv_pro_inv_invoice_no',
        'inv_pro_inv_total_qty',
        'inv_pro_inv_short_qty',
        'inv_pro_inv_short_remarks',
        'inv_pro_inv_qty',
        'inv_pro_inv_unit_price',
        'inv_pro_inv_debit',
        'inv_pro_inv_credit',
        'inv_pro_inv_issue_date',
        'inv_pro_inv_barcode',
        'inv_pro_inv_exp_date',
        'inv_pro_inv_tran_desc',
        'inv_pro_inv_deal_type',
        'inv_pro_inv_tran_type',
        'inv_pro_inv_confirm',
        'inv_pro_inv_status',
        'inv_pro_inv_submit_at',
        'inv_pro_inv_submit_by',
        'inv_pro_inv_update_at',
        'inv_pro_inv_update_by',
    ];
    protected $table = 'inv_product_inventories';
    protected $primaryKey = 'inv_pro_inv_id';
    public $incrementing = true;
    public $timestamps = false;

    // ============= For  Accounts Query =============
    public static function getSupCreditByID($party_id)
    {
        return Inv_product_inventory::where('inv_pro_inv_party_id', $party_id)
            ->where('inv_pro_inv_com_id', Auth::user()->au_company_id)
            ->where('inv_pro_inv_deal_type', 1)
            ->where('inv_pro_inv_status', 1)
            ->sum('inv_pro_inv_credit');
    }
    public static function getCusCreditByID($party_id)
    {
        return Inv_product_inventory::where('inv_pro_inv_party_id', $party_id)
            ->where('inv_pro_inv_com_id', Auth::user()->au_company_id)
            ->where('inv_pro_inv_status', 1)
            ->where('inv_pro_inv_deal_type',2)
            ->sum('inv_pro_inv_credit');
    }




    public static function getSupDebitByID($party_id)
    {
        return Inv_product_inventory::where('inv_pro_inv_party_id', $party_id)
            ->where('inv_pro_inv_com_id', Auth::user()->au_company_id)
            ->where('inv_pro_inv_deal_type', 1)
            ->where('inv_pro_inv_status', 1)->sum('inv_pro_inv_debit');
    }
    public static function getCusDebitByID($party_id)
    {
        return Inv_product_inventory::where('inv_pro_inv_party_id', $party_id)
            ->where('inv_pro_inv_com_id', Auth::user()->au_company_id)
            ->where('inv_pro_inv_deal_type',2)
            ->where('inv_pro_inv_status', 1)->sum('inv_pro_inv_debit');
    }





    public static function getSupBalanceByID($party_id)
    {
        return (Inv_product_inventory::where('inv_pro_inv_party_id', $party_id)
            ->where('inv_pro_inv_com_id', Auth::user()->au_company_id)
            ->where('inv_pro_inv_status', 1)
            ->where('inv_pro_inv_deal_type', 1)
            ->sum('inv_pro_inv_credit'))-
        (Inv_product_inventory::where('inv_pro_inv_party_id', $party_id)
            ->where('inv_pro_inv_com_id', Auth::user()->au_company_id)
            ->where('inv_pro_inv_status', 1)
            ->where('inv_pro_inv_deal_type', 1)
            ->sum('inv_pro_inv_debit'));
    }
    public static function getCusBalanceByID($party_id)
    {
        return (Inv_product_inventory::where('inv_pro_inv_party_id', $party_id)
            ->where('inv_pro_inv_com_id', Auth::user()->au_company_id)
            ->where('inv_pro_inv_status', 1)
            ->where('inv_pro_inv_deal_type',2)
            ->sum('inv_pro_inv_credit'))-
        (Inv_product_inventory::where('inv_pro_inv_party_id', $party_id)
            ->where('inv_pro_inv_com_id', Auth::user()->au_company_id)
            ->where('inv_pro_inv_status', 1)
            ->where('inv_pro_inv_deal_type',2)
            ->sum('inv_pro_inv_debit'));
    }






    public static function getCreditForLedgerByInvoice($invoiceNo)
    {
        return Inv_product_inventory::where('inv_pro_inv_invoice_no',$invoiceNo)->where('inv_pro_inv_deal_type',1)->where('inv_pro_inv_status',1)->sum('inv_pro_inv_credit');
    }
    public static function getDebitForLedgerByInvoice($invoiceNo)
    {
        return Inv_product_inventory::where('inv_pro_inv_invoice_no',$invoiceNo)->where('inv_pro_inv_deal_type',1)->where('inv_pro_inv_status',1)->sum('inv_pro_inv_debit');
    }
    public static function getRunningBalanceByDate($lastDate,$supplier_id)
    {
        return (Inv_product_inventory::where('inv_pro_inv_party_id',$supplier_id)->where('inv_pro_inv_deal_type',1)->where('inv_pro_inv_status',1)->where('inv_pro_inv_issue_date','<=',$lastDate)->sum('inv_pro_inv_credit'))-(Inv_product_inventory::where('inv_pro_inv_party_id',$supplier_id)->where('inv_pro_inv_deal_type',1)->where('inv_pro_inv_status',1)->where('inv_pro_inv_issue_date','<=',$lastDate)->sum('inv_pro_inv_debit'));
    }
    public static function getOpenningBalance($startDate,$supplier_id)
    {
        return (Inv_product_inventory::where('inv_pro_inv_party_id',$supplier_id)->where('inv_pro_inv_deal_type',1)->where('inv_pro_inv_status',1)->where('inv_pro_inv_issue_date','<',$startDate)->sum('inv_pro_inv_credit'))-(Inv_product_inventory::where('inv_pro_inv_party_id',$supplier_id)->where('inv_pro_inv_deal_type',1)->where('inv_pro_inv_status',1)->where('inv_pro_inv_issue_date','<',$startDate)->sum('inv_pro_inv_debit'));
    }

    public static function getCreditByInvoiceNo($invoiceNo)
    {
       return Inv_product_inventory::where('inv_pro_inv_invoice_no',$invoiceNo)->sum('inv_pro_inv_credit');
    }
    public static function getDebitByInvoiceNo($invoiceNo)
    {
       return Inv_product_inventory::where('inv_pro_inv_invoice_no',$invoiceNo)->sum('inv_pro_inv_debit');
    }
    public static function getTotalDiscountBySupplierID($supplier_id)
    {
        return Inv_product_inventory::where('inv_pro_inv_party_id',$supplier_id)->where('inv_pro_inv_status',1)->where('inv_pro_inv_tran_type',6)->where('inv_pro_inv_deal_type',1)->sum('inv_pro_inv_debit');

    }
    public static function getTotalDiscountByCustomerID($customer_id)
    {
        return Inv_product_inventory::where('inv_pro_inv_party_id',$customer_id)->where('inv_pro_inv_status',1)->where('inv_pro_inv_tran_type',6)->where('inv_pro_inv_deal_type',2)->sum('inv_pro_inv_credit');
    }
    public static function getIssueDateByInvoice($invoiceNo)
    {
        return Inv_product_inventory::where('inv_pro_inv_invoice_no',$invoiceNo)->first();
    }
    public function getProductInfo()
    {
        return $this->belongsTo('App\Inv_product_detail','inv_pro_inv_prodet_id','inv_pro_det_id');
    }

    public function getCustomerInfo(){
        return $this->belongsTo('App\Inv_customer','inv_pro_inv_party_id','inv_cus_id');
    }

    public function getSupplierInfo(){
        return $this->belongsTo('App\Inv_supplier','inv_pro_inv_party_id','inv_sup_id');
    }

    public function getProductWarranty(){
        return $this->belongsTo('App\Inv_product_detail','inv_pro_inv_prodet_id','inv_pro_det_id');
    }

    public static function getTotalDebit($invoiceId){
        $com = Auth::user()->au_company_id;
        $total = Inv_product_inventory::where('inv_pro_inv_com_id',$com)
                                            ->where('inv_pro_inv_invoice_no',$invoiceId)
                                            ->sum('inv_pro_inv_debit');
        return $total;
    }

    public static function ProductSerial($pro_inv_id){
        return Inv_product_inventory_detail::where('inv_pro_invdet_proinv_id', $pro_inv_id)->pluck('inv_pro_invdet_slno')->toArray();
    }

}
