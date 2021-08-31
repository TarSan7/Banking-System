<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActiveDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('active_deposits', function (Blueprint $table) {
            $table->id();
            $table->integer('deposit_id');
            $table->float('sum', 10, 2);
            $table->float('total_sum', 10, 2);
            $table->string('currency');
            $table->float('month_pay', 10, 2);
            $table->integer('duration');
            $table->integer('month_left');
            $table->boolean('early_percent');
            $table->boolean('intime_percent');
            $table->integer('card_id');
            $table->integer('user_id');
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
        Schema::dropIfExists('active_deposits');
    }
}
