<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function create()
    {
        return Inertia::render('Contact');
    }

    public function store(ContactRequest $request)
    {
        $data = $request->validated();

        try {
            Mail::to(config('mail.contact_to'))
                ->send(new ContactMessage($data));
        } catch (TransportExceptionInterface $exception) {
            Log::error('Contact form owner notification could not be sent.', [
                'exception' => $exception::class,
            ]);

            return back()->withErrors([
                'message' => 'Your message could not be sent. Please try again later.',
            ]);
        }

        // The sender's copy is optional; a failure must not invalidate delivery to the owner.
        if (! empty($data['email'])) {
            try {
                Mail::to($data['email'])->send(new ContactMessage($data, true));
            } catch (TransportExceptionInterface $exception) {
                Log::warning('Contact form sender copy could not be sent.', [
                    'exception' => $exception::class,
                ]);
            }
        }

        return back()->with('flash', ['ok' => true, 'msg' => 'Thanks—your message is on its way.']);
    }
}
