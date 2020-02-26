<?php
namespace ElementorUserMovieDashboard\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class User_Movie_Dashboard extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'user-movie-dashboard';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'User Movie Dashboard', 'elementor-user-movie-dashboard' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'elementor-user-movie-dashboard' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'elementor-user-movie-dashboard' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'elementor-user-movie-dashboard' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_transform',
			[
				'label' => __( 'Text Transform', 'elementor-user-movie-dashboard' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'None', 'elementor-user-movie-dashboard' ),
					'uppercase' => __( 'UPPERCASE', 'elementor-user-movie-dashboard' ),
					'lowercase' => __( 'lowercase', 'elementor-user-movie-dashboard' ),
					'capitalize' => __( 'Capitalize', 'elementor-user-movie-dashboard' ),
				],
				'selectors' => [
					'{{WRAPPER}} .title' => 'text-transform: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
    }		
		protected function render_user_start($user) {
			$html = "";
			$html .= '<div class="user">';
			$html .= '<h2>' . esc_html($user->user_login) . '</h2>';
			echo $html;
		}

		protected function render_user_end() {
			$html = "";
			$html .= '</div>'; //end class="user"
			echo $html;
		}

		protected function render_user_seen_movies_and_ratings($user) {
			$html = "";
			$movieArray = [];
        	$movieMetaKey = 'movie-statuses';
            $userMovieArray = get_user_meta($user->ID, $movieMetaKey, true);
            if(is_array($userMovieArray) || is_object($userMovieArray)) {
			    foreach($userMovieArray as $movieID=>$movie) {
				    if($movie['seen'] && has_term('2020', 'oscar-year', $movieID)) {
                        $html .= '<div class="movie">';
    					$html .= '<span class="title">' . wp_trim_words(get_the_title($movieID), 6) . '</span>';
					    $html .= '<span class="rating">' . ($movie['rating'] ? $movie['rating'] : "unrated") . '</span>';
					    $html .= '</div>';
				    }
                }
            }
			echo $html;
		}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$user_args = array(
			'role__in'	    => array(
				                'movie_watecher',
				                'administrator'
                                ),
            'meta_key'      => 'movie-statuses',
            'meta_value'    => '',
            'meta_compare'  => '!='
		);

        $movieWatchers = get_users($user_args);


		echo '<div class="user-flex">';
		foreach($movieWatchers as $u) {
			$this->render_user_start($u);
			$this->render_user_seen_movies_and_ratings($u);
			$this->render_user_end();
		}
		echo '</div>';

	}
}