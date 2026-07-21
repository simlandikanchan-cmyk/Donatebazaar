<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Show Contact Page
    public function index()
    {
        return view('contact');
    }

    // Save Message
    public function store(Request $request)
    {

        // dd($request->all());

        $request->validate([

            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',

        ]);

        Contact::create([

            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,

        ]);

        return back()->with('success', 'Message Sent Successfully');

    }

    // Admin Contact List
    public function adminIndex()
    {

        $contacts = Contact::latest()->get();

        return view('admin.contacts.index', compact('contacts'));

    }

    // Delete Contact
    public function adminDelete($id)
    {

        Contact::find($id)->delete();

        return back()->with('success', 'Message Deleted');

    }
}
