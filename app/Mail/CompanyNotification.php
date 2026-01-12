<?php

namespace App\Mail;

use App\Models\MailConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;


class CompanyNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $customerData;

    /**
     * Create a new message instance.
     *
     * @param array $customerData
     */
    public function __construct($customerData)
    {
        $this->customerData = $customerData;
    }

    /**
     * Build the Mail Configuration.
     *
     * 
     */
    protected function getMailConfiguration()
    {
        $config = MailConfiguration::where('is_active', true)->first();
        $explicitMailer = env('MAIL_MAILER');
        $resendKey = config('services.resend.key');

        if ((!$explicitMailer || $explicitMailer === 'smtp') && $resendKey && !app()->environment('local')) {
            config(['mail.default' => 'resend']);
        }

        if ($config) {
            $mailPassword = $config->mail_password;

            config([
                'mail.mailers.smtp.host' => $config->mail_host,
                'mail.mailers.smtp.port' => $config->mail_port,
                'mail.mailers.smtp.username' => $config->mail_username,
                'mail.mailers.smtp.password' => $mailPassword,
                'mail.mailers.smtp.encryption' => $config->mail_encryption,
                'mail.from.address' => $config->mail_from_address,
                'mail.from.name' => $config->mail_from_name,
            ]);
        }
    }

    /**
     * Build the message.
     *
     * @return \Illuminate\Mail\Mailable
     */
    public function build()
    {
        $this->getMailConfiguration();

        return $this->subject('Thank You for Your Submission')
            ->view('emails.company_notification')
            ->with([
                'name' => $this->customerData['name'],
                // 'email' => $this->customerData['email'],
                // 'notes' => $this->customerData['notes'],
                // 'topic' => $this->customerData['topic'],
                // 'customerMessage' => $this->customerData['customerMessage'],
            ]);
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        // Menyusun envelope dengan subjek email
        return new Envelope(
            subject: 'Customer Notification', // Subjek email
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return []; // Jika tidak ada lampiran, kosongkan array ini
    }
}
