<?php

namespace App\Console\Commands;

use App\Repository\Eloquent\ActiveLoanRepository;
use App\Services\LoanService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

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
    private $loanService;

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
    public function __construct(LoanService $loanService)
    {
        parent::__construct();
        $this->loanService = $loanService;
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle(): bool
    {
        return $this->loanService->decrease();
    }
}
