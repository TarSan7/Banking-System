<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repository\Eloquent\ActiveLoanRepository;
use Database\Factories\ActiveLoanFactory;
use Illuminate\Support\Arr;

class ChangeActiveLoanDatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loan:dates';

    private  $activeLoanRepository;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ActiveLoanRepository $activeLoanRepository)
    {
        parent::__construct();
        $this->activeLoanRepository = $activeLoanRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $allLoans = $this->activeLoanRepository->getIds();
        foreach ($allLoans as $oneLoan) {
            $this->activeLoanRepository->updateDate(Arr::get($oneLoan, 'id', null), (new ActiveLoanFactory)->date());
        }
        return true;
    }
}
