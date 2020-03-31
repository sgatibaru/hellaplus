<?php


namespace App\Models;


class CustomerModel extends \CodeIgniter\Model
{
    protected $table      = 'customers';
    protected $primaryKey = 'id';

    protected $returnType = '\App\Entities\CustomerEntity';

    protected $allowedFields = ['fname', 'mname', 'lname', 'phone'];

    protected $validationRules = [
        'phone'  => 'trim|required|is_numeric|is_natural|exact_length[12]',
        'fname'     => 'trim|required',
    ];
    protected $skipValidation = false;
}