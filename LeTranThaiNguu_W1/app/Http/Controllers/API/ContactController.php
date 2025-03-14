<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Http\Resources\ContactResource;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        $contact = Contact::all();
        return ContactResource::collection($contact);
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $contact = new Contact();
        $contact->user_id = $request->input('user_id', null);
        $contact->name = $request->input('name');
        $contact->email = $request->input('email');
        $contact->phone = $request->input('phone');
        $contact->title = $request->input('title');
        $contact->content = $request->input('content');
        $contact->status = 1;
        $contact->save();

        return new ContactResource($contact);
    }

    public function show($id)
    {
        $contact = Contact::find($id);
        if ($contact) {
            // Trả về dữ liệu dạng JSON
            return response()->json($contact, 200);
        }

        return response()->json(['message' => 'Contact not found'], 404);
    }

    public function edit(Contact $contact)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);
        if ($contact == null) {
            return redirect()->route('admin.contact.index');
        }
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->title = $request->title;
        $contact->content = $request->content;
        $contact->replay_id = $request->replay_id;
        $contact->status = $request->status;
        $contact->updated_at = date('Y-m-d H:i:s');
        $contact->updated_by = Auth::id() ?? 1;
        $contact->save();
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response(null, 204);
    }
    public function status(string $id)
    {
        $contact = Contact::find($id);
        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        $contact->status = ($contact->status == 2) ? 1 : 2;
        $contact->save();

        return response()->json(['message' => 'Contact status updated successfully']);
    }
    public function delete(string $id)
    {
        $contact = Contact::find($id);
        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        // Đặt trạng thái thành 0 khi xóa
        $contact->status = 0;
        $contact->save();

        return response()->json(['message' => 'Contact status updated successfully']);
    }
}
