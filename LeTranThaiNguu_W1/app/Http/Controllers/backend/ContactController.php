<?php

namespace App\Http\Controllers\backend;
use App\Models\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateContactRequest;
class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = Contact::where('status', '!=', 0)
        ->select('id','user_id', 'name', 'email', 'phone','title','content','replay_id', 'status')
            ->orderBy('created_at', 'desc')
            ->get();
        return view("backend.contact.index", compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */ 
    public function show(string $id)
    {
        $contact = Contact::find($id);
        if ($contact == null)
        {
            return redirect()->route('admin.contact.index');
        }
        return view("backend.contact.show", compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contact = Contact::find($id);
        if ($contact == null) {
            return redirect()->route('admin.contact.index');
        }
        $list = Contact::where('status', '!=', 0)
            ->select('id','user_id', 'name', 'email', 'phone','title','content','replay_id', 'status')
            ->orderBy('created_at', 'desc')
            ->get();
        return view("backend.contact.edit", compact('list','contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, string $id)
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
        return redirect()->route('admin.contact.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = Contact::find($id);
        if ($contact == null) {
            return redirect()->route('admin.contact.trash');
        }
        $contact->delete();
        return redirect()->route('admin.contact.trash');
    }

    public function delete(string $id)
    {
    $contact = Contact::find($id);

    if ($contact == null) {
        return redirect()->route('admin.contact.index');
    }
    $contact->status = 0;
    $contact->updated_at = date('Ymd H:i:s');
    $contact->updated_by = Auth::id() ?? 1;
    $contact->save(); //Lưu
    return redirect()->route('admin.contact.index');
    }

    public function status(string $id)
    {
        $contact = Contact::find($id);
        if($contact==null)
        {
            return redirect()->route('admin.contact.index');
        }
        $contact->status = ($contact->status==2)?1:2;
        $contact->updated_at = date('Y-m-d H:i:s');
        $contact->updated_by = Auth::id() ?? 1;
        $contact->save();
        return redirect()->route('admin.contact.index');
    }
    public function trash()
    {
    $list = Contact::where('status', '=', 0)
        ->select('id', 'name', 'email', 'phone', 'title', 'content', 'replay_id', 'status')
        ->orderBy('created_at', 'desc')
        ->get();
    return view("backend.contact.trash", compact('list'));
    }
    public function restore(string $id)
    {
    $contact = Contact::find($id);
    if ($contact == null) {
        return redirect()->route('admin.contact.index');
    }
    $contact->status = 1;
    $contact->save();
    return redirect()->route('admin.contact.trash');
    }
}
