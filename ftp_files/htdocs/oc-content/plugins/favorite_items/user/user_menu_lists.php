<?php
  if(osc_is_web_user_logged_in()) {
    $user_id = osc_logged_user_id();
  } else {
    $user_id = mb_get_cookie('fi_user_id');
  }


  // ADD NEW LIST
  if(Params::getParam('add_new_list') == 1) {
    if(Params::getParam('fi_new_list_name') == '') {
      message_error(__('List name cannot be empty. Please enter name.', 'favorite_items'));
    } else {
      if(Params::getParam('fi_new_list_current') == 'on') {     // if new list is set to be active, deactive all others
        ModelFI::newInstance()->updateAllListCurrentByUserId( $user_id, 0 );
      }

      if(Params::getParam('edit_action') == 1) {
        ModelFI::newInstance()->updateFavoriteList( Params::getParam('edit_list_id'), Params::getParam('fi_new_list_name'), Params::getParam('fi_new_list_current') == 'on' ? 1 : 0, $user_id, 1, Params::getParam('fi_new_list_notification') == 'on' ? 1 : 0 );
        message_ok(__('List has been updated', 'favorite_items'));
      } else {
        $list_check_current = ModelFI::newInstance()->getCurrentFavoriteListByUserId( $user_id );

        if(count($list_check_current) == 0) {
          ModelFI::newInstance()->addFavoriteList( Params::getParam('fi_new_list_name'), 1, $user_id, 1, Params::getParam('fi_new_list_notification') == 'on' ? 1 : 0 );
          message_ok(__('New list has been created and set as active, because you do not have any active favorite list', 'favorite_items'));
        } else {
          ModelFI::newInstance()->addFavoriteList( Params::getParam('fi_new_list_name'), Params::getParam('fi_new_list_current') == 'on' ? 1 : 0, $user_id, 1, Params::getParam('fi_new_list_notification') == 'on' ? 1 : 0 );
          message_ok(__('New list has been created', 'favorite_items'));
        }

      }
    }
  }


  // CURRENT ACTIVE LIST CHANGE
  if(Params::getParam('list-id') > 0 && Params::getParam('current-update') <> '' && Params::getParam('current-update') <> 0) {
    ModelFI::newInstance()->updateAllListCurrentByUserId( $user_id, 0 );
    ModelFI::newInstance()->updateListCurrentByListId(Params::getParam('list-id'), 1);
  }


  // NOTIFICATION LIST CHANGE
  if(Params::getParam('list-id') > 0 && Params::getParam('notification-update') <> '' && Params::getParam('notification-update') <> 0) {
    ModelFI::newInstance()->updateListNotificationByListId(Params::getParam('list-id'), Params::getParam('notification-update') - 1);
  }


  // LIST REMOVE
  $list_remove_id = '';
  if(Params::getParam('list-id') > 0 && Params::getParam('list-remove') == 1) {
    $list_remove_id = Params::getParam('list-id');

    ModelFI::newInstance()->deleteFavoriteListById( Params::getParam('list-id') );
    message_ok(__('Favorite list', 'favorite_items') . ' <strong>#' . Params::getParam('list-id') . '</strong> ' . __('has been successfully removed', 'favorite_items'));

    
    // CHECK IF THERE IS SOME LIST SET AS CURRENT
    $list_current = ModelFI::newInstance()->getCurrentFavoriteListByUserId( $user_id );

    if(!isset($list_current['list_id']) or $list_current['list_id'] == '') {
      $list_to_set = ModelFI::newInstance()->getLastFavoriteListByUserId( $user_id );

      if(isset($list_to_set['list_id']) and $list_to_set['list_id'] <> '' and $list_to_set['list_id'] > 0) {
        ModelFI::newInstance()->updateListCurrentByListId( $list_to_set['list_id'], 1 );
        message_ok(__('List', 'favorite_items') . ' <strong>' . $list_to_set['list_name'] . '</strong> (#' . $list_to_set['list_id'] . ') ' . __('has been set to your current favorite list', 'favorite_items'));
      }
    }
  }
?>

