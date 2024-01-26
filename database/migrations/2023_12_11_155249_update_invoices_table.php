<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('currency_id')->nullable()->constrained('currencies');
            $table->boolean('is_bank_transfer')->default(false)->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_account_owner')->nullable();
            $table->string('bank_iban')->nullable();
            $table->string('bank_swift')->nullable();
            $table->string('bank_address')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_city')->nullable();
            $table->string('bank_country')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
