<?php
/*
  Plugin Name: Virtual Products Plugin
  Plugin URI: https://osclasspoint.com/osclass-plugins/payments-and-shopping/virtual-products-plugin-i83
  Description: Enable your visitors to upload file and/or share virtual products
  Version: 1.3.1
  Author: MB Themes
  Author URI: https://osclasspoint.com
  Author Email: info@osclasspoint.com
  Short Name: virtual
  Plugin update URI: virtual
  Support URI: https://forums.osclasspoint.com/virtual-products-plugin/
  Product Key: FU7UTpHmDThsRKWwXwZO
*/


require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'model/ModelVRT.php';
require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'functions.php';
require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'email.php';



osc_enqueue_style('vrt-user-style', osc_base_url() . 'oc-content/plugins/virtual/css/user.css?v=' . date('YmdHis'));
osc_enqueue_style('font-awesome47', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
osc_register_script('vrt-user', osc_base_url() . 'oc-content/plugins/virtual/js/user.js?v=' . date('YmdHis'), 'jquery');
osc_enqueue_script('vrt-user');


osc_add_route('vrt-download', 'download/([0-9]+)', 'download/{itemId}', osc_plugin_folder(__FILE__).'user/download.php', false, 'vrt', 'download');
osc_add_route('vrt-download-admin', 'download-admin/([0-9]+)', 'download-admin/{fileId}', osc_plugin_folder(__FILE__).'user/download-admin.php', false, 'vrt', 'download-admin');
osc_add_route('vrt-downloads', 'user/downloads', 'user/downloads', osc_plugin_folder(__FILE__).'user/downloads.php', true, 'vrt', 'downloads');
osc_add_route('vrt-upload', 'user/upload/([0-9]+)', 'user/upload/{itemId}', osc_plugin_folder(__FILE__).'user/upload.php', true, 'vrt', 'upload');


// DOWNLOAD LINK TO ITEM_DETAIL
function vrt_download_hook($item) {
  if(vrt_param('download_hook') == 1) {
    echo vrt_download($item['pk_i_id']);
  }
}

osc_add_hook('item_detail', 'vrt_download_hook');


// UPLOAD NEW FILE LINK
function vrt_upload_file($item_id = -1) {
  if($item_id <= 0) {
    $item_id = osc_item_id();
  }

  if($item_id <= 0 || !osc_is_web_user_logged_in() || osc_item_user_id() <> osc_logged_user_id()) {
    return false;
  }

  return '<a href="' . osc_route_url('vrt-upload', array('itemId' => $item_id))  . '" class="vrt-upload-file">' . __('Upload file', 'virtual') . '</a>';
}


// UPLOAD FILE - ITEM PUBLISH
osc_add_hook('posted_item', 'vrt_upload');
osc_add_hook('edited_item', 'vrt_upload');


// ADD DOWNLOADS SECTION TO USER MENU
function vrt_user_menu_downloads(){
  if(osc_current_web_theme() == 'veronika' || osc_current_web_theme() == 'stela' || (defined('USER_MENU_ICONS') && USER_MENU_ICONS == 1)) {
    echo '<li class="opt_vrt"><a href="' . osc_route_url('vrt-downloads') .'" ><i class="fa fa-download"></i> '.__('Downloads', 'virtual') . '</a></li>';
  } else {
    echo '<li class="opt_vrt"><a href="' . osc_route_url('vrt-downloads') .'" >'.__('Downloads', 'virtual') . '</a></li>';
  }
}

osc_add_hook('user_menu', 'vrt_user_menu_downloads');



// ADD NOTIFICATION TO ADMIN TOOLBAR MENU - PENDING FILES
function vrt_admin_toolbar_file(){ 
  if( !osc_is_moderator() ) {
    $total = ModelVRT::newInstance()->countInactiveFiles();

    if($total > 0) {
      $title = '<i class="circle circle-red">' . $total . '</i>' . ($total == 1 ? __('File to be validated', 'virtual') : __('Files to be validated', 'virtual'));
      AdminToolbar::newInstance()->add_menu(
        array(
          'id' => 'vrt_files',
          'title' => $title,
          'href'  => osc_admin_base_url(true) . '?page=plugins&action=renderplugin&file=virtual/admin/configure.php&type=0',
          'meta'  => array('class' => 'action-btn action-btn-black')
        )
      );
    }
  }
}

osc_add_hook('add_admin_toolbar_menus', 'vrt_admin_toolbar_file', 1);


// PUBLISH FORM - ADD FILE UPLOAD
function vrt_publish_form($catId = '') {
  $allowed_cats = array_filter(explode(',', vrt_param('category')));

  if($catId > 0) {
    if(empty($allowed_cats) || in_array($catId, $allowed_cats)) {
      require_once 'user/upload-form.php';
    }
  }
}

osc_add_hook('item_form', 'vrt_publish_form');
osc_add_hook('item_edit', 'vrt_publish_form');


// INSTALL FUNCTION - DEFINE VARIABLES
function vrt_call_after_install() {
  ModelVRT::newInstance()->install();

  osc_set_preference('require_validation', 1, 'plugin-virtual', 'INTEGER');
  osc_set_preference('remove_older', 0, 'plugin-virtual', 'INTEGER');
  osc_set_preference('file_required', 0, 'plugin-virtual', 'INTEGER');
  osc_set_preference('download_hook', 1, 'plugin-virtual', 'INTEGER');
  osc_set_preference('style_button', 1, 'plugin-virtual', 'INTEGER');
  osc_set_preference('osclasspay_link', 0, 'plugin-virtual', 'INTEGER');
  osc_set_preference('allowed_extensions', 'zip,doc,pdf,rar,txt', 'plugin-virtual', 'STRING');
  osc_set_preference('max_file_size', '10', 'plugin-virtual', 'STRING');

}


function vrt_call_after_uninstall() {
  ModelVRT::newInstance()->uninstall();
}



// ADMIN MENU
function vrt_menu($title = NULL) {
  echo '<link href="' . osc_base_url() . 'oc-content/plugins/virtual/css/admin.css?v=' . date('YmdHis') . '" rel="stylesheet" type="text/css" />';
  echo '<link href="' . osc_base_url() . 'oc-content/plugins/virtual/css/bootstrap-switch.css" rel="stylesheet" type="text/css" />';
  echo '<link href="' . osc_base_url() . 'oc-content/plugins/virtual/css/tipped.css" rel="stylesheet" type="text/css" />';
  echo '<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />';
  echo '<script src="' . osc_base_url() . 'oc-content/plugins/virtual/js/admin.js?v=' . date('YmdHis') . '"></script>';
  echo '<script src="' . osc_base_url() . 'oc-content/plugins/virtual/js/tipped.js"></script>';
  echo '<script src="' . osc_base_url() . 'oc-content/plugins/virtual/js/bootstrap-switch.js"></script>';


  if( $title == '') { $title = __('Configure', 'virtual'); }

  $text  = '<div class="mb-head">';
  $text .= '<div class="mb-head-left">';
  $text .= '<h1>' . $title . '</h1>';
  $text .= '<h2>Virtual Products Plugin</h2>';
  $text .= '</div>';
  $text .= '<div class="mb-head-right">';
  $text .= '<ul class="mb-menu">';
  $text .= '<li><a href="' . osc_admin_base_url(true) . '?page=plugins&action=renderplugin&file=virtual/admin/configure.php"><i class="fa fa-wrench"></i><span>' . __('Configure', 'virtual') . '</span></a></li>';
  $text .= '</ul>';
  $text .= '</div>';
  $text .= '</div>';

  echo $text;
}



// ADMIN FOOTER
function vrt_footer() {
  $pluginInfo = osc_plugin_get_info('virtual/index.php');
  $text  = '<div class="mb-footer">';
  $text .= '<a target="_blank" class="mb-developer" href="https://osclasspoint.com"><img src="https://osclasspoint.com/favicon.ico" alt="MB Themes" /> osclasspoint.com</a>';
  $text .= '<a target="_blank" href="' . $pluginInfo['support_uri'] . '"><i class="fa fa-bug"></i> ' . __('Report Bug', 'virtual') . '</a>';
  $text .= '<a target="_blank" href="https://forums.osclasspoint.com/"><i class="fa fa-handshake-o"></i> ' . __('Support Forums', 'virtual') . '</a>';
  $text .= '<a target="_blank" class="mb-last" href="mailto:info@osclasspoint.com"><i class="fa fa-envelope"></i> ' . __('Contact Us', 'virtual') . '</a>';
  $text .= '<span class="mb-version">v' . $pluginInfo['version'] . '</span>';
  $text .= '</div>';

  return $text;
}



// ADD MENU LINK TO PLUGIN LIST
function vrt_admin_menu() {
echo '<h3><a href="#">Virtual Products Plugin</a></h3>
<ul> 
  <li><a style="color:#2eacce;" href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/configure.php') . '">&raquo; ' . __('Configure', 'virtual') . '</a></li>
</ul>';
}


// ADD MENU TO PLUGINS MENU LIST
osc_add_hook('admin_menu','vrt_admin_menu', 1);



// DISPLAY CONFIGURE LINK IN LIST OF PLUGINS
function vrt_conf() {
  osc_admin_render_plugin( osc_plugin_path( dirname(__FILE__) ) . '/admin/configure.php' );
}

osc_add_hook( osc_plugin_path( __FILE__ ) . '_configure', 'vrt_conf' );	


// CALL WHEN PLUGIN IS ACTIVATED - INSTALLED
osc_register_plugin(osc_plugin_path(__FILE__), 'vrt_call_after_install');

// SHOW UNINSTALL LINK
osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'vrt_call_after_uninstall');

?>