<?php

namespace App\Console\Commands;

use App\Repository\Eloquent\ActiveLoanRepository;
use Illuminate\Console\Command;

class LoanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loan:start';

    /**
     * @var ActiveLoanRepository
     */
    private $activeLoanRepository;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starting loan period';

    /**
     * Create a new command instance.
     *
     * @param ActiveLoanRepository $loanRepository
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
     * @return bool
     */
    public function handle(): bool
    {
        $allCards = $this->activeLoanRepository->getCardsId();
        if ($allCards) {
            return $this->activeLoanRepository->decrease($allCards);
        }
        return true;
    }
}
