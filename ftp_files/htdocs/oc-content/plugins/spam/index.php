<?php
/*
  Plugin Name: Anti-Spam & Bot Protect Plugin
  Plugin URI: https://osclasspoint.com/osclass-plugins/protection-and-spam/anti-spam-and-bot-protection-osclass-plugin-i51
  Description: Provides advanced functionality to fight with spammers and spam bots
  Version: 3.3.4
  Author: MB Themes
  Author URI: https://osclasspoint.com
  Author Email: info@osclasspoint.com
  Short Name: spam
  Plugin update URI: spam-plugin
  Support URI: https://forums.osclasspoint.com/spam-solution-plugin/
  Product Key: ac52YupMpzkKcAtcCy8m
*/



require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'admin/pagination.php';
require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'model/ModelANS.php';
require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'functions.php';


function ans_call_after_install() {
  osc_set_preference('duplicate_percent', '80', 'plugin-spam', 'INTEGER');
  osc_set_preference('allow_check', '1', 'plugin-spam', 'INTEGER');
  osc_set_preference('honeypot_enabled', '1', 'plugin-spam', 'INTEGER');
  osc_set_preference('action_check', 'spam', 'plugin-spam', 'STRING');
  osc_set_preference('duplicate_length', '200', 'plugin-spam', 'INTEGER');
  osc_set_preference('allow_triple', '1', 'plugin-spam', 'INTEGER');
  osc_set_preference('ban_triple', '1', 'plugin-spam', 'INTEGER');
  osc_set_preference('submask_triple', '1', 'plugin-spam', 'INTEGER');
  osc_set_preference('domains_triple', 'mail.ru,bigmir.net', 'plugin-spam', 'STRING');
  osc_set_preference('white_domains', '', 'plugin-spam', 'STRING');
  osc_set_preference('dots_triple', '2', 'plugin-spam', 'INTEGER');
  osc_set_preference('stopforumspam_triple', '1', 'plugin-spam', 'INTEGER');
  osc_set_preference('upper_triple', '1', 'plugin-spam', 'INTEGER');
  osc_set_preference('number_triple', '1', 'plugin-spam', 'INTEGER');
  osc_set_preference('enable_badwords', '0', 'plugin-spam', 'INTEGER');
  osc_set_preference('list_badwords', '', 'plugin-spam', 'STRING');
  osc_set_preference('enable_banwords', '0', 'plugin-spam', 'INTEGER');
  osc_set_preference('list_banwords', '', 'plugin-spam', 'STRING');
  osc_set_preference('track_reffer', '0', 'plugin-spam', 'INTEGER');
  osc_set_preference('multiple_user_ip', '0', 'plugin-spam', 'INTEGER');
  osc_set_preference('per_page', '15', 'plugin-spam', 'INTEGER');
  osc_set_preference('min_confidence', 80, 'plugin-spam', 'INTEGER');
  
  ModelANS::newInstance()->install();

}

function ans_call_after_uninstall() {
  ModelANS::newInstance()->uninstall();
}




