<?php
class TVC_Post_Type {
  public function __construct() {
    add_action('init', [$this, 'register_cpt']);
    add_action('add_meta_boxes', [$this, 'add_meta_box']);
    add_action('save_post', [$this, 'save_serial']);
  }

  public function register_cpt() {
    register_post_type('trusted_vendor', [
      'label' => 'Trusted Vendors',
      'public' => true,
      'menu_icon' => 'dashicons-groups',
      'supports' => ['title','thumbnail'],
      'show_in_rest' => true,
    ]);
  }

  public function add_meta_box() {
    add_meta_box('vendor_serial', 'Serial Number', [$this, 'render_serial_box'], 'trusted_vendor', 'side');
  }

  public function render_serial_box($post) {
    $value = get_post_meta($post->ID, '_tvc_serial', true);
    echo '<input type="number" name="tvc_serial" value="'.esc_attr($value).'" style="width:100%;">';
  }

  public function save_serial($post_id) {
    if(isset($_POST['tvc_serial']))
      update_post_meta($post_id, '_tvc_serial', intval($_POST['tvc_serial']));
  }
}
new TVC_Post_Type();
