<?php

// DUPLICATE CHECKED
function ans_duplicate_check($item_input) {
  $item = array();
  if(isset($item_input['pk_i_id']) && $item_input['pk_i_id'] > 0) {
    $item = Item::newInstance()->findByPrimaryKey($item_input['pk_i_id']);
  } else if($item_input > 0) {
    $item = Item::newInstance()->findByPrimaryKey($item_input);
  } else {
    return false;
  }

  $block = false;
  $action = ans_param('action_check');
  $dup_perc = ans_param('duplicate_percent');
  $duplicate_length = ans_param('duplicate_length');

  if(@$item['fk_c_locale_code'] <> '') {
    $locale = $item['fk_c_locale_code'];
  } else {
    $locale = '';
  }

  $origin_title = substr(trim(strtolower($item['s_title'])), 1, $duplicate_length);
  $origin_description = substr(trim(strtolower($item['s_description'])), 1, $duplicate_length);

  $title_percent = 0;
  $description_percent = 0;
  
  // We have user email, go to check all listings from this email
  foreach(ModelANS::newInstance()->getItemsByEmail($item['s_contact_email'], $locale, $item['pk_i_id']) as $item_check) {
    $check_title = substr(trim(strtolower($item_check['s_title'])), 1, $duplicate_length);
    $check_description = substr(trim(strtolower($item_check['s_description'])), 1, $duplicate_length);

    // check similarity on title
    similar_text($origin_title, $check_title, $title_percent);

    // check similarity on description
    similar_text($origin_description, $check_description, $description_percent);

    if( $title_percent >= $dup_perc) { 
      if($action == 'spam') {
        ModelANS::newInstance()->updateItemDuplicate($item_check['pk_i_id']);       // Block older listing
      }

      if($action == 'delete') {       
        Item::newInstance()->deleteByPrimaryKey($item_check['pk_i_id']);            // Delete older listing
      }
    } 
    
    if( $description_percent >= $dup_perc) { 
      if($action == 'spam') {
        ModelANS::newInstance()->updateItemDuplicate($item_check['pk_i_id']);       // Block older listing
      }

      if($action == 'delete') {       
        Item::newInstance()->deleteByPrimaryKey($item_check['pk_i_id']);            // Delete older listing
      }
    }
  }
}


function ans_duplicate_check_hook($item = null) {
  if(ans_param('allow_check') == 1) {
    ans_duplicate_check($item);
  }
}

osc_add_hook('posted_item', 'ans_duplicate_check_hook');
osc_add_hook('activate_item', 'ans_duplicate_check_hook');



// Call stopforumspam
function ans_call_stopforumspam($email, $ip) {
  $url = 'https://api.stopforumspam.org/api';

  $data = array(
    'email' => $email,
    'ip' => $ip,
    'badtorexit',
    'json'
  );

  $data = http_build_query($data);

  // init the request, set some info, send it and finally close it
  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $result = curl_exec($ch);

  curl_close($ch);
  
  if($result !== false) {
    $result = @json_decode($result, true);
  }

  return $result;
}


