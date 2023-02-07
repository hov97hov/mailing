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

        if (gettype($data->categoryId) === 'string') {
            $exploderIds = explode(',', $data->categoryId);

            foreach ($exploderIds as $item) {
                ContactGroup::create([
                    'contact_id' => $contact->id,
                    'group_id' => $item,
                ]);
            }
        } else {
            foreach ($data->categoryId as $item) {
                ContactGroup::create([
                    'contact_id' => $contact->id,
                    'group_id' => $item,
                ]);
            }
        }
    }

    /**
     * @param $data
     */
    public function updateContact($data)
    {
        Contact::where('id', $data['id'])->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'description' => $data['description'],
        ]);

        foreach ($data['categoryId'] as $item) {
            ContactGroup::updateOrCreate([
                'contact_id' =>$data['id'],
                'group_id' => $item,
            ]);
        }
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
