<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactCreateRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create(ContactCreateRequest $request)
    {

        $data = $request->validated();
        $user = Auth::user();

        $contact = new Contact($data);
        $contact->user_id = $user->id;
        $contact->save();

        return (new ContactResource($contact))->response()->setStatusCode(201);
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        $query = Contact::where('user_id', $user->id);

        $query->when($request->input('name'), function ($builder, $name) {
            $builder->where(function ($q) use ($name) {
                $q->where('first_name', 'like', '%' . $name . '%')
                    ->orWhere('last_name', 'like', '%' . $name . '%');
            });
        });

        $query->when($request->input('email'), function ($builder, $email) {
            $builder->where('email', 'like', '%' . $email . '%');
        });

        $query->when($request->input('phone'), function ($builder, $phone) {
            $builder->where('phone', 'like', '%' . $phone . '%');
        });

        $size = $request->input('size', 10);
        $page = $request->input('page', 1);

        $contacts = $query->paginate(perPage: $size, page:$page);

        return ContactResource::collection($contacts);
    }


    public function get(int $id)
    {
        $user = Auth::user();
        $contact = Contact::where('id', $id)->where('user_id', $user->id)->first();
        if (!$contact) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'Contact Not Found'
                    ]
                ]
            ]));
        }
        return new ContactResource($contact);
    }
}
