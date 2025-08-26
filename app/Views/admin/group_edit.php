<div class="container-fluid py-3">
    <h2 class="mb-4 text-center">Add New Group</h2>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <div id="alert-box"></div>
            <form class="form-horizontal" action="" id="group_update" method="post">
                <input type="hidden" id="group_id" name="group_id" value="<?= $group_data['group_id'];?>">
                <div class="d-flex mt-2 mb-2">
                    <div class="form-group d-flex align-items-center col-md-5">
                        <div class="col-md-2">
                            <label class="control-label h6 mx-1" for="group_name">Group Name :</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="group_name" placeholder="Enter Group Name" name="group_name" value="<?= $group_data['group_name'];?>">
                        </div>
                    </div>
                    <div class="form-group d-flex align-items-center col-md-5">
                        <div class="col-md-2">
                            <label class="control-label h6 mx-1" for="contact_no">Contact No :</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="contact_no" placeholder="Enter Contact No" name="contact_no" value="<?= $group_data['contactno'];?>">
                        </div>
                    </div>
                </div>
                <hr>

                <div class="d-flex mt-4 mb-2">
                    <div class="form-group d-flex align-items-center col-md-5">
                        <label class="control-label h6 mx-2" for="address">Address : </label>
                        <div class="col-md-8">
                            <textarea class="form-control" name="address" id="address" cols="50" rows="3" placeholder="Enter Address"><?= $group_data['address'];?></textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex align-items-center col-md-4">
                        <label class="control-label h6 mx-2" for="job_details">Job details :</label>
                        <div class="col-md-8">
                            <textarea class="form-control" name="job_details" id="job_details" cols="30" rows="3" placeholder="Enter Job details"><?= $group_data['job_details'];?></textarea>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="d-flex justify-content-end">
                    <button type="button" id="group_save" name="group_save" class="btn btn-primary mx-4">
                    <i class="fa fa-spinner fa-spin d-none mx-2" id="faLoader"></i><span class="btn-label">Update</span></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#group_save').on('click', function(){
            $('#faLoader').removeClass('d-none');
            $('.btn-label').text('Updating...');

            var form = $('#group_update');
            var data = form.serialize();
            $.ajax({
                url : '<?= base_url('/group_update')?>',
                type : "POST",
                data : data,
                success : function(response){
                    $('#alert-box').html('');
                    if(response.status == 'success'){
                        $('#faLoader').addClass('d-none');
                        $('.btn-label').text('Update');
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => { if(result.isConfirmed){ $('#group_add')[0].reset(); } });
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
    });
</script>