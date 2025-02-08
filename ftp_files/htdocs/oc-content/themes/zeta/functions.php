<?php
define('ZETA_THEME_VERSION', '1.0.5');
define('USER_MENU_ICONS', 1);
define('PRELOAD_CATEGORIES', true);
define('THEME_COMPATIBLE_WITH_OSCLASS_HOOKS', 820);       // Compatibility with new hooks up to version

if(!defined('OC_ADMIN')) { 
  define('THEME_ITEM_TABLE', 't_item_zeta');
  define('THEME_CATEGORY_TABLE', 't_category_zeta'); 
}

define('ICON_ALL', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="32" height="32" class="all"><path d="M116.65 219.35a15.68 15.68 0 0 0 22.65 0l96.75-99.83c28.15-29 26.5-77.1-4.91-103.88C203.75-7.7 163-3.5 137.86 22.44L128 32.58l-9.85-10.14C93.05-3.5 52.25-7.7 24.86 15.64c-31.41 26.78-33 74.85-5 103.88zm143.92 100.49h-48l-7.08-14.24a27.39 27.39 0 0 0-25.66-17.78h-71.71a27.39 27.39 0 0 0-25.66 17.78l-7 14.24h-48A27.45 27.45 0 0 0 0 347.3v137.25A27.44 27.44 0 0 0 27.43 512h233.14A27.45 27.45 0 0 0 288 484.55V347.3a27.45 27.45 0 0 0-27.43-27.46zM144 468a52 52 0 1 1 52-52 52 52 0 0 1-52 52zm355.4-115.9h-60.58l22.36-50.75c2.1-6.65-3.93-13.21-12.18-13.21h-75.59c-6.3 0-11.66 3.9-12.5 9.1l-16.8 106.93c-1 6.3 4.88 11.89 12.5 11.89h62.31l-24.2 83c-1.89 6.65 4.2 12.9 12.23 12.9a13.26 13.26 0 0 0 10.92-5.25l92.4-138.91c4.88-6.91-1.16-15.7-10.87-15.7zM478.08.33L329.51 23.17C314.87 25.42 304 38.92 304 54.83V161.6a83.25 83.25 0 0 0-16-1.7c-35.35 0-64 21.48-64 48s28.65 48 64 48c35.2 0 63.73-21.32 64-47.66V99.66l112-17.22v47.18a83.25 83.25 0 0 0-16-1.7c-35.35 0-64 21.48-64 48s28.65 48 64 48c35.2 0 63.73-21.32 64-47.66V32c0-19.48-16-34.42-33.92-31.67z"></path></svg>');
define('ICON_SCROLL_PREV', '<svg class="icon prev" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512" width="20" height="20"><path d="M4.2 247.5L151 99.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17L69.3 256l118.5 119.7c4.7 4.7 4.7 12.3 0 17L168 412.5c-4.7 4.7-12.3 4.7-17 0L4.2 264.5c-4.7-4.7-4.7-12.3 0-17z"/></svg>');
define('ICON_SCROLL_NEXT', '<svg class="icon next" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512" width="20" height="20"><path d="M187.8 264.5L41 412.5c-4.7 4.7-12.3 4.7-17 0L4.2 392.7c-4.7-4.7-4.7-12.3 0-17L122.7 256 4.2 136.3c-4.7-4.7-4.7-12.3 0-17L24 99.5c4.7-4.7 12.3-4.7 17 0l146.8 148c4.7 4.7 4.7 12.3 0 17z"/></svg>');


require_once osc_base_path() . zet_oc_content_folder() . '/themes/zeta/model/ModelZET.php';


// GET THEME INFO FROM INDEX.PHP
function zet_theme_info() {
  return WebThemes::newInstance()->loadThemeInfo('zeta');
}



// HOME PAGE SEARCH CONFIG
function zet_home_search_config() {
  $config = array();
  $data_json = trim((string)zet_param('home_search_cat'));
  
  if($data_json != '') {
    $data = @json_decode($data_json, true);
  
    if(is_array($data) && count($data) > 0) {
      foreach($data as $dline) {
        $cat_id = (isset($dline['catId']) && $dline['catId'] != '') ? $dline['catId'] : -99;
        $filters = (isset($dline['filters']) && $dline['filters'] != '') ? $dline['filters'] : '';
        $filters = array_filter(array_unique(array_map('strtolower', array_map('trim', explode(',', $filters)))));

        if($cat_id != -99 && !empty($filters)) {
          $cat_id = (int)($cat_id == -9 ? 0 : $cat_id);    // Replace "All categories" with 0 id
          
          if(!in_array($cat_id, array_keys($config))) {
            $config[$cat_id] = $filters;
          }
        }
      }
    }
  }
  
  // If config is empty, return default setup
  if(empty($config)) {
    return array(
      1 => array('category', 'location', 'radius', 'condition'),
      2 => array('seller', 'condition', 'location', 'radius', 'price_max'),
      4 => array('category', 'location', 'transaction', 'radius', 'price_max'),
      8 => array('category', 'location', 'pattern', 'price_min'),
      0 => array('location', 'radius', 'pattern')
    );
  }
  
  return $config;
}


// GET FOOTER SOCIAL LINK
function zet_get_social_link($type) {
  $url = '';
  
  if(zet_param('footer_social_define') == 1) {
    switch($type) {
      case 'whatsapp': $url = zet_param('footer_social_whatsapp'); break;
      case 'facebook': $url = zet_param('footer_social_facebook'); break;
      case 'pinterest': $url = zet_param('footer_social_pinterest'); break;
      case 'instagram': $url = zet_param('footer_social_instagram'); break;
      case 'x': $url = zet_param('footer_social_x'); break;
      case 'linkedin': $url = zet_param('footer_social_linkedin'); break;
    }
    
  } else {
    $share_url = urlencode(osc_is_ad_page() ? osc_item_url() : osc_base_url());

    switch($type) {
      case 'whatsapp': $url = 'whatsapp://send?text=' . $share_url; break;
      case 'facebook': $url = 'https://www.facebook.com/sharer/sharer.php?u=' . $share_url; break;
      case 'pinterest': $url = 'https://pinterest.com/pin/create/button/?url=' . $share_url . '&media=' . zet_logo(true) . '&description='; break;
      case 'instagram': $url = zet_param('footer_social_instagram'); break;
      case 'x': $url = 'https://twitter.com/home?status=' . $share_url . '%20-%20' . urlencode(zet_param('site_name')); break;
      case 'linkedin': $url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $share_url . '&title=' . urlencode(zet_param('site_name')) . '&summary=&source='; break;
    }
  }

  if(trim($url) != '') {
    return $url;
  }
  
  return false;  
}



// ADD ANY LOCATION INTO COOKIES
function zet_add_location_to_recent($form = array()) {
  if(!is_array($form) || $form == '') {
    $form = array(); 
  }
  
  $city_id = @array_values(array_filter(array(@$form['fk_i_city_id'],Params::getParam('fk_i_city_id'),Params::getParam('cityId'),Params::getParam('sCity'),Params::getParam('city'))))[0];
  $region_id = @array_values(array_filter(array(@$form['fk_i_region_id'],Params::getParam('fk_i_region_id'),Params::getParam('regionId'),Params::getParam('sRegion'),Params::getParam('region'))))[0];
  $country_code = @array_values(array_filter(array(@$form['fk_c_country_code'],Params::getParam('fk_c_country_code'),Params::getParam('countryId'),Params::getParam('sCountry'),Params::getParam('country'))))[0];
  
  $city = array();
  if($city_id != '') {
    if($city_id > 0) {
      $city = osc_get_city_row($city_id);
    } else {
      $city = City::newInstance()->findByName($city_id);
    }
  }
  
  $region = array();
  if(!isset($city['pk_i_id']) && $region_id != '') {
    if($region_id > 0) {
      $region = osc_get_region_row($region_id);
    } else {
      $region = Region::newInstance()->findByName($region_id);
    }
  }

  $country = array();
  if(!isset($city['pk_i_id']) && !isset($region['pk_i_id']) && $country_code != '') {
    if(strlen($country_code) == 2) {
      $country = osc_get_country_row($country_code);
    } else {
      $country = Country::newInstance()->findByName($country_code);
    }      
  }
  
  $location = array();
  
  if(isset($city['pk_i_id'])) {
    $location['s_name'] = $city['s_name'];
    $location['s_name_native'] = @$city['s_name_native'];
    $location['s_country'] = '';
    $location['s_region'] = '';
    $location['s_city'] = $city['s_name'];
    $location['fk_i_city_id'] = $city['pk_i_id'];
    $location['fk_i_region_id'] = $city['fk_i_region_id'];
    $location['fk_c_country_code'] = $city['fk_c_country_code'];
    $location['s_slug'] = $city['s_slug'];
    $location['d_coord_lat'] = $city['d_coord_lat'];
    $location['d_coord_long'] = $city['d_coord_long'];
    
  } else if(isset($region['pk_i_id'])) {
    $location['s_name'] = $region['s_name'];
    $location['s_name_native'] = @$region['s_name_native'];
    $location['s_country'] = '';
    $location['s_region'] = $region['s_name'];
    $location['s_city'] = '';
    $location['fk_i_city_id'] = NULL;
    $location['fk_i_region_id'] = $region['pk_i_id'];
    $location['s_slug'] = $region['s_slug'];
    $location['fk_c_country_code'] = $region['fk_c_country_code'];
    $location['d_coord_lat'] = NULL;
    $location['d_coord_long'] = NULL;
    
  } else if(isset($country['pk_c_code'])) {
    $location['s_name'] = $country['s_name'];
    $location['s_name_native'] = @$country['s_name_native'];
    $location['s_country'] = $country['s_name'];
    $location['s_region'] = '';
    $location['s_city'] = '';
    $location['fk_i_city_id'] = NULL;
    $location['fk_i_region_id'] = NULL;
    $location['fk_c_country_code'] = $country['pk_c_code'];
    $location['s_slug'] = $country['s_slug'];
    $location['d_coord_lat'] = NULL;
    $location['d_coord_long'] = NULL;
    
  } else {
    return false; 
  }
  
  $location['success']= true;
  $location['message'] = 'APPLICATION';
  $location['s_location'] = $location['s_name'];
  $location['dt_date'] = date('Y-m-d H:i:s');

  zet_location_to_recent($location);
}

osc_add_hook('header', function() { 
  if(osc_is_search_page()) {
    zet_add_location_to_recent();
  }
});

osc_add_hook('posted_item', 'zet_add_location_to_recent');
osc_add_hook('edited_item', 'zet_add_location_to_recent');
osc_add_hook('user_edit_completed', 'zet_add_location_to_recent');

  
// GET FIRST NAME OF FULL NAME
function zet_user_first_name($name) {
  $name_arr = array_filter(explode(' ', trim((string)$name)));
  $text = '';
  
  if(isset($name_arr[0])) {
    if(strlen($name_arr[0]) > 2) {
      return $name_arr[0];
    } else {
      $text = $name_arr[0];
    }
  }
  
  if(isset($name_arr[1])) {
    $text .= ' ' . $name_arr[1];
  }
  
  return $text;
}


// CHECK IF DARK MODE SHOULD BE ENABLED
function zet_is_dark_mode() {
  if(zet_param('enable_dark_mode') == 1) {
    $mode = (zet_param('default_mode') == '' ? 'LIGHT' : zet_param('default_mode'));
    
    if(isset($_COOKIE['zetLightDarkMode']) && $_COOKIE['zetLightDarkMode'] != '') {
      $mode = $_COOKIE['zetLightDarkMode'];
    }
    
    if($mode == 'DARK') {
      return true;
    }
  }
  
  return false;
}



// GET DARK MODE STRING
function zet_light_dark_mode() {
  return zet_is_dark_mode() ? 'dark' : 'light';
}



// GET USERS LIST
function zet_get_users($type = '', $limit = 12) {
  if($type == 'by_items') {
    $order_col = 'i_items';
    $order_type = 'DESC';
  } else if($type == 'by_reg_date') {
    $order_col = 'dt_reg_date';
    $order_type = 'DESC';
  }
  
  $limit = ($limit > 0 ? $limit : 12);
  
  $users = ModelZET::newInstance()->getUsers($limit, $order_col, $order_type, true);
  
  if(is_array($users) && count($users) > 0) {
    foreach($users as $user) {
      if(!View::newInstance()->_exists('zet_user_' . $user['pk_i_id'])) {
        View::newInstance()->_exportVariableToView('zet_user_' . $user['pk_i_id'], $user);
      }
    }
  }
  
  return $users;
}


// PREPARE LATEST SEARCHES WITH LENGTH CRITERIA
function zet_get_latest_searches($limit = 20) {
  if(View::newInstance()->_exists('latest_searches')) {
    View::newInstance()->_reset('latest_searches');
    return View::newInstance()->_get('latest_searches');
  }
  
  $data = osc_get_latest_searches($limit*2);
  $output = array();
  $stop_words = strtolower(@osc_get_current_user_locale()['s_stop_words']);
  $stop_words = array_filter(array_map('trim', explode(',', $stop_words)));
  
  if(is_array($data) && count($data) > 0) {
    foreach($data as $d) {
      if(mb_strlen($d['s_search']) >= 3 && mb_strlen($d['s_search']) <= 30 && !in_array(strtolower($d['s_search']), $stop_words)) {
        $output[] = $d;
      }
    }
  }
  
  if(count($output) > $limit) {
    $output = array_slice($output, 0, $limit, false);
  }

  View::newInstance()->_exportVariableToView('latest_searches', $output);

  return $output;
}


// ITEM TO RECENTLY VIEWED
function zet_item_to_recent() {
  $item_id = Params::getParam('id');
  
  if($item_id > 0) {
    $recent = array();
    
    if(isset($_COOKIE['zetItemRecent']) && $_COOKIE['zetItemRecent'] != '') {
      $recent = json_decode($_COOKIE['zetItemRecent'], true);
    }
    
    if(($key = array_search($item_id, $recent)) !== false) {
      unset($recent[$key]);
    }

    array_unshift($recent, $item_id);

    if(count($recent) > 48) {
      $recent = array_slice($recent, 0, 48, false);
    }
    
    $recent = array_values(array_filter(array_unique($recent)));
    setcookie('zetItemRecent', json_encode($recent, JSON_NUMERIC_CHECK), time() + (86400 * 365 * 3), '/');
  }
}

osc_add_hook('init', function() {
  if(osc_is_ad_page()) {
    zet_item_to_recent();
  }
});


// GET THEME COLOR
function zet_get_theme_color_primary() {
  if(zet_is_demo() && isset($_COOKIE['zetCustomColorPrimary']) && $_COOKIE['zetCustomColorPrimary'] != '') {
    return osc_esc_html($_COOKIE['zetCustomColorPrimary']);
  } else {
    return zet_param('color_primary');
  }
}

// function zet_get_theme_color_primary_hover() {
  // if(zet_is_demo() && isset($_COOKIE['zetCustomColorPrimaryHover']) && $_COOKIE['zetCustomColorPrimaryHover'] != '') {
    // return osc_esc_html($_COOKIE['zetCustomColorPrimaryHover']);
  // } else {
    // return zet_param('color_primary_hover');
  // }
// }

function zet_get_theme_color_secondary() {
  if(zet_is_demo() && isset($_COOKIE['zetCustomColorSecondary']) && $_COOKIE['zetCustomColorSecondary'] != '') {
    return osc_esc_html($_COOKIE['zetCustomColorSecondary']);
  } else {
    return zet_param('color_secondary');
  }
}

// function zet_get_theme_color_secondary_hover() {
  // if(zet_is_demo() && isset($_COOKIE['zetCustomColorSecondaryHover']) && $_COOKIE['zetCustomColorSecondaryHover'] != '') {
    // return osc_esc_html($_COOKIE['zetCustomColorSecondaryHover']);
  // } else {
    // return zet_param('color_secondary_hover');
  // }
// }


// SET LOCATION TO COOKIES
function zet_set_theme_color() {
  if(zet_is_demo() && Params::getParam('setCustomColor') == 1) {
    if(Params::getParam('customColorPrimary') != '') {
      $color = osc_esc_html(Params::getParam('customColorPrimary'));

      if($color != '' && strlen($color) == 6) {
        $color = '#' . $color;
        setcookie('zetCustomColorPrimary', $color, time() + (86400 * 365 * 3), '/');
      } else {
        setcookie('zetCustomColorPrimary', '', time() + (86400 * 365 * 3), '/');
      }
    }
    
    if(Params::getParam('customColorSecondary') != '') {
      $color = osc_esc_html(Params::getParam('customColorSecondary'));

      if($color != '' && strlen($color) == 6) {
        $color = '#' . $color;
        setcookie('zetCustomColorSecondary', $color, time() + (86400 * 365 * 3), '/');
      } else {
        setcookie('zetCustomColorSecondary', '', time() + (86400 * 365 * 3), '/');
      }
    }
    
    if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '') {
      header('Location:' . $_SERVER['HTTP_REFERER']);
      exit;
    } else {
      header('Location:' . $_SERVER['HTTP_REFERER']);
      exit;
    }    
  }
}

osc_add_hook('init', 'zet_set_theme_color');


// PUBLIC PROFILE ITEMS PER PAGE
function zet_public_profile_items_per_page() {
  $section = osc_get_osclass_section();  
  if(zet_param('public_items') > 0 && osc_get_osclass_location() == 'user' && ($section == 'items' || $section == 'pub_profile')) {
    Params::setParam('itemsPerPage', zet_param('public_items'));
  }
}

osc_add_hook('init', 'zet_public_profile_items_per_page', 10);


// ALERT ITEMS PER PAGE
function zet_public_items_per_page($per_page) {
  return (zet_param('public_items') > 0 ? zet_param('public_items') : $per_page);
}

osc_add_filter('public_items_per_page', 'zet_public_items_per_page');



// ALERT ITEMS LIMIT
function zet_alert_items_limit($per_page) {
  return (zet_param('alert_items') > 0 ? zet_param('alert_items') : $per_page);
}

osc_add_filter('limit_alert_items', 'zet_alert_items_limit');


// AJAX REQUESTS MANAGEMENT
function zet_ajax_manage() {
  if(Params::getParam('ajaxRequest') == 1) {
    error_reporting(0);
    ob_clean();
    osc_current_web_theme_path('ajax.php');
    exit;
  }
}

osc_add_hook('init', 'zet_ajax_manage', 2);


// FIND USER PUBLIC PROFILE URL
function zet_user_public_profile_url($id) {
  if($id > 0) {
    if(osc_rewrite_enabled()) {
      $user = osc_get_user_row($id);
      
      if($user !== false && isset($user['s_username'])) {
        return osc_base_url() . osc_get_preference('rewrite_user_profile') . '/' . $user['s_username'];
      }
    } else {
      return sprintf(osc_base_url(true) . '?page=user&action=pub_profile&id=%d', $id);
    }
  }
  
  return osc_base_url();
}


function zet_user_is_online($id) {
  $limit_seconds = 600; // 10 minutes

  if($id > 0) {
    $user = osc_get_user_row($id);

    if(isset($user['pk_i_id']) && $user['pk_i_id'] > 0) {
      $date = $user['dt_access_date'];
      
      if($date != '') {
        $last_access_date = date('Y-m-d H:i:s', strtotime($date));
        $threshold = date('Y-m-d H:i:s', strtotime(' -' . $limit_seconds . ' seconds', time()));

        if(isset($last_access_date) && $last_access_date <> '' && $last_access_date <> null && $last_access_date >= $threshold) {
          return true;
        }
      }
    }
  }

  return false;
}


// CHECK IF USER IS COMPANY
function zet_user_is_company($id) {
  if($id > 0) {
    $user = osc_get_user_row($id);
    if(isset($user['b_company']) && $user['b_company'] == 1) {
      return true;
    }
  }
  
  return false;
}


// GET ITEM MOBILE PHONE
function zet_get_item_phone() {
  $found = true;
  $code = 'OK';
  $title = __('Click to see phone number', 'zeta');
  
  $mobile = osc_item_field('s_contact_phone');
  if($mobile == '') { $mobile = zet_item_extra(osc_item_id())['s_phone']; }      
  if($mobile == '' && function_exists('bo_mgr_show_mobile')) { $mobile = bo_mgr_show_mobile(); }
  // if($mobile == '' && osc_item_user_id() <> 0) { $mobile = $item_user['s_phone_mobile']; }      
  // if($mobile == '' && osc_item_user_id() <> 0) { $mobile = $item_user['s_phone_land']; } 
  if($mobile == null) { $mobile = ''; }

  $login_required = false;

  if(osc_item_show_phone() == 0) {
    $mobile = '';
    $title = __('No phone number', 'zeta');
    $found = false;
    $code = 'NOT_ALLOWED';
  } else if(osc_get_preference('reg_user_can_see_phone', 'osclass') == 1 && !osc_is_web_user_logged_in() && strlen(trim($mobile)) >= 6) {
    //$mobile = '';
    $title = __('Login to see phone number. Click to login.', 'zeta');
    $found = true;
    $login_required = true;
    $code = 'LOGIN_REQUIRED';
  } else if(trim($mobile) == '' || strlen(trim($mobile)) < 6) { 
    $mobile = '';
    $title = __('No phone number', 'zeta');
    $found = false;
    $code = 'EMPTY';
  }
  
  return array(
    'found' => $found,
    'code' => $code,
    'login_required' => $login_required,
    'title' => $title,
    'phone' => $mobile,
    'masked' => $found ? substr($mobile, 0, strlen($mobile) - 6) . ' &bull;&bull;&bull; &bull;&bull;&bull;' : '',
    'part1' => ($found && !$login_required) ? substr($mobile, 0, strlen($mobile) - 6) : '',
    'part2' => ($found && !$login_required) ? substr($mobile, strlen($mobile) - 6) : '',
    'class' => ($found && !$login_required) ? 'masked' : '',
    'url' => ($found && $login_required) ? osc_user_login_url() : '#'
  );
}


// GET ITEM EMAIL
function zet_get_item_email() {
  $visible = true;
  $title = __('Click to see email', 'zeta');
  $email = osc_item_contact_email();

  if(osc_item_show_email() == 0) {
    $email = '';
    $visible = false;
  }

  $data = zet_mask_email($email);
  
  return array(
    'visible' => $visible,
    'title' => $title,
    'email' => $email,
    'masked' => $visible ? @$data['masked'] : '',
    'part1' => $visible ? @$data['part1'] : '',
    'part2' => $visible ? '@' . @$data['part2'] : '',
    'class' => $visible ? 'masked' : ''
  );
}


// GET PHONE
function zet_get_phone($mobile = '') {
  $found = true;
  $code = 'OK';
  $title = __('Click to see phone number', 'zeta');
  
  $login_required = false;
  if($mobile == null) { $mobile = ''; }

  if(osc_get_preference('reg_user_can_see_phone', 'osclass') == 1 && !osc_is_web_user_logged_in() && strlen(trim($mobile)) >= 6) {
    //$mobile = '';
    $title = __('Login to see phone number. Click to login.', 'zeta');
    $found = true;
    $login_required = true;
    $code = 'LOGIN_REQUIRED';
  } else if(trim($mobile) == '' || strlen(trim($mobile)) < 6) { 
    $mobile = '';
    $title = __('No phone number', 'zeta');
    $found = false;
    $code = 'EMPTY';
  }
  
  return array(
    'found' => $found,
    'code' => $code,
    'login_required' => $login_required,
    'title' => $title,
    'phone' => $mobile,
    'masked' => $found ? substr($mobile, 0, strlen($mobile) - 6) . ' &bull;&bull;&bull; &bull;&bull;&bull;' : '',
    'part1' => ($found && !$login_required) ? substr($mobile, 0, strlen($mobile) - 6) : '',
    'part2' => ($found && !$login_required) ? substr($mobile, strlen($mobile) - 6) : '',
    'class' => ($found && !$login_required) ? 'masked' : '',
    'url' => ($found && $login_required) ? osc_user_login_url() : '#'
  );
}


// EXPORT ALL CATEGORIES
// May not work in case of huge categories list, disable via PRELOAD_CATEGORIES constant
function zet_get_categories() {
  if(defined('PRELOAD_CATEGORIES') && PRELOAD_CATEGORIES) {
    if(!View::newInstance()->_exists('zet_categories')) {
      $categories = array();
      $categories_raw = Category::newInstance()->listEnabled();
      
      if(is_array($categories_raw) && count($categories_raw) > 0) {
        foreach($categories_raw as $c) {
          $categories[$c['pk_i_id']] = $c;
        }
      }
      
      View::newInstance()->_exportVariableToView('zet_categories', $categories);
    }
    
    return View::newInstance()->_get('zet_categories');
  }
  
  return array();
}


// GET SINGLE CATEGORY
function zet_get_category($id) {
  $categories = zet_get_categories();
  
  if(isset($categories[$id])) {
    return $categories[$id];
  }
  
  return osc_get_category_row($id);
}


