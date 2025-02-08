<?php
/*
  Plugin Name: All in One SEO Plugin
  Plugin URI: https://osclasspoint.com/osclass-plugins/backoffice/all-in-one-seo-plugin_i47
  Description: Powerful features to improve onpage & offpage SEO factors of your classifieds.
  Version: 3.3.8
  Author: MB Themes
  Author URI: https://osclasspoint.com
  Author Email: info@osclasspoint.com
  Short Name: all_in_one
  Plugin update URI: all-in-one-seo-plugin
  Support URI: https://forums.osclasspoint.com/all-in-one-seo-plugin/
  Product Key: 
*/

require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'model/ModelAisItem.php';
require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'model/ModelAisPage.php';
require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'model/ModelAisLink.php';
require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'model/ModelAisCategory.php';
require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'model/ModelAisLocation.php';
require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'sitemap_generator.php';
require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'email.php';
require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'functions.php';


function ais_call_after_install() {
  // CREATE BACKUP OF ROBOTS.TXT IF EXISTS
  if(file_exists($_SERVER['DOCUMENT_ROOT']."/robots.txt")) {
    $robots_original = file_get_contents($_SERVER['DOCUMENT_ROOT']."/robots.txt");
  } else {
    $robots_original = '';
  }
  
  // CREATE BACKUP OF .HTACCESS IF EXISTS
  if(file_exists($_SERVER['DOCUMENT_ROOT']."/.htaccess")) {
    $htaccess_original = file_get_contents($_SERVER['DOCUMENT_ROOT']."/.htaccess");
  } else {
    $htaccess_original = '';
  }
  
  ModelAisItem::newInstance()->import('all_in_one/model/struct.sql');


  osc_set_preference('use_default', 1, 'plugin-ais', 'INTEGER');
  osc_set_preference('title_extra', 1, 'plugin-ais', 'INTEGER');
  osc_set_preference('title_delimiter', '-', 'plugin-ais', 'STRING');
  osc_set_preference('description_delimiter', '-', 'plugin-ais', 'STRING');

  osc_set_preference('page_custom_text', '', 'plugin-ais', 'STRING');
  osc_set_preference('page_title_active', 'page_title,web_title', 'plugin-ais', 'STRING');
  osc_set_preference('page_meta_title_active', 'page_title,web_title', 'plugin-ais', 'STRING');
  osc_set_preference('page_meta_description_active', 'page_text', 'plugin-ais', 'STRING');

  osc_set_preference('search_custom_text', '', 'plugin-ais', 'STRING');
  osc_set_preference('search_title_active', 'search_pattern,region_name,city_name,category_name,page_number,web_title', 'plugin-ais', 'STRING');
  osc_set_preference('search_meta_title_active', 'search_pattern,region_name,city_name,category_name,page_number,web_title', 'plugin-ais', 'STRING');
  osc_set_preference('search_meta_description_active', 'region_meta_description,city_meta_description,category_meta_description', 'plugin-ais', 'STRING');

  osc_set_preference('item_custom_text', '', 'plugin-ais', 'STRING');
  osc_set_preference('item_title_active', 'item_title,web_title', 'plugin-ais', 'STRING');
  osc_set_preference('item_meta_title_active', 'item_title,web_title', 'plugin-ais', 'STRING');
  osc_set_preference('item_meta_description_active', 'item_category,item_region,item_city,item_description', 'plugin-ais', 'STRING');
  osc_set_preference('item_custom_meta', 1, 'plugin-ais', 'INTEGER');
  osc_set_preference('item_form', 1, 'plugin-ais', 'INTEGER');

  osc_set_preference('home_title-en_US', osc_page_title(), 'plugin-ais', 'STRING');
  osc_set_preference('home_meta_title-en_US', osc_page_title(), 'plugin-ais', 'STRING');
  osc_set_preference('home_meta_description-en_US', osc_page_description(), 'plugin-ais', 'STRING');

  osc_set_preference('backlinks_hook', 1, 'plugin-ais', 'INTEGER');

  osc_set_preference('sitemap_frequency', 1, 'plugin-ais', 'INTEGER');
  osc_set_preference('sitemap_items_include', 1, 'plugin-ais', 'INTEGER');
  osc_set_preference('sitemap_items_limit', 1000, 'plugin-ais', 'INTEGER');

  osc_set_preference('htaccess_custom', $htaccess_original, 'plugin-ais', 'STRING');
  osc_set_preference('htaccess_file', 0, 'plugin-ais', 'INTEGER');

  osc_set_preference('robots_custom', $robots_original, 'plugin-ais', 'STRING');
  osc_set_preference('robots_file', 0, 'plugin-ais', 'INTEGER');

  osc_set_preference('disable_meta_tags', 0, 'plugin-ais', 'INTEGER');
  osc_set_preference('disable_sitemap', 0, 'plugin-ais', 'INTEGER');

  //upload email templates
  $tpl = array();
  $locales = OSCLocale::newInstance()->listAllEnabled();
  foreach($locales as $l) {
    $tpl[$l['pk_c_code']]['s_title'] = '{WEB_TITLE} - There is problem with backlink placed on your site';
    $tpl[$l['pk_c_code']]['s_text'] = '<p>Dear Partner!</p> <p>Let us inform you, that we were not able to find link referring to our site: <strong>{YOUR_URL}</strong> on your website <strong>{LINK_URL}</strong>.</p> <p>Please add our link to your site or our cooperation in backlink building will be cancelled. If reason of removing link is maintenance or similar, please inform us about this.</p> <p>Regards, <br />{WEB_TITLE}</p>';
  } 
  
  Page::newInstance()->insert(array('s_internal_name' => 'ais_rec_link', 'b_indelible' => '1'), $tpl);
}



