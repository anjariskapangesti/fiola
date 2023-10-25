<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Mail;
use App\Mail\TaskReminder;

class TestReminderEmail extends Command
{
    protected $signature = 'email:test';

    protected $description = 'Test sending a reminder email';

    public function handle()
    {
        // Logika untuk mengirim email reminder
        $countUnfinishedTasks = 'test';// Logika untuk menghitung task yang belum selesai
        Mail::to('diki@aiia.co.id')->send($countUnfinishedTasks);
        $this->info('Test email sent successfully!');
    }
}