function zet_base64url_encode($data) {
  return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function zet_base64url_decode($data) {
  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}


// GENERATE URL BASED ON PARAMETERS
function zet_create_url($params) {
  $params = array_filter($params);
  $url = osc_base_url(true) . '?' . http_build_query($params);
  return $url;
}


// CHANGE LOCATION IN SEARCH ROW
function zet_location_default_row() {
  $location_cookie = zet_location_from_cookies();

  if($location_cookie['success'] == true) { 
    ?>
    <div class="row defloc">
      <a href="#" class="change-search-location">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M256 168c-48.6 0-88 39.4-88 88s39.4 88 88 88 88-39.4 88-88-39.4-88-88-88zm0 128c-22.06 0-40-17.94-40-40s17.94-40 40-40 40 17.94 40 40-17.94 40-40 40zm240-64h-49.66C435.49 145.19 366.81 76.51 280 65.66V16c0-8.84-7.16-16-16-16h-16c-8.84 0-16 7.16-16 16v49.66C145.19 76.51 76.51 145.19 65.66 232H16c-8.84 0-16 7.16-16 16v16c0 8.84 7.16 16 16 16h49.66C76.51 366.81 145.19 435.49 232 446.34V496c0 8.84 7.16 16 16 16h16c8.84 0 16-7.16 16-16v-49.66C366.81 435.49 435.49 366.8 446.34 280H496c8.84 0 16-7.16 16-16v-16c0-8.84-7.16-16-16-16zM256 400c-79.4 0-144-64.6-144-144s64.6-144 144-144 144 64.6 144 144-64.6 144-144 144z"></path></svg>
        <?php echo sprintf(__('Searching only in %s (your default location)', 'zeta'), osc_location_native_name_selector($location_cookie, 's_name')); ?>
        <i class="fas fa-times-circle input-clean"></i>
      </a>
    </div>
    <?php 
  }
}


// CHANGE LOCATION IN SEARCH ROW
function zet_location_default_row_search() {
  $location_cookie = zet_location_from_cookies();
  $html = '';

  if(zet_param('default_location') == 1){
    $html .= '<a href="#" class="row2 defloc2 option standalone' . ($location_cookie['success'] == false ? ' not-located' : '') . '" ' . ($location_cookie['success'] == true ? $location_cookie['option_attr'] : '') . '>';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M256 168c-48.6 0-88 39.4-88 88s39.4 88 88 88 88-39.4 88-88-39.4-88-88-88zm0 128c-22.06 0-40-17.94-40-40s17.94-40 40-40 40 17.94 40 40-17.94 40-40 40zm240-64h-49.66C435.49 145.19 366.81 76.51 280 65.66V16c0-8.84-7.16-16-16-16h-16c-8.84 0-16 7.16-16 16v49.66C145.19 76.51 76.51 145.19 65.66 232H16c-8.84 0-16 7.16-16 16v16c0 8.84 7.16 16 16 16h49.66C76.51 366.81 145.19 435.49 232 446.34V496c0 8.84 7.16 16 16 16h16c8.84 0 16-7.16 16-16v-49.66C366.81 435.49 435.49 366.8 446.34 280H496c8.84 0 16-7.16 16-16v-16c0-8.84-7.16-16-16-16zM256 400c-79.4 0-144-64.6-144-144s64.6-144 144-144 144 64.6 144 144-64.6 144-144 144z"/></svg>';

    if($location_cookie['success'] == true) {
      $html .= '<span>' . osc_location_native_name_selector($location_cookie, 's_name') . '</span>';
      $html .= '<em>' .  __('Near me', 'zeta') . '</em>';
    } else {
      $html .= '<span>' .  __('Listings near me', 'zeta') . '</span>';
    }
    
    $html .= '</a>';
  }
  
  if(osc_is_web_user_logged_in()) {
    $user = osc_logged_user();
    
    if($user['fk_i_city_id'] > 0 && $user['s_city'] != '') {
      $html .= '<div class="row2 option standalone" data-city="' . $user['fk_i_city_id'] . '">';
      $html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="20" height="20"><path d="M358.9 433.3l-6.8 61c-1.1 10.2 7.5 18.8 17.6 17.6l60.9-6.8 137.9-137.9-71.7-71.7-137.9 137.8zM633 268.9L595.1 231c-9.3-9.3-24.5-9.3-33.8 0l-41.8 41.8 71.8 71.7 41.8-41.8c9.2-9.3 9.2-24.4-.1-33.8zM223.9 288c79.6.1 144.2-64.5 144.1-144.1C367.9 65.6 302.4.1 224.1 0 144.5-.1 79.9 64.5 80 144.1c.1 78.3 65.6 143.8 143.9 143.9zm-4.4-239.9c56.5-2.6 103 43.9 100.4 100.4-2.3 49.2-42.1 89.1-91.4 91.4-56.5 2.6-103-43.9-100.4-100.4 2.3-49.3 42.2-89.1 91.4-91.4zM134.4 352c14.6 0 38.3 16 89.6 16 51.7 0 74.9-16 89.6-16 16.7 0 32.2 5 45.5 13.3l34.4-34.4c-22.4-16.7-49.8-26.9-79.9-26.9-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 304 0 364.2 0 438.4V464c0 26.5 21.5 48 48 48h258.3c-3.8-14.6-2.2-20.3.9-48H48v-25.6c0-47.6 38.8-86.4 86.4-86.4z"/></svg>';
      $html .= '<span>' . osc_location_native_name_selector($user, 's_city') . '</span>';
      $html .= '<em>' . __('Your profile', 'zeta') . '</em>';
      $html .= '</div>';
      
    } else if($user['fk_i_region_id'] > 0 && $user['s_region'] != '') {
      $html .= '<div class="row2 option standalone" data-region="' . $user['fk_i_region_id'] . '">';
      $html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="20" height="20"><path d="M358.9 433.3l-6.8 61c-1.1 10.2 7.5 18.8 17.6 17.6l60.9-6.8 137.9-137.9-71.7-71.7-137.9 137.8zM633 268.9L595.1 231c-9.3-9.3-24.5-9.3-33.8 0l-41.8 41.8 71.8 71.7 41.8-41.8c9.2-9.3 9.2-24.4-.1-33.8zM223.9 288c79.6.1 144.2-64.5 144.1-144.1C367.9 65.6 302.4.1 224.1 0 144.5-.1 79.9 64.5 80 144.1c.1 78.3 65.6 143.8 143.9 143.9zm-4.4-239.9c56.5-2.6 103 43.9 100.4 100.4-2.3 49.2-42.1 89.1-91.4 91.4-56.5 2.6-103-43.9-100.4-100.4 2.3-49.3 42.2-89.1 91.4-91.4zM134.4 352c14.6 0 38.3 16 89.6 16 51.7 0 74.9-16 89.6-16 16.7 0 32.2 5 45.5 13.3l34.4-34.4c-22.4-16.7-49.8-26.9-79.9-26.9-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 304 0 364.2 0 438.4V464c0 26.5 21.5 48 48 48h258.3c-3.8-14.6-2.2-20.3.9-48H48v-25.6c0-47.6 38.8-86.4 86.4-86.4z"/></svg>';
      $html .= '<span>' . osc_location_native_name_selector($user, 's_region') . '</span>';
      $html .= '<em>' .  __('Your profile', 'zeta') . '</em>';
      $html .= '</div>';
      
    } else if($user['fk_c_country_code'] <> '' && $user['s_country'] != '') {
      $html .= '<div class="row2 option standalone" data-country="' . $user['fk_c_country_code'] . '">';
      $html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="20" height="20"><path d="M358.9 433.3l-6.8 61c-1.1 10.2 7.5 18.8 17.6 17.6l60.9-6.8 137.9-137.9-71.7-71.7-137.9 137.8zM633 268.9L595.1 231c-9.3-9.3-24.5-9.3-33.8 0l-41.8 41.8 71.8 71.7 41.8-41.8c9.2-9.3 9.2-24.4-.1-33.8zM223.9 288c79.6.1 144.2-64.5 144.1-144.1C367.9 65.6 302.4.1 224.1 0 144.5-.1 79.9 64.5 80 144.1c.1 78.3 65.6 143.8 143.9 143.9zm-4.4-239.9c56.5-2.6 103 43.9 100.4 100.4-2.3 49.2-42.1 89.1-91.4 91.4-56.5 2.6-103-43.9-100.4-100.4 2.3-49.3 42.2-89.1 91.4-91.4zM134.4 352c14.6 0 38.3 16 89.6 16 51.7 0 74.9-16 89.6-16 16.7 0 32.2 5 45.5 13.3l34.4-34.4c-22.4-16.7-49.8-26.9-79.9-26.9-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 304 0 364.2 0 438.4V464c0 26.5 21.5 48 48 48h258.3c-3.8-14.6-2.2-20.3.9-48H48v-25.6c0-47.6 38.8-86.4 86.4-86.4z"/></svg>';
      $html .= '<span>' . osc_location_native_name_selector($user, 's_country') . '</span>';
      $html .= '<em>' . __('Your profile', 'zeta') . '</em>';
      $html .= '</div>';
    }
  }

  $html .= '<div class="row2 option standalone all-loc" data-country="" data-region="" data-city="">';
  $html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="20" height="20"><path d="M192 0C86.4 0 0 86.4 0 192c0 76.8 25.6 99.2 172.8 310.4 4.8 6.4 12 9.6 19.2 9.6s14.4-3.2 19.2-9.6C358.4 291.2 384 268.8 384 192 384 86.4 297.6 0 192 0zm0 446.09c-14.41-20.56-27.51-39.12-39.41-55.99C58.35 256.48 48 240.2 48 192c0-79.4 64.6-144 144-144s144 64.6 144 144c0 48.2-10.35 64.48-104.59 198.09-11.9 16.87-25 35.44-39.41 56zm99.93-292.32l-23.21-23.4c-3.85-3.88-10.11-3.9-13.98-.06l-87.36 86.66-37.88-38.19c-3.84-3.88-10.11-3.9-13.98-.06l-23.4 23.21c-3.88 3.85-3.9 10.11-.06 13.98l68.05 68.6c3.85 3.88 10.11 3.9 13.98.06l117.78-116.83c3.88-3.84 3.91-10.1.06-13.97z"/></svg>';
  $html .= '<span>' . __('All locations', 'zeta') . '</span>';
  $html .= '</div>';
  
  return $html;
}


// GET SEARCH LCOATION
function zet_get_search_location() {
  $search_location = '';
  $search_location_array = array_values(array_filter(array(osc_search_city(), osc_search_region(), osc_search_country())));
  
  if(isset($search_location_array[0]) && $search_location_array[0] != '') {
    $search_location = $search_location_array[0];
  }
  
  if($search_location == '') { //Params::getParam('sLocation') != '') {
    $search_location = Params::getParam('sLocation');
  }
  
  return $search_location;
}
  

// DEFAULT CONTENT FOR PATTERN BOX
function zet_default_pattern_content($type = 'advanced') {
  $min_length = 1;
  $html = '<div class="row minlength"><em>' . sprintf(__('Enter %s more character to search...', 'zeta'), '<span class="min">' . $min_length . '</span>') . '</em></div>';


  // SAVED RECENT SEARCHES
  $patterns = array_reverse(zet_get_recent_patterns());

  if(is_array($patterns) && count($patterns) > 0) {
    $html .= '<div class="row patterns">';
    $html .= '<div class="lead">' . __('Your recent search', 'zeta') . '</div>';
    
    foreach($patterns as $p) {
      $html .= '<a class="option direct" href="' . osc_search_url(array('page' => 'search', 'sPattern' => $p)) . '" data-pattern="' . osc_esc_html($p) . '">' . $p . '</a>';
    }
    
    $html .= '</div>';
  }
  
  
  // POPULAR CATEGORIES
  if($type == 'advanced') {
    $categories = ModelZET::newInstance()->getPopularCategories(6, 1);
    
    if(is_array($categories) && count($categories) > 0) {
      $html .= '<div class="row categories">';
      $html .= '<div class="lead">' . __('Popular categories', 'zeta') . '</div>';
      
      foreach($categories as $c) {
        $html .= '<a class="option direct" href="' . osc_search_url(array('page' => 'search', 'sCategory' => $c['pk_i_id'])) . '" data-category="' . osc_esc_html($c['pk_i_id']) . '">' . ($c['s_name_parent'] <> '' ? $c['s_name_parent'] . ' > ' : '') . $c['s_name'] . '</a>';
      }
      
      $html .= '</div>';
    }
  }
  
  
  // LATEST SEARCH
  zet_get_latest_searches(8);

  if(osc_count_latest_searches() > 0) {
    $html .= '<div class="row searches">';
    $html .= '<div class="lead">' . __('Other people searched', 'zeta') . '</div>';
    
    while(osc_has_latest_searches()) {
      $html .= '<a class="option direct" href="' . osc_search_url(array('page' => 'search', 'sPattern' => osc_esc_html(osc_latest_search_text()))) . '" data-pattern="' . osc_esc_html(osc_latest_search_text()) . '">' . osc_highlight(osc_latest_search_text(), 24) . '</a>';
    }
    
    $html .= '</div>';
  }
  
  return $html;
}



// DEFAULT CONTENT FOR PATTERN BOX
function zet_default_location_content() {
  $min_length = 1;
  $html = '<div class="row minlength"><em>' . sprintf(__('Enter %s more character to search...', 'zeta'), '<span class="min">' . $min_length . '</span>') . '</em></div>';

  $html .= zet_location_default_row_search();

  // RECENT LOCATIONS
  $locations = array_reverse(zet_get_recent_locations());
  
  if(is_array($locations) && count($locations) > 0) {
    $html .= '<div class="row recent locations">';
    $html .= '<div class="lead">' . __('Recent locations', 'zeta') . '</div>';
    
    foreach($locations as $loc) {
      if(@$loc['fk_i_city_id'] > 0) {
        $html .= '<a class="option direct' . (Params::getParam('sCity') == $loc['fk_i_city_id'] ? ' selected' : '') . '" href="' . osc_search_url(array('page' => 'search', 'sCity' => $loc['fk_i_city_id'])) . '" data-city="' . osc_esc_html($loc['fk_i_city_id']) . '">' . osc_location_native_name_selector($loc, 's_name') . '</a>';

      } else if(@$loc['fk_i_region_id'] > 0) {
        $html .= '<a class="option direct' . (Params::getParam('sRegion') == $loc['fk_i_region_id'] ? ' selected' : '') . '" href="' . osc_search_url(array('page' => 'search', 'sRegion' => $loc['fk_i_region_id'])) . '" data-region="' . osc_esc_html($loc['fk_i_region_id']) . '">' . osc_location_native_name_selector($loc, 's_name') . '</a>';

      } else if(@$loc['fk_c_country_code'] <> '') {
        $html .= '<a class="option direct' . (Params::getParam('sCountry') == $loc['fk_c_country_code'] ? ' selected' : '') . '" href="' . osc_search_url(array('page' => 'search', 'sCountry' => $loc['fk_c_country_code'])) . '" data-country="' . osc_esc_html($loc['fk_c_country_code']) . '">' . osc_location_native_name_selector($loc, 's_name') . '</a>';
      }
    }
    
    $html .= '</div>';
  }
  
  // CITIES
  $cities = ModelZET::newInstance()->getPopularCities(6);
  
  if(is_array($cities) && count($cities) > 0) {
    $html .= '<div class="row cities locations">';
    $html .= '<div class="lead">' . __('Popular cities', 'zeta') . '</div>';
    
    foreach($cities as $c) {
      $html .= '<a class="option direct' . (Params::getParam('sCity') == $c['fk_i_city_id'] ? ' selected' : '') . '" href="' . osc_search_url(array('page' => 'search', 'sCity' => $c['fk_i_city_id'])) . '" data-city="' . osc_esc_html($c['fk_i_city_id']) . '">' . osc_location_native_name_selector($c, 's_name') . ', ' . osc_location_native_name_selector($c, 's_name_top') . ($c['i_num_items'] > 0 ? ' <em>' . $c['i_num_items'] . ' ' . ($c['i_num_items'] == 1 ? __('item', 'zeta') : __('items', 'zeta')) . '</em>' : '') . '</a>';
    }
    
    $html .= '</div>';
  }
  
  return $html;
}


// CLEAN COOKIE LOCATION
function zet_clean_cookies_locations() {
  if(Params::getParam('cleanCookieLocation') == 1) {
    zet_location_to_cookies('');
    osc_add_flash_ok_message(__('Default location has been cleaned', 'zeta'));
    header('Location:' . osc_base_url());
    exit;
  }
}

osc_add_hook('init', 'zet_clean_cookies_locations');


// SET MANUAL LOCATION
function zet_set_cookies_location_manual() {
  if(Params::getParam('manualCookieLocation') == 1) {
    $data = json_decode(base64_decode(rawurldecode(Params::getParam('hash'))), true);
    $loc = implode(', ', array_filter(array(osc_location_native_name_selector($data, 's_name'), osc_location_native_name_selector($data, 's_name_top'))));

    $location = json_encode(array(
      'success' => true,
      'message' => 'MANUAL',
      's_location' => sprintf(__('Located in %s', 'zeta'), $loc),
      's_name' => $data['s_name'] . ($data['s_name_top'] != '' ? ', ' . $data['s_name_top'] : ''),
      's_name_native' => $data['s_name_native'] . ($data['s_name_top_native'] != '' ? ', ' . $data['s_name_top_native'] : ''),
      's_region' => @$data['s_region'],
      's_city' => @$data['s_city'],
      'fk_i_city_id' => $data['fk_i_city_id'],
      'fk_i_region_id' => $data['fk_i_region_id'],
      'fk_c_country_code' => $data['fk_c_country_code'],
      's_slug' => @$data['s_slug'],
      'd_coord_lat' => @$data['d_coord_lat'],
      'd_coord_long' => @$data['d_coord_long'],
      'dt_date' => date('Y-m-d H:i:s')
    ));

    zet_location_to_cookies($location);
    osc_add_flash_ok_message(__('Default location has been saved', 'zeta'));
    header('Location:' . osc_base_url());
    exit;
  }
}

osc_add_hook('init', 'zet_set_cookies_location_manual');


// SET LOCATION TO COOKIES
function zet_location_to_cookies($location) {
  if($location != '') {
    setcookie('zetLocation', $location, time() + (86400 * 365 * 3), '/');
    zet_location_to_recent(json_decode($location, true));
  } else {
    setcookie('zetLocation', null, -1, '/'); 
  }
}

// SET LOCATION AS RECENT
function zet_location_to_recent($location) {
  $recent = array();
  
  if(isset($_COOKIE['zetLocationRecent']) && $_COOKIE['zetLocationRecent'] != '') {
    $recent = zet_decode_array($_COOKIE['zetLocationRecent']);
  }

  $found = false;
  if(is_array($recent) && count($recent) > 0) {
    foreach($recent as $p) {
      if(is_array($location) && is_array($p)) {
        if(@$p['fk_i_city_id'] . @$p['fk_i_region_id'] . @$p['fk_c_country_code'] == @$location['fk_i_city_id'] . @$location['fk_i_region_id'] . @$location['fk_c_country_code']) {
          $found = true;
        }
      }
    }
  }

  if(!$found) {
    $recent[] = $location;
  }

  if(count($recent) > 10) {
    $recent = array_slice($recent, -10, 10, false);
  }
  
  $recent = array_filter($recent);
 
  setcookie('zetLocationRecent', zet_encode_array($recent), time() + (86400 * 365 * 3), '/');
}


// GET LOCATION COOKIES
function zet_location_from_cookies() {
  if(zet_param('default_location') == 1 && isset($_COOKIE['zetLocation']) && $_COOKIE['zetLocation'] != '') {
    $data = array();
    
    if(isset($_COOKIE['zetLocation']) && $_COOKIE['zetLocation'] != '') {
      $data = json_decode($_COOKIE['zetLocation'], true);
    }
    
    $data['success'] == isset($data['success']) ? $data['success'] : false;
    
    if($data['success'] == 1) {
      if(isset($data['fk_i_city_id']) && $data['fk_i_city_id'] > 0) {
        $data['option_attr'] = 'data-city="' . $data['fk_i_city_id'] . '"';

      } else if(isset($data['fk_i_region_id']) && $data['fk_i_region_id'] > 0) {
        $data['option_attr'] = 'data-region="' . $data['fk_i_region_id'] . '"';
        
      } else if(isset($data['fk_c_country_code']) && $data['fk_c_country_code'] <> '') {
        $data['option_attr'] = 'data-country="' . $data['fk_c_country_code'] . '"';
        
      }
    }
    
    return $data;
  }
  
  return array('success' => false);
}

// GET RECENT LOCATIONS FROM COOKIES
function zet_get_recent_locations($limit = 6) {
  if(isset($_COOKIE['zetLocationRecent']) && $_COOKIE['zetLocationRecent'] != '') {
    $data = zet_decode_array($_COOKIE['zetLocationRecent']);

    if(is_array($data) && count($data) > $limit) {
      $data = array_slice($data, -$limit, $limit, false);
    }

    return (is_array($data) ? $data : array());
  }
  
  return array();
}

// SAVE PATTERN TO COOKIES
function zet_pattern_to_cookies($pattern) {
  $patterns = array();
  
  if(isset($_COOKIE['zetPatternRecent']) && $_COOKIE['zetPatternRecent'] != '') {
    $patterns = zet_decode_array($_COOKIE['zetPatternRecent']);
  }
  
  $patterns[] = $pattern;
  $patterns = array_unique(array_filter($patterns));
  
  if(count($patterns) > 30) {
    $patterns = array_slice($patterns, -30, 30, false);
  }
  
  setcookie('zetPatternRecent', zet_encode_array($patterns), time() + (86400 * 365 * 3), '/');
}


// GET RECENT PATTERNS FROM COOKIES
function zet_get_recent_patterns($term = '', $limit = 6) {
  if(isset($_COOKIE['zetPatternRecent']) && $_COOKIE['zetPatternRecent'] != '') {
    $data = zet_decode_array($_COOKIE['zetPatternRecent']);

    if($term != '') {
      //$term = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $term);

      $data = array_filter($data, function($element) use($term){
        return stripos($element, $term) !== false;
      });
    }

    if(is_array($data) && count($data) > $limit) {
      $data = array_slice($data, -$limit, $limit, false);
    }
    
    return (is_array($data) ? $data : array());
  }
  
  return array();
}


// ENCODE ARRAY TO REDUCE SIZE
function zet_encode_array($array) {
  $array = (is_array($array) ? $array : array());
  return gzcompress(json_encode($array), 7);
}


// DECODE ARRAY
function zet_decode_array($json) {
  return @json_decode(gzuncompress((string)$json), true);
}


// FAVORITE ITEMS SUPPORT
function zet_make_favorite($item_id = NULL, $with_count = true, $with_text = false, $class = '') {
  if(function_exists('fi_save_favorite')) {
    $item_id = ($item_id === NULL ? osc_item_id() : $item_id);
    $options = array(
      'icon_on' => 'fas fa-heart',
      'icon_off' => 'far fa-heart',
      'title_on' => __('Remove from favorites', 'zeta'),
      'title_off' => __('Save to favorite', 'zeta')
    );
    
    if($with_text) {
      $count = ModelZET::newInstance()->countItemFavorited($item_id);
      
      if(zet_is_mobile()) {
        $text = $count;
      } else {
        $text = ($count == 1 ? sprintf(__('%d like', 'zeta'), $count) : sprintf(__('%d likes', 'zeta'), $count));
      }
      
      $options['title_on'] = $text;
      $options['title_off'] = $text;
    }
    
    $html = '';
    $html .= '<div class="favorite' . ($class != '' ? ' ' . trim((string) $class) : '') . '">';
    $html .= fi_save_favorite($item_id, $options);
    
    if($with_count == true) {
      $count = ModelZET::newInstance()->countItemFavorited($item_id);
      
      if($count > 0) { 
        $html .= '<em class="fav-count">' . $count . '</em>';
      }
    }

    $html .= '</div>';

    echo $html;
  }
}


// GET FAVORITED ITEMS
function zet_favorited_items($limit = 4) {
  //$limit = (zet_param('favorite_count') > 0 ? zet_param('favorite_count') : 16);

  // SEARCH ITEMS IN LIST AND CREATE ITEM ARRAY
  $aSearch = new Search();
  $aSearch->addField(sprintf('count(%st_item.pk_i_id) as count_id', DB_TABLE_PREFIX));
  $aSearch->addConditions(sprintf("%st_favorite_list.list_id = %st_favorite_items.list_id", DB_TABLE_PREFIX, DB_TABLE_PREFIX));
  $aSearch->addConditions(sprintf("%st_favorite_items.item_id = %st_item.pk_i_id", DB_TABLE_PREFIX, DB_TABLE_PREFIX));
  $aSearch->addConditions(sprintf("%st_favorite_list.user_id <> coalesce(%st_item.fk_i_user_id, 0)", DB_TABLE_PREFIX, DB_TABLE_PREFIX));
  $aSearch->addConditions(sprintf("%st_item.b_enabled = 1", DB_TABLE_PREFIX));
  $aSearch->addConditions(sprintf("%st_item.b_spam = 0", DB_TABLE_PREFIX));
  $aSearch->addConditions(sprintf("%st_item.b_active = 1", DB_TABLE_PREFIX));
  $aSearch->addTable(sprintf("%st_favorite_items", DB_TABLE_PREFIX));
  $aSearch->addTable(sprintf("%st_favorite_list", DB_TABLE_PREFIX));
  $aSearch->addGroupBy(DB_TABLE_PREFIX.'t_item.pk_i_id');
  $aSearch->order('count(*)', 'DESC');
  $aSearch->limit(0, $limit);
  $list_items = $aSearch->doSearch();

  return $list_items;
}


// GET ITEMS BASED ON USER LOCATION
function zet_location_items($location) {
  $limit = 16;
  $radius = 100; // km
  
  $mSearch = new Search();

  if(@$location['fk_c_country_code'] > 0) {
    $mSearch->addCountry($location['fk_c_country_code']);
  }
  
  if(@$location['fk_i_region_id'] > 0) {
    $mSearch->addRegion($location['fk_i_region_id']);
  }
  
  if(@$location['fk_i_city_id'] > 0) {
    $mSearch->addCity($location['fk_i_city_id']);
  }
  
  $mSearch->limit(0, $limit);
  $mSearch->addGroupBy(DB_TABLE_PREFIX.'t_item.pk_i_id');
  
  $aItems = $mSearch->doSearch(); 
  
  
  // TRY RADIUS SEARCH IN AREA
  if(count($aItems) < $limit && @$location['d_coord_lat'] <> '' && @$location['d_coord_long'] <> '') {
    $not_ids = array();
    
    if(count($aItems) > 0) {
      foreach($aItems as $ai) {
        $not_ids[] = $ai['pk_i_id'];
      }
    }
    
    $not_ids = implode(',', $not_ids);
    
    $lat = $location['d_coord_lat'];
    $lon = $location['d_coord_long'];
    $measurement = 6371;  // 3959 for miles 
    $distance_condition = sprintf('(%d * acos(cos(radians(%f)) * cos(radians(d_coord_lat)) * cos(radians(d_coord_long) - radians(%f)) + sin(radians(%f)) * sin(radians(d_coord_lat)))) <= %f', (int)$measurement, (float)$lat, (float)$lon, (float)$lat, (float)$radius);
    
    $mSearch2 = new Search();
    $mSearch2->addJoinTable(DB_TABLE_PREFIX.'t_item_location.fk_i_item_id', DB_TABLE_PREFIX.'t_item_location', DB_TABLE_PREFIX.'t_item.pk_i_id = '.DB_TABLE_PREFIX.'t_item_location.fk_i_item_id', 'INNER');

    //$mSearch2->addConditions(sprintf('(POWER(d_coord_lat - %f, 2) + POWER(d_coord_long - %f, 2) <= POWER(%f, 2))', (float)$location['d_coord_lat'], (float)$location['d_coord_long'], (float)$radius));
    $mSearch2->addConditions($distance_condition);
    
    if($not_ids <> '') {
      $mSearch2->addConditions(DB_TABLE_PREFIX.'t_item.pk_i_id NOT IN (' . $not_ids . ')');
    }

    $mSearch2->limit(0, $limit - count($aItems));
    $aItems2 = $mSearch2->doSearch(); 
    
    if(count($aItems2) > 0) {
      $aItems = (count($aItems) > 0 ? array_merge($aItems, $aItems2) : $aItems2);
    }
  }
  
  return $aItems;  
}


// HIGHLIGHT SEARCHED TERM
function zet_highlight_term($text, $substring) {
  return preg_replace('/(' . $substring . ')/i', '<u>$1</u>', $text);
}


// VERSION SCRIPTS (DEVELOPMENT)
function zet_asset_version() {
  $set = (zet_param('asset_versioning') == 1 ? true : false);
  return ($set ? '?v=' . date('YmdHis') : '');
}


// REMOVE OLD FONT AWESOME (V4)
function zet_clean_old_fonts() {
  osc_remove_style('font-open-sans');
  osc_remove_style('open-sans');
  osc_remove_style('fi_font-awesome');
  osc_remove_style('font-awesome44');
  osc_remove_style('font-awesome45');
  osc_remove_style('font-awesome47');
  osc_remove_style('cookiecuttr-style');
  osc_remove_style('responsiveslides');
  osc_remove_style('font-awesome');
}

osc_add_hook('init', 'zet_clean_old_fonts');
osc_add_hook('header', 'zet_clean_old_fonts');



// SAVE SEARCH SECTION
function zet_save_search_section($position) {
?>
<div id="search-alert" class="pos-<?php echo $position; ?>">
  <?php osc_alert_form(); ?>
</div>
<?php
}  

// ONLINE CHAT
function zet_chat_button($user_id = '', $class = '', $use_svg = false) {
  if(function_exists('oc_chat_button')) {
    $html = '';
    $user_name = '';
    $text = '';
    $title = '';
    $class2 = '';

    if((osc_is_ad_page() || osc_is_search_page()) && $user_id == '') {
      $user_id = osc_item_user_id();
      $user_name = osc_item_contact_name();
    }

    if($user_id <> '' && $user_id > 0) {
      $registered = 1;
      $last_active = ModelOC::newInstance()->getUserLastActive($user_id);
      $user = osc_get_user_row($user_id);
      $user_name = @$user['s_name'];

      $active_limit = osc_get_preference('refresh_user', 'plugin-online_chat');
      $active_limit = ($active_limit > 0 ? $active_limit : 120);
      $active_limit = $active_limit + 10;

      $limit_datetime = date('Y-m-d H:i:s', strtotime(' -' . $active_limit . ' seconds', time()));
    } else {
      $registered = 0;
    }

    if($registered == 1 && $user_id <> osc_logged_user_id() && !oc_check_bans($user_id)) {
      $class .= ' oc-active';
      $class2 .= ' oc-active';
    } else {
      $class .= ' oc-disabled';
      $class2 .= ' oc-disabled';
    }

    if(isset($limit_datetime) && $limit_datetime <> '' && $last_active >= $limit_datetime) {
      $class .= ' oc-online';
      $class2 .= ' oc-online';
      $title .= __('User is online', 'zeta');
    } else {
      $class .= ' oc-offline';
      $class2 .= ' oc-offline';
      $title .= __('User is offline', 'zeta');
    }


    //$html .= '<div class="row mob oc-chat-box' . $class . '" data-user-id="' . $user_id . '">';
    //$html .= '<i class="fa fa-comment"></i>';



    if($registered == 0) {
      $text .=  __('Chat unavailable', 'zeta');
      $title .= ', ' . __('User is not registered', 'zeta');
    } else {
      if($user_id == osc_logged_user_id()) {
        $text .= __('Chat unavailable', 'zeta');
        $title .= ', ' . __('It\'s your ad', 'zeta');
      } else if(oc_check_bans($user_id)) {
        $text .= __('Chat unavailable', 'zeta');
        $title .= ', ' . __('User has blocked you', 'zeta');
      } else {
        //$text .= '<span class="oc-user-top oc-status-offline">' . __('Chat unavailable', 'zeta') . '</span>';
        $text .= '<span class="oc-user-top oc-status-online">' . __('Start chat', 'zeta') . '</span>';
      }
    }


    $html .= '<a href="#" class="btn-chat oc-start-chat ' . $class . '" data-to-user-id="' . $user_id . '" data-to-user-name="' . osc_esc_html($user_name) . '" data-to-user-image="' . oc_get_picture($user_id) . '" title="' . osc_esc_html($title) . '">';
    //$html .= '<svg height="24" viewBox="0 0 512 512" width="24" xmlns="http://www.w3.org/2000/svg"><path d="m512 346.5c0-74.628906-50.285156-139.832031-121.195312-159.480469-4.457032-103.878906-90.347657-187.019531-195.304688-187.019531-107.800781 0-195.5 87.699219-195.5 195.5 0 35.132812 9.351562 69.339844 27.109375 99.371094l-26.390625 95.40625 95.410156-26.386719c27.605469 16.324219 58.746094 25.519531 90.886719 26.90625 19.644531 70.914063 84.851563 121.203125 159.484375 121.203125 29.789062 0 58.757812-7.933594 84.210938-23.007812l80.566406 22.285156-22.285156-80.566406c15.074218-25.453126 23.007812-54.421876 23.007812-84.210938zm-411.136719-15.046875-57.117187 15.800781 15.800781-57.117187-3.601563-5.632813c-16.972656-26.554687-25.945312-57.332031-25.945312-89.003906 0-91.257812 74.242188-165.5 165.5-165.5s165.5 74.242188 165.5 165.5-74.242188 165.5-165.5 165.5c-31.671875 0-62.445312-8.972656-89.003906-25.945312zm367.390625 136.800781-42.382812-11.726562-5.660156 3.683594c-21.941407 14.253906-47.433594 21.789062-73.710938 21.789062-58.65625 0-110.199219-37.925781-128.460938-92.308594 89.820313-10.355468 161.296876-81.832031 171.65625-171.65625 54.378907 18.265625 92.304688 69.808594 92.304688 128.464844 0 26.277344-7.535156 51.769531-21.789062 73.710938l-3.683594 5.660156zm0 0"/><path d="m180.5 271h30v30h-30zm0 0"/><path d="m225.5 150c0 8.519531-3.46875 16.382812-9.765625 22.144531l-35.234375 32.25v36.605469h30v-23.394531l25.488281-23.328125c12.398438-11.347656 19.511719-27.484375 19.511719-44.277344 0-33.085938-26.914062-60-60-60s-60 26.914062-60 60h30c0-16.542969 13.457031-30 30-30s30 13.457031 30 30zm0 0"/></svg>';

    if(!$use_svg) {
      $html .= '<i class="fas fa-comment-alt"></i>';
    } else {
      $html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M144 208c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32zm112 0c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32zm112 0c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32zM256 32C114.6 32 0 125.1 0 240c0 47.6 19.9 91.2 52.9 126.3C38 405.7 7 439.1 6.5 439.5c-6.6 7-8.4 17.2-4.6 26S14.4 480 24 480c61.5 0 110-25.7 139.1-46.3C192 442.8 223.2 448 256 448c141.4 0 256-93.1 256-208S397.4 32 256 32zm0 368c-26.7 0-53.1-4.1-78.4-12.1l-22.7-7.2-19.5 13.8c-14.3 10.1-33.9 21.4-57.5 29 7.3-12.1 14.4-25.7 19.9-40.2l10.6-28.1-20.6-21.8C69.7 314.1 48 282.2 48 240c0-88.2 93.3-160 208-160s208 71.8 208 160-93.3 160-208 160z"/></svg>';
    }
    
    $html .= '<span>' . $text . '</span>';
    //$html .= '<em class="' . $class2 . '"></em>';
    $html .= '</a>';

    //$html .= '</div>';

    return $html;
  } else {
    return false;
  }
}


// ON POST/EDIT PAGE TO GET SESSION
function zet_post_item_title() {
  $title = osc_item_title();
  foreach(osc_get_locales() as $locale) {
    if(Session::newInstance()->_getForm('title') != "") {
      $title_ = Session::newInstance()->_getForm('title');
      if(@$title_[$locale['pk_c_code']] != ""){
        $title = $title_[$locale['pk_c_code']];
      }
    }
  }
  return $title;
}


// ON POST/EDIT PAGE TO GET SESSION
function zet_post_item_description() {
  $description = osc_item_description();
  foreach(osc_get_locales() as $locale) {
    if(Session::newInstance()->_getForm('description') != "") {
      $description_ = Session::newInstance()->_getForm('description');
      if(@$description_[$locale['pk_c_code']] != ""){
        $description = $description_[$locale['pk_c_code']];
      }
    }
  }
  return $description;
}


// MASK EMAIL
function zet_mask_email($email) {
  if($email == '') { return false; }
  
  $em = explode('@',$email);
  $name = implode('@', array_slice($em, 0, count($em)-1));
  $domain = end($em);

  $len_name = strlen($name)-2;
  $mask_name = substr($name,0, strlen($name) - $len_name) . str_repeat('&bull;', $len_name);
 
  $len_domain = strlen($domain) - 4;
  $mask_domain = str_repeat('&bull;', $len_domain) . substr($domain, $len_domain, strlen($domain));

  return array(
    'email' => $email,
    'masked' => $mask_name . '@' . $mask_domain,
    'part1' => $name,
    'part2' => $domain
  );
}


// PUBLIC PROFILE ITEMS
function zet_public_profile_per_page() {
  $section = osc_get_osclass_section();  
  if(osc_get_osclass_location() == 'user' && $section == 'pub_profile') {
    Params::setParam('itemsPerPage', (zet_param('public_profile_per_page') > 0 ? zet_param('public_profile_per_page') : 24));
  }
}

osc_add_hook('init', 'zet_public_profile_per_page');


// USER ITEMS
function zet_user_items_per_page() {
  $section = osc_get_osclass_section();  
  if(osc_get_osclass_location() == 'user' && $section == 'items') {
    Params::setParam('itemsPerPage', (zet_param('user_items_per_page') > 0 ? zet_param('user_items_per_page') : 12));
  }
}

osc_add_hook('init', 'zet_user_items_per_page');


// CHECK IF LAZY LOAD ENABLED
function zet_is_lazy($disabled = false) {
  if($disabled === true) {
    return false; 
  } else if(zet_param('lazy_load') == 1) {
    return true;
  }

  return false;
}


// CHECK IF BUILT-IN BROWSER BASED LAZY LOAD ENABLED
function zet_is_lazy_browser($disabled = false) {
  if($disabled === true) {
    return false; 
  } else if(zet_param('lazy_load') == 2) {
    return true;
  }

  return false;
}

function zet_get_load_image($type = '') {
  if($type == 'transparent') {
    return osc_current_web_theme_url('images/load-image-transparent.png');
  }
  
  return osc_current_web_theme_url('images/load-image.png');
}


// GET NO IMAGE LINK
function zet_get_noimage($type = 'thumb') {
  if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/no-image.svg')) {
    return osc_current_web_theme_url('images/no-image.svg');
  }
}


