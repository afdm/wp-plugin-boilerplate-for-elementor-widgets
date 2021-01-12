<?php

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 
/**
 * @since 1.0.0
 */
class Simple_Menu extends Elementor\Widget_Base {

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
	    wp_register_script( 'simple-menu', plugin_dir_url( __FILE__ ) . 'js/simple-menu.js', ['elementor-frontend'], $version, true );
    }
 
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
        return 'simple-menu';
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
        return __( 'Cool and Simple Menu', 'elementor-monplugin' );
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
        return 'eicon-nav-menu';
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
     * Build an array of Wordpress menu name
     * 
     * Used to build a list
     * 
     * @since 1.0
     *
     * @access protected
     * 
     * @return array Wordpress menu name.
     */
    protected function select_menu() {
        $menus = wp_get_nav_menus();

        $items = array();
        $i     = 0;
        foreach ( $menus as $menu ) {
            if ( $i == 0 ) {
                $default = $menu->slug;
                $i ++;
            }
            $items[ $menu->slug ] = $menu->name;
        }
        return $items;
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
                'label' => __( 'Cool and Simple Menu', 'elementor-monplugin' ),
            ]
        );

        $this->add_control(
            'simple_nav_menu',
            [
                'label'   => __( 'Select Menu', 'elementor-monplugin' ),
                'type'    => Controls_Manager::SELECT, 'options' => $this->select_menu(),
                'default' => '',
            ]
        );
        $this->end_controls_section();
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

        $settings = $this->get_settings();

        // Get menu
        $nav_menu = ! empty( $settings['simple_nav_menu'] ) ? wp_get_nav_menu_object( $settings['simple_nav_menu'] ) : false;

        if ( ! $nav_menu ) {
            return;
        }

        $nav_menu_args = array(
            'fallback_cb'    => false,
            'container'      => false,
            'menu_id'        => 'elementor-navmenu',
            'menu_class'     => 'elementor-navmenu',
            'theme_location' => 'default_navmenu', // creating a fake location for better functional control
            'menu'           => $nav_menu,
            'echo'           => true,
            'depth'          => 0,
            'walker'         => '',
            'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        );

        ?>
            <nav class="menu" role="navigation">
                <?php
                    wp_nav_menu(
                        apply_filters(
                            'widget_nav_menu_args',
                            $nav_menu_args,
                            $nav_menu,
                            $settings
                        )
                    );
                ?>
            </nav> 
        <?php
    }
 
    /**
     * Render the widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function _content_template() {
        
    }

    public function get_script_depends() {
        return array('simple-menu');
    }
}
