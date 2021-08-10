<?php

namespace App\Console\Commands;

use App\Repository\Eloquent\LoanRepository;
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
     * @var LoanRepository
     */
    private $loanRepository;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starting loan period';

    /**
     * Create a new command instance.
     *
     * @param LoanRepository $loanRepository
     * @return void
     */
    public function __construct(LoanRepository $loanRepository)
    {
        parent::__construct();
        $this->loanRepository = $loanRepository;
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle(): bool
    {

    }
}