function ais_call_after_uninstall() {
  ModelAisItem::newInstance()->uninstall();
  ModelAisPage::newInstance()->uninstall();
  ModelAisLink::newInstance()->uninstall();
  ModelAisCategory::newInstance()->uninstall();


  //get list of primary keys of static pages (emails) that should be deleted on uninstall
  $pages = ModelAisPage::newInstance()->getEmailPages();  
  foreach($pages as $page) {
    Page::newInstance()->deleteByPrimaryKey($page['pk_i_id']);
  }
}



// TITLE GENERATION
function ais_title_filter() {
  if(ais_param('disable_meta_tags') == 1) {
    return meta_title();
  }
  
  $location = Rewrite::newInstance()->get_location();
  $section  = Rewrite::newInstance()->get_section();

  $use_default = ais_param('use_default');
  $page_title_active = ais_param('page_title_active');
  $search_title_active = ais_param('search_title_active');
  $item_title_active = ais_param('item_title_active');

  $delimiter = ais_title_delimiter();
  $tag = '';
  
  switch ($location) {

    // Listing page and pages related to listings
    case ('item'):
      switch ($section) {
        case 'item_add':      $tag = __('Publish a listing', 'all_in_one'); break;
        case 'item_edit':     $tag = __('Edit your listing', 'all_in_one'); break;
        case 'send_friend':   $tag = __('Send to a friend', 'all_in_one'); break;
        case 'contact':       $tag = __('Contact seller', 'all_in_one'); break;
        default:              $tag = ais_create_tag($item_title_active, $delimiter); break;
      }

      break;
    

    // Static page
    case('page'):
      $tag = ais_create_tag($page_title_active, $delimiter);
      break;
    

    // Error page
    case('error'):
      $tag = __('Page not found', 'all_in_one');
      break;
    

    // Search, Category & Location page
    case('search'):
      $country = ais_search_country();
      $region = osc_search_region();
      $city = osc_search_city();
      $pattern = osc_search_pattern();
      $category = osc_search_category_id();

      if(!isset($category[0]) && $country == '' && $region == '' && $city == '' && $pattern == '') {
        $tag = __('All listings', 'all_in_one');
      } else {
        $tag = ais_create_tag($search_title_active, $delimiter);
      }

      break;

    
    // Login page
    case('login'):
      switch ($section) {
        case('recover'):    $tag = __('Recover your password', 'all_in_one'); break;
        default:            $tag = __('Login into your account', 'all_in_one');break;
      }

      break;
    

    // Registration page
    case('register'):
      $tag = __('Create a new account', 'all_in_one');
      break;
    

    // User page and pages related to user
    case('user'):
      switch ($section) {
        case('dashboard'):        $tag = __('Dashboard', 'all_in_one'); break;
        case('items'):            $tag = __('Manage my listings', 'all_in_one'); break;
        case('alerts'):           $tag = __('Manage my alerts', 'all_in_one'); break;
        case('profile'):          $tag = __('Update my profile', 'all_in_one'); break;
        case('pub_profile'):      $tag = __('Public profile of', 'all_in_one') . ' ' . ucfirst(osc_user_name()); break;
        case('change_email'):     $tag = __('Change my email', 'all_in_one'); break;
        case('change_password'):  $tag = __('Change my password', 'all_in_one'); break;
        case('forgot'):           $tag = __('Recover my password', 'all_in_one'); break;
      }

      break;
    

    // Contact page
    case('contact'):
      $tag = __('Contact us', 'all_in_one');
      break;


    // Home page
    case(''):
      $tag = (ais_param('home_title-' . osc_current_user_locale()) <> '' ? ais_param('home_title-' . osc_current_user_locale()) : osc_page_title());
      break;


    // INSTANT MESSENGER
    case('im'):
      if(function_exists('im_meta_title')) {
        $tag = im_meta_title($default);
      } else {
        $tag = meta_title();
      }

      break;


    // Plugins
    case('blg'):
    case('bpr'):
    case('custom'):
      $tag = meta_title();
      break;


    // Other, not specified pages, custom plugin pages
    default:
      $tag = osc_page_title();
      break;
  }

  if(trim($tag) == '' && $use_default == 1) {
    return osc_page_title();
  } else {
    return (trim((string)$tag) != '' ? $tag : $original_tag);
  }
}



