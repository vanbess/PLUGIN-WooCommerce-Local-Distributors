<?php

// register admin page
add_action('admin_menu', 'sbwc_loc_dist_admin');

function sbwc_loc_dist_admin()
{
    add_menu_page(
        __('Local Distributors & Retailers', 'woocommerce'),
        __('Local Distributors', 'woocommerce'),
        'manage_options',
        'sbwc-local-dist',
        'sbwc_local_dist_render',
        'dashicons-randomize',
        20
    );
}

// render admin page
function sbwc_local_dist_render()
{
    global $title;
?>

    <div id="sbwc_local_dist_settings_cont">

        <h2><?php echo $title; ?></h2>

        <?php echo 'blah'; ?>

    </div>

<?php }
