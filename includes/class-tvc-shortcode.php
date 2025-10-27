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
    $width      = get_option('tvc_logo_width', 120);
    $height     = get_option('tvc_logo_height', 80);
    $speed      = get_option('tvc_ticker_speed', 30);

    // Alignment options (restored)
    $h_align_opt = get_option('tvc_alignment_horizontal', 'center'); // left|center|right
    $v_align_opt = get_option('tvc_alignment_vertical',   'center'); // top|center|bottom

    $justify = array(
      'left'   => 'flex-start',
      'center' => 'center',
      'right'  => 'flex-end',
    );
    $align_items = array(
      'top'    => 'flex-start',
      'center' => 'center',
      'bottom' => 'flex-end',
    );
    $justify_css = isset($justify[$h_align_opt]) ? $justify[$h_align_opt] : 'center';
    $align_css   = isset($align_items[$v_align_opt]) ? $align_items[$v_align_opt] : 'center';

    ob_start();
    echo '<div class="tvc-carousel-wrapper">';
    echo '<div class="tvc-carousel" style="animation-duration:'.esc_attr($speed).'s;justify-content:'.esc_attr($justify_css).';align-items:'.esc_attr($align_css).'">';

    // Render twice for seamless loop
    for ($i = 0; $i < 2; $i++) {
      $query->rewind_posts();
      while ($query->have_posts()) {
        $query->the_post();
        $logo = get_the_post_thumbnail_url(get_the_ID(), 'medium');
        $name = get_the_title();
        echo '<div class="tvc-item" style="text-align:'.esc_attr($h_align_opt).'">';
        echo '<img src="'.esc_url($logo).'" alt="'.esc_attr($name).'" style="width:'.intval($width).'px;height:'.intval($height).'px;">';
        if ($show_title) echo '<span>'.esc_html($name).'</span>';
        echo '</div>';
      }
    }

    echo '</div></div>';
    wp_reset_postdata();
    return ob_get_clean();
  }
}
new TVC_Shortcode();
