<?php


namespace App\Entities;


use App\Models\BusinessModel;

class UserEntity extends \CodeIgniter\Entity
{
    public function getName()
    {
        return $this->attributes['first_name'].' '.$this->attributes['last_name'];
    }

    public function getShortcodes()
    {
        return (new BusinessModel())->where('user', $this->attributes['id'])->findAll();
    }
}