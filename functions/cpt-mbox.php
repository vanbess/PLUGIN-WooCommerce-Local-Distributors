<?php

// Add metabox to distributor post type
function sbwc_dist_cpt_register_mbox()
{
    add_meta_box('sbwc_dist_data', __('Distributor/Retailer Data', 'sbwc-dists'), 'sbwc_cpt_render_dist_data', 'distributor', 'normal', 'default', null);
}

add_action('add_meta_boxes', 'sbwc_dist_cpt_register_mbox');

// Render metabox
function sbwc_cpt_render_dist_data()
{
    global $post;
    $post_id = $post->ID;

    // retrieve WC country data
    $country_data = new WC_Countries();
    $countries = $country_data->get_countries();
    $provinces = $country_data->get_states();

    // retrieve post meta
    $logo        = get_post_meta($post_id, 'logo', true);
    $addr_line_1 = get_post_meta($post_id, 'addr_line_1', true);
    $addr_line_2 = get_post_meta($post_id, 'addr_line_2', true);
    $country     = get_post_meta($post_id, 'country', true);
    $province    = get_post_meta($post_id, 'province', true);
    $city        = get_post_meta($post_id, 'city', true);
    $tel         = get_post_meta($post_id, 'tel', true);
    $email       = get_post_meta($post_id, 'email', true);

?>

    <div id="sbwc_dist_data_mbox_cont">

        <!-- image/logo -->
        <div id="sbwc_dist_logo_cont">

            <p><b><i><?php _e('Distributor/Retailer logo', 'sbwc-dists'); ?></i></b></p>

            <?php if ($logo) : ?>
                <img src="<?php echo $logo; ?>" alt="">
            <?php endif; ?>


            <input type="file" name="logo">

        </div>

        <!-- address -->
        <div id="sbwc_dist_address_cont">

            <p><b><i><?php _e('Address data', 'sbwc-dists'); ?></i></b></p>

            <p>
                <!-- address line 1 -->
                <input type="text" name="addr_line_1" placeholder="<?php _e('address line 1', 'sbwc-dists'); ?>" value="<?php echo $addr_line_1; ?>">
            </p>

            <p>
                <!-- address line 2 -->
                <input type="text" name="addr_line_2" placeholder="<?php _e('address line 2', 'sbwc-dists'); ?>" value="<?php echo $addr_line_2; ?>">
            </p>

            <p>
                <!-- country select -->
                <select id="dist-country-select" name="country" data-provinces="<?php echo base64_encode(json_encode($provinces, JSON_FORCE_OBJECT)); ?>" data-country="<?php echo $country; ?>">

                    <option value=""><?php _e('select country...', 'sbwc-dists'); ?></option>

                    <?php foreach ($countries as $key => $name) : ?>
                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                    <?php endforeach; ?>

                </select>
            </p>

            <p id="country-cont" style="display: none;">

                <!-- province select -->
                <select name="province" id="dist-province-select" data-province="<?php echo $province; ?>">
                    <option value=""><?php _e('select province/state...', 'sbwc-dists'); ?></option>
                </select>

            </p>

            <p>
                <!-- city -->
                <input type="text" name="city" placeholder="<?php _e('city', 'sbwc-dists'); ?>" value="<?php echo $city; ?>">
            </p>

        </div>

        <!-- contact dets -->
        <div id="sbwc_dist_contact_dets_cont">

            <p><b><i><?php _e('Contact details', 'sbwc-dists'); ?></i></b></p>

            <p>
                <!-- tel -->
                <input type="tel" name="tel" placeholder="<?php _e('telephone number', 'sbwc-dists'); ?>" value="<?php echo $tel; ?>">
            </p>

            <p>
                <!-- email -->
                <input type="email" name="email" placeholder="<?php _e('email', 'sbwc-dists'); ?>" value="<?php echo $email; ?>">
            </p>

        </div>

    </div>

<?php

    // enqueue js and css
    wp_enqueue_script('sbwc-loc-dist-admin-js');
    wp_enqueue_style('sbwc-loc-dist-admin-css');
}

/**
 * Admin JS
 *
 * @return void
 */
