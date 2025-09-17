<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Mail;
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

        // Send to site owner
        Mail::to(config('mail.from.address'))
            ->send(new ContactMessage($data));

        // Optional: send copy to sender
        if (!empty($data['email'])) {
            Mail::to($data['email'])->send(new ContactMessage($data, true));
        }

        return back()->with('flash', ['ok' => true, 'msg' => 'Thanks—your message is on its way.']);
    }
}
