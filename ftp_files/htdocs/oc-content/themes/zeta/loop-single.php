<?php 
  $item_extra = zet_item_extra(osc_item_id(), osc_item()); 
  $is_day_offer = (osc_item_id() == zet_param('day_offer_id') ? true : false);
  $card_class = trim((isset($c) ? ' o'. $c : '') . (osc_item_is_premium() ? ' is-premium' : '') . ($is_day_offer ? ' day-offer' : '') . (@$class != '' ? ' ' . $class : '') . (@$item_extra['i_sold'] == 1 ? ' st-sold' : '') . (@$item_extra['i_sold'] == 2 ? ' st-reserved' : ''));
  $phone_data = zet_get_item_phone();
  $email_data = zet_get_item_email();
?>

<div class="simple-prod<?php echo $card_class <> '' ? ' ' . $card_class : ''; ?> <?php osc_run_hook('highlight_class'); ?>">
  <div class="simple-wrap" title="<?php echo osc_esc_html(@$item_extra['i_sold'] == 1 ? __('Sold', 'zeta') : ''); ?>">
    <?php osc_run_hook('item_loop_top'); ?>
    
    <div class="top-wrap">
      <?php if(osc_item_user_id() > 0 && zet_has_profile_picture(osc_item_user_id())) { ?>
        <a href="<?php echo zet_user_public_profile_url(osc_item_user_id()); ?>" class="user-image">
          <img class="usr <?php echo (zet_is_lazy() ? 'lazy' : ''); ?>" <?php echo (zet_is_lazy_browser() ? 'loading="lazy"' : ''); ?> src="<?php echo (zet_is_lazy() ? zet_get_load_image() : zet_profile_picture(osc_item_user_id(), 'small')); ?>" data-src="<?php echo zet_profile_picture(osc_item_user_id(), 'small'); ?>" alt="<?php echo osc_esc_html(osc_item_contact_name()); ?>"/>

          <?php if(zet_user_is_company(osc_item_user_id())) { ?>
            <span class="business isGrid" title="<?php echo osc_esc_html(__('Professional seller', 'zeta')); ?>"><?php _e('Pro', 'zeta'); ?></span>
          <?php } ?>
          
          <?php if(zet_user_is_online(osc_item_user_id())) { ?>
            <div class="online" title="<?php echo osc_esc_html(__('User is online', 'zeta')); ?>"></div>
          <?php } ?>
        </a>
        
      <?php } else { ?>
        <a href="<?php echo osc_item_url(); ?>" class="user-image">
          <img class="usr <?php echo (zet_is_lazy() ? 'lazy' : ''); ?>" <?php echo (zet_is_lazy_browser() ? 'loading="lazy"' : ''); ?> src="<?php echo (zet_is_lazy() ? zet_get_load_image() : osc_current_web_theme_url('images/default-user-image.png')); ?>" data-src="<?php echo osc_current_web_theme_url('images/default-user-image.png'); ?>" alt="<?php echo osc_esc_html(osc_item_contact_name()); ?>"/>
        </a>
      <?php } ?>
      
      <strong class="cn"><?php echo osc_item_contact_name(); ?></strong>
      <span class="dt isGrid">
        <?php if($is_day_offer) { ?>
          <i class="fas fa-bolt" title="<?php echo osc_esc_html(__('Offer of the day', 'zeta')); ?>"></i>
          
        <?php } else if(osc_item_is_premium()) { ?>
          <i class="fas fa-bookmark" title="<?php echo osc_esc_html(__('Premium', 'zeta')); ?>"></i>

        <?php } else if(isset($item_extra['i_sold']) && $item_extra['i_sold'] == 2) { ?>
          <i class="fas fa-history" title="<?php echo osc_esc_html(__('Reserved', 'zeta')); ?>"></i>

        <?php } else if(isset($item_extra['i_sold']) && $item_extra['i_sold'] == 1) { ?>
          <i class="fas fa-gavel" title="<?php echo osc_esc_html(__('Sold', 'zeta')); ?>"></i>

        <?php } else if($is_day_offer) { ?>

        <?php } ?>
        
        <?php echo zet_smart_date2(osc_item_pub_date(), true); ?>
      </span>
    
    </div>
    
    <div class="img-wrap<?php if(osc_count_item_resources() <= 0) { ?> no-image<?php } ?>">
      <a class="img" href="<?php echo osc_item_url(); ?>">
        <?php if(osc_count_item_resources() > 0) { ?>
          <img class="<?php echo (zet_is_lazy() ? 'lazy' : ''); ?>" <?php echo (zet_is_lazy_browser() ? 'loading="lazy"' : ''); ?> src="<?php echo (zet_is_lazy() ? zet_get_load_image() : osc_resource_thumbnail_url()); ?>" data-src="<?php echo osc_resource_thumbnail_url(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" />
        <?php } else { ?>
          <img class="<?php echo (zet_is_lazy() ? 'lazy' : ''); ?>" <?php echo (zet_is_lazy_browser() ? 'loading="lazy"' : ''); ?> src="<?php echo (zet_is_lazy() ? zet_get_load_image() : zet_get_noimage()); ?>" data-src="<?php echo zet_get_noimage(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" />
        <?php } ?>
      </a>
      
      <div class="marks">
        <?php if(osc_item_is_premium()) { ?>
          <span class="prem"><?php _e('Premium', 'zeta'); ?></span>
        <?php } ?>
        
        <?php if($is_day_offer) { ?>
          <span class="oday"><?php _e('Offer of the day', 'zeta'); ?></span>
        <?php } ?>
        
        <?php if(isset($item_extra['i_sold']) && $item_extra['i_sold'] == 1) { ?>
          <span class="sold"><?php _e('Sold', 'zeta'); ?></span>
        <?php } else if(isset($item_extra['i_sold']) && $item_extra['i_sold'] == 2) { ?>
          <span class="resr"><?php _e('Reserved', 'zeta'); ?></span>
        <?php } ?>
      </div>
    </div>


    <div class="data">
      <div class="info isList">
        <?php if(osc_item_user_id() > 0 && zet_user_is_company(osc_item_user_id())) { ?>
          <span class="business" title="<?php echo osc_esc_html(__('Professional seller', 'zeta')); ?>"><?php _e('Pro', 'zeta'); ?></span>
        <?php } ?>
        
        <a class="cat" href="<?php echo osc_search_url(array('page' => 'search', 'sCategory' => osc_item_category_id())); ?>"><?php echo osc_item_category(); ?></a>
  
        <?php if(isset(osc_item()['fk_i_city_id']) && osc_item()['fk_i_city_id'] > 0) { ?>
          <a class="cit" href="<?php echo osc_search_url(array('page' => 'search', 'sCity' => osc_item()['fk_i_city_id'])); ?>"><?php echo osc_item_city(); ?></a>
        <?php } ?>

        <?php if(isset(osc_item()['fk_i_region_id']) && osc_item()['fk_i_region_id'] > 0) { ?>
          <a class="reg" href="<?php echo osc_search_url(array('page' => 'search', 'sRegion' => osc_item()['fk_i_region_id'])); ?>"><?php echo osc_item_region(); ?></a>
        <?php } ?>
        
        <span class="dat"><?php echo zet_smart_date2(osc_item_pub_date(), true); ?></span>
      </div>
      

      <a class="title" href="<?php echo osc_item_url(); ?>"><?php echo osc_highlight(osc_item_title(), 100); ?></a>

      <?php osc_run_hook('item_loop_title'); ?>

      <?php if(zet_check_category_price(osc_item_category_id())) { ?>
        <div class="price isGrid"><span><?php echo osc_item_formated_price(); ?></span></div>
      <?php } ?>

      <a class="title2" href="<?php echo osc_item_url(); ?>"><?php echo osc_highlight(osc_item_title(), 100); ?></a>

      <div class="location isGrid"><?php echo zet_item_location(); ?></div>
      
      <div class="description"><?php echo osc_highlight(strip_tags(osc_item_description()), 360); ?></div>
      
      <?php osc_run_hook('item_loop_description'); ?>

      <div class="extra">
        <span class="e1 isGrid"><?php echo zet_smart_date2(osc_item_pub_date()); ?></span>
        
        <?php if(!in_array(osc_item_category_id(), zet_extra_fields_hide())) { ?>
          <?php if(zet_get_simple_name($item_extra['i_condition'], 'condition', false) <> '') { ?>
            <span class="e2"><?php echo zet_get_simple_name($item_extra['i_condition'], 'condition', false); ?></span>
          <?php } ?>

          <?php if(zet_get_simple_name($item_extra['i_transaction'], 'transaction', false) <> '') { ?>
            <span class="e3"><?php echo zet_get_simple_name($item_extra['i_transaction'], 'transaction', false); ?></span>
          <?php } ?>          
        <?php } ?>

        <span class="e4 isGrid"><?php echo osc_item_category(); ?></span>

        <?php if(isset($item_extra['i_sold']) && $item_extra['i_sold'] == 1) { ?>
          <span class="e5 isList sold"><?php _e('Sold', 'zeta'); ?></span>
        <?php } else if(isset($item_extra['i_sold']) && $item_extra['i_sold'] == 2) { ?>
          <span class="e5 isList reserved"><?php _e('Reserved', 'zeta'); ?></span>
        <?php } ?>

        <?php if(osc_item_city_area() != '') { ?>
          <span class="e5 isList"><?php echo osc_item_city_area(); ?></span>
        <?php } ?>
        
        <?php if(osc_item_address() != '' || osc_item_zip() != '') { ?>
          <span class="e6 isList"><?php echo implode(' - ', array_filter(array(osc_item_zip(), osc_item_address()))); ?></span>
        <?php } ?>

        <span class="e7"><?php echo (osc_item_views() == 1 ? __('1 view', 'zeta') : sprintf(__('%s views', 'zeta'), osc_item_views())); ?></span>
      </div>
      
      <?php if(zet_check_category_price(osc_item_category_id())) { ?>
        <div class="price standalone isList"><span><?php echo osc_item_formated_price(); ?></span></div>
      <?php } ?>
      
      <div class="favorite-block1"><?php zet_make_favorite(); ?></div>
    </div>
    
    <?php osc_run_hook('item_loop_bottom'); ?>
  </div>
</div>