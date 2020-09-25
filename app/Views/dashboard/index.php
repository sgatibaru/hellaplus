<?php


?>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <h1><strong><?php echo (new \App\Models\BusinessModel())->countAll(); ?></strong></h1>
                    <h6>Shortcodes</h6>
                    <a href="<?php echo site_url(route_to('dashboard.shortcodes')) ?>"><span><i class="mdi mdi-search-web"></i></span> View</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <h1><strong><?php echo (new \App\Models\UsersModel())->countAll(); ?></strong></h1>
                    <h6>Clients</h6>
                    <a href="<?php echo site_url(route_to('dashboard.clients')) ?>"><span><i class="mdi mdi-search-web"></i></span> View</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <h1><strong><?php echo (new \App\Models\TransactionsModel())->countAll(); ?></strong></h1>
                    <h6>C2B Transactions</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <h1><strong><?php echo (new \App\Models\B2CTransactionsModel())->countAll(); ?></strong></h1>
                    <h6>B2C Transactions</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Latest Shortcodes</h4>
                </div>
                <div class="card-body">
                    <?php
                    $shortcodes = (new \App\Models\BusinessModel())->orderBy('id', 'DESC')->limit(10)->findAll();
                    ?>
                    <div class="table-responsive">
                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Shortcode</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Owner</th>
                                    <th>Action</th>
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
                                    <td>
                                        <?php
                                        if(!empty($shortcode->owner)) {
                                            echo $shortcode->owner->name;
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                    <td><a class="btn btn-sm btn-primary text-white" href="">View</a></td>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">New Clients</h4>
                </div>
                <div class="card-body">
                    <?php
                    $clients = (new \App\Models\UsersModel())->orderBy('id', 'DESC')->limit(10)->findAll();
                    ?>
                    <div class="table-responsive">
                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Shortcodes</th>
                                    <th>Join Date</th>
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
    </div>
</div>