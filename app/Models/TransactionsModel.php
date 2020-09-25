<?php


namespace App\Models;


class TransactionsModel extends \CodeIgniter\Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
}