<?php
class TVC_Scripts {
  public function __construct() {
    add_action('wp_enqueue_scripts', [$this, 'enqueue']);
  }
  public function enqueue() {
    wp_enqueue_style('tvc-style', plugin_dir_url(__FILE__).'../assets/css/style.css');
    wp_enqueue_script('tvc-carousel', plugin_dir_url(__FILE__).'../assets/js/carousel.js', ['jquery'], null, true);
  }
}
new TVC_Scripts();
