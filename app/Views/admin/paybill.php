<?php
$shortcode = active_business();
?>
<div class="row">
    <div class="col-md-3">
        <div class="settings-menu">
            <nav class="navbar">
                <ul class="nav navbar-nav">
                    <li class="active"><a data-toggle="tab" href="#api_settings" aria-expanded="true"><span><i class="mdi mdi-wrench"></i></span>  M-Pesa API Settings</a></li>
                    <li class=""><a data-toggle="tab" href="#sms_settings" aria-expanded="false"><span><i class="glyphicon glyphicon-cog"></i></span>  SMS API Settings</a></li>
                    <li class=""><a data-toggle="tab" href="#sms_templates" aria-expanded="false"><span><i class="glyphicon glyphicon-file"></i></span>  SMS Templates</a></li>
                 </ul>
            </nav>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body p-zero">
                <div class="tab-content settings">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="api_settings" class="tab-pane fade in active">
                                <h3>API Settings</h3>
                                <p class="text-muted text-thin">Update paybill information</p>
                                <form class="simcy-form" action="<?php echo site_url('admin/paybill/create'); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $shortcode->id; ?>" />
                                    <div class="">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Name</label>
                                                    <input type="text" class="form-control" name="name" value="<?php echo $shortcode->name; ?>" placeholder="Shortcode Name" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Shortcode (Paybill or Store Number)</label>
                                                    <input type="number" class="form-control" min="1" name="shortcode" value="<?php echo $shortcode->shortcode; ?>" placeholder="Shortcode Number" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Shortcode Type</label>
                                                    <select class="form-control select2" name="type" require="">
                                                        <option <?php echo $shortcode->type == 'C2B' ? 'selected' : ''; ?> value="C2B">Client to Business</option>
                                                        <option <?php echo $shortcode->type == 'B2C' ? 'selected' : ''; ?> value="B2C">Business to Client</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>API Consumer Key</label>
                                                    <input type="text" class="form-control" name="consumer_key" value="<?php echo $shortcode->consumer_key; ?>" placeholder="Consumer Key for the production App" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>API Consumer Secret</label>
                                                    <input type="text" class="form-control" name="consumer_secret" value="<?php echo $shortcode->consumer_secret; ?>" placeholder="Consumer Secret for the production App" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Initiator Username</label>
                                                    <input type="text" class="form-control" name="initiator_username" value="<?php echo $shortcode->initiator_username; ?>" placeholder="Initiator Username">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Initiator Password</label>
                                                    <input type="text" class="form-control" name="initiator_password" value="<?php echo $shortcode->initiator_password; ?>" placeholder="Initiator Password">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update Shortcode</button>
                                    </div>
                                </form>
                            </div>
                            <div id="sms_settings" class="tab-pane fade">
                                <h3>SMS Settings</h3>
                                <p class="text-muted text-thin">Update SMS information using the <a target="_blank" href="https://africastalking.com">Africa's Talking SMS API</a> </p>
                                <form class="simcy-form" action="<?php echo site_url('admin/settings/sms'); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $shortcode->id; ?>" />
                                    <div class="">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>SMS Username</label>
                                                    <input type="text" class="form-control" name="sms_username" value="<?php echo get_option('sms_username', get_parent_option('sms_api', 'sms_api_username', '')); ?>" placeholder="SMS Username" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>API Key</label>
                                                    <input type="text" class="form-control" name="sms_apikey" value="<?php echo get_option('sms_apikey', get_parent_option('sms_api', 'sms_api_apikey', '')); ?>" placeholder="API Key" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>API Sender ID <small>(Leave blank if you have not set up a Sender ID)</small></label>
                                                    <input type="text" class="form-control" min="1" name="sms_sender_id" value="<?php echo get_option('sms_sender_id', get_parent_option('sms_api', 'sms_api_sender_id', '')); ?>" placeholder="API Sender ID">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"><!--
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                            <div id="sms_templates" class="tab-pane fade">
                                <h3>SMS Templates</h3>
                                <p class="text-muted text-thin">If active, this text will be sent to customers after a payment is received. </p>
                                <form class="simcy-form" action="<?php echo site_url('admin/settings/sms-templates'); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $shortcode->id; ?>" />
                                    <div class="">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label><input type="checkbox" class="checkbox checkbox-inline" value="1" name="sms_active" <?php echo get_option('sms_active', get_parent_option('sms_api', 'sms_active', false)) == 1 ? 'checked' : ''; ?>>  Active</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>SMS Template</label>
                                                    <textarea rows="5" type="text" class="form-control" name="sms_template" required=""><?php echo get_option('sms_template', get_parent_option('sms_api', 'sms_template', FALSE)); ?></textarea>
                                                    <p>
                                                        <b>Some Placeholders include:</b><br/>
                                                        <code>{TransID}, {TransTime}, {MSISDN}, {TransAmount}, {BillRefNumber}, {FirstName}, {MiddleName}, {LastName}</code>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"><!--
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>