<div class="container-fluid mt-3 mb-5">
    <div class="d-flex justify-content-between">
        <div>
            <h3 class="mb-4">Groups List</h3>
        </div>
        <div>
            <a href="<?= base_url('group_add');?>" target="_blank" class="btn btn-primary">Add New Group</a>
        </div>
    </div>
    <table id="customers_table" class="table table-bordered table-striped w-100 mb-5">
        <thead class="table-primary">
            <tr>
                <th>SI.NO</th>
                <th>Group Name</th>
                <th>Contact No</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($groups)){
                $i = 1;
                foreach($groups as $key => $value){?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $value['group_name']; ?></td>
                    <td><?= $value['contactno']; ?></td>
                    <?php if($value['status'] == 1) {?>
                        <td>Active</td>
                    <?php }else{ ?>
                        <td>Inactive</td>
                    <?php } ?>
                    <td>
                        <a href="<?= base_url('/group_view').'/'.$value['group_id'];?>" target="_blanck" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                        <a href="#" target="_blanck" class="btn btn-sm btn-primary" title="View"><i class="fas fa-edit"></i></a>
                        <a href="#" target="_blanck" class="btn btn-sm btn-danger" title="View"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
            <?php }
                }else{?>
                <tr>
                    <td>No data</td>
                </tr>
            <?php }?>
        </tbody>
    </table>
</div>
