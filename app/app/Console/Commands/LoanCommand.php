<?php

namespace App\Console\Commands;

use App\Repository\Eloquent\ActiveLoanRepository;
use App\Services\LoanService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use function Symfony\Component\Translation\t;

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
    private $loanService, $activeLoanRepository;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starting loan period';

    /**
     * Create a new command instance.
     *
     * @param LoanService $loanService
     * @return void
     */
    public function __construct(LoanService $loanService, ActiveLoanRepository $activeLoanRepository)
    {
        parent::__construct();
        $this->loanService = $loanService;
        $this->activeLoanRepository = $activeLoanRepository;
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle(): bool
    {
        if (date('d') === date('d', strtotime("last day of this month"))) {
            for ($i = (int) date('d'); $i <= 31; $i++) {
                $loans = $this->activeLoanRepository->getLoansByDate($i);
                $this->loanService->decrease($loans);
            }
        } else {
            $loans = $this->activeLoanRepository->getLoansByDate();
            return $this->loanService->decrease($loans);
        }
        return true;
    }
}
