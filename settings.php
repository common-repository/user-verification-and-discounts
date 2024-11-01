<?php

// Enqueue non PHP files for use on settings page
function uvd_plugin_enqueue_files()
{
    wp_enqueue_style('my-style', plugins_url('/css/custom.css', __FILE__), false, '1.0', 'all'); // Inside a plugin
    wp_enqueue_script('my-script', plugins_url('/js/script.js', __FILE__), array('jquery'), '1.0.0', true);

}

add_action('admin_init', 'uvd_plugin_enqueue_files');

// Define the settings section and fields
function uvd_plugin_settings_init()
{

    // Register a new setting for "settings" page
    register_setting('user-verification-and-discounts-settings', 'uvd-plugin-settings', 'uvd_plugin_settings_sanitize');

    // Register the Verification App settings section
    add_settings_section(
        'uvd_plugin_authentication_section',
        'Verification Service Authentication',
        'uvd_plugin_authentication_section_callback',
        'user-verification-and-discounts-settings'
    );

    // Register a settings field for API key
    add_settings_field(
        'uvd_plugin_authentication_text_field',
        'Proxi.id API Key:',
        'uvd_plugin_api_key_text_field_callback',
        'user-verification-and-discounts-settings',
        'uvd_plugin_authentication_section'
    );

    // Register a settings field WooCommerce button
    add_settings_field(
        'uvd_plugin_wc_authentication_text_field',
        'WooCommerce:',
        'uvd_plugin_wc_auth_callback',
        'user-verification-and-discounts-settings',
        'uvd_plugin_authentication_section'
    );

    // Register the Verification App settings section
    add_settings_section(
        'uvd_plugin_section',
        'Configure Discount Settings',
        'uvd_plugin_section_callback',
        'user-verification-and-discounts-settings'
    );

    add_settings_field(
        'render_radio_button',
        'Discount Configuration:',
        'uvd_plugin_configuration_radio_button_callback',
        'user-verification-and-discounts-settings',
        'uvd_plugin_section'
    );

    // Register a settings field for discount value
    add_settings_field(
        'uvd_plugin_discount_value_field',
        'Discount Value:',
        'uvd_plugin_discount_value_field_callback',
        'user-verification-and-discounts-settings',
        'uvd_plugin_section',
        array('class' => 'hidden')
    );

    //Register a settings field for organization type drop down
    add_settings_field(
        'uvd_plugin_discount_type_drop_down',
        'Discount Type:',
        'uvd_plugin_discount_type_drop_down_callback',
        'user-verification-and-discounts-settings',
        'uvd_plugin_section',
        array('class' => 'hidden')
    );

    //Register a settings field for the category select dropdown
    add_settings_field(
        'uvd_plugin_category_select_drop_down',
        'Discount Category:',
        'uvd_plugin_category_select_drop_down_callback',
        'user-verification-and-discounts-settings',
        'uvd_plugin_section',
        array('class' => 'hidden')
    );

    // Register a settings field for button text
    add_settings_field(
        'uvd_plugin_button_text_field',
        'Button Text:',
        'uvd_plugin_button_text_field_callback',
        'user-verification-and-discounts-settings',
        'uvd_plugin_section',
        array('class' => 'hidden')
    );

    // Register a settings field for button text
    add_settings_field(
        'uvd_plugin_button_pretext_field',
        'Button Pre-Text:',
        'uvd_plugin_button_pretext_field_callback',
        'user-verification-and-discounts-settings',
        'uvd_plugin_section',
        array('class' => 'hidden')
    );

    // Register a settings field for notice link text
    add_settings_field(
        'uvd_plugin_notice_link_field',
        'Notice Link Text:',
        'uvd_plugin_notice_link_field_callback',
        'user-verification-and-discounts-settings',
        'uvd_plugin_section',
        array('class' => 'hidden')
    );

    // Register a settings field to display verification URL
    add_settings_field(
        'uvd_plugin_verification_url_field',
        'Optional Verification URL:',
        'uvd_plugin_verification_url_callback',
        'user-verification-and-discounts-settings',
        'uvd_plugin_section',
        array('class' => 'hidden')
    );

    // Register a settings field to disable Proxi generated user interfaces
    add_settings_field(
        'uvd_plugin_disable_ui_checkbox_field',
        'Plugin Buttons:',
        'uvd_plugin_disable_ui_checkbox_callback',
        'user-verification-and-discounts-settings',
        'uvd_plugin_section',
        array('class' => 'hidden')
    );

}

// Register uvd_plugin_settings_init to the admin_init action hook
add_action('admin_init', 'uvd_plugin_settings_init');

