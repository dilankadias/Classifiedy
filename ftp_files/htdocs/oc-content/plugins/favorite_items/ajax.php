<?php
if(!defined('ABS_PATH')) {
  define('ABS_PATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');
}

require_once ABS_PATH . 'oc-load.php';


$item_id = Params::getParam('item_id');                      // FAVORITE BUTTON PRESSED
$item_remove_id = Params::getParam('item_remove_id');        // REMOVE ITEM FROM LIST BUTTON PRESSED
$quick_message = (fi_param('quick_message') <> '' ? fi_param('quick_message') : 1); 
$list_max = (fi_param('max_per_list') > 0 ? fi_param('max_per_list') : 24); 
$is_save = Params::getParam('isSave');


// ITEM_ID RECIEVED TO WORK WITH AJAX
if($item_id <> '' and $item_id > 0) {
  $return = array();
  $return['max_reached'] = 0;

  $item_complete = Item::newInstance()->findByPrimaryKey($item_id);
  $item_resource = ItemResource::newInstance()->getResource($item_id);

  if(osc_is_web_user_logged_in()) {
    $user_id = osc_logged_user_id();
  } else {
    $user_id = mb_get_cookie('fi_user_id');
  }

  $favorite_list = ModelFI::newInstance()->getCurrentFavoriteListByUserId( $user_id );
  $list_id = isset($favorite_list['list_id']) ? $favorite_list['list_id'] : '';

  if($list_id == '') {
    // NO LIST HAS BEEN CREATED FOR THIS USER, CREATE ONE    
    ModelFI::newInstance()->addFavoriteList( __('Favorite List', 'favorite_items'), 1, $user_id, osc_is_web_user_logged_in() ? 1 : 0, 0 );
    $list = ModelFI::newInstance()->getCurrentFavoriteListByUserId( $user_id );

    // ADD ITEM TO LIST
    if(isset($list['list_id'])) {
      ModelFI::newInstance()->addFavoriteItem( $list['list_id'], $item_id );
    }

    if($is_save == 1) {
      if(osc_current_web_theme() == 'starter') {
        $return['title'] = __('Offer in watch list', 'favorite_items');
      } else {
        $return['title'] = __('Saved', 'favorite_items');
      }
    } else {
      $return['title'] = __('Favorited', 'favorite_items');
    }

    $return['message'] = __('Listing', 'favorite_items') . ' <strong>' . $item_complete['s_title'] . '</strong> ' . __('has been marked as your favorite', 'favorite_items');
    $return['is_favorite'] = 1;

  } else {
    $favorite_item = ModelFI::newInstance()->getFavoriteItems( $list_id, $item_id );

    if(!isset($favorite_item['record_id']) or $favorite_item['record_id'] == '') {

      // CHECK IF THERE IS ALREADY NOT MAXIMUM ITEMS IN LIST
      $items_to_count = ModelFI::newInstance()->getFavoriteItemsByListId( $list_id );

      if(count($items_to_count) < $list_max) {
        ModelFI::newInstance()->addFavoriteItem( $list_id, $item_id );                           // ITEM WAS NOT FAVORITE

        if($is_save == 1) {
          if(osc_current_web_theme() == 'careerjob') {
            $return['title'] = __('Job offer watched', 'favorite_items');
            $return['message'] = __('Job offer', 'favorite_items') . ' <strong>' . $item_complete['s_title'] . '</strong> ' . __('has been added to watch list', 'favorite_items');
          } else {
            $return['title'] = __('Saved', 'favorite_items');
            $return['message'] = __('Listing', 'favorite_items') . ' <strong>' . $item_complete['s_title'] . '</strong> ' . __('has been saved', 'favorite_items');
          }
        } else {
          $return['title'] = __('Favorited', 'favorite_items');
          $return['message'] = __('Listing', 'favorite_items') . ' <strong>' . $item_complete['s_title'] . '</strong> ' . __('has been marked as your favorite', 'favorite_items');
        }

        $return['is_favorite'] = 1;
      } else {
        $return['message'] = __('Your favorite list already contains maximum allowed listings. You can have maximum', 'favorite_items') . ' <strong>' . $list_max . ' ' . ($list_max == 1 ? __('listing', 'favorite_items') : __('listings', 'favorite_items')) . '</strong> ' . __('in each favorite list.', 'favorite_items');
        $return['is_favorite'] = 0;
        $return['max_reached'] = 1;
      }
    } else {
      if(isset($favorite_item['record_id'])) {
        ModelFI::newInstance()->deleteFavoriteItemByRecordId( $favorite_item['record_id'] );     // ITEM WAS FAVORITE
      }

      if($is_save == 1) {
        if(osc_current_web_theme() == 'careerjob') {
          $return['title'] = __('Watch job offer', 'favorite_items');
          $return['message'] = __('Job offer', 'favorite_items') . ' <strong>' . $item_complete['s_title']  . '</strong> ' . __('is not watched anymore', 'favorite_items');
        } else {
          $return['title'] = __('Save', 'favorite_items');
          $return['message'] = __('Listing', 'favorite_items') . ' <strong>' . $item_complete['s_title']  . '</strong> ' . __('is not saved anymore', 'favorite_items');
        }
      } else {
        $return['title'] = __('Make favorite', 'favorite_items');
        $return['message'] = __('Listing', 'favorite_items') . ' <strong>' . $item_complete['s_title']  . '</strong> ' . __('is not your favorite anymore', 'favorite_items');
      }

      $return['is_favorite'] = 0;
    }
  }


  // RETURN DATA IN JSON FORMAT

  $return['allow_message'] = $quick_message;
  $return['item_title'] = isset($item_complete['s_title']) ? $item_complete['s_title'] : '';
  $return['item_url'] = osc_item_url_ns($item_id);


  // item price
  if(isset($item_complete['i_price']) and isset($item_complete['fk_c_currency_code'])) {
    $return['item_price'] = fi_price_format($item_complete['i_price'], $item_complete['fk_c_currency_code']);
  }
  
  // item img
  if(isset($item_resource['s_path']) and isset($item_resource['pk_i_id']) and isset($item_resource['s_extension']) and $item_resource['s_path'] <> '') {
    $return['item_img'] = osc_apply_filter('resource_path', osc_base_url() . $item_resource['s_path']) . $item_resource['pk_i_id'] . '_thumbnail.' . $item_resource['s_extension'];
  } else {
    $return['item_img'] = osc_base_url() . 'oc-content/plugins/favorite_items/img/no-image.png';
  }

  $return['json'] = json_encode($return);
  echo json_encode($return, JSON_PRETTY_PRINT);
  exit;
}


// ITEM_ID RECIEVED TO WORK WITH AJAX
if($item_remove_id <> '' && $item_remove_id > 0) {
  $return = array();

  if(osc_is_web_user_logged_in()) {
    $user_id = osc_logged_user_id();
  } else {
    $user_id = mb_get_cookie('fi_user_id');
  }


  $favorite_list = ModelFI::newInstance()->getCurrentFavoriteListByUserId( $user_id );

  if(isset($favorite_list['list_id'])) {
    $favorite_item = ModelFI::newInstance()->getFavoriteItems( $favorite_list['list_id'], $item_remove_id );
  }

  if(isset($favorite_item['record_id'])) {
    ModelFI::newInstance()->deleteFavoriteItemByRecordId( $favorite_item['record_id'] );
  }

  $return['message'] = __('Listing has been removed from your favorite list', 'favorite_items');


  // RETURN DATA IN JSON FORMAT
  $return['allow_message'] = $quick_message;
  $return['json'] = json_encode($return);
  echo json_encode($return, JSON_PRETTY_PRINT);
  exit;
}
?>