// Anti-spam & bot protect checking
function ans_bot_check($id = null, $item = null) {
  $allowed = ans_param('allow_triple');
  $honeypot_enabled = ans_param('honeypot_enabled');
  $do_ban = ans_param('ban_triple');
  $min_confidence = (ans_param('min_confidence') >= 0 ? ans_param('min_confidence') : 70);
  $submask = ans_param('submask_triple');
  $domains = array_map('trim', array_filter(explode(',', ans_param('domains_triple'))));
  $white_domains = array_map('trim', array_filter(explode(',', ans_param('white_domains'))));
  $mail_block = false;
  
  $allowed_dots = ans_param('dots_triple');
  $stopforumspam = ans_param('stopforumspam_triple');
  $upper_triple = ans_param('upper_triple');
  $number_triple = ans_param('number_triple');
  $track_reffer = ans_param('track_reffer');
  $multiple_accounts = ans_param('multiple_user_ip');
  $ip = '';
  $reason = '';
  $reasons = array();


  if($allowed == 1) {
    $email = isset($item['s_contact_email']) ? $item['s_contact_email'] : '';
    if($email == '') { $email = Params::getParam('authorEmail');}       //Add comment form
    if($email == '') { $email = Params::getParam('yourEmail');}         //Contact seller post
    if($email == '') { $email = Params::getParam('contactEmail');}      //Item post, Item edit
    if($email == '') { $email = Params::getParam('s_email');}           //User registration

    $email_domain = substr($email, strpos($email, '@')+1);
    $ip = get_client_ip();
    $ip_v4_valid = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);

    $user_accounts = ModelANS::newInstance()->countUsersByIP($ip);
    $user_accounts_count = $user_accounts['user_count'];


    //Lets check this email and ip on stopforumspam.com
    $spambot = false;
    $spambot_type = array();
    
    if($stopforumspam == 1) {
      $response = ans_call_stopforumspam($email, $ip);

      if($response !== false && isset($response['success']) && $response['success'] == 1) {
        // Email check
        $r_email = isset($response['email']) ? $response['email'] : array();

        if(@$r_email['blacklisted'] == 1 || (@$r_email['appears'] == 1 && @$r_email['confidence'] >= $min_confidence)) {
          $spambot = true;
          $spambot_type[] = __('Email', 'spam');
        }
        
        // IP Check
        $r_ip = isset($response['ip']) ? $response['ip'] : array();
        
        if(@$r_ip['appears'] == 1 && @$r_ip['confidence'] >= $min_confidence) {
          $spambot = true; 
          $spambot_type[] = __('IP', 'spam');
        }
        
        if($spambot) {
          $reasons[] = 'StopForumSpam.com : ' . implode(' & ', $spambot_type);
        }        
      }
    }

    // Check for refferal. If is empty, we will not use it since it is pretty risky
    $bad_refer = false;
    if($track_reffer == 1) {
      $refering_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

      if($refering_url <> '') {
        // Get and clear web url
        $web_url = osc_base_url();
        $web_url = str_replace('http://', '', $web_url);
        $web_url = str_replace('www.', '', $web_url);
        $web_url = str_replace('/', '', $web_url);
        $web_url = strtolower($web_url);
        $refering_url = strtolower($refering_url);
        
        if( 
          strpos($refering_url, $web_url) === false 
          && strpos($refering_url, 'google') === false
          && strpos($refering_url, 'yahoo') === false
          && strpos($refering_url, 'bing') === false
        ) {
          $bad_refer = true;
          $reasons[] = __('Bad referring URL', 'spam');
        }        
      }
    }

    // Check for uppercase and numbers in mail
    if($upper_triple == 1 || $number_triple == 1) {
      $mail_block = false;
      $mail_control = $email;
      $mail_control = substr($mail_control, 0, strrpos($mail_control, '@'));
      $mail_control = str_replace('.', '', $mail_control);

      $mail_length = strlen($mail_control);
      preg_match_all('/[A-Z]/', $mail_control, $match_upper);
      preg_match_all('/[0-9]/', $mail_control, $match_number);

      $total_upper = count($match_upper[0]);
      $total_number = count($match_number[0]);

      if($total_upper > 0.3 * $mail_length && $mail_length > 10 && $upper_triple == 1)  { 
        $mail_block = true;
        $reasons[] = __('Too many uppercase letters in email', 'spam') . ': ' . $total_upper . ' ' . __('chars', 'spam');
      }
      if($total_number > 4 && $number_triple == 1)  { 
        $mail_block = true;
        $reasons[] = __('Too many digits in email', 'spam') . ': ' . $total_number . ' ' . __('digits', 'spam');
      }
    }
 
    // Honeypot check
    $honeypot_check = false;
    if(
      $honeypot_enabled == 1 
      && Params::getParam('xcheckfield') != '' 
      && (
        Params::getParam('xcheckfield') <> 'ans' 
        || Params::getParam('whatSellA') <> 'greenTshirt' 
        || Params::getParam('sellingToB') <> 'greenTshirt' 
        || Params::getParam('userNameC') <> '' 
        || Params::getParam('userAddressD') <> '' 
        || Params::getParam('cityE') <> '' 
        || Params::getParam('yourAgeF') <> date("Y")
      )
    ) {
      $honeypot_check = true;
      $reasons[] = sprintf(__('Failed honeypot check. Values entered: %s', 'spam'), Params::getParam('xcheckfield') . ' / ' . Params::getParam('whatSellA') . ' / ' . Params::getParam('sellingToB') . ' / ' . Params::getParam('userNameC') . ' / ' . Params::getParam('userAddressD') . ' / ' . Params::getParam('cityE') . ' / ' . Params::getParam('yourAgeF'));
    }

    // Multiple user accounts check
    if($multiple_accounts <> 0 && $user_accounts_count >= 1 && Params::getParam('action') == 'register_post') {
      if($multiple_accounts == 1 && $user_accounts_count >= 3) {
        osc_add_flash_error_message(__('Your account was not created because there are multiple accounts created from your IP address.', 'spam'));
        header('Location: '.osc_base_url());
        exit;
      }

      if($multiple_accounts == 2 && $user_accounts_count >= 1) {
        osc_add_flash_error_message(__('Your account was not created because only 1 account can be created by your IP address.', 'spam'));
        header('Location: '.osc_base_url());
        exit;
      }
    }

    if(
      $honeypot_check 
      || in_array($email_domain, $domains) 
      || (!empty($white_domains) && !in_array($email_domain, $white_domains))
      || $allowed_dots < substr_count(substr($email,0, strpos($email, '@')), '.')
      || $spambot
      || $mail_block
      || $bad_refer
    ) {
  
      // Found bot
      if($allowed_dots < substr_count( substr($email,0, strpos($email, '@')), '.')) {
        $reasons[] = __('Too many dots in email', 'spam') . ': ' . substr_count( substr($email,0, strpos($email, '@')), '.') . ' dots';
      }

      if(in_array($email_domain, $domains)) {
        $reasons[] = __('Banned email domain', 'spam') . ': ' . $email_domain;
      }

      if(!empty($white_domains) && !in_array($email_domain, $white_domains)) {
        $reasons[] = __('Email domain not in white list', 'spam') . ': ' . $email_domain;
      }
      
      $reason = implode('; ', $reasons);

      $user_reg = '';
      if(Params::getParam('s_email') <> '') {
        $user_reg = 'User registration / ';
      }

      // if($do_ban == 1 && $ip_v4_valid) {
      if($do_ban == 1) {
        if($submask == 1) {
          $ip = substr($ip, 0, strrpos($ip, '.')) . '.*';
        }
        
        osc_add_flash_error_message(__('You were permanently banned as spam bot', 'spam'));
        ModelANS::newInstance()->insertBan(sprintf(__('Spam > Bot identified (%s%s)', 'spam'), $user_reg, $reason), $email, $ip);
        
      } else {
        osc_add_flash_error_message(__('Your request has been blocked as it looks like spam', 'spam'));
      }

      // if comment, delete it
      if(Params::getParam('action') == 'add_comment') {
        ItemComment::newInstance()->deleteByPrimaryKey($id);
      }
      
      // Honepot control, we might redirect to some nasty page here
      if($honeypot_check) {
        // do not do anything now
      }
      
      header('Location: ' . osc_base_url()); 
      exit;
    }
  }
}

