<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="pull-right">
                    <button type="button" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#sendSMSes"><i class="mdi mdi-send"></i> Send SMS</button>
                    <button type="button" class="btn btn-info btn-sm"  data-toggle="modal" data-target="#newCustomer"><i class="mdi mdi-plus"></i> New Customer</button>
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
            <div class="modal fade" id="newCustomer" role="dialog">
                <div class="modal-dialog modal-md">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">New Customer</h4>
                        </div>
                        <form  class="simcy-form" action="<?php echo site_url('admin/customers/add'); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="business" value="<?php echo is_object(active_business()) && isset(active_business()->id) ? active_business()->id : ''; ?>" />
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="msg">First Name</label>
                                    <input type="text" name="fname" class="form-control" >
                                </div>
                                <div class="form-group">
                                    <label for="msg">Middle Name</label>
                                    <input type="text" name="mname" class="form-control" >
                                </div>
                                <div class="form-group">
                                    <label for="msg">Last Name</label>
                                    <input type="text" name="lname" class="form-control" >
                                </div>
                                <div class="form-group">
                                    <label for="msg">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" required >
                                    <small class="text-warning">Phone Number should be in the format 2547********</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php
                $customers = (new \App\Models\CustomerModel())->whereIn('business', function () {
                    return (new \App\Models\BusinessModel())->select('id')->where('user', (new \App\Libraries\IonAuth())->getUserId());
                })->findAll();
                $business = active_business();
                if($customers && count($customers) > 0) {
                    ?>
                    <div class="table-responsive longer" style="padding-bottom: 80px">
                        <table class="table table-hover display" id="customers">
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
                                                    <li><a  style="cursor: pointer" data-toggle="modal" data-target="#sendSMS_<?php echo $customer->id; ?>"><i class="mdi mdi-send"></i> Send SMS</a></li>
                                                    <?php
                                                    if($business->type == 'B2C') {
                                                        ?> <li><a style="cursor: pointer" data-toggle="modal" data-target="#sendMoney_<?php echo $customer->id; ?>"><i class="mdi mdi-currency-usd"></i> Send Money</a></li>

                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if($customer->status == 1) {
                                                        ?>
                                                        <li><a style="cursor: pointer" class="send-to-server-click" data="id:<?php echo $customer->id; ?>|status:1" url="<?php echo site_url('admin/customers/deactivate/'.$customer->id); ?>" warning-title="Are you sure?" warning-message="This customer will NOT receive any SMS from this application" warning-button="Continue" loader="true"><i class="mdi mdi-information"></i> Deactivate</a></li>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <li><a style="cursor: pointer" class="send-to-server-click" data="id:<?php echo $customer->id; ?>|status:1" url="<?php echo site_url('admin/customers/activate/'.$customer->id); ?>" warning-title="Are you sure?" warning-message="This customer will receive SMS notifications from this app" warning-button="Continue" loader="true"><i class="mdi mdi-information"></i> Activate</a></li>
                                                        <?php
                                                    }
                                                    ?>
                                                    <li><a  style="cursor: pointer" data-toggle="modal" data-target="#editCustomer_<?php echo $customer->id; ?>"><i class="mdi mdi-account-edit"></i> Edit</a></li>
                                                    <li><a style="cursor: pointer" class="send-to-server-click text-danger" data="id:<?php echo $customer->id; ?>|status:1" url="<?php echo site_url('admin/customers/delete/'.$customer->id); ?>" warning-title="Are you sure?" warning-message="This customer will be deleted" warning-button="Continue" loader="true"><i class="mdi mdi-delete"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                            <?php
                                            if($business->type == 'B2C') {
                                                ?>
                                                <div class="modal fade" id="sendMoney_<?php echo $customer->id; ?>" role="dialog">
                                                    <div class="modal-dialog modal-md">
                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                            <form class="simcy-form" method="post" action="<?php echo site_url(route_to('admin.transactions.send_money')); ?>" data-parsley-validate="" loader="true">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Send Money</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>Transaction Type</label>
                                                                        <select class="form-control select2" name="command" required>
                                                                            <option>-- Please select --</option>
                                                                            <option value="SalaryPayment">Salary Payment</option>
                                                                            <option value="PromotionPayment">Promotion Payment</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Phone Number</label>
                                                                        <input type="text" class="form-control disabled" readonly="readonly" name="phone" min="0" value="<?php echo $customer->phone; ?>" placeholder="Phone Number"
                                                                               required/>
                                                                        <small><span class="text-danger"><i class="glyphicon glyphicon-warning-sign"></i> confirm this is the actual phone number. No in-app validation for this!</span></small>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Amount</label>
                                                                        <input type="number" class="form-control" name="amount" min="50" value="50" max="70000"
                                                                               placeholder="Amount to send" required/>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Send Request</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <div class="modal fade" id="editCustomer_<?php echo $customer->id; ?>" role="dialog">
                                                <div class="modal-dialog modal-md">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">New Customer</h4>
                                                        </div>
                                                        <form  class="simcy-form" action="<?php echo site_url('admin/customers/add'); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="id" value="<?php echo $customer->id; ?>">
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="msg">First Name</label>
                                                                    <input type="text" name="fname" value="<?php echo $customer->fname; ?>" class="form-control" >
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="msg">Middle Name</label>
                                                                    <input type="text" name="mname" value="<?php echo $customer->mname; ?>" class="form-control" >
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="msg">Last Name</label>
                                                                    <input type="text" name="lname" value="<?php echo $customer->lname; ?>" class="form-control" >
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="msg">Phone Number</label>
                                                                    <input type="text" name="phone" class="form-control" value="<?php echo $customer->phone; ?>" required >
                                                                    <small class="text-warning">Phone Number should be in the format 2547********</small>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Save</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
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