// META TITLE GENERATION
function ais_meta_title_filter($original_tag) {
  if(ais_param('disable_meta_tags') == 1) {
    return $original_tag;
  }
  
  $tag = '';
  $location = Rewrite::newInstance()->get_location();
  $section  = Rewrite::newInstance()->get_section();

  $use_default = ais_param('use_default');
  $page_meta_title_active = ais_param('page_meta_title_active');
  $search_meta_title_active = ais_param('search_meta_title_active');
  $item_meta_title_active = ais_param('item_meta_title_active');

  $delimiter = ais_title_delimiter();

  switch ($location) {

    // Listing page and pages related to listings
    case ('item'):
      switch ($section) {
        case 'item_add':      $tag = __('Publish a listing', 'all_in_one'); break;
        case 'item_edit':     $tag = __('Edit your listing', 'all_in_one'); break;
        case 'send_friend':   $tag = __('Send to a friend', 'all_in_one'); break;
        case 'contact':       $tag = __('Contact seller', 'all_in_one'); break;
        default:              $tag = ais_create_tag($item_meta_title_active, $delimiter); break;
      }

      break;
    

    // Static page
    case('page'):
      $tag = ais_create_tag($page_meta_title_active, $delimiter);
      break;
    

    // Error page
    case('error'):
      $tag = __('Page not found', 'all_in_one');
      break;
    

    // Search, Category & Location page
    case('search'):
      $country = ais_search_country();
      $region = osc_search_region();
      $city = osc_search_city();
      $pattern = osc_search_pattern();
      $category = osc_search_category_id();

      if(!isset($category[0]) && $country == '' && $region == '' && $city == '' && $pattern == '') {
        $tag = __('All listings', 'all_in_one');
      } else {
        $tag = ais_create_tag($search_meta_title_active, $delimiter);
      }

      break;

    
    // Login page
    case('login'):
      switch ($section) {
        case('recover'):    $tag = __('Recover your password', 'all_in_one'); break;
        default:            $tag = __('Login into your account', 'all_in_one');break;
      }

      break;
    

    // Registration page
    case('register'):
      $tag = __('Create a new account', 'all_in_one');
      break;
    

    // User page and pages related to user
    case('user'):
      switch ($section) {
        case('dashboard'):        $tag = __('Dashboard', 'all_in_one'); break;
        case('items'):            $tag = __('Manage my listings', 'all_in_one'); break;
        case('alerts'):           $tag = __('Manage my alerts', 'all_in_one'); break;
        case('profile'):          $tag = __('Update my profile', 'all_in_one'); break;
        case('pub_profile'):      $tag = __('Public profile of', 'all_in_one') . ' ' . ucfirst(osc_user_name()); break;
        case('change_email'):     $tag = __('Change my email', 'all_in_one'); break;
        case('change_password'):  $tag = __('Change my password', 'all_in_one'); break;
        case('forgot'):           $tag = __('Recover my password', 'all_in_one'); break;
      }

      break;
    

    // Contact page
    case('contact'):
      $tag = __('Contact us', 'all_in_one');
      break;
    

    // Home page
    case(''):
      $tag = (ais_param('home_meta_title-' . osc_current_user_locale()) <> '' ? ais_param('home_meta_title-' . osc_current_user_locale()) : osc_page_title());
      break;


    // Plugins
    case('blg'):
    case('bpr'):
    case('custom'):
      $tag = $original_tag;
      break;
      

    // Other, not specified pages, custom plugin pages
    default:
      if(trim($original_tag) != '') {
        return $original_tag;
      }
      
      $tag = osc_page_title();
      break;
  }


  if(trim($tag) == '' && $use_default == 1) {
    $tag = $original_tag;
  }


  if(ais_param('title_multilang') == 1) { 
    $locales = OSCLocale::newInstance()->listAllEnabled();

    if(count($locales) > 1) {
      $user_locale = OSCLocale::newInstance()->findByCode(osc_current_user_locale());
      $tag .= ' (' . $user_locale[0]['s_short_name'] . ')';
    }
  }

  return (trim((string)$tag) != '' ? $tag : $original_tag);
}



