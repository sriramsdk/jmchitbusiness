<?php

namespace App\Models;

use CodeIgniter\Model;

class AmountGivenDetails extends Model
{
    protected $table = "amount_given_details";
    protected $primaryKey = "amount_given_id";
    protected $allowedFields = ['customer_id','amount_given_date','actual_amount_given','given_amount','deduction_amount','balance_given_amount','amount_given_method','book_entry','given_by','cheque_ft_transfer_details','received_documents','remarks_amt_calc','status'];
    protected $returnType = "array";
}