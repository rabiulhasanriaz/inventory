<?php

Route::group(['middleware' => 'auth'],function(){

//UserController
Route::get('/index','UserController@home')->name('dashboard');
Route::get('/provide_reason','SdsController@provide_reason');
Route::get('/provide_reason_select','SdsController@reason_response')->name('reason_selected');
Route::post('/provide_reason','SdsController@provide_reason_submit');
Route::get('/create_team','UserController@create_team')->middleware('au_access:create_team');
Route::post('/create_team',['uses' => 'UserController@create_team_insert']);
Route::get('/team_update/{id}','UserController@team_update')->name('pages.team_update')->middleware('au_access:create_team');
Route::post('/team_update_submit/{id}','UserController@team_update_submit')->name('pages.team_update_submit');
Route::get('/user_register','UserController@user_register')->middleware('au_access:user_register');
Route::post('/user_register',['uses' => 'UserController@user_register_insert']);
Route::get('/team_leaders','UserController@team_leaders')->middleware('au_access:team_leader');
Route::get('/team_leader_suspend/{id}','UserController@team_leader_suspend')->name('pages.team_leader_suspend');
Route::get('/team_leader_active/{id}','UserController@team_leader_active')->name('pages.team_leader_active');
Route::get('/user_list','UserController@user_list')->middleware('au_access:user');
Route::get('/user_list_activate/{id}','UserController@user_list_activate')->name('pages.user_list_activate');
Route::get('/user_list_deactivate/{id}','UserController@user_list_deactivate')->name('pages.user_list_deactivate');
Route::get('/user_profile/{id}','UserController@user_profile')->name('pages.user_profile');
Route::get('/admin_list/{id}','UserController@user_profile_status')->name('pages.admin_list');
Route::get('/admin_list_activate/{id}','UserController@user_profile_status_activate')->name('pages.admin_list_activate');
Route::get('/user_edit/{id}','UserController@user_edit')->name('pages.user_edit');
Route::post('/user_edit_update/{id}','UserController@user_update')->name('pages.user_edit');
Route::post('/user_settings/{id}','UserController@userSettings');
Route::get('/mobileno_exist','UserController@mobileno_exists');
//UserController End



Route::group(['middleware' => 'au_company_access:crm'], function() {
    //////////////////////////////
    Route::get('user/customer_list/{id}','SdsController@user_customer_list')->name('user.customer_list')
                                                                            ->middleware('au_access:crm_customer_entry_info');
    Route::get('/customer_list','SdsController@customer_list')->name('customer_list');
    /////////////////////////////

    //SdsController Start
    Route::get('/company_register','SdsController@company_register');
    Route::post('/company_register',['uses' => 'SdsController@company_register_insert']);
    Route::get('/admin_list','SdsController@admin_list');
    Route::get('/add_reason','SdsController@add_reason');
    Route::get('/reason_list','SdsController@reason_list')->name('show_reason_list');
    Route::post('/reason_edit','SdsController@reason_edit')->name('reason_edit');
    Route::get('/reason_delete/{id}','SdsController@reason_delete')->name('reason_delete');
    Route::post('/add_reason' ,['uses' => 'SdsController@add_reason_insert']);
    Route::get('/add_category','SdsController@add_category');
    Route::post('/add_category',['uses' => 'SdsController@add_category_insert']);
    Route::post('/catagory_edit','SdsController@catagory_edit')->name('catagory_edit');
    Route::get('/catagory_delete/{id}','SdsController@catagory_delete')->name('catagory_delete');
    Route::get('/create_customer','SdsController@create_customer')->middleware('au_access:crm_create_customer');
    Route::post('/create_customer',['uses' => 'SdsController@create_customer_submit']);
    Route::get('/create_customer/show_reason_by_ajax',['uses' => 'SdsController@show_reason_by_ajax']);

    Route::get('/customer_list','SdsController@customer_list')->middleware('au_access:crm_my_customer_list');
    Route::get('/client_feedback/{id}','SdsController@client_feedback')->name('pages.client_feedback')->where(['id' => '[0-9]+']);
    Route::get('/client_feedback_oms/{id}','SdsController@client_feedback_oms')->name('oms_client');
    Route::get('/client_feedback_modal/{id}','SdsController@smsNotify')->name('pages.sms_notification');
    Route::post('/client_feedback/{id}',['uses' => 'SdsController@client_feedback_submit'])->where(['id' => '[0-9]+']);

    Route::get('/client_feedback_edit/{id}','SdsController@client_feedback_edit')->name('pages.client_feedback_edit');
    Route::post('/client_feedback_edit/{id}','SdsController@client_feedback_edit_update')->name('client_feedback_edit');
    Route::post('/client_feedback/send-feedback/{id}','SdsController@send_notification');
    Route::post('/client_feedback/visit/{id}','SdsController@visit_notify');

    Route::get('/total_customer_list','SdsController@total_customer_list')->middleware('au_access:crm_total_customer_list');
    Route::get('/admin_customer/{id}','SdsController@admin_created')->middleware('au_access:crm_admin_created_customer');
    Route::get('/order_customer','SdsController@order_customer')->middleware('au_access:crm_ordered_customer');
    Route::get('/visited_customer','SdsController@visited_customer');
    Route::get('/members_list/{id}','SdsController@members_list')->name('members_list')->middleware('au_access:crm_team_member_list');
    Route::get('/followup','SdsController@followup')->middleware('au_access:crm_followup_customer');
    Route::get('/non_followup','SdsController@non_followup')->middleware('au_access:crm_non_followup_customer');
    Route::get('/search_findus','SdsController@search_findus')->middleware('au_access:crm_search_find_us');
    Route::get('/search_reason','SdsController@search_reason')->middleware('au_access:crm_search_reason');
    Route::get('/search_result','SdsController@search_result')->middleware('au_access:crm_search_result');
    Route::get('/search_feedback','SdsController@search_feedback')->middleware('au_access:crm_search_feedback');
    Route::get('/todays_customer_list' , 'SdsController@todays_customer_list')->middleware('au_access:crm_customer_entry_info');
    Route::get('/customer_list_monthly/{id}','SdsController@customer_list_monthly')->name('customer_list_monthly')->middleware('au_access:crm_customer_entry_info');
    Route::get('/customer_list_today/{id}','SdsController@customer_list_today')->name('customer_list_today')->middleware('au_access:crm_customer_entry_info');
    Route::get('/find_us','SdsController@find_us');
    Route::post('/find_us',['uses' => 'SdsController@find_us_submit']);
    Route::post('/find_us_edit','SdsController@find_us_edit')->name('find_edit');
    Route::get('/find_delete/{id}','SdsController@find_up_delete')->name('find_delete');
    Route::get('/weekly_summery','SdsController@weekly_summery')->middleware('au_access:crm_weekly_summery');
    Route::get('/monthly_summery','SdsController@monthly_summery')->middleware('au_access:crm_monthly_summery');
    Route::get('/mobile_num_exist','SdsController@mobile_num_exist')->name('ajax.check_mobile_num_exists');
    //SdsController End

});
///////////////////////


Route::group(['middleware' => 'au_company_access:sms'], function() {
    //Sms Controller Start
    Route::get('/sms_setup' , 'SmsController@sms_setup')->middleware('au_access:sms_setup');
    Route::post('/sms_setup','SmsController@sms_setup_submit');
    Route::get('/sms_campaign' , 'SmsController@sms_campaign')->middleware('au_access:sms_campaign');
    Route::post('/sms_campaign' , 'SmsController@sms_campaign_send');
    Route::get('/sms_temp_edit' , 'SmsController@sms_temp_edit')->middleware('au_access:sms_template');
    Route::post('/sms_temp_edit' , ['uses' => 'SmsController@create_template']);
    Route::get('/sms_temp_edit/{id}','SmsController@temp_del')->name('pages.sms_temp_edit_del');
    Route::get('/sms_temp_modify/{id}','SmsController@sms_temp_modify')->name('pages.sms_temp_modify');
    Route::post('/sms_temp_modify/{id}','SmsController@sms_temp_modify_edit');
    //Sms Controller End
});
////////////////////////////
///////////////////////////

Route::group(['middleware' => 'au_company_access:oms'], function() {
    //OmsController Start
    Route::get('/oms_convence_users','OmsController@oms_convence_users')->middleware('au_access:oms_conveyance_users');
    Route::post('/oms_convence_users',['uses' => 'OmsController@oms_pay_convence']);
    Route::get('/oms_convence_details/{id}','OmsController@oms_convence_details');
    Route::get('/convence_add_new_bill','OmsController@convence_add_new_bill')->middleware('au_access:oms_conveyance_add_new_bill');
    Route::post('/convence_add_new_bill',['uses' => 'OmsController@convence_add_new_bill_submit']);
    Route::get('/convence_pending_bill','OmsController@convence_pending_bill')->middleware('au_access:oms_conveyance_pending_bill');
    Route::get('/convence_my_pending_bill','OmsController@my_pending_bill')->middleware('au_access:oms_my_pending_bill');
    Route::post('/convence_reject_bill_update/{id}','OmsController@convence_reject_bill_update')->name('pages.oms_update_bill');
    Route::get('/convence_pending_bill_reject/{id}','OmsController@convence_reject_update')->name('pages.convence_pending_bill_reject');
    Route::get('/convence_pending_bill_confirm/{id}','OmsController@convence_confirm_update')->name('pages.convence_pending_bill_confirm');
    Route::get('/convence_reject_bill','OmsController@convence_reject_bill')->middleware('au_access:oms_conveyance_reject_bill');
    Route::get('/convence_paid_bill','OmsController@convence_paid_bill')->middleware('au_access:oms_conveyance_paid_bill');
    Route::get('/oms_lunch_users','OmsController@oms_lunch_users')->middleware('au_access:oms_lunch_users');
    Route::post('/oms_lunch_users',['uses' => 'OmsController@oms_pay_lunch']);
    Route::get('/oms_lunch_details/{id}','OmsController@oms_lunch_details');
    Route::get('/lunch_add_new_bill','OmsController@lunch_add_new_bill')->middleware('au_access:oms_lunch_add_new_bill');
    Route::post('/lunch_add_new_bill',['uses' => 'OmsController@lunch_add_new_bill_submit']);
    Route::get('/lunch_pending_bill','OmsController@lunch_pending_bill')->middleware('au_access:oms_lunch_pending_bill');
    Route::get('/lunch_my_pending_bill','OmsController@lunch_my_pending_bill')->middleware('au_access:oms_lunch_my_pending_bill');
    Route::get('/lunch_pending_bill_confirm/{id}','OmsController@lunch_pending_confirm')->name('pages.lunch_pending_bill_confirm');
    Route::get('/lunch_pending_bill_reject/{id}','OmsController@lunch_pending_reject')->name('pages.lunch_pending_bill_reject');
    Route::post('/lunch_reject_update/{id}','OmsController@lunch_reject_update')->name('pages.oms_lunch_update_bill');
    Route::get('/lunch_reject_bill','OmsController@lunch_reject_bill')->middleware('au_access:oms_lunch_reject_bill');
    Route::get('/lunch_paid_bill','OmsController@lunch_paid_bill')->middleware('au_access:oms_lunch_paid_bill');
    Route::get('/oms_update_bill/{id}','OmsController@oms_update_bill');
    Route::get('/oms_lunch_update_bill/{id}','OmsController@oms_lunch_update_bill');
    Route::get('/oms_user_amount','OmsController@oms_convence_bill_users')->name('users_amount');
    Route::get('/oms_user_lunch_amount','OmsController@oms_lunch_bill_users')->name('users_lunch_amount');
    //Oms Controller End
});

//Dashboard Controller Start
Route::get('/todays_followup','DashboardController@todays_followup');
Route::get('/given_followup','DashboardController@given_followup');
Route::get('/followup_list','DashboardController@followup_list');
Route::get('/page_permission/{id}','DashboardController@page_permission')->name('permission');
Route::post('/page_permission/{id}','DashboardController@page_permission_submit')->name('permission');
Route::get('/page_permission_com/{id}','DashboardController@page_permission_com')->name('com-permission');
Route::post('/page_permission_com/{id}','DashboardController@page_permission_com_submit')->name('com-permission-submit');
Route::get('/permission_user','DashboardController@permission_user_type');
//Dashboard Controller End

Route::group(['middleware' => 'au_company_access:inventory'], function() {
    //Inventory Controller Start
    Route::group(['prefix' => 'product','as' => 'inventory.'],function(){
        Route::get('product_add','Inventory\InvProductController@product_add')->name('add_product');
        Route::get('product_type_ajax','Inventory\InvProductController@show_pro_grp_by_ajax')->name('product_submit_ajax');
        Route::post('product_detail_add','Inventory\InvProductController@product_detail_submit')->name('product_detail_submit');
        Route::get('product_list','Inventory\InvProductController@product_list')->name('product_list');
        Route::get('product_list/{id}','Inventory\InvProductController@product_detail_show')->name('product_show');
        Route::get('product_delete/{id}','Inventory\InvProductController@product_delete')->name('product_delete');
        Route::get('product_edit_page/{id}','Inventory\InvProductController@product_edit_page')->name('product_edit_page');
        Route::post('product_edit/{id}','Inventory\InvProductController@pro_edit')->name('product_edit');
        Route::get('group_add','Inventory\InvProductController@product_group')->name('add_group');
        Route::post('group_add','Inventory\InvProductController@product_group_submit')->name('add_group_submit');
        Route::get('group_list','Inventory\InvProductController@product_group_list')->name('group_list');
        Route::get('group_show/{id}','Inventory\InvProductController@product_group_show')->name('group_show');
        Route::get('group_delete/{id}','Inventory\InvProductController@pro_grp_del')->name('group_delete');
        Route::get('group_edit_page/{id}','Inventory\InvProductController@pro_grp_edit_page')->name('group_edit_page');
        Route::post('group_edit/{id}','Inventory\InvProductController@pro_grp_edit')->name('group_edit');
        Route::get('product_type','Inventory\InvProductController@product_type_add')->name('type_add');
        Route::post('product_type_submit','Inventory\InvProductController@product_type_submit')->name('type_submit');
        Route::get('product_type_show/{id}','Inventory\InvProductController@product_type_show')->name('type_show');
        Route::get('product_type_delete/{id}','Inventory\InvProductController@product_type_delete')->name('type_delete');
        Route::get('product_type_edit_page/{id}','Inventory\InvProductController@pro_type_edit_page')->name('type_edit_page');
        Route::post('product_type_edit/{id}','Inventory\InvProductController@pro_type_edit')->name('type_edit');
        Route::get('product_type_list','Inventory\InvProductController@product_type_list')->name('type_list');


        Route::get('short_quantity','Inventory\InvProductController@product_short_notify')->name('short');
    });

    Route::group(['prefix' => 'supplier' , 'as' => 'inventory.'],function(){
        Route::get('supplier_add','Inventory\InvSupplierController@add_supplier')->name('add');
        Route::post('supplier_add','Inventory\InvSupplierController@add_supplier_submit')->name('submit');
        Route::get('supplier_list','Inventory\InvSupplierController@supplier_list')->name('list');
        Route::get('supplier_list/{id}','Inventory\InvSupplierController@supplier_list_show')->name('list_show');
        Route::get('supplier_delete/{id}','Inventory\InvSupplierController@sup_del')->name('supplier_delete');
        Route::get('supplier_edit_page/{id}','Inventory\InvSupplierController@sup_edit_page')->name('supplier_show');
        Route::post('supplier_edit/{id}','Inventory\InvSupplierController@sup_edit')->name('supplier_edit');
        Route::get('supplier_product_add','Inventory\InvSupplierController@supplier_product_add')->name('pro_add');
        Route::post('supplier_product_add','Inventory\InvSupplierController@supplier_product_submit')->name('sup_pro_submit');
        Route::get('supplier_pro_show/{id}','Inventory\InvSupplierController@supplier_product_detail')->name('sup_pro_show');
        Route::get('supplier_pro_del/{id}','Inventory\InvSupplierController@supplier_product_delete')->name('sup_pro_del');
        Route::get('supplier_pro/{id}','Inventory\InvSupplierController@supplier_pro_edit_page')->name('sup_pro_edit');
        Route::post('supplier_pro_edit/{id}','Inventory\InvSupplierController@supplier_pro_edit')->name('edit_pro');
        Route::get('supplier_product_list','Inventory\InvSupplierController@supplier_product_list')->name('pro_list');



        // ================= Supplier Accounts Routes List Start============= 

        Route::group(['prefix' => 'supplier' , 'as' => 'supplier.'],function(){ 
            Route::get('accounts.deposit-withdraw','Inventory\SupplierAccountsController@depositWithdrawForm')->name('accounts.deposit-withdraw');
            Route::post('accounts.deposit-withdraw','Inventory\SupplierAccountsController@depositWithdrawStore')->name('accounts.deposit-withdraw');
            Route::get('accounts.payment','Inventory\SupplierAccountsController@supplierPaymentForm')->name('accounts.payment');
            Route::post('accounts.payment','Inventory\SupplierAccountsController@supplierPaymentStore')->name('accounts.payment');
            Route::get('accounts.payment-collection','Inventory\SupplierAccountsController@supplierPaymentCollectionForm')->name('accounts.payment-collection');
            Route::post('accounts.payment-collection','Inventory\SupplierAccountsController@supplierPaymentCollectionStore')->name('accounts.payment-collection');
            Route::get('accounts.account-statement','Inventory\SupplierAccountsController@showAccountStatement')->name('accounts.account-statement');
            Route::get('accounts.account-statement-details/{supplier_id}','Inventory\SupplierAccountsController@showAccountStatementDetails')->name('accounts.account-statement-details');
            
            Route::get('accounts.download-supplier-account-statement-details/{supplier_id}','Inventory\SupplierAccountsController@downloadAccountStatementDetails')->name('accounts.download-supplier-account-statement-details');


            Route::get('accounts.supplier-voucher-details','Inventory\SupplierAccountsController@loadAjaxInvoiceDetails')->name('accounts.supplier-voucher-details');

            //=========18-11-19================
            Route::get('accounts.today-payment','Inventory\SupplierAccountsController@supplierTodayPaymentReport')->name('accounts.today-payment');
        });
        // ================= Supplier Accounts Routes List End============= 

    });

    Route::group(['prefix' => 'customer' , 'as' => 'customer.'],function(){
        Route::get('customer_add_page','Inventory\InvCustomerController@customer_add_page')->name('customer_add');
        Route::post('customer_add_submit','Inventory\InvCustomerController@customer_add_submit')->name('customer_submit');
        Route::get('customer_list','Inventory\InvCustomerController@customer_list')->name('customer_list');
        Route::get('customer_detail/{id}','Inventory\InvCustomerController@customer_detail')->name('customer_detail');
        Route::get('customer_delete/{id}','Inventory\InvCustomerController@customer_delete')->name('customer_delete');
        Route::get('customer_edit_page/{id}','Inventory\InvCustomerController@customer_edit_page')->name('customer_edit_page');
        Route::post('customer_edit/{id}','Inventory\InvCustomerController@customer_edit')->name('customer_edit');
        Route::get('customer_exist','Inventory\InvCustomerController@customer_exist')->name('customer_exist');



        //==============Inventory Customer Account Routes Start======================
    
        Route::group(['prefix' => 'accounts' , 'as' => 'accounts.'],function(){
            Route::get('deposit-withdraw','Inventory\CustomerAccountsController@depositWithdrawForm')->name('deposit-withdraw');
            Route::post('deposit-withdraw','Inventory\CustomerAccountsController@depositWithdrawStore')->name('deposit-withdraw');
            Route::get('payment','Inventory\CustomerAccountsController@customerPaymentForm')->name('payment');
            Route::post('payment','Inventory\CustomerAccountsController@customerPaymentStore')->name('payment');
            Route::get('payment-refund','Inventory\CustomerAccountsController@customerPaymentRedfundForm')->name('payment-refund');
            Route::post('payment-refund','Inventory\CustomerAccountsController@customerPaymentRedfundStore')->name('payment-refund');
            Route::get('account-statement','Inventory\CustomerAccountsController@showAccountStatement')->name('account-statement');
            
            Route::get('account-statement-details/{customer_id}','Inventory\CustomerAccountsController@showAccountStatementDetails')->name('account-statement-details');

              //=====================17-11-19 ================

        Route::get('general_ledger_download','Inventory\BankAccountController@downLoadGeneralLedger')->name('general_ledger_download');

        //=====================17-11-19 ================

//  ====================18-11-19 ===============
            
       

            Route::get('download-customer-account-statement-details/{customer_id}','Inventory\CustomerAccountsController@downloadAccountStatementDetails')->name('download-customer-account-statement-details');


            Route::get('cusotmer-voucher-details','Inventory\CustomerAccountsController@loadAjaxInvoiceDetails')->name('cusotmer-voucher-details');

            //===========18-11-19 ============

            Route::get('today-payment','Inventory\CustomerAccountsController@customerTodayPaymentReport')->name('today-payment');
        });

        //==============Inventory Customer Account Routes End======================

    });

    Route::group(['prefix' => 'product_inventory' , 'as' => 'buy.'],function(){
        Route::get('buy_product','Inventory\ProductInventoryController@buy_product_add')->name('buy_add');
        Route::post('buy_product_add','Inventory\ProductInventoryController@buy_product_submit')->name('buy_submit');
        Route::get('buy-product-new','Inventory\ProductInventoryController@buy_product_new')->name('buy-product-new')->middleware('au_access:purchase_product');
        Route::get('buy_product/add-to-cart','Inventory\InventoryPurchaseCartController@addToCart')->name('buy-add-to-cart');
        Route::get('buy_product/add-to-cart-warrenty-product','Inventory\InventoryPurchaseCartController@addToCartWarrentyProduct')->name('buy-add-to-cart-warrenty-product');
        Route::get('buy_product/add-warrenty-product-sl-no','Inventory\InventoryPurchaseCartController@addWarrentyProductSlNo')->name('buy-add-warrenty-product-sl-no');
        Route::get('buy_product/remove-warrenty-product-sl','Inventory\InventoryPurchaseCartController@removeWarrentyProductSlNo')->name('buy-remove-warrenty-product-sl');
        Route::get('buy_product/get-cart','Inventory\InventoryPurchaseCartController@getCartContent')->name('buy-get-cart');
        Route::get('buy_product/cart-remove','Inventory\InventoryPurchaseCartController@removecart')->name('buy-remove-cart');
        Route::get('buy_product/cart-update','Inventory\InventoryPurchaseCartController@updatecart')->name('buy-update-cart');
        Route::post('buy_product/cart-submit','Inventory\InventoryPurchaseCartController@cartSubmit')->name('buy-cart-submit');
        Route::get('buy_prodect/buy_product_invoice','Inventory\InventoryPurchaseCartController@invTemporaryBuy')->name('buy-temporary');
        Route::get('available_list','Inventory\ProductInventoryController@available_list')->name('available');

        Route::get('sell_product','Inventory\ProductInventoryController@sell_product')->name('pro_sell')->middleware('au_access:sell_product');
        Route::get('sell_product/add-to-cart','Inventory\InventoryCartController@addToCart')->name('add-to-cart');
        Route::get('sell_product/add-to-cart-warrenty-product','Inventory\InventoryCartController@addToCartWarrentyProduct')->name('add-to-cart-warrenty-product');
        Route::get('sell_product/add-warrenty-product-sl-no','Inventory\InventoryCartController@addWarrentyProductSlNo')->name('add-warrenty-product-sl-no');
        Route::get('sell_product/remove-warrenty-product-sl','Inventory\InventoryCartController@removeWarrentyProductSlNo')->name('remove-warrenty-product-sl');
        Route::get('sell_product/get-cart','Inventory\InventoryCartController@getCartContent')->name('get-cart');
        Route::get('sell_product/cart-remove','Inventory\InventoryCartController@removecart')->name('remove-cart');
        Route::get('sell_product/cart-update','Inventory\InventoryCartController@updatecart')->name('update-cart');
        Route::post('sell_product/car-submit','Inventory\InventoryCartController@cartSubmit')->name('car-submit');
        Route::get('sell_product/sell_product_invoice','Inventory\InventoryCartController@invTemporaryProduct')->name('sell_temp_invoice');


        Route::get('sell_service_charge','Inventory\InventoryCartController@addServiceCharges')->name('add-to-cart-service-charge');


        Route::get('product_type_ajax','Inventory\ProductInventoryController@show_pro_type_ajax')->name('type_submit_ajax');
        Route::get('product_search_ajax','Inventory\ProductInventoryController@product_search_ajax')->name('product-search');

    });

    Route::group(['prefix' => 'reports', 'as' => 'reports.'],function(){
        Route::get('buy_invoice','Inventory\ReportsController@buy_invoice_pdf')->name('buy_pdf');
        Route::get('buy_invoice_pdf','Inventory\ReportsController@buy_invoice_pdf_generate')->name('pdf-generate');
        Route::get('sell_invoice_pdf','Inventory\ReportsController@sell_invoice_pdf_generate')->name('sell-pdf-generate');
        Route::get('sell_reports','Inventory\ReportsController@sell_reports')->name('sell-reports');
        Route::get('sell_reports_confirm','Inventory\ReportsController@sell_confirm_reports')->name('sell-confirm_reports');
        Route::get('sell_report_ajax','Inventory\ReportsController@sell_report_ajax')->name('sell-reports-ajax');
        Route::get('buy_reports','Inventory\ReportsController@buy_reports')->name('buy-reports');
        Route::get('buy_reports_confirm','Inventory\ReportsController@buy_reports_confirm')->name('buy-report-confirm');
        Route::get('combined_reports','Inventory\ReportsController@combinedReports')->name('combined-reports');
        Route::get('buy_reports_ajax','Inventory\ReportsController@buy_reports_ajax')->name('buy-reports-ajax');
        Route::get('buy_pdf/{invoice}','Inventory\ReportsController@buy_reports_pdf')->name('buy-pdf');
        Route::get('sell_pdf/{invoice}','Inventory\ReportsController@sell_reports_pdf')->name('sell-pdf');
        Route::get('sell_statement_download','Inventory\ReportsController@sellReportsDownload')->name('sell-reports-download');
        Route::get('sell_confirm/{invoice}','Inventory\ReportsController@sell_confirm')->name('sell-confirm');
        Route::get('buy_confirm/{invoice}','Inventory\ReportsController@buy_confirm')->name('buy-confirm');
        Route::get('buy_statement_download','Inventory\ReportsController@BuyReportsDownload')->name('buy-reports-download');

        Route::get('sell_print/{invoice}','Inventory\ReportsController@sell_reports_pdf')->name('sell-print');
        Route::get('buy_print/{invoice}','Inventory\ReportsController@buy_reports_pdf')->name('buy-print');

        Route::get('buy_return_print/{invoice}','Inventory\ReportsController@buyReturnInvoicePrint')->name('buy-return-print');
        Route::get('sell_return_print/{invoice}','Inventory\ReportsController@sellReturnInvoicePrint')->name('sell-return-print');


    });

    Route::group(['prefix' => 'sell' , 'as' => 'sell_edit.'],function(){
        Route::get('sell-edit/{invoice}','Inventory\ProductSellEditController@sell_product_edit')->name('sell-pro-edit');
        Route::get('sell_edit/add-to-cart','Inventory\ProductSellEditController@addToCart')->name('add-to-cart');
        Route::get('sell_edit/add-to-cart-warrenty-product','Inventory\ProductSellEditController@addToCartWarrentyProduct')->name('add-to-cart-warrenty-product');
        Route::get('sell_edit/add-warrenty-product-sl-no','Inventory\ProductSellEditController@addWarrentyProductSlNo')->name('add-warrenty-product-sl-no');
        Route::get('sell_edit/remove-warrenty-product-sl','Inventory\ProductSellEditController@removeWarrentyProductSlNo')->name('remove-warrenty-product-sl');
        Route::get('sell_edit/get-cart','Inventory\ProductSellEditController@getCartContent')->name('get-cart');
        Route::get('sell_edit/cart-remove','Inventory\ProductSellEditController@removecart')->name('remove-cart');
        Route::get('sell_edit/cart-update','Inventory\ProductSellEditController@updatecart')->name('update-cart');
        Route::get('sell_edit/sell-confirm','Inventory\ProductSellEditController@sellEditConfirmFormShow')->name('sll-confirm');
        Route::post('sell_edit/cart-submit','Inventory\ProductSellEditController@cartSubmit')->name('cart-submit');

        Route::get('sell_edit_service_charge','Inventory\ProductSellEditController@addServiceCharges')->name('sell-edit-service-charge');
        

        Route::get('sell_edit_again/{invoice}','Inventory\ProductSellEditController@sell_edit_again')->name('sell-edit-again');
        
    });

    Route::group(['prefix' => 'buy' , 'as' => 'buy_edit.'],function(){
        Route::get('buy-edit/{invoice}','Inventory\ProductBuyEditController@buy_product_edit')->name('buy-edit-pro');

        Route::get('buy_edit/add-to-cart','Inventory\ProductBuyEditController@addToCart')->name('add-to-cart');
        Route::get('buy_edit/add-to-cart-warrenty-product','Inventory\ProductBuyEditController@addToCartWarrentyProduct')->name('add-to-cart-warrenty-product');
        //Buy_edit_new
        Route::get('buy_edit/update-warranty-product','Inventory\ProductBuyEditController@updateWarrantyProduct')->name('update-warranty-product');
        Route::get('buy_edit/add-warrenty-product-sl-no','Inventory\ProductBuyEditController@addWarrentyProductSlNo')->name('add-warrenty-product-sl-no');
        Route::get('buy_edit/remove-warrenty-product-sl','Inventory\ProductBuyEditController@removeWarrentyProductSlNo')->name('remove-warrenty-product-sl');
        Route::get('buy_edit/get-cart','Inventory\ProductBuyEditController@getCartContent')->name('get-cart');
        Route::get('buy_edit/cart-remove','Inventory\ProductBuyEditController@removecart')->name('remove-cart');
        Route::get('buy_edit/cart-update','Inventory\ProductBuyEditController@updatecart')->name('update-cart');
        Route::get('buy_edit/buy-confirm','Inventory\ProductBuyEditController@buyEditConfirmFormShow')->name('buy-confirm');
        Route::post('buy_edit/cart-submit','Inventory\ProductBuyEditController@cartSubmit')->name('cart-submit');


        Route::get('buy_edit_again/{invoice}','Inventory\ProductBuyEditController@sell_edit_again')->name('sell-edit-again');
    });

    Route::group(['prefix' => 'return' , 'as' => 'product_return.'],function(){
        Route::get('sell_return','Inventory\ProductReturnController@sale_return')->name('sell-return-view');
        Route::get('sell_return_ajax','Inventory\ProductReturnController@sale_product_ajax')->name('sale-return-ajax');
        
        Route::get('sell/add-to-cart','Inventory\ProductSellReturnController@addToCart')->name('sell.add-to-cart');
        Route::get('sell/add-to-cart-warrenty-product','Inventory\ProductSellReturnController@addToCartWarrentyProduct')->name('sell.add-to-cart-warrenty-product');
        Route::get('sell/add-warrenty-product-sl-no','Inventory\ProductSellReturnController@addWarrentyProductSlNo')->name('sell.add-warrenty-product-sl-no');
        Route::get('sell/remove-warrenty-product-sl','Inventory\ProductSellReturnController@removeWarrentyProductSlNo')->name('sell.remove-warrenty-product-sl');
        Route::get('sell/get-cart','Inventory\ProductSellReturnController@getCartContent')->name('sell.get-cart');
        Route::get('sell/cart-remove','Inventory\ProductSellReturnController@removecart')->name('sell.remove-cart');
        Route::get('sell/sell-confirm','Inventory\ProductSellReturnController@invTemporaryProduct')->name('sell.sell-confirm');
        Route::post('sell/cart-submit','Inventory\ProductSellReturnController@cartSubmit')->name('sell.cart-submit');

        
        Route::get('buy_return','Inventory\ProductReturnController@buy_return')->name('buy-return-view');
        Route::get('buy_return_ajax','Inventory\ProductReturnController@buy_product_ajax')->name('buy-return-ajax');
        
        Route::get('buy/add-to-cart','Inventory\ProductBuyReturnController@addToCart')->name('buy.add-to-cart');
        Route::get('buy/add-to-cart-warrenty-product','Inventory\ProductBuyReturnController@addToCartWarrentyProduct')->name('buy.add-to-cart-warrenty-product');
        Route::get('buy/add-warrenty-product-sl-no','Inventory\ProductBuyReturnController@addWarrentyProductSlNo')->name('buy.add-warrenty-product-sl-no');
        Route::get('buy/remove-warrenty-product-sl','Inventory\ProductBuyReturnController@removeWarrentyProductSlNo')->name('buy.remove-warrenty-product-sl');
        Route::get('buy/get-cart','Inventory\ProductBuyReturnController@getCartContent')->name('buy.get-cart');
        Route::get('buy/cart-remove','Inventory\ProductBuyReturnController@removecart')->name('buy.remove-cart');
        Route::get('buy/buy-confirm','Inventory\ProductBuyReturnController@invTemporaryProduct')->name('buy.buy-confirm');
        Route::post('buy/cart-submit','Inventory\ProductBuyReturnController@cartSubmit')->name('buy.cart-submit');
    });

    Route::group(['prefix' => 'damage' , 'as' => 'pro_damage.'],function(){
        Route::get('add','Inventory\ProductInventoryController@damage_add')->name('damage-add');
        Route::get('pro_name_ajax','Inventory\ProductInventoryController@show_pro_name_ajax')->name('pro_name');
        Route::post('pro_damage_submit','Inventory\ProductInventoryController@damage_add_submit')->name('damage-submit');
        Route::get('pro_damage_list','Inventory\ProductInventoryController@damage_list')->name('damage-list');
    });

    Route::group(['prefix' => 'profit_loss' , 'as' => 'profit_loss.'],function(){
        Route::get('profit','Inventory\ProfitLossController@profit_statement')->name('profit');
        Route::get('loss','Inventory\ProfitLossController@loss_statement')->name('loss');
    });


    //=============Inventory Account Controller Start================
    Route::group(['prefix' => 'accounts','as' => 'accounts.'],  function(){
        Route::get('add-bank','Inventory\BankAccountController@addBank')->name('add-bank');
        Route::post('add-bank','Inventory\BankAccountController@storeBank')->name('add-bank');
        Route::get('bank-list','Inventory\BankAccountController@showBankList')->name('bank-list');
        Route::get('delete-bank/{id}','Inventory\BankAccountController@deleteBank')->name('delete-bank');
        Route::get('update-bank/{id}','Inventory\BankAccountController@editBank')->name('update-bank');
        Route::post('update-bank/{id}','Inventory\BankAccountController@updateBank')->name('update-bank');
        Route::get('bank-diposit-withdraw','Inventory\BankAccountController@bankDepositWithdrawShowForm')->name('bank-diposit-withdraw');
        Route::get('bank-statement-detail/{id}','Inventory\BankAccountController@bankStatementDetails')->name('bank-statement-detail');
        
        Route::post('bank-diposit-withdraw','Inventory\BankAccountController@bankDepositWithdrawStore')->name('bank-diposit-withdraw');
        Route::get('expense-categories','Inventory\ExpenseCategoryController@showExpenseCategories')->name('expense-categories');
        Route::get('expenses','Inventory\ExpenseCategoryController@showExpenses')->name('expenses');
        Route::post('expense-categories','Inventory\ExpenseCategoryController@storeExpenseCategory')->name('expense-categories');
        //Riaz's Created
        Route::get('expense_category_edit/{cat_id}','Inventory\ExpenseCategoryController@expense_category_edit')->name('expense-category-edit');
        Route::post('expense_category_update/{id}','Inventory\ExpenseCategoryController@expense_category_update')->name('expense-category-update');
        //
        Route::post('expenses','Inventory\ExpenseCategoryController@storeExpenses')->name('expenses');
        Route::get('ajax-load_expense','Inventory\ExpenseCategoryController@ajaxLoadExpense')->name('ajax-load_expense');
        Route::get('create-contra','Inventory\BankAccountController@showContraForm')->name('create-contra');
        Route::get('contra-list','Inventory\BankAccountController@showContraList')->name('contra-list');
        Route::post('create-contra','Inventory\BankAccountController@createContra')->name('create-contra');
            
        Route::get('general_ledger','Inventory\BankAccountController@showGeneralLedgerForm')->name('general_ledger');
        Route::post('general_ledger','Inventory\BankAccountController@showGeneralLedgerData')->name('general_ledger');


        //=====================17-11-19 ================

        Route::get('general_ledger_download','Inventory\BankAccountController@downLoadGeneralLedger')->name('general_ledger_download');

        //=====================17-11-19 ================

//  ====================18-11-19 ===============
                Route::get('account-statement','Inventory\BankAccountController@showAccountStatementForm')->name('account-statement');
        Route::post('account-statement','Inventory\BankAccountController@showAccountStatementData')->name('account-statement');

        // ==================/18-11-19==================

        
        //=======================20-11-19===================
        Route::get('account-statement-download','Inventory\BankAccountController@downloadAccountStatement')->name('account-statement-download');
//================/20-11-19================
        // Routes for loading ajax for balance query

        Route::get('ajax-load-bank-balance','Inventory\BankAccountController@ajaxLoadBankBalance')->name('ajax-load-bank-balance');

        Route::get('ajax-load-supplier-balance','Inventory\BankAccountController@ajaxLoadSupplierBalance')->name('ajax-load-supplier-balance');

        Route::get('ajax-load-customer-balance','Inventory\BankAccountController@ajaxLoadCustomerBalance')->name('ajax-load-customer-balance'); 


        //============== Expense Voucher =============
        Route::get('expenses-voucher','Inventory\BankAccountController@expensesVoucherForm')->name('expenses-voucher');
        Route::post('expenses-voucher','Inventory\BankAccountController@expensesVoucherStore')->name('expenses-voucher');

                //==================== 16-11-19 =================

        Route::get('expense-categories-load-ajax','Inventory\ExpenseCategoryController@showAjaxLoadedExpensesCategory')->name('expense-categories-load-ajax');

        Route::get('expense-load-ajax','Inventory\ExpenseCategoryController@showAjaxLoadedExpenses')->name('expense-load-ajax');

    });
    //Inventory Account End

});
 
//Inventory Controller End
});
Auth::routes();

Route::get('/', 'UserController@home')->name('home')->middleware('auth');
// Route::get('/index', 'HomeController@dashboard')->name('dashboard');
// Route::get('/followup', 'HomeController@dashboard2')->name('followup');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