// CONVERT COLOR: HEX TO RGBA 
function zet_hex_to_rgb($colour, $opacity = 1) {
  if(strlen($colour) != 7) {
    return $colour;
  }
  
  if($colour[0] == '#') {
    $colour = substr($colour, 1);
  }
  
  if(strlen($colour) == 6) {
    list($r, $g, $b) = array($colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
  } elseif(strlen($colour) == 3) {
    list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
  } else {
    return false;
  }
  
  $r = hexdec($r);
  $g = hexdec($g);
  $b = hexdec($b);
  
  return 'rgba(' . $r . ', ' . $g . ', ' . $b . ', ' . $opacity . ')'; 
}


// ADJUST BRIGHNESS OF HEX COLOR
function zet_hex_brightness($hex, $steps = 20) {
  if(strlen($hex) != 7) {
    return $hex;
  }

  // Steps should be between -255 and 255. Negative = darker, positive = lighter
  $steps = max(-255, min(255, $steps));

  // Normalize into a six character long hex string
  $hex = str_replace('#', '', $hex);
  if(strlen($hex) == 3) {
    $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
  }

  // Split into three parts: R, G and B
  $color_parts = str_split($hex, 2);
  $return = '#';

  foreach ($color_parts as $color) {
    $color   = hexdec($color); // Convert to decimal
    $color   = max(0,min(255,$color + $steps)); // Adjust color
    $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
  }

  return $return;
}


// ADJUST HUE OF HEX COLOR
function zet_hex_hue($hex, $steps = 20) {
  if(strlen($hex) != 7) {
    return $hex;
  }

  // Steps should be between -255 and 255. Negative = darker, positive = lighter
  $steps = max(-255, min(255, $steps));

  // Normalize into a six character long hex string
  $hex = str_replace('#', '', $hex);
  if(strlen($hex) == 3) {
    $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
  }

  // Split into three parts: R, G and B
  $color_parts = str_split($hex, 2);
  $return = '#';

  $i = 1;
  foreach ($color_parts as $color) {
    $add = ($i == 3 ? $steps : 0);
    
    $color   = hexdec($color); // Convert to decimal
    $color   = max(0,min(255,$color + $add)); // Adjust color
    $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
    $i++;
  }

  return $return;
}



// NEXT - PREV ITEM LINKS
function zet_next_prev_item($type, $category_id, $item_id) {
  $mSearch = new Search();
  $mSearch->addCategory($category_id);
  $mSearch->limit(0, 1);
  

  if($type == 'next') {
    $mSearch->addItemConditions(sprintf("%st_item.pk_i_id > %d", DB_TABLE_PREFIX, $item_id));
    $mSearch->order(sprintf("%st_item.pk_i_id", DB_TABLE_PREFIX), 'ASC');
  } else {
    $mSearch->addItemConditions(sprintf("%st_item.pk_i_id < %d", DB_TABLE_PREFIX, $item_id));
    $mSearch->order(sprintf("%st_item.pk_i_id", DB_TABLE_PREFIX), 'DESC');
  }
  
  $aItems = $mSearch->doSearch();

  if(isset($aItems[0])) {
    $item = $aItems[0];
    
    if(isset($item['pk_i_id']) && $item['pk_i_id'] > 0) {
      return osc_item_url_from_item($item);
    }
  }
  
  return false;
}


function zet_next_prev_user($type, $user_id) {
  $db_prefix = DB_TABLE_PREFIX;
  
  if($type == 'prev') {
    $cond = '<';
    $order = 'pk_i_id DESC';
  } else if($type == 'next') {
    $cond = '>';
    $order = 'pk_i_id ASC';
  }
  
  $query = "SELECT * FROM {$db_prefix}t_user WHERE pk_i_id {$cond} {$user_id} AND b_enabled = 1 AND b_active = 1 ORDER BY {$order} LIMIT 0,1;";
  $result = User::newInstance()->dao->query($query);
  
  if($result) { 
    $user = $result->row();
    
    if(isset($user['pk_i_id']) && $user['pk_i_id'] > 0) {
      return osc_user_public_profile_url($user['pk_i_id']);
    }
  }
    
  return false;
}


// RELATED ADS
function zet_related_ads($by = 'category', $card_type = 'normal', $limit = 0, $class = '') {
  if($limit <= 0) {
    $limit = (zet_param('related_count') > 0 ? zet_param('related_count') : 12);
  }
  
  if($limit <= 0) {
    $limit = 12; 
  }
  
  $mSearch = new Search();
  
  $id = 'rel-block';
  
  if($by == 'category') {
    $title = __('Related items', 'zeta');
    $mSearch->addCategory(osc_item_category_id());
  } else if($by == 'user') {
    $title = __('Other items from this seller', 'zeta');
    
    if(osc_item_user_id() > 0) {
      $mSearch->fromUser(osc_item_user_id());
    } else {
      $mSearch->addContactEmail(osc_item_contact_email());
    }
    
    $id = 'rel-user-block';
  } else if($by == 'user-pb-premium') {
    $title = __('Premium', 'zeta');
    $mSearch->fromUser(osc_user_id());
    $mSearch->onlyPremium(true);
    $id = 'rel-user-pb-block';
  }  
  
  //$mSearch->withPicture(true); 
  $mSearch->limit(0, $limit);
  
  if($by != 'user-pb-premium') {
    $mSearch->addItemConditions(sprintf("%st_item.pk_i_id <> %d", DB_TABLE_PREFIX, osc_item_id()));
  }
  
  $aItems = $mSearch->doSearch(); 

  $default_items = View::newInstance()->_get('items');
  View::newInstance()->_exportVariableToView('items', $aItems); 

  if(osc_count_items() > 0) {
  ?>
    <div id="<?php echo $id; ?>" class="related type-<?php echo $by; ?> <?php echo $class; ?>">
      <h2><?php echo $title; ?></h2>
      <div class="nice-scroll-wrap nice-scroll-have-overflow">
        <div class="nice-scroll-prev"><span class="mover"><?php echo ICON_SCROLL_PREV; ?></span></div>

        <div class="products grid nice-scroll no-visible-scroll style3">
          <?php
            $c = 1;
            while(osc_has_items()) {
              zet_draw_item($c, false, $card_type);
              $c++;
            }
          ?>
        </div>
        
        <div class="nice-scroll-next"><span class="mover"><?php echo ICON_SCROLL_NEXT; ?></span></div>
      </div>
    </div>
  <?php
  }

  View::newInstance()->_exportVariableToView('items', $default_items);
}


// RELATED ADS
function zet_recent_ads($card_type = 'normal', $limit = 24, $class = '', $only_items = false, $clear_btn = FALSE) {
  if($limit <= 0) {
    $limit = (zet_param('recent_count') > 0 ? zet_param('recent_count') : 12);
  }
  
  $recent = array();
  
  if(isset($_COOKIE['zetItemRecent']) && $_COOKIE['zetItemRecent'] != '') {
    $recent = json_decode($_COOKIE['zetItemRecent'], true);
  }
    
  if(osc_item_id() > 0) {
    if(($key = array_search(osc_item_id(), $recent)) !== false) {
      unset($recent[$key]);
    }
  }

  if(!is_array($recent) || count($recent) <= 0) {
    return array();
  }

  $mSearch = new Search();
  $mSearch->addItemConditions(sprintf("%st_item.pk_i_id in (%s)", DB_TABLE_PREFIX, implode(',', $recent)));
  $mSearch->order(sprintf("FIELD (%st_item.pk_i_id, %s)", DB_TABLE_PREFIX, implode(',', $recent)), 'ASC');
  $mSearch->limit(0, $limit);
  $aItems = $mSearch->doSearch(); 

  if($only_items) {
    return $aItems;
  }
  
  $default_items = View::newInstance()->_get('items');
  View::newInstance()->_exportVariableToView('items', $aItems); 

  if($class == null) { $class = ''; }

  if(osc_count_items() > 0) {
  ?>
    <div id="recent-ads" class="recent-ads<?php echo (trim($class) <> '' ? ' ' . $class : ''); ?>">
      <h2>
        <span><?php _e('Recently viewed', 'zeta'); ?></span>
        
        <?php if($clear_btn) { ?>
          <a href="#" class="btn btn-secondary mini clear-recently-viewed"><?php _e('Clear all', 'zeta'); ?></a>
        <?php } ?>
      </h2>
      
      <div class="nice-scroll-wrap nice-scroll-have-overflow">
        <div class="nice-scroll-prev"><span class="mover"><?php echo ICON_SCROLL_PREV; ?></span></div>

        <div class="products grid nice-scroll no-visible-scroll style4">
          <?php
            $c = 1;
            while(osc_has_items()) {
              zet_draw_item($c, false, $card_type);
              $c++;
            }
          ?>
        </div>
        
        <div class="nice-scroll-next"><span class="mover"><?php echo ICON_SCROLL_NEXT; ?></span></div>
      </div>
    </div>
  <?php
  }

  View::newInstance()->_exportVariableToView('items', $default_items);
}


// GET LOCALE SELECT FOR PUBLISH PAGE
function zet_locale_post_links() {
  $c = osc_current_user_locale();

  $html = '';
  $locales = osc_get_locales();

  if(count($locales) > 0) {
    $html .= '<div class="locale-links">';

    foreach($locales as $l) {
      $html .= '<a href="#" data-locale="' . $l['pk_c_code'] . '" data-name="' . $l['s_name'] . '" class="mbBg3Active' . ($c == $l['pk_c_code'] ? ' active' : '') . '">' . $l['s_short_name'] . '</a>';
    }

    $html .= '</div>';
  }

  return $html;
}


// GET PROPER PROFILE IMAGE
function zet_profile_picture($user_id = NULL, $size = 'small') {
  $user_id = ($user_id > 0 ? $user_id : 0);
  
  if(View::newInstance()->_exists('zet_profile_picture_url_' . $user_id)) {
    return View::newInstance()->_get('zet_profile_picture_url_' . $user_id);
  }
 
  // if($user_id === NULL) {
    // $user_id = osc_item_user_id();
    // $user_id = ($user_id > 0 ? $user_id : osc_premium_user_id());
  // }

  if($size == 'small') {
    $dimension = 36;
  } else if($size == 'medium') {
    $dimension = 128;
  } else {
    $dimension = 256;
  }

  $img = '';


  // GET IMAGE FROM PROFILE PICTURE FIRST
  if($user_id > 0) {
    if(function_exists('profile_picture_show')) {
      $conn = getConnection();
      $result = $conn->osc_dbFetchResult("SELECT user_id, pic_ext FROM %st_profile_picture WHERE user_id = '%d' ", DB_TABLE_PREFIX, $user_id);

      if($result > 0) { 
        $path = osc_plugins_path().'profile_picture/images/';

        if(file_exists($path . 'profile' . $user_id . $result['pic_ext'])) { 
          $img = osc_base_url() . zet_oc_content_folder() . '/plugins/profile_picture/images/' . 'profile' . $user_id . $result['pic_ext'];
        }
      }
    } else if(osc_profile_img_users_enabled()) {
      $img = zet_user_profile_img_url($user_id);
    }
  }

  if($img == '') {
    $img = osc_current_web_theme_url('images/default-user-image.png');
  }

  View::newInstance()->_exportVariableToView('zet_profile_picture_url_' . $user_id, $img);
  
  return $img;
}


// CUSTOMIZED USER PROFILE IMG URL FUNCTION
function zet_user_profile_img_url($id = null) {
  return (string) osc_apply_filter('user_profile_img_url', osc_base_url() . zet_oc_content_folder() . '/uploads/user-images/' . zet_user_profile_img($id));
}


// CUSTOMIZED USER PROFILE IMG FUNCTION
function zet_user_profile_img($id = null) {
  if($id === 0) {
    $img = 'default-user-image.png';
  } else if($id !== null) {
    $user = osc_get_user_row($id);
    $img = isset($user['s_profile_img']) ? $user['s_profile_img'] : '';
  } else {
    $img = osc_user_field("s_profile_img");
  }

  if($img === NULL || trim($img) == '') {
    $img = 'default-user-image.png';
  }

  return (string) $img;
}


// CHECK IF USER HAS PROFILE PICTURE
function zet_has_profile_picture($user_id) {
  $img = zet_profile_picture($user_id);
  
  if(strpos($img, 'no-user') !== false || strpos($img, 'default-user-image') !== false || strpos($img, 'no-image') !== false) {
    return false;
  }
  
  return true;  
}


// USER RATING ADD BUTTON
function zet_add_rating_link($user_id = NULL, $item_id = NULL) {
  if(function_exists('ur_add_rating_link_raw')) {
    $link = ur_add_rating_link_raw($user_id, $item_id);
    return '<a href="' . $link . '" id="add-new-rating" class="add-new-rating" rel="nofollow noreferrer">' . __('Rate', 'zeta') . '</a>';
  }
}


// GET SEARCH PARAMS FOR REMOVE
function zet_search_param_remove() {
  $params = Params::getParamsAsArray();
  $output = array();

  foreach($params as $n => $v) {
    if(!in_array($n, array('page','sLocation')) && ((int)$v > 0 || $v <> '')) {
      // Skip if value is set to default
      if(in_array($n, array('bPic','bPremium','bPhone','sCondition','sTransaction','iRadius','sPeriod')) && (int)$v == 0) {
        continue;
      }
      
      $output[$n] = array(
        'value' => $v, 
        'param' => $n,
        'title' => zet_param_name($n),
        'name' => zet_remove_value_name($v, $n, 'long'),
        'to_remove' => true  //(in_array($n, array('sCompany')) ? false : true)
     );
    }
  }

  return $output;
}


// GET NAME FOR REMOVE PARAMETER
function zet_remove_value_name($value, $type, $format = 'short') {
  $def_cur = (zet_param('def_cur') <> '' ? zet_param('def_cur') : '$');
  $is_long = ($format == 'long' ? true : false);

  if($type == 'sPeriod') {  
    return zet_get_simple_name($value, 'period', true, $is_long);

  } else if($type == 'sTransaction') {  
    return zet_get_simple_name($value, 'transaction', true, $is_long);

  } else if($type == 'sCondition') {  
    return zet_get_simple_name($value, 'condition', true, $is_long);
    
  } else if($type == 'iRadius') {  
    return zet_get_simple_name($value, 'radius', true, $is_long);
    
  } else if($type == 'sCompany') { 
    $value_mod = $value;
    
    if(Params::existParam('sCompany')) {
      $value_mod++;
    }
    
    return zet_get_simple_name($value_mod, 'seller_type', true, $is_long);

  } else if($type == 'sCategory' || $type == 'category') {
    if(@osc_search_category_id()[0] > 0) {
      $category = zet_get_category(osc_search_category_id()[0]);
      return $category['s_name'];
    }

  } else if($type == 'sCountry' || $type == 'country') {
    return osc_search_country();

  } else if($type == 'sRegion' || $type == 'region') {
    return osc_search_region();

  } else if($type == 'sCity' || $type == 'city') {
    return osc_search_city();
  
  } else if($type == 'sPriceMin' || $type == 'sPriceMax') {
    return $def_cur . $value;

  } else if($type == 'sPattern') {
    return $value;

  } else if($type == 'user' || $type == 'sUser' || $type == 'userId') {
    if(is_numeric($value)) {
      $usr = osc_get_user_row($value);
      return (@$usr['s_name'] <> '' ? @$usr['s_name'] : $value);
    } else {
      return $value;
    }
    
  } else if($type == 'notFromUserId') {
    if(is_numeric($value)) {
      $usr = osc_get_user_row($value);
      return (@$usr['s_name'] <> '' ? @$usr['s_name'] : $value);
    } else {
      return $value;
    }

  }  else if($type == 'bPic') {
    if($format == 'long') {
      return ($value == 1 ? __('With picture only', 'zeta') : __('Without picture only', 'zeta'));
    } else {
      return ($value == 1 ? __('Yes', 'zeta') : __('No', 'zeta'));
    }
    
  }  else if($type == 'bPremium') {
    if($format == 'long') {
      return ($value == 1 ? __('Only premium', 'zeta') : __('Only non-premium', 'zeta'));
    } else {
      return ($value == 1 ? __('Yes', 'zeta') : __('No', 'zeta'));
    }

  }  else if($type == 'bPhone') {
    if($format == 'long') {
      return ($value == 1 ? __('Only with phone', 'zeta') : __('Only without phone', 'zeta'));
    } else {
      return ($value == 1 ? __('Yes', 'zeta') : __('No', 'zeta'));
    }

  }
}


// GET PARAMETER NICE NAME
function zet_param_name($param) {
  if($param == 'sTransaction') {
    return __('Transaction', 'zeta');

  } else if($param == 'sCondition') {
    return __('Condition', 'zeta');
    
  } else if($param == 'sCompany') {
    return __('Seller type', 'zeta');

  } else if($param == 'user' || $param == 'sUser' || $param == 'userId') {
    return __('From user', 'zeta');

  } else if($param == 'notFromUserId') {
    return __('Not from user', 'zeta');
    
  } else if($param == 'sCategory' || $param == 'category') {
    return __('Category', 'zeta');

  } else if($param == 'sPeriod') {
    return __('Item age', 'zeta');

  } else if($param == 'sCountry' || $param == 'country') {
    return __('Country', 'zeta');

  } else if($param == 'sRegion' || $param == 'region') {
    return __('Region', 'zeta');

  } else if($param == 'sCity' || $param == 'city') {
    return __('City', 'zeta');

  } else if($param == 'bPic') {
    return __('With picture', 'zeta');

  } else if($param == 'bPremium') {
    return __('Only premium', 'zeta');

  } else if($param == 'bPhone') {
    return __('With phone', 'zeta');

  } else if($param == 'sPriceMin') {
    return __('Min price', 'zeta');

  } else if($param == 'sPriceMax') {
    return __('Max price', 'zeta');

  } else if($param == 'sPattern') {
    return __('Keyword', 'zeta');

  } else if($param == 'iRadius') {
    return __('Radius', 'zeta');

  } 

  return '';
}


// LIST AVAILABLE OPTIONS
function zet_list_options($type, $is_publish = false, $is_mobile_search = false) {
  $opt = array();

  if($type == 'condition') {
    $all_string = !$is_publish ? __('All', 'zeta') : __('Select a condition...', 'zeta');
    $all_string = ($is_mobile_search ? __('Any condition', 'zeta') : $all_string);
    
    $opt[0] = $all_string;
    $opt[1] = __('New', 'zeta');
    $opt[2] = __('Used', 'zeta');

  } else if($type == 'transaction') {
    $all_string = !$is_publish ? __('All', 'zeta') : __('Select a transaction...', 'zeta');
    $all_string = ($is_mobile_search ? __('Any transaction', 'zeta') : $all_string);
    
    $opt[0] = $all_string;
    $opt[1] = __('Sell', 'zeta');
    $opt[2] = __('Buy', 'zeta');
    $opt[3] = __('Rent', 'zeta');
    $opt[4] = __('Exchange', 'zeta');

  } else if($type == 'period') {
    $all_string = ($is_mobile_search ? __('Any period', 'zeta') : __('All', 'zeta'));

    $opt[0] = $all_string;
    $opt[1] = __('Yesterday', 'zeta');
    $opt[7] = __('Last week', 'zeta');
    $opt[14] = __('Last 2 weeks', 'zeta');
    $opt[31] = __('Last month', 'zeta');
    $opt[365] = __('Last year', 'zeta');

  } else if($type == 'seller_type') {
    $all_string = ($is_mobile_search ? __('Any seller', 'zeta') : __('All', 'zeta'));

    $opt[0] = $all_string;
    $opt[1] = __('Personal', 'zeta');
    $opt[2] = __('Company', 'zeta');

  } else if($type == 'radius') {
    $measure = __('km', 'zeta');
    $opt[0] = '0 ' . $measure;
    $opt[20] = '20 ' . $measure;
    $opt[50] = '50 ' . $measure;
    $opt[100] = '100 ' . $measure;
    $opt[200] = '200 ' . $measure;
  }
  
  return $opt;
}


// GET SIMPLE OPTION NAME
function zet_get_simple_name($id, $type, $include_null = true, $is_mobile_search = false) {
  if($include_null === false && ($id == '' || $id == 0)) {
    return '';
  }
  
  $options = zet_list_options($type, false, $is_mobile_search);
  return @$options[$id];
}


// GET COUNTRY FLAG, IF EXISTS
function zet_country_flag_image($code) {
  if($code != '' && file_exists(osc_base_path() . zet_oc_content_folder() . '/themes/zeta/images/country_flags/large/' . strtolower($code) . '.png')) {
    return osc_current_web_theme_url() . 'images/country_flags/large/' . strtolower($code) . '.png';
  } 
  
  return osc_current_web_theme_url() . 'images/country_flags/large/default.png';
}


// GET COUNTRY COUNT
function zet_count_countries() {
  if(!View::newInstance()->_exists('zet_countries')) {
    View::newInstance()->_exportVariableToView('zet_countries', Country::newInstance()->listAll());
  }
  
  return View::newInstance()->_count('zet_countries');
}


// GET DEFAULT CARD VIEW
function zet_get_search_view() {
  $def_view = 'grid';
  
  if(zet_param('def_view') == 1) {
    $def_view = 'list';
  } else if(zet_param('def_view') == 2) {
    $def_view = 'detail';
  }
  
  if(Params::getParam('sShowAs') == '') {
    $view = $def_view;
  } else {
    $view = (Params::getParam('sShowAs') == 'gallery' ? 'grid' : Params::getParam('sShowAs'));  
  }
  
  if(!in_array($view, array('grid', 'list', 'detail'))) {
    $view = 'grid';  
  }
  
  return $view;  
}


// GET CORRECT FANCYBOX URL
function zet_item_fancy_url($type, $params = array()) {
  if(osc_rewrite_enabled()) {
    $url = '?type=' . $type;
    $login_url = osc_user_login_url() . '?loginRequired=1&type='. $type;
  } else {
    $url = '&type=' . $type;
    $login_url = osc_user_login_url() . '&loginRequired=1&type=' . $type;
  }

  $extra = '';
  
  if($type == 'contact' || $type == 'contact_public') {
    if(osc_reg_user_can_contact() && !osc_is_web_user_logged_in()) {
      return $login_url;
    }
  } else if($type == 'comment') {
    if(osc_reg_user_post_comments() && !osc_is_web_user_logged_in()) {
      return $login_url;
    }
  }

  if(!empty($params) && is_array($params)) {
    foreach($params as $n => $v) {
      $extra .= '&' . $n . '=' . $v;
    }
  }

  return zet_item_send_friend_url() . $url . $extra;
}


// RECOGNIZE LOGIN URL PARAMS
function zet_login_redirect_required() {
  if(Params::getParam('loginRequired') == 1) {
    if(Params::getParam('type') == 'contact' || Params::getParam('type') == 'contact_public') {
      osc_add_flash_info_message(__('You must be logged in to contact publisher', 'zeta'));
    } else if(Params::getParam('type') == 'comment') {
      osc_add_flash_info_message(__('You must be logged in to add a comment', 'zeta'));
    }
  }
}

osc_add_hook('init', 'zet_login_redirect_required');


// CUSTOM SEND FRIEND URL
function zet_item_send_friend_url($item_id = '') {
  if($item_id <= 0) {
    $item_id = (osc_item_id() > 0 ? osc_item_id() : osc_premium_id());
  }

  if(osc_rewrite_enabled()) {
    return osc_base_url() . osc_get_preference('rewrite_item_send_friend') . '/' . $item_id;
  } else {
    return osc_base_url(true)."?page=item&action=send_friend&id=" . $item_id;
  }
}


// GET CORRECT BLOCK ON REGISTER PAGE
function zet_reg_url($type) {
  if(osc_rewrite_enabled()) {
    $reg_url = '?move=' . $type;
  } else {
    $reg_url = '&move=' . $type;
  }

 return osc_register_account_url() . $reg_url;
}


// UPDATE PAGINATION ARROWS
function zet_fix_arrow($data) {
  $data = str_replace('&laquo;', '<i class="fas fa-step-backward"></i>', $data);
  $data = str_replace('&raquo;', '<i class="fas fa-step-forward"></i>', $data);
  $data = str_replace('&lt;', '<i class="fas fa-angle-left"></i>', $data);
  $data = str_replace('&gt;', '<i class="fas fa-angle-right"></i>', $data);
  
  return $data;
}


// CREATE PAGINATION NEXT-PREV BUTTONS BASED ON PAGINATION
function zet_create_next_prev_btn() {
  $params = array();
  if(View::newInstance()->_exists('search_uri')) { // CANONICAL URL
    $params['url'] = osc_base_url().View::newInstance()->_get('search_uri') . '/{PAGE}';
    $params['first_url'] = osc_base_url().View::newInstance()->_get('search_uri');
  } else {
    $params['url'] = osc_update_search_url(array('iPage' => '{PAGE}'));
    $params['first_url'] = osc_update_search_url(array('iPage' => null));
  }
  
  $pagination = new Pagination($params);
  $pages = $pagination->get_pages();

  $prev_link = '';
  if(isset($pages['prev']) && $pages['prev'] <> '') {
    if($pages['prev'] == 1 ) {
      $prev_link = str_replace(array(urlencode('{PAGE}'), '{PAGE}'), '', $params['first_url']);
    } else {
      $prev_link = str_replace(array(urlencode('{PAGE}'), '{PAGE}') , array($pages['prev'], $pages['prev']), $params['url']);
    }
  }
  
  $next_link = '';
  if(isset($pages['next']) && $pages['next'] <> '' && $pages['next'] > 1) {
    $next_link = str_replace(array(urlencode('{PAGE}'), '{PAGE}') , array($pages['next'], $pages['next']), $params['url']);
  }

  return array(
    'prev' => $prev_link,
    'next' => $next_link
  );  
}


// GET THEME PARAM
function zet_param($name) {
  return osc_get_preference($name, 'theme-zeta');
}


// CHECK IF PRICE ENABLED ON CATEGORY
function zet_check_category_price($id) {
  if(!osc_price_enabled_at_items()) {
    return false;
  } else if(!isset($id) || $id == '' || $id <= 0) {
    return true;
  } else {
    $category = zet_get_category($id);
    if(isset($category['b_price_enabled'])) {
      return ($category['b_price_enabled'] == 0 ? false : true);
    }
    
    return true;
  }
}


// RTL LANGUAGE SUPPORT
function zet_is_rtl() {
  $current_lang = strtolower(osc_current_user_locale());
  $locale = osc_get_current_user_locale();
  
  if(isset($locale['b_rtl']) && $locale['b_rtl'] == 1) {
    return true;
  } else if(in_array(osc_current_user_locale(), zet_rtl_languages())) {
    return true;
  } else {
    return false;
  }
}

// GET DIRECTION STRING
function zet_language_dir() {
  return zet_is_rtl() ? 'rtl' : 'ltr';
}

// LIST ALL RTL LANGUAGES/LOCALES FOR OLDER OSCLASS VERSIONS
function zet_rtl_languages() {
  $langs = array('ar_LB','ar_DZ','ar_BH','ar_EG','ar_IQ','ar_JO','ar_KW','ar_LY','ar_MA','ar_OM','ar_SA','ar_SY','fa_IR','ar_TN','ar_AE','ar_YE','ar_TD','ar_CO','ar_DJ','ar_ER','ar_MR','ar_SD');
  return $langs;
}


// FLAT CATEGORIES CONTENT (Publish)
function zet_flat_categories() {
  return '<div id="flat-cat-fancy" style="display:none;overflow:hidden;">' . zet_category_loop() . '</div>';
}


// SMART DATE
function zet_smart_date($time) {
  $time_diff = round(abs(time() - strtotime($time)) / 60);
  $time_diff_h = floor($time_diff/60);
  $time_diff_d = floor($time_diff/1440);
  $time_diff_w = floor($time_diff/10080);
  $time_diff_m = floor($time_diff/43200);
  $time_diff_y = floor($time_diff/518400);


  if($time_diff < 2) {
    $time_diff_name = __('minute ago', 'zeta');
  } else if($time_diff < 60) {
    $time_diff_name = sprintf(__('%d minutes ago', 'zeta'), $time_diff);
  } else if($time_diff < 120) {
    $time_diff_name = sprintf(__('%d hour ago', 'zeta'), $time_diff_h);
  } else if($time_diff < 1440) {
    $time_diff_name = sprintf(__('%d hours ago', 'zeta'), $time_diff_h);
  } else if($time_diff < 2880) {
    $time_diff_name = sprintf(__('%d day ago', 'zeta'), $time_diff_d);
  } else if($time_diff < 10080) {
    $time_diff_name = sprintf(__('%d days ago', 'zeta'), $time_diff_d);
  } else if($time_diff < 20160) {
    $time_diff_name = sprintf(__('%d week ago', 'zeta'), $time_diff_w);
  } else if($time_diff < 43200) {
    $time_diff_name = sprintf(__('%d weeks ago', 'zeta'), $time_diff_w);
  } else if($time_diff < 86400) {
    $time_diff_name = sprintf(__('%d month ago', 'zeta'), $time_diff_m);
  } else if($time_diff < 518400) {
    $time_diff_name = sprintf(__('%d months ago', 'zeta'), $time_diff_m);
  } else if($time_diff < 1036800) {
    $time_diff_name = sprintf(__('%d year ago', 'zeta'), $time_diff_y);
  } else {
    $time_diff_name = sprintf(__('%d years ago', 'zeta'), $time_diff_y);
  }

  return $time_diff_name;
}


// SMART DATE2
function zet_smart_date2($time, $with_time = false) {
  $is_today = (date('Y-m-d', strtotime($time)) == date('Y-m-d'));
  $is_yesterday = (date('Y-m-d', strtotime($time)) == date('Y-m-d', strtotime('-1 day')));
  $is_this_week = (date('Y-m-d', strtotime($time)) >= date('Y-m-d', strtotime('-7 day')));
  $week_day = date('N', strtotime($time));
  $time_diff = round(abs(time() - strtotime($time)) / 60);
  $time_diff_h = floor($time_diff/60);
  $time_diff_d = floor($time_diff/1440);
  $time_diff_w = floor($time_diff/10080);
  $time_diff_m = floor($time_diff/43200);
  $time_diff_y = floor($time_diff/518400);


  if($is_today) {
    $time_diff_name = __('Today', 'zeta');

    if($with_time) {
      $time_diff_name .= ' ' . date('H:i', strtotime($time));
    }
    
  } else if($is_today) {
    $time_diff_name = __('Yesterday', 'zeta');

    if($with_time) {
      $time_diff_name .= ' ' . date('H:i', strtotime($time));
    }

  } else if($is_this_week) {
    $time_diff_name = zet_week_day_name($week_day);

    if($with_time) {
      $time_diff_name .= ' ' . date('H:i', strtotime($time));
    }

  } else if($time_diff < 10080) {
    $time_diff_name = sprintf(__('%d days', 'zeta'), $time_diff_d);
  } else if($time_diff < 20160) {
    $time_diff_name = sprintf(__('%d week', 'zeta'), $time_diff_w);
  } else if($time_diff < 43200) {
    $time_diff_name = sprintf(__('%d weeks', 'zeta'), $time_diff_w);
  } else if($time_diff < 86400) {
    $time_diff_name = sprintf(__('%d month', 'zeta'), $time_diff_m);
  } else if($time_diff < 518400) {
    $time_diff_name = sprintf(__('%d months', 'zeta'), $time_diff_m);
  } else if($time_diff < 1036800) {
    $time_diff_name = sprintf(__('%d year', 'zeta'), $time_diff_y);
  } else if ($time_diff > 0) {
    $time_diff_name = sprintf(__('%d years', 'zeta'), $time_diff_y);
  } else {
    $time_diff_name = osc_format_date($time);
  }
  
  return $time_diff_name;
}


// GET WEEK DAY NAME
function zet_week_day_name($num, $short = false) {
  $name = '';
  
  switch($num) {
    case 1: $name = __('Monday', 'zeta'); break;
    case 2: $name = __('Tuesday', 'zeta'); break;
    case 3: $name = __('Wednesday', 'zeta'); break;
    case 4: $name = __('Thursday', 'zeta'); break;
    case 5: $name = __('Friday', 'zeta'); break;
    case 6: $name = __('Saturday', 'zeta'); break;
    case 7: $name = __('Sunday', 'zeta'); break;
  }
  
  if($short !== false && $short > 0) {
    return substr($name, 0, $short);
  }
  
  return $name;
}


// CHECK IF ITEM MARKED AS SOLD-UNSOLD
function zet_check_sold(){
  $status = Params::getParam('markSold');
  $item_id = Params::getParam('itemId');
  $secret = Params::getParam('secret');
  $item_type = Params::getParam('itemType');

  if($status <> '' && $item_id <> '' && $item_id > 0) {
    $item = osc_get_item_row($item_id);

    if($secret == $item['s_secret']) {
      $item_extra = zet_item_extra($item_id);
      
      if(isset($item_extra['fk_i_item_id']) && $item_extra['fk_i_item_id'] > 0 && @$item_extra['found'] != 'NOTFOUND') {
        ModelZET::newInstance()->updateItemExtra($item_id, array('i_sold' => $status));
      } else {
        ModelZET::newInstance()->insertItemExtra(array('fk_i_item_id' => $item_id, 'i_sold' => $status));
      }
 
      if(osc_rewrite_enabled()) {
        $item_type_url = '?itemType=' . $item_type;
      } else {
        $item_type_url = '&itemType=' . $item_type;
      }

      header('Location: ' . osc_user_list_items_url() . $item_type_url);
      exit;
    }
  }
}

osc_add_hook('header', 'zet_check_sold');



// HELP FUNCTION TO GET CATEGORIES
function zet_category_loop($parent_id = NULL, $parent_color = NULL) {
  $parent_color = isset($parent_color) ? $parent_color : NULL;

  if(Params::getParam('sCategory') <> '') {
    $id = Params::getParam('sCategory');
  } else if(zet_get_session('sCategory') <> '' && (osc_is_publish_page() || osc_is_edit_page())) {
    $id = zet_get_session('sCategory');
  } else if(osc_item_category_id() <> '') {
    $id = osc_item_category_id();
  } else {
    $id = '';
  }


  if($parent_id <> '' && $parent_id > 0) {
    $categories = Category::newInstance()->findSubcategoriesEnabled($parent_id);
  } else {
    $parent_id = 0;
    $categories = Category::newInstance()->findRootCategoriesEnabled();
  }

  $html = '<div class="flat-wrap' . ($parent_id == 0 ? ' root' : '') . '" data-parent-id="' . $parent_id . '">';
  $html .= '<div class="single info">' . __('Select category', 'zeta') . ' ' . ($parent_id <> 0 ? '<span class="back tr1 round2"><i class="fa fa-angle-left"></i> ' . __('Back', 'zeta') . '</span>' : '') . '</div>';

  foreach($categories as $c) {
    if($parent_id == 0) {
      $parent_color = zet_get_cat_color($c['pk_i_id'], $c);
      $icon = '<div class="parent-icon" style="background:' . zet_get_cat_color($c['pk_i_id'], $c) . ';">' . zet_get_cat_icon($c['pk_i_id'], $c) . '</div>';
    } else {
      $icon = '<div class="parent-icon children" style="background: ' . $parent_color . '">' . zet_get_cat_icon($c['pk_i_id'], $c) . '</div>';
    }
    
    $html .= '<div class="single tr1' . ($c['pk_i_id'] == $id ? ' selected' : '') . '" data-id="' . $c['pk_i_id'] . '"><span>' . $icon . $c['s_name'] . '</span></div>';

    $subcategories = Category::newInstance()->findSubcategoriesEnabled($c['pk_i_id']);
    if(isset($subcategories[0])) {
      $html .= zet_category_loop($c['pk_i_id'], $parent_color);
    }
  }
  
  $html .= '</div>';
  return $html;
}



// FLAT CATEGORIES SELECT (Publish)
function zet_flat_category_select(){  
  $root = Category::newInstance()->findRootCategoriesEnabled();

  $html = '<div class="category-box tr1">';
  foreach($root as $c) {
    $html .= '<div class="option tr1" style="background:' . zet_get_cat_color($c['pk_i_id'], $c) . ';">' . zet_get_cat_icon($c['pk_i_id'], $c) . '</div>';
  }
 
  $html .= '</div>';
  return $html;
}



// GET CITY, REGION, COUNTRY FOR AJAX LOADER
function zet_ajax_city() {
  $user = osc_user();
  $item = osc_item();

  if(Params::getParam('sCity') <> '') {
    return Params::getParam('sCity');
  } else if(isset($item['fk_i_city_id']) && $item['fk_i_city_id'] <> '') {
    return $item['fk_i_city_id'];
  } else if(isset($user['fk_i_city_id']) && $user['fk_i_city_id'] <> '') {
    return $user['fk_i_city_id'];
  }
}


function zet_ajax_region() {
  $user = osc_user();
  $item = osc_item();

  if(Params::getParam('sRegion') <> '') {
    return Params::getParam('sRegion');
  } else if(isset($item['fk_i_region_id']) && $item['fk_i_region_id'] <> '') {
    return $item['fk_i_region_id'];
  } else if(isset($user['fk_i_region_id']) && $user['fk_i_region_id'] <> '') {
    return $user['fk_i_region_id'];
  }
}


function zet_ajax_country() {
  $user = osc_user();
  $item = osc_item();

  if(Params::getParam('sCountry') <> '') {
    return Params::getParam('sCountry');
  } else if(isset($item['fk_c_country_code']) && $item['fk_c_country_code'] <> '') {
    return $item['fk_c_country_code'];
  } else if(isset($user['fk_c_country_code']) && $user['fk_c_country_code'] <> '') {
    return $user['fk_c_country_code'];
  }
}


// CARD DESIGN OPTIONS
function zet_card_designs() {
  return array(
    'verywide' => __('Very wide', 'zeta'), 
    'wide' => __('Wide', 'zeta'), 
    'normal' => __('Normal', 'zeta'), 
    'square' => __('Square', 'zeta'), 
    'tall' => __('Tall', 'zeta'), 
    'verytall' => __('Very tall', 'zeta'), 
    'extratall' => __('Extra tall', 'zeta'),
    'megatall' => __('Mega tall', 'zeta')
  );
}


// GET CURRENT URL
function zet_current_url() {
  $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  return $actual_link;
}


// USER MENU - SIDE MENU - ONLY HOOKS
function zet_user_menu_side() {
  osc_run_hook('user_menu_items');
  osc_run_hook('user_menu');
}


function zet_user_items_url($type = '') {
  $url = osc_user_list_items_url();
  
  if($type != '') {
    if(strpos($url, '?') !== false) {
      $url .= '&itemType=' . $type;
    } else {
      $url .= '?itemType=' . $type;
    }
  }
  
  return $url;  
}

function zet_item_sold_reserved_url($type = '', $item_extra = array()) {
  $url = osc_user_list_items_url();
  $flag = (@$item_extra['i_sold'] > 0 ? $item_extra['i_sold'] : 0);
  
  if($type != '') {
    if(strpos($url, '?') !== false) {
      $url .= '&';
    } else {
      $url .= '?';
    }
    
    if($type == 'sold') {
      $url .= 'itemId=' . osc_item_id() . '&markSold=' . ($flag == 1 ? 0 : 1) . '&secret=' . osc_item_field('s_secret');
    } else if($type == 'reserved') { 
      $url .= 'itemId=' . osc_item_id() . '&markSold=' . ($flag == 2 ? 0 : 2) . '&secret=' . osc_item_field('s_secret');
    }
  }
  
  return $url;  
}


// ALERT FREQUENCY MAPPING
function zet_alert_frequency($type) {
  if($type == 'DAILY') {
    return __('daily', 'zeta'); 
  } else if($type == 'WEEKLY') {
    return __('weekly', 'zeta'); 
  } else if($type == 'MONTHLY') {
    return __('monthly', 'zeta'); 
  } else if($type == 'INSTANT') {
    return __('instant', 'zeta'); 
  }
}


// USER ACCOUNT - MENU ELEMENTS
function zet_user_menu() {
  $current_url = zet_current_url();
  $c_active = Item::newInstance()->countItemTypesByUserID(osc_logged_user_id(), 'active');
  $c_pending = Item::newInstance()->countItemTypesByUserID(osc_logged_user_id(), 'pending_validate');
  $c_expired = Item::newInstance()->countItemTypesByUserID(osc_logged_user_id(), 'expired');
  $alerts = Alerts::newInstance()->findByUser(osc_logged_user_id());
  
  $c_alerts = 0;
  if(is_array($alerts)) {
    $c_alerts = count($alerts);
  }
  ?>
  <a href="<?php echo osc_user_public_profile_url(osc_logged_user_id()); ?>" class="public"><?php echo __('Public profile', 'zeta'); ?> <i class="ext fas fa-external-link-alt"></i></a>

  <a href="<?php echo osc_user_dashboard_url(); ?>" class="<?php echo (strpos(osc_user_dashboard_url(), $current_url) !== false ? 'active' : ''); ?>"><?php echo __('Dashboard', 'zeta'); ?></a>
  <a href="<?php echo zet_user_items_url('active'); ?>" class="has-counter<?php echo (strpos(zet_user_items_url('active'), $current_url) !== false ? 'active' : ''); ?>">
    <?php echo __('Active listings', 'zeta'); ?>
    <span class="counter"><?php echo $c_active; ?></span>
  </a>
  
  <?php if($c_pending > 0) { ?>
    <a href="<?php echo zet_user_items_url('pending_validate'); ?>" class="has-counter<?php echo (strpos(zet_user_items_url('pending_validate'), $current_url) !== false ? 'active' : ''); ?>">
      <?php echo __('Pending listings', 'zeta'); ?>
      <span class="counter"><?php echo $c_pending; ?></span>
    </a>
  <?php } ?>
  
  <?php if($c_expired > 0) { ?>
    <a href="<?php echo zet_user_items_url('expired'); ?>" class="has-counter<?php echo (strpos(zet_user_items_url('expired'), $current_url) !== false ? 'active' : ''); ?>">
      <?php echo __('Expired listings', 'zeta'); ?>
      <span class="counter"><?php echo $c_expired; ?></span>
    </a>
  <?php } ?>

  <a href="<?php echo osc_user_alerts_url(); ?>" class="<?php echo (strpos(osc_user_alerts_url(), $current_url) !== false ? 'active' : ''); ?>">
    <?php echo __('Saved searches', 'zeta'); ?>
    <span class="counter"><?php echo $c_alerts; ?></span>
  </a>
  
  <a href="<?php echo osc_user_profile_url(); ?>" class="<?php echo (strpos(osc_user_profile_url(), $current_url) !== false ? 'active' : ''); ?>"><?php echo __('My Profile', 'zeta'); ?></a>
  
  <div class="menu-hooks">
    <?php
      osc_run_hook('user_menu_items');
      osc_run_hook('user_menu');
    ?>
  </div>
  
  <a href="<?php echo osc_user_logout_url(); ?>" class="logout"><i class="fas fa-sign-out-alt"></i> <?php echo __('Logout', 'zeta'); ?></a>
  <?php
}


// GET TERM NAME BASED ON COUNTRY, REGION & CITY
// function zet_get_term($term = '', $country = '', $region = '', $city = ''){
  // if($term == '') {
    // if($city <> '' && is_numeric($city)) {
      // $city_info = osc_get_city_row($city);
      // return (osc_location_native_name_selector($city_info, 's_name') <> '' ? osc_location_native_name_selector($city_info, 's_name') : $city);
    // }
 
    // if($region <> '' && is_numeric($region)) {
      // $region_info = osc_get_region_row($region);
      // return (osc_location_native_name_selector($region_info, 's_name') <> '' ? osc_location_native_name_selector($region_info, 's_name') : $region);
    // }

    // if($country <> '' && strlen($country) == 2) {
      // $country_info = osc_get_country_row($country);
      // return (osc_location_native_name_selector($country_info, 's_name') <> '' ? osc_location_native_name_selector($country_info, 's_name') : $country);
    // }

    // $array = array_filter(array($city, $region, $country));
    // return @$array[0]; // if all fail, return first non-empty

  // } else {
    // return $term;
  // }
// }


// GET LOCATION FULL NAME BASED ON COUNTRY, REGION & CITY
// function zet_get_full_loc($country = '', $region = '', $city = ''){
  // if($city <> '' && is_numeric($city)) {
    // $city_info = osc_get_city_row($city);
    // $region_info = osc_get_region_row($city_info['fk_i_region_id']);
    // $country_info = osc_get_country_row($city_info['fk_c_country_code']);
    // return osc_location_native_name_selector($city_info, 's_name') . ', ' . osc_location_native_name_selector($region_info, 's_name') . ', ' . osc_location_native_name_selector($country_info, 's_name');
  // }

  // if($region <> '' && is_numeric($region)) {
    // $region_info = osc_get_region_row($region);
    // $country_info = osc_get_country_row($region_info['fk_c_country_code']);

    // return osc_location_native_name_selector($region_info, 's_name') . ', ' . osc_location_native_name_selector($country_info, 's_name');
  // }

  // if($country <> '' && strlen($country) == 2) {
    // $country_info = osc_get_country_row($country);
    // return osc_location_native_name_selector($country_info, 's_name');
  // }

  // return '';
// }



// ADD TRANSACTION AND CONDITION TO OC-ADMIN EDIT ITEM
function zet_extra_add_admin($catId = null, $item_id = null){
  if(defined('OC_ADMIN') && OC_ADMIN === true) {
    if($item_id > 0) {
      $item = osc_get_item_row($item_id);
      $item_extra = zet_item_extra($item_id);
      ?>
      
      <div class="control-group">
        <label class="control-label" for="sTransaction"><?php _e('Transaction', 'zeta'); ?></label>
        <div class="controls"><?php echo zet_simple_transaction(true, $item_id <> '' ? $item_id : false); ?></div>
      </div>

      <div class="control-group">
        <label class="control-label" for="sCondition"><?php _e('Condition', 'zeta'); ?></label>
        <div class="controls"><?php echo zet_simple_condition(true, $item_id <> '' ? $item_id : false); ?></div>
      </div>

      <?php if(!method_exists('ItemForm', 'contact_phone_text')) { ?>
        <div class="control-group">
        <label class="control-label" for="sPhone"><?php _e('Phone', 'zeta'); ?></label>
        <div class="controls"><input type="text" name="sPhone" id="sPhone" value="<?php echo osc_esc_html($item_extra['s_phone']); ?>" /></div>
        </div>
      <?php } ?>
      
      <div class="control-group">
        <label class="control-label" for="sSold"><?php _e('Status', 'zeta'); ?></label>
        <div class="controls">
          <select name="sSold">
            <option value="" <?php if($item_extra['i_sold'] == '') { ?>selected="selected"<?php } ?>><?php _e('Select a status...', 'zeta'); ?></option>
            <option value="2" <?php if($item_extra['i_sold'] == 2) { ?>selected="selected"<?php } ?>><?php _e('Reserved', 'zeta'); ?></option>
            <option value="1" <?php if($item_extra['i_sold'] == 1) { ?>selected="selected"<?php } ?>><?php _e('Sold', 'zeta'); ?></option>
          </select>
        </div>
      </div>
      
      <?php
    }
  }
}

osc_add_hook('item_form', 'zet_extra_add_admin');
osc_add_hook('item_edit', 'zet_extra_add_admin');



function zet_extra_edit($item) {
  $item['pk_i_id'] = isset($item['pk_i_id']) ? $item['pk_i_id'] : 0;
  $detail = ModelAisItem::newInstance()->findByItemId($item['pk_i_id']);

  if(isset($detail['fk_i_item_id'])) {
    ModelAisItem::newInstance()->updateItemMeta($item['pk_i_id'], Params::getParam('ais_meta_title'), Params::getParam('ais_meta_description'));
  } else {
    ModelAisItem::newInstance()->insertItemMeta($item['pk_i_id'], Params::getParam('ais_meta_title'), Params::getParam('ais_meta_description'));
  } 
}


// SIMPLE SEARCH SORT
function zet_simple_sort() {
  $type = Params::getParam('sOrder');           // date - price
  $order = Params::getParam('iOrderType');      // asc - desc

  $orders = osc_list_orders();

  $params = zet_search_params_all();


  //$html = '<input type="hidden" name="sOrder" id="sOrder" val="' . $type . '"/>';
  //$html = '<input type="hidden" name="iOrderType" id="iOrderType" val="' . $order . '"/>';


  $html = '<select class="orderSelect" id="orderSelect">';
  
  foreach($orders as $label => $spec) {
    $selected = '';
    if($spec['sOrder'] == $type && $spec['iOrderType'] == $order) {
      $selected = ' selected="selected"';
    }
 
    $params['sOrder'] = $spec['sOrder'];
    $params['iOrderType'] = $spec['iOrderType'];
    
    $html .= '<option' . $selected . ' data-type="' . $spec['sOrder'] . '" data-order="' . $spec['iOrderType'] . '" data-link="' . osc_search_url($params) . '" value="' . $spec['sOrder'] . '-' . $spec['iOrderType'] . '">' . $label . '</option>';
  }

  $html .= '</select>';
 

  return $html;
}


// SIMPLE CATEGORY SELECT
function zet_simple_category($select = false, $level = 3, $id = 'sCategory', $parent_id = NULL) {
  if($parent_id !== NULL) {
    $categories = Category::newInstance()->toSubTree($parent_id);
  } else {
    $categories = Category::newInstance()->toTree();
  }
  
  // Use select box on mobile, otherwise styled box
  if($select === 'auto') {
    $select = zet_is_mobile();
  }

  $allow_parent = ($id == 'catId' ? osc_get_preference('selectable_parent_categories', 'osclass') : 1);
  $selected_id = 0;
  
  if($parent_id !== NULL) {
    $current = $parent_id;
    $selected_id = @osc_search_category_id()[0];

  } else {
    $current = @osc_search_category_id()[0];

    if($id == 'catId') {   // publish-edit listing page
      $current = osc_item_category_id();
    }
  }

  $c_category = zet_get_category($current);
  $root = Category::newInstance()->toRootTree($current);
  $root = isset($root[0]) ? $root[0] : array('pk_i_id' => $current, 's_name' => (isset($c_category['s_name']) ? $c_category['s_name'] : ''));

  if(!$select) {
    $html = '<div class="simple-cat simple-select input-box' . ($current != '' ? ' active' : '') . ($parent_id > 0 ? ' with-parent' : '') . ' level' . $level . '">';
    $html .= '<input type="hidden" id="' . $id . '" name="' . $id . '" class="input-hidden ' . $id . '" value="' . $current . '"/>';
    $html .= '<span class="text round3 tr1">';
    $html .= '<span>' . (@$c_category['s_name'] <> '' ? $c_category['s_name'] : __('Category', 'zeta')) . '</span>';
    // $html .= '<i class="fa fa-angle-down"></i>';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" class="caret" viewBox="0 0 320 512" width="20" height="20"><path d="M151.5 347.8L3.5 201c-4.7-4.7-4.7-12.3 0-17l19.8-19.8c4.7-4.7 12.3-4.7 17 0L160 282.7l119.7-118.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17l-148 146.8c-4.7 4.7-12.3 4.7-17 0z"/></svg>';
    $html .= '</span>';
    $html .= '<div class="list nice-scroll">';
    $html .= '<div class="option info">' . __('Filter by category', 'zeta') . '</div>';

    if($id <> 'catId' && $parent_id === NULL) {
      $html .= '<div class="option bold' . ($root['pk_i_id'] == "" ? ' selected' : '') . '" data-id="">' . __('All', 'zeta') . '</div>';
    }

    // Current (parent) category
    if($parent_id > 0) {
      $html .= '<div class="option bold' . ($c_category['pk_i_id'] == $selected_id ? ' selected' : '') . '" data-id="' . $c_category['pk_i_id'] . '">' . $c_category['s_name'] . '</div>';
    }
    
    // Root cat
    foreach($categories as $c) {
      $disable = false;
      if($allow_parent == 0 && count(@$c['categories']) > 0) { $disable = true; }

      $html .= '<div class="option ' . ($disable ? 'nonclickable' : '') . ' root' . ($root['pk_i_id'] == $c['pk_i_id'] ? ' selected' : '') . '" data-id="' . $c['pk_i_id'] . '">' . $c['s_name'] . '</span></div>';

      // Sub cat level 1
      if(count(@$c['categories']) > 0 && $level >= 1) { 
        foreach($c['categories'] as $s1) {
          $disable = false;
          if($allow_parent == 0 && count($s1['categories']) > 0) { $disable = true; }

          $html .= '<div class="option ' . ($disable ? 'nonclickable' : '') . ' sub1' . ($current == $s1['pk_i_id'] ? ' selected' : '') . '" data-id="' . $s1['pk_i_id'] . '">' . $s1['s_name'] . '</span></div>';

          // Sub cat level 2
          if(count($s1['categories']) > 0 && $level >= 2) { 
            foreach($s1['categories'] as $s2) {
              $disable = false;
              if($allow_parent == 0 && count($s2['categories']) > 0) { $disable = true; }

              $html .= '<div class="option ' . ($disable ? 'nonclickable' : '') . ' sub2' . ($current == $s2['pk_i_id'] ? ' selected' : '') . '" data-id="' . $s2['pk_i_id'] . '">' . $s2['s_name'] . '</span></div>';

              // Sub cat level 3
              if(count($s2['categories']) > 0 && $level >= 3) { 
                foreach($s2['categories'] as $s3) {
                  $html .= '<div class="option sub3' . ($current == $s3['pk_i_id'] ? ' selected' : '') . '" data-id="' . $s3['pk_i_id'] . '">' . $s3['s_name'] . '</span></div>';
                }
              }

            }
          }
        }
      }
    }

    $html .= '</div>';
    $html .= '</div>';

    return $html;

  } else {
    $html = '<select class="' . $id . '" id="' . $id . '" name="' . $id . '">';
    $html .= '<option value="" ' . ($root['pk_i_id'] == "" ? ' selected="selected"' : '') . '>' . __('All categories', 'zeta') . '</option>';

    foreach($categories as $c) {
      $html .= '<option ' . ($root['pk_i_id'] == $c['pk_i_id'] ? ' selected="selected"' : '') . ' value="' . $c['pk_i_id'] . '">' . $c['s_name'] . '</option>';

      // Sub cat level 1
      if(count(@$c['categories']) > 0 && $level >= 1) { 
        foreach($c['categories'] as $s1) {
          $html .= '<option ' . ($current == $s1['pk_i_id'] ? ' selected="selected"' : '') . ' value="' . $s1['pk_i_id'] . '">- ' . $s1['s_name'] . '</option>';

          // Sub cat level 2
          if(count($s1['categories']) > 0 && $level >= 2) { 
            foreach($s1['categories'] as $s2) {
              $html .= '<option ' . ($current == $s2['pk_i_id'] ? ' selected="selected"' : '') . ' value="' . $s2['pk_i_id'] . '">-- ' . $s2['s_name'] . '</option>';

              // Sub cat level 3
              if(count($s2['categories']) > 0 && $level >= 3) { 
                foreach($s2['categories'] as $s3) {
                  $html .= '<option ' . ($current == $s3['pk_i_id'] ? ' selected="selected"' : '') . ' value="' . $s3['pk_i_id'] . '">--- ' . $s3['s_name'] . '</option>';
                }
              }

            }
          }
        }
      }
    }

    $html .= '</select>';

    return $html;

  }
}


// SIMPLE SELLER TYPE SELECT
function zet_simple_seller($select = false) {
  $id = Params::getParam('sCompany');

  if($id !== '' && $id !== null) {
    $id_mod = (int)$id + 1;
  } else {
    $id_mod = 0;
  }

  // Use select box on mobile, otherwise styled box
  if($select === 'auto') {
    $select = zet_is_mobile();
  }
  
  $name = zet_get_simple_name($id_mod, 'seller_type', true, $select);
  $name = ($id_mod == 0 ? __('Seller type', 'zeta') : $name);

  if(!$select) {
    $html = '<div class="simple-select input-box simple-seller' . ($id != '' ? ' active' : '') . '">';
    $html .= '<input type="hidden" id="sCompany" name="sCompany" class="input-hidden" value="' . $id . '"/>';
    $html .= '<span class="text round3 tr1">';
    $html .= '<span>' . $name . '</span>';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" class="caret" viewBox="0 0 320 512" width="20" height="20"><path d="M151.5 347.8L3.5 201c-4.7-4.7-4.7-12.3 0-17l19.8-19.8c4.7-4.7 12.3-4.7 17 0L160 282.7l119.7-118.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17l-148 146.8c-4.7 4.7-12.3 4.7-17 0z"/></svg>';
    $html .= '</span>';
    $html .= '<div class="list nice-scroll">';
    $html .= '<div class="option info">' . __('Filter by seller type', 'zeta') . '</div>';
    $html .= '<div class="option' . ($id_mod == "0" ? ' selected' : '') . '" data-id="">' . __('All sellers', 'zeta') . '</div>';
    $html .= '<div class="option' . ($id_mod == "1" ? ' selected' : '') . '" data-id="0">' . __('Personal', 'zeta') . '</div>';
    $html .= '<div class="option' . ($id_mod == "2" ? ' selected' : '') . '" data-id="1">' . __('Company', 'zeta') . '</div>';
    $html .= '</div>';
    $html .= '</div>';
    
  } else {
    $html = '<select class="sCompany" id="sCompany" name="sCompany">';
    $html .= '<option value="" ' . ($id_mod == "0" ? ' selected="selected"' : '') . '>' . $name . '</option>';
    $html .= '<option value="0" ' . ($id_mod == "1" ? ' selected="selected"' : '') . '>' . __('Personal', 'zeta') . '</option>';
    $html .= '<option value="1" ' . ($id_mod == "2" ? ' selected="selected"' : '') . '>' . __('Company', 'zeta') . '</option>';
    $html .= '</select>';
  }
  
  return $html;

}


// LOCATION PICKER - ADVANCED
function zet_advanced_location($with_inputs = true, $class = '') {
  ?>
  <div class="input-box picker v2 location has-icon <?php echo $class; ?>">
    <?php if($with_inputs) { ?>
      <input type="hidden" name="sCountry" value="<?php echo osc_esc_html(Params::getParam('sCountry')); ?>"/>
      <input type="hidden" name="sRegion" value="<?php echo osc_esc_html(Params::getParam('sRegion')); ?>"/>
      <input type="hidden" name="sCity" value="<?php echo osc_esc_html(Params::getParam('sCity')); ?>"/>
    <?php } ?>

    <input name="sLocation" type="text" class="location-pick" id="sLocation" placeholder="<?php echo osc_esc_html(__('Country, region or city', 'zeta')); ?>" data-placeholder="<?php echo osc_esc_html(__('Country, region or city', 'zeta')); ?>" value="<?php echo osc_esc_html(zet_get_search_location()); ?>" autocomplete="off"/>
    <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="20" height="20"><path d="M192 0C85.903 0 0 86.014 0 192c0 71.117 23.991 93.341 151.271 297.424 18.785 30.119 62.694 30.083 81.457 0C360.075 285.234 384 263.103 384 192 384 85.903 297.986 0 192 0zm0 464C64.576 259.686 48 246.788 48 192c0-79.529 64.471-144 144-144s144 64.471 144 144c0 54.553-15.166 65.425-144 272zm-80-272c0-44.183 35.817-80 80-80s80 35.817 80 80-35.817 80-80 80-80-35.817-80-80z"/></svg>
    <i class="clean fas fa-times-circle"></i>
    
    <div class="results nice-scroll">
      <div class="loaded"></div>
      <div class="default"><?php echo zet_default_location_content(); ?></div>
    </div>
  </div>
  <?php 
}


// PATTERN PICKER - ADVANCED
function zet_advanced_pattern($type = 'advanced', $with_inputs = true, $class = '') {
  $placeholder = __('Search for anything', 'zeta');
  
  if($type == 'advanced' && osc_is_search_page()) {
    $search_cat_id = osc_search_category_id();
    $search_cat_id = isset($search_cat_id[0]) ? $search_cat_id[0] : '';

    $category = zet_get_category($search_cat_id);
    
    if(isset($category['s_name']) && $category['s_name'] != '') {
      $placeholder = sprintf(__('Search for anything in %s', 'zeta'), $category['s_name']);
    }
  }
  ?>
  <div class="input-box picker v2 pattern type-<?php echo $type; ?> has-icon <?php echo $class; ?>">
    <?php if($with_inputs) { ?>
      <input type="hidden" name="sPattern" value="<?php echo osc_esc_html(Params::getParam('sPattern')); ?>"/>
      <?php if($type == 'advanced') { ?><input type="hidden" name="sCategory" value="<?php echo osc_esc_html(Params::getParam('sCategory')); ?>"/><?php } ?>
    <?php } ?>

    <input type="text" name="sPattern" class="pattern" placeholder="<?php echo osc_esc_html($placeholder); ?>" value="<?php echo osc_esc_html(Params::getParam('sPattern')); ?>" autocomplete="off"/>
    <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z"/></svg>
    <i class="clean fas fa-times-circle"></i>
    
    
    <div class="results nice-scroll">
      <div class="loaded"></div>
      <div class="default"><?php echo zet_default_pattern_content($type); ?></div>
    </div>
  </div>
  <?php
}



// SIMPLE TRANSACTION TYPE SELECT
function zet_simple_transaction($select = false, $is_publish = false, $item_id = false) {
  if((osc_is_publish_page() || osc_is_edit_page()) && zet_get_session('sTransaction') <> '') {
    $id = zet_get_session('sTransaction');
  } else {
    $id = Params::getParam('sTransaction');
  }
  
  // Use select box on mobile, otherwise styled box
  if($select === 'auto') {
    $select = zet_is_mobile();
  }

  if($item_id == '') {
    $item_id = osc_item_id();
  }

  if($item_id > 0) {
    $id = zet_item_extra($item_id);
    $id = $id['i_transaction'];
  }

  $name = zet_get_simple_name($id, 'transaction', true, $select);
  $name = ($name == '' ? __('Transaction', 'zeta') : $name);

  $options =  zet_list_options('transaction', $is_publish, $select);


  if(!$select) {
    $html = '<div class="simple-select input-box simple-transaction' . ($id != '' ? ' active' : '') . '">';
    $html .= '<input type="hidden" id="sTransaction" name="sTransaction" class="input-hidden" value="' . $id . '"/>';
    $html .= '<span class="text round3 tr1">';
    $html .= '<span>' . $name . '</span>';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" class="caret" viewBox="0 0 320 512" width="20" height="20"><path d="M151.5 347.8L3.5 201c-4.7-4.7-4.7-12.3 0-17l19.8-19.8c4.7-4.7 12.3-4.7 17 0L160 282.7l119.7-118.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17l-148 146.8c-4.7 4.7-12.3 4.7-17 0z"/></svg>';
    $html .= '</span>';
    $html .= '<div class="list nice-scroll">';
    $html .= '<div class="option info">' . __('Filter by transaction', 'zeta') . '</div>';
    
    foreach($options as $n => $v) {
      $html .= '<div class="option' . ((int)$id == $n ? ' selected' : '') . '" data-id="' . ($n == 0 ? '' : $n) . '">' . ($n == 0 ? __('All transactions', 'zeta') : $v) . '</div>';
    }
  
    $html .= '</div>';
    $html .= '</div>';
    
  } else {
    $html = '<select class="sTransaction' . ($is_publish ? ' mini' : '') . '" id="sTransaction" name="sTransaction">';

    foreach($options as $n => $v) {
      $html .= '<option value="' . ($n == 0 ? '' : $n) . '" ' . ((int)$id == $n ? ' selected="selected"' : '') . '>' . $v . '</option>';
    }

    $html .= '</select>';
  }
  
  return $html;
}


// SIMPLE OFFER TYPE SELECT
function zet_simple_condition($select = false, $is_publish = false, $item_id = false) {
  if((osc_is_publish_page() || osc_is_edit_page()) && zet_get_session('sCondition') <> '') {
    $id = zet_get_session('sCondition');
  } else {
    $id = Params::getParam('sCondition');
  }

  // Use select box on mobile, otherwise styled box
  if($select === 'auto') {
    $select = zet_is_mobile();
  }

  if($item_id == '') {
    $item_id = osc_item_id();
  }

  if($item_id > 0) {
    $id = zet_item_extra($item_id);
    $id = $id['i_condition'];
  }

  $name = zet_get_simple_name($id, 'condition', true, $select);
  $name = ($name == '' ? __('Condition', 'zeta') : $name);

  $options = zet_list_options('condition', $is_publish, $select);


  if(!$select) {
    $html = '<div class="simple-select input-box simple-condition' . ($id != '' ? ' active' : '') . '">';
    $html .= '<input type="hidden" id="sCondition" name="sCondition" class="input-hidden" value="' . $id . '"/>';
    $html .= '<span class="text round3 tr1">';
    $html .= '<span>' . $name . '</span>';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" class="caret" viewBox="0 0 320 512" width="20" height="20"><path d="M151.5 347.8L3.5 201c-4.7-4.7-4.7-12.3 0-17l19.8-19.8c4.7-4.7 12.3-4.7 17 0L160 282.7l119.7-118.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17l-148 146.8c-4.7 4.7-12.3 4.7-17 0z"/></svg>';
    $html .= '</span>';
    $html .= '<div class="list nice-scroll">';
    $html .= '<div class="option info">' . __('Filter by condition', 'zeta') . '</div>';
    
    foreach($options as $n => $v) {
      $html .= '<div class="option' . ((int)$id == $n ? ' selected' : '') . '" data-id="' . ($n == 0 ? '' : $n) . '">' . ($n == 0 ? __('All conditions', 'zeta') : $v) . '</div>';
    }
  
    $html .= '</div>';
    $html .= '</div>';
    
  } else { 
    $html = '<select class="sCondition' . ($is_publish ? ' mini' : '') . '" id="sCondition" name="sCondition">';

    foreach($options as $n => $v) {
      $html .= '<option value="' . ($n == 0 ? '' : $n) . '" ' . ((int)$id == $n ? ' selected="selected"' : '') . '>' . $v . '</option>';
    }

    $html .= '</select>';
  }
  
  return $html;
}



// SIMPLE RADIUS SELECT
function zet_simple_radius($select = false) {
  $id = Params::getParam('iRadius');
  
  // Use select box on mobile, otherwise styled box
  if($select === 'auto') {
    $select = zet_is_mobile();
  }

  $name = zet_get_simple_name($id, 'radius', true, $select);
  $options = zet_list_options('radius', false, $select);

  if($id > 0 && !in_array($id, $options)) {
    $measure = __('km', 'zeta');
    $name = $id . ' ' . $measure;
  }

  $name = ($name == '' ? __('Area', 'zeta') : $name);


  if(!$select) {
    $html = '<div class="simple-select input-box simple-radius' . ($id != '' ? ' active' : '') . '">';
    $html .= '<input type="hidden" id="iRadius" name="iRadius" class="input-hidden" value="' . $id . '"/>';
    $html .= '<span class="text round3 tr1">';
    $html .= '<span>' . $name . '</span>';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" class="caret" viewBox="0 0 320 512" width="20" height="20"><path d="M151.5 347.8L3.5 201c-4.7-4.7-4.7-12.3 0-17l19.8-19.8c4.7-4.7 12.3-4.7 17 0L160 282.7l119.7-118.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17l-148 146.8c-4.7 4.7-12.3 4.7-17 0z"/></svg>';
    $html .= '</span>';
    $html .= '<div class="list nice-scroll">';
    $html .= '<div class="option info">' . __('Search area', 'zeta') . '</div>';
    
    foreach($options as $n => $v) {
      $html .= '<div class="option' . ((int)$id == $n ? ' selected' : '') . '" data-id="' . ($n == 0 ? '' : $n) . '">' . $v . '</div>';
    }
  
    $html .= '</div>';
    $html .= '</div>';
    
  } else { 
    $html = '<select class="iRadius" id="iRadius" name="iRadius">';

    foreach($options as $n => $v) {
      $html .= '<option value="' . ($n == 0 ? '' : $n) . '" ' . ((int)$id == $n ? ' selected="selected"' : '') . '>' . $v . '</option>';
    }

    $html .= '</select>';
  }
  
  return $html;
}



// SIMPLE COUNTRY SELECT
function zet_simple_country($select = false) {
  $id = Params::getParam('sCountry');
  
  // Use select box on mobile, otherwise styled box
  if($select === 'auto') {
    $select = zet_is_mobile();
  }

  $options = Country::newInstance()->listAll();
  
  if(!is_array($options) || count($options) <= 1) {
    return false;
  }
  
  $selected = osc_get_country_row($id);
  $name = isset($selected['s_name']) ? $selected['s_name'] : __('Country', 'zeta');

  if(!$select) {
    $html = '<div class="simple-select input-box simple-country' . ($id != '' ? ' active' : '') . '">';
    $html .= '<input type="hidden" id="sCountry" name="sCountry" class="input-hidden" value="' . $id . '"/>';
    $html .= '<span class="text round3 tr1">';
    $html .= '<span>' . $name . '</span>';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" class="caret" viewBox="0 0 320 512" width="20" height="20"><path d="M151.5 347.8L3.5 201c-4.7-4.7-4.7-12.3 0-17l19.8-19.8c4.7-4.7 12.3-4.7 17 0L160 282.7l119.7-118.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17l-148 146.8c-4.7 4.7-12.3 4.7-17 0z"/></svg>';
    $html .= '</span>';
    $html .= '<div class="list nice-scroll">';
    $html .= '<div class="option info">' . __('Filter by country', 'zeta') . '</div>';

    $html .= '<div class="option' . ($id == '' ? ' selected' : '') . '" data-id="">' . __('All countries', 'zeta') . '</div>';

    foreach($options as $n => $v) {
      $html .= '<div class="option' . ($id == $opt['pk_c_code'] ? ' selected' : '') . '" data-id="' . $opt['pk_c_code'] . '">' . $opt['s_name'] . '</div>';
    }
  
    $html .= '</div>';
    $html .= '</div>';
    
  } else { 
    $html = '<select class="sCountry" id="sCountry" name="sCountry">';
    $html .= '<option value="" ' . ($id == '' ? ' selected="selected"' : '') . '>' . __('All countries', 'zeta') . '</option>';

    foreach($options as $opt) {
      $html .= '<option value="' . $opt['pk_c_code'] . '" ' . ($id == $opt['pk_c_code'] ? ' selected="selected"' : '') . '>' . $opt['s_name'] . '</option>';
    }

    $html .= '</select>';
  }
  
  return $html;
}


// SIMPLE REGION SELECT
function zet_simple_region($select = false) {
  $parent = Params::getParam('sCountry');
  $id = Params::getParam('sRegion');
  
  // Use select box on mobile, otherwise styled box
  if($select === 'auto') {
    $select = zet_is_mobile();
  }

  $options = array();
  if(zet_count_countries() <= 1) {
    $options = Region::newInstance()->listAll();
    
  } else if($parent != '') {
    $options = Region::newInstance()->findByCountry($parent); 
  }
  
  if(!is_array($options) || count($options) <= 0) {
    return false;
  }
  
  $selected = osc_get_region_row($id);
  $name = isset($selected['s_name']) ? $selected['s_name'] : __('Region', 'zeta');

  if(!$select) {
    $html = '<div class="simple-select input-box simple-region' . ($id != '' ? ' active' : '') . '">';
    $html .= '<input type="hidden" id="sRegion" name="sRegion" class="input-hidden" value="' . $id . '"/>';
    $html .= '<span class="text round3 tr1">';
    $html .= '<span>' . $name . '</span>';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" class="caret" viewBox="0 0 320 512" width="20" height="20"><path d="M151.5 347.8L3.5 201c-4.7-4.7-4.7-12.3 0-17l19.8-19.8c4.7-4.7 12.3-4.7 17 0L160 282.7l119.7-118.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17l-148 146.8c-4.7 4.7-12.3 4.7-17 0z"/></svg>';
    $html .= '</span>';
    $html .= '<div class="list nice-scroll">';
    $html .= '<div class="option info">' . __('Filter by region', 'zeta') . '</div>';

    $html .= '<div class="option' . ($id == '' ? ' selected' : '') . '" data-id="">' . __('All regions', 'zeta') . '</div>';

    foreach($options as $n => $v) {
      $html .= '<div class="option' . ($id == $opt['pk_i_id'] ? ' selected' : '') . '" data-id="' . $opt['pk_i_id'] . '">' . $opt['s_name'] . '</div>';
    }
  
    $html .= '</div>';
    $html .= '</div>';
    
  } else { 
    $html = '<select class="sRegion" id="sRegion" name="sRegion">';
    $html .= '<option value="" ' . ($id == '' ? ' selected="selected"' : '') . '>' . __('All regions', 'zeta') . '</option>';

    foreach($options as $opt) {
      $html .= '<option value="' . $opt['pk_i_id'] . '" ' . ($id == $opt['pk_i_id'] ? ' selected="selected"' : '') . '>' . $opt['s_name'] . '</option>';
    }

    $html .= '</select>';
  }
  
  return $html;
}



// SIMPLE CITY SELECT
function zet_simple_city($select = false) {
  $parent = Params::getParam('sRegion');
  $id = Params::getParam('sCity');
  
  // Use select box on mobile, otherwise styled box
  if($select === 'auto') {
    $select = zet_is_mobile();
  }

  $options = City::newInstance()->findByRegion($parent); 
  
  if(!is_array($options) || count($options) <= 0) {
    return false;
  }
  
  $selected = osc_get_city_row($id);
  $name = isset($selected['s_name']) ? $selected['s_name'] : __('City', 'zeta');

  if(!$select) {
    $html = '<div class="simple-select input-box simple-city' . ($id != '' ? ' active' : '') . '">';
    $html .= '<input type="hidden" id="sCity" name="sCity" class="input-hidden" value="' . $id . '"/>';
    $html .= '<span class="text round3 tr1">';
    $html .= '<span>' . $name . '</span>';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" class="caret" viewBox="0 0 320 512" width="20" height="20"><path d="M151.5 347.8L3.5 201c-4.7-4.7-4.7-12.3 0-17l19.8-19.8c4.7-4.7 12.3-4.7 17 0L160 282.7l119.7-118.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17l-148 146.8c-4.7 4.7-12.3 4.7-17 0z"/></svg>';
    $html .= '</span>';
    $html .= '<div class="list nice-scroll">';
    $html .= '<div class="option info">' . __('Filter by city', 'zeta') . '</div>';

    $html .= '<div class="option' . ($id == '' ? ' selected' : '') . '" data-id="">' . __('All cities', 'zeta') . '</div>';

    foreach($options as $n => $v) {
      $html .= '<div class="option' . ($id == $opt['pk_i_id'] ? ' selected' : '') . '" data-id="' . $opt['pk_i_id'] . '">' . $opt['s_name'] . '</div>';
    }
  
    $html .= '</div>';
    $html .= '</div>';
    
  } else { 
    $html = '<select class="sCity" id="sCity" name="sCity">';
    $html .= '<option value="" ' . ($id == '' ? ' selected="selected"' : '') . '>' . __('All cities', 'zeta') . '</option>';

    foreach($options as $opt) {
      $html .= '<option value="' . $opt['pk_i_id'] . '" ' . ($id == $opt['pk_i_id'] ? ' selected="selected"' : '') . '>' . $opt['s_name'] . '</option>';
    }

    $html .= '</select>';
  }
  
  return $html;
}


// SIMPLE PRICE SELECT
function zet_simple_price_min_max() {
  $id_min = Params::getParam('sPriceMin');
  $id_max = Params::getParam('sPriceMax');
  $def_cur = (zet_param('def_cur') <> '' ? zet_param('def_cur') : '$');
  
  $name_min = zet_remove_value_name($id_min, 'sPriceMin');
  $name_max = zet_remove_value_name($id_max, 'sPriceMax');
  
  $name = __('Price', 'zeta');
  
  if($id_min != '' && $id_max == '') {
    $name = sprintf(__('Price > %s', 'zeta'), $name_min);
    
  } else if($id_min == '' && $id_max != '') {
    $name = sprintf(__('Price < %s', 'zeta'), $name_max);

  } else if($id_min != '' && $id_max != '') {
    $name = sprintf(__('Price: %s to %s', 'zeta'), $name_min, $name_max);
  }

  $html = '<div class="simple-select input-box simple-price-min-max' . (($id_min != '' || $id_max != '') ? ' active' : '') . '">';
  $html .= '<span class="text round3 tr1">';
  $html .= '<span>' . $name . '</span>';
  $html .= '<svg xmlns="http://www.w3.org/2000/svg" class="caret" viewBox="0 0 320 512" width="20" height="20"><path d="M151.5 347.8L3.5 201c-4.7-4.7-4.7-12.3 0-17l19.8-19.8c4.7-4.7 12.3-4.7 17 0L160 282.7l119.7-118.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17l-148 146.8c-4.7 4.7-12.3 4.7-17 0z"/></svg>';
  $html .= '</span>';
  $html .= '<div class="list inputs between">';
  $html .= '<div class="option info">' . __('Filter by price', 'zeta') . '</div>';

  $html .= '<div class="sinfo">' . __('Define minimum and maximum price:', 'zeta') . '</div>';

  $html .= '<div class="swrap">';
  $html .= '<input type="number" name="sPriceMin" value="' . osc_esc_html(Params::getParam('sPriceMin')) . '" placeholder="' . osc_esc_html(sprintf(__('Min %s', 'zeta'), $def_cur)) . '"/>';
  $html .= '<div class="del2">-</div>';
  $html .= '<input type="number" name="sPriceMax" value="' . osc_esc_html(Params::getParam('sPriceMax')) . '" placeholder="' . osc_esc_html(sprintf(__('Max %s', 'zeta'), $def_cur)) . '"/>';
  $html .= '</div>';
  $html .= '</div>';
  $html .= '</div>';
  
  return $html;
}



// SIMPLE LOCATION SELECT
function zet_simple_location($with_inputs = true) {
  $id1 = Params::getParam('sCountry');
  $id2 = Params::getParam('sRegion');
  $id3 = Params::getParam('sCity');
  
  $name = __('Location', 'zeta');
  $active = false;
  
  if($id1 != '' || $id2 != '' || $id3 != '') {
    $name = zet_get_search_location();
    $active = true;
  }

  $html = '<div class="simple-select input-box simple-location' . ($active == true ? ' active' : '') . '">';
  
  if($with_inputs) { 
    $html .= '<input type="hidden" name="sCountry" value="' . osc_esc_html(Params::getParam('sCountry')) . '"/>';
    $html .= '<input type="hidden" name="sRegion" value="' . osc_esc_html(Params::getParam('sRegion')) . '"/>';
    $html .= '<input type="hidden" name="sCity" value="' . osc_esc_html(Params::getParam('sCity')) . '"/>';
  }

  $html .= '<span class="text round3 tr1">';
  $html .= '<span>' . $name . '</span>';
  $html .= '<svg xmlns="http://www.w3.org/2000/svg" class="caret" viewBox="0 0 320 512" width="20" height="20"><path d="M151.5 347.8L3.5 201c-4.7-4.7-4.7-12.3 0-17l19.8-19.8c4.7-4.7 12.3-4.7 17 0L160 282.7l119.7-118.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17l-148 146.8c-4.7 4.7-12.3 4.7-17 0z"/></svg>';
  $html .= '</span>';
  $html .= '<div class="list inputs spec">';
  $html .= '<div class="option info">' . __('Filter by location', 'zeta') . '</div>';

  //$html .= '<div class="sinfo">' . __('Start typing location name:', 'zeta') . '</div>';

  $html .= '<div class="swrap">';

  $html .= '<div class="input-box picker v2 location has-icon">';
  $html .= '<input name="sLocation" type="text" class="location-pick" id="sLocation" placeholder="' . osc_esc_html(__('Country, region or city', 'zeta')) . '" data-placeholder="' . osc_esc_html(__('Country, region or city', 'zeta')) . '" value="' . osc_esc_html($active == true ? $name : '') . '" autocomplete="off"/>';
  $html .= '<svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="20" height="20"><path d="M192 0C85.903 0 0 86.014 0 192c0 71.117 23.991 93.341 151.271 297.424 18.785 30.119 62.694 30.083 81.457 0C360.075 285.234 384 263.103 384 192 384 85.903 297.986 0 192 0zm0 464C64.576 259.686 48 246.788 48 192c0-79.529 64.471-144 144-144s144 64.471 144 144c0 54.553-15.166 65.425-144 272zm-80-272c0-44.183 35.817-80 80-80s80 35.817 80 80-35.817 80-80 80-80-35.817-80-80z"/></svg>';
  $html .= '<i class="clean fas fa-times-circle"></i>';

  $html .= '<div class="results nice-scroll">';
  $html .= '<div class="loaded"></div>';
  $html .= '<div class="default">' . zet_default_location_content() . '</div>';
  $html .= '</div>';
  $html .= '</div>';

  $html .= '</div>';
  $html .= '</div>';
  $html .= '</div>';
  
  return $html;
}


// SIMPLE PRICE SELECT
function zet_simple_pattern() {
  $id = Params::getParam('sPattern');
  $name = __('Keyword', 'zeta');
  
  if($id != '') {
    $name = osc_esc_html($id);
  }

  $html = '<div class="simple-select input-box simple-pattern' . ($id != '' ? ' active' : '') . '">';
  $html .= '<span class="text round3 tr1">';
  $html .= '<span>' . $name . '</span>';
  $html .= '<svg xmlns="http://www.w3.org/2000/svg" class="caret" viewBox="0 0 320 512" width="20" height="20"><path d="M151.5 347.8L3.5 201c-4.7-4.7-4.7-12.3 0-17l19.8-19.8c4.7-4.7 12.3-4.7 17 0L160 282.7l119.7-118.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17l-148 146.8c-4.7 4.7-12.3 4.7-17 0z"/></svg>';
  $html .= '</span>';
  $html .= '<div class="list inputs one">';
  $html .= '<div class="option info">' . __('Filter by keyword', 'zeta') . '</div>';

  $html .= '<div class="sinfo">' . __('Enter one or more keywords to filter results:', 'zeta') . '</div>';

  $html .= '<div class="swrap">';
  $html .= '<div class="input-box">';
  $html .= '<input type="text" name="sPattern" value="' . osc_esc_html(Params::getParam('sPattern')) . '" placeholder="' . osc_esc_html(__('Search for anything', 'zeta')) . '"/>';
  $html .= '<i class="clean fas fa-times-circle"></i>';
  $html .= '</div>';
  $html .= '</div>';
  $html .= '</div>';
  $html .= '</div>';
  
  return $html;
}



// SIMPLE CURRENCY SELECT (publish)
function zet_simple_currency() {
  $currencies = osc_get_currencies();
  $item = osc_item(); 

  if((osc_is_publish_page() || osc_is_edit_page()) && zet_get_session('currency') <> '') {
    $id = zet_get_session('currency');
  } else {
    $id = Params::getParam('currency');
  }

  $currency = $id <> '' ? $id : osc_get_preference('currency', 'osclass');

  if(isset($item['fk_c_currency_code'])) {
    $default_key = $item['fk_c_currency_code'];
  } elseif(isset($currency) && $currency <> '') {
    $default_key = $currency;
  } else {
    $default_key = $currencies[0]['pk_c_code'];
  }
 
  $html = '<select class="currency" id="currency" name="currency">';
  foreach($currencies as $c) {
    $html .= '<option value="' . $c['pk_c_code'] . '"' . ($c['pk_c_code'] == $default_key ? ' selected="selected"' : '') . '>' . $c['pk_c_code'] . ' (' . $c['s_description'] . ')</option>';
  }

  $html .= '</select>';

  return $html;
}



// SIMPLE PRICE TYPE SELECT (publish)
function zet_simple_price_type() {
  $item = osc_item(); 

  // Item edit
  if(isset($item['i_price'])) {
    if($item['i_price'] > 0) {
      $default_key = 0;
      $default_name = '<i class="fa fa-pencil help"></i> ' . __('Enter price', 'zeta');
    } else if($item['i_price'] == 0) {
      $default_key = 1;
      $default_name = '<i class="fa fa-cut help"></i> ' . __('Free', 'zeta');
    } else if($item['i_price'] == '') {
      $default_key = 2;
      $default_name = '<i class="fa fa-phone help"></i> ' . __('Check with seller', 'zeta');
    } 
  
  // Item publish
  } else {
    $default_key = 0;
    $default_name = '<i class="fa fa-pencil help"></i> ' . __('Enter price', 'zeta');
  }


  $html = '<div class="simple-price-type simple-select">';
  $html .= '<span class="text round3 tr1"><span>' . $default_name . '</span> <i class="fa fa-angle-down"></i></span>';
  $html .= '<div class="list">';
  $html .= '<div class="option info">' . __('Choose price type', 'zeta') . '</div>';

  $html .= '<div class="option' . ($default_key == 0 ? ' selected' : '') . '" data-id="0"><i class="fa fa-pencil help"></i> ' . __('Enter price', 'zeta') . '</span></div>';
  $html .= '<div class="option' . ($default_key == 1 ? ' selected' : '') . '" data-id="1"><i class="fa fa-cut help"></i> ' . __('Free', 'zeta') . '</span></div>';
  $html .= '<div class="option' . ($default_key == 2 ? ' selected' : '') . '" data-id="2"><i class="fa fa-phone help"></i> ' . __('Check with seller', 'zeta') . '</span></div>';

  $html .= '</div>';
  $html .= '</div>';

  return $html;
}


// SIMPLE PERIOD SELECT (search only)
function zet_simple_period($select = false) {
  $id = Params::getParam('sPeriod');

  // Use select box on mobile, otherwise styled box
  if($select === 'auto') {
    $select = zet_is_mobile();
  }
  
  $name = zet_get_simple_name($id, 'period', true, $select);
  $name = ($name == '' ? __('Age', 'zeta') : $name);

  $options =  zet_list_options('period', false, $select);


  if(!$select) {
    $html = '<div class="simple-select input-box simple-period' . ($id != '' ? ' active' : '') . '">';
    $html .= '<input type="hidden" id="sPeriod" name="sPeriod" class="input-hidden" value="' . $id . '"/>';
    $html .= '<span class="text round3 tr1">';
    $html .= '<span>' . $name . '</span>';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" class="caret" viewBox="0 0 320 512" width="20" height="20"><path d="M151.5 347.8L3.5 201c-4.7-4.7-4.7-12.3 0-17l19.8-19.8c4.7-4.7 12.3-4.7 17 0L160 282.7l119.7-118.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17l-148 146.8c-4.7 4.7-12.3 4.7-17 0z"/></svg>';
    $html .= '</span>';
    $html .= '<div class="list nice-scroll">';
    $html .= '<div class="option info">' . __('Filter by item age', 'zeta') . '</div>';
    
    foreach($options as $n => $v) {
      $html .= '<div class="option' . ((int)$id == $n ? ' selected' : '') . '" data-id="' . ($n == 0 ? '' : $n) . '">' . ($n == 0 ? __('Include all', 'zeta') : $v) . '</div>';
    }
  
    $html .= '</div>';
    $html .= '</div>';
    
  } else { 
    $html = '<select class="sPeriod" id="sPeriod" name="sPeriod">';

    foreach($options as $n => $v) {
      $html .= '<option value="' . ($n == 0 ? '' : $n)   . '" ' . ((int)$id == $n ? ' selected="selected"' : '') . '>' . $v . '</option>';
    }

    $html .= '</select>';
  }
  
  return $html;
}


// SIMPLE PERIOD LIST
function zet_simple_period_list($is_mobile = false) {
  $id = Params::getParam('sPeriod');

  // Use select box on mobile, otherwise styled box
  if($is_mobile == 'auto') {
    $is_mobile = zet_is_mobile();
  }
  
  $name = zet_get_simple_name($id, 'period', true, $is_mobile);
  $name = ($name == '' ? __('Age', 'zeta') : $name);

  $options =  zet_list_options('period', false, $is_mobile);
  $params = zet_search_params_all();


  $html = '<div class="simple-period simple-list">';
  $html .= '<input type="hidden" name="sPeriod" class="input-hidden" value="' . $id . '"/>';

  $html .= '<div class="list link-check-box">';

  foreach($options as $n => $v) {
    if($n > 0) {
      $params['sPeriod'] = $n;
    } else {
      unset($params['sPeriod']);
    }
    
    $html .= '<a href="' . osc_search_url($params) . '" ' . ((int)$id == $n ? 'class="active"' : '') . ' data-name="sPeriod" data-val="' . $n . '">' . $v . '</a>';
  }

  $html .= '</div>';
  $html .= '</div>';

  return $html;
}


// SIMPLE TRANSACTION LIST
function zet_simple_transaction_list($is_mobile = false) {
  $id = Params::getParam('sTransaction');

  // Use select box on mobile, otherwise styled box
  if($is_mobile == 'auto') {
    $is_mobile = zet_is_mobile();
  }
  
  $name = zet_get_simple_name($id, 'transaction');
  $name = ($name == '' ? __('Transaction', 'zeta') : $name);

  $options =  zet_list_options('transaction', false, $is_mobile);
  $params = zet_search_params_all();


  $html = '<div class="simple-transaction simple-list">';
  $html .= '<input type="hidden" name="sTransaction" class="input-hidden" value="' . $id . '"/>';

  $html .= '<div class="list link-check-box">';

  foreach($options as $n => $v) {
    if($n > 0) {
      $params['sTransaction'] = $n;
    } else {
      unset($params['sTransaction']);
    }
    
    $html .= '<a href="' . osc_search_url($params) . '" ' . ((int)$id == $n ? 'class="active"' : '') . ' data-name="sTransaction" data-val="' . $n . '">' . $v . '</a>';
  }

  $html .= '</div>';
  $html .= '</div>';

  return $html;
}


// SIMPLE CONDITION LIST
function zet_simple_condition_list($is_mobile = false) {
  $id = Params::getParam('sCondition');

  // Use select box on mobile, otherwise styled box
  if($is_mobile == 'auto') {
    $is_mobile = zet_is_mobile();
  }
  
  $name = zet_get_simple_name($id, 'condition');
  $name = ($name == '' ? __('Condition', 'zeta') : $name);

  $options =  zet_list_options('condition', false, $is_mobile);
  $params = zet_search_params_all();


  $html = '<div class="simple-condition simple-list">';
  $html .= '<input type="hidden" name="sCondition" class="input-hidden" value="' . $id . '"/>';

  $html .= '<div class="list link-check-box">';

  foreach($options as $n => $v) {
    if($n > 0) {
      $params['sCondition'] = $n;
    } else {
      unset($params['sCondition']);
    }
    
    $html .= '<a href="' . osc_search_url($params) . '" ' . ((int)$id == $n ? 'class="active"' : '') . ' data-name="sCondition" data-val="' . $n . '">' . $v . '</a>';
  }

  $html .= '</div>';
  $html .= '</div>';

  return $html;
}


// SIMPLE SELLER LIST
function zet_simple_seller_list($is_mobile = false) {
  $id = Params::getParam('sCompany');

  if($id !== '' && $id !== null) {
    $id_mod = (int)$id + 1;
  } else {
    $id_mod = 0;
  }

  // Use select box on mobile, otherwise styled box
  if($is_mobile == 'auto') {
    $is_mobile = zet_is_mobile();
  }
  
  $name = zet_get_simple_name($id_mod, 'seller_type', true, $is_mobile);
  $name = ($id_mod == 0 ? __('Seller type', 'zeta') : $name);

  $options =  zet_list_options('seller_type', false, $is_mobile);
  $params = zet_search_params_all();

  $html = '<div class="simple-seller simple-list">';
  $html .= '<input type="hidden" name="sCompany" class="input-hidden" value="' . $id . '"/>';

  $html .= '<div class="list link-check-box">';

  foreach($options as $n => $v) {
    if($n === '') {
      $params['sCompany'] = $n;
    } else {
      unset($params['sCompany']);
    }
    
    $html .= '<a href="' . osc_search_url($params) . '" ' . ((int)$id == $n ? 'class="active"' : '') . ' data-name="sCompany" data-val="' . $n . '">' . $v . '</a>';
  }

  $html .= '</div>';
  $html .= '</div>';

  return $html;
}


// Cookies work
if(!function_exists('mb_set_cookie')) {
  function mb_set_cookie($name, $val) {
    Cookie::newInstance()->set_expires(86400 * 30);
    Cookie::newInstance()->push($name, $val);
    Cookie::newInstance()->set();
  }
}

if(!function_exists('mb_get_cookie')) {
  function mb_get_cookie($name) {
    return Cookie::newInstance()->get_value($name);
  }
}

if(!function_exists('mb_drop_cookie')) {
  function mb_drop_cookie($name) {
    Cookie::newInstance()->pop($name);
  }
}


// FIND ROOT CATEGORY OF SELECTED
function zet_category_root($category_id) {
  $category = Category::newInstance()->findRootCategory($category_id);
  return $category;
}


// CHECK IF THEME IS DEMO
function zet_is_demo() {
  if(defined('DEMO_THEMES') && DEMO_THEMES === true) {
    return true;
  } else if(isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'],'mb-themes') !== false || strpos($_SERVER['HTTP_HOST'],'abprofitrade') !== false)) {
    return true;
  } else {
    return false;
  }
}


// MANAGE DAILY OFFER
function zet_manage_day_offer() {
  osc_reset_preferences();
  
  if(zet_param('enable_day_offer') != 1) {
    osc_set_preference('day_offer_id', '', 'theme-zeta');
    osc_reset_preferences();
    return;
  } else {
    if(zet_param('day_offer_admin_id') > 0 || Params::getParam('day_offer_admin_id') > 0) {
      $id = (Params::getParam('day_offer_admin_id') > 0 ? Params::getParam('day_offer_admin_id') : zet_param('day_offer_admin_id'));
      $item = osc_get_item_row($id);
      
      if($item !== false && isset($item['pk_i_id'])) {
        osc_set_preference('day_offer_id', $id, 'theme-zeta');
        osc_reset_preferences();
        return;
      }
    }

    $mSearch = new Search();
    $mSearch->onlyPremium(true); 
    $mSearch->order('RAND()', '');
    $mSearch->limit(0, 1);
    $aItems = $mSearch->doSearch(); 

    if(isset($aItems[0]) && isset($aItems[0]['pk_i_id'])) {
      osc_set_preference('day_offer_id', $aItems[0]['pk_i_id'], 'theme-zeta');
      osc_reset_preferences();
      return;
    }
  }
}

osc_add_hook('cron_daily', 'zet_manage_day_offer');


// CREATE ITEM (in loop)
function zet_draw_item($c = NULL, $premium = false, $class = false) {
  if($premium){
    $filename = 'loop-single-premium.php';
  } else {
    $filename = 'loop-single.php';
  }

  if(function_exists('osc_current_web_theme_path_value')) {
    include osc_current_web_theme_path_value($filename);
  } else {
    include $filename;
  }
}


function zet_draw_placeholder_item($id = '', $class = '') {
  ?>
  <a href="<?php echo osc_esc_html(zet_param('search_premium_promote_url')); ?>" class="simple-prod placeholder o<?php echo $id; ?> <?php echo $class; ?>">
    <div class="simple-wrap">
      <div class="dx">
        <svg width="48" height="48" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M384 250v12c0 6.6-5.4 12-12 12h-98v98c0 6.6-5.4 12-12 12h-12c-6.6 0-12-5.4-12-12v-98h-98c-6.6 0-12-5.4-12-12v-12c0-6.6 5.4-12 12-12h98v-98c0-6.6 5.4-12 12-12h12c6.6 0 12 5.4 12 12v98h98c6.6 0 12 5.4 12 12zm120 6c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-32 0c0-119.9-97.3-216-216-216-119.9 0-216 97.3-216 216 0 119.9 97.3 216 216 216 119.9 0 216-97.3 216-216z"/></svg>
        <strong>- <?php _e('Your listing here', 'zeta'); ?> -</strong>
        <span><?php _e('Promote your item', 'zeta'); ?></span>
      </div>
    </div>
  </a>
  <?php
}


// RANDOM LATEST ITEMS ON HOME PAGE
function zet_random_items($numItems = 10, $category = array(), $withPicture = false) {
  $max_items = osc_get_preference('maxLatestItems@home', 'osclass');

  if($max_items == '' or $max_items == 0) {
    $max_items = 24;
  }

  $numItems = $max_items;

  $withPicture = zet_param('latest_picture');
  $randomOrder = zet_param('latest_random');
  $premiums = zet_param('latest_premium');
  $category_ids = zet_param('latest_category');

  $randSearch = Search::newInstance();
  $randSearch->dao->select(DB_TABLE_PREFIX.'t_item.* ');
  $randSearch->dao->from(DB_TABLE_PREFIX.'t_item use index (PRIMARY)');

  // where
  $whe = DB_TABLE_PREFIX.'t_item.b_active = 1 AND ';
  $whe .= DB_TABLE_PREFIX.'t_item.b_enabled = 1 AND ';
  $whe .= DB_TABLE_PREFIX.'t_item.b_spam = 0 AND ';

  if($premiums == 1) {
    $whe .= DB_TABLE_PREFIX.'t_item.b_premium = 1 AND ';
  }

  $whe .= '('.DB_TABLE_PREFIX.'t_item.b_premium = 1 OR '.DB_TABLE_PREFIX.'t_item.dt_expiration >= \''. date('Y-m-d H:i:s').'\') ';

  // if($category <> '' and $category > 0) {
    // $subcat_list = Category::newInstance()->findSubcategories($category);
    // $subcat_id = array();
    // $subcat_id[] = $category;

    // foreach($subcat_list as $s) {
      // $subcat_id[] = $s['pk_i_id'];
    // }

    // $listCategories = implode(', ', $subcat_id);
    // $whe .= ' AND '.DB_TABLE_PREFIX.'t_item.fk_i_category_id IN ('.$listCategories.') ';
  // }

  $category_ids = array_filter(array_unique(explode(',', $category_ids)));
  if(is_array($category_ids) && count($category_ids) > 0) {
    $category_ids = implode(',', $category_ids);
    $whe .= ' AND '.DB_TABLE_PREFIX.'t_item.fk_i_category_id IN ('.$category_ids.') ';
  }

  // Subdomain (multisite)
  $options = osc_get_subdomain_params();
  $tables = array();
  
  if(isset($options['sCategory']) && $options['sCategory'] != '') {
    $whe .= ' AND ' . DB_TABLE_PREFIX.'t_item.fk_i_category_id in (SELECT fk_i_category_id FROM '.DB_TABLE_PREFIX.'t_category_description WHERE s_slug = "' . osc_esc_html($options['sCategory']) . '")';
    
  } else if(isset($options['sCountry']) && $options['sCountry'] != '') {
    $tables[] = DB_TABLE_PREFIX.'t_item_location';
    $whe .= ' AND ' . DB_TABLE_PREFIX.'t_item_location.fk_i_item_id = '.DB_TABLE_PREFIX.'t_item.pk_i_id';
    $whe .= ' AND ' . DB_TABLE_PREFIX.'t_item_location.fk_c_country_code in (SELECT pk_c_code FROM '.DB_TABLE_PREFIX.'t_country WHERE s_slug = "' . osc_esc_html($options['sCountry']) . '")';
    
  } else if(isset($options['sRegion']) && $options['sRegion'] != '') {
    $tables[] = DB_TABLE_PREFIX.'t_item_location';
    $whe .= ' AND ' . DB_TABLE_PREFIX.'t_item_location.fk_i_item_id = '.DB_TABLE_PREFIX.'t_item.pk_i_id';
    $whe .= ' AND ' . DB_TABLE_PREFIX.'t_item_location.fk_i_region_id in (SELECT pk_i_id FROM '.DB_TABLE_PREFIX.'t_region WHERE s_slug = "' . osc_esc_html($options['sRegion']) . '")';
    
  } else if(isset($options['sCity']) && $options['sCity'] != '') {
    $tables[] = DB_TABLE_PREFIX.'t_item_location';
    $whe .= ' AND ' . DB_TABLE_PREFIX.'t_item_location.fk_i_item_id = '.DB_TABLE_PREFIX.'t_item.pk_i_id';
    $whe .= ' AND ' . DB_TABLE_PREFIX.'t_item_location.fk_i_city_id in (SELECT pk_i_id FROM '.DB_TABLE_PREFIX.'t_city WHERE s_slug = "' . osc_esc_html($options['sCity']) . '")';
    
  } else if(isset($options['sUser']) && $options['sUser'] != '') {
    $tables[] = DB_TABLE_PREFIX.'t_user';
    $whe .= ' AND ' . DB_TABLE_PREFIX.'t_item.fk_i_user_id = '.DB_TABLE_PREFIX.'t_user.pk_i_id';
    $whe .= ' AND ' . DB_TABLE_PREFIX.'t_user.s_username = "' . osc_esc_html($options['sUser']) . '"';

  }

  $tables = implode(', ', $tables);
  $tables = ($tables <> '' ? ', ' : '') . $tables;

  if($withPicture) {
    $prem_where = ' AND ' . $whe;

    $randSearch->dao->from('(' . sprintf("select %st_item.pk_i_id FROM %st_item, %st_item_resource %s WHERE %st_item_resource.s_content_type LIKE '%%image%%' AND %st_item.pk_i_id = %st_item_resource.fk_i_item_id %s GROUP BY %st_item.pk_i_id ORDER BY %st_item.dt_pub_date DESC LIMIT %s", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, $tables, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, $prem_where, DB_TABLE_PREFIX, DB_TABLE_PREFIX, $numItems) . ') AS LIM');
  } else {
    $prem_where = ' WHERE ' . $whe;

    $randSearch->dao->from('(' . sprintf("select %st_item.pk_i_id FROM %st_item %s %s ORDER BY %st_item.dt_pub_date DESC LIMIT %s", DB_TABLE_PREFIX, DB_TABLE_PREFIX, $tables, $prem_where, DB_TABLE_PREFIX, $numItems) . ') AS LIM');
  }

  $randSearch->dao->where(DB_TABLE_PREFIX.'t_item.pk_i_id = LIM.pk_i_id');
  

  // group by & order & limit
  $randSearch->dao->groupBy(DB_TABLE_PREFIX.'t_item.pk_i_id');

  if(!$randomOrder) {
    $randSearch->dao->orderBy(DB_TABLE_PREFIX.'t_item.dt_pub_date DESC');
  } else {
    $randSearch->dao->orderBy('RAND()');
  }
  

  $randSearch->dao->limit($numItems);

  $rs = $randSearch->dao->get();

  if($rs === false){
    return array();
  }
  if($rs->numRows() == 0) {
    return array();
  }

  $items = $rs->result();
  return Item::newInstance()->extendData($items);
}


// RANDOM LATEST ITEMS ON HOME PAGE
function zet_premium_items($limit = 10, $exclude_ids = array(), $category_id = NULL, $with_picture = true) {
  if(View::newInstance()->_exists('zet_home_premium_items')) {
    return View::newInstance()->_count('zet_home_premium_items');
  }
  
  $limit = ($limit > 0 ? $limit : 4);

  $search = Search::newInstance();
  $search->dao->select('i.*');
  $search->dao->from(DB_TABLE_PREFIX.'t_item as i use index (PRIMARY)');

  // where
  $search->dao->where('i.b_active = 1');
  $search->dao->where('i.b_enabled = 1');
  $search->dao->where('i.b_spam = 0');
  $search->dao->where('i.b_premium = 1');

  if(is_array($exclude_ids) && count($exclude_ids) > 0) {
    $exclude_ids = implode(',', $exclude_ids);
    $search->dao->where('i.pk_i_id NOT IN (' . $exclude_ids . ')');
  } else if(!is_array($exclude_ids) && $exclude_ids > 0) { 
    $search->dao->where('i.pk_i_id <> ' . $exclude_ids);
  }
  
  if($category_id > 0) {
    $subcat_list = Category::newInstance()->findSubcategories($category_id);
    $subcat_id = array();
    $subcat_id[] = $category_id;

    foreach($subcat_list as $s) {
      $subcat_id[] = $s['pk_i_id'];
    }

    $listCategories = implode(',', $subcat_id);

    if($listCategories != '') {
      $search->dao->where('i.fk_i_category_id IN ('.$listCategories.')');
    }
  }

  if($with_picture == true) {
    $search->dao->where('i.pk_i_id in (SELECT fk_i_item_id FROM '.DB_TABLE_PREFIX.'t_item_resource WHERE s_content_type LIKE "%image%")'); 
  }
 
  // group by & order & limit
  $search->dao->groupBy('i.pk_i_id');
  //$search->dao->orderBy('i.dt_pub_date DESC');
  
  
  // CHECK FOR LOCATION AND IF EXISTS, TRY TO SHOW CLOSEST LISTINGS ONLY (BY ORDERING)
  $lat = $lon = '';
  if(Params::getParam('sCity') != '' && (int)Params::getParam('sCity') > 0) {
    $city = osc_get_city_row((int)Params::getParam('sCity'));
    
    if(isset($city['pk_i_id'])) {
      $lat = $city['d_coord_lat'];
      $lon = $city['d_coord_long'];
    }
  }
  
  if($lat == '' && $lon == '') {
    $location_cookie = zet_location_from_cookies();

    if($location_cookie['success'] == true) { 
      $lat = (@$location_cookie['d_device_coord_lat'] <> '' ? $location_cookie['d_device_coord_lat'] : @$location_cookie['d_coord_lat']);
      $lon = (@$location_cookie['d_device_coord_long'] <> '' ? $location_cookie['d_device_coord_long'] : @$location_cookie['d_coord_long']);
    }
  }
  
  if($lat <> '' && $lon <> '') {
    $measurement = 6371;  // 3959 for miles 
    
    $search->dao->select(sprintf('(%d * acos(cos(radians(%f)) * cos(radians(l.d_coord_lat)) * cos(radians(l.d_coord_long) - radians(%f)) + sin(radians(%f)) * sin(radians(l.d_coord_lat)))) as d_distance', (int)$measurement, (float)$lat, (float)$lon, (float)$lat));
    $search->dao->from(DB_TABLE_PREFIX.'t_item_location as l');
    $search->dao->where('i.pk_i_id = l.fk_i_item_id');
    $search->dao->orderby('d_distance', 'ASC');
  }

  $search->dao->limit($limit);

  $rs = $search->dao->get();

  if($rs === false){
    return array();
  }
  
  if($rs->numRows() == 0) {
    return array();
  }

  $items = $rs->result();
  $items = Item::newInstance()->extendData($items);
  
  View::newInstance()->_exportVariableToView('zet_home_premium_items', $items);

  return $items;
}


// ITEM LOOP FORMAT LOCATION
function zet_item_location($premium = false) {
  if(!$premium) {
    $loc = array_filter(array(osc_item_city(), (osc_item_city() != '' ? '' : osc_item_region()), (zet_count_countries() > 1 ? osc_item_country() : '')));
  } else {
    $loc = array_filter(array(osc_premium_city(), (osc_premium_city() != '' ? '' : osc_premium_region()), (zet_count_countries() > 1 ? osc_premium_country() : '')));
  }

  return implode(', ', $loc);
}


// USER ITEMS LOOP FORMAT LOCATION
function zet_user_item_location() {
  $loc = array_filter(array(osc_item_city(), osc_item_region(), (zet_count_countries() > 1 ? osc_item_country() : ''), osc_item_city_area(), osc_item_address(), osc_item_zip()));
  return implode(', ', $loc);
}


function zet_item_location_url($premium = false) {
  $array = array('page' => 'search');
  
  if(!$premium) {
    if(osc_item_city_id() > 0) {
      $array['sCity'] = osc_item_city_id();
    } else {
      $array['sCity'] = osc_item_city();
      
      if(osc_item_region_id() > 0) {
        $array['sRegion'] = osc_item_region_id();
      } else {
        $array['sRegion'] = osc_item_region();
        
        if(osc_item_country_code() > 0) {
          $array['sCountry'] = osc_item_country_code();
        } else {
          $array['sCountry'] = osc_item_country();
        }
      }
    }
  } else {
    if(osc_premium_city_id() > 0) {
      $array['sCity'] = osc_premium_city_id();
    } else {
      $array['sCity'] = osc_premium_city();
      
      if(osc_premium_region_id() > 0) {
        $array['sRegion'] = osc_premium_region_id();
      } else {
        $array['sRegion'] = osc_premium_region();
        
        if(osc_premium_country_code() > 0) {
          $array['sCountry'] = osc_premium_country_code();
        } else {
          $array['sCountry'] = osc_premium_country();
        }
      }
    }
  }

  return osc_search_url($array);
}


// CHECK IF DEVICE IS MOBILE
function zet_is_mobile() {
  $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

  if($useragent != '' && preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
    return true;
  }

  return false;
}



// LOCATION FORMATER - USED ON SEARCH LIST
function zet_location_format($country = null, $region = null, $city = null) { 
  if($country <> '') {
    if(strlen($country) == 2) {
      $country_full = osc_get_country_row($country);
    } else {
      $country_full = Country::newInstance()->findByName($country);
    }

    if($region <> '') {
      if($city <> '') {
        return $city . ' ' . __('in', 'zeta') . ' ' . $region . (osc_location_native_name_selector($country_full, 's_name') <> '' ? ' (' . osc_location_native_name_selector($country_full, 's_name') . ')' : '');
      } else {
        return $region . ' (' . osc_location_native_name_selector($country_full, 's_name') . ')';
      }
    } else { 
      if($city <> '') {
        return $city . ' ' . __('in', 'zeta') . ' ' . osc_location_native_name_selector($country_full, 's_name');
      } else {
        return osc_location_native_name_selector($country_full, 's_name');
      }
    }
  } else {
    if($region <> '') {
      if($city <> '') {
        return $city . ' ' . __('in', 'zeta') . ' ' . $region;
      } else {
        return $region;
      }
    } else { 
      if($city <> '') {
        return $city;
      } else {
        return __('Location not entered', 'zeta');
      }
    }
  }
}


// SEARCH CONDITIONS EXTEND SEARCH
function zet_filter_extend() {
  // SEARCH - ALL - INDIVIDUAL - COMPANY TYPE
  Search::newInstance()->addJoinTable(DB_TABLE_PREFIX.'t_item_zeta.fk_i_item_id', DB_TABLE_PREFIX.'t_item_zeta', DB_TABLE_PREFIX.'t_item.pk_i_id = '.DB_TABLE_PREFIX.'t_item_zeta.fk_i_item_id', 'LEFT OUTER'); // Mod


  // SEARCH - TRANSACTION
  if(Params::getParam('sTransaction') > 0) {
    Search::newInstance()->addConditions(sprintf("%st_item_zeta.i_transaction = %d", DB_TABLE_PREFIX, Params::getParam('sTransaction')));
  }


  // SEARCH - CONDITION
  if(Params::getParam('sCondition') > 0) {
    Search::newInstance()->addConditions(sprintf("%st_item_zeta.i_condition = %d", DB_TABLE_PREFIX, Params::getParam('sCondition')));
  }

  // SEARCH - PRICE FREE
  if(Params::getParam('bPriceFree') > 0) {
    Search::newInstance()->addConditions(sprintf("%st_item.i_price = 0", DB_TABLE_PREFIX));
  }
  
  // SEARCH - PRICE CHECK WITH SELLER
  if(Params::getParam('bPriceCheckWithSeller') > 0) {
    Search::newInstance()->addConditions(sprintf("%st_item.i_price is NULL", DB_TABLE_PREFIX));
  }

  // SEARCH - PERIOD
  if(Params::getParam('sPeriod') > 0) {
    $date_from = date('Y-m-d', strtotime(' -' . Params::getParam('sPeriod') . ' day', time()));
    Search::newInstance()->addConditions(sprintf('cast(%st_item.dt_pub_date as date) > "%s"', DB_TABLE_PREFIX, $date_from));
  }

  // SEARCH - USER ID
  if(Params::getParam('userId') > 0) {
    Search::newInstance()->addConditions(sprintf("%st_item.fk_i_user_id = %d", DB_TABLE_PREFIX, Params::getParam('userId')));
  }

  // ONLY WITH PHONE
  if(Params::getParam('bPhone') == 1) {
    if(function_exists('osc_item_show_phone') && method_exists('ItemForm', 'contact_phone_text')) {
      Search::newInstance()->addConditions(sprintf('(%st_item.b_show_phone = 1 AND (length(coalesce(%st_item_zeta.s_phone, "")) > 4 OR length(coalesce(%st_item.s_contact_phone, "")) > 4))', DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX));
    } else {
      Search::newInstance()->addConditions(sprintf('length(coalesce(%st_item_zeta.s_phone, "")) > 4', DB_TABLE_PREFIX));
    }
  }
  
  if(Params::getParam('notFromUserId') > 0) {
    //Search::newInstance()->addJoinTable(DB_TABLE_PREFIX.'t_user.pk_i_id', DB_TABLE_PREFIX.'t_user', DB_TABLE_PREFIX.'t_item.fk_i_user_id = '.DB_TABLE_PREFIX.'t_user.pk_i_id', 'LEFT OUTER'); // Mod
    //Search::newInstance()->addConditions(sprintf('((%st_user.pk_i_id = %st_item.fk_i_user_id AND %st_item.fk_i_user_id != %d) || %st_item.fk_i_user_id IS NULL) ', DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, Params::getParam('notFromUserId'), DB_TABLE_PREFIX));
    Search::newInstance()->addConditions(sprintf('(%st_item.fk_i_user_id != %d OR %st_item.fk_i_user_id IS NULL)', DB_TABLE_PREFIX, Params::getParam('notFromUserId'), DB_TABLE_PREFIX));
  }

  // SEARCH - COMPANY
  if(Params::getParam('sCompany') <> '' and Params::getParam('sCompany') <> null) {
    if(Params::getParam('notFromUserId') <= 0) {
      Search::newInstance()->addJoinTable(DB_TABLE_PREFIX.'t_user.pk_i_id', DB_TABLE_PREFIX.'t_user', DB_TABLE_PREFIX.'t_item.fk_i_user_id = '.DB_TABLE_PREFIX.'t_user.pk_i_id', 'LEFT OUTER'); // Mod
    }
    
    if(Params::getParam('sCompany') == 1) {
      Search::newInstance()->addConditions(sprintf("%st_user.b_company = 1", DB_TABLE_PREFIX));
    } else {
      Search::newInstance()->addConditions(sprintf("coalesce(%st_user.b_company, 0) <> 1", DB_TABLE_PREFIX));
    }
  }
  
  
  // RADIUS SEARCH
  if((float)Params::getParam('iRadius') > 0) {
    $radius = (float)Params::getParam('iRadius');
    $lat = 0;
    $lng = 0;
    
    $city = Params::getParam('sCity');
    $region = Params::getParam('sRegion');
    $source = '';

    // Get coordinates from search city
    if((int)$city > 0) {
      $city_row = osc_get_city_row($city);
      
      if($city_row !== false && isset($city_row['d_coord_lat'])) {
        $lat = (float)$city_row['d_coord_lat'];
        $lng = (float)$city_row['d_coord_long'];
        $source = 'city';
      }
    }

    // Get coordinates from search region
    if((int)$region > 0 && ($lat == 0 || $lng == 0)) {
      $region_row = osc_get_region_row($region);
      
      if($region_row !== false && isset($region_row['d_coord_lat'])) {
        $lat = (float)$region_row['d_coord_lat'];
        $lng = (float)$region_row['d_coord_long'];
        $source = 'region';
      }
    }
    
    // Coordinates found
    if($lat <> 0 && $lng <> 0 && $source != '') {
      $list = ModelZet::newInstance()->getLocationsInRadius($lat, $lng, $radius, $source);

      if(is_array($list) && count($list) > 0) {
        foreach($list as $loc) {
          if($source == 'city') {
            if(isset($loc['fk_i_city_id']) && (int)$loc['fk_i_city_id'] > 0 && $loc['fk_i_city_id'] != $city) {
              Search::newInstance()->addCity($loc['fk_i_city_id']);
            }
          
          } else if($source == 'region') {
            if(isset($loc['fk_i_region_id']) && (int)$loc['fk_i_region_id'] > 0 && $loc['fk_i_region_id'] != $region) {
              Search::newInstance()->addRegion($loc['fk_i_region_id']);
            }
          }
        }
      }
    }
  }

  
  // SUPPORT ALERTS URLS
  $extra_tables = Params::getParam('extraTables');
  if($extra_tables != '') {
    $tables = @json_decode(zet_base64url_decode($extra_tables), true);
    
    if(is_array($tables) && count($tables) > 0) {
      Search::newInstance()->addTable($tables);
    }
  }

  $extra_conditions = Params::getParam('extraConditions');
  if($extra_conditions != '') {
    $conditions = @json_decode(zet_base64url_decode($extra_conditions), true);
    
    if(is_array($conditions) && count($conditions) > 0) {
      Search::newInstance()->addConditions($conditions);
    }
  }
}

osc_add_hook('search_conditions', 'zet_filter_extend');



// GET SELECTED SEARCH PARAMETERS
function zet_search_params() {
 return array(
   'sCategory' => Params::getParam('sCategory'),
   'sCountry' => Params::getParam('sCountry'),
   'sRegion' => Params::getParam('sRegion'),
   'sCity' => Params::getParam('sCity'),
   //'sPriceMin' => Params::getParam('sPriceMin'),
   //'sPriceMin' => Params::getParam('sPriceMax'),
   'sCompany' => Params::getParam('sCompany'),
   'sShowAs' => Params::getParam('sShowAs'),
   'sOrder' => Params::getParam('sOrder'),
   'iOrderType' => Params::getParam('iOrderType')
 );
}


// GET ALL PARAMS
function zet_search_params_all() {
  $params = Params::getParamsAsArray();
  unset($params['iPage']);
  return $params;
}


// FIND MAXIMUM PRICE
function zet_max_price($cat_id = null, $country_code = null, $region_id = null, $city_id = null) {
  // Search by all parameters
  $allSearch = new Search();
  $allSearch->addCategory($cat_id);
  $allSearch->addCountry($country_code);
  $allSearch->addRegion($region_id);
  $allSearch->addCity($city_id);
  $allSearch->order('i_price', 'DESC');
  $allSearch->limit(0, 1);

  $result = $allSearch->doSearch();
  $result = $result[0];

  $max_price = isset($result['i_price']) ? $result['i_price'] : 0;


  // FOLLOWING BLOCK LOOKS FOR MAX-PRICE IF IT IS 0
  // City is set, find max price by Region
  if($max_price <= 0 && isset($city_id) && $city_id <> '') {
    $regSearch = new Search();
    $regSearch->addCategory($cat_id);
    $regSearch->addCountry($country_code);
    $regSearch->addRegion($region_id);
    $regSearch->order('i_price', 'DESC');
    $regSearch->limit(0, 1);

    $result = $regSearch->doSearch();
    $result = $result[0];

    $max_price = isset($result['i_price']) ? $result['i_price'] : 0;
  }


  // Region is set, find max price by Country
  if($max_price <= 0 && isset($region_id) && $region_id <> '') {
    $regSearch = new Search();
    $regSearch->addCategory($cat_id);
    $regSearch->addCountry($country_code);
    $regSearch->order('i_price', 'DESC');
    $regSearch->limit(0, 1);

    $result = $regSearch->doSearch();
    $result = $result[0];

    $max_price = isset($result['i_price']) ? $result['i_price'] : 0;
  }


  // Country is set, find max price WorldWide
  if($max_price <= 0 && isset($country_code) && $country_code <> '') {
    $regSearch = new Search();
    $regSearch->addCategory($cat_id);
    $regSearch->order('i_price', 'DESC');
    $regSearch->limit(0, 1);

    $result = $regSearch->doSearch();
    $result = $result[0];

    $max_price = isset($result['i_price']) ? $result['i_price'] : 0;
  }


  // Category is set, find max price in all Categories
  if($max_price <= 0 && isset($region_id) && $region_id <> '') {
    $regSearch = new Search();
    $regSearch->addCategory($cat_id);
    $regSearch->order('i_price', 'DESC');
    $regSearch->limit(0, 1);

    $result = $regSearch->doSearch();
    $result = $result[0];

    $max_price = isset($result['i_price']) ? $result['i_price'] : 0;
  }


  // If max_price is still 0, set it to 1 to avoid slider defect
  if($max_price <= 0) {
    $max_price = 1000000;
  }


  return array(
    'max_price' => $max_price/1000000,
    'max_currency' => zet_param('def_cur')
 );
}


// CHECK IF AJAX IMAGE UPLOAD ON PUBLISH-EDIT PAGE CAN BE USED (from osclass 3.3)
function zet_ajax_image_upload() {
  if(class_exists('Scripts')) {
    return Scripts::newInstance()->registered['jquery-fineuploader'] && method_exists('ItemForm', 'ajax_photos');
  }
}


// CLOSE BUTTON RETRO-COMPATIBILITY
if(!OC_ADMIN) {
  if(!function_exists('add_close_button_action')) {
    function add_close_button_action(){
      echo '<script type="text/javascript">';
      echo '$(".flashmessage .ico-close").click(function(){';
      echo '$(this).parent().hide();';
      echo '});';
      echo '</script>';
    }
    osc_add_hook('footer', 'add_close_button_action');
  }
}


if(!function_exists('message_ok')) {
  function message_ok($text) {
    $final = '<div style="padding: 1%;width: 98%;margin-bottom: 15px;" class="flashmessage flashmessage-ok flashmessage-inline">';
    $final .= $text;
    $final .= '</div>';
    echo $final;
  }
}


if(!function_exists('message_error')) {
  function message_error($text) {
    $final = '<div style="padding: 1%;width: 98%;margin-bottom: 15px;" class="flashmessage flashmessage-error flashmessage-inline">';
    $final .= $text;
    $final .= '</div>';
    echo $final;
  }
}





// FIX PRICE FORMAT OF PREMIUM ITEMS
function zet_premium_formated_price($price = null) {
  if($price == '') {
    $price = osc_premium_price();
  }

  return (string) zet_premium_format_price($price);
}


function zet_premium_format_price($price, $symbol = null) {
  if($price === null) return osc_apply_filter ('item_price_null', __('Check with seller', 'zeta'));
  if($price == 0) return osc_apply_filter ('item_price_zero', __('Free', 'zeta'));

  if($symbol==null) { $symbol = osc_premium_currency_symbol(); }

  $price = $price/1000000;

  $currencyFormat = osc_locale_currency_format();
  $currencyFormat = str_replace('{NUMBER}', number_format($price, osc_locale_num_dec(), osc_locale_dec_point(), osc_locale_thousands_sep()), $currencyFormat);
  $currencyFormat = str_replace('{CURRENCY}', $symbol, $currencyFormat);
  return osc_apply_filter('premium_price', $currencyFormat);
}


function zet_ajax_item_format_price($price, $symbol_code) {
  if($price === null) return __('Check with seller', 'zeta');
  if($price == 0) return __('Free', 'zeta');
  return round($price/1000000, 2) . ' ' . $symbol_code;
}


AdminMenu::newInstance()->add_menu(__('Theme Setting', 'zeta'), osc_admin_render_theme_url(zet_oc_content_folder() . '/themes/zeta/admin/configure.php'), 'zeta_menu');
AdminMenu::newInstance()->add_submenu('zeta_menu', __('Configure', 'zeta'), osc_admin_render_theme_url(zet_oc_content_folder() . '/themes/zeta/admin/configure.php'), 'settings_zeta1');
AdminMenu::newInstance()->add_submenu('zeta_menu', __('Advertisement', 'zeta'), osc_admin_render_theme_url(zet_oc_content_folder() . '/themes/zeta/admin/banner.php'), 'settings_zeta2');
AdminMenu::newInstance()->add_submenu('zeta_menu', __('Category Icons', 'zeta'), osc_admin_render_theme_url(zet_oc_content_folder() . '/themes/zeta/admin/category.php'), 'settings_zeta3');
AdminMenu::newInstance()->add_submenu('zeta_menu', __('Logo', 'zeta'), osc_admin_render_theme_url(zet_oc_content_folder() . '/themes/zeta/admin/logo.php'), 'settings_zeta4');
AdminMenu::newInstance()->add_submenu('zeta_menu', __('Plugins', 'zeta'), osc_admin_render_theme_url(zet_oc_content_folder() . '/themes/zeta/admin/plugins.php'), 'settings_zeta5');


function zet_admin_toolbar() {
  AdminMenu::newInstance()->add_submenu_divider('appearance', __('Zeta Theme Settings', 'zeta'), 'zeta_submenu');

  AdminMenu::newInstance()->add_submenu('appearance', __('Configure', 'zeta'), osc_admin_render_theme_url(zet_oc_content_folder() . '/themes/zeta/admin/configure.php'), 'settings_zeta1');
  AdminMenu::newInstance()->add_submenu('appearance', __('Advertisement', 'zeta'), osc_admin_render_theme_url(zet_oc_content_folder() . '/themes/zeta/admin/banner.php'), 'settings_zeta2');
  AdminMenu::newInstance()->add_submenu('appearance', __('Category Icons', 'zeta'), osc_admin_render_theme_url(zet_oc_content_folder() . '/themes/zeta/admin/category.php'), 'settings_zeta3');
  AdminMenu::newInstance()->add_submenu('appearance', __('Logo', 'zeta'), osc_admin_render_theme_url(zet_oc_content_folder() . '/themes/zeta/admin/logo.php'), 'settings_zeta4');
  AdminMenu::newInstance()->add_submenu('appearance', __('Plugins', 'zeta'), osc_admin_render_theme_url(zet_oc_content_folder() . '/themes/zeta/admin/plugins.php'), 'settings_zeta5');
}

osc_add_hook('add_admin_toolbar_menus', 'zet_admin_toolbar');


// GET SITE LOGO
function zet_logo($image_only = false, $square = false, $dark = false) {
  $src = '';
  $name = 'logo';
  $class = 'img-logo';
  
  if($square === true) {
    $name .= '-square';
    $class .= '-square';
  }

  if($dark === true) {
    $name .= '-dark';
    $class .= ' dark';
  }
  
  if(zet_param('default_logo') == 1 && file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/' . $name . '-default.png')) {
    $src = osc_current_web_theme_url('images/' . $name . '-default.png');
  } else if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/' . $name . '.jpg')) {
    $src = osc_current_web_theme_url('images/' . $name . '.jpg');
  } else if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/' . $name . '.png')) {
    $src = osc_current_web_theme_url('images/' . $name . '.png');
  } else if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/' . $name . '.webp')) {
    $src = osc_current_web_theme_url('images/' . $name . '.webp');
  } else if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/' . $name . '.jpeg')) {
    $src = osc_current_web_theme_url('images/' . $name . '.jpeg');
  } else if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/' . $name . '.gif')) {
    $src = osc_current_web_theme_url('images/' . $name . '.gif');
  }
  
  
  // No logo found, use default anyway
  if($src == '') {
    $src = osc_current_web_theme_url('images/' . $name . '-default.png');
  }
  
  
  // Only image URL, no html tag
  if($image_only === true) {
    return $src;
  }

  //return '<img src="' . (zet_is_lazy() ? zet_get_load_image('transparent') : $src) . '" data-src="' . $src . '" alt="' . osc_esc_html(osc_page_title()) . '" class="' . (zet_is_lazy() ? 'lazy' : '') . '"/>';
  return '<img class="' . $class . '" src="' . $src . '" alt="' . osc_esc_html(osc_page_title()) . '"/>';
}