// Function to sanitize user input before added to the database
function uvd_plugin_settings_sanitize($input)
{
    // Sanitize each individual field in the input array
    foreach ($input as $key => $value) {

        switch ($key) {
            case 'config_setting':
                $input[$key] = sanitize_text_field($value);
                break;
            case 'api_key':
                $input[$key] = sanitize_text_field($value);
                break;
            case 'discount_value':
                $input[$key] = sanitize_text_field($value);
                break;
            case 'discount_type':
                $input[$key] = sanitize_text_field($value);
                break;
            case 'discount_category':
                $input[$key] = sanitize_text_field($value);
                break;
            case 'button_text':
                $input[$key] = sanitize_text_field($value);
                break;
            case 'button_pretext':
                $input[$key] = sanitize_text_field($value);
                break;
            case 'notice_link_text':
                $input[$key] = sanitize_text_field($value);
                break;
            // Add cases for other fields if needed
        }
    }

    // Return the sanitized input
    return $input;
}

// Callback function for the settings section
function uvd_plugin_authentication_section_callback()
{
    echo '<p>' . esc_html__('To proceed, please enter your verification service API key and authorize our service to use your WooCommerce installation.', 'user-verification-and-discounts') . '</p>';
}

// Callback function for the API key text input field
function uvd_plugin_api_key_text_field_callback()
{
    $value = get_option('uvd-plugin-settings');
    $api_key = isset($value['api_key']) ? sanitize_text_field($value['api_key']) : '';
    echo '<input type="text" pattern="[a-zA-Z0-9-]+" maxlength="50" id="api_key" size="50" name="uvd-plugin-settings[api_key]" value="' . esc_attr($api_key) . '" required />';
    echo '<p class="description">' . esc_html__('Enter the verification service API key that was provided to you. If you do not have an API key, you can get one from', 'text-domain') . ' <a href="' . esc_url('https://www.proxi.id/pricing/') . '" target="_blank">www.proxi.id</a>.</p>';
}

// Function to display the WooCommerce authentication text and button
function uvd_plugin_wc_auth_callback()
{

    $settings = get_option('uvd-plugin-settings');
    $plugin_slug = 'user-verification-and-discounts';
    $response_code = $settings['response_code'];
    //var_dump($response_code);
    $store_url = get_site_url();
    $store_cart_url = wc_get_cart_url();
    $user_id = $settings['api_key'] . '@' . $store_cart_url;
    $endpoint = '/wc-auth/v1/authorize';
    $params = [
        'app_name' => 'User Verification and Discounts',
        'scope' => 'write',
        'user_id' => $user_id,
        'return_url' => $store_url . '/wp-admin/admin.php?page=' . $plugin_slug,
        'callback_url' => 'https://federation.proxi.id/woo/api/v1.0/register-store/',
    ];
    $query_string = http_build_query($params);
    $register_proxi_api_request = $store_url . $endpoint . '?' . $query_string;

    if ($response_code == 401 || $response_code == 0) {
        $disabled = true;
        $auth_url_param = '';
    } else {
        $disabled = false;
        $auth_url_param = $register_proxi_api_request;
    }

    echo '<a class="a button-primary" target="_blank" href="' . esc_url($auth_url_param) . '" ' . disabled($disabled, true, false) . '>' . esc_html__('Authorize', 'user-verification-and-discounts') . '</a><p class="description">' . esc_html__('You will need to give this plugin admin access to your WooCommerce instance in order to be able to apply discounts.', 'user-verification-and-discounts') . '</p>';
}

// Callback function for the settings section
function uvd_plugin_section_callback()
{
    echo '<p>' . esc_html__('Please configure your discount options. You can enable student discounts for a users entire shopping cart or define products under a product category that a discount can be applied to.', 'user-verification-and-discounts') . '</p>';
}

function uvd_plugin_configuration_radio_button_callback()
{
    $options = get_option('uvd-plugin-settings');
    $disabled = empty($options['button_url']);
    ?>
    <input type="radio" name="uvd-plugin-settings[config_setting]" value="disabled" <?php checked($options['config_setting'], 'disabled'); ?>>
    <?php echo esc_html__('Disabled', 'user-verification-and-discounts') ?><br>
    <input type="radio" name="uvd-plugin-settings[config_setting]" value="cart" <?php checked($options['config_setting'], 'cart');
    disabled($disabled); ?>>
    <?php echo esc_html__('Enable Cart Discounts', 'user-verification-and-discounts') ?><br>
    <input type="radio" name="uvd-plugin-settings[config_setting]" value="category" <?php checked($options['config_setting'], 'category');
    disabled($disabled); ?>>
    <?php echo esc_html__('Enable Category Discounts', 'user-verification-and-discounts') ?><br>
    <?php
}

