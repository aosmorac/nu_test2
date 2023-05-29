<?php

namespace App\Listeners;

use App\Events\ContactStored;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SendNewContactStoredEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ContactStored  $event
     * @return void
     */
    public function handle(ContactStored $event)
    {
        $contact = $event->contact;

        $to = Config::get('app.admin_mail');
        $subject = 'New contact';
        $message = "Name: " . $contact->name . "\n" .
            "Email: " . $contact->email . "\n" .
            "Phone: " . $contact->phone . "\n" .
            "Comment: " . $contact->comment . "\n\n\n";

        Mail::raw($message, function ($email) use ($to, $subject) {
            $email->to($to)->subject($subject);
        });
    }
}
