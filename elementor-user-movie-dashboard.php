<?php
/**
 * Plugin Name: Elementor User Movie Dashboard
 * Description: Adds dashboard to see users' watched state and rating
 * Plugin URI: https://www.evilgeniusdevel.com
 * Version: 0.0.1
 * Author: Drew Wiltjer
 * Author URI: https://www.evilgeniusdvel.com
 * text-domain: elementor-user-movie-dashboard
 */

 if( ! defined( 'ABSPATH') ) exit; //exit if accessed directly

 final class Elementor_User_Movie_Dashboard {
     const VERSION = '0.0.1';
     const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
     const MINIMUM_PHP_VERSION = '7.0';

     public function __construct() {
        add_action( 'init', array( $this, 'i18n' ) ); //loads translation
        add_action( 'plugins_loaded', array( $this, 'init') ); //init plugin
     }

     public function i18n() {
         load_plugin_textdomain( 'elementor-user-movie-dashboard');
     }

     public function init() {
        //Check if Elementor is installed and activated
        if ( !did_action( 'elementor/loaded') ) {
             add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
             return;
        }

        //check for required Elementor version
        if( !version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version') );
            return;
        }

        //check for required PHP version
        if ( !version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '>' ) ) {
            add_action( 'admin_notices' , array( $this, 'admin_notice_minimum_php_version' ) );
            return;
        }

        //passed validation, load plugin
        require_once( 'plugin.php' );
     }

     /**
      * Admin notices
      */
      public function admin_notice_missing_main_plugin() {
          if (isset( $_GET['activate'] ) ) {
              unset( $_GET['activate'] );
          }

          $message = sprintf(
              esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-user-movie-dashboard'),
                        '<strong>' . esc_html__( 'Elementor User Movie Dashboard', 'elementor-user-movie-dashboard') . '</strong>',
                        '<strong>' . esc_html__( 'Elementor', 'elementor-user-movie-dashboard') . '</strong>'
          );

          printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
      }

      public function admin_notice_minimum_elementor_version() {
        if (isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-user-movie-dashboard'),
                      '<strong>' . esc_html__( 'Elementor User Movie Dashboard', 'elementor-user-movie-dashboard') . '</strong>',
                      '<strong>' . esc_html__( 'Elementor', 'elementor-user-movie-dashboard') . '</strong>',
                      self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function admin_notice_minimum_php_version() {
        if (isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-user-movie-dashboard'),
                      '<strong>' . esc_html__( 'Elementor User Movie Dashboard', 'elementor-user-movie-dashboard') . '</strong>',
                      '<strong>' . esc_html__( 'PHP', 'elementor-user-movie-dashboard') . '</strong>',
                      self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }
 }

 new Elementor_User_Movie_Dashboard();