// Callback function for the discount value text input field
function uvd_plugin_discount_value_field_callback()
{
    $value = get_option('uvd-plugin-settings');
    $discount_value = isset($value['discount_value']) ? sanitize_text_field($value['discount_value']) : '';

    echo '<input type="text" pattern="[0-9]+" maxlength="3" size="5" class="standard_setting" id="discount" name="uvd-plugin-settings[discount_value]" value="' . esc_attr($discount_value) . '" required />';
    echo '<p class="description">' . esc_html__('Enter a numeric value to define how much of a discount you want to give students.', 'user-verification-and-discounts') . '</p>';
}


// Callback function for the discount value text input field
function uvd_plugin_discount_type_drop_down_callback()
{
    $value = get_option('uvd-plugin-settings');
    $setting = isset($value['discount_type']) ? $value['discount_type'] : '';
    $options = array(
        'percent' => __('Percent (%)', 'user-verification-and-discounts'),
        'fixed_cart' => __('Dollar Amount ($)', 'user-verification-and-discounts'),
    );

    echo '<select class="standard_setting" name="uvd-plugin-settings[discount_type]">';
    foreach ($options as $option_value => $option_label) {
        echo '<option value="' . esc_attr($option_value) . '" ' . selected($setting, $option_value, false) . '>' . esc_html($option_label) . '</option>';
    }
    echo '</select>';
    echo '<p class="description">' . esc_html__('Select whether you would like to provide users with a dollar or percentage based discount.', 'user-verification-and-discounts') . '</p>';
}


// Callback function for the button text input field
function uvd_plugin_button_text_field_callback()
{
    $value = get_option('uvd-plugin-settings');
    $button_text = isset($value['button_text']) ? sanitize_text_field($value['button_text']) : '';
    echo '<input type="text" maxlength="30" id="button_text" class="cart_setting" name="uvd-plugin-settings[button_text]" value="' . esc_attr($button_text) . '" />';
    echo '<p class="description">' . esc_html__('Enter the text you would like to appear on the buttons that students click to start verification.', 'user-verification-and-discounts') . '</p>';
}

// Callback function for the button text input field
function uvd_plugin_button_pretext_field_callback()
{
    $value = get_option('uvd-plugin-settings');
    $button_pretext = isset($value['button_pretext']) ? sanitize_text_field($value['button_pretext']) : '';
    echo '<input type="text" maxlength="60" id="button_pretext" class="cart_setting" size="50" name="uvd-plugin-settings[button_pretext]" value="' . esc_attr($button_pretext) . '" />';
    echo '<p class="description">' . esc_html__('Enter the text you would like to appear above the verification button. This can be used to provide context around the discount or what users need to do.', 'user-verification-and-discounts') . '</p>';
}

// Callback function for the notice link text input field
function uvd_plugin_notice_link_field_callback()
{
    $value = get_option('uvd-plugin-settings');
    $notice_link_text = isset($value['notice_link_text']) ? sanitize_text_field($value['notice_link_text']) : '';
    echo '<input type="text" id="notice_link_text" class="category_setting" size="50" name="uvd-plugin-settings[notice_link_text]" value="' . esc_attr($notice_link_text) . '" />';
    echo '<p class="description">' . esc_html__('Enter the text you would like to appear in the notification that appears at the top of the cart page. This can be used to provide context around the discount or what users need to do.', 'user-verification-and-discounts') . '</p>';
}

// Callback function for the setting showing the verification url
function uvd_plugin_verification_url_callback()
{

    $value = get_option('uvd-plugin-settings');
    echo '<input type="text" size="90" readonly class="standard_setting" id="discount" name="uvd-plugin-settings[button_url]" value="' . esc_url($value['button_url']) . '" />';
    echo '<p class="description">' . esc_html__('You can use the URL above to create your own buttons or blocks to verify users for your discount.', 'user-verification-and-discounts') . '</p>';
}

