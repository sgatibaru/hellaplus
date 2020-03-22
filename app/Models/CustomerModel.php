<?php


namespace App\Models;


class CustomerModel extends \CodeIgniter\Model
{
    protected $table      = 'customers';
    protected $primaryKey = 'id';

    protected $returnType = '\App\Entities\CustomerEntity';

    protected $allowedFields = ['fname', 'mname', 'lname', 'phone'];

    protected $validationRules = [
        'phone'  => 'trim|required',
    ];
    protected $skipValidation = false;
}