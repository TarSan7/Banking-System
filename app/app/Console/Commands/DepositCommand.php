<?php

namespace App\Console\Commands;

use App\Repository\Eloquent\ActiveDepositRepository;
use App\Services\DepositService;
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
    private $depositService;

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Adding percents to deposit';

    /**
     * Create a new command instance.
     * @param ActiveDepositRepository $activeDepositRepository
     */
    public function __construct(DepositService $depositService)
    {
        parent::__construct();
        $this->depositService = $depositService;
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle(): bool
    {

        if (!in_array((int) date('d'), [29, 30, 31])) {
            return $this->depositService->decrease();
        }
        return true;
    }
}
