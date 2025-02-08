<?php

// INCLUDE MAILER SCRIPT
function vrt_include_mailer() {
  if(file_exists(osc_lib_path() . 'phpmailer/class.phpmailer.php')) {
    require_once osc_lib_path() . 'phpmailer/class.phpmailer.php';
  } else if(file_exists(osc_lib_path() . 'vendor/phpmailer/phpmailer/class.phpmailer.php')) {
    require_once osc_lib_path() . 'vendor/phpmailer/phpmailer/class.phpmailer.php';
  }
}

// CREATE DOWNLOAD LINK
function vrt_download($item_id = -1) {
  if($item_id <= 0) {
    $item_id = osc_item_id();
  }

  if($item_id <= 0) {
    $item_id = osc_premium_id();
  }

  if($item_id <= 0) {
    return false;
  }

  $check_order = vrt_item_purchased($item_id, osc_logged_user_id());
  $file = vrt_item_file($item_id);

  $class = (vrt_param('style_button') == 1 ? ' vrt-button' : '');

  if($file !== false && isset($file['pk_i_id']) && $file['i_version'] <> '') {
    if(osc_logged_user_id() == osc_item_user_id() || osc_is_admin_user_logged_in() || $check_order !== false || osc_item_price() <= 0) {
      return '<a class="vrt-download' . $class . '" href="' . osc_route_url('vrt-download', array('itemId' => osc_item_id())) . '">' . sprintf(__('Download v%s', 'virtual'), $file['i_version']) . ' <em>(' . $file['i_download'] . 'x)</em></a>';
    } else if (!osc_is_web_user_logged_in()) {
      return '<a class="vrt-download vrt-disabled' . $class . '" href="' . osc_user_login_url() . '" title="' . osc_esc_html(__('Please login to download this file', 'virtual')) . '">' . sprintf(__('Download v%s', 'virtual'), $file['i_version']) . ' <em>(' . $file['i_download'] . 'x)</em></a>';
    }
  }

  return false;
}


// CHECK IF ITEM PRUCHASED (IN CASE OSCLASS PAY INSTALLED)
function vrt_item_purchased($item_id, $user_id) {
  if(function_exists('osp_product_to_cart_link_hook') && vrt_param('osclasspay_link') == 1) {
    if($user_id <= 0 || $item_id <= 0) {
      return false;
    }
    
    $order = ModelOSP::newInstance()->checkOrder($item_id, $user_id);

    if($order !== false) {
      return true;
    } else {
      return false;
    }
  }

  return true;
}


// GET LAST FILE BY ITEM ID
function vrt_item_file($item_id) {
  if($item_id > 0) {
    return ModelVRT::newInstance()->getLastFileByItemId($item_id);
  }
  
  return false;
}


// GET VERSION OF LAST FILE BY ITEM ID
function vrt_item_file_version($item_id) {
  if($item_id > 0) {
    $file = vrt_item_file($item_id);
    
    if(isset($file['i_version']) && $file['i_version'] <> '') {
      return $file['i_version'];
    }
  }

  return false;
}



