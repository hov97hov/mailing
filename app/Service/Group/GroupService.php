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
        $createGroup = Group::create([
            'name' => $data->name,
            'color' => $data->color,
            'sort' => $data->sort,
        ]);

        if($data->hasFile('image')) {
            $name = $data->image->getClientOriginalName();
            $data->image->move(public_path() . '/storage/groups/', $name);
            $filePath = '/storage/groups/'. $name;

            Group::where('id', $createGroup->id)->update([
                'image' => $filePath
            ]);
        }

        return response()->json([
            'status' => 200
        ]);

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
