<?php


namespace App\Entities;


class CustomerEntity extends \CodeIgniter\Entity
{
    public function getFullName() {
        return $this->fname.' '.$this->mname.' '.$this->lname;
    }
}