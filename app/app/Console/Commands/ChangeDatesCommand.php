<?php

namespace App\Console\Commands;

use App\Repository\Eloquent\ActiveLoanRepository;
use Database\Factories\ActiveLoanFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class ChangeDatesCommand extends Command
{

    private  $activeLoanRepository;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dates:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Changing dates in active deposits';

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