// CHECK IF SITE LOGO IS UPLOADED
function zet_logo_is_uploaded($default = true, $name = 'logo') {
  if(zet_param('default_logo') == 1 && file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/' . $name . '-default.png')) {
    if($default) { 
      return false;
    } else {
      return '' . $name . '-default.png'; 
    }
  } else if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/' . $name . '.jpg')) {
    return '' . $name . '.jpg';
  } else if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/' . $name . '.jpeg')) {
    return '' . $name . '.jpeg';
  } else if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/' . $name . '.png')) {
    return '' . $name . '.png';
  } else if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/' . $name . '.webp')) {
    return '' . $name . '.webp';
  } else if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/' . $name . '.gif')) {
    return '' . $name . '.gif';
  }
  
  return false;
}



// INSTALL & UPDATE OPTIONS
function zet_theme_install() {
  osc_set_preference('home_cat_rows', 2, 'theme-zeta');
  osc_set_preference('sample_images', 1, 'theme-zeta');
  osc_set_preference('default_location', 1, 'theme-zeta');
  osc_set_preference('profile_location', 0, 'theme-zeta');
  osc_set_preference('publish_location', 0, 'theme-zeta');
  osc_set_preference('messenger_replace_button', 1, 'theme-zeta');
  osc_set_preference('enable_custom_font', 0, 'theme-zeta');
  osc_set_preference('font_name', '', 'theme-zeta');
  osc_set_preference('font_url', '', 'theme-zeta');
  osc_set_preference('version', ZETA_THEME_VERSION, 'theme-zeta');
  osc_set_preference('enable_custom_color', 0, 'theme-zeta');
  osc_set_preference('enable_dark_mode', 1, 'theme-zeta');
  osc_set_preference('default_mode', 'LIGHT', 'theme-zeta');
  osc_set_preference('color_primary', '#ff2636', 'theme-zeta');
  osc_set_preference('color_primary_hover', '#cc1e2b', 'theme-zeta');
  osc_set_preference('color_secondary', '#008f79', 'theme-zeta');
  osc_set_preference('color_secondary_hover', '#026958', 'theme-zeta');
  osc_set_preference('footer_link', '1', 'theme-zeta');
  osc_set_preference('default_logo', '1', 'theme-zeta');
  osc_set_preference('def_cur', '$', 'theme-zeta');
  osc_set_preference('def_view', '1', 'theme-zeta');
  osc_set_preference('latest_picture', '0', 'theme-zeta');
  osc_set_preference('latest_random', '1', 'theme-zeta');
  osc_set_preference('latest_premium', '0', 'theme-zeta');
  osc_set_preference('latest_category', '', 'theme-zeta');
  osc_set_preference('latest_design', '', 'theme-zeta');
  osc_set_preference('publish_category', '2', 'theme-zeta');
  osc_set_preference('premium_home', '1', 'theme-zeta');
  osc_set_preference('location_home', '1', 'theme-zeta');
  osc_set_preference('premium_search', '1', 'theme-zeta');
  osc_set_preference('premium_home_count', '4', 'theme-zeta');
  osc_set_preference('premium_search_count', '24', 'theme-zeta');
  osc_set_preference('premium_home_design', '', 'theme-zeta');
  osc_set_preference('premium_search_design', 'normal', 'theme-zeta');
  osc_set_preference('search_ajax', '1', 'theme-zeta');
  osc_set_preference('post_required', '', 'theme-zeta');
  osc_set_preference('post_extra_exclude', '', 'theme-zeta');
  osc_set_preference('favorite_home', '1', 'theme-zeta');
  osc_set_preference('favorite_count', '20', 'theme-zeta');
  osc_set_preference('favorite_design', 'wide', 'theme-zeta');
  osc_set_preference('blog_home', '1', 'theme-zeta');
  osc_set_preference('blog_home_count', '6', 'theme-zeta');
  osc_set_preference('company_home', '1', 'theme-zeta');
  osc_set_preference('company_home_count', '5', 'theme-zeta');
  osc_set_preference('banners', '0', 'theme-zeta');
  osc_set_preference('banner_optimize_adsense', '0', 'theme-zeta');
  osc_set_preference('lazy_load', '1', 'theme-zeta');
  osc_set_preference('public_items', '12', 'theme-zeta');
  osc_set_preference('alert_items', '12', 'theme-zeta');
  osc_set_preference('related', '1', 'theme-zeta');
  osc_set_preference('related_count', '24', 'theme-zeta');
  osc_set_preference('related_design', 'normal', 'theme-zeta');
  osc_set_preference('recent_home', '1', 'theme-zeta');
  osc_set_preference('recent_item', '1', 'theme-zeta');
  osc_set_preference('recent_search', '1', 'theme-zeta');
  osc_set_preference('recent_count', '24', 'theme-zeta');
  osc_set_preference('recent_design', 'tall', 'theme-zeta');
  osc_set_preference('generate_favicons', '1', 'theme-zeta');
  osc_set_preference('sample_favicons', '1', 'theme-zeta');
  osc_set_preference('gallery_ratio', '', 'theme-zeta');
  osc_set_preference('home_search_cat', '', 'theme-zeta');


  // BANNERS
  if(function_exists('zeta_banner_list')) {
    foreach(zet_banner_list() as $b) {
      osc_set_preference($b['id'], '', 'theme-zeta');
    }
  }

  osc_reset_preferences();

  // Add extra item fields into database
  ModelZET::newInstance()->install();
}