<div class="content user_account fi_user_menu_wrap">
  <h2 class="r1"><?php _e('Favorite listings', 'favorite_items'); ?></h2>

  <div id="fi_user_new_list">
    <form name="fi_new_list" action="<?php echo osc_route_url('favorite-lists'); ?>" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="add_new_list" value="1" />
      <input type="hidden" name="edit_action" value="" />
      <input type="hidden" name="edit_list_id" value="" />

      <strong class="fi_title" style="display:none;"><?php _e('Create a new list', 'favorite_items'); ?></strong>

      <input type="text" class="fi_new_name" name="fi_new_list_name" placeholder="<?php echo osc_esc_html(__('New list name', 'favorite_items')); ?>"/>

      <div class="fi_check first">
        <span>
          <input type="checkbox" class="fi_checkbox" name="fi_new_list_current" id="fi_new_list_current"/>
          <label for="fi_new_list_current"><?php _e('set as active', 'favorite_items'); ?></label>
        </span>
      </div>

      <div class="fi_check">
        <span>
          <input type="checkbox" class="fi_checkbox" name="fi_new_list_notification" id="fi_new_list_notification"/>
          <label for="fi_new_list_notification"><?php _e('notify me', 'favorite_items'); ?></label>
        </span>
      </div>

      <button class="btn mbBg3" type="submit"><?php _e('Submit', 'favorite_items'); ?></button>
    </form>
  </div>


  <h2 class="r2"><?php _e('Your favorite lists', 'favorite_items'); ?></h2>

  <div id="fi_user_lists">
    <?php $lists = ModelFI::newInstance()->getAllFavoriteListsByUserId( $user_id ); ?>

    <?php if(count($lists) <= 0) { ?>
      <div class="fi_empty"><?php _e('You did not created any favorite list', 'favorite_items'); ?></div>
    <?php } else { ?> 
      <div class="fi_list fi_head">
        <div class="fi_name"><?php _e('List Name', 'favorite_items'); ?></div>
        <div class="fi_count"><?php _e('# Items', 'favorite_items'); ?></div>
        <div class="fi_current"><?php _e('Current', 'favorite_items'); ?> <sup>*</sup></div>
        <div class="fi_notification"><?php _e('Notify', 'favorite_items'); ?> <sup>**</sup></div>
        <div class="fi_user_list_remove"><?php _e('Remove', 'favorite_items'); ?></div>
      </div>


      <?php foreach($lists as $l) { ?>
        <?php $count = count(ModelFI::newInstance()->getActiveFavoriteItemsByListId( $l['list_id'] )); ?>

        <div class="fi_list fi_list_<?php echo $l['list_id']; ?>" rel="<?php echo $l['list_id']; ?>">
          <div class="fi_name"><a href="<?php echo osc_route_url('favorite-lists', array('list-id' => $l['list_id'])); ?>"><?php echo $l['list_name']; ?></a> (#<?php echo $l['list_id']; ?>) <span class="fi_list_edit" rel="<?php echo $l['list_id']; ?>">[<?php _e('edit', 'favorite_items'); ?>]</span></div>
          <div class="fi_count"><?php echo $count; ?> <?php echo $count == 1 ? __('listing', 'favorite_items') : __('listings', 'favorite_items'); ?></div>
          <div class="fi_current<?php echo $l['current'] == 1 ? ' fi_active' : ''; ?>"><a href="<?php echo osc_route_url('favorite-lists', array('list-id' => $l['list_id'], 'current-update' => 1)); ?>"><i class="fa fa-check"></i></a></div>
          <div class="fi_notification<?php echo $l['notification'] == 1 ? ' fi_active' : ''; ?>"><a href="<?php echo osc_route_url('favorite-lists', array('list-id' => $l['list_id'], 'notification-update' => ($l['notification']*(-1) + 2))); ?>"><i class="fa fa-check"></i></a></div>
          <div class="fi_user_list_remove"><a href="<?php echo osc_route_url('favorite-lists', array('list-id' => $l['list_id'], 'list-remove' => 1)); ?>" onclick="return confirm('<?php echo osc_esc_js(__('Are you sure you want to continue? This action cannot be undone.', 'favorite_items')); ?>')"><i class="fa fa-trash-o"></i></a></div>
        </div>
      <?php } ?>
    <?php } ?>

    <div class="fi_explain">
      <div><sup>*</sup> <?php _e('Every user can have just 1 favorite list active/current. It only means, that if you add item to favorite list, it is added to active one. You can set favorite list to be active if you are going to work with it (add/remove items from it).', 'favorite_items'); ?></div>
      <div><sup>**</sup> <?php _e('If notifications are marked, you will be notified by email in case: Price on listing you has favorited has changed or Listing you favorited has been removed. It does not matter if favorite list is current/active or not.', 'favorite_items'); ?></div>
    </div>
  </div>

<?php
  $list_id = Params::getParam('list-id');

  if($list_id == '' or $list_id == 0 or $list_remove_id > 0) {
    $list = ModelFI::newInstance()->getCurrentFavoriteListByUserId( $user_id );
    $list_id = (isset($list['list_id']) ? $list['list_id'] : 0);
  } 


  // SEARCH ITEMS IN LIST AND CREATE ITEM ARRAY
  $perPage = (fi_param('per_page') > 0 ? fi_param('per_page') : 8);


  if(Params::getParam('iPage') != '' && Params::getParam('iPage') != 0) {
    $iPage = Params::getParam('iPage') - 1;
  } else {
    $iPage = 0;
  }
 
  $iSearch = new Search();
  $iSearch->addConditions(sprintf("%st_favorite_items.list_id = %d", DB_TABLE_PREFIX, $list_id));
  $iSearch->addConditions(sprintf("%st_favorite_items.item_id = %st_item.pk_i_id", DB_TABLE_PREFIX, DB_TABLE_PREFIX));
  $iSearch->addTable(sprintf("%st_favorite_items", DB_TABLE_PREFIX));
  $iSearch->page($iPage, $perPage);

  $list_items = $iSearch->doSearch();

  $totalItems = $iSearch->count();
  $totalPages = ceil($totalItems / $perPage);

  View::newInstance()->_exportVariableToView('search_total_pages', $totalPages);
  View::newInstance()->_exportVariableToView('search_page', $iPage);


  // EXPORT FAVORITE ITEMS TO VARIABLE
  GLOBAL $global_items;
  $global_items = View::newInstance()->_get('items');                 //save existing item array
  View::newInstance()->_exportVariableToView('items', $list_items);    //exporting our searched item array
    
  require_once 'user_menu_items.php';

  GLOBAL $global_items;                                                //calling stored item array
  View::newInstance()->_exportVariableToView('items', $global_items);  //restore original item array
?>


</div>							