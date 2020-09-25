<?php






$shortcodes = $client->shortcodes;
?>
<div class="">
    <div class="card">
        <div class="card-header">
            <h4><?php echo $client->name; ?></h4>
            <h6>Phone: <?php echo $client->phone; ?>, Email: <?php echo $client->email; ?></h6>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4 class="title">Shortcodes</h4>
        </div>
        <div class="card-body">
            <?php
            if(is_array($shortcodes) && count($shortcodes) > 0) {
                ?>
                <div class="table-responsive">
                    <table class="table" id="datatable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Shortcode</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Transactions</th>
                            <th>Owner</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($shortcodes as $shortcode) {
                            $n++;
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo $shortcode->shortcode; ?></td>
                                <td><?php echo $shortcode->name; ?></td>
                                <td><?php echo $shortcode->type; ?></td>
                                <td><?php echo count($shortcode->allTransactions); ?></td>
                                <td><?php
                                    if(!empty($shortcode->owner)) {
                                        echo $shortcode->owner->name;
                                    } else {
                                        echo '-';
                                    }
                                    ?></td>
                                <td>
                                    <a class="btn btn-sm btn-primary text-white" href="<?php echo site_url(route_to('dashboard.shortcodes.view', $shortcode->id)); ?>">View</a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                ?>
                <div class="alert alert-warning">
                    This user has no shortcodes registered
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
