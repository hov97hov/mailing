<?php

namespace App\Service\Contact;

use App\Interfaces\Contact\ContactInterface;
use App\Models\Contact;
use App\Models\ContactGroup;
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
            'description' => $data->description,
        ]);

        foreach ($data->categoryId as $item) {
            ContactGroup::create([
                'contact_id' => $contact->id,
                'group_id' => $item,
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
