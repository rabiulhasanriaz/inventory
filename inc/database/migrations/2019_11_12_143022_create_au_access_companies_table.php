<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuAccessCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('au_access_companies', function (Blueprint $table) {
            $table->bigIncrements('access_company_id');
            $table->string('code', 128)->comment('permission status code');
            $table->string('permission_name', 128)->comment('user permission name');
            $table->string('permission_title', 128)->comment('user permission title');
            $table->tinyInteger('status')->default(1)->comment('1=active, 0=in-active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('au_access_companies');
    }
}
