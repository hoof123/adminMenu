<?php
/*
* Plugin Name: admin menu
* Description: defines the admin menu based on users and creates dashboard widgets.
* Version: 1.0
* Author: Jake Price | JP Creative Media
* Author URI: https://jpcreative.ca/
* License: GPLv2
*/

// hooks
add_action('wp_dashboard_setup', 'custom_dashboard_widgets');
add_action( 'admin_menu', 'remove_menus', 999 );
// add_action('admin_menu', 'custom_menu', 999);
add_action('admin_bar_menu', 'remove_from_admin_bar', 999);
add_filter( 'login_redirect', 'custom_login_redirect', 999, 3 );

// custom widgets
function custom_dashboard_widgets() {

   global $wp_meta_boxes;

   wp_add_dashboard_widget('custom_contact_widget', 'a warm welcome from JP Creative Media', 'custom_dashboard_contact');
}

// define widgets
function custom_dashboard_contact() {

   // content
   echo '<p>welcome to your website!<br><br>need help? contact me at: <a href="mailto:support@jpcreativemedia.ca">support@jpcreativemedia.ca</a>.</p>';
}

/* debug admin menu
add_action( 'admin_notices', 'debug_admin_menu' );

function debug_admin_menu() {
   
   global $menu, $submenu, $pagenow;

   if( $pagenow == 'index.php' ) {  // prints on dashboard

      echo '<pre>'; print_r( $menu ); echo '</pre>'; // parent menus
      echo '<pre>'; print_r( $submenu ); echo '</pre>'; // submenus
  }
}
*/

// remove menus
function remove_menus() {

   // allowed user ids
   $allowed_ids = array(1);

   if( !in_array(get_current_user_id(), $allowed_ids) ) {

      show_admin_bar(false);

      remove_submenu_page( 'index.php', 'update-core.php' ); // updates
      remove_menu_page( 'edit.php' ); // posts
      remove_menu_page( 'edit.php?post_type=page' ); // pages
      remove_menu_page( 'upload.php' ); // media
      remove_menu_page( 'edit-comments.php' ); // comments
      remove_menu_page( 'tools.php' ); // tools
      remove_menu_page( 'plugins.php' ); // plugins
      remove_menu_page( 'themes.php' ); // themes
      remove_submenu_page( 'kadence-blocks', 'kadence-blocks' ); // kadence blocks
      remove_menu_page( 'edit.php?post_type=kt_gallery' ); // kadence galleries
      remove_submenu_page( 'monsterinsights_reports', 'monsterinsights_settings' ); // monster insights settings
      remove_submenu_page( 'monsterinsights_reports', 'https://dougephotography.com/wp-admin/admin.php?page=monsterinsights_settings#/addons' ); // monster insights addons
      remove_submenu_page( 'options-general.php', 'akismet-key-config' ); // akismet
      
   }

   /*
   // remove menus by not allowed user capability
   if( !current_user_can('install_themes') ) {

      // current user can't install themes, so remove the menu
      remove_menu_page( 'themes.php' );
   }
   */
}

// create a custom menu
/*
function custom_menu() {

   /* template
   add_menu_page( 
      'Page Title', 
      'Menu Title', 
      'edit_posts', 
      'menu_slug', 
      'page_callback_function', 
      'dashicons-media-spreadsheet'
   );
   
}
*/

function remove_from_admin_bar() {

   global $wp_admin_bar;

   // allowed user ids
   $allowed_ids = array(1);

   if( !in_array(get_current_user_id(), $allowed_ids) ) {

      // WordPress Core Items (uncomment to remove)

      //$wp_admin_bar->remove_node('updates');
      //$wp_admin_bar->remove_node('comments');
      $wp_admin_bar->remove_node( 'new-content' );
      //$wp_admin_bar->remove_node('wp-logo');
      //$wp_admin_bar->remove_node('site-name');
      //$wp_admin_bar->remove_node('my-account');
      //$wp_admin_bar->remove_node('search');
      //$wp_admin_bar->remove_node('customize');

      $wp_admin_bar->remove_node( 'bluehost-support' ); // bluehost support

   }
}

function custom_login_redirect( $redirect_to, $request, $user ) {

   if ( isset( $user->roles ) && is_array( $user->roles ) ) {

      if ( in_array( 'administrator', $user->roles ) || in_array( 'editor', $user->roles ) || in_array( 'author', $user->roles ) ) {

      $redirect_to = admin_url();
      
      } else if ( in_array( 'shop_manager', $user->roles ) ) {

         $redirect_to = admin_url();

      } else if ( in_array( 'customer', $user->roles ) ) {

         $redirect_to = home_url( '/my-account' );

      } else {
         
      $redirect_to = home_url();
      }
   }

   return $redirect_to;
}
?>