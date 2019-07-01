<?php

namespace App\Console\Commands;

use App\Transaction;
use App\InfoTransaction;
use Illuminate\Console\Command;

class PreviousDayTransactions extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:prevDay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stores the previous day\'s total number transactions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command
     */
    public function handle(): void {
        $yesterday = date("Y-m-d", strtotime('-1 days'));
        $prevDaytransactions = Transaction::whereDate('created_at', $yesterday)->get();

        // Find the total transactions
        $previousDayTotalTransactions = sizeof($prevDaytransactions);

        // Save the total transactions into the table info_transactions
        $transactionsInfo = InfoTransaction::create(['total_transactions' => $previousDayTotalTransactions, 'date' => $yesterday]);
        $transactionsInfo->save();

        // Print message
        $this->info('We had ' . $previousDayTotalTransactions . ' transactions on ' . $yesterday);
    }
}
