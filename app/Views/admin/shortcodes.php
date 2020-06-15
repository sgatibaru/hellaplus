<?php
if($shortcodes && count($shortcodes) > 0) {
    ?>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Shortcode</th>
                <th>Name</th>
                <th>Type</th>
                <th>API Environment</th>
                <th>API Setup</th>
                <th>Actions</th>
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
                    <td><?php echo $shortcode->env; ?></td>
                    <td><?php echo (($shortcode->api_setup == 1 && $shortcode->type == 'C2B') || $shortcode->type == 'B2C') ? '<div class="text-success">YES</div>' : '<div class="text-danger">NO</div>'; ?></td>
                    <td>
                        <a role="menuitem" class="send-to-server-click text-danger" style="cursor: pointer"
                           data="id:<?php echo $shortcode->id; ?>|status:1"
                           url="<?php echo site_url('admin/paybill/delete/' . $shortcode->id); ?>"
                           warning-title="Delete Shortcode"
                           warning-message="You are about to delete <?php echo $shortcode->shortcode; ?> and all of its transactions"
                           warning-button="Continue" loader="true"> <i
                                    class="mdi mdi-delete"></i> Delete
                        </a>
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
    <div class="alert alert-warning">
        No shortcodes have been added yet
    </div>
    <?php
}