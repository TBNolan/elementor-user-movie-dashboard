<?php 
namespace ElementorUserMovieDashboard;

class Plugin {
    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    public function widget_scripts() {

    }

    private function include_widgets_files() {
        require_once(__DIR__ . '/widgets/user-movie-dashboard.php');
    }

    public function register_widgets() {
        $this->include_widgets_files();
        
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\User_Movie_Dashboard() );
        
    }

    public function __construct() {
        add_action( 'elementor/frontend/after_register_scripts', array( $this, 'widget_scripts') );

        add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets') );
    }
}

Plugin::instance();