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
        $emails = explode(',', $data->contact_ids);
        foreach ($emails as $email) {

            ContactGroup::updateOrCreate(
                [
                    'contact_id' => (int) $email,
                    'group_id' => $data->categoryId,
                ],
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
