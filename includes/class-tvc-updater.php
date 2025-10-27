<?php
if ( ! class_exists( 'TVC_GitHub_Updater' ) ) {
  class TVC_GitHub_Updater {
    private $repo = 'forhadulislamshuvo/trusted-vendor-carousel';
    private $plugin_file;
    private $plugin_basename;
    private $slug = 'trusted-vendor-carousel';

    public function __construct( $plugin_file ) {
      $this->plugin_file = $plugin_file;
      $this->plugin_basename = plugin_basename( $plugin_file );

      add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'check_for_update' ] );
      add_filter( 'plugins_api', [ $this, 'plugins_api_handler' ], 10, 3 );
    }

    public function check_for_update( $transient ) {
      if ( empty( $transient->checked ) ) return $transient;

      $remote = wp_remote_get( 'https://api.github.com/repos/' . $this->repo . '/releases/latest', [ 'timeout' => 15 ] );
      if ( is_wp_error( $remote ) ) return $transient;

      $release = json_decode( wp_remote_retrieve_body( $remote ) );
      if ( ! isset( $release->tag_name ) ) return $transient;

      $new_version = ltrim( $release->tag_name, 'v' );
      if ( ! function_exists( 'get_plugin_data' ) ) require_once ABSPATH . 'wp-admin/includes/plugin.php';
      $plugin_data = get_plugin_data( $this->plugin_file );
      $current_version = $plugin_data['Version'];

      if ( version_compare( $new_version, $current_version, '>' ) ) {
        $package = '';
        if ( ! empty( $release->assets ) && ! empty( $release->assets[0]->browser_download_url ) ) {
          $package = $release->assets[0]->browser_download_url;
        } else {
          $package = 'https://api.github.com/repos/' . $this->repo . '/zipball/' . $release->tag_name;
        }

        $item = (object) [
          'slug'        => $this->slug,
          'plugin'      => $this->plugin_basename,
          'new_version' => $new_version,
          'tested'      => get_bloginfo( 'version' ),
          'package'     => $package,
          'url'         => 'https://github.com/' . $this->repo
        ];

        $transient->response[ $this->plugin_basename ] = $item;
      }

      return $transient;
    }

    public function plugins_api_handler( $res, $action, $args ) {
      if ( 'plugin_information' !== $action || ( $args->slug ?? '' ) !== $this->slug ) return $res;

      $remote = wp_remote_get( 'https://api.github.com/repos/' . $this->repo . '/releases/latest', [ 'timeout' => 15 ] );
      if ( is_wp_error( $remote ) ) return $res;
      $release = json_decode( wp_remote_retrieve_body( $remote ) );

      $res = (object) [
        'name'          => 'Trusted Vendor Carousel',
        'slug'          => $this->slug,
        'version'       => ltrim( $release->tag_name ?? 'v2.5.1', 'v' ),
        'author'        => '<a href="http://digital.strawbd.com/">Md Forhadul Islam Shuvo – Straw Digital</a>',
        'homepage'      => 'https://github.com/' . $this->repo,
        'download_link' => !empty($release->assets[0]->browser_download_url) ? $release->assets[0]->browser_download_url : 'https://api.github.com/repos/' . $this->repo . '/zipball/' . ($release->tag_name ?? 'v2.5.1'),
        'sections'      => [
          'description' => $release->body ?? 'Trusted Vendor Carousel by Straw Digital — infinite logo ticker with GitHub-powered auto-updates.'
        ],
      ];

      return $res;
    }
  }
  new TVC_GitHub_Updater( __FILE__ );
}