// META DESCRIPTION GENERATION
function ais_meta_description_filter($original_tag) {
  if(ais_param('disable_meta_tags') == 1) {
    return $original_tag;
  }
  
  $tag = '';
  $location = Rewrite::newInstance()->get_location();
  $section  = Rewrite::newInstance()->get_section();
  $use_default = ais_param('use_default');
  $page_meta_description_active = ais_param('page_meta_description_active');
  $search_meta_description_active = ais_param('search_meta_description_active');
  $item_meta_description_active = ais_param('item_meta_description_active');

  $delimiter = ais_description_delimiter();

  switch ($location) {

    // Listing page and pages related to listings
    case ('item'):
      switch ($section) {
        case 'item_add':      $tag = __('Create listing and sell your items faster with our classifieds.', 'all_in_one'); break;
        case 'send_friend':   $tag = __('Share listing with your friend.', 'all_in_one'); break;
        case 'contact':       $tag = __('In case of questions, feel free to contact seller of this listing.', 'all_in_one'); break;
        default:              $tag = ais_create_tag($item_meta_description_active, $delimiter); break;
      }

      break;
    

    // User page and pages related to user
    case('user'):
      switch ($section) {
        case('pub_profile'):  $tag = __('Browse listings and information of seller', 'all_in_one') . ' ' . ucfirst(osc_user_name()); break;
      }

      break;


    // Static page
    case('page'):
      $tag = ais_create_tag($page_meta_description_active, $delimiter);
      break;
    

    // Error page
    case('error'):
      $tag = __('Sorry, we were not able to find this page.', 'all_in_one');
      break;
    

    // Search, Category & Location page
    case('search'):
      $country = ais_search_country();
      $region = osc_search_region();
      $city = osc_search_city();
      $pattern = osc_search_pattern();
      $category = osc_search_category_id();

      if(!isset($category[0]) && $country == '' && $region == '' && $city == '' && $pattern == '') {
        $tag = osc_page_description();
      } else {
        $tag = ais_create_tag($search_meta_description_active, $delimiter);
      }

      break;

    
    // Login page
    case('login'):
      switch ($section) {
        case('recover'):    $tag = __('In case you have lost your password, you get get new one here', 'all_in_one'); break;
        default:            $tag = __('Sign in to your user account and use benefits of registered users.', 'all_in_one'); break;
      }

      break;
    

    // Registration page
    case('register'):
      $tag = __('Sign in to your user account and use benefits of registered users.', 'all_in_one');
      break;


    // Contact page
    case('contact'):
      $tag = __('In case you have question about our classifieds or you want to connect with our team, feel free to contact us anytime.', 'all_in_one');
      break;
    

    // Home page
    case(''):
      $tag = (ais_param('home_meta_description-' . osc_current_user_locale()) <> '' ? ais_param('home_meta_description-' . osc_current_user_locale()) : osc_page_description());
      break;


    // Plugins
    case('blg'):
    case('bpr'):
    case('custom'):
      $tag = $original_tag;
      break;

    // Other, not specified pages, custom plugin pages
    default:
      if(trim($original_tag) != '') {
        return $original_tag;
      }
      
      $tag = osc_page_description();
      break;
  }


  if(trim((string)$original_tag) == '' && $use_default == 1) {
    return $original_tag;
  } else {
    return (trim((string)$tag) != '' ? $tag : $original_tag);
  }
}



