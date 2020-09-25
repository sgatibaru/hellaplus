<?php


?>
<div class="">
    <div class="card">
        <div class="card-header">
            <h4>Settings</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="settings-menu">
                        <nav class="navbar">
                            <ul class="nav navbar-nav">
                                <li class="active"><a data-toggle="tab" href="#system_settings" aria-expanded="true"><span><i class="mdi mdi-wrench"></i></span> System Settings</a></li>
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
                                        <div id="system_settings" class="tab-pane fade in active">
                                            <h3>System Settings</h3>
                                            <hr/>
                                            <form class="simcy-form" action="<?php echo current_url(); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data" novalidate="">
                                                <input type="hidden" name="id" value="6">
                                                <div class="" style="margin-bottom: 2em">
                                                    <div class="form-group">
                                                        <label>Application Name</label>
                                                        <input type="text" class="form-control" name="site_name" value="<?php echo get_option('site_name'); ?>" required />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Application Logo</label>
                                                        <input type="file" class="form-control" name="logo" accept="image/*" />
                                                    </div>
                                                    <hr/>
                                                    <h3>User Registration</h3>
                                                    <br/>
                                                    <div class="form-group">
                                                        <label><input type="checkbox" name="allow_registration" <?php echo get_option('allow_registration', 1) == 1 ? 'checked' : '' ?> value="1" /> Allow user and clients registration </label>
                                                    </div>
                                                </div>
                                                <div>
                                                    <button type="submit" class="btn btn-primary">Save Settings</button>
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
        </div>
    </div>
</div>
