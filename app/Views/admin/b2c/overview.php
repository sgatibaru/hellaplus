<?php

use Config\Database;

$business = active_business();

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