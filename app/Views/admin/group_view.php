<div class="container-fluid py-3">
    <h2 class="mb-4 text-center">Group Details</h2>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <div id="alert-box"></div>
            <form class="form-horizontal" action="" id="group_add" method="post">

                <div class="d-flex mt-2 mb-2">
                    <div class="form-group d-flex align-items-center col-md-5">
                        <div class="col-md-2">
                            <label class="control-label h6 mx-1" for="group_name">Group Name :</label>
                        </div>
                        <div class="col-md-6">
                            <?= $group_data['group_name'];?>
                        </div>
                    </div>
                    <div class="form-group d-flex align-items-center col-md-5">
                        <div class="col-md-2">
                            <label class="control-label h6 mx-1" for="contact_no">Contact No :</label>
                        </div>
                        <div class="col-md-6">
                            <?= $group_data['contactno'];?>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="d-flex mt-4 mb-2">
                    <div class="form-group d-flex align-items-center col-md-5">
                        <label class="control-label h6 mx-2" for="address">Address : </label>
                        <div class="col-md-8">
                            <?= $group_data['address'];?>
                        </div>
                    </div>
                    <div class="form-group d-flex align-items-center col-md-4">
                        <label class="control-label h6 mx-2" for="job_details">Job details :</label>
                        <div class="col-md-8">
                            <?= $group_data['job_details'];?>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="d-flex justify-content-end">
                    <button type="button" id="group_save" name="group_save" class="btn btn-primary mx-4">
                    <i class="fa fa-spinner fa-spin d-none mx-2" id="faLoader"></i><span class="btn-label">Edit</span></button>
                </div>
            </form>
        </div>
    </div>

    <h2 class="mb-4 text-center mt-4">Customer List under <?= $group_data['group_name'];?></h2>
    
    <div class="card shadow-sm mt-2">
        <div class="card-body">
            <table id="customers_table" class="table table-bordered table-striped w-100 mb-5">
                <thead class="table-primary">
                    <tr>
                        <th>SI</th>
                        <th>Name</th>
                        <th>ForC</th>
                        <th>Date</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Pending</th>
                        <th>Int</th>
                        <th>Cm</th>
                        <th>Tm</th>
                        <th>Pm</th>
                        <th>T.Paid</th>
                        <th>T/NT</th>
                        <th>Dt</th>
                        <th>ToGiv</th>
                        <th>Ask</th>
                        <th width="5%">Code</th>
                        <th>Group</th>
                        <th>C.By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {

    var group_id = <?= $group_data['group_id'];?>;

    $('#customers_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= site_url('/customers_list_data') ?>",
            data : { group_id : group_id },
            type: "POST"
        },
        lengthMenu: [ [50, 100, 150], [50, 100, 150] ],
        pageLength: 50,
        columns: [
            { data: 'customer_id', orderable: true },
            { data: 'customer_name', orderable: true },
            { data: 'forc', orderable: true },
            { data: 'cm_paid_dates', orderable: false},
            { data: 'cm_paid', orderable: false},
            { data: 'due_amount', orderable: false},
            { data: 'pending', orderable: false},
            { data: 'interest', orderable: false},
            { data: 'cm', orderable: false},
            { data: 'tm', orderable: false},
            { data: 'pm', orderable: false},
            { data: 'tpaid', orderable: false},
            { data: 'tnt', orderable: false},
            { data: 'dt', orderable: false},
            { data: 'togiv', orderable: false},
            { data: 'ask', orderable: false},
            { data: 'code', orderable: false},
            { data: 'group', orderable: true},
            { data: 'cby', orderable: true},
            { data: 'action', orderable: false}
        ]
    });
});
</script>