// Callback function for the category select drop down
function uvd_plugin_category_select_drop_down_callback()
{
    $setting = get_option('uvd-plugin-settings');

    $categories = get_terms(
        'product_cat',
        array(
            'hide_empty' => false,
        )
    );

    $options = array();
    foreach ($categories as $category) {
        $options[$category->term_id] = $category->name;
    }

    $options = array('' => __('Disabled', 'user-verification-and-discounts')) + $options;

    echo '<select class="category_setting" name="uvd-plugin-settings[discount_category]">';
    foreach ($options as $value => $label) {
        printf(
            '<option value="%s" %s>%s</option>',
            esc_attr($value),
            selected($setting['discount_category'], $value, false),
            esc_html($label)
        );
    }
    echo '</select>';
    echo '<p class="description">' . esc_html__('Select which product category you would like to apply discounts to. If using category wide discounts, we recommend creating a new category like "Student Discount".', 'user-verification-and-discounts') . '</p>';

}

// Callback function for the checkbox to disable the showing of Proxi UI elements
function uvd_plugin_disable_ui_checkbox_callback()
{
    $options = get_option('uvd-plugin-settings');

    $ui_elements_checkbox = isset($options['ui_elements_checkbox']) ? $options['ui_elements_checkbox'] : 'false';
    ?>
    <input type='checkbox' class="standard_setting" name='uvd-plugin-settings[ui_elements_checkbox]' <?php checked($ui_elements_checkbox, 'true'); ?> value='true'>
    <label>
        <?php echo esc_html__('Enabled', 'user-verification-and-discounts') ?>
    </label>
    <p class="description">
        <?php echo esc_html__('Uncheck this to disable default user interface elements if you want to use your own.', 'user-verification-and-discounts') ?>
    </p>
    <?php
}

// Add the settings page to the WordPress admin menu
function uvd_plugin_add_settings_page()
{
    add_options_page(
        'User Verification & Discounts',
        'User Verification & Discounts',
        'manage_options',
        'user-verification-and-discounts',
        'uvd_plugin_page'
    );
}

// Add the necessary action hooks
add_action('admin_menu', 'uvd_plugin_add_settings_page');

// Define the settings page itself
function uvd_plugin_page()
{
    ?>
    <div class="wrap">
        <h1>
            <?php echo esc_html(get_admin_page_title()); ?>
        </h1>
        <hr />
        <?php
        if (!class_exists('WooCommerce')) {
            $class = 'notice notice-error';
            $message = __('Proxi ID Verification requires WooCommerce to be installed and active. You can download <a target="_blank" href="https://www.woocommerce.com">WooCommerce</a> here.');

            printf('<div class="%1$s"><p><strong>%2$s</strong></p></div>', esc_attr($class), ($message));

        } else {
            ?>
            <form action="options.php" method="post">
                <?php uvd_plugin_get_url(); ?>
                <?php settings_fields('user-verification-and-discounts-settings'); ?>
                <?php do_settings_sections('user-verification-and-discounts-settings'); ?>
                <?php submit_button('Save Settings'); ?>
            </form>
            <?php
        }
        ?>
        <p>Thank you for using our verification service! If you have any questions or would like to leave feedback <a
                target="_blank" href='https://federation.proxi.id/self-serve/merchant-support'>click here</a>.</p>
    </div>
    <?php
}

