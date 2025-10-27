<?php
if ( ! class_exists( 'TVC_GitHub_Updater' ) ) {
  class TVC_GitHub_Updater {
    private $repo = 'forhadulislamshuvo/trusted-vendor-carousel';
    private $plugin_file;

    public function __construct( $plugin_file ) {
      $this->plugin_file = $plugin_file;
      add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'check_for_update' ] );
      add_filter( 'plugins_api', [ $this, 'set_plugin_info' ], 10, 3 );
    }

    public function check_for_update( $transient ) {
      if ( empty( $transient->checked ) ) return $transient;
      $remote = wp_remote_get( 'https://api.github.com/repos/' . $this->repo . '/releases/latest' );
      if ( is_wp_error( $remote ) ) return $transient;
      $release = json_decode( wp_remote_retrieve_body( $remote ) );
      if ( isset( $release->tag_name ) ) {
        $new_version = str_replace( 'v', '', $release->tag_name );
        $plugin_data = get_plugin_data( $this->plugin_file );
        if ( version_compare( $new_version, $plugin_data['Version'], '>' ) ) {
          $transient->response[ plugin_basename( $this->plugin_file ) ] = (object) [
            'slug' => plugin_basename( $this->plugin_file ),
            'new_version' => $new_version,
            'package' => $release->assets[0]->browser_download_url ?? '',
            'url' => $release->html_url
          ];
        }
      }
      return $transient;
    }

    public function set_plugin_info( $res, $action, $args ) {
      if ( 'plugin_information' !== $action || $args->slug !== plugin_basename( $this->plugin_file ) )
        return $res;
      $remote = wp_remote_get( 'https://api.github.com/repos/' . $this->repo . '/releases/latest' );
      if ( is_wp_error( $remote ) ) return $res;
      $release = json_decode( wp_remote_retrieve_body( $remote ) );
      $res = (object) [
        'name' => 'Trusted Vendor Carousel',
        'slug' => plugin_basename( $this->plugin_file ),
        'version' => str_replace('v','',$release->tag_name),
        'author' => '<a href="https://strawdigital.com">Straw Digital</a>',
        'homepage' => $release->html_url,
        'download_link' => $release->assets[0]->browser_download_url ?? '',
        'sections' => [ 'description' => $release->body ?? 'Trusted Vendor Carousel auto-update via GitHub.' ]
      ];
      return $res;
    }
  }
  new TVC_GitHub_Updater( __FILE__ );
}
