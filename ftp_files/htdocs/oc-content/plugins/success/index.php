<?php
/*
  Plugin Name: Success Publish Box Plugin
  Plugin URI: https://osclasspoint.com/osclass-plugins/design-and-appearance/success-publish-box-osclassplugin_i75
  Description: When listing is published, user gets box with options to share and promote newly added listing
  Version: 1.2.0
  Author: MB Themes
  Author URI: https://www.mb-themes.com
  Author Email: info@mb-themes.com
  Short Name: success
  Plugin update URI: success
  Support URI: https://forums.osclasspoint.com/success-publish-box-plugin/
  Product Key: RhTE7SKNogtq8bNVvIcJ
*/



osc_enqueue_style('font-awesome47', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
osc_enqueue_style('scf-user-style', osc_base_url() . 'oc-content/plugins/success/css/user.css');

require_once 'functions.php';


// CREATE FLAG WHEN NEW ITEM IS POSTED
function scf_posted_item($item) {
  if(!defined('OC_ADMIN') || OC_ADMIN === false) {
    if(isset($item['pk_i_id']) && $item['b_active'] == 1 && $item['b_spam'] == 0 && $item['b_enabled'] == 1) {
      Session::newInstance()->_set('scfPostedItem', $item['pk_i_id']);
    }
  }
}

osc_add_hook('posted_item', 'scf_posted_item', 2);


function scf_posted_init() {
  if(osc_get_preference('enabled', 'plugin-success') <> 1) {
    return false;
  }

  $show_box = false;
  $success_message = false;

  $messages = Session::newInstance()->_getMessage('pubMessages');

  if(is_array($messages) && !empty($messages) && count($messages) > 0) {
    foreach($messages as $m) {
      if($m['type'] == 'ok') {
        $success_message = true;
      }
    }
  }

  if(osc_item_post_url() == @$_SERVER['HTTP_REFERER']) {   // comming from item publish
    if(Params::getParam('page') == 'search' || Params::getParam('page') == osc_get_preference('rewrite_search_url', 'osclass')) {   // send to search page
      if(Params::getParam('sCategory') <> '') {   // has some category
        if($success_message) {   // contains success message
          $show_box = true;
        }
      }
    }
  }

  if(Params::getParam('route') == 'osp-item-pay-publish' && Params::getParam('isPublish') == 1 && Params::getParam('itemId') > 0) {
    $show_box = true;
  }
  
  
  if(Session::newInstance()->_get('scfPostedItem') > 0) {
    $item_id = Session::newInstance()->_get('scfPostedItem');
    Session::newInstance()->_drop('scfPostedItem');
    Params::setParam('itemId', $item_id);
    $show_box = true;
  }
  

  if($show_box) {
    require_once 'user/box.php';
  }
}

osc_add_hook('header', 'scf_posted_init', 2);


// ADD SCRIPT TO FOOTER
function scf_footer_script() { 
  ?>
    <script>
      $(document).ready(function(){
        $('body').on('click', '#scf-cover, #scf-close', function(e){
          $('#scf-cover, #scf-box').fadeOut(200);
        });
      });
    </script>
  <?php
}

osc_add_hook('footer', 'scf_footer_script');


// INSTALL FUNCTION - DEFINE VARIABLES
function scf_call_after_install() {
  osc_set_preference('enabled', 1, 'plugin-success', 'INTEGER');
  osc_set_preference('support', 1, 'plugin-success', 'INTEGER');
}


function scf_call_after_uninstall() {
  osc_delete_preference('enabled', 'plugin-success');
  osc_delete_preference('support', 'plugin-success');
}



// ADMIN MENU
function scf_menu($title = NULL) {
  echo '<link href="' . osc_base_url() . 'oc-content/plugins/success/css/admin.css" rel="stylesheet" type="text/css" />';
  echo '<link href="' . osc_base_url() . 'oc-content/plugins/success/css/bootstrap-switch.css" rel="stylesheet" type="text/css" />';
  echo '<link href="' . osc_base_url() . 'oc-content/plugins/success/css/tipped.css" rel="stylesheet" type="text/css" />';
  echo '<link href="//fonts.googleapis.com/css?family=Open+Sans:300,600&amp;subset=latin,latin-ext" rel="stylesheet" type="text/css" />';
  echo '<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />';
  echo '<script src="' . osc_base_url() . 'oc-content/plugins/success/js/admin.js"></script>';
  echo '<script src="' . osc_base_url() . 'oc-content/plugins/success/js/tipped.js"></script>';
  echo '<script src="' . osc_base_url() . 'oc-content/plugins/success/js/bootstrap-switch.js"></script>';



  if( $title == '') { $title = __('Configure', 'success'); }

  $text  = '<div class="mb-head">';
  $text .= '<div class="mb-head-left">';
  $text .= '<h1>' . $title . '</h1>';
  $text .= '<h2>Success Publish Box Plugin</h2>';
  $text .= '</div>';
  $text .= '<div class="mb-head-right">';
  $text .= '<ul class="mb-menu">';
  $text .= '<li><a href="' . osc_admin_base_url(true) . '?page=plugins&action=renderplugin&file=success/admin/configure.php"><i class="fa fa-wrench"></i><span>' . __('Configure', 'success') . '</span></a></li>';
  $text .= '</ul>';
  $text .= '</div>';
  $text .= '</div>';

  echo $text;
}



// ADMIN FOOTER
function scf_footer() {
  $pluginInfo = osc_plugin_get_info('success/index.php');
  $text  = '<div class="mb-footer">';
  $text .= '<a target="_blank" class="mb-developer" href="https://mb-themes.com"><img src="https://mb-themes.com/favicon.ico" alt="MB Themes" /> MB-Themes.com</a>';
  $text .= '<a target="_blank" href="' . $pluginInfo['support_uri'] . '"><i class="fa fa-bug"></i> ' . __('Report Bug', 'success') . '</a>';
  $text .= '<a target="_blank" href="https://forums.mb-themes.com/"><i class="fa fa-handshake-o"></i> ' . __('Support Forums', 'success') . '</a>';
  $text .= '<a target="_blank" class="mb-last" href="mailto:info@mb-themes.com"><i class="fa fa-envelope"></i> ' . __('Contact Us', 'success') . '</a>';
  $text .= '<span class="mb-version">v' . $pluginInfo['version'] . '</span>';
  $text .= '</div>';

  return $text;
}



// ADD MENU LINK TO PLUGIN LIST
function scf_admin_menu() {
echo '<h3><a href="#">Success Publish Box Plugin</a></h3>
<ul> 
  <li><a style="color:#2eacce;" href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/configure.php') . '">&raquo; ' . __('Configure', 'success') . '</a></li>
</ul>';
}


// ADD MENU TO PLUGINS MENU LIST
osc_add_hook('admin_menu','scf_admin_menu', 1);



// DISPLAY CONFIGURE LINK IN LIST OF PLUGINS
function scf_conf() {
  osc_admin_render_plugin( osc_plugin_path( dirname(__FILE__) ) . '/admin/configure.php' );
}

osc_add_hook( osc_plugin_path( __FILE__ ) . '_configure', 'scf_conf' );	


// CALL WHEN PLUGIN IS ACTIVATED - INSTALLED
osc_register_plugin(osc_plugin_path(__FILE__), 'scf_call_after_install');

// SHOW UNINSTALL LINK
osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'scf_call_after_uninstall');

?>