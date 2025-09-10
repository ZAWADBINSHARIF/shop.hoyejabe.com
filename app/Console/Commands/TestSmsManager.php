<?php

namespace App\Console\Commands;

use App\Services\SmsManager;
use App\Models\SmsConfiguration;
use Illuminate\Console\Command;

class TestSmsManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:test {phone? : Phone number to test} {--provider= : Force specific provider (smsq or bulksmsbd)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test SMS Manager functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $phone = $this->argument('phone') ?? '01700000000'; // Default test number
        $forcedProvider = $this->option('provider');

        // Show current configuration
        $config = SmsConfiguration::first();
        $activeProvider = $config?->active_provider ?? 'smsq';
        
        $this->info('=== SMS Manager Test ===');
        $this->info('Current Active Provider: ' . $activeProvider);
        
        if ($forcedProvider) {
            $this->warn("Temporarily switching to provider: {$forcedProvider}");
            if ($config) {
                $config->active_provider = $forcedProvider;
                $config->save();
            }
        }

        // Initialize SMS Manager
        $smsManager = new SmsManager();
        
        $this->info('Active Provider (from Manager): ' . $smsManager->getActiveProvider());
        $this->line('');

        // Test 1: Single SMS
        $this->info('Test 1: Sending single SMS...');
        $testMessage = "Test SMS from " . config('app.name') . " at " . now()->format('Y-m-d H:i:s');
        
        $result = $smsManager->sendSms($phone, $testMessage);
        
        if ($result) {
            $this->info('✓ Single SMS sent successfully');
        } else {
            $this->error('✗ Failed to send single SMS');
        }
        
        // Test 2: Check balance (if supported)
        $this->line('');
        $this->info('Test 2: Checking balance...');
        
        $balance = $smsManager->checkBalance();
        
        if ($balance['success'] ?? false) {
            $this->info('✓ Balance: ' . ($balance['balance'] ?? 'N/A') . ' ' . ($balance['currency'] ?? ''));
        } else {
            $this->warn('⚠ ' . ($balance['error'] ?? 'Balance check not available'));
        }

        // Test 3: Format phone number
        $this->line('');
        $this->info('Test 3: Phone number formatting...');
        
        $testNumbers = ['01712345678', '8801712345678', '1712345678'];
        foreach ($testNumbers as $number) {
            $formatted = $smsManager->formatPhoneNumber($number);
            $this->line("  {$number} => {$formatted}");
        }

        // Restore original provider if forced
        if ($forcedProvider && $config) {
            $config->active_provider = $activeProvider;
            $config->save();
            $this->line('');
            $this->info("Restored original provider: {$activeProvider}");
        }

        $this->line('');
        $this->info('=== Test Complete ===');
        
        return Command::SUCCESS;
    }
}