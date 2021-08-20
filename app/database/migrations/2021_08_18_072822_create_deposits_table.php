<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration
{
    /**
     * Standard deposits
     *
     * @var array[]
     */
    private $deposits = array(
        0 => array(
            'title' => 'Junior',
            'early_percent' => 6,
            'intime_percent' => 7,
            'min_duration' => 9,
            'max_duration' => 12,
            'max_sum' => 500000
        ),
        1 => array(
            'title' => 'Standard',
            'early_percent' => 5,
            'intime_percent' => 6.5,
            'min_duration' => 6,
            'max_duration' => 8,
            'max_sum' => 100000
        ),
        2 => array(
            'title' => 'Standard',
            'early_percent' => 6,
            'intime_percent' => 7,
            'min_duration' => 1,
            'max_duration' => 2,
            'max_sum' => 50000
        ),
        3 => array(
            'title' => 'Ten stars',
            'early_percent' => 6,
            'intime_percent' => 7,
            'min_duration' => 18,
            'max_duration' => 24,
            'max_sum' => 1000000
        ),
        4 => array(
            'title' => 'Friends',
            'early_percent' => 20,
            'intime_percent' => 30,
            'min_duration' => 4,
            'max_duration' => 12,
            'max_sum' => 999999
        )
    );

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->float('early_percent');
            $table->float('intime_percent');
            $table->integer('min_duration');
            $table->integer('max_duration');
            $table->integer('max_sum');
            $table->timestamps();
        });

        foreach ($this->deposits as $one) {
            $this->addDeposit($one);
        }
    }

    /**
     * Inserting deposits
     *
     * @param array $deposit
     */
    public function addDeposit($deposit)
    {
        DB::table('deposits')->insert($deposit);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deposits');
    }
}