osc_add_hook('pre_item_post', 'ans_bot_check', 1);
osc_add_hook('posted_item', 'ans_bot_check', 1);
osc_add_hook('hook_email_item_inquiry', 'ans_bot_check', 1);
osc_add_hook('add_comment', 'ans_bot_check', 1);
osc_add_hook('before_user_register', 'ans_bot_check', 1);
osc_add_hook('pre_contact_post', 'ans_bot_check', 1);


// Add honeypot fields to forms
function ans_bot_protect_form() { 
  if(ans_param('honeypot_enabled') == 1) {
    include 'form/form_bot_protect.php';        // not include_once as it will not work if there are multiple forms at same page!
  }
} 

osc_add_hook('item_contact_form', 'ans_bot_protect_form', 1);
osc_add_hook('item_comment_form', 'ans_bot_protect_form', 1);
osc_add_hook('item_publish_top', 'ans_bot_protect_form', 1);
osc_add_hook('user_register_form', 'ans_bot_protect_form', 1);
osc_add_hook('user_login_form', 'ans_bot_protect_form', 1);
osc_add_hook('contact_form', 'ans_bot_protect_form', 1);
osc_add_hook('ans_bot_protect', 'ans_bot_protect_form', 1);





// BAN WORDS CONTROL
function ans_banwords($item) {
  $banwords_allowed = ans_param('enable_banwords');
  $banwords_list = trim(ans_param('list_banwords')) . ',';

  if($banwords_allowed == 1) {
    $action = '';
    
    $email = $item['s_contact_email'];
    if($email == '') { $email = Params::getParam('authorEmail'); $action = 'comment'; }
    if($email == '') { $email = Params::getParam('yourEmail'); $action = 'contact'; }
    if($email == '') { $email = Params::getParam('contactEmail'); $action = 'listing'; }

    $post_title = Params::getParam('title');
    $post_title_final = '';

    if(is_array($post_title)) {
      $post_title_final = implode(' ', $post_title);
    } else {
      $post_title_final = $post_title;
    }

    // Only one variable of bellow listed should be non-empty
    $desc  = $item['s_description'] . Params::getParam('body') . Params::getParam('message');
    $title = $item['s_title'] . $post_title_final;
    
    $banwords_array = explode(',', $banwords_list);
    $banwords_array = array_filter($banwords_array);  //remove NULLs
    $ban = false;

    foreach($banwords_array as $word) {
      if(trim($word) <> '') {
        if(stripos($title, ' ' . trim($word) . ' ') !== false || stripos($desc, ' ' . trim($word) . ' ') !== false) {
          // Banword found, user should be banned, foreach loop can be cancelled
          $ban = true;
          break;
        } 
      }
    }

    // User should be banned
    if($ban) {
      ModelANS::newInstance()->insertBan(sprintf(__('Spam > Banword (%s)', 'spam'), trim($word)), $email, get_client_ip() );
      osc_add_flash_error_message(__('You were permanently banned as spam bot', 'spam'));

      if($item['pk_i_id'] <> '') {
        // Banword used on new comment form
        if($action = 'comment') {
          ItemComment::newInstance()->deleteByPrimaryKey( $item['pk_i_id'] );
        }

        // Banword found on new listing form
        if($action = 'listing') {
          Item::newInstance()->deleteByPrimaryKey($item['pk_i_id']);
        }
      }

      header('Location: '.osc_base_url());
      exit;
    }
  }
}

