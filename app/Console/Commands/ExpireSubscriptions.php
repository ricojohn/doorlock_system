<?php

namespace App\Console\Commands;

use App\Models\MemberSubscription;
use App\Models\Member;
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
        
        $expiredSubscriptions = Member::whereHas('memberSubscriptions', function ($query) use ($today) {
            $query->where('end_date', '<', $today)
                ->where('payment_status', 'paid');
        })->get();

        $expiredSubscriptions->each(function ($member) {
            $member->memberSubscriptions->each(function ($memberSubscription) {
                $memberSubscription->update(['status' => 'expired']);
            });

            $this->info("Successfully expired {$member->full_name}'s subscription.");
            Log::info("Successfully expired {$member->full_name}'s subscription.");
        });

        

        return Command::SUCCESS;
    }
}