// THEME UPDATE - version with dots, ie: 1.9.2
function zet_theme_update($version) {
  if(version_compare2('1.1.0', $version) == 1) {  // A > B
    // Do something if current version was undre 1.1.0  
  }
  
  osc_reset_preferences();
}


function zet_install_update_theme() {
  $installed_version = zet_param('version');
  $current_version = ZETA_THEME_VERSION;
  
  $compare = version_compare2($current_version, $installed_version);

  if(!$installed_version || $installed_version == '') {
    zet_theme_install();
    osc_set_preference('version', ZETA_THEME_VERSION, 'theme-zeta');
  } else if($compare == 1) {    // A > B
    zet_theme_update($current_version);
    osc_set_preference('version', ZETA_THEME_VERSION, 'theme-zeta');
  }
}

osc_add_hook('init', 'zet_install_update_theme');


// WHEN NEW LISTING IS CREATED, ADD IT TO ZETA EXTRA TABLE
function zet_new_item_extra($item) {
  ModelZET::newInstance()->insertItemExtra(array('fk_i_item_id' => $item['pk_i_id']));
}

osc_add_hook('posted_item', 'zet_new_item_extra', 1);



// USER MENU FIX
function zet_export_variables() {
  $user = osc_logged_user();
  View::newInstance()->_exportVariableToView('user', $user);
}

