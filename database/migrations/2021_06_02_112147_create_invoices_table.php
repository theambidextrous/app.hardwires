<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('org', 15);
            $table->string('item');
            $table->string('qty', 5);
            $table->string('unit_cost', 15);
            $table->string('cost', 15);
            $table->string('due_date', 15);
            $table->string('is_paid', 1)->default(0);
            $table->string('paid_sum', 15)->nullable();
            $table->string('balance', 15)->nullable();
            $table->string('path')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
