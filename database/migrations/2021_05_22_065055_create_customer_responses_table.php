<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_responses', function (Blueprint $table) {
            $table->id();
            $table->string('customer', 55);
            $table->string('section', 55);
            $table->string('question', 55);
            $table->text('label');
            $table->string('choice', 55);
            $table->text('choice_text');
            $table->string('points', 15);
            $table->string('running_sum', 15);
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('responses');
    }
}
