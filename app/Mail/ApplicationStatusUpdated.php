<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Application $application)
    {
    }

    public function envelope(): Envelope
    {
        $subjects = [
            'under_review' => 'Your application is under review — ' . $this->application->pet->name,
            'meet_greet' => 'Meet & greet scheduled — ' . $this->application->pet->name,
            'home_check' => 'Home visit scheduled — ' . $this->application->pet->name,
            'approved' => '🎉 Your adoption has been approved — ' . $this->application->pet->name,
            'rejected' => 'Application update — ' . $this->application->pet->name,
            'completed' => '🏠 Welcome home! Adoption complete — ' . $this->application->pet->name,
        ];

        return new Envelope(
            subject: $subjects[$this->application->status] ?? 'Application update — ' . $this->application->pet->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application-status-updated',
        );
    }
}