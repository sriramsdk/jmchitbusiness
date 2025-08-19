<div class="container-fluid py-3">
    <h2 class="mb-4 text-center">Edit Customer</h2>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <div id="alert-box"></div>
            <form class="form-horizontal" action="" id="customer_update" method="post">
                <input type="hidden" name="customer_id" value="<?= $customer_details['customer_id'];?>">
                <div class="d-flex mt-2 mb-2">
                    <div class="form-group d-flex align-items-center col-md-3">
                        <label class="control-label h6 mx-2" for="group">Select Group : </label>
                        <div class="col-md-8">
                            <select name="group" id="group" class="form-control">
                                <option value="">Select Group</option>
                                <?php if(!empty($groups)){ 
                                foreach($groups as $key => $group){?>
                                    <option value="<?= $group['group_id'] ?>" <?= ($customer_details['group_id'] == $group['group_id'])?"selected":""; ?>  ><?= $group['group_name']?></option>
                                <?php }}?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group d-flex align-items-center col-md-6">
                        <div class="col-md-3">
                            <label class="control-label h6 mx-1" for="forc">Family or C/o Name :</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="forc" placeholder="Enter Family or C/o Name" name="forc" autocomplete="off" value="<?= !empty($customer_address['forc'])?$customer_address['forc']:""; ?>">
                        </div>
                        <div class="col-md-4 mx-2">
                            <span class="text-success">[If not exist,enter here or leave it.]</span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-flex mt-4 mb-2">
                    <div class="form-group d-flex align-items-center col-md-3">
                        <label class="control-label h6 mx-2" for="customer_name">Customer Name : </label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="customer_name" placeholder="Enter Customer name" name="customer_name" autocomplete="off" value="<?= !empty($customer_address['customer_name'])?$customer_address['customer_name']:""; ?>">
                        </div>
                    </div>
                    <div class="form-group d-flex align-items-center col-md-6">
                        <div class="col-md-3">
                            <label class="control-label h6 mx-2" for="book_name">Name mentioned in Book :</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="book_name" placeholder="Enter Book name" name="book_name" autocomplete="off" value="<?= !empty($customer_details['book_name'])?$customer_details['book_name']:""; ?>">
                        </div>
                        <div class="col-md-4 mx-2">
                            <span class="text-success">[If it is same as Customer name,leave it.]</span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-flex mt-4 mb-2">
                    <div class="form-group d-flex align-items-center col-md-4">
                        <label class="control-label h6 mx-2" for="address">Address : </label>
                        <div class="col-md-8">
                            <textarea class="form-control" name="address" id="address" cols="50" rows="3" placeholder="Enter Address"><?= !empty($customer_address['address'])?$customer_address['address']:""; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex align-items-center col-md-4">
                        <label class="control-label h6 mx-2" for="job_details">Job details :</label>
                        <div class="col-md-8">
                            <textarea class="form-control" name="job_details" id="job_details" cols="30" rows="3" placeholder="Enter Job details"><?= !empty($customer_address['job_details'])?$customer_address['job_details']:""; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex align-items-center col-md-4">
                        <label class="control-label h6 mx-2" for="contact_no">Contact No :</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="contact_no" placeholder="Enter Contact No" name="contact_no" autocomplete="off" value="<?= !empty($customer_address['contact_no'])?$customer_address['contact_no']:""; ?>">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-flex mt-4 mb-2">
                    <div class="form-group d-flex align-items-center col-md-8">
                        <div class="col-md-2">
                            <input type="hidden"  name="joined_date_guessed" id="joined_date_guessed" <?php if(!empty($customer_details['guessed_doj']) && $customer_details['guessed_doj'] !== "0000-00-00" ){?>  value="<?= date('d-m-Y',strtotime($customer_details['guessed_doj']));?>" <?php }?> >
                            <label class="control-label h6 mx-2" for="date_of_join">Date of join [dd/mm/yy] : </label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="join_date" placeholder="Enter Date of join" name="date_of_join" <?php if(!empty($customer_details['real_doj']) && $customer_details['real_doj'] !== "0000-00-00" ){?>  value="<?= date('d-m-Y',strtotime($customer_details['real_doj']));?>" <?php }else{?> value="<?= date('d-m-Y');?>" <?php } ?> >
                        </div>
                        <div class="col-md-6 mx-2">
                            <input type="radio" class="join_date" id="join_date_today" name="join_date" autocomplete="off" checked value="today" onclick="date_display()"> Today
                            <input type="radio" class="join_date" id="join_date_yesterday" name="join_date" autocomplete="off" value="yesterday" onclick="date_display()"> Yesterday
                            <input type="radio" class="join_date" id="join_date_other_date" name="join_date" autocomplete="off" value="other_date" onclick="date_display()"> Other Date
                            <input type="checkbox" class="mx-2" id="approximate_date" name="approximate_date" <?= ($customer_details['aprox_doj'] == 1)?"checked":""; ?> ><span class="text-success" >approximate date</span>
                        </div>
                    </div>
                    <div class="form-group d-flex align-items-center col-md-4">
                        <label class="control-label h6 mx-2" for="due_amount">Due Amount :</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="due_amount" placeholder="Enter Due Amount" name="due_amount" autocomplete="off" value="<?= $customer_details['due_amount'] ?>" onblur="amount_calculation()">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-flex mt-4 mb-2">
                    <div class="form-group d-flex align-items-center col-md-4">
                        <div class="col-md-2">
                            <label class="control-label h6 mx-2" for="months">Months : </label>
                        </div>
                        <div class="col-md-3">
                            <select name="months" id="months" class="form-control" onchange=amount_calculation()>
                                <option value="">Select Months</option>
                                <?php if(!empty($months)){ 
                                foreach($months as $key => $month){?>
                                    <option value="<?= $key ?>" <?= ($customer_details['months'] == $key)?"selected":""; ?> ><?= $month;?></option>
                                <?php }}?>
                            </select>
                        </div>
                        <b class="mx-2"><span id="amount_tot" style="color:green"></span></b>
                        <input type="hidden" name="amount"  id="amount"  value="" width="5%"  >
                    </div>
                    <div class="form-group d-flex align-items-center col-md-4">
                        <label class="control-label h6 mx-2" for="group">Starts with :</label>
                        <div class="col-md-2">
                            <select name="starts_month" id="starts_month" class="form-control" onchange=month_start_calc()>
                                <option value="">Select Start Month</option>
                                <?php if(!empty($start_months)){ 
                                foreach($start_months as $key => $month){?>
                                    <option value="<?= $key ?>" <?= ($customer_details['starts_with_months'] == $month)?"selected":""; ?> ><?= $month;?></option>
                                <?php }}?>
                            </select>
                        </div>
                        <b> <span class="mx-1" id="guessed_date" style="color:green"></span></b>
                    </div>
                    <div class="form-group d-flex align-items-center col-md-4">
                        <label class="control-label h6 mx-2" for="collection_by">Collection By : </label>
                        <div class="col-md-4">
                            <select name="collection_by" id="collection_by" class="form-control">
                                <option value="">Select Collection By</option>
                                <?php if(!empty($collection_by)){ 
                                foreach($collection_by as $key => $data){?>
                                    <option value="<?= $data['id']; ?>" <?= ($customer_details['collection_by'] == $data['id'])?"selected":""; ?> ><?= $data['name'];?></option>
                                <?php }}?>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-flex mt-4 mb-2">
                    <div class="form-group d-flex align-items-center col-md-8">
                        <div class="col-md-3">
                            <label class="control-label h6 mx-2" for="amount_needed_on">Amount need on(dd-mm-yyyy)	: </label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="amount_needed_on" placeholder="Enter date" name="amount_needed_on" autocomplete="off" <?php if(!empty($customer_details['amount_need_on'])){?>  value="<?= date('d-m-Y',strtotime($customer_details['amount_need_on']));?>" <?php }?>>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-end">
                    <button type="button" id="customer_save" name="customer_save" class="btn btn-primary mx-4">
                    <i class="fa fa-spinner fa-spin d-none mx-2" id="faLoader"></i><span class="btn-label">Update</span></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    function month_start_calc(){
        $('#joined_date_guessed').val('');
        var month_st = $("#starts_month option:selected").val();
        if(month_st!=''){
            var doj=$('#join_date').val();
            if( doj!=''){
                var date_split = doj.split("-");
                var day=date_split[0];
                var month=date_split[1];
                var year=date_split[2];
                var doj_with_format=year+" "+month+" "+day;
                var dt=new Date(doj_with_format);
                var month_new=(dt.getMonth()-month_st)+2;
                if(month_new<=0){
                    var new_date='10'+"-"+(month_new+12)+"-"+(year-1);
                }else{
                    var new_date='10'+"-"+month_new+"-"+year;
                }
                $('#joined_date_guessed').val(new_date);
                $('#guessed_date').text("Joined date converted as:"+new_date);
            }else{
                // alert('Please Enter Joined date');
            }
        }   
    }

    function amount_calculation(){
        if($('#due_amount').val()!=''){
            var amount=$('#due_amount').val()*$('#months').val();
            $('#amount').val(amount);
            $('#amount_tot').text(' Amount : ' +amount);
        }
    }

    function date_display(){
        var type = $('input[class="join_date"]:checked').val();
        // console.log(type);
        $('#join_date').val('');
        switch (type) {
            case 'today':
                $('#join_date').val('<?= date('d-m-Y');?>');
                break;
            case 'yesterday':
                $('#join_date').val('<?= date('d-m-Y',strtotime('-1 days'));?>');
                break;
            case 'other_date':
                $('#join_date').val('');
                break;
            default:
                $('#join_date').val('<?= date('d-m-Y');?>');
                break;
        }
    }

    // date_display();
    amount_calculation();
    month_start_calc();
    
    $(document).ready(function(){
        
        $("#join_date, #amount_needed_on").datepicker({
            dateFormat: 'dd-mm-yy',
            onSelect: function (date) {
                month_start_calc();
            }
        });

        $("#customer_save").on('click',function(){
            $('#faLoader').removeClass('d-none');
            $('.btn-label').text('Updating...');

            var form = $('#customer_update');
            var data = form.serialize();
            $.ajax({
                url : '<?= base_url('/customer_update')?>',
                type : "POST",
                data : data,
                success : function(response){
                    $('#alert-box').html('');
                    if(response.status == 'success'){
                        $('#faLoader').addClass('d-none');
                        $('.btn-label').text('Update');
                        $('#guessed_date').text('');
                        $('#amount_tot').text('');
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => { if(result.isConfirmed){ $('#customer_add')[0].reset(); } });
                    }else{
                        $('#faLoader').addClass('d-none');
                        $('.btn-label').text('Update');
                        let errorHtml = '<div class="alert alert-danger" role="alert"><ul>';
                        $.each(response.errors,function(key,val){
                            errorHtml += '<li>'+val+'</li>'
                        });
                        errorHtml += '</ul></div>';
                        $('#alert-box').html(errorHtml);
                    }
                },
                error: function(xhr, status, error){
                    $('#faLoader').addClass('d-none');
                    $('.btn-label').text('Update');
                    $('#alert-box').html('');
                    let message = '<div class="alert alert-danger" role="alert">Error : '+error+'</div>';
                    $('#alert-box').html(message);
                }
            });
        });

        $('#group').on('change',function(){
            var group_id = $(this).val();
            console.log(group_id);

            $.ajax({
                url : '<?= base_url('/get_group_details')?>',
                type : "POST",
                data : { group_id : group_id },
                success : function(response){
                    // console.log(response);
                    data = response.data;
                    $('#address').val(data.address);
                    $('#job_details').val(data.job_details);
                    $('#contact_no').val(data.contactno);
                },
                error: function(xhr, status, error){
                    console.log('Ajax Error',xhr);
                    console.log('Ajax Error',status);
                    console.log('Ajax Error',error);
                }
            })
        });

    });
</script>