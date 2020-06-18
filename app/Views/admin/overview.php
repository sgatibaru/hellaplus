<?php
$business = active_business();
$todaysTransactions = $business->getTodaysTotalTransactions();
$todaysReversals = $business->getTodaysTotalReversals();

$builder = \Config\Database::connect()->table('transactions');
$totalTransRows = $builder->where('date', date('m-d-Y'))->where('shortcode', $business->shortcode)->countAllResults(true);
$totalReversalRows = $builder->where('date', date('m-d-Y'))->where('shortcode', $business->shortcode)->where('trans_type', 'reversal')->countAllResults(true);
$totalSuccessRows = $totalTransRows-$totalReversalRows;

$incomeLoaded = $totalTransRows > 0 ? ($totalSuccessRows/$totalTransRows)*100 : 0;
$reversalLoaded = $totalTransRows > 0 ? 100-$incomeLoaded : 0;
$todaysTransactions = is_numeric($todaysTransactions) ? $todaysTransactions : 0;
$todaysReversals = is_numeric($todaysReversals) ? $todaysReversals : 0;
?>
<script>
    var totalIncome = "<?php echo $todaysTransactions; ?>";
    var totalReversals = "<?php echo $todaysReversals; ?>";
    var currency = "<?php echo get_option('currency', 'Kshs'); ?>";
    var reportsUrl = "<?php echo site_url('admin/transactions/reports'); ?>"
</script>
<div class="row overview-widgets">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4 class="">Today's Income</h4>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <h1><strong><?php echo get_option('currency', 'Kshs').' '.($todaysTransactions-$todaysReversals); ?></strong></h1>
                    <br/>
                    <a href="<?php echo site_url('admin/transactions'); ?>" >View Transactions <span><i class="mdi mdi-hand-pointing-right"></i></span></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4>Today's Income Info</h4>
            </div>
            <div class="card-body overflow">
                <div class="transaction-amount">
                    <!-- item -->
                    <div class="transaction-amount-item">
                        <div class="transaction-icon">
                            <i class="mdi mdi-checkbox-blank-circle text-primary"></i>
                        </div>
                        <div class="transaction-info">
                            <strong><?php echo $todaysTransactions.' '.get_option('currency_symbol', '/-'); ?></strong>
                            <span>Income</span>
                        </div>
                    </div>
                    <!-- item -->
                    <div class="transaction-amount-item">
                        <div class="transaction-icon">
                            <i class="mdi mdi-checkbox-blank-circle text-danger"></i>
                        </div>
                        <div class="transaction-info">
                            <strong><?php echo $todaysReversals.' '.get_option('currency_symbol', '/-'); ?></strong>
                            <span>Reversals</span>
                        </div>
                    </div>
                </div>

                <div class="transaction-visual">
                    <div id="transactions" style="height: 200px"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4>Today's Transactions</h4>
            </div>
            <div class="card-body">
                <div class="transaction-progress">
                    <div class="item mt-5">
                        <strong class="pull-right"><?php echo $totalSuccessRows; ?> Transactions</strong>
                        <p class="text-muted"> <i class="mdi mdi-checkbox-blank-circle-outline text-success"></i> Income</p>
                        <div class="progress progress-bar-success-alt">
                            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $incomeLoaded; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $incomeLoaded; ?>%">
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <strong class="pull-right"><?php echo $totalReversalRows; ?> Transactions</strong>
                        <p class="text-muted"> <i class="mdi mdi-checkbox-blank-circle-outline text-warning"></i> Reversals</p>
                        <div class="progress progress-bar-primary-alt">
                            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $reversalLoaded; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $reversalLoaded; ?>%">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="row transaction-links">
                        <div class="col-md-12">
                            <p class="text-center view-all-transaction">View all transaction records</p>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo site_url('admin/transactions'); ?>" class="btn btn-primary btn-block" type="button"> Income</a>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo site_url('admin/transactions'); ?>" class="btn btn-danger btn-block" type="button"> Reversals</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" id="range">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="reportrange" id="reportrange" name="reportrange">
                    <i class="mdi mdi-calendar-text"></i>&nbsp;
                    <span></span>
                    <i class="mdi mdi-menu-down-outline"></i>
                </div>
                <h4><span class="reports-title">Last 30 Days</span> activities</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 figure-stats">
                        <div class="figure-section">
                            <p>Total Income</p>
                            <span class="badge badge-primary pull-right income-count" data-toggle="tooltip" data-original-title="Transactions"><?php echo $totalSuccessRows; ?> Trns.</span>
                            <h2 class="text-primary reports-income"><?php echo get_option('currency', 'Kshs').' '.$business->getLastTotalTransactions(); ?></h2>
                        </div>
                        <div class="figure-section">
                            <p>Total Reversals</p>
                            <span class="badge badge-danger pull-right expenses-count" data-toggle="tooltip" data-original-title="Transactions"><?php echo $totalReversalRows; ?> Trns.</span>
                            <h2 class="text-danger reports-expenses"><?php echo get_option('currency', 'Kshs').' '.$business->getLastTotalReversals(); ?></h2>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div id="monthly" style="height: 379px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    <?php
        $graph = $business->graph();
    ?>
    // graph
    var labels = <?php echo json_encode($graph['labels']); ?>;
    var income = <?php echo json_encode($graph['income']); ?>;
    var expenses = <?php echo json_encode($graph['reversals']); ?>;

</script>