<?php

namespace App\Console\Commands;

use App\Repository\Eloquent\ActiveDepositRepository;
use Illuminate\Console\Command;

class DepositCommand extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'deposit:start';

    /**
     * @var ActiveDepositRepository
     */
    private $activeDepositRepository;

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Adding percents to deposit';

    /**
     * Create a new command instance.
     * @param ActiveDepositRepository $activeDepositRepository
     */
    public function __construct(ActiveDepositRepository $activeDepositRepository)
    {
        parent::__construct();
        $this->activeDepositRepository = $activeDepositRepository;
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle(): bool
    {
        $allDeposits = $this->activeDepositRepository->all();
        if ($allDeposits) {
            return $this->activeDepositRepository->decrease($allDeposits);
        }
        return true;
    }
}