// UPLOAD FUNCTIONALITY
function vrt_upload($item, $is_publish = true) {
  if(Params::getParam('vrtUpload') == 'done') {
    $item_id = $item['pk_i_id'];
    $upload_ok = false;
    $file = Params::getFiles('attachment');

    $allowed_extensions = array_map('trim', explode(',', vrt_param('allowed_extensions')));
    $require_validation = vrt_param('require_validation');
    $remove_older = vrt_param('remove_older');
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $max_file_size = 20024 * 1000;  //(in bytes) ...20MB    // vrt_param('max_file_size')*1024*1024
    
    $file_size = $file['size'];
    $title_part = substr(preg_replace('/[^\w]/', '', preg_replace('/\s+/', '', osc_sanitizeString(strtolower($item['s_title'])))), 0, 20);
    $title_part = ($title_part <> '' ? $title_part : 'item');
    $file_name = $item['pk_i_id'] . '_' . $title_part . '_v' . Params::getParam('productVersion') . '_' . date('Ymd') . '_' . strtolower(mb_generate_rand_string(6)) . '.' . $extension;

    $update_file_name = '';
    if($file['name'] <> '') {
      if($file['error'] == UPLOAD_ERR_OK) {
        if(in_array($extension, $allowed_extensions) || empty($allowed_extensions)) {
          if( $file_size < $max_file_size || $max_file_size == '' ) {
            if( move_uploaded_file($file['tmp_name'], osc_content_path() . 'plugins/virtual/files/' . $file_name ) ) {
              $update_file_name = $file_name;
              $upload_ok = true;
            } else {
              osc_add_flash_error_message(__('An error with file upload has occurred, please try again', 'virtual') );
            }
          } else {
            osc_add_flash_error_message( __('File is too big and was not uploaded. Maximum file size is:', 'virtual') . ' ' . round($max_file_size/1000) . 'kb' );
          }
        } else {
          osc_add_flash_error_message( __('File extension is not allowed, file was not sent. Only files with following extensions are allowed to be uploaded', 'virtual') . ': ' . implode(', ', $allowed_extensions) );
        }
      } else {
        osc_add_flash_error_message( __('An error with file upload has occurred, please try again.', 'virtual') );
      }
    } else {
      if(vrt_param('file_required') == 1) {
        osc_add_flash_error_message( __('No file selected.', 'virtual') );
      }
    }

    
    $fields_file = array(
      'fk_i_item_id' => $item['pk_i_id'],
      'i_version' => Params::getParam('productVersion'),
      's_comment' => Params::getParam('versionSummary'),
      's_file' => $file_name,
      'i_status' => ($require_validation == 1 ? 0 : 1)
    );


    if($upload_ok) {
      $id = ModelVRT::newInstance()->insertFile($fields_file);

      $check_files = ModelVRT::newInstance()->getFilesByItemId($item_id);
      $last_valid = ModelVRT::newInstance()->getLastFileByItemId($item_id, 1);

      if($check_files) {
        foreach($check_files as $cf) {
          if($cf['pk_i_id'] < $id && $cf['pk_i_id'] <> @$last_valid['pk_i_id']) {
            if($cf['i_status'] == 0 || $cf['i_status'] == 1) {
              ModelVRT::newInstance()->updateFileStatusById($cf['pk_i_id'], 2, $cf['s_comment']);
            }

            if($remove_older == 1 && file_exists(osc_content_path() . 'plugins/virtual/files/' . $cf['s_file']) && $cf['s_file'] <> '') {
              unlink(osc_content_path() . 'plugins/virtual/files/' . $cf['s_file']);
            }
          }
        }
      }

      if($require_validation == 1) {
        vrt_email_file_admin($item['pk_i_id']);
        osc_add_flash_ok_message( __('New file version uploaded successfully, it will be active after validation.', 'virtual') );
      } else {
        osc_add_flash_ok_message( __('New file version uploaded successfully.', 'virtual') );
      }
    }
    

    if(!$is_publish) {
      header('Location:'.osc_route_url('vrt-upload', array('itemId' => Params::getParam('itemId'))));
    }
  }
}



// UPLOAD FUNCTIONALITY
function vrt_upload_check() {
  if(Params::getParam('vrtUpload') == 'done' && vrt_param('file_required') == 1) {
    $upload_ok = false;
    $file = Params::getFiles('attachment');

    $allowed_extensions = array_map('trim', explode(',', vrt_param('allowed_extensions')));
    $require_validation = vrt_param('require_validation');
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $max_file_size = vrt_param('max_file_size')*1024*1024;
    
    $file_size = $file['size'];

    if($file['error'] == UPLOAD_ERR_OK) {
      if(in_array($extension, $allowed_extensions) || empty($allowed_extensions)) {
        if( $file_size < $max_file_size || $max_file_size == '' ) {
          $upload_ok = true;
        } else {
          osc_add_flash_error_message( __('File is too big and was not uploaded. Maximum file size is:', 'virtual') . ' ' . round($max_file_size/1000) . 'kb' );
        }
      } else {
        osc_add_flash_error_message( __('File extension is not allowed, file was not sent. Only files with following extensions are allowed to be uploaded', 'virtual') . ': ' . 'zip' );
      }
    } else {
      osc_add_flash_error_message( __('File is required, please upload file to this item.', 'virtual') );
    }

    if(!$upload_ok) {
      header('Location:' . osc_item_post_url());
      exit;
    }
  }
}

osc_add_hook('pre_item_add', 'vrt_upload_check'); 



// CHECK IF RUNNING ON DEMO
function vrt_is_demo() {
  if(osc_logged_admin_username() == 'admin') {
    return false;
  } else if(isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'],'mb-themes') !== false || strpos($_SERVER['HTTP_HOST'],'abprofitrade') !== false)) {
    return true;
  } else {
    return false;
  }
}


// INCREASE CUSTOM VIEWS
function vrt_increase_downloads($file_id) {
  ModelVRT::newInstance()->increaseDownloads($file_id);
}


