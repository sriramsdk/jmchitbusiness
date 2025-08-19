<div class="container-fluid py-3">
  <h2 class="mb-4 text-center">Customer Details</h2>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="row mb-2 mx-2 mt-2">
        <div class="col-md-9">
          <h4 class="mb-1">Customer Name : <?= !empty($customer_address['customer_name'])?$customer_address['customer_name']:$customer_details['book_name'];?> </h4>
          <p class="text-muted mb-0">Customer code: <?= date('my',strtotime($customer_details['guessed_doj'])).'-'.$customer_details['month_si'] ?></p>
          <?php
            if($customer_details['aprox_doj'] == 1){
                $real_doj = '(Approx.date)';
            }else{
                $real_doj = date('d-m-Y',strtotime($customer_details['real_doj']));
            } 
          ?>
          <p class="text-muted">Real DOJ : <?= $real_doj; ?></p>
        </div>
      </div>

      <hr>

      <div class="row mb-4 mx-2">
        <div class="col-md-2">
            <h5 class="mb-1">Book Name : </h5><p><?= $customer_details['book_name']; ?></p>
        </div>
        <div class="col-md-2">
          <h5 class="mb-1">Family or C/O : </h5><p><?= !empty($customer_address['forc'])?$customer_address['forc']:$customer_details['care_of_name']; ?></p>
        </div>
        <div class="col-md-2">
          <h5 class="mb-1">Due Amount : </h5><p><?= $customer_details['due_amount']; ?></p>
        </div>
        <div class="col-md-2">
          <h5 class="mb-1">Months : </h5><p><?= $customer_details['months']; ?></p>
        </div>
        <div class="col-md-2">
          <h5 class="mb-1">Month starts with : </h5><p><?= $customer_details['starts_with_months']; ?></p>
        </div>
        <div class="col-md-2">
            <?php
            if(!empty($amount_given_details)){
              if($amount_given_details['amount_given_date']!='0000-00-00' and $amount_given_details['amount_given_date']!='' )
				      {
                $amt_giv_date = trim($amount_given_details['amount_given_date']);
                if(strlen($amt_giv_date)==10)
				        {
                    if(date('M-Y',strtotime($amt_giv_date)) == date('M-Y')){
						          $day_of_given = date('d',strtotime($amt_giv_date));
                    }
                    $amt_giv_date = date('M-y',strtotime($amt_giv_date));
                }else{
                    $amt_giv_date = $amount_given_details['amount_given_date'];
                }
              }else{
                $amt_giv_date='';
              }
            }else{
              $amt_giv_date='';
            }
            ?>
          <h5 class="mb-1">Amount Taken Date : </h5><p><?= $amt_giv_date; ?></p>
        </div>
      </div>

      <div class="row mb-3 mx-2">
        <div class="col-md-2">
          <h5 class="mb-1">Address : </h5><p><?= !empty($customer_address['address'])?$customer_address['address']:"N/A"; ?></p>
        </div>
        <div class="col-md-2">
          <h5 class="mb-1">Contact No : </h5><p><?= !empty($customer_address['contact_no'])?$customer_address['contact_no']:"N/A"; ?></p>
        </div>
        <div class="col-md-2">
          <h5 class="mb-1">Job details : </h5><p><?= !empty($customer_address['job_details'])?$customer_address['job_details']:"N/A"; ?></p>
        </div>
        <div class="col-md-2">
          <h5 class="mb-1">Recorded Date of Join : </h5><p><?= !empty($customer_details['guessed_doj'])?date('Y-m-d',strtotime($customer_details['guessed_doj'])):"N/A"; ?></p>
        </div>
        <?php
          $cm=0;
          $today_date = strtotime(date('Y-m-01'));
          $guessed_doj = strtotime ($customer_details['guessed_doj']);
				  $guessed_doj = strtotime(date('Y-m-01',$guessed_doj));
          while($guessed_doj <= $today_date)
          {
            $guessed_doj = strtotime ( '+1 month' , $guessed_doj ) ;
            $cm++;
          }
        ?>
        <div class="col-md-2">
          <h5 class="mb-1">Current Month : </h5><p><?= $cm; ?></p>
        </div>
        <?php
          if(!empty($customer_details['amount_needed_on']) && !empty($amt_giv_date)){
            $amount_needed_on = date('d-m-Y',strtotime($customer_details['amount_need_on'])).'('.date('M',strtotime($customer_details['amount_need_on'])).')';
          }else{
            $amount_needed_on = '';
          }
        ?>
        <div class="col-md-2">
          <h5 class="mb-1">Amount Needed On : </h5><p><?= !empty($amount_needed_on)?$amount_needed_on:"N/A"; ?></p>
        </div>
      </div>

      <div class="row mb-3 mx-2">
        <?php
          switch ($customer_details['status']){
            case 1:
              $color = "bg-primary";
              $status = "Running";
              break;
            case 2:
              $color = "bg-success";
              $status = "Closed - No pending";
              break;
            case 3:
              $color = "bg-info";
              $status = "Closed Intrest only Pending";
              break;
            case 4:
              $color = "bg-danger";
              $status = "Closed with Dues and Intrest Pending";
              break;
            case 5:
              $color = "bg-secondary";
              $status = "Cancelled";
              break;
            default: 
              $color = "bg-success";
              $status = "Running";
              break;
          }
        ?>
        <div class="col-md-2">
          <h5 class="mb-1">Status : </h5>
          <span class="badge w-25 <?= $color;?>"><?= $status;?></span>
        </div>
        <div class="col-md-2">
          <h5 class="mb-1">Total Amount : </h5>
          <p class="info-value"><?= !empty($total_amount)?$total_amount:"N/A";?></p>
        </div>
        <div class="col-md-2">
          <h5 class="mb-1">Group Name : </h5>
          <p class="info-value"><?= !empty($groups['group_name'])?$groups['group_name']:"N/A";?></p>
        </div>
        <div class="col-md-2">
          <h5 class="mb-1">Collection By : </h5>
          <p class="info-value"><?= !empty($collectionby['name'])?$collectionby['name']:"N/A";?></p>
        </div>
      </div>

      <div class="text-end mt-4">
        <a href="/customer_edit/<?= $customer_details['customer_id'];?>" class="btn btn-primary me-2">Edit</a>
        <a href="/dashboard" class="btn btn-outline-secondary">Back to List</a>
      </div>
    </div>
  </div>

  <h2 class="mb-4 mt-4 text-center">Pending Details</h2>
  <div class="card shadow-sm">
    <div class="card-body">
      <table class="table table-striped table-bordered">
        <thead align="center">
          <th>Details</th>
          <th>Upto Last Month</th>
          <th>Upto This Month</th>
        </thead>
        <tbody align="center">
          <tr>
            <td>Pending</td>
            <td><?= $pending_details_lm['end_pending']?></td>
            <td><?= $pending_details['upto_today_pending']?></td>
          </tr>
          <tr>
            <td>Amount Paid</td>
            <td><?= $pending_details_lm['total_paid'].'/'.$pending_details_lm['had_paid'];?></td>
            <td><?= $pending_details['total_paid'].'/'.$pending_details['had_paid'];?></td>
          </tr>
          <tr>
            <td>Excess Due Paid</td>
            <td><?= $pending_details_lm['excess_amount'];?></td>
            <td><?= $pending_details['excess_amount'];?></td>
          </tr>
          <tr>
            <td>Profit</td>
            <td><?= $pending_details_lm['profit_last_month'];?></td>
            <td><?= $pending_details['profit_this_month'];?></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <h2 class="mb-4 mt-4 text-center">Monthly Due Paid Details</h2>
  <div class="card shadow-sm">
    <div class="card-body">
      <table class="table table-striped table-bordered">
        <thead align="center">
          <th>Month</th>
          <th>Paid Date</th>
          <th>Amount Paid</th>
          <th>Paid Method</th>
          <th>B.Entry</th>
          <th>Collection By</th>
          <th>Hand Over By</th>
          <th>System Entry</th>
          <th>Remarks</th>
        </thead>
        <tbody align="center">
          <?php foreach($amount_entries as $value){

            switch ($value['paid_by']){
              case 1: $paid_by = "Cash";break;
              case 2: $paid_by = "Self Cheque";break;
              case 3: $paid_by = "Cash Deposit";break;
              case 4: $paid_by = "Collection Cheque";break;
              case 5: $paid_by = "Fund Transfer";break;
              case 6: $paid_by = "Fund Transfer to other";break;
              case 7: $paid_by = "Adjustments";break;
              default: $paid_by = "";break;
            }

            switch ($value['book_entry']){
              case 1: $book_entry = "Yes";break;
              case 2: $book_entry = "No";break;
              default: $book_entry = "";break;
            }

            $collection_id = $value['received_by'];

            $filtered = array_filter($collection_by, function($data) use ($collection_id){
              return $data['id'] == $collection_id;
            });

            $collection_by_name = reset($filtered);

            ?>
            <tr>
              <td><?= $value['month_no'];?></td>
              <td><?= date('d-m-Y',strtotime($value['paid_date']));?></td>
              <td><?= $value['paid_amount'];?></td>
              <td><?= $paid_by;?></td>
              <td><?= $book_entry;?></td>
              <td><?= $collection_by_name['name'] ?? "";?></td>
              <td><?= $value['hand_over_by'];?></td>
              <td><?= ($value['entry_date_in_system'] !== "0000-00-00")?date('d-m-Y',strtotime($value['entry_date_in_system'])):"N/A";?></td>
              <td><?= $value['remarks'];?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
