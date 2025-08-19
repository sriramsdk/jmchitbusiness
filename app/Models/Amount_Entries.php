<?php

namespace App\Models;

use CodeIgniter\Model;

class Amount_Entries extends Model
{
    protected $table = "amount_entries";
    protected $primaryKey = "entries_id";
    protected $allowedFields = ['customer_id','month_no','paid_amount','paid_date','paid_by','bank_name','entry_date_in_system','book_entry','hand_over_by','received_by','remarks'];
    protected $returnType = "array";
}

?>