osc_add_hook('init', 'zet_export_variables', 1);


// UPDATE THEME COLS ON ITEM POST-EDIT
function zet_update_fields($item) {
  if(!isset($item['pk_i_id']) || $item['pk_i_id'] <= 0) {
    return false;
  }
  
  $fields = array(
    's_phone' => (Params::getParam('contactPhone') <> '' ? Params::getParam('contactPhone') : Params::getParam('sPhone')),
    'i_condition' => Params::getParam('sCondition'),
    'i_transaction' => Params::getParam('sTransaction'),
    'i_sold' => Params::getParam('sSold')
  );

  Item::newInstance()->dao->update(DB_TABLE_PREFIX.'t_item_zeta', $fields, array('fk_i_item_id' => $item['pk_i_id']));
}

osc_add_hook('posted_item', 'zet_update_fields', 1);
osc_add_hook('edited_item', 'zet_update_fields', 1);


// GET ZETA ITEM EXTRA VALUES
function zet_item_extra($item_id, $item = array()) {
  if(empty($item)) {
    $item = osc_item();
  }
  
  // Theme item tables support in osclass from 8.0.2
  if(isset($item['theme_item_table_loaded']) && $item['theme_item_table_loaded'] == 1) {
    return array(
      'fk_i_item_id' => $item_id,
      's_phone' => $item['s_phone'],
      'i_condition' => $item['i_condition'],
      'i_transaction' => $item['i_transaction'],
      'i_sold' => $item['i_sold']
    );
    
  } else {
    $data = ModelZET::newInstance()->getItemExtra($item_id);
    
    if(isset($data['fk_i_item_id']) && $data['fk_i_item_id'] > 0) {
      return $data;
    }
    
    return array(
      'fk_i_item_id' => $item_id,
      's_phone' => '',
      'i_condition' => null,
      'i_transaction' => null,
      'i_sold' => null,
      'found' => 'NOTFOUND'
    );
  }
}


