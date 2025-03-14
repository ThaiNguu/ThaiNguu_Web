<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        return view("frontend.contact");
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20', // Adjust max length as per your database schema
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Create new contact instance
        $contact = new Contact();
        $contact->user_id = auth()->id(); 
        $contact->name = $request->input('name');
        $contact->email = $request->input('email');
        $contact->phone = $request->input('phone');
        $contact->title = $request->input('title');
        $contact->content = $request->input('content');
        $contact->status = 1;
        $contact->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Phản ánh của bạn đã được gửi thành công!');
    }
}