function sbwc_loc_dist_admin_js()
{ ?>
    <script>
        $ = jQuery;

        $(document).ready(function() {

            // add file support to form
            $('#post').attr('enctype', 'multipart/form-data');

            // if post meta is present for dropdowns, set dropdown vals
            $('#dist-country-select').val($('#dist-country-select').data('country'));

            if ($('#dist-province-select').data('province')) {

                var country_code = $('#dist-country-select').val();
                var provinces = JSON.parse(atob($('#dist-country-select').data('provinces')));
                var prov_selected = provinces[country_code];

                $('#dist-province-select').empty().append('<option value=""><?php _e('select province/state', 'sbwc-dists') ?></option>');

                $.each(prov_selected, function(key, val) {
                    $('#dist-province-select').append('<option value="' + key + '">' + val + '</option>');
                });

                $('#country-cont').show();

                $('#dist-province-select').val($('#dist-province-select').data('province'));
            }

            // show/hide country provinces, if present, on country select change
            $('#dist-country-select').change(function(e) {
                e.preventDefault();

                var country_code = $(this).val();
                var provinces = JSON.parse(atob($(this).data('provinces')));
                var prov_selected = provinces[country_code];

                if (typeof(prov_selected) === 'undefined' || $.isEmptyObject(prov_selected) === true) {
                    $('#country-cont').hide();
                } else {
                    $('#dist-province-select').empty().append('<option value=""><?php _e('select province/state', 'sbwc-dists') ?></option>');
                    $.each(prov_selected, function(key, val) {
                        $('#dist-province-select').append('<option value="' + key + '">' + val + '</option>');
                    });
                    $('#country-cont').show();
                }

            });
        });
    </script>
<?php }

/**
 * Update/save post meta
 *
 * @param  int $post_id - ID of the distributor being updated
 * @return void
 */
function sbwc_dist_cpt_save_data($post_id)
{

    // file_put_contents(LOC_DIST_PATH . 'posted.txt', print_r($_POST, true));
    // file_put_contents(LOC_DIST_PATH . 'img.txt', print_r($_FILES, true));


    // check post type and bail if not distributer
    if (get_post_type($post_id) !== 'distributor') :
        return;
    endif;

    // upload logo file
    $target_dir_data = wp_upload_dir();
    $target_dir      = trailingslashit($target_dir_data['path']);
    $target_url      = trailingslashit($target_dir_data['url']);
    $target_file     = $target_dir . basename($_FILES["logo"]["name"]);
    $target_file_src = $target_url . $_FILES["logo"]["name"];

    $moved = move_uploaded_file($_FILES['logo']['tmp_name'], $target_file);

    if ($moved) :
        update_post_meta($post_id, 'logo', $target_file_src);
    elseif (!$moved) :
        update_post_meta($post_id, 'logo', $moved);
    endif;

    // save post meta
    update_post_meta($post_id, 'addr_line_1', $_POST['addr_line_1'] ? $_POST['addr_line_1'] : '');
    update_post_meta($post_id, 'addr_line_2', $_POST['addr_line_2'] ? $_POST['addr_line_2'] : '');
    update_post_meta($post_id, 'country', $_POST['country'] ? $_POST['country'] : '');
    update_post_meta($post_id, 'province', $_POST['province'] ? $_POST['province'] : '');
    update_post_meta($post_id, 'city', $_POST['city'] ? $_POST['city'] : '');
    update_post_meta($post_id, 'tel', $_POST['tel'] ? $_POST['tel'] : '');
    update_post_meta($post_id, 'email', $_POST['email'] ? $_POST['email'] : '');
}

add_action('save_post', 'sbwc_dist_cpt_save_data');

/**
 * Admin CSS
 *
 * @return void
 */
function sbwc_loc_dist_admin_css()
{ ?>
    <style>
        #sbwc_dist_data_mbox_cont input,
        #sbwc_dist_data_mbox_cont select {
            width: 350px;
        }

        #sbwc_dist_logo_cont {
            max-width: 317px;
            padding-top: 6px;
        }

        #sbwc_dist_logo_cont>img {
            width: 100%;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 3px;
        }
    </style>
<?php }

/**
 * Enqueue admin CSS and JS
 *
 * @return void
 */
function sbwc_loc_dist_admin_scripts()
{
    wp_register_script('sbwc-loc-dist-admin-js', sbwc_loc_dist_admin_js(), [], false, true);
    wp_register_style('sbwc-loc-dist-admin-css', sbwc_loc_dist_admin_css(), [], false);
}

add_action('admin_footer', 'sbwc_loc_dist_admin_scripts', 99);
