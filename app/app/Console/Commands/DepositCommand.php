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
    private $depositService, $activeDepositRepository;

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Adding percents to deposit';

    /**
     * Create a new command instance.
     * @param ActiveDepositRepository $activeDepositRepository
     */
    public function __construct(DepositService $depositService, ActiveDepositRepository $activeDepositRepository)
    {
        parent::__construct();
        $this->depositService = $depositService;
        $this->activeDepositRepository = $activeDepositRepository;
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
                $deposits = $this->activeDepositRepository->getDepositsByDate($i);
                $this->depositService->decrease($deposits);
            }
        } else {
            $deposits = $this->activeDepositRepository->getDepositsByDate();
            return $this->depositService->decrease($deposits);
        }
        return true;
    }
}
