<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class TVC_Settings {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_menu() {
        add_submenu_page(
            'edit.php?post_type=trusted_vendor',
            'Carousel Settings',
            'Settings',
            'manage_options',
            'tvc-settings',
            [$this, 'settings_page']
        );
    }

    public function register_settings() {
        register_setting('tvc_settings_group', 'tvc_show_title');
        register_setting('tvc_settings_group', 'tvc_logo_width');
        register_setting('tvc_settings_group', 'tvc_logo_height');
        register_setting('tvc_settings_group', 'tvc_ticker_speed');

        // Restored alignment options
        register_setting('tvc_settings_group', 'tvc_alignment_horizontal');
        register_setting('tvc_settings_group', 'tvc_alignment_vertical');
    }

    public function settings_page() {
        $h = get_option('tvc_alignment_horizontal', 'center');
        $v = get_option('tvc_alignment_vertical', 'center');
        $speed = get_option('tvc_ticker_speed', 30);
        ?>
        <div class="wrap">
            <h1>Trusted Vendor Carousel Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('tvc_settings_group'); ?>
                <table class="form-table">
                    <tr><th>Show Vendor Title</th>
                        <td><input type="checkbox" name="tvc_show_title" value="1" <?php checked(1, get_option('tvc_show_title', 1)); ?> />
                        <label>Display vendor name below logo</label></td>
                    </tr>
                    <tr><th>Custom Image Size</th>
                        <td><input type="number" name="tvc_logo_width" value="<?php echo esc_attr(get_option('tvc_logo_width', 120)); ?>" min="30" max="600"> px width ×
                            <input type="number" name="tvc_logo_height" value="<?php echo esc_attr(get_option('tvc_logo_height', 80)); ?>" min="30" max="400"> px height</td>
                    </tr>
                    <tr><th>Horizontal Alignment</th>
                        <td>
                            <select name="tvc_alignment_horizontal">
                                <option value="left"   <?php selected($h, 'left'); ?>>Left</option>
                                <option value="center" <?php selected($h, 'center'); ?>>Center</option>
                                <option value="right"  <?php selected($h, 'right'); ?>>Right</option>
                            </select>
                        </td>
                    </tr>
                    <tr><th>Vertical Alignment</th>
                        <td>
                            <select name="tvc_alignment_vertical">
                                <option value="top"    <?php selected($v, 'top'); ?>>Top</option>
                                <option value="center" <?php selected($v, 'center'); ?>>Middle</option>
                                <option value="bottom" <?php selected($v, 'bottom'); ?>>Bottom</option>
                            </select>
                        </td>
                    </tr>
                    <tr><th>Ticker Speed</th>
                        <td>
                            <input type="range" min="5" max="100" step="1" name="tvc_ticker_speed" value="<?php echo esc_attr($speed); ?>" oninput="this.nextElementSibling.value = this.value">
                            <output><?php echo esc_attr($speed); ?></output> seconds
                            <p class="description">Lower = faster scrolling speed.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
new TVC_Settings();
