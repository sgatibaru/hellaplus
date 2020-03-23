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
?>

<div class="row overview-widgets">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center">Today's Disbursements</h4>
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
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="pull-right"><a title="Refresh Balances"><i class="mdi mdi-refresh"></i></a></div>
                <h4 class="text-center">Shortcode Balances</h4>
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
                </div>
            </div>
        </div>
    </div>
</div>