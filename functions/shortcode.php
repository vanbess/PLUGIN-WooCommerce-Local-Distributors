<?php

/**
 * Shortcode to display distributors on front-end
 */

add_shortcode('sbwc_loc_dist_sc', 'sbwc_loc_dist_display');

/**
 * Function to display shortcode contents
 *
 * @return void
 */
function sbwc_loc_dist_display()
{

    wp_enqueue_style('sbwc-dist-jq-ui-css');

    // retrieve distributor posts
    $distribs = new WP_Query([
        'post_type'      => 'distributor',
        'post_status'    => 'publish',
        'posts_per_page' => -1
    ]);

    // if query fails, return error
    if (is_wp_error($distribs)) :
        return '<p>' . __('No distributors found, or failed to load distributors', 'default') . '</p>';
    endif;

    // setup post_id->country array
    $pid_country_arr = [];

    if ($distribs->have_posts()) :
        while ($distribs->have_posts()) :
            $distribs->the_post();

            // retrieve distributor country
            $country = get_post_meta(get_the_ID(), 'country', true);

            // push country code and associated dist ids to $pid_country_arr
            $pid_country_arr[$country][] = get_the_ID();

        endwhile;
    endif;

    // turn on output buffering so that our content displays in correct order
    ob_start();

    // retrieve countries list and provinces/states list
    $geo_data = new WC_Countries();
    $c_list = $geo_data->get_countries();
    $p_list = $geo_data->get_states();

?>

    <script>
        $ = jQuery;

        $(function() {

            // show first accordion link content by default
            $('div.sbwc-dist-accordion-container:nth-child(1)>div:nth-child(2)').show().parent().find('.sbwc-dist-accordion-oc').text('-');

            // show accordion content on title click; hide all other accordion content
            $('.sbwc-dist-accordion-title').on('click', function() {

                var visible = $(this).parent().find('.sbwc-dist-accordion-content').is(":visible");

                if (visible === true) {
                    $('.sbwc-dist-accordion-content').slideUp();
                    $(this).find('.sbwc-dist-accordion-oc').text('+');
                } else {
                    $('.sbwc-dist-accordion-content').slideUp().parent().find('.sbwc-dist-accordion-oc').text('+');
                    $(this).parent().find('.sbwc-dist-accordion-content').slideDown();
                    $(this).find('.sbwc-dist-accordion-oc').text('-');
                }

                $(this).parent().scrollTop();

            });
        });
    </script>

    <div id="sbwc-dist-accordion">

        <?php foreach ($pid_country_arr as $country => $dist_ids) : ?>

            <div class="sbwc-dist-accordion-container">

                <h3 class="sbwc-dist-accordion-title"><?php echo $c_list[$country]; ?><span class="sbwc-dist-accordion-oc">+</span></h3>

                <div class="sbwc-dist-accordion-content" style="display: none;">
                    <?php foreach ($dist_ids as $did) :

                        // retrieve post meta
                        $addr_line_1 = get_post_meta($did, 'addr_line_1', true) ? get_post_meta($did, 'addr_line_1', true) : '-';
                        $addr_line_2 = get_post_meta($did, 'addr_line_2', true) ? get_post_meta($did, 'addr_line_2', true) : '-';
                        $province    = get_post_meta($did, 'province', true) ? get_post_meta($did, 'province', true) : '-';
                        $city        = get_post_meta($did, 'city', true) ? get_post_meta($did, 'city', true) : '-';
                        $tel         = get_post_meta($did, 'tel', true) ? get_post_meta($did, 'tel', true) : '-';
                        $email       = get_post_meta($did, 'email', true) ? get_post_meta($did, 'email', true) : '-';

                    ?>
                        <div class="sbwc-dist-data-table-cont">

                            <table class="sbwc-dist-data-table">

                                <!-- store name/title -->
                                <tr>
                                    <th><?php _e('Store/Distributor Name', 'sbwc-dists'); ?></th>
                                    <td><b><?php echo get_the_title($did); ?></b></td>
                                </tr>

                                <!-- address line 1 -->
                                <tr>
                                    <th><?php _e('Address', 'sbwc-dists'); ?></th>
                                    <td>
                                        <?php echo $addr_line_1; ?><br>
                                        <?php echo $addr_line_2 !== '-' ? $addr_line_2 . '<br>' : ''; ?>
                                        <?php echo $province !== '-' ? $p_list[$country][$province] . '<br>' : ''; ?>
                                        <?php echo $city; ?><br>
                                    </td>
                                </tr>

                                <!-- telephone -->
                                <tr>
                                    <th><?php _e('Tel', 'sbwc-dists'); ?></th>
                                    <td><?php echo $tel; ?></td>
                                </tr>

                                <!-- email address -->
                                <tr>
                                    <th><?php _e('Email', 'sbwc-dists'); ?></th>
                                    <td><?php echo $email; ?></td>
                                </tr>
                            </table>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php
    return ob_get_clean();
}

/**
 * Enqueue jQuery UI CSS
 */
add_action('wp_enqueue_scripts', 'sbwc_dist_reg_frontend_scripts');

/**
 * Register jQuery UI CSS
 *
 * @return void
 */
function sbwc_dist_reg_frontend_scripts()
{
    wp_register_style('sbwc-dist-jq-ui-css', sbwc_dist_jq_ui_css());
}

/**
 * jQuery UI CSS
 *
 * @return void
 */
function sbwc_dist_jq_ui_css()
{ ?>
    <style>
        .sbwc-dist-data-table th {
            width: 244px;
            vertical-align: top;
            background: lightgray;
            padding: 15px !important;
        }

        .sbwc-dist-data-table {
            border-right: 1px solid #ececec;
            border-top: 1px solid #ececec;
            border-left: 1px solid #ececec;
        }

        .sbwc-dist-data-table-cont {
            margin-bottom: 2.2em;
        }

        .sbwc-dist-data-table td {
            padding-left: 15px;
        }

        .sbwc-dist-accordion-title {
            background: var(--wp--preset--color--black);
            color: var(--wp--preset--color--white);
            font-weight: normal;
            font-size: 18px;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 2px;
        }

        div.sbwc-dist-accordion-container:nth-child(1)>div:nth-child(2) {
            display: block;
        }

        .sbwc-dist-accordion-content {
            padding-top: 1.6em;
        }

        .sbwc-dist-accordion-oc {
            float: right;
        }
    </style>
<?php }


?>