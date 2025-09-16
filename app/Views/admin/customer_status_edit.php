<div class="container-fluid py-3">
    <h2 class="mb-4 text-center">Edit Status</h2>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <div id="alert-box"></div>
        </div>

        <input type="hidden" name="customer_id" id="customer_id" value="<?= $customer_details['customer_id'] ?>">

        <table id="customers_table" class="table table-bordered table-striped w-100 mb-5 mx-1">
            <thead class="table-primary">
                <tr>
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
                </tr>
            </thead>
        </table>
    </div>

    <div class="card shadow-sm mt-3">
        <table id="customers_table" class="table table-bordered table-striped mb-2 mx-1">
            <thead class="table-primary" align="center">
                <tr>
                    <th colspan="6">Details on <?= date('d-m-y');?></th>
                    <th colspan="6">Details on <?= $pending_details_lm['last_month_date']; ?></th>
                </tr>
            </thead>
            <tbody align="center">
                <tr>
                    <td colspan="3">Pending : </td>
                    <td colspan="3"><?= $pending_details['upto_today_pending']; ?></td>
                    <td colspan="3">Pending : </td>
                    <td colspan="3"><?= $pending_details_lm['end_pending']; ?></td>
                </tr>
                <tr>
                    <td colspan="3">Rs Paid : </td>
                    <td colspan="3"><?= $pending_details['total_paid']; ?></td>
                    <td colspan="3">Rs Paid : </td>
                    <td colspan="3"><?= $pending_details_lm['total_paid']; ?></td>
                </tr>
                <tr>
                    <td colspan="3">Had Paid : </td>
                    <td colspan="3"><?= $pending_details['had_paid']; ?></td>
                    <td colspan="3">Had Paid : </td>
                    <td colspan="3"><?= $pending_details_lm['had_paid']; ?></td>
                </tr>
                <tr>
                    <td colspan="3">Excess Paid : </td>
                    <td colspan="3"><?= $pending_details['excess_amount']; ?></td>
                    <td colspan="3">Excess Paid : </td>
                    <td colspan="3"><?= $pending_details_lm['excess_amount']; ?></td>
                </tr>
                <tr>
                    <td colspan="3">Profit : </td>
                    <td colspan="3"><?= $pending_details['profit_this_month']; ?></td>
                    <td colspan="3">Profit : </td>
                    <td colspan="3"><?= $pending_details_lm['profit_last_month']; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {

    var customer_id = $("#customer_id").val();

    $('#customers_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= site_url('/customers_list_data') ?>",
            type: "POST",
            data : { customer_id : customer_id }
        },
        lengthMenu: [ [50, 100, 150], [50, 100, 150] ],
        pageLength: 50,
        // dom: 'lfrtip',
        dom: 'rt',
        columns: [
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
        ]
    });
});
</script>