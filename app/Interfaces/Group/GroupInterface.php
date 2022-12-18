<?php

namespace App\Interfaces\Group;

interface GroupInterface {

    public function createGroup($data);

    public function createContactGroup($data);

    public function deleteContactGroup($data);

    public function deleteSelectedContactGroup($data);
}
