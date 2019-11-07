<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvProductInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_product_inventories', function (Blueprint $table) {
            $table->bigIncrements('inv_pro_inv_id');
            $table->unsignedInteger('inv_pro_inv_com_id');
            $table->unsignedInteger('inv_pro_inv_prodet_id');
            $table->unsignedInteger('inv_pro_inv_ref_id')->comments('supplier and customer id');
            $table->tinyInteger('inv_pro_inv_ref_type')->comments('1=suppliers, 2=customer');
            $table->date('inv_pro_inv_issue_date');
            $table->integer('inv_pro_inv_qty');
            $table->decimal('inv_pro_inv_pur_price',12,2)->comments('purchase price');
            $table->string('inv_pro_inv_memo_no');
            $table->tinyInteger('inv_pro_inv_status')->default('1')->comments('1=active 0=inactive');
            $table->string('inv_pro_inv_barcode')->nullable();
            $table->date('inv_pro_inv_exp_date')->nullable();
            $table->unsignedInteger('inv_pro_inv_submit_by')->nullable();
            $table->dateTime('inv_pro_inv_submit_at')->nullable();
            $table->unsignedInteger('inv_pro_inv_update_by')->nullable();
            $table->dateTime('inv_pro_inv_update_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_product_inventories');
    }
}
