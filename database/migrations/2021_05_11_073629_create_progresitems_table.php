<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgresitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progresitems', function (Blueprint $table) {
            $table->id();
            $table->string('customer',15);
            $table->string('series',15);
            $table->string('prev_section')->nullable();
            $table->string('next_section')->nullable();
            $table->string('next_url')->nullable();
            $table->boolean('has_finished')->default(1);
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
        Schema::dropIfExists('progresitems');
    }
}
