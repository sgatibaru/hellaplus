<?php
$model = new \App\Models\UsersModel();
$clients = $model->orderBy('id', 'DESC')->findAll();

?>
<div class="card">
    <div class="card-header">
        <h4 class="title">Clients</h4>
    </div>
    <div class="card-body">
        <div>
            <div class="table-responsive">
                <table class="table" id="datatable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Shortcodes</th>
                        <th>Created On</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($clients as $client) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $client->name; ?></td>
                            <td><?php echo $client->email; ?></td>
                            <td><?php echo $client->phone; ?></td>
                            <td><?php echo count($client->shortcodes); ?></td>
                            <td><?php echo date('d/m/Y', $client->created_on); ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary text-white" href="<?php echo site_url(route_to('dashboard.clients.view', $client->id)); ?>">View</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