// META KEYWORDS GENERATION
function ais_keywords_filter($tag) {
  if(ais_param('disable_meta_tags') == 1) {
    return $tag;
  }
  
  if(ais_param('disable_keywords') == 1) {
    return '';
  } else {
    return $tag;
  }
}



// DISPLAY CONFIGURE LINK IN PLUGIN LIST
function ais_conf() {
  osc_admin_render_plugin(osc_plugin_path(dirname(__FILE__)) . '/admin/global.php') ;
}



// PLUGIN TITLE IN OC-ADMIN
function ais_plugin_title($title){
  $file = explode('/', Params::getParam('file'));
  if($file[0] == 'all_in_one'){
    $title = 'All in One SEO Plugin';         
  }

  return $title;
}



// ADMIN MENU
function ais_menu($title = NULL) {
  echo '<link href="' . osc_base_url() . 'oc-content/plugins/all_in_one/css/admin.css" rel="stylesheet" type="text/css" />';
  echo '<link href="' . osc_base_url() . 'oc-content/plugins/all_in_one/css/bootstrap-switch.css" rel="stylesheet" type="text/css" />';
  echo '<link href="' . osc_base_url() . 'oc-content/plugins/all_in_one/css/tipped.css" rel="stylesheet" type="text/css" />';
  echo '<link href="//fonts.googleapis.com/css?family=Open+Sans:300,600&amp;subset=latin,latin-ext" rel="stylesheet" type="text/css" />';
  echo '<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />';
  echo '<script src="' . osc_base_url() . 'oc-content/plugins/all_in_one/js/admin.js"></script>';
  echo '<script src="' . osc_base_url() . 'oc-content/plugins/all_in_one/js/tipped.js"></script>';
  echo '<script src="' . osc_base_url() . 'oc-content/plugins/all_in_one/js/bootstrap-switch.js"></script>';

  if($title == '') { $title = __('Configure', 'all_in_one'); }

  $text  = '<div class="mb-head">';
  $text .= '<div class="mb-head-left">';
  $text .= '<h1>' . $title . '</h1>';
  $text .= '<h2>All in One SEO Plugin</h2>';
  $text .= '</div>';
  $text .= '<div class="mb-head-right">';
  $text .= '<ul class="mb-menu">';
  $text .= '<li><a href="' . osc_admin_base_url() . '?page=plugins&action=renderplugin&file=all_in_one/admin/global.php"><i class="fa fa-wrench"></i><span>' . __('Global', 'all_in_one') . '</span></a></li>';
  $text .= '<li><a href="' . osc_admin_base_url() . '?page=plugins&action=renderplugin&file=all_in_one/admin/items.php"><i class="fa fa-list"></i><span>' . __('Items', 'all_in_one') . '</span></a></li>';
  $text .= '<li><a href="' . osc_admin_base_url() . '?page=plugins&action=renderplugin&file=all_in_one/admin/search.php"><i class="fa fa-search"></i><span>' . __('Search', 'all_in_one') . '</span></a></li>';
  $text .= '<li><a href="' . osc_admin_base_url() . '?page=plugins&action=renderplugin&file=all_in_one/admin/categories.php"><i class="fa fa-folder-o"></i><span>' . __('Categories', 'all_in_one') . '</span></a></li>';
  $text .= '<li><a href="' . osc_admin_base_url() . '?page=plugins&action=renderplugin&file=all_in_one/admin/locations.php"><i class="fa fa-map-o"></i><span>' . __('Locations', 'all_in_one') . '</span></a></li>';
  $text .= '<li><a href="' . osc_admin_base_url() . '?page=plugins&action=renderplugin&file=all_in_one/admin/pages.php"><i class="fa fa-file-o"></i><span>' . __('Pages', 'all_in_one') . '</span></a></li>';
  $text .= '<li><a href="' . osc_admin_base_url() . '?page=plugins&action=renderplugin&file=all_in_one/admin/back_links.php"><i class="fa fa-exchange"></i><span>' . __('Back Links', 'all_in_one') . '</span></a></li>';
  $text .= '<li><a href="' . osc_admin_base_url() . '?page=plugins&action=renderplugin&file=all_in_one/admin/reciprocal_links.php"><i class="fa fa-random"></i><span>' . __('Reciprocal Links', 'all_in_one') . '</span></a></li>';
  $text .= '<li><a href="' . osc_admin_base_url() . '?page=plugins&action=renderplugin&file=all_in_one/admin/sitemap.php"><i class="fa fa-sitemap"></i><span>' . __('Sitemap', 'all_in_one') . '</span></a></li>';
  $text .= '<li><a href="' . osc_admin_base_url() . '?page=plugins&action=renderplugin&file=all_in_one/admin/htaccess.php"><i class="fa fa-code"></i><span>' . __('.htaccess', 'all_in_one') . '</span></a></li>';
  $text .= '<li><a href="' . osc_admin_base_url() . '?page=plugins&action=renderplugin&file=all_in_one/admin/robots.php"><i class="fa fa-filter"></i><span>' . __('Robots', 'all_in_one') . '</span></a></li>';
  $text .= '</ul>';
  $text .= '</div>';
  $text .= '</div>';

  echo $text;
}



