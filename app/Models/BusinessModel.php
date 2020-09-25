<?php


namespace App\Models;


class BusinessModel extends \CodeIgniter\Model
{
    protected $table      = 'businesses';
    protected $primaryKey = 'id';

    protected $returnType = '\App\Entities\BusinessEntity';

    protected $allowedFields = ['name', 'shortcode', 'user', 'type', 'consumer_key', 'env', 'consumer_secret', 'initiator_username', 'initiator_password'];

    protected $validationRules = [
        'name'  => 'trim|required',
        'shortcode' => 'trim|required|is_numeric|is_natural|min_length[6]',
        'type'  => 'trim|required|in_list[B2C,C2B]',
        'env'  => 'trim|required|in_list[live,sandbox]',
        'consumer_key'  => 'trim|required',
        'consumer_secret'   => 'trim|required',
        'initiator_username'    => 'trim',
        'initiator_password'    => 'trim'
    ];
    protected $skipValidation = false;

}