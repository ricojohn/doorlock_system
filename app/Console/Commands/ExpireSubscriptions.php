<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpireSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically expire subscriptions where end_date has passed';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = now()->toDateString();

        $expiredCount = Subscription::where('status', 'active')
            ->where('end_date', '<', $today)
            ->update(['status' => 'expired']);

        if ($expiredCount > 0) {
            $this->info("Successfully expired {$expiredCount} subscription(s).");
            Log::info("Successfully expired {$expiredCount} subscription(s).");
        } else {
            $this->info('No subscriptions to expire.');
            Log::info('No subscriptions to expire.');
        }

        return Command::SUCCESS;
    }
}
