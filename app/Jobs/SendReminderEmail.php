<?php

namespace App\Jobs;

use App\Mail\ReminderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reservation;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->reservation->buyer_email)->send(new ReminderMail($this->reservation));
            Log::info('Reminder email sent to: ' . $this->reservation->buyer_email);
        } catch (\Exception $e) {
            Log::error('Failed to send reminder email to: ' . $this->reservation->buyer_email . '. Error: ' . $e->getMessage());
            throw $e; // Re-throw the exception to trigger the retry mechanism
        }
    }

    /**
     * Handle a job failure.
     *
     * @return void
     */
    public function failed()
    {
        // Handle the failure scenario, e.g., log the failure or notify an admin
        Log::error('Failed to send reminder email to: ' . $this->reservation->buyer_email . ' after multiple attempts.');
    }
}