// SUB MENU IN ADMIN SIDEBAR
function ais_admin_menu() {
echo '<h3><a href="#">All in One SEO</a></h3>
<ul> 
  <li><a style="color:#2eacce;" href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/global.php') . '">&raquo; ' . __('Global', 'all_in_one') . '</a></li>
  <li><a style="color:#2eacce;" href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/items.php') . '">&raquo; ' . __('Items', 'all_in_one') . '</a></li>
  <li><a style="color:#2eacce;" href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/search.php') . '">&raquo; ' . __('Search', 'all_in_one') . '</a></li>
  <li><a style="color:#2eacce;" href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/categories.php') . '">&raquo; ' . __('Categories', 'all_in_one') . '</a></li>
  <li><a style="color:#2eacce;" href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/locations.php') . '">&raquo; ' . __('Locations', 'all_in_one') . '</a></li>
  <li><a style="color:#2eacce;" href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/pages.php') . '">&raquo; ' . __('Pages', 'all_in_one') . '</a></li>
  <li><a style="color:#2eacce;" href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/back_links.php') . '">&raquo; ' . __('Back Links', 'all_in_one') . '</a></li>
  <li><a style="color:#2eacce;" href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/reciprocal_links.php') . '">&raquo; ' . __('Reciprocal Links', 'all_in_one') . '</a></li>
  <li><a style="color:#2eacce;" href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/sitemap.php') . '">&raquo; ' . __('Sitemap', 'all_in_one') . '</a></li>
  <li><a style="color:#2eacce;" href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/htaccess.php') . '">&raquo; ' . __('htaccess', 'all_in_one') . '</a></li>
  <li><a style="color:#2eacce;" href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/robots.php') . '">&raquo; ' . __('Robots', 'all_in_one') . '</a></li>
</ul>';
}



// ADMIN FOOTER
function ais_footer() {
  $pluginInfo = osc_plugin_get_info('all_in_one/index.php');
  $text  = '<div class="mb-footer">';
  $text .= '<a target="_blank" class="mb-developer" href="https://osclasspoint.com"><img src="https://osclasspoint.com/favicon.ico" alt="OsclassPoint Market" /> OsclassPoint Market</a>';
  $text .= '<a target="_blank" href="' . $pluginInfo['support_uri'] . '"><i class="fa fa-bug"></i> ' . __('Report Bug', 'all_in_one') . '</a>';
  $text .= '<a target="_blank" href="https://forums.osclasspoint.com/"><i class="fa fa-handshake-o"></i> ' . __('Support Forums', 'all_in_one') . '</a>';
  $text .= '<a target="_blank" class="mb-last" href="mailto:info@osclasspoint.com"><i class="fa fa-envelope"></i> ' . __('Contact Us', 'all_in_one') . '</a>';
  $text .= '<span class="mb-version">v' . $pluginInfo['version'] . '</span>';
  $text .= '</div>';

  return $text;
}



// ITEM POST FORM
function ais_item_post_form($category_id = '') {
  $item_custom_meta = ais_param('item_custom_meta');
  $item_form = ais_param('item_form');

  if($item_custom_meta == 1) {
    if(($item_form == 1)  ||  (($item_form == 2 && osc_is_web_user_logged_in()) || osc_is_admin_user_logged_in())) {
      include_once 'form/item_post_edit.php';
    }
  }
}



// ITEM POST INSERT META
function ais_item_post_insert($item) {
  ModelAisItem::newInstance()->insertItemMeta($item['pk_i_id'], Params::getParam('ais_meta_title'), Params::getParam('ais_meta_description'));
}



