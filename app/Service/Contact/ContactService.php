<?php

namespace App\Service\Contact;

use App\Interfaces\Contact\ContactInterface;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class ContactService implements ContactInterface
{
    /**
     * @param $data
     */
    public function createContact($data)
    {
        $contact = Contact::create([
            'name' => $data->name,
            'email' => $data->email,
            'user_id' => 1,
        ]);

        if ($data->hasFile('image')) {

            $data->validate([
                'image' => 'mimes:jpeg,jpg,png',
            ]);

            $image = $data->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('storage/images/contacts');
            $image->move($destinationPath, $name);

            Contact::where('id', $contact->id)->update([
                'image' => $name
            ]);
        }
    }

    /**
     * @param $data
     */
    public function updateContact($data)
    {
        return Contact::where('id', $data['id'])->update($data);
    }

    /**
     * @param $id
     */
    public function deleteContact($id)
    {
        return Contact::where('id', $id)->delete();
    }

    /**
     * @param $ids
     */
    public function deleteContacts($ids)
    {
        return Contact::whereIn('id', $ids)->delete();
    }

    public function deleteSelectedContact($ids)
    {
        return Contact::whereIn('id', $ids)->delete();
    }
}
