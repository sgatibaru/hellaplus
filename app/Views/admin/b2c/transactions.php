<script>
    //var reportsUrl = "<?php echo site_url('admin/transactions/filter'); ?>";
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
                <h4 class="transactions-title">Disbursement records</h4>
            </div>
            <div class="card-body" id="transactions_server">
                <?php
                $transactions = active_business()->getAllB2CTransactions();
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