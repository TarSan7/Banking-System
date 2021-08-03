<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('card_from');
            $table->string('card_to');
            $table->dateTime('date');
            $table->float('sum', 10);
            $table->float('new_sum', 10);
            $table->string('currency');
            $table->text('comment');
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
        Schema::dropIfExists('card_transfers');
    }
}
