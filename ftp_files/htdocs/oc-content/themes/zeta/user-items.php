<?php Params::setParam('itemsPerPage', 12); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo zet_language_dir(); ?>" mode="<?php echo zet_light_dark_mode(); ?>" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php'); ?>
  <meta name="robots" content="index, follow" />
  <meta name="googlebot" content="index, follow" />
</head>

<body id="user-items" class="body-ua <?php osc_run_hook('body_class'); ?>">
  <?php osc_current_web_theme_path('header.php'); ?>

  <?php
    $current_url =  zet_current_url();

    if(strpos($current_url, 'itemType=active') !== false) {
      $type = 'active';
      $title = __('Active listings', 'zeta');
      $subtitle = __('Listings visible to customers on site.', 'zeta');
    } else if(strpos($current_url, 'itemType=pending_validate') !== false) {
      $type = 'pending_validate';
      $title = __('Validation pending listings', 'zeta');
      $subtitle = __('Listings pending approval. Unapproved listings are not visible on site.', 'zeta');
    } else if(strpos($current_url, 'itemType=expired') !== false) {
      $type = 'expired';
      $title = __('Expired listings', 'zeta');
      $subtitle = __('Listings those has expired and are now not visible in site.', 'zeta');
    } else {
      $type = 'all';
      $title = __('All listings', 'zeta');
      $subtitle = __('Active, pending validation and expired items.', 'zeta');
    }
    
    //$count = Item::newInstance()->countItemTypesByUserID(osc_logged_user_id(), $type);
    //$items = Item::newInstance()->findItemTypesByUserID(osc_logged_user_id(), $type);
  ?>
  
  <div class="container primary">
    <div id="user-menu"><?php zet_user_menu(); ?></div>

    <div id="user-main">
      <?php echo zet_banner('user_account_top'); ?>
      
      <?php osc_run_hook('user_items_top'); ?>
      
      <h1><?php echo $title; ?></h1>
      <h2><?php echo $subtitle; ?></h2>

      <div class="items-box <?php echo $type; ?>">
        <?php if(osc_count_items() > 0) { ?>
          <?php while(osc_has_items()) { ?> 
            <?php $item_extra = zet_item_extra(osc_item_id()); ?>
          
            <div class="item<?php if(osc_item_is_inactive()) { ?> inactive<?php } ?><?php if(osc_item_is_expired()) { ?> inactive<?php } ?> <?php osc_run_hook('highlight_class'); ?>">
              <?php if(osc_images_enabled_at_items()) { ?>
                <a href="<?php echo osc_item_url(); ?>" class="image">
                  <?php if(osc_count_item_resources() > 0) { ?>
                    <img class="<?php echo (zet_is_lazy() ? 'lazy' : ''); ?>" <?php echo (zet_is_lazy_browser() ? 'loading="lazy"' : ''); ?> src="<?php echo (zet_is_lazy() ? zet_get_load_image() : osc_resource_thumbnail_url()); ?>" data-src="<?php echo osc_resource_thumbnail_url(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" />
                  <?php } else { ?>
                    <img class="<?php echo (zet_is_lazy() ? 'lazy' : ''); ?>" <?php echo (zet_is_lazy_browser() ? 'loading="lazy"' : ''); ?> src="<?php echo (zet_is_lazy() ? zet_get_load_image() : zet_get_noimage()); ?>" data-src="<?php echo zet_get_noimage(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" />
                  <?php } ?>
                  
                  <?php if(osc_item_is_premium()) { ?>
                    <div class="label-premium"><?php _e('Premium', 'zeta'); ?></div>
                  <?php } ?>
                  
                  <?php if(osc_item_is_inactive()) { ?>
                    <div class="label-inactive"><?php _e('Pending validation', 'zeta'); ?></div>
                  <?php } else if(osc_item_is_expired()) { ?>
                    <div class="label-expired"><?php _e('Expired listing', 'zeta'); ?></div>
                  <?php } ?>
                  
                  <?php if(!in_array(osc_item_category_id(), zet_extra_fields_hide())) { ?>
                    <?php if(@$item_extra['i_sold'] == 1) { ?>
                      <div class="label-sold"><?php _e('Sold!', 'zeta'); ?></div>
                    <?php } else if(@$item_extra['i_sold'] == 2) { ?>
                      <div class="label-reserved"><?php _e('Reserved!', 'zeta'); ?></div>
                    <?php } ?>
                  <?php } ?>
                  
                  <div class="image-counter"><i class="fas fa-camera"></i> <?php echo osc_count_item_resources(); ?></div>
                </a>
              <?php } ?>
              
              <div class="body">
                <?php if(zet_check_category_price(osc_item_category_id())) { ?>
                  <div class="price"><?php echo osc_item_formated_price(); ?></div>
                <?php } ?>
                
                <div class="top">
                  <span><?php echo osc_format_date(osc_item_pub_date()); ?></span>
                  <span><?php echo osc_item_category(); ?></span>
                  <span><?php echo (zet_user_item_location() <> '' ? zet_user_item_location() : __('Location not set', 'zeta')); ?></span>

                  <?php if(!in_array(osc_item_category_id(), zet_extra_fields_hide())) { ?>
                    <?php if(zet_get_simple_name($item_extra['i_condition'], 'condition', false) <> '') { ?>
                      <span><?php echo zet_get_simple_name($item_extra['i_condition'], 'condition', false); ?></span>
                    <?php } ?>

                    <?php if(zet_get_simple_name($item_extra['i_transaction'], 'transaction', false) <> '') { ?>
                      <span><?php echo zet_get_simple_name($item_extra['i_transaction'], 'transaction', false); ?></span>
                    <?php } ?>          
                  <?php } ?>
                </div>
                
                <a class="title" href="<?php echo osc_item_url(); ?>"><?php echo osc_item_title(); ?></a>

                <div class="description"><?php echo osc_highlight(osc_item_description(), 240); ?></div>
                
                <div class="buttons">
                  <?php if(osc_item_can_renew()) { ?>
                    <a class="renew" href="<?php echo osc_item_renew_url();?>" ><?php _e('Renew', 'zeta'); ?></a>
                    <span class="delim">/</span>
                  <?php } ?>
          
                  <?php if(osc_item_is_active() && osc_can_deactivate_items()) {?>
                    <a class="deactivate" href="<?php echo osc_item_deactivate_url();?>" ><?php _e('Deactivate', 'zeta'); ?></a>
                    <span class="delim">/</span>
                  <?php } ?>
                  
                  <?php if(osc_item_is_inactive()) { ?>
                    <?php if((function_exists('iv_add_item') && osc_get_preference('enable','plugin-item_validation') <> 1) || !function_exists('iv_add_item')) { ?>
                      <a class="activate" target="_blank" href="<?php echo osc_item_activate_url(); ?>"><?php _e('Validate', 'zeta'); ?></a>
                      <span class="delim">/</span>
                    <?php } ?>
                  <?php } else { ?>
                    <?php if(!in_array(osc_item_category_id(), zet_extra_fields_hide())) { ?>
                      <a class="sold round2 tr1" href="<?php echo zet_item_sold_reserved_url('sold', $item_extra); ?>"><?php echo (@$item_extra['i_sold'] == 1 ? __('Unmark sold', 'zeta') : __('Mark as sold', 'zeta')); ?></a>
                      <span class="delim">/</span>

                      <a class="reserved" href="<?php echo zet_item_sold_reserved_url('reserved', $item_extra); ?>"><?php echo (@$item_extra['i_sold'] == 2 ? __('Unmark reserved', 'zeta') : __('Mark as reserved', 'zeta')); ?></a>
                      <span class="delim">/</span>
                    <?php } ?>                  
                  <?php } ?>
                  
                  <a class="edit" target="_blank" href="<?php echo osc_item_edit_url(); ?>" rel="nofollow"><?php _e('Edit', 'zeta'); ?></a>
                  <span class="delim">/</span>

                  <?php if(function_exists('republish_link_raw') && republish_link_raw(osc_item_id())) { ?>
                    <a class="republish" href="<?php echo republish_link_raw(osc_item_id()); ?>" rel="nofollow"><?php _e('Republish', 'zeta'); ?></a>
                    <span class="delim">/</span>
                  <?php } ?>

                  <a class="delete" onclick="return confirm('<?php echo osc_esc_js(__('Are you sure you want to delete this listing? This action cannot be undone.', 'zeta')); ?>')" href="<?php echo osc_item_delete_url(); ?>"><i class="fas fa-trash"></i> <?php _e('Delete', 'zeta'); ?></a>
                </div>
              </div>
            </div>
          <?php } ?>

          <div class="paginate">
            <?php echo zet_fix_arrow(osc_pagination_items()); ?>
          </div>
        <?php } else { ?>
          <div class="empty"><?php _e('No listings found', 'zeta'); ?></div>
        <?php } ?>
      </div>
      
      <?php osc_run_hook('user_items_bottom'); ?>
    </div>
  </div>

  <?php osc_current_web_theme_path('footer.php'); ?>
</body>
</html>