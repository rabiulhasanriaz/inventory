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
    public static function getCreditByID($party_id)
    {
        return Inv_product_inventory::where('inv_pro_inv_party_id', $party_id)
            ->where('inv_pro_inv_com_id', Auth::user()->au_company_id)
            ->where('inv_pro_inv_status', 1)
            ->sum('inv_pro_inv_credit');
    }
    public static function getDebitByID($party_id)
    {
        return Inv_product_inventory::where('inv_pro_inv_party_id', $party_id)
            ->where('inv_pro_inv_com_id', Auth::user()->au_company_id)
            ->where('inv_pro_inv_status', 1)
            ->sum('inv_pro_inv_debit');
    }
    public static function getBalanceByID($party_id)
    {
        return (Inv_product_inventory::where('inv_pro_inv_party_id', $party_id)
            ->where('inv_pro_inv_com_id', Auth::user()->au_company_id)
            ->where('inv_pro_inv_status', 1)
            ->sum('inv_pro_inv_credit'))-
        (Inv_product_inventory::where('inv_pro_inv_party_id', $party_id)
            ->where('inv_pro_inv_com_id', Auth::user()->au_company_id)
            ->where('inv_pro_inv_status', 1)
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
}
