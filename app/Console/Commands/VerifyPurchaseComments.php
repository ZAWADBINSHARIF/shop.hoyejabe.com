<?php

namespace App\Console\Commands;

use App\Models\ProductComment;
use Illuminate\Console\Command;

class VerifyPurchaseComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comments:verify-purchases 
                            {--all : Verify all comments regardless of current status}
                            {--unverified : Only check currently unverified comments}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify purchase status for existing product comments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting verification of product comments...');
        
        $query = ProductComment::query();
        
        if ($this->option('unverified')) {
            $query->where('is_verified_purchase', false);
        }
        
        $comments = $query->get();
        $totalComments = $comments->count();
        $verifiedCount = 0;
        
        $this->info("Processing {$totalComments} comments...");
        
        $bar = $this->output->createProgressBar($totalComments);
        $bar->start();
        
        foreach ($comments as $comment) {
            $wasVerified = $comment->is_verified_purchase;
            $shouldBeVerified = $comment->hasCustomerPurchasedProduct();
            
            if ($shouldBeVerified !== $wasVerified) {
                $comment->update(['is_verified_purchase' => $shouldBeVerified]);
                
                if ($shouldBeVerified) {
                    $verifiedCount++;
                }
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        
        $this->info("Verification complete!");
        $this->info("Total comments processed: {$totalComments}");
        $this->info("Newly verified comments: {$verifiedCount}");
        
        return Command::SUCCESS;
    }
}
