<?php
class TVC_Shortcode {
  public function __construct() {
    add_shortcode('trusted_vendor_carousel', [$this, 'render']);
  }

  public function render($atts) {
    $args = [
      'post_type'      => 'trusted_vendor',
      'posts_per_page' => -1,
      'meta_key'       => '_tvc_serial',
      'orderby'        => 'meta_value_num',
      'order'          => 'ASC',
    ];
    $query = new WP_Query($args);

    $show_title = get_option('tvc_show_title', 1);
    $width = get_option('tvc_logo_width', 120);
    $height = get_option('tvc_logo_height', 80);
    $speed = get_option('tvc_ticker_speed', 30);

    ob_start();
    echo '<div class="tvc-carousel-wrapper">';
    echo '<div class="tvc-carousel" style="animation-duration:'.$speed.'s;">';

    // Render twice for seamless loop
    for ($i = 0; $i < 2; $i++) {
      $query->rewind_posts();
      while ($query->have_posts()) {
        $query->the_post();
        $logo = get_the_post_thumbnail_url(get_the_ID(), 'medium');
        $name = get_the_title();
        echo '<div class="tvc-item">';
        echo '<img src="'.$logo.'" alt="'.$name.'" style="width:'.$width.'px;height:'.$height.'px;">';
        if ($show_title) echo '<span>'.$name.'</span>';
        echo '</div>';
      }
    }

    echo '</div></div>';
    wp_reset_postdata();
    return ob_get_clean();
  }
}
new TVC_Shortcode();
