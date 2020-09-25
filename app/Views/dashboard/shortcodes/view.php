<?php


use Config\Database; ?>
<div class="">
    <div class="card">
        <div class="card-header">
            <h4><?php echo '['.$shortcode->shortcode.'] '. $shortcode->name; ?></h4>
            <h6><?php echo $shortcode->type. ' shortcode ' . (isset($shortcode->owner) ? ' Owned by <a class="text-success" href="'.site_url(route_to('dashboard.clients.view', $shortcode->owner->id)).'">'.$shortcode->owner->name.'</a>' : ''); ?></h6>
        </div>
    </div>
    <?php
    if($shortcode->type == 'C2B') {
        //TODO: C2B
        $business = $shortcode;
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
        <script>
            var reportsUrl = "<?php echo site_url('admin/transactions/filter'); ?>";
        </script>
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="reportrange" id="reportrange" name="reportrange">
                            <i class="mdi mdi-calendar-text"></i>&nbsp;
                            <span></span>
                            <i class="mdi mdi-menu-down-outline"></i>
                        </div>
                        <h4 class="transactions-title">Transaction records</h4>
                    </div>
                    <div class="card-body" id="transactions_server">
                        <?php
                        $transactions = $shortcode->getAllTransactions();
                        $currency = get_option('currency', 'Kshs');
                        if($transactions && count($transactions) > 0) {
                            ?>
                            <div class="table-responsive longer" style="padding-bottom: 80px">
                                <table class="table table-hover display" id="datatable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Phone No.</th>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Ref No.</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $n_ = 0;
                                    foreach($transactions as $transaction) {
                                        $n_++;
                                        ?>
                                        <tr>
                                            <td><?php echo $n_; ?></td>
                                            <td><div title="<?php echo \Carbon\Carbon::createFromFormat('YmdHis', $transaction->trans_time, config('appTimezone'))->format('d/m/Y h:i A'); ?>"><?php echo \Carbon\Carbon::createFromFormat('YmdHis', $transaction->trans_time)->format('d/m/Y'); ?></div></td>
                                            <td><?php echo $transaction->msisdn; ?></td>
                                            <td><?php echo $transaction->trans_id; ?></td>
                                            <td><?php echo $currency.' '.number_format($transaction->trans_amount, 2); ?></td>
                                            <td><?php echo $transaction->ref_number; ?></td>
                                            <td>
                                                <?php
                                                if($transaction->trans_type == 'income') {
                                                    echo '<div class="text-success">Income</div>';
                                                } else {
                                                    echo '<div class="text-danger">Reversed</div>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Actions <span class="caret"></span> </button>
                                                    <ul class="dropdown-menu">
                                                        <?php
                                                        if($transaction->trans_type == 'income') {
                                                            ?>
                                                            <li><a class="c-dropdown__item dropdown-item  send-to-server-click" data="id:<?php echo $transaction->id; ?>|status:1" url="<?php echo site_url('admin/transactions/reverse/'.$transaction->id); ?>" warning-title="Reverse Transaction" warning-message="You are about to reverse this transaction" warning-button="Continue" loader="true"><i class="mdi mdi-refresh"></i> Reverse Transaction</a></li>
                                                            <?php
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
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
                            <div class="alert alert-info">
                                No transactions available
                            </div>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    <?php
    } else if ($shortcode->type == 'B2C') {
        //TODO: B2C
        $business = $shortcode;

        $builder = Database::connect()->table('transactions');
        ?>

        <div class="row overview-widgets">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Today's Disbursements</h4>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <h1>
                                <?php
                                $amount = \Config\Database::connect()->table('b2c')->selectSum('amount', 'amt')->where('date', date('m-d-Y'))->where('trx_id !=', '')->get()->getRowObject()->amt;
                                $amount = (isset($amount) && !is_null($amount)) ? number_format($amount, 2) : '0.00';
                                ?>
                                <strong><?php echo get_option('currency', 'Kshs') . ' '.$amount; ?></strong>
                            </h1>
                            <br/>
                            <a href="<?php echo site_url('admin/transactions'); ?>">View Transactions <span><i
                                            class="mdi mdi-hand-pointing-right"></i></span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="pull-right"><a title="Refresh Balances" class="send-to-server-click" data="check_balance:1" url="<?php echo site_url(route_to('admin.paybill.check_balance')); ?>" warning-title="Check Balance" warning-message="You are about to request for balances" warning-button="Continue" loader="true" style="cursor: pointer"><i
                                        class="mdi mdi-refresh"></i></a></div>
                        <h4 class="text-center">Shortcode Balances <small style="font-size: 10px">Last check <?php echo \Carbon\Carbon::createFromTimestamp(get_option($business->shortcode.'_last_balance_check', ''))->ago(); ?></small></h4>
                    </div>
                    <table class="card-body overflow table table-condensed table-striped">
                        <?php
                        $actual_data = get_option($business->shortcode.'_balance', FALSE);
                        if ($data = format_account_balance($actual_data) ) {
                            if ($data->ResultCode == 0) {
                                foreach ($data->Balances as $balance) {
                                    ?>
                                    <tr>
                                        <td><i class="mdi mdi-arrow-right-box"></i></td>
                                        <th><?php echo $balance->name; ?></th>
                                        <td><?php echo $balance->currency . ' ' . number_format($balance->amount, 2); ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="alert alert-danger"><b>WARNING: </b><?php echo $data->ResultDesc; ?></div> <?php
                            }
                        }  else {
                            ?>
                            <div class="alert alert-warning">Balance data may not be available</div> <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="reportrange" id="reportrange" name="reportrange">
                            <i class="mdi mdi-calendar-text"></i>&nbsp;
                            <span></span>
                            <i class="mdi mdi-menu-down-outline"></i>
                        </div>
                        <h4 class="transactions-title">Disbursement records</h4>
                    </div>
                    <div class="card-body" id="transactions_server">
                        <?php
                        $transactions = $shortcode->getAllB2CTransactions();
                        $currency = get_option('currency', 'Kshs');
                        if($transactions && count($transactions) > 0) {
                            ?>
                            <div class="table-responsive longer" style="padding-bottom: 80px">
                                <table class="table table-hover display" id="b2c">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Phone No.</th>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Sent To</th>
                                        <th>Completed At</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $n_ = 0;
                                    foreach($transactions as $transaction) {
                                        $n_++;
                                        ?>
                                        <tr>
                                            <td><?php echo $n_; ?></td>
                                            <td><div title="<?php echo \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_on, config('appTimezone'))->format('d/m/Y h:i A'); ?>"><?php echo \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_on)->format('d/m/Y'); ?></div></td>
                                            <td><?php echo $transaction->phone; ?></td>
                                            <td><?php echo $transaction->trx_id ? $transaction->trx_id : '-'; ?></td>
                                            <td><?php echo $currency.' '.number_format(is_numeric($transaction->amount) ? $transaction->amount : 0, 2); ?></td>
                                            <td><?php echo $transaction->receiver_name ? $transaction->receiver_name : '-'; ?></td>
                                            <td><?php echo $transaction->trx_time ? $transaction->trx_time : '-'; ?></td>
                                            <td>
                                                <?php
                                                if(isset($transaction->trx_id) && $transaction->trx_id != '') {
                                                    if($transaction->result_code == 0) {
                                                        echo '<div class="text-success">Completed</div>';
                                                    } else {
                                                        echo '<div class="text-danger" title="'.$transaction->result_desc.'">Failed</div>';
                                                    }
                                                } elseif ($transaction->result_code != 0) {
                                                    echo '<div class="text-danger" title="'.$transaction->result_desc.'">Failed</div>';
                                                } else {
                                                    echo '<div class="text-warning">Pending</div>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Actions <span class="caret"></span> </button>
                                                    <ul class="dropdown-menu">
                                                        <?php
                                                        if(false) {
                                                            ?>
                                                            <li><a class="c-dropdown__item dropdown-item send-to-server-click" data="id:<?php echo $transaction->id; ?>|status:1" url="<?php echo site_url('admin/transactions/reverse/'.$transaction->id); ?>" warning-title="Reverse Transaction" warning-message="You are about to reverse this transaction" warning-button="Continue" loader="true"><i class="mdi mdi-refresh"></i> Reverse Transaction</a></li>
                                                            <?php
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
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
                            <div class="alert alert-info">
                                No transactions available
                            </div>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    <?php
    } else {

    }
    ?>
</div>
