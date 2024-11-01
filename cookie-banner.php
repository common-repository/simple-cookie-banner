<?php
/**
 *Plugin Name: Simple Cookie banner
 *Description: The Plugin allow to add and change banner with information about cookie
 *Author: Varion
 *Author URI:  https://varion.de
 *Version: 1.0.0
 **/
/*
/*
  Copyright (C) 2019 Varion

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/ 
if(!class_exists( 'CookieBannerPlugin' )){
    class CookieBannerPlugin{
        public function __construct(){
            add_action( 'admin_menu', array($this, 'add_admin_pages') );
            add_action( 'wp_head', array($this, 'add_cookies' ));            
            add_action('admin_head',array($this, 'add_optout_tc_button'));
            add_shortcode( 'optout', array($this,'register_optout_shortcode') );
        }

        public function add_admin_pages(){
            if(current_user_can("administrator"))
            {
              add_menu_page(
                  'Cookie banner',
                  'Cookie banner',
                  'manage_options',
                  'cookie_banner_plugin',
                  array( $this, 'admin_index' )
                  );
            }
        }
        
        public function admin_index(){
          if(current_user_can("administrator"))
            {
              wp_enqueue_script('jquery');
              wp_enqueue_script('tiny_mce');
              wp_enqueue_style('cookie-bootstrap', plugin_dir_url(__FILE__).'/bootstrap.min.css');
              require_once plugin_dir_path( __FILE__ ) . 'template/admin.php';
            }
        }

        public function add_optout_tc_button() {
              add_filter("mce_external_plugins", array($this, "optout_add_tinymce_plugin"));
              add_filter('mce_buttons', array($this, 'register_optout_tc_button'));
        }

        public function optout_add_tinymce_plugin($plugin_array) {
              $plugin_array['optout_tc_button'] = plugins_url( '/text-button.js', __FILE__ );
              return $plugin_array;
        }
        
        public function register_optout_tc_button($buttons) {
          array_push($buttons, "optout_tc_button");
          return $buttons;
       }       

        public function add_cookies(){
            if(is_singular()){
                if( get_option('cookie-msg') && get_option('cookie-btn')){
                  wp_enqueue_script('cookie-banner-script', plugin_dir_url(__FILE__). 'cookie_banner_script.js');
                  wp_localize_script( 'cookie-banner-script', 'php_var', 
                    array(
                      'msg' => do_shortcode(get_option('cookie-msg')), 
                      'btn' => get_option('cookie-btn'), 
                      'bck' => get_option('bck'), 
                      'font' => get_option('font'), 
                      'btn_bck' => get_option('btn-bck'), 
                      'btn_font' => get_option('btn-font'),
                      'style_url' => plugin_dir_url(__FILE__) . 'banner-style.css'
                    ));
                 }
            }
        }
        public function register_optout_shortcode($atts, $content){
             return "<span onclick='OptOutAnalytics()'>$content</span>";
        }
    }
}

$cookiePlugin = new CookieBannerPlugin();
?>