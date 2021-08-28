<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('customer', 25);
            $table->string('amount', 15);
            $table->string('currency', 5);
            $table->string('ref');
            $table->string('ext_ref');
            $table->string('email', 100);
            $table->string('paid_amount', 15)->nullable();
            $table->text('init_payload');
            $table->text('payload')->nullable();;
            $table->boolean('is_paid')->default(0);
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
        Schema::dropIfExists('payments');
    }
}