osc_add_hook('posted_item', 'ans_banwords');
osc_add_hook('hook_email_item_inquiry', 'ans_banwords');
osc_add_hook('add_comment', 'ans_banwords');



// REMOVE BAD WORDS FROM ITEM TITLE
function ans_clear_title($item_title = null) {
  $badwords_allowed = ans_param('enable_badwords');
  $badwords_list = trim(ans_param('list_badwords'));

  $title = trim($item_title);
  if($title == '') { $title = osc_item_title(); }

  if($badwords_allowed == 1) {
    $badwords_array = explode(',', $badwords_list);
    $badwords_array = array_filter( $badwords_array, 'strlen' );  //remove NULLs

    foreach($badwords_array as $word) {
      $title = str_ireplace($word, str_pad('', strlen($word), '*'), $title);
    }
  }

  return $title;
}

osc_add_filter('item_title', 'ans_clear_title');


// REMOVE BAD WORDS FROM ITEM DESCRIPTION
function ans_clear_description($item_desc = null) {
  $badwords_allowed = ans_param('enable_badwords');
  $badwords_list = trim(ans_param('list_badwords'));

  $desc = trim($item_desc);
  if($desc == '') { $desc = osc_item_description(); }

  if($badwords_allowed == 1) {
    $badwords_array = explode(',', $badwords_list);
    $badwords_array = array_filter( $badwords_array, 'strlen' );  //remove NULLs

    foreach($badwords_array as $word) {
      //$desc = str_ireplace(' ' . $word . ' ', ' ' . str_pad('', strlen($word), '*') . ' ', $desc);
      $desc = str_ireplace($word, str_pad('', strlen($word), '*'), $desc);
    }
  }

  return $desc;
}

osc_add_filter('item_description', 'ans_clear_description');


// REMOVE BAD WORDS FROM TEXT
function ans_clear_text($text = '') {
  if(ans_param('enable_badwords') == 1 && trim((string)$text) != '') {
    $badwords_arr = array_filter(explode(',', ans_param('list_badwords')), 'strlen');

    if(is_array($badwords_arr) && count($badwords_arr) > 0) {
      foreach($badwords_arr as $word) {
        if(trim($word) != '') {
          $text = str_ireplace($word, str_pad('', strlen($word), '*'), (string)$text);
        }
      }
    }
  }

  return $text;
}

