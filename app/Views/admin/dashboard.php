<div class="container-fluid mt-3 mb-5">
    <div class="d-flex justify-content-between">
        <div>
            <h3 class="mb-4">Customers List</h3>
        </div>
        <div>
            <a href="<?= base_url('customer_add');?>" target="_blank" class="btn btn-primary">Add Customer</a>
            <a href="<?= base_url('balance_sheet');?>" class="btn btn-secondary">Balance Sheet</a>
            <a href="<?= base_url('due_report');?>" class="btn btn-info">Due Report</a>
        </div>
    </div>
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

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    $('#customers_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= site_url('/customers_list') ?>",
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
