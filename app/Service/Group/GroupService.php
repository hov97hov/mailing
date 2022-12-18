<?php

namespace App\Service\Group;

use App\Interfaces\Group\GroupInterface;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Group;

class GroupService implements GroupInterface
{
    public function createGroup($data)
    {
        return Group::create($data);
    }

    public function createContactGroup($data)
    {
        $contacts = explode(',', $data->contacts);
        foreach ($contacts as $contact) {

            ContactGroup::firstOrCreate(
                [
                    'contact_id' => (int) $contact,
                    'group_id' => $data->group_id,
                ],
                [
                    'user_id' => 1,
                ]
            );
        }
    }

    public function deleteContactGroup($data)
    {
        return ContactGroup::where(['group_id' => $data->group_id, 'contact_id' => $data->contact_id])->delete();
    }

    public function deleteSelectedContactGroup($data)
    {
        return ContactGroup::whereIn('contact_id', $data->contact_ids)->where('group_id' , $data->group_id)->delete();
    }
}