osc_add_filter('osc_item_meta_textarea_value_filter', 'ans_clear_text');
osc_add_filter('osc_item_meta_value_filter', 'ans_clear_text');
osc_add_filter('atr_item_value_pre_filter', 'ans_clear_text');


// FULL USER IP ON REGISTRATION
function ans_user_reg_ip($user_id) {
  $user_ip = get_client_ip();
  $ip_v4_valid = filter_var($user_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);

  if($ip_v4_valid && $user_ip <> '' && $user_id <> '') {
    ModelANS::newInstance()->updateUserIP( $user_id, $user_ip );
  }
}

osc_add_hook('user_register_completed', 'ans_user_reg_ip');







// CORE FUNCTIONS
function ans_param($name) {
  return osc_get_preference($name, 'plugin-spam');
}


if(!function_exists('mb_param_update')) {
  function mb_param_update( $param_name, $update_param_name, $type = NULL, $plugin_var_name = NULL ) {
  
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
        $val = ( osc_get_preference( $param_name, $plugin_var_name) != '' ) ? osc_get_preference( $param_name, $plugin_var_name ) : '';
      }
    }


    // If save button was pressed, update param
    if( Params::getParam( $update_param_name ) == 'done' ) {

      if(osc_get_preference( $param_name, $plugin_var_name ) == '') {
        osc_set_preference( $param_name, $val, $plugin_var_name, 'STRING');  
      } else {
        $dao_preference = new Preference();
        $dao_preference->update( array( "s_value" => $val ), array( "s_section" => $plugin_var_name, "s_name" => $param_name ));
        osc_reset_preferences();
        unset($dao_preference);
      }
    }

    return $val;
  }
}


// CHECK IF RUNNING ON DEMO
function ans_is_demo() {
  if(osc_logged_admin_username() == 'admin') {
    return false;
  } else if(isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'],'mb-themes') !== false || strpos($_SERVER['HTTP_HOST'],'abprofitrade') !== false)) {
    return true;
  } else {
    return false;
  }
}


if(!function_exists('message_ok')) {
  function message_ok( $text ) {
    $final  = '<div class="flashmessage flashmessage-ok flashmessage-inline">';
    $final .= $text;
    $final .= '</div>';
    echo $final;
  }
}


if(!function_exists('message_error')) {
  function message_error( $text ) {
    $final  = '<div class="flashmessage flashmessage-error flashmessage-inline">';
    $final .= $text;
    $final .= '</div>';
    echo $final;
  }
}



// COOKIES WORK
if(!function_exists('mb_set_cookie')) {
  function mb_set_cookie($name, $val) {
    Cookie::newInstance()->set_expires( 86400 * 30 );
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


if(!function_exists('mb_generate_rand_string')) {
  function mb_generate_rand_string($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
  }
}


// Get client IP
if(!function_exists('get_client_ip')) {
  function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] <> '')
      $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] <> '')
      $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']) && $_SERVER['HTTP_X_FORWARDED'] <> '')
      $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']) && $_SERVER['HTTP_FORWARDED_FOR'] <> '')
      $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']) && $_SERVER['HTTP_FORWARDED'] <> '')
      $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] <> '')
      $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
      $ipaddress = 'UNKNOWN';
    if(strpos($ipaddress, ',')) {
      return trim(substr($ipaddress, 0, strpos($ipaddress, ','))); 
    } else {
      return trim($ipaddress); 
    }
  }
}



// JAVASCRIPT SCROLL TO DIV
function ans_js_scroll($block) { 
  ?>

  <script>
    $(document).ready(function() {
      if($('<?php echo $block; ?>').length) { 
        var flash = $('.mb-head').nextAll('.flashmessage');
        flash = flash.add('#content-render > .flashmessage:not(.jsMessage)');
        flash.each(function(){
          $(this).removeAttr('style');
          $(this).removeAttr('style');
          $(this).find('a.btn').remove();
          $(this).html($(this).text().trim());

          if($(this).text() != '') {
            $('<?php echo $block; ?>').before($(this).wrap('<div/>').parent().html());
            $(this).hide(0);
          }
        });

        var flashCount = 0;

        if(flash.length > 0) {
          flashCount = flash.length;
        }

        $('html,body').animate({scrollTop: $('<?php echo $block; ?>').offset().top - 70 - parseInt(flashCount*64)}, 0);

      }
    });
  </script>

  <?php
}


?>