// COUNT INSTANT MESSENGER MESSAGES
function zet_count_messages($user_id) {
  if($user_id > 0 && class_exists('ModelIM')) {
    $mes_counter = ModelIM::newInstance()->countMessagesByUserId($user_id); 
    $mes_counter = (isset($mes_counter['i_count']) ? $mes_counter['i_count'] : 0);
    return $mes_counter;
  } else {
    return 0;   
  }
}


// COUNT LOGGED USER ALERTS
function zet_count_alerts($user_id = NULL) {
  if($user_id === NULL) {
    $user_id = osc_logged_user_id();
  }
  
  if($user_id > 0) {
    $alerts = Alerts::newInstance()->findByUser($user_id);
    
    if(is_array($alerts)) {
      return count($alerts);
    }
  }
  
  return 0;  
}
  
  
// COUNT FAVORITE ITEMS
function zet_count_favorite($user_id = '') {
  if($user_id > 0) { 
    // nothing
  } else if(osc_is_web_user_logged_in()) {
    $user_id = osc_logged_user_id();
  } else {
    $user_id = mb_get_cookie('fi_user_id');
  }


  if($user_id > 0 && class_exists('ModelFI')) {
    $db_prefix = DB_TABLE_PREFIX;

    $query = "SELECT count(*) as count FROM {$db_prefix}t_favorite_items i, {$db_prefix}t_favorite_list l WHERE l.list_id = i.list_id AND l.user_id = " . $user_id . ";";
    $result = Item::newInstance()->dao->query($query);
    if(!$result) { 
      $prepare = array();
      return 0;
    } else {
      $prepare = @$result->row()['count'];
      return $prepare;
    }
  }

  return 0;
}


// GET ZETA CATEGORY EXTRA VALUES
function zet_category_extra($id, $category = array()) {
  if(isset($category['theme_category_table_loaded']) && $category['theme_category_table_loaded'] == 1) {
    return array(
      'fk_i_category_id' => $id,
      's_color' => $category['s_color'],
      's_icon' => $category['s_icon']
    );
    
  } else {
    $data = ModelZET::newInstance()->getCategoryExtra($id);
    
    if(isset($data['fk_i_category_id']) && $data['fk_i_category_id'] > 0) {
      return $data;
    }

    return array(
      'fk_i_category_id' => $id,
      's_color' => '',
      's_icon' => '',
      'found' => false
    );
  }
}


// KEEP VALUES OF INPUTS ON RELOAD
function zet_post_preserve() {
  Session::newInstance()->_setForm('sPhone', Params::getParam('sPhone'));
  Session::newInstance()->_setForm('contactPhone', Params::getParam('contactPhone'));
  Session::newInstance()->_setForm('term', Params::getParam('term'));
  Session::newInstance()->_setForm('zip', Params::getParam('zip'));
  Session::newInstance()->_setForm('sCondition', Params::getParam('sCondition'));
  Session::newInstance()->_setForm('sTransaction', Params::getParam('sTransaction'));

  Session::newInstance()->_keepForm('sPhone');
  Session::newInstance()->_keepForm('contactPhone');
  Session::newInstance()->_keepForm('term');
  Session::newInstance()->_keepForm('zip');
  Session::newInstance()->_keepForm('sCondition');
  Session::newInstance()->_keepForm('sTransaction');
}

osc_add_hook('pre_item_post', 'zet_post_preserve');


// DROP VALUES OF INPUTS ON SUCCESSFUL PUBLISH
function zet_post_drop() {
  Session::newInstance()->_dropKeepForm('sPhone');
  Session::newInstance()->_dropKeepForm('contactPhone');
  Session::newInstance()->_dropKeepForm('term');
  Session::newInstance()->_dropKeepForm('zip');
  Session::newInstance()->_dropKeepForm('sCondition');
  Session::newInstance()->_dropKeepForm('sTransaction');

  Session::newInstance()->_clearVariables();
}

osc_add_hook('posted_item', 'zet_post_drop');



// GET VALUES FROM SESSION ON PUBLISH PAGE
function zet_get_session($param) {
  return Session::newInstance()->_getForm($param);
}


// DEFAULT ICONS ARRAY
function zet_default_icons() {
  $icons = array(
    1 => 'fa-newspaper', 2 => 'fa-motorcycle', 3 => 'fa-graduation-cap', 4 => 'fa-home', 5 => 'fa-wrench', 6 => 'fa-users', 7 => 'fa-venus-mars', 8 => 'fa-briefcase', 9 => 'fa-paw', 
    10 => 'fa-paint-brush', 11 => 'fa-exchange', 12 => 'fa-newspaper', 13 => 'fa-camera', 14 => 'fa-tablet', 15 => 'fa-mobile', 16 => 'fa-shopping-bag', 
    17 => 'fa-laptop', 18 => 'fa-mobile', 19 => 'fa-lightbulb-o', 20 => 'fa-soccer-ball-o', 21 => 'fa-s15', 22 => 'fa-medkit', 23 => 'fa-home', 24 => 'fa-clock-o', 
    25 => 'fa-microphone', 26 => 'fa-bicycle', 27 => 'fa-ticket', 28 => 'fa-plane', 29 => 'fa-television', 30 => 'fa-ellipsis-h', 31 => 'fa-car', 32 => 'fa-gears', 
    33 => 'fa-motorcycle', 34 => 'fa-ship', 35 => 'fa-bus', 36 => 'fa-truck', 37 => 'fa-ellipsis-h', 38 => 'fa-laptop', 39 => 'fa-language', 40 => 'fa-microphone', 
    41 => 'fa-graduation-cap', 42 => 'fa-ellipsis-h', 43 => 'fa-building-o', 44 => 'fa-building', 45 => 'fa-refresh', 46 => 'fa-exchange', 47 => 'fa-plane', 48 => 'fa-car', 
    49 => 'fa-window-minimize', 50 => 'fa-suitcase', 51 => 'fa-shopping-basket', 52 => 'fa-child', 53 => 'fa-microphone', 54 => 'fa-laptop', 55 => 'fa-music', 
    56 => 'fa-stethoscope', 57 => 'fa-star', 58 => 'fa-home', 59 => 'fa-truck', 60 => 'fa-wrench', 61 => 'fa-pencil', 62 => 'fa-ellipsis-h', 63 => 'fa-refresh', 
    64 => 'fa-sun-o', 65 => 'fa-star', 66 => 'fa-music', 67 => 'fa-wheelchair', 68 => 'fa-key', 69 => 'fa-venus', 70 => 'fa-mars', 71 => 'fa-mars-double', 
    72 => 'fa-venus-double', 73 => 'fa-genderless', 74 => 'fa-phone', 75 => 'fa-money', 76 => 'fa-television', 77 => 'fa-paint-brush', 78 => 'fa-book', 79 => 'fa-headphones', 
    80 => 'fa-graduation-cap', 81 => 'fa-paper-plane-o', 82 => 'fa-medkit', 83 => 'fa-users', 84 => 'fa-internet-explorer', 85 => 'fa-gavel', 86 => 'fa-wrench', 
    87 => 'fa-industry', 88 => 'fa-newspaper', 89 => 'fa-wheelchair', 90 => 'fa-home', 91 => 'fa-spoon', 92 => 'fa-exchange', 93 => 'fa-gavel', 94 => 'fa-microchip', 
    95 => 'fa-ellipsis-h', 999 => 'fa-newspaper', 0 => 'fa-search-dollar'
 );

  return $icons;
}


function zet_default_colors() {
  $colors = array(1 => '#F44336', 2 => '#00BCD4', 3 => '#009688', 4 => '#FDE74C', 5 => '#8BC34A', 6 => '#D32F2F', 7 => '#2196F3', 8 => '#777', 999 => '#F44336');
  return $colors;
}


// GET CATEGORY ICON OR IMAGE
function zet_get_cat_icon($id, $category = array(), $string = false, $force_icon = false) {
  $category_extra = zet_category_extra($id, $category);
  $default_icons = zet_default_icons();

  if(zet_param('cat_icons') == 1 || $force_icon == true) { 
    if($category_extra !== false && $category_extra['s_icon'] <> '') {
      $icon_code = $category_extra['s_icon'];
      
    } else {
      if(isset($default_icons[$id]) && $default_icons[$id] <> '') {
        $icon_code = $default_icons[$id];
      } else {
        $icon_code = $default_icons[999];
      }
    }

    if($string) {
      return $icon_code;
    } else {
      return '<i class="fa ' . $icon_code . '"></i>';
    }
    
  } else {
    if($string) {
      if(file_exists(osc_base_path() . zet_oc_content_folder() . '/themes/zeta/images/small_cat/' . $id . '.png')) {
        return osc_current_web_theme_url() . 'images/small_cat/' . $id . '.png';
        
      } else if(zet_param('sample_images') == 1 && file_exists(osc_base_path() . zet_oc_content_folder() . '/themes/zeta/images/small_cat/sample/' . $id . '.png')) {
        return osc_current_web_theme_url() . 'images/small_cat/sample/' . $id . '.png';

      } else {
        return osc_current_web_theme_url() . 'images/small_cat/default.png';
      }
      
    } else {
      if(file_exists(osc_base_path() . zet_oc_content_folder() . '/themes/zeta/images/small_cat/' . $id . '.png')) {
        return '<img src="' . osc_current_web_theme_url() . 'images/small_cat/' . $id . '.png" />';
        
      } else if(zet_param('sample_images') == 1 && file_exists(osc_base_path() . zet_oc_content_folder() . '/themes/zeta/images/small_cat/sample/' . $id . '.png')) {
        return '<img src="' . osc_current_web_theme_url() . 'images/small_cat/sample/' . $id . '.png" />';
        
      } else {
        return '<img src="' . osc_current_web_theme_url() . 'images/small_cat/default.png" />';
      }
    }
  }

  if($string) {
    // do nothing
  } else {
    return $icon;
  }
}


function zet_get_cat_color($id, $category = array()) {
  $category_extra = zet_category_extra($id, $category);
  $default_colors = zet_default_colors();

  if($category_extra !== false && $category_extra['s_color'] <> '') {
    $color_code = $category_extra['s_color'];                        
  } else {
    if(isset($default_colors[$id]) && $default_colors[$id] <> '') {
      $color_code = $default_colors[$id];
    } else {
      $color_code = $default_colors[999];
    }
  }

  return $color_code;
}