// SMART DATE
function vrt_smart_date( $time ) {
  $time_diff = round(abs(time() - strtotime( $time )) / 60);
  $time_diff_h = floor($time_diff/60);
  $time_diff_d = floor($time_diff/1440);
  $time_diff_w = floor($time_diff/10080);
  $time_diff_m = floor($time_diff/43200);
  $time_diff_y = floor($time_diff/518400);


  if($time_diff < 2) {
    $time_diff_name = __('minute ago', 'virtual');
  } else if ($time_diff < 60) {
    $time_diff_name = sprintf(__('%d minutes ago', 'virtual'), $time_diff);
  } else if ($time_diff < 120) {
    $time_diff_name = sprintf(__('%d hour ago', 'virtual'), $time_diff_h);
  } else if ($time_diff < 1440) {
    $time_diff_name = sprintf(__('%d hours ago', 'virtual'), $time_diff_h);
  } else if ($time_diff < 2880) {
    $time_diff_name = sprintf(__('%d day ago', 'virtual'), $time_diff_d);
  } else if ($time_diff < 10080) {
    $time_diff_name = sprintf(__('%d days ago', 'virtual'), $time_diff_d);
  } else if ($time_diff < 20160) {
    $time_diff_name = sprintf(__('%d week ago', 'virtual'), $time_diff_w);
  } else if ($time_diff < 43200) {
    $time_diff_name = sprintf(__('%d weeks ago', 'virtual'), $time_diff_w);
  } else if ($time_diff < 86400) {
    $time_diff_name = sprintf(__('%d month ago', 'virtual'), $time_diff_m);
  } else if ($time_diff < 518400) {
    $time_diff_name = sprintf(__('%d months ago', 'virtual'), $time_diff_m);
  } else if ($time_diff < 1036800) {
    $time_diff_name = sprintf(__('%d year ago', 'virtual'), $time_diff_y);
  } else {
    $time_diff_name = sprintf(__('%d years ago', 'virtual'), $time_diff_y);
  }

  return $time_diff_name;
}



// CATEGORIES WORK
function vrt_cat_tree($list = array()) {
  if(!is_array($list) || empty($list)) {
    $list = Category::newInstance()->listAll();
  }

  $array = array();
  //$root = Category::newInstance()->findRootCategoriesEnabled();

  foreach($list as $c) {
    if($c['fk_i_parent_id'] <= 0) {
      $array[$c['pk_i_id']] = array('pk_i_id' => $c['pk_i_id'], 's_name' => $c['s_name']);
      $array[$c['pk_i_id']]['sub'] = vrt_cat_sub($list, $c['pk_i_id']);
    }
  }

  return $array;
}

function vrt_cat_sub($list, $parent_id) {
  $array = array();
  //$cats = Category::newInstance()->findSubcategories($id);

  if(count($list) > 0) {
    foreach($list as $c) {
      if($c['fk_i_parent_id'] == $parent_id) {  echo $c['s_name'];
        $array[$c['pk_i_id']] = array('pk_i_id' => $c['pk_i_id'], 's_name' => $c['s_name']);
        $array[$c['pk_i_id']]['sub'] = vrt_cat_sub($list, $c['pk_i_id']);
      }
    }
  }
      
  return $array;
}


function vrt_cat_list($selected = array(), $categories = '', $level = 0) {
  if($categories == '' || $level == 0) {
    $categories = vrt_cat_tree($categories);
  }


  foreach($categories as $c) {
    echo '<option value="' . $c['pk_i_id'] . '" ' . (in_array($c['pk_i_id'], $selected) ? 'selected="selected"' : '') . '>' . str_repeat('-', $level) . ($level > 0 ? ' ' : '') . $c['s_name'] . '</option>';

    if(@count($c['sub']) > 0) {
      vrt_cat_list($selected, $c['sub'], $level + 1);
    }
  }
}


function vrt_list_values_ol($values) {
 if(count($values) > 0 && is_array($values)) {
    foreach($values as $v) {
      ?>

      <li class="mb-val" id="val_<?php echo $v['pk_i_id']; ?>">
        <?php vrt_div_value($v); ?>
      
        <ol>
          <?php 
            if(isset($v['values']) && count($v['values']) > 0) { 
              vrt_list_values_ol($v['values']); 
            }
          ?>
        </ol>
      </li>
    <?php
    }
  }
}


// CORE FUNCTIONS
function vrt_param($name) {
  return osc_get_preference($name, 'plugin-virtual');
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


if( !function_exists('osc_is_contact_page') ) {
  function osc_is_contact_page() {
    $location = Rewrite::newInstance()->get_location();
    $section = Rewrite::newInstance()->get_section();
    if( $location == 'contact' ) {
      return true ;
    }

    return false ;
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

?>