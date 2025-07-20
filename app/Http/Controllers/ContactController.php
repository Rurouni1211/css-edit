<?php

namespace App\Http\Controllers;

use App\Enums\ContactSubjectType;
use App\Http\Requests\ContactRequest;
use App\Mail\Contacted;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function create()
    {
        $from_email_address = config('mail.from.address');
        $email_parts = explode('@', $from_email_address);
        $domain = $email_parts[1];
        $customer_email = auth('customer')->user()->email ?? '';
        $customer_name = auth('customer')->user()->name ?? '';
        $contact_subject_types = ContactSubjectType::getCollection();

        return Inertia::render('Home/Contact/Create', [
            'domain' => $domain,
            'customerEmail' => $customer_email,
            'customerName' => $customer_name,
            'contactSubjectTypes' => $contact_subject_types
        ]);
    }

    public function store(ContactRequest $request)
    {
        $result = false;

        $customer = auth('customer')->user();
        $to = config('mail.admin.address');
        $contact = new Contact();
        $contact->customer_id = $customer->id ?? null;
        // 姓と名を結合して保存
        $contact->name = $request->last_name . ' ' . $request->first_name;
        $contact->email = $request->email;
        $contact->contact_subject_type = $request->contact_subject_type;
        $contact->order_id = $request->order_id;
        $contact->subject = $request->subject;
        $contact->body = $request->body;
        $contact->email_settings = $request->email_settings === 'true';
        $contact->confirmed = $request->confirmed === 'true';

        try {

            $contact->save();
            Mail::to($to)->send(new Contacted($request));
            $result = true;

        } catch (\Exception $e) {

            logger()->error($e->getMessage());

        }


        return [
            'result' => $result,
        ];
    }
}