// ADMIN MENU
function ans_menu($title = NULL) {
  echo '<link href="' . osc_base_url() . 'oc-content/plugins/spam/css/admin.css?v=' . date('YmdHis') . '" rel="stylesheet" type="text/css" />';
  echo '<link href="' . osc_base_url() . 'oc-content/plugins/spam/css/bootstrap-switch.css" rel="stylesheet" type="text/css" />';
  echo '<link href="' . osc_base_url() . 'oc-content/plugins/spam/css/tipped.css" rel="stylesheet" type="text/css" />';
  echo '<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />';
  echo '<script src="' . osc_base_url() . 'oc-content/plugins/spam/js/admin.js?v=' . date('YmdHis') . '"></script>';
  echo '<script src="' . osc_base_url() . 'oc-content/plugins/spam/js/tipped.js"></script>';
  echo '<script src="' . osc_base_url() . 'oc-content/plugins/spam/js/bootstrap-switch.js"></script>';



  if( $title == '') { $title = __('Configure', 'spam'); }

  $text  = '<div class="mb-head">';
  $text .= '<div class="mb-head-left">';
  $text .= '<h1>' . $title . '</h1>';
  $text .= '<h2>Anti-Spam & Bot Protect Plugin</h2>';
  $text .= '</div>';
  $text .= '<div class="mb-head-right">';
  $text .= '<ul class="mb-menu">';
  $text .= '<li><a href="' . osc_admin_base_url(true) . '?page=plugins&action=renderplugin&file=spam/admin/duplicate.php"><i class="fa fa-clone"></i><span>' . __('Duplicates', 'spam') . '</span></a></li>';
  $text .= '<li><a href="' . osc_admin_base_url(true) . '?page=plugins&action=renderplugin&file=spam/admin/bot_protect.php"><i class="fa fa-shield"></i><span>' . __('Bot Protect', 'spam') . '</span></a></li>';
  $text .= '<li><a href="' . osc_admin_base_url(true) . '?page=plugins&action=renderplugin&file=spam/admin/badwords.php"><i class="fa fa-eye-slash"></i><span>' . __('Bad words', 'spam') . '</span></a></li>';
  $text .= '<li><a href="' . osc_admin_base_url(true) . '?page=plugins&action=renderplugin&file=spam/admin/banwords.php"><i class="fa fa-ban"></i><span>' . __('Ban words', 'spam') . '</span></a></li>';
  $text .= '</ul>';
  $text .= '</div>';
  $text .= '</div>';

  echo $text;
}



// ADMIN FOOTER
function ans_footer() {
  $pluginInfo = osc_plugin_get_info('spam/index.php');
  $text  = '<div class="mb-footer">';
  $text .= '<a target="_blank" class="mb-developer" href="https://osclasspoint.com"><img src="https://osclasspoint.com/favicon.ico" alt="OsclassPoint Market" /> OsclassPoint Market</a>';
  $text .= '<a target="_blank" href="' . $pluginInfo['support_uri'] . '"><i class="fa fa-bug"></i> ' . __('Report Bug', 'spam') . '</a>';
  $text .= '<a target="_blank" href="https://forums.osclasspoint.com/"><i class="fa fa-handshake-o"></i> ' . __('Support Forums', 'spam') . '</a>';
  $text .= '<a target="_blank" class="mb-last" href="mailto:info@osclasspoint.com"><i class="fa fa-envelope"></i> ' . __('Contact Us', 'spam') . '</a>';
  $text .= '<span class="mb-version">v' . $pluginInfo['version'] . '</span>';
  $text .= '</div>';

  return $text;
}



// ADD MENU LINK TO PLUGIN LIST
function ans_admin_menu() {
echo '<h3><a href="#">Anti-Spam & Bot Protect Plugin</a></h3>
<ul> 
  <li><a style="color:#2eacce;" href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/duplicate.php') . '">&raquo; ' . __('Duplicate', 'spam') . '</a></li>
  <li><a style="color:#2eacce;" href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/bot_protect.php') . '">&raquo; ' . __('Bot protect', 'spam') . '</a></li>
  <li><a style="color:#2eacce;" href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/badwords.php') . '">&raquo; ' . __('Bad words', 'spam') . '</a></li>
  <li><a style="color:#2eacce;" href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/banwords.php') . '">&raquo; ' . __('Ban words', 'spam') . '</a></li>
</ul>';
}


// ADD MENU TO PLUGINS MENU LIST
osc_add_hook('admin_menu','ans_admin_menu', 1);



// DISPLAY CONFIGURE LINK IN LIST OF PLUGINS
function ans_conf() {
  osc_admin_render_plugin( osc_plugin_path( dirname(__FILE__) ) . '/admin/duplicate.php' );
}

osc_add_hook( osc_plugin_path( __FILE__ ) . '_configure', 'ans_conf' );	


// CALL WHEN PLUGIN IS ACTIVATED - INSTALLED
osc_register_plugin(osc_plugin_path(__FILE__), 'ans_call_after_install');

// SHOW UNINSTALL LINK
osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'ans_call_after_uninstall');



?>