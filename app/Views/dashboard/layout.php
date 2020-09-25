<?php


?>
<div style="margin-bottom: 1em">
    <ul class="nav nav-pills">
        <li role="presentation"><a href="<?php echo site_url(route_to('dashboard.index')); ?>">Dashboard</a></li>
        <li role="presentation"><a href="<?php echo site_url(route_to('dashboard.clients')); ?>">Clients</a></li>
        <li role="presentation"><a href="<?php echo site_url(route_to('dashboard.shortcodes')); ?>">Shortcodes</a></li>
        <li role="presentation"><a href="<?php echo site_url(route_to('dashboard.settings')); ?>">Settings</a></li>
    </ul>
</div>

<div>
    <?php
    echo isset($the_content) ? $the_content : '';
    ?>
</div>