// ITEM EDIT FORM
function ais_item_edit_form($catId = null, $item_id = null) {
  $item_custom_meta = ais_param('item_custom_meta');
  $item_form = ais_param('item_form');

  if($item_custom_meta == 1) {
    if(($item_form == 1)  ||  (($item_form == 2 && osc_is_web_user_logged_in()) || osc_is_admin_user_logged_in())) {
      include_once 'form/item_post_edit.php';
    }
  }
}



// ITEM EDIT META UPDATE
function ais_item_edit_update($item) {
  $detail = ModelAisItem::newInstance()->findByItemId($item['pk_i_id']);

  if(isset($detail['fk_i_item_id'])) {
    ModelAisItem::newInstance()->updateItemMeta($item['pk_i_id'], Params::getParam('ais_meta_title'), Params::getParam('ais_meta_description'));
  } else {
    ModelAisItem::newInstance()->insertItemMeta($item['pk_i_id'], Params::getParam('ais_meta_title'), Params::getParam('ais_meta_description'));
  } 
}



// KEEP VALUES OF INPUTS ON RELOAD
function ais_item_meta_preserve() {
  Session::newInstance()->_setForm('ais_meta_title', Params::getParam('ais_meta_title'));
  Session::newInstance()->_setForm('ais_meta_description', Params::getParam('ais_meta_description'));
  
  // keep values on session
  Session::newInstance()->_keepForm('ais_meta_title');
  Session::newInstance()->_keepForm('ais_meta_description');
}



// ON ITEM DELETE REMOVE ALSO META
function ais_item_delete_meta($item_id) {
  ModelAisItem::newInstance()->deleteItemMeta($item_id) ;
}



// HOOK BACKLINKS TO FOOTER (IF AUTOHOOK ENABLED)
function ais_generate_link($title, $href, $rel) {
  if($rel == 1) { 
    $nofollow = ' rel="nofollow" '; 
  } else { 
    $nofollow = ' '; 
  }

  $text = '<a href="' . $href . '" title = "' . $title . '" target="_blank"' . $nofollow . '>' . $title . '</a>';
  return isset($text) ? $text : '';
}


// CREATE BACK LINKS BLOCK
function ais_backlinks() {
  $text = '';
  $links = ModelAisLink::newInstance()->getAllBackLinks();

  foreach($links as $l) {
    if($l['i_footer'] == 1) {
      if($text <> '') { $text .= ' | '; }
      $text .= ais_generate_link($l['s_title'], $l['s_url'], $l['i_nofollow']);
    }
  }
  
  echo '<div id="footer-links" class="ais-backlinks" style="float:left;width:100%;clear:both;">' . $text . '</div>';
}

osc_add_hook('footer', function() {
  if(ais_param('backlinks_hook') == 1) {  
    ais_backlinks();
  }
});



$myPlugin = file(osc_content_path() . 'plugins/all_in_one/index.php');
if(strpos($myPlugin[2],'All') == false && !osc_is_admin_user_logged_in()) {header('Location:'.osc_base_url());}




// HOOKS & FILTERS
osc_add_filter('meta_title_filter', 'ais_meta_title_filter');                           // meta title filter
osc_add_filter('meta_description_filter', 'ais_meta_description_filter');               // meta description filter
osc_add_filter('meta_keywords_filter', 'ais_keywords_filter');                          // meta keywords filter

osc_add_hook('item_form', 'ais_item_post_form');
osc_add_hook('posted_item', 'ais_item_post_insert');
osc_add_hook('item_edit', 'ais_item_edit_form');
osc_add_hook('edited_item', 'ais_item_edit_update');
osc_add_hook('delete_item', 'ais_item_delete_meta');
osc_add_hook('pre_item_post', 'ais_item_meta_preserve') ;

osc_add_hook('admin_menu','ais_admin_menu', '1');                                       // admin menu in sidebar (oc-admin)
osc_add_filter('custom_plugin_title', 'ais_plugin_title');                              // plugin title in oc-admin
osc_register_plugin(osc_plugin_path(__FILE__), 'ais_call_after_install');               // activate plugin
osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'ais_call_after_uninstall');     // uninstall link
osc_add_hook(osc_plugin_path(__FILE__) . '_configure', 'ais_conf');                 // configure link


?>