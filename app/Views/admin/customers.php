<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="pull-right">
                    <button type="button" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#sendSMSes"><i class="mdi mdi-send"></i> Send SMS</button>
                </div>
                <h4>My Customers</h4>
            </div>
            <div class="modal fade" id="sendSMSes" role="dialog">
                <div class="modal-dialog modal-sm">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Send Bulk SMS to Customers</h4>
                        </div>
                        <form  class="simcy-form" action="<?php echo site_url('admin/customers/send-bulk-sms'); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="msg">Message</label>
                                    <textarea id="msg" rows="5" type="text" class="form-control" name="message" required=""></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Send SMS</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php
                $customers = (new \App\Models\CustomerModel())->findAll();
                if($customers && count($customers) > 0) {
                    ?>
                    <div class="table-responsive longer" style="padding-bottom: 80px">
                        <table class="table table-hover display" id="datatable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Phone No.</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $n_ = 0;
                                foreach($customers as $customer) {
                                    $n_++;
                                    ?>
                                    <tr>
                                        <td><?php echo $n_; ?></td>
                                        <td><?php echo $customer->getFullName(); ?></td>
                                        <td><?php echo $customer->phone; ?></td>
                                        <td><?php echo $customer->status == 1 ? '<div class="text-success">Active</div>' : '<div class="text-danger">Inactive</div>'; ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Actions <span class="caret"></span> </button>
                                                <ul class="dropdown-menu">
                                                    <?php
                                                    if($customer->status == 1) {
                                                        ?>
                                                        <li><a class="send-to-server-click" data="id:<?php echo $customer->id; ?>|status:1" url="<?php echo site_url('admin/customers/deactivate/'.$customer->id); ?>" warning-title="Are you sure?" warning-message="This customer will NOT receive any SMS from this application" warning-button="Continue" loader="true"><i class="mdi mdi-information"></i> Deactivate</a></li>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <li><a class="send-to-server-click" data="id:<?php echo $customer->id; ?>|status:1" url="<?php echo site_url('admin/customers/activate/'.$customer->id); ?>" warning-title="Are you sure?" warning-message="This customer will receive SMS notifications from this app" warning-button="Continue" loader="true"><i class="mdi mdi-information"></i> Activate</a></li>
                                                        <?php
                                                    }
                                                    ?>
                                                    <li><a data-toggle="modal" data-target="#sendSMS_<?php echo $customer->id; ?>"><i class="mdi mdi-send"></i> Send SMS</a></li>
                                                </ul>
                                            </div>
                                            <div class="modal fade" id="sendSMS_<?php echo $customer->id; ?>" role="dialog">
                                                <div class="modal-dialog modal-sm">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Send SMS</h4>
                                                        </div>
                                                        <form  class="simcy-form" action="<?php echo site_url('admin/customers/send-sms'); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="phone" value="<?php echo '+'.$customer->phone; ?>"/>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="msg">Message</label>
                                                                    <textarea id="msg" rows="5" type="text" class="form-control" name="message" required=""></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Send SMS</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
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