// Get the merchants current configuration in Proxi ID system
function uvd_plugin_get_url()
{

    $product_categories = [];
    $settings = get_option('uvd-plugin-settings');
    //var_dump($settings);
    $amount = $settings['discount_value'];
    $discount_type = $settings['discount_type'];
    $discount_category = $settings['discount_category'];
    $config = $settings['config_setting'];
    $store_url = 'woo-proxi-academic';

    if ($discount_category !== '') {
        $product_categories = [(int) $discount_category];
    }

    if ($config !== 'category') {
        $product_categories = [];
    }

    if ($config == 'category' && $discount_type !== 'percent') {
        $discount_type = 'fixed_product';
    }

    if ($config === 'disabled') {
        $amount = 0;
    }

    if ($config == 'category' && $discount_category == '') {
        $amount = 0;
    }

    $url = "https://federation.proxi.id/woo/api/v1.0/campaign/" . $store_url;

    $payload = [
        "categories" => [
            "college",
            "university",
            "vocational school",
        ],
        "person_affiliation" => [
            "member"
        ],
        "woocommerce_coupon" => [
            "amount" => $amount,
            "discount_type" => $discount_type,
            "product_categories" => $product_categories
        ],
    ];

    // Arguments for the wp_remote_get() function
    $args = array(
        'timeout' => 10,
        // Request timeout in seconds
        'headers' => array("Authorization" => "Token " . $settings['api_key']),
        'body' => json_encode($payload)
    );


    // Debug payload being sent to Proxi API
    //var_dump($payload);

    // Perform the remote GET request
    $response = wp_remote_post($url, $args);

    $settings['response_code'] = wp_remote_retrieve_response_code($response);

    update_option('uvd-plugin-settings', $settings);

    // Check if the request was successful
    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
        $body = wp_remote_retrieve_body($response); // Get the response body
        // Process the response data
        // ...

        // Debug response from Proxi API
        //var_dump($body);

        $jsonArray = json_decode($body, true);

        $settings['button_url'] = $jsonArray['campaign_url'] ?? null;

        update_option('uvd-plugin-settings', $settings);

    } elseif (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 401 && ($settings['api_key']) !== '') {
        $class = 'notice notice-error';
        $message = __('The API key you have entered is incorrect. Please try again or contact us for assistance.', 'user-verification-and-discounts');

        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));

        $settings['config_setting'] = 'disabled';
        $settings['button_url'] = null;
        update_option('uvd-plugin-settings', $settings);

    } elseif (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 404) {
        $class = 'notice notice-error';
        $message = __('Please authorize our service to use your stores instance of WooCommerce.', 'user-verification-and-discounts');

        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));

        $settings['config_setting'] = 'disabled';
        $settings['button_url'] = null;
        update_option('uvd-plugin-settings', $settings);
    } else {
        $error_message = is_wp_error($response) ? $response->get_error_message() : 'Request failed.';

        $settings['config_setting'] = 'disabled';
        $settings['button_url'] = null;
        update_option('uvd-plugin-settings', $settings);
        // Handle the error
        // ...

        // var_dump($error_message);

    }


    if ($settings['api_key'] == '') {
        $class = 'notice notice-warning';
        $message = __('A verification service API key is required to use this plugin.', 'user-verification-and-discounts');

        printf('<div class="%1$s"><p>%2$s</p><p><a href="https://www.proxi.id/pricing">Click here to learn more.</a></p></div>', esc_attr($class), esc_html($message));
    }
}

// Add settings link
function uvd_plugin_add_settings_link($links)
{
    $settings_link = '<a href="' . admin_url('admin.php?page=user-verification-and-discounts') . '">' . esc_html__('Settings', 'user-verification-and-discounts') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_user-verification-and-discounts/user-verification-and-discounts.php', 'uvd_plugin_add_settings_link');

/*
 *
 * Code below is not currently being used by plugin, but may be used in the future
 *
 */

// Callback function for the organization type drop down field
function uvd_plugin_eligible_institutions_checkbox_callback()
{
    $options = get_option('uvd-plugin-settings', []);

    $institution_checkbox = isset($options['institution_checkbox'])
        ? (array) $options['institution_checkbox'] : [];
    ?>
    <input type='checkbox' name='uvd-plugin-settings[institution_checkbox][]' <?php checked(in_array('higher-ed', $institution_checkbox), 1); ?> value='higher-ed'>
    <label>
        <?php esc_html__('Higher Education (Colleges & Universities)', 'user-verification-and-discounts') ?>
    </label><br>
    <input type='checkbox' name='uvd-plugin-settings[institution_checkbox][]' <?php checked(in_array('k12', $institution_checkbox), 1); ?> value='k12'>
    <label>
        <?php esc_html__('K-12 (Primary and Lower Secondary Schools)', 'user-verification-and-discounts') ?>
    </label><br>
    <input type='checkbox' name='uvd-plugin-settings[institution_checkbox][]' <?php checked(in_array('vocational', $institution_checkbox), 1); ?> value='vocational school'>
    <label>
        <?php esc_html__('Vocational Schools (Technical & Professional Development)', 'user-verification-and-discounts') ?>
    </label>
    <?php
}

// Callback function for the eligible regions checkboxes
function uvd_plugin_check_box_callback()
{
    $options = get_option('uvd-plugin-settings', []);

    $region_checkbox = isset($options['region_checkbox'])
        ? (array) $options['region_checkbox'] : [];
    ?>
    <input type='checkbox' name='uvd-plugin-settings[region_checkbox][]' <?php checked(in_array('na', $region_checkbox), 1); ?> value='na'>
    <label>
        <?php esc_html__('America', 'user-verification-and-discounts') ?>
    </label><br>
    <input type='checkbox' name='uvd-plugin-settings[region_checkbox][]' <?php checked(in_array('eu', $region_checkbox), 1); ?> value='eu'>
    <label>
        <?php esc_html__('Europe', 'user-verification-and-discounts') ?>
    </label><br>
    <input type='checkbox' name='uvd-plugin-settings[region_checkbox][]' <?php checked(in_array('apac', $region_checkbox), 1); ?> value='apac'>
    <label>
        <?php esc_html__('Asia & Pacific', 'user-verification-and-discounts') ?>
    </label>
    <?php
}