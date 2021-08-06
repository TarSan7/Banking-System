<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Standard loans
     *
     * @var array[]
     */
    private $loans = array(
        0 => array(
            'title' => 'Best',
            'percent' => 6,
            'duration' => 6,
            'max_sum' => 50000,
            'currency' => 'EUR'
        ),
        1 => array(
            'title' => 'Standard',
            'percent' => 10,
            'duration' => 12,
            'max_sum' => 100000,
            'currency' => 'UAH'
        ),
        2 => array(
            'title' => 'Standard',
            'percent' => 10,
            'duration' => 12,
            'max_sum' => 10000,
            'currency' => 'EUR'
        ),
        3 => array(
            'title' => 'Five stars',
            'percent' => 12.5,
            'duration' => 24,
            'max_sum' => 1000000,
            'currency' => 'USD'
        ),
        4 => array(
            'title' => 'Family',
            'percent' => 9,
            'duration' => 12,
            'max_sum' => 500000,
            'currency' => 'PLN'
        )
    );
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->float('percent');
            $table->integer('duration');
            $table->float('max_sum', $precision = 12, $scale = 2);
            $table->string('currency');
            $table->timestamps();
        });

        foreach ($this->loans as $loan) {
            $this->addLoan($loan);
        }
    }

    /**
     * Insert types og loans
     *
     * @param array $loan
     */
    private function addLoan($loan)
    {
        DB::table('loans')->insert($loan);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
