<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * @var string[]
     */
    private $currency = array('UAH', 'EUR', 'USD', 'RUR', 'GBP', 'PLN');
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('number')->unique();
            $table->string('cvv');
            $table->date('expires_end');
            $table->float('sum', $precision = 30, $scale = 2);
            $table->string('currency');
            $table->timestamps();
        });

        for ($i = 0; $i < count($this->currency); $i++) {
            $this->addGlobal('000000000000000'.$i, $this->currency[$i]);
        }
    }

    /**
     * @param string $number
     * @param string $currency
     */
    private function addGlobal($number, $currency)
    {
        DB::table('cards')->insert( array(
            'type' => 'general',
            'number' => $number,
            'cvv' => rand(100, 999),
            'expires_end' => date('Y-m-d', strtotime( '+'.mt_rand(0,300).' days')),
            'sum' => 10000000000000000000000000000,
            'currency' => $currency
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
}
