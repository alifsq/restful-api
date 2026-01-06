<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressCreateRequest;
use App\Http\Requests\AddressUpdateRequest;
use App\Http\Resources\AddressCollection;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    private function getContact(User $user, int $idcontact)
    {
        $contact = Contact::where('user_id', $user->id)->where('id', $idcontact)->first();

        if (!$contact) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'Contact Not Found'
                    ]
                ]
            ])->setStatusCode(404));
        }
        return $contact;
    }

    private function getAddress(Contact $contact, int $idaddress)
    {
        $address = Address::where('contact_id', $contact->id)->where('id', $idaddress)->first();

        if (!$address) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'Address Not Found'
                    ]
                ]
            ])->setStatusCode(404));
        }
        return $address;
    }

    public function create(int $idcontact, AddressCreateRequest $request)
    {
        $user = Auth::user();
        $contact = $this->getContact($user, $idcontact);

        $data = $request->validated();
        $address = new Address($data);
        $address->contact_id = $contact->id;
        $address->save();
        return (new AddressResource($address))->response()->setStatusCode(201);
    }

    public function get(int $idcontact, int $idaddress): AddressResource
    {
        $user = Auth::user();
        $contact = $this->getContact($user, $idcontact);
        $address = $this->getAddress($contact, $idaddress);
        if (!$address) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'Adrress NOt found'
                    ]
                ]
            ], 404));
        }

        return new AddressResource($address);
    }

    public function update(int $idcontact, int $idaddress, AddressUpdateRequest $request)
    {
        $user = Auth::user();
        $contact = $this->getContact($user, $idcontact);
        $address = $this->getAddress($contact, $idaddress);

        $data = $request->validated();
        $address->fill($data);
        $address->save();

        return new AddressResource($address);
    }

    public function delete(int $idcontact, int $idaddress)
    {
        $user = Auth::user();
        $contact = $this->getContact($user, $idcontact);
        $address = $this->getAddress($contact, $idaddress);

        $address->delete();
        return response()->json([
            'data' => true
        ], 200);
    }

    public function list(int $idcontact): AddressCollection
    {
        $user = Auth::user();
        $contact = $this->getContact($user, $idcontact);
        $address = Address::where('contact_id', $contact->id)->get();

        return new AddressCollection($address);
    }


}