// GET PROPER CATEGORY IMAGE (ICON)
function zet_get_cat_image($category_id) {
  if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/small_cat/' . $category_id . '.png')) {
    return osc_current_web_theme_url() . 'images/small_cat/' . $category_id . '.png';
    
  } else if(zet_param('sample_images') == 1 && file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/small_cat/sample/' . $category_id . '.png')) {
    return osc_current_web_theme_url() . 'images/small_cat/sample/' . $category_id . '.png';
    
  } else {
    return osc_current_web_theme_url() . 'images/small_cat/default.png';
  }
}


// INCREASE PHONE CLICK VIEWS
function zet_increase_clicks($itemId, $itemUserId = NULL) {
  if($itemId > 0) {
    if($itemUserId == '' || $itemUserId == 0 || ($itemUserId <> '' && $itemUserId > 0 && $itemUserId <> osc_logged_user_id())) {
      $db_prefix = DB_TABLE_PREFIX;
      $query = 'INSERT INTO ' . $db_prefix . 't_item_stats_zeta (fk_i_item_id, dt_date, i_num_phone_clicks) VALUES (' . $itemId . ', "' . date('Y-m-d') . '", 1) ON DUPLICATE KEY UPDATE  i_num_phone_clicks = i_num_phone_clicks + 1';
      return ItemStats::newInstance()->dao->query($query);
    }
  }
}


// FIX ADMIN MENU LIST WITH THEME OPTIONS
function zet_admin_menu_fix(){
  echo '<style>' . PHP_EOL;
  echo 'body.compact #zeta_menu .ico-zeta_menu {bottom:-6px!important;width:50px!important;height:50px!important;margin:0!important;background:#fff url(' . osc_base_url() . zet_oc_content_folder() . '/themes/zeta/images/favicons/favicon-32x32.png) no-repeat center center !important;}' . PHP_EOL;
  echo 'body.compact #zeta_menu .ico-zeta_menu:hover {background-color:rgba(255,255,255,0.95)!important;}' . PHP_EOL;
  echo 'body.compact #menu_zeta_menu > h3 {bottom:0!important;}' . PHP_EOL;
  echo 'body.compact #menu_zeta_menu > ul {border-top-left-radius:0px!important;margin-top:1px!important;}' . PHP_EOL;
  echo 'body.compact #menu_zeta_menu.current:after {content:"";display:block;width:6px;height:6px;border-radius:10px;box-shadow:1px 1px 3px rgba(0,0,0,0.1);position:absolute;left:3px;bottom:3px;background:#03a9f4}' . PHP_EOL;
  echo 'body:not(.compact) #zeta_menu .ico-zeta_menu {background:transparent url(' . osc_base_url() . zet_oc_content_folder() . '/themes/zeta/images/favicons/favicon-32x32.png) no-repeat center center !important;}' . PHP_EOL;
  echo '</style>' . PHP_EOL;
}

osc_add_hook('admin_header', 'zet_admin_menu_fix');




// CREATE URL FOR THEME AJAX REQUESTS
function zet_ajax_url() {
  return osc_base_url(true) . '?ajaxRequest=1';
}


// COUNT PHONE CLICKS ON ITEM
function zet_phone_clicks($item_id) {
  if($item_id <> '') {
    $db_prefix = DB_TABLE_PREFIX;

    $query = "SELECT sum(coalesce(i_num_phone_clicks, 0)) as phone_clicks FROM {$db_prefix}t_item_stats_zeta s WHERE fk_i_item_id = " . $item_id . ";";
    $result = ItemStats::newInstance()->dao->query($query);
    if(!$result) { 
      $prepare = array();
      return '0';
    } else {
      $prepare = $result->row();

      if($prepare['phone_clicks'] <> '') {
        return $prepare['phone_clicks'];
      } else {
        return '0';
      }
    }
  }
}


// NO CAPTCHA RECAPTCHA CHECK
function zet_show_recaptcha($section = ''){
  if(function_exists('anr_get_option')) {
    if(anr_get_option('site_key') <> '') {
      if($section == 'contact_listing') {
        if(anr_get_option('contact_listing') == '1') {
          osc_run_hook("anr_captcha_form_field");
        }
      } else if($section == 'login') {
        if(anr_get_option('login') == '1') {
          osc_run_hook("anr_captcha_form_field");
        }
      } else {
        // plugin sections are: login, registration, new, contact, contact_listing, send_friend, comment
        osc_run_hook("anr_captcha_form_field");
      }
    }
  } else {
    if(osc_recaptcha_public_key() <> '') {
      if(((osc_is_publish_page() || osc_is_edit_page()) && osc_recaptcha_items_enabled()) || (!osc_is_publish_page() && !osc_is_edit_page())) {
        osc_show_recaptcha($section);
      }
    }
  }
}


// SHOW BANNER
function zet_banner($location) {
  $html = '';
  $class = '';
  
  if(zet_param('banners') == 1) {
    if(zet_is_demo()) {
      $visible = array_filter(array_map('trim', explode(',', zet_param('banners_demo_ids'))));
      $always_show = false;
      
      //if(count($visible) > 0 && !in_array('banner_' . $location, $visible) && !in_array($location, $visible)) {
      if(in_array('banner_' . $location, $visible) || in_array($location, $visible)) {
        $always_show = true;
        $class .= ' is-visible';
      }
    }
    
    if(zet_is_demo()) {
      $class .= ' is-demo';
    }
    
    if(zet_param('banner_optimize_adsense') == 1) {
      $class .= ' opt-adsense';
    }

    if(zet_param('banner_' . $location) == '') {
      $blank = ' blank';
    } else {
      $blank = '';
    }

    if(zet_is_demo() && zet_param('banner_' . $location) == '') {
      $title = ' title="' . __('You can define your own banner code from theme settings', 'zeta') . '"';
    } else {
      $title = '';
    }

    $html .= '<div id="banner-theme" class="banner-theme banner-' . $location . ' not767' . $class . $blank . '"' . $title . '><div class="myad"><div class="text">';


    // BANNER ADS PLUGIN SUPPORT
    if(function_exists('ba_show_banner') && strpos(strtoupper(zet_param('banner_' . $location)), 'BANNER-ADS-PLUGIN') !== false) {
      $xdata = strtoupper(trim(zet_param('banner_' . $location)));

      if(strpos(zet_param('banner_' . $location), 'BANNER-ADS-PLUGIN-HOOK')) {
        $hook = trim(str_replace(array(' ', '  ', '{', '{{', '{{{', '}', '}}', '}}}', 'BANNER-ADS-PLUGIN-HOOK', ':'), '', $xdata));

        if(trim($hook) <> '') {
          $html .= ba_hook($hook, false);
        }
      } else if(strpos(zet_param('banner_' . $location), 'BANNER-ADS-PLUGIN-BANNER')) {
        $banner_id = trim(str_replace(array(' ', '  ', '{', '{{', '{{{', '}', '}}', '}}}', 'BANNER-ADS-PLUGIN-BANNER', ':'), '', $xdata));

        if(is_numeric($banner_id) && $banner_id > 0) {
          $html .= ba_show_banner($banner_id, false);
        }
      } else if(strpos(zet_param('banner_' . $location), 'BANNER-ADS-PLUGIN-ADVERT')) {
        $advert_id = trim(str_replace(array(' ', '  ', '{', '{{', '{{{', '}', '}}', '}}}', 'BANNER-ADS-PLUGIN-ADVERT', ':'), '', $xdata));

        if(is_numeric($advert_id) && $advert_id > 0) {
          $html .= ba_show_advert($advert_id);
        }
      }
    } else {
      $html .= zet_param('banner_' . $location);
    }
    
    
    if(zet_is_demo() && zet_param('banner_' . $location) == '') {
      $html .= '<div class="demo-text"><span>' . __('Banner space', 'zeta') . '</span><strong>[' .  str_replace('_', ' ', $location) . ']</strong></div>';
    }

    $html .= '</div></div></div>';

    if(!zet_is_demo() && trim(zet_param('banner_' . $location)) == '') {
      return '';
    } else {
      return $html;
    }
  } else {
    return false;
  }
}


function zet_banner_list() {
  $list = array(
    array('id' => 'banner_home_top', 'position' => __('Top of home page', 'zeta')),
    array('id' => 'banner_home_middle', 'position' => __('Middle of home page', 'zeta')),
    array('id' => 'banner_home_bottom', 'position' => __('Bottom of home page', 'zeta')),
    array('id' => 'banner_search_side', 'position' => __('Search side', 'zeta')),
    array('id' => 'banner_search_top', 'position' => __('Top of search page', 'zeta')),
    array('id' => 'banner_search_bottom', 'position' => __('Bottom of search page', 'zeta')),
    array('id' => 'banner_search_middle', 'position' => __('Between listings', 'zeta')),
    array('id' => 'banner_item_top', 'position' => __('Top of item page', 'zeta')),
    array('id' => 'banner_item_bottom', 'position' => __('Bottom of item page', 'zeta')),
    array('id' => 'banner_item_sidebar', 'position' => __('Middle of item sidebar', 'zeta')),
    array('id' => 'banner_item_sidebar_bottom', 'position' => __('Bottom of item sidebar', 'zeta')),
    array('id' => 'banner_item_description', 'position' => __('Under item description', 'zeta')),
    array('id' => 'banner_public_profile_sidebar', 'position' => __('Public profile sidebar', 'zeta')),
    array('id' => 'banner_public_profile_top', 'position' => __('Public profile above header', 'zeta')),
    array('id' => 'banner_public_profile_middle', 'position' => __('Public profile between items', 'zeta')),
    array('id' => 'banner_public_profile_bottom', 'position' => __('Public profile under items', 'zeta')),
    array('id' => 'banner_static_page_top', 'position' => __('Top of static page', 'zeta')),
    array('id' => 'banner_static_page_bottom', 'position' => __('Bottom of static page', 'zeta')),
    array('id' => 'banner_user_account_top', 'position' => __('Top of user account pages', 'zeta')),
    array('id' => 'banner_body_left', 'position' => __('All pages on left from body', 'zeta')),
    array('id' => 'banner_body_right', 'position' => __('All pages on right from body', 'zeta'))
 );

  return $list;
}


function zet_extra_fields_hide() {
  $list = trim(zet_param('post_extra_exclude'));
  $array = explode(',', $list);
  $array = array_map('trim', $array);
  $array = array_filter($array);

  if(!empty($array) && count($array) > 0) {
    return $array;
  } else {
    return array();
  }
}


// DISABLE ERROR404 ON SEARCH PAGE WHEN NO ITEMS FOUND
function zet_disable_404() {
  if(osc_is_search_page() && osc_count_items() <= 0) {
    if(http_response_code() == 404) {
      http_response_code(200);
    }
  }
}

osc_add_hook('header', 'zet_disable_404');


// THEME PARAMS UPDATE
if(!function_exists('zet_param_update')) {
  function zet_param_update( $param_name, $update_param_name, $type = NULL, $plugin_var_name = NULL ) {
  
    $val = '';
    if( $type == 'check') {

      // Checkbox input
      if( Params::getParam( $param_name ) == 'on' ) {
        $val = 1;
      } else {
        if( Params::getParam( $update_param_name ) == 'done' ) {
          $val = 0;
        } else {
          $val = ( osc_get_preference( $param_name, $plugin_var_name ) != '' ) ? osc_get_preference( $param_name, $plugin_var_name ) : '';
        }
      }
    } else {

      // Other inputs (text, password, ...)
      if( Params::getParam( $update_param_name ) == 'done' && Params::existParam($param_name)) {
        $val = Params::getParam( $param_name );
      } else {
        $val = ( osc_get_preference( $param_name, $plugin_var_name) != '' ) ? ($type == 'array' ? json_decode(osc_get_preference($param_name, $plugin_var_name), true) : osc_get_preference($param_name, $plugin_var_name)) : ($type == 'array' ? array() : '');
      }
    }


    // If save button was pressed, update param
    if( Params::getParam( $update_param_name ) == 'done' ) {

      if(osc_get_preference( $param_name, $plugin_var_name ) == '') {
        osc_set_preference( $param_name, ($type == 'array' ? json_encode($val, JSON_UNESCAPED_SLASHES) : $val), $plugin_var_name, 'STRING');  
      } else {
        $dao_preference = new Preference();
        $dao_preference->update( array( "s_value" => ($type == 'array' ? json_encode($val, JSON_UNESCAPED_SLASHES) : $val)), array( "s_section" => $plugin_var_name, "s_name" => $param_name ));
        osc_reset_preferences();
        unset($dao_preference);
      }
    }

    return $val;
  }
}



// FIND SUBCATEGORIES FROM LIST OF ALL CATEGORIES
// Effective version comparing to Category::newInstance()->findSubcategories($id)
function zet_find_subcats($parent_id, $all = NULL) {
  if($all === NULL) {
    //$all = Category::newInstance()->listEnabled();
    $all = zet_get_categories();
  }
  
  $output = array();
  if(is_array($all) && count($all) > 0) {
    foreach($all as $cat) {
      if(isset($cat['fk_i_parent_id']) && $cat['fk_i_parent_id'] == $parent_id) {
        $output[$cat['pk_i_id']] = $cat;
      }
    }
  }
  
  return $output;
}


// MULTI-LEVEL CATEGORIES SELECT
function zet_cat_tree() {
  $array = array();
  $root = Category::newInstance()->findRootCategoriesEnabled();
  //$all = Category::newInstance()->listEnabled();
  $all = zet_get_categories();
  
  $i = 0;
  foreach($root as $c) {
    $array[$i] = array('pk_i_id' => $c['pk_i_id'], 's_name' => $c['s_name'], 'i_num_items' => $c['i_num_items']);
    $array[$i]['sub'] = zet_cat_sub($c['pk_i_id']);
    $i++;
  }

  return $array;
}


function zet_cat_sub($id, $all = NULL) {
  $array = array();
  // $cats = Category::newInstance()->findSubcategories($id);
  $cats = zet_find_subcats($id, $all);
  
  if($cats && count($cats) > 0) {
    $i = 0;
    foreach($cats as $c) {
      $array[$i] = array('pk_i_id' => $c['pk_i_id'], 's_name' => $c['s_name'], 'i_num_items' => $c['i_num_items']);
      $array[$i]['sub'] = zet_cat_sub($c['pk_i_id']);
      $i++;
    }
  }

  return $array;
}


function zet_cat_list($selected = array(), $categories = '', $level = 0) {
  if($categories == '') {
    $categories = zet_cat_tree();
  }

  foreach($categories as $c) {
    echo '<option value="' . $c['pk_i_id'] . '" ' . (in_array($c['pk_i_id'], $selected) ? 'selected="selected"' : '') . '>' . str_repeat('-', $level) . ($level > 0 ? ' ' : '') . $c['s_name'] . '</option>';

    if(isset($c['sub']) && count($c['sub']) > 0) {
      zet_cat_list($selected, $c['sub'], $level + 1);
    }
  }
}


// CREATE CATEGORY BOX
function zet_cat_box($selected_cat_id = 0, $selected_cat_hierarchy = array()) {
  if($selected_cat_id > 0 && empty($selected_cat_hierarchy)) {
    $selected_cat_hierarchy = Category::newInstance()->toRootTree($search_cat_id);
  }

  $categories = zet_cat_tree();

  $html = '<div class="cat-box">' . PHP_EOL;
  $html .= zet_cat_box_one($categories, array(), $selected_cat_hierarchy);
  $html .= '<div class="cat-foot">';
  $html .= '<a href="#" class="close-category-box btn btn-white" data-id="' . $selected_cat_id . '">' . __('Cancel', 'zeta') . '</a>';
  $html .= '<a href="#" class="confirm-category-box btn btn-secondary" data-alt-text="' . osc_esc_html(sprintf(__('Select "%s"', 'zeta'), '{CAT}')) . '" data-orig-text="' . osc_esc_html(__('Select "All categories"', 'zeta')) . '">' . __('Select "All categories"', 'zeta') . '</a>';
  $html .= '</div>';
  $html .= '</div>' . PHP_EOL;

  return $html;
}


// GENERATE ONE BOX FOR LOOP
function zet_cat_box_one($categories, $parent = array(), $selected_cats = array()) {
  $html = '<div class="cat-one nice-scroll" data-id="' . (isset($parent['pk_i_id']) ? $parent['pk_i_id'] : 0) . '">' . PHP_EOL;
  
  // Has parent
  if(isset($parent['pk_i_id'])) {
    $html .= '<a href="#" class="cat-elem all-cat" data-id="0">' . PHP_EOL;
    // $html .= ICON_ALL . PHP_EOL;
    $html .= '<i class="fas fa-arrow-left"></i>' . PHP_EOL;
    $html .= '<span>' . __('Back to all categories', 'zeta') . '</span>' . PHP_EOL;
    $html .= '</a>' . PHP_EOL;
  }

  foreach($categories as $c) {
    $html .= zet_cat_box_loop_one($c);
  }
  
  $html .= '</div>' . PHP_EOL;

  foreach($categories as $c) {
    if(isset($c['sub']) && count($c['sub']) > 0) {
      $html .= zet_cat_box_one($c['sub'], $c, $selected_cats);
    }
  }

  return $html;
}


// GENERATE ONE ROW FOR LOOP
function zet_cat_box_loop_one($c, $class = '') {
  $has_subcat = false;

  if(isset($c['sub']) && count($c['sub']) > 0) {
    $has_subcat = true;
  }

  $html = '<a href="#" class="cat-elem' . ($has_subcat ? ' has-subcat' : '') . '' . ($class != '' ? ' ' . trim($class) : '') . '" data-id="' . $c['pk_i_id'] . '">' . PHP_EOL;

  if(zet_param('cat_icons') == 1) {
    $icon = zet_get_cat_icon($c['pk_i_id'], $c, true);
    $icon_ = explode(' ', $icon);
    
    $has_type = false;
    if(in_array($icon_, array('fas', 'far', 'fab'))) {
      $has_type = true;
    }

    $html .= '<i class="' . ($has_type ? '' : 'fas') . ' ' . $icon . '" ' . ($color <> '' ? 'style="color:' . $color . ';"' : '') . '></i>' . PHP_EOL;
  } else {
    $html .= '<img src="' . zet_get_cat_image($c['pk_i_id']) . '" alt="' . osc_esc_html($c['s_name']) . '" class="' . (zet_is_lazy() ? 'lazy' : '') . '"/>' . PHP_EOL;
  }
  
  $html .= '<span>' . $c['s_name'] . '</span>' . PHP_EOL;
  
  if(isset($c['i_num_items']) && $c['i_num_items'] > 0) { 
    $html .= '<em>(' . $c['i_num_items'] . ')</em>' . PHP_EOL;
  }
  
  $html .= '</a>' . PHP_EOL;
  
  return $html;
}



function zet_location_javascript($path = 'front') {
  ?>
  <script type="text/javascript">
  $(document).ready(function(){
    $("#countryId").on("change",function(){
      var pk_c_code = $(this).val();
      <?php if($path === 'admin') { ?>
        var url = '<?php echo osc_admin_base_url(true) . '?page=ajax&action=regions&countryId='; ?>' + pk_c_code;
      <?php } else { ?>
        var url = '<?php echo osc_base_url(true) . '?page=ajax&action=regions&countryId='; ?>' + pk_c_code;
      <?php } ?>
      var result = '';

      if(pk_c_code != '') {

        $("#regionId").attr('disabled',false);
        $("#cityId").attr('disabled',true);

        $.ajax({
          type: "POST",
          url: url,
          dataType: 'json',
          success: function(data){
            var length = data.length;
            var locationsNative = "<?php echo osc_get_current_user_locations_native(); ?>";

            if(length > 0) {
              result += '<option selected value=""><?php echo osc_esc_js(__('Select a region...')); ?></option>';
              for(key in data) {
                var vname = data[key].s_name;
                if(data[key].hasOwnProperty('s_name_native')) { 
                  if(data[key].s_name_native != '' && data[key].s_name_native != 'null' && data[key].s_name_native != null && locationsNative == "1") {
                    vname = data[key].s_name_native;
                  }
                }
                result += '<option value="' + data[key].pk_i_id + '">' + vname + '</option>';
              }

              $("#region").before('<select name="regionId" id="regionId" ></select>');
              $("#region").remove();
              $("#city").before('<select name="cityId" id="cityId" ></select>');
              $("#city").remove();
              $("#regionId").val("");
            } else {
              $("#regionId").before('<input type="text" name="region" id="region" />');
              $("#regionId").remove();
              $("#cityId").before('<input type="text" name="city" id="city" />');
              $("#cityId").remove();
            }

            $("#regionId").html(result);
            $("#cityId").html('<option selected value=""><?php echo osc_esc_js(__('Select a city...')); ?></option>');
            $("#regionId").trigger('change');
            $("#cityId").trigger('change');
          }
         });

       } else {

         // add empty select
         $("#region").before('<select name="regionId" id="regionId" ><option value=""><?php echo osc_esc_js(__('Select a region...')); ?></option></select>');
         $("#region").remove();

         $("#city").before('<select name="cityId" id="cityId" ><option value=""><?php echo osc_esc_js(__('Select a city...')); ?></option></select>');
         $("#city").remove();

         if($("#regionId").length > 0){
           $("#regionId").html('<option value=""><?php echo osc_esc_js(__('Select a region...')); ?></option>');
         } else {
           $("#region").before('<select name="regionId" id="regionId" ><option value=""><?php echo osc_esc_js(__('Select a region...')); ?></option></select>');
           $("#region").remove();
         }
         if($("#cityId").length > 0){
           $("#cityId").html('<option value=""><?php echo osc_esc_js(__('Select a city...')); ?></option>');
         } else {
           $("#city").before('<select name="cityId" id="cityId" ><option value=""><?php echo osc_esc_js(__('Select a city...')); ?></option></select>');
           $("#city").remove();
         }
         $("#regionId").attr('disabled',true);
         $("#cityId").attr('disabled',true);
       }
    });

    $("#regionId").on("change",function(){
      var pk_c_code = $(this).val();
      <?php if($path === 'admin') { ?>
        var url = '<?php echo osc_admin_base_url(true) . '?page=ajax&action=cities&regionId='; ?>' + pk_c_code;
      <?php } else { ?>
        var url = '<?php echo osc_base_url(true) . '?page=ajax&action=cities&regionId='; ?>' + pk_c_code;
      <?php } ?>

      var result = '';
      if(pk_c_code != '') {
        $("#cityId").attr('disabled',false);
        $.ajax({
          type: "POST",
          url: url,
          dataType: 'json',
          success: function(data){
            var length = data.length;
            var locationsNative = "<?php echo osc_get_current_user_locations_native(); ?>";

            if(length > 0) {
              result += '<option selected value=""><?php echo osc_esc_js(__('Select a city...')); ?></option>';
              for(key in data) {
                var vname = data[key].s_name;
                if(data[key].hasOwnProperty('s_name_native')) {
                  if(data[key].s_name_native != '' && data[key].s_name_native != 'null' && data[key].s_name_native != null && locationsNative == "1") {
                    vname = data[key].s_name_native;
                  }
                }

                result += '<option value="' + data[key].pk_i_id + '">' + vname + '</option>';
              }

              $("#city").before('<select name="cityId" id="cityId" ></select>');
              $("#city").remove();
            } else {
              result += '<option value=""><?php echo osc_esc_js(__('No results')); ?></option>';
              $("#cityId").before('<input type="text" name="city" id="city" />');
              $("#cityId").remove();
            }
            $("#cityId").html(result);
            $("#cityId").trigger('change');
          }
         });
       } else {
        $("#cityId").attr('disabled',true);
       }
    });

    if($("#regionId").attr('value') == "") {
      $("#cityId").attr('disabled',true);
    }

    if($("#countryId").length != 0) {
      if($("#countryId").prop('type').match(/select-one/)) {
        if($("#countryId").attr('value') == "") {
          $("#regionId").attr('disabled',true);
        }
      }
    }
  });
  </script>
  <?php
}





// COMPATIBILITY FUNCTIONS
if(!function_exists('osc_can_deactivate_items')) {
  function osc_can_deactivate_items() {
    return false;
  }
}

if(!function_exists('osc_item_can_renew')) {
  function osc_item_can_renew() {
    return false;
  }
}

if(!function_exists('osc_profile_img_users_enabled')) {
  function osc_profile_img_users_enabled() {
    return false;
  }
}

if(!function_exists('osc_item_show_phone')) {
  function osc_item_show_phone() {
    return true;
  }
}

if(!function_exists('osc_get_current_user_locations_native')) {
  function osc_get_current_user_locations_native() {
    return false;
  }
}

if(!function_exists('osc_location_native_name_selector')) {
  function osc_location_native_name_selector($array, $column = 's_name') {
    return @$array[$column];
  }
}

if(!function_exists('osc_count_cities')) {
  function osc_count_cities($region = '%%%%') {
    if(!View::newInstance()->_exists('cities')) {
      View::newInstance()->_exportVariableToView('cities', Search::newInstance()->listCities($region, ">=", "city_name ASC"));
    }

    return View::newInstance()->_count('cities');
  }
}

if(!function_exists('array_column')) {
  function array_column(array $input, $columnKey, $indexKey = null) {
    $array = array();
    foreach ($input as $value) {
      if(!array_key_exists($columnKey, $value)) {
        trigger_error("Key \"$columnKey\" does not exist in array");
        return false;
      }
      if(is_null($indexKey)) {
        $array[] = $value[$columnKey];
      }
      else {
        if(!array_key_exists($indexKey, $value)) {
          trigger_error("Key \"$indexKey\" does not exist in array");
          return false;
        }
        if(! is_scalar($value[$indexKey])) {
          trigger_error("Key \"$indexKey\" does not contain scalar value");
          return false;
        }
        $array[$value[$indexKey]] = $value[$columnKey];
      }
    }
    return $array;
  }
}

if(!function_exists('osc_is_current_page')){
  function osc_is_current_page($location, $section) {
    if(osc_get_osclass_location() === $location && osc_get_osclass_section() === $section) {
      return true;
    }

    return false;
  }
}

if(!function_exists('osc_is_register_page')) {
  function osc_is_register_page() {
    return osc_is_current_page("register", "register");
  }
}

if(!function_exists('osc_is_edit_page')) {
  function osc_is_edit_page() {
    return osc_is_current_page('item', 'item_edit');
  }
}

if(!function_exists('osc_count_countries')) {
  function osc_count_countries() {
    if(!View::newInstance()->_exists('contries')) {
      View::newInstance()->_exportVariableToView('countries', Search::newInstance()->listCountries(">=", "country_name ASC"));
    }
    
    return View::newInstance()->_count('countries');
  }
}


// GET USER FROM SESSION
if(!function_exists('osc_get_user_row')) {
  function osc_get_user_row($id) {
    if($id <= 0) {
      return false;
    }

    if(View::newInstance()->_exists('user_' . $id)) {
      return View::newInstance()->_get('user_' . $id);
    }
    
    $user = User::newInstance()->findByPrimaryKey($id);
    View::newInstance()->_exportVariableToView('user_' . $id, $user);
    
    return $user;
  }
}


// GET ITEM FROM SESSION
if(!function_exists('osc_get_item_row')) {
  function osc_get_item_row($id) {
    if($id <= 0) {
      return false;
    }

    if(View::newInstance()->_exists('item_' . $id)) {
      return View::newInstance()->_get('item_' . $id);
    }
    
    $item = Item::newInstance()->findByPrimaryKey($id);
    View::newInstance()->_exportVariableToView('item_' . $id, $item);
    
    return $item;
  }
}

// GET CATEGORY ROW CACHED
if(!function_exists('osc_get_category_row')) {
  function osc_get_category_row($id) {
    if($id <= 0) {
      return false;
    }

    if(View::newInstance()->_exists('category_' . $id)) {
      return View::newInstance()->_get('category_' . $id);
    }
    
    $category = Category::newInstance()->findByPrimaryKey($id);
    View::newInstance()->_exportVariableToView('category_' . $id, $category);
    
    return $category;
  }
}


// GET COUNTRY ROW CACHED
if(!function_exists('osc_get_country_row')) {
  function osc_get_country_row($code) {
    if($code == '') {
      return false;
    }

    if(View::newInstance()->_exists('country_' . $code)) {
      return View::newInstance()->_get('country_' . $code);
    }
    
    $country = Country::newInstance()->findByCode($code);
    View::newInstance()->_exportVariableToView('country_' . $code, $country);
    
    return $country;
  }
}


// GET REGION ROW CACHED
if(!function_exists('osc_get_region_row')) {
  function osc_get_region_row($id) {
    if($id <= 0) {
      return false;
    }

    if(View::newInstance()->_exists('region_' . $id)) {
      return View::newInstance()->_get('region_' . $id);
    }
    
    $region = Region::newInstance()->findByPrimaryKey($id);
    View::newInstance()->_exportVariableToView('region_' . $id, $region);
    
    return $region;
  }
}


// GET CITY ROW CACHED
if(!function_exists('osc_get_city_row')) {
  function osc_get_city_row($id) {
    if($id <= 0) {
      return false;
    }

    if(View::newInstance()->_exists('city_' . $id)) {
      return View::newInstance()->_get('city_' . $id);
    }
    
    $city = City::newInstance()->findByPrimaryKey($id);
    View::newInstance()->_exportVariableToView('city_' . $id, $city);
    
    return $city;
  }
}


// GET LOGGED USER OBJECT
if(!function_exists('osc_logged_user')) {
  function osc_logged_user() {
    if(osc_is_web_user_logged_in()) {
      if(View::newInstance()->_exists('_loggedUser')) {
        return View::newInstance()->_get('_loggedUser');
      }
    }
    
    return false;
  }
}



// RETURN DISTANCE BETWEEN 2 COORDINATES IN KM
function zet_calc_cord_distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371){
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  $dist = (float)($angle * $earthRadius);
  
  return number_format($dist, 2);
}


function zet_oc_content_folder() {
  if(defined('OC_CONTENT_FOLDER') && OC_CONTENT_FOLDER != '') {
    return OC_CONTENT_FOLDER;
  }
  
  return 'oc-content';
}

?>