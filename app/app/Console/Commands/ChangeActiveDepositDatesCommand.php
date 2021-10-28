<?php

namespace App\Console\Commands;

use App\Repository\Eloquent\ActiveDepositRepository;
use Database\Factories\ActiveLoanFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class ChangeActiveDepositDatesCommand extends Command
{
    private  $activeDepositRepository;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deposit:dates';

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
    public function __construct(ActiveDepositRepository $activeDepositRepository)
    {
        parent::__construct();
        $this->activeDepositRepository = $activeDepositRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $allDeposits = $this->activeDepositRepository->getIds();
        foreach ($allDeposits as $oneLoan) {
            $this->activeDepositRepository->updateDate(Arr::get($oneLoan, 'id', null), (new ActiveLoanFactory)->date());
        }
        return true;
    }
}
