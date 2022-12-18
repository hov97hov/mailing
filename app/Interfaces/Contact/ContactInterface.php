<?php

namespace App\Interfaces\Contact;

interface ContactInterface {

    /**
     * @param $data
     */
    public function createContact($data);

    /**
     * @param $data
     */
    public function updateContact($data);

    /**
     * @param $id
     */
    public function deleteContact($id);

    /**
     * @param $ids
     * @return mixed
     */
    public function deleteContacts($ids);

    public function deleteSelectedContact($ids);

}
