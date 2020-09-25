<?php


namespace App\Models;


class UsersModel extends \CodeIgniter\Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $returnType = '\App\Entities\UserEntity';
}