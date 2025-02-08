<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo zet_language_dir(); ?>" mode="<?php echo zet_light_dark_mode(); ?>" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php'); ?>
  <meta name="robots" content="index, follow" />
  <meta name="googlebot" content="index, follow" />
  <?php
    $itemviewer = (Params::getParam('itemviewer') == 1 ? 1 : 0);
    $item_extra = zet_item_extra(osc_item_id());

    $location_array = array_filter(array(osc_item_city(), osc_item_region(), osc_item_country_code()));
    $location = implode(', ', $location_array);

    $location_full_array = array_filter(array(osc_item_address(), osc_item_city_area(), implode(' ', array_filter(array(osc_item_zip(), osc_item_city()))), osc_item_region(), osc_item_country()));
    $location_full = implode(', ', $location_full_array);

    $is_company = false;
    
    if(osc_item_user_id() <> 0) {
      $item_user = osc_get_user_row(osc_item_user_id());
      View::newInstance()->_exportVariableToView('user', $item_user);
      $user_item_count = $item_user['i_items'];
      
      if($item_user['b_company'] == 1) {
        $is_company = true;
      }
    } else {
      $item_user = false;
      $user_item_count = Item::newInstance()->countItemTypesByEmail(osc_item_contact_email(), 'active');
    }
    
    $contact_name = (osc_item_contact_name() <> '' ? osc_item_contact_name() : __('Anonymous', 'zeta'));

    $item_user_location_array = array_filter(array(osc_user_address(), osc_user_zip(), osc_user_city_area(), osc_user_city(), osc_user_region(), osc_user_country()));
    $item_user_location = implode(', ', $item_user_location_array);


    $reg_type = '';
    $last_online = '';

    if($item_user && $item_user['dt_reg_date'] <> '') { 
      $reg_type = sprintf(__('Registered for %s', 'zeta'), zet_smart_date2($item_user['dt_reg_date']));
    } else if($item_user) { 
      $reg_type = __('Registered user', 'zeta');
    } else {
      $reg_type = __('Unregistered user', 'zeta');
    }

    if($item_user && @$item_user['dt_access_date'] != '') {
      $last_online = sprintf(__('Last online %s', 'zeta'), zet_smart_date($item_user['dt_access_date']));
    }
    
    
    //$user_about = nl2br(strip_tags(osc_user_info()));

    $phone_data = zet_get_item_phone();
    $email_data = zet_get_item_email();
    $user_phone_mobile_data = zet_get_phone(isset($item_user['s_phone_mobile']) ? $item_user['s_phone_mobile'] : '');
    $user_phone_land_data = zet_get_phone(isset($item_user['s_phone_land']) ? $item_user['s_phone_land'] : '');

    $has_cf = false;
    while(osc_has_item_meta()) {
      if(osc_item_meta_value() != '') {
        $has_cf = true;
        break;
      }
    }

    View::newInstance()->_reset('metafields');
    
    $make_offer_enabled = false;

    if(function_exists('mo_ajax_url')) {
      $history = osc_get_preference('history', 'plugin-make_offer');
      $category = osc_get_preference('category', 'plugin-make_offer');
      $category_array = explode(',', $category);

      $root = Category::newInstance()->findRootCategory(osc_item_category_id());
      $root_id = $root['pk_i_id'];

      if((in_array($root_id, $category_array) || trim($category) == '') && (osc_item_price() > 0 || osc_item_price() !== 0)) {
        $setting = ModelMO::newInstance()->getOfferSettingByItemId(osc_item_id());

        if((isset($setting['i_enabled']) && $setting['i_enabled'] == 1) || ((!isset($setting['i_enabled']) || $setting['i_enabled'] == '') && $history == 1)) {
          $make_offer_enabled = true;
        }
      }
    }
    
    $item_search_url = osc_search_url(array('page' => 'search', 'sCategory' => osc_item_category_id(), 'sCountry' => osc_item_country_code(), 'sRegion' => osc_item_region_id(), 'sCity' => osc_item_city_id()));

    if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '' && strpos($_SERVER['HTTP_REFERER'], osc_base_url()) !== false) {
      $item_search_url = false;
    }
    
    $dimNormal = explode('x', osc_get_preference('dimNormal', 'osclass')); 
    
    if(zet_param('gallery_ratio') == '') {
      $aspect_ratio = round($dimNormal[0]/$dimNormal[1], 3);
    } else if(zet_param('gallery_ratio') == '1:1') {
      $aspect_ratio = 1/1;
    } else if(zet_param('gallery_ratio') == '4:3') {
      $aspect_ratio = 4/3;
    } else if(zet_param('gallery_ratio') == '1:1') {
      $aspect_ratio = 16/9;
    } else if(zet_param('gallery_ratio') == '2:1') {
      $aspect_ratio = 2/1;
    }
    
    $gallery_padding_top = round(1/($aspect_ratio)*100, 2);
    
    osc_reset_resources();
    osc_get_item_resources();
    $resource_url = osc_resource_url(); 
    
    $breadcrumb = osc_breadcrumb('>', false);
    $breadcrumb = str_replace('<span itemprop="title">' . osc_page_title() . '</span>', '<span itemprop="title" class="home"><span>' . __('Home', 'zeta') . '</span></span>', $breadcrumb);
    $breadcrumb = str_replace('<span itemprop="name">' . osc_page_title() . '</span>', '<span itemprop="name" class="home"><span>' . __('Home', 'zeta') . '</span></span>', $breadcrumb);
  ?>

  <!-- FACEBOOK OPEN GRAPH TAGS -->
  <meta property="og:title" content="<?php echo osc_esc_html(osc_item_title()); ?>" />
  <?php if(osc_count_item_resources() > 0) { ?><meta property="og:image" content="<?php echo $resource_url; ?>" /><?php } ?>
  <meta property="og:site_name" content="<?php echo osc_esc_html(osc_page_title()); ?>"/>
  <meta property="og:url" content="<?php echo osc_item_url(); ?>" />
  <meta property="og:description" content="<?php echo osc_esc_html(osc_highlight(osc_item_description(), 500)); ?>" />
  <meta property="og:type" content="article" />
  <meta property="og:locale" content="<?php echo osc_current_user_locale(); ?>" />
  <meta property="product:retailer_item_id" content="<?php echo osc_item_id(); ?>" />
  <?php if(zet_check_category_price(osc_item_category_id())) { ?>
  <meta property="product:price:amount" content="<?php echo osc_esc_html(strip_tags(osc_item_price()/1000000)); ?>" />
  <?php if(osc_item_price() <> '' and osc_item_price() <> 0) { ?><meta property="product:price:currency" content="<?php echo osc_item_currency(); ?>" /><?php } ?>
  <?php } ?>
  
  <!-- GOOGLE RICH SNIPPETS -->
  <span itemscope itemtype="http://schema.org/Product">
    <meta itemprop="name" content="<?php echo osc_esc_html(osc_item_title()); ?>" />
    <meta itemprop="description" content="<?php echo osc_esc_html(osc_highlight(osc_item_description(), 500)); ?>" />
    <?php if(osc_count_item_resources() > 0) { ?><meta itemprop="image" content="<?php echo $resource_url; ?>" /><?php } ?>
  </span>
  
  <style>#item-image li {--mb-gallery-padding:<?php echo $gallery_padding_top; ?>%;}</style>
</head>

<body id="item" class="<?php osc_run_hook('body_class'); ?><?php if(osc_item_is_expired()) { ?> expired<?php } ?>">
  <?php osc_current_web_theme_path('header.php'); ?>

  <div class="container primary">
    <?php osc_run_hook('item_top'); ?>
    
    <?php echo zet_banner('item_top'); ?>

    <div id="item-top">
      <?php if(osc_images_enabled_at_items()) { ?> 
        <div id="item-image" class="<?php if(osc_count_item_resources() <= 0) { ?>noimg<?php } else if(osc_count_item_resources() < 3) { ?>fewimg<?php } ?>" data-count="<?php echo osc_count_item_resources(); ?>">
          <div class="mlinks top">
            <a href="#" class="mlink report-button">
              <i class="fas fa-flag"></i>
              <span><?php _e('Report', 'zeta'); ?></span>
            </a>

            <div class="report-wrap" style="display:none;">
              <div class="head"><?php _e('Report listing', 'zeta'); ?></div>
              <div class="wrap">
                <div class="line"><?php _e('If you find this listing as inappropriate, offensive or spammy, please let us know about it.', 'zeta'); ?></div>
                <div class="line"><strong><?php _e('Select one of following reasons:', 'zeta'); ?></strong></div>
                
                <div class="text">
                  <a href="<?php echo osc_item_link_spam(); ?>" rel="nofollow" class="btn btn-white"><?php _e('Spam', 'zeta'); ?></a>
                  <a href="<?php echo osc_item_link_bad_category(); ?>" rel="nofollow" class="btn btn-white"><?php _e('Misclassified', 'zeta'); ?></a>
                  <a href="<?php echo osc_item_link_repeated(); ?>" rel="nofollow" class="btn btn-white"><?php _e('Duplicated', 'zeta'); ?></a>
                  <a href="<?php echo osc_item_link_expired(); ?>" rel="nofollow" class="btn btn-white"><?php _e('Expired', 'zeta'); ?></a>
                  <a href="<?php echo osc_item_link_offensive(); ?>" rel="nofollow" class="btn btn-white"><?php _e('Offensive', 'zeta'); ?></a>
                </div>
                
                <div class="line center"><?php _e('Thanks for helping us!', 'zeta'); ?></div>
              </div>
            </div>
            
            
            <?php zet_make_favorite(osc_item_id(), false, true, 'mlinkw'); ?>
            
            <a href="#" class="mlink share">
              <i class="fas fa-share-square"></i>
              <span><?php _e('Share', 'zeta'); ?></span>
            </a>
          </div>

          <div class="mlinks bottom">
            <a href="<?php echo osc_resource_url(); ?>" class="mlink gallery">
              <i class="fas fa-images"></i>
              <span><?php echo sprintf(__('%d images', 'zeta'), osc_count_item_resources()); ?>
            </a>
          </div>
          
          <?php 
            osc_get_item_resources(); 
            osc_reset_resources();
          ?>
          
          <?php if(osc_count_item_resources() < 3) { ?>
            <div class="img-bg">
              <img class="<?php echo (zet_is_lazy() ? 'lazy' : ''); ?>" <?php echo (zet_is_lazy_browser() ? 'loading="lazy"' : ''); ?> src="<?php echo (zet_is_lazy() ? zet_get_load_image() : osc_resource_preview_url()); ?>" data-src="<?php echo osc_resource_preview_url(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>"/>
              <div class="img-overlay"></div>
            </div>
          <?php } ?>
          
          <?php //osc_get_item_resources(); ?>
          <?php osc_reset_resources(); ?>
          
          <?php if(osc_count_item_resources() > 0) { ?>
            <div class="swiper-container<?php if(osc_count_item_resources() <= 1) { ?> hide-buttons<?php } ?>">
              <div class="swiper-wrapper">
                <?php for($i = 0;osc_has_item_resources(); $i++) { ?>
                  <li class="swiper-slide ratio<?php echo str_replace(':', 'to', zet_param('gallery_ratio')); ?>" <?php if(zet_param('gallery_ratio') == '') { ?>style="*padding-top:<?php echo $gallery_padding_top; ?>%;"<?php } ?>>
                    <a href="<?php echo osc_resource_url(); ?>">
                      <img Xloading="lazy" class="<?php echo (zet_is_lazy() ? 'lazy' : ''); ?>" <?php echo (zet_is_lazy_browser() ? 'loading="lazy"' : ''); ?> src="<?php echo (zet_is_lazy() ? zet_get_load_image() : osc_resource_preview_url()); ?>" data-src="<?php echo osc_resource_preview_url(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?> - <?php echo $i+1;?>/<?php echo osc_count_item_resources(); ?>"/>
                    </a>
                  </li>
                <?php } ?>
              </div>
              
              <div class="swiper-pg"></div>

              <div class="swiper-button swiper-next"><?php echo ICON_SCROLL_NEXT; ?></div>
              <div class="swiper-button swiper-prev"><?php echo ICON_SCROLL_PREV; ?></div>
            </div>
          <?php } ?>
          
          <?php //osc_get_item_resources(); ?>
          <?php //osc_reset_resources(); ?>

          <?php if(osc_count_item_resources() > 0 && 1==2) { ?>
            <div class="swiper-thumbs">
              <ul>
                <?php for($i = 0;osc_has_item_resources(); $i++) { ?>
                  <li class="<?php if($i == 0) { ?>active<?php } ?>" data-id="<?php echo $i; ?>">
                    <img class="<?php echo (zet_is_lazy() ? 'lazy' : ''); ?>" <?php echo (zet_is_lazy_browser() ? 'loading="lazy"' : ''); ?> src="<?php echo (zet_is_lazy() ? zet_get_load_image() : osc_resource_thumbnail_url()); ?>" data-src="<?php echo osc_resource_thumbnail_url(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?> - <?php echo $i+1;?>"/>
                  </li>
                <?php } ?>
              </ul>
            </div>
          <?php } ?>
        </div>
      <?php } ?>
      
      <?php osc_run_hook('item_images'); ?>
    </div>
    
    
    <div class="data-box<?php echo osc_esc_html(@$item_extra['i_sold'] == 1 ?  ' sold' : ''); ?>" title="<?php echo osc_esc_html(@$item_extra['i_sold'] == 1 ? __('This listing has been sold', 'zeta') : ''); ?>">
      <div id="item-main">
        <div class="basic">
          <div class="item-bread isMobile"><?php echo $breadcrumb; ?></div>
        
          <h1 class="row">
            <?php if(osc_item_is_premium()) { ?><span class="label premium"><?php _e('Premium', 'zeta'); ?></span><?php } ?>
            <?php if(osc_item_is_expired()) { ?><span class="label expired"><?php _e('Expired', 'zeta'); ?></span><?php } ?>
            <?php if($item_extra['i_sold'] == 1) { ?><span class="label sold"><?php _e('Sold', 'zeta'); ?></span><?php } ?>
            <?php if($item_extra['i_sold'] == 2) { ?><span class="label reserved"><?php _e('Reserved', 'zeta'); ?></span><?php } ?>

            <?php echo osc_item_title(); ?>
          </h1>
          
          <?php osc_run_hook('item_title'); ?>

          <div class="price-row isDesktop isTablet">
            <?php if(zet_check_category_price(osc_item_category_id())) { ?>
              <div class="row price under-header p-<?php echo osc_esc_html(osc_item_price()); ?>x<?php if(osc_item_price() <= 0) { ?> isstring<?php } ?>"><?php echo osc_item_formated_price(); ?></div>
            <?php } ?>
            
            <?php if(function_exists('mo_show_offer_link_raw') && mo_show_offer_link_raw() !== false) { ?>
              <a href="<?php echo mo_show_offer_link_raw(); ?>" class="mo-open-offer mo-button-create mo-make-offer-price"><?php _e('Make price offer', 'zeta'); ?></a>
            <?php } ?>
          </div>
          
          <div class="row details isDesktop isTablet">
            <div class="elem ct">
              <?php echo ICON_ALL; ?>
              <?php echo osc_item_category(); ?>
            </div>            

            <div class="elem dt">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16"><path d="M148 288h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12zm108-12v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm-96 96v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm-96 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm192 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96-260v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h48V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h128V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h48c26.5 0 48 21.5 48 48zm-48 346V160H48v298c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z"/></svg>
              <?php echo zet_smart_date2(osc_item_pub_date(), true); ?>
            </div>
            
            <?php if(!in_array(osc_item_category_id(), zet_extra_fields_hide())) { ?>
              <?php if(zet_get_simple_name($item_extra['i_condition'], 'condition', false) <> '') { ?>
                <div class="elem cd">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="16" height="16"><path d="M638.3 143.8L586.8 41c-4-8-12.1-9.5-16.7-8.9L320 64 69.8 32.1c-4.6-.6-12.6.9-16.6 8.9L1.7 143.8c-4.6 9.2.3 20.2 10.1 23L64 181.7V393c0 14.7 10 27.5 24.2 31l216.2 54.1c6 1.5 17.4 3.4 31 0L551.8 424c14.2-3.6 24.2-16.4 24.2-31V181.7l52.1-14.9c9.9-2.8 14.7-13.8 10.2-23zM86 82.6l154.8 19.7-41.2 68.3-138-39.4L86 82.6zm26 112.8l97.8 27.9c8 2.3 15.2-1.8 18.5-7.3L296 103.8v322.7l-184-46V195.4zm416 185.1l-184 46V103.8l67.7 112.3c3.3 5.5 10.6 9.6 18.5 7.3l97.8-27.9v185zm-87.7-209.9l-41.2-68.3L554 82.6l24.3 48.6-138 39.4z"/></svg>
                  <?php echo zet_get_simple_name($item_extra['i_condition'], 'condition', false); ?>
                </div>
              <?php } ?>

              <?php if(zet_get_simple_name($item_extra['i_transaction'], 'transaction', false) <> '') { ?>
                <div class="elem tr">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="16" height="16"><path d="M255.7 182.7l65.6-60.1c7.4-6.8 17-10.5 27-10.5l83.7-.2c2.1 0 4.1.8 5.5 2.3l61.7 61.6H624c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16H519.2l-47.6-47.6C461.1 69.9 446.9 64 432 64H205.2c-14.8 0-29.1 5.9-39.6 16.3L118 127.9H16c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h121.9l61.7-61.6c2-.8 3.7-1.5 5.7-2.3H262l-38.7 35.5c-29.4 26.9-31.1 72.3-4.4 101.3 14.8 16.2 61.2 41.2 101.5 4.4l8.2-7.5 108.2 87.8c3.4 2.8 4 7.8 1.2 11.3L411.9 377c-2.8 3.4-7.8 4-11.3 1.2l-23.9-19.4-30 36.5c-2.2 2.7-5.4 4.4-8.9 4.8-3.5.4-7-.7-9.1-2.4l-36.8-31.5-15.6 19.2c-13.9 17.1-39.2 19.7-55.3 6.6l-97.3-88H16c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h89l84.6 76.4c30.9 25.1 73.8 25.7 105.6 3.8 12.5 10.8 26 15.9 41.1 15.9 18.2 0 35.3-7.4 48.8-24 22.1 8.7 48.2 2.6 64-16.8l26.2-32.3c5.6-6.9 9.1-14.8 10.9-23H624c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16H474.8c-2.3-2.8-4.9-5.4-7.7-7.7l-102.7-83.4 12.5-11.4c6.5-6 7-16.1 1-22.6L367 167.1c-6-6.5-16.1-6.9-22.6-1l-55.2 50.6c-9.5 8.7-25.7 9.4-34.6 0-9.4-9.9-8.5-25.2 1.1-34z"/></svg>
                  <?php echo zet_get_simple_name($item_extra['i_transaction'], 'transaction', false); ?>
                </div>
              <?php } ?>          
            <?php } ?>
            
            <?php if(osc_item_country() != '') { ?>
              <div class="elem cn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" width="16" height="16"><path d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm-32 50.8v11.3c0 11.9-12.5 19.6-23.2 14.3l-24-12c14.9-6.4 30.7-10.9 47.2-13.6zm32 369.8V456c-110.3 0-200-89.7-200-200 0-29.1 6.4-56.7 17.6-81.7 9.9 14.7 25.2 37.4 34.6 51.1 5.2 7.6 11.2 14.6 18.1 20.7l.8.7c9.5 8.6 20.2 16 31.6 21.8 14 7 34.4 18.2 48.8 26.1 10.2 5.6 16.5 16.3 16.5 28v32c0 8.5 3.4 16.6 9.4 22.6 15 15.1 24.3 38.7 22.6 51.3zm42.7 22.7l17.4-46.9c2-5.5 3.3-11.2 4.8-16.9 1.1-4 3.2-7.7 6.2-10.7l11.3-11.3c8.8-8.7 13.7-20.6 13.7-33 0-8.1-3.2-15.9-8.9-21.6l-13.7-13.7c-6-6-14.1-9.4-22.6-9.4H232c-9.4-4.7-21.5-32-32-32s-20.9-2.5-30.3-7.2l-11.1-5.5c-4-2-6.6-6.2-6.6-10.7 0-5.1 3.3-9.7 8.2-11.3l31.2-10.4c5.4-1.8 11.3-.6 15.5 3.1l9.3 8.1c1.5 1.3 3.3 2 5.2 2h5.6c6 0 9.8-6.3 7.2-11.6l-15.6-31.2c-1.6-3.1-.9-6.9 1.6-9.3l9.9-9.6c1.5-1.5 3.5-2.3 5.6-2.3h9c2.1 0 4.2-.8 5.7-2.3l8-8c3.1-3.1 3.1-8.2 0-11.3l-4.7-4.7c-3.1-3.1-3.1-8.2 0-11.3L264 112l4.7-4.7c6.2-6.2 6.2-16.4 0-22.6l-28.3-28.3c2.5-.1 5-.4 7.6-.4 78.2 0 145.8 45.2 178.7 110.7l-13 6.5c-3.7 1.9-6.9 4.7-9.2 8.1l-19.6 29.4c-5.4 8.1-5.4 18.6 0 26.6l18 27c3.3 5 8.4 8.5 14.1 10l29.2 7.3c-10.8 84-73.9 151.9-155.5 169.7z"/></svg>
                <?php echo osc_item_country(); ?>
              </div>
            <?php } ?>
            
            <?php if(osc_item_region() != '') { ?>
              <div class="elem rg">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path d="M344 126c0-13.3-10.7-24-24-24s-24 10.7-24 24c0 13.2 10.7 24 24 24s24-10.8 24-24zm-24 226c5 0 10-2 13.5-6.1 35.3-40 127.3-150.1 127.3-210.6C460.8 60.6 397.8 0 320 0S179.2 60.6 179.2 135.3c0 60.4 92 170.6 127.3 210.6C310 350 315 352 320 352zm0-304c51.2 0 92.8 39.2 92.8 87.3 0 21.4-31.8 79.1-92.8 152.6-61-73.5-92.8-131.2-92.8-152.6 0-48.1 41.6-87.3 92.8-87.3zm240 112c-2 0-4 .4-6 1.2l-73.5 27.2c-8.2 20.4-20.2 42-34.2 63.8L528 222v193l-128 44.5V316.3c-13.7 17.3-27.9 34.3-42.5 50.8-1.7 1.9-3.6 3.5-5.5 5.1v81.4l-128-45.2v-113c-18.1-24.1-34.8-48.8-48-72.8v180.2l-.6.2L48 450V257l123.6-43c-8-15.4-14.1-30.3-18.3-44.5L20.1 216C8 220.8 0 232.6 0 245.7V496c0 9.2 7.5 16 16 16 2 0 4-.4 6-1.2L192 448l172 60.7c13 4.3 27 4.4 40 .2L555.9 456c12.2-4.9 20.1-16.6 20.1-29.7V176c0-9.2-7.5-16-16-16z"/></svg>
                <?php echo osc_item_region(); ?>
              </div>
            <?php } ?>
            
            <?php if(osc_item_city() != '') { ?>
              <div class="elem ct">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="16" height="16"><path d="M244 384h-40c-6.63 0-12 5.37-12 12v40c0 6.63 5.37 12 12 12h40c6.63 0 12-5.37 12-12v-40c0-6.63-5.37-12-12-12zm0-192h-40c-6.63 0-12 5.37-12 12v40c0 6.63 5.37 12 12 12h40c6.63 0 12-5.37 12-12v-40c0-6.63-5.37-12-12-12zm-96 0h-40c-6.63 0-12 5.37-12 12v40c0 6.63 5.37 12 12 12h40c6.63 0 12-5.37 12-12v-40c0-6.63-5.37-12-12-12zm0 192h-40c-6.63 0-12 5.37-12 12v40c0 6.63 5.37 12 12 12h40c6.63 0 12-5.37 12-12v-40c0-6.63-5.37-12-12-12zm0-96h-40c-6.63 0-12 5.37-12 12v40c0 6.63 5.37 12 12 12h40c6.63 0 12-5.37 12-12v-40c0-6.63-5.37-12-12-12zm96 0h-40c-6.63 0-12 5.37-12 12v40c0 6.63 5.37 12 12 12h40c6.63 0 12-5.37 12-12v-40c0-6.63-5.37-12-12-12zm288 96h-40c-6.63 0-12 5.37-12 12v40c0 6.63 5.37 12 12 12h40c6.63 0 12-5.37 12-12v-40c0-6.63-5.37-12-12-12zm0-96h-40c-6.63 0-12 5.37-12 12v40c0 6.63 5.37 12 12 12h40c6.63 0 12-5.37 12-12v-40c0-6.63-5.37-12-12-12zm84-96H512V24c0-13.26-10.74-24-24-24H280c-13.26 0-24 10.74-24 24v72h-32V16c0-8.84-7.16-16-16-16h-16c-8.84 0-16 7.16-16 16v80h-64V16c0-8.84-7.16-16-16-16H80c-8.84 0-16 7.16-16 16v80H24c-13.26 0-24 10.74-24 24v376c0 8.84 7.16 16 16 16h16c8.84 0 16-7.16 16-16V144h256V48h160v192h128v256c0 8.84 7.16 16 16 16h16c8.84 0 16-7.16 16-16V216c0-13.26-10.75-24-24-24zM404 96h-40c-6.63 0-12 5.37-12 12v40c0 6.63 5.37 12 12 12h40c6.63 0 12-5.37 12-12v-40c0-6.63-5.37-12-12-12zm0 192h-40c-6.63 0-12 5.37-12 12v40c0 6.63 5.37 12 12 12h40c6.63 0 12-5.37 12-12v-40c0-6.63-5.37-12-12-12zm0-96h-40c-6.63 0-12 5.37-12 12v40c0 6.63 5.37 12 12 12h40c6.63 0 12-5.37 12-12v-40c0-6.63-5.37-12-12-12z"/></svg>
                <div>
                  <?php echo osc_item_city(); ?>
                  <a target="_blank" class="directions" href="https://maps.google.com/maps?daddr=<?php echo urlencode($location); ?>"><?php _e('Get directions', 'zeta'); ?> &#8594;</a>
                </div>
              </div>
            <?php } ?>

            <?php if(osc_item_city_area() != '') { ?>
              <div class="elem ca">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16"><path d="M512 256.01c0-12.86-7.53-24.42-19.12-29.44l-79.69-34.58 79.66-34.57c11.62-5.03 19.16-16.59 19.16-29.44s-7.53-24.41-19.12-29.42L274.66 3.89c-11.84-5.17-25.44-5.19-37.28-.02L19.16 98.55C7.53 103.58 0 115.14 0 127.99s7.53 24.41 19.12 29.42l79.7 34.58-79.67 34.57C7.53 231.58 0 243.15 0 256.01c0 12.84 7.53 24.41 19.12 29.42L98.81 320l-79.65 34.56C7.53 359.59 0 371.15 0 384.01c0 12.84 7.53 24.41 19.12 29.42l218.28 94.69a46.488 46.488 0 0 0 18.59 3.88c6.34-.02 12.69-1.3 18.59-3.86l218.25-94.69c11.62-5.03 19.16-16.59 19.16-29.44 0-12.86-7.53-24.42-19.12-29.44L413.19 320l79.65-34.56c11.63-5.03 19.16-16.59 19.16-29.43zM255.47 47.89l.03.02h-.06l.03-.02zm.53.23l184.16 79.89-183.63 80.09-184.62-80.11L256 48.12zm184.19 335.92l-183.66 80.07L71.91 384l87.21-37.84 78.29 33.96A46.488 46.488 0 0 0 256 384c6.34-.02 12.69-1.3 18.59-3.86l78.29-33.97 87.31 37.87zM256.53 336.1L71.91 255.99l87.22-37.84 78.28 33.96a46.488 46.488 0 0 0 18.59 3.88c6.34-.02 12.69-1.3 18.59-3.86l78.29-33.97 87.31 37.88-183.66 80.06z"/></svg>
                <?php echo osc_item_city_area(); ?>
              </div>
            <?php } ?>
            
            <?php if(osc_item_address() != '' || osc_item_zip() != '') { ?>
              <div class="elem ad">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16"><path d="M441.37 192c8.49 0 16.62-4.21 22.63-11.72l43.31-54.14c6.25-7.81 6.25-20.47 0-28.29L464 43.71C458 36.21 449.86 32 441.37 32H280V16c0-8.84-7.16-16-16-16h-16c-8.84 0-16 7.16-16 16v16H56c-13.25 0-24 13.43-24 30v100c0 16.57 10.75 30 24 30h176v32H70.63C62.14 224 54 228.21 48 235.71L4.69 289.86c-6.25 7.81-6.25 20.47 0 28.29L48 372.28c6 7.5 14.14 11.72 22.63 11.72H232v112c0 8.84 7.16 16 16 16h16c8.84 0 16-7.16 16-16V384h176c13.25 0 24-13.43 24-30V254c0-16.57-10.75-30-24-30H280v-32h161.37zM432 336H80.44l-25.6-32 25.6-32H432v64zM80 80h351.56l25.6 32-25.6 32H80V80z"/></svg>
                <?php echo implode(' - ', array_filter(array(osc_item_zip(), osc_item_address()))); ?>
              </div>
            <?php } ?>

            <div class="elem vw">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path d="M288 144a110.94 110.94 0 0 0-31.24 5 55.4 55.4 0 0 1 7.24 27 56 56 0 0 1-56 56 55.4 55.4 0 0 1-27-7.24A111.71 111.71 0 1 0 288 144zm284.52 97.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400c-98.65 0-189.09-55-237.93-144C98.91 167 189.34 112 288 112s189.09 55 237.93 144C477.1 345 386.66 400 288 400z"/></svg>
              <?php echo (osc_item_views() == 1 ? sprintf(__('%d view', 'zeta'), osc_item_views()) : sprintf(__('%d views', 'zeta'), osc_item_views())); ?>
            </div>
            
            <div class="elem id">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="16" height="16"><path d="M360 384h48c4.4 0 8-3.6 8-8V136c0-4.4-3.6-8-8-8h-48c-4.4 0-8 3.6-8 8v240c0 4.4 3.6 8 8 8zm96 0h48c4.4 0 8-3.6 8-8V136c0-4.4-3.6-8-8-8h-48c-4.4 0-8 3.6-8 8v240c0 4.4 3.6 8 8 8zm-160 0h16c4.4 0 8-3.6 8-8V136c0-4.4-3.6-8-8-8h-16c-4.4 0-8 3.6-8 8v240c0 4.4 3.6 8 8 8zM592 0H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h544c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zm0 464H48V48h544v416zm-456-80h48c4.4 0 8-3.6 8-8V136c0-4.4-3.6-8-8-8h-48c-4.4 0-8 3.6-8 8v240c0 4.4 3.6 8 8 8zm96 0h16c4.4 0 8-3.6 8-8V136c0-4.4-3.6-8-8-8h-16c-4.4 0-8 3.6-8 8v240c0 4.4 3.6 8 8 8z"/></svg>
              <?php echo sprintf(__('ID: %d', 'zeta'), osc_item_id()); ?>
            </div>
          </div>


          <div class="row details2 isMobile">
            <div class="elem dt">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M20 24h10c6.627 0 12 5.373 12 12v94.625C85.196 57.047 165.239 7.715 256.793 8.001 393.18 8.428 504.213 120.009 504 256.396 503.786 393.181 392.834 504 256 504c-63.926 0-122.202-24.187-166.178-63.908-5.113-4.618-5.354-12.561-.482-17.433l7.069-7.069c4.503-4.503 11.749-4.714 16.482-.454C150.782 449.238 200.935 470 256 470c117.744 0 214-95.331 214-214 0-117.744-95.331-214-214-214-82.862 0-154.737 47.077-190.289 116H164c6.627 0 12 5.373 12 12v10c0 6.627-5.373 12-12 12H20c-6.627 0-12-5.373-12-12V36c0-6.627 5.373-12 12-12zm321.647 315.235l4.706-6.47c3.898-5.36 2.713-12.865-2.647-16.763L272 263.853V116c0-6.627-5.373-12-12-12h-8c-6.627 0-12 5.373-12 12v164.147l84.884 61.734c5.36 3.899 12.865 2.714 16.763-2.646z"/></svg>
              <div><?php echo sprintf(__('Published %s by %s', 'zeta'), zet_smart_date(osc_item_pub_date(), true), (osc_item_user_id() > 0 ? '<a href="' . zet_user_public_profile_url(osc_item_user_id()) . '">' . osc_item_contact_name() . '</a>' : osc_item_contact_name())); ?></div>
            </div>

            <?php if(zet_check_category_price(osc_item_category_id())) { ?>
              <div class="elem pc">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M497.941 225.941L286.059 14.059A48 48 0 0 0 252.118 0H48C21.49 0 0 21.49 0 48v204.118a48 48 0 0 0 14.059 33.941l211.882 211.882c18.745 18.745 49.137 18.746 67.882 0l204.118-204.118c18.745-18.745 18.745-49.137 0-67.882zm-22.627 45.255L271.196 475.314c-6.243 6.243-16.375 6.253-22.627 0L36.686 263.431A15.895 15.895 0 0 1 32 252.117V48c0-8.822 7.178-16 16-16h204.118c4.274 0 8.292 1.664 11.314 4.686l211.882 211.882c6.238 6.239 6.238 16.39 0 22.628zM144 124c11.028 0 20 8.972 20 20s-8.972 20-20 20-20-8.972-20-20 8.972-20 20-20m0-28c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48z"/></svg>
                
                <div>
                  <div><?php echo osc_item_formated_price(); ?></div>

                  <?php if(function_exists('mo_show_offer_link_raw') && mo_show_offer_link_raw() !== false) { ?>
                    <a href="<?php echo mo_show_offer_link_raw(); ?>" class="mo-open-offer mo-button-create mo-make-offer-price"><?php _e('Make price offer', 'zeta'); ?></a>
                  <?php } ?>
                </div>
              </div>
            <?php } ?>
          
            <div class="elem ct">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M506 114H134a6 6 0 0 1-6-6V84a6 6 0 0 1 6-6h372a6 6 0 0 1 6 6v24a6 6 0 0 1-6 6zm6 154v-24a6 6 0 0 0-6-6H134a6 6 0 0 0-6 6v24a6 6 0 0 0 6 6h372a6 6 0 0 0 6-6zm0 160v-24a6 6 0 0 0-6-6H134a6 6 0 0 0-6 6v24a6 6 0 0 0 6 6h372a6 6 0 0 0 6-6zM84 120V72c0-6.627-5.373-12-12-12H24c-6.627 0-12 5.373-12 12v48c0 6.627 5.373 12 12 12h48c6.627 0 12-5.373 12-12zm0 160v-48c0-6.627-5.373-12-12-12H24c-6.627 0-12 5.373-12 12v48c0 6.627 5.373 12 12 12h48c6.627 0 12-5.373 12-12zm0 160v-48c0-6.627-5.373-12-12-12H24c-6.627 0-12 5.373-12 12v48c0 6.627 5.373 12 12 12h48c6.627 0 12-5.373 12-12z"/></svg>
              <div><?php echo sprintf(__('In %s category', 'zeta'), '<a href="' . osc_search_url(array('page' => 'search', 'sCategory' => osc_category_id())) . '">' . osc_item_category() . '</a>' ); ?></div>
            </div>            

           
            <?php if(!in_array(osc_item_category_id(), zet_extra_fields_hide())) { ?>
              <?php if(zet_get_simple_name($item_extra['i_condition'], 'condition', false) <> '') { ?>
                <div class="elem cd">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="20" height="20"><path d="M492.5 133.4L458.9 32.8C452.4 13.2 434.1 0 413.4 0H98.6c-20.7 0-39 13.2-45.5 32.8L2.5 184.6c-1.6 4.9-2.5 10-2.5 15.2V464c0 26.5 21.5 48 48 48h400c106 0 192-86 192-192 0-90.7-63-166.5-147.5-186.6zM272 32h141.4c6.9 0 13 4.4 15.2 10.9l28.5 85.5c-3-.1-6-.5-9.1-.5-56.8 0-107.7 24.8-142.8 64H272V32zM83.4 42.9C85.6 36.4 91.7 32 98.6 32H240v160H33.7L83.4 42.9zM48 480c-8.8 0-16-7.2-16-16V224h249.9c-16.4 28.3-25.9 61-25.9 96 0 66.8 34.2 125.6 86 160H48zm400 0c-88.2 0-160-71.8-160-160s71.8-160 160-160 160 71.8 160 160-71.8 160-160 160zm64.6-221.7c-3.1-3.1-8.1-3.1-11.2 0l-69.9 69.3-30.3-30.6c-3.1-3.1-8.1-3.1-11.2 0l-18.7 18.6c-3.1 3.1-3.1 8.1 0 11.2l54.4 54.9c3.1 3.1 8.1 3.1 11.2 0l94.2-93.5c3.1-3.1 3.1-8.1 0-11.2l-18.5-18.7z"/></svg>
                  <div><?php echo zet_get_simple_name($item_extra['i_condition'], 'condition', false); ?></div>
                </div>
              <?php } ?>

              <?php if(zet_get_simple_name($item_extra['i_transaction'], 'transaction', false) <> '') { ?>
                <div class="elem tr">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="20" height="20"><path d="M16 319.8c8.8 0 16-7.2 16-16s-7.2-16-16-16-16 7.2-16 16c0 8.9 7.2 16 16 16zM632 128l-113.5.2-51.2-49.9c-9.1-9.1-21.1-14.1-33.9-14.1h-101c-10.4 0-20.1 3.9-28.3 10-8.4-6.5-18.7-10.3-29.3-10.3h-69.5c-12.7 0-24.9 5.1-33.9 14.1l-50 50H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h56v191.9H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h56c17.6 0 31.8-14.2 31.9-31.7h33.2l81.5 78c29.8 24.1 71.8 23.4 101-.2l7.2 6.2c9.6 7.8 21.3 11.9 33.5 11.9 16 0 31.1-7 41.4-19.6l21.9-26.9c16.4 8.9 42.9 9 60-12l9.5-11.7c6.2-7.6 9.6-16.6 10.5-25.7h48.6c.1 17.5 14.4 31.7 31.9 31.7h56c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8h-56V160.2l56-.2c4.4 0 8-3.6 8-8v-16c-.1-4.5-3.7-8-8.1-8zM460.2 357.6l-9.5 11.7c-5.4 6.6-15.4 8.1-22.5 2.3l-17.8-14.4-41.5 51c-7.5 9.3-21 10.2-29.4 3.4l-30.6-26.1-10.4 12.8c-16.7 20.5-47 23.7-66.6 7.9L142 320.1H96V159.9h38.6l59.3-59.3c3-3 7.1-4.7 11.3-4.7h69.5c.9 2.2.3.7 1.1 2.9l-59 54.2c-28.2 25.9-29.6 69.2-4.2 96.9 14.3 15.6 58.6 39.3 96.9 4.2l22.8-20.9 125.6 101.9c6.8 5.6 7.8 15.7 2.3 22.5zm83.8-37.5h-57.2c-2.5-3.5-5.3-6.9-8.8-9.8l-121.9-99 28.4-26.1c6.5-6 7-16.1 1-22.6s-16.1-6.9-22.6-1l-75.1 68.8c-14.4 13.1-38.6 12-51.7-2.2-13.6-14.8-12.7-38 2.2-51.7l83.1-76.2c3-2.7 6.8-4.2 10.8-4.2h101c4.3 0 8.3 1.7 11.4 4.8l60.7 59.1H544v160.1zm80-32.2c-8.8 0-16 7.2-16 16s7.2 16 16 16 16-7.2 16-16c0-8.9-7.2-16-16-16z"/></svg>
                  <div><?php echo zet_get_simple_name($item_extra['i_transaction'], 'transaction', false); ?></div>
                </div>
              <?php } ?>          
            <?php } ?>
            
            <?php if($location_full != '') { ?>
              <div class="elem lc">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="20" height="20"><path d="M192 96c-52.935 0-96 43.065-96 96s43.065 96 96 96 96-43.065 96-96-43.065-96-96-96zm0 160c-35.29 0-64-28.71-64-64s28.71-64 64-64 64 28.71 64 64-28.71 64-64 64zm0-256C85.961 0 0 85.961 0 192c0 77.413 26.97 99.031 172.268 309.67 9.534 13.772 29.929 13.774 39.465 0C357.03 291.031 384 269.413 384 192 384 85.961 298.039 0 192 0zm0 473.931C52.705 272.488 32 256.494 32 192c0-42.738 16.643-82.917 46.863-113.137S149.262 32 192 32s82.917 16.643 113.137 46.863S352 149.262 352 192c0 64.49-20.692 80.47-160 281.931z"/></svg>
                <div>
                  <div><?php echo $location_full; ?></div>
                  
                  <?php if($location != '') { ?>
                    <a target="_blank" class="directions" href="https://maps.google.com/maps?daddr=<?php echo urlencode($location); ?>"><?php _e('Get directions', 'zeta'); ?> &#8594;</a>
                  <?php } ?>
                </div>
              </div>
            <?php } ?>

            <div class="elem vw">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="20" height="20"><path d="M288 288a64 64 0 0 0 0-128c-1 0-1.88.24-2.85.29a47.5 47.5 0 0 1-60.86 60.86c0 1-.29 1.88-.29 2.85a64 64 0 0 0 64 64zm284.52-46.6C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 96a128 128 0 1 1-128 128A128.14 128.14 0 0 1 288 96zm0 320c-107.36 0-205.46-61.31-256-160a294.78 294.78 0 0 1 129.78-129.33C140.91 153.69 128 187.17 128 224a160 160 0 0 0 320 0c0-36.83-12.91-70.31-33.78-97.33A294.78 294.78 0 0 1 544 256c-50.53 98.69-148.64 160-256 160z"/></svg>
              <?php echo (osc_item_views() == 1 ? sprintf(__('%d item view', 'zeta'), osc_item_views()) : sprintf(__('%d item views', 'zeta'), osc_item_views())); ?>
            </div>
            
            <div class="elem ds">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20"><path d="M288 52v24a6 6 0 0 1-6 6H6a6 6 0 0 1-6-6V52a6 6 0 0 1 6-6h276a6 6 0 0 1 6 6zM6 210h436a6 6 0 0 0 6-6v-24a6 6 0 0 0-6-6H6a6 6 0 0 0-6 6v24a6 6 0 0 0 6 6zm0 256h436a6 6 0 0 0 6-6v-24a6 6 0 0 0-6-6H6a6 6 0 0 0-6 6v24a6 6 0 0 0 6 6zm276-164H6a6 6 0 0 0-6 6v24a6 6 0 0 0 6 6h276a6 6 0 0 0 6-6v-24a6 6 0 0 0-6-6z"/></svg>
              <div class="desc-text">
                <div class="item-mob-desc">
                  <?php if(function_exists('show_qrcode')) { ?>
                    <div class="qr-code">
                      <strong><?php _e('Scan QR', 'zeta'); ?></strong>
                      <?php show_qrcode(); ?>
                    </div>
                  <?php } ?>
                  
                  <?php echo osc_item_description(); ?>
                  
                  <?php osc_run_hook('item_description'); ?>
                  
                  <a href="#" class="desc-show-more isMobile"><?php _e('Read more', 'zeta'); ?></a>
                </div>
              </div>
            </div>
          </div>


          <div class="row date isDesktop isTablet">
            <p>
              <?php 
                echo sprintf(__('Published on %s', 'zeta'), osc_format_date(osc_item_pub_date()));
                echo (osc_item_mod_date() <> '' ? '. ' . sprintf(__('Modified on %s', 'zeta'), osc_format_date(osc_item_mod_date())) . '.' : '');
              ?>
            </p>
          </div>
        </div>
        

        <!-- CUSTOM FIELDS -->
        <div class="props<?php if($has_cf) { ?> style<?php } ?>">
          <?php if($has_cf) { ?>
            <h2><?php _e('Attributes', 'zeta'); ?></h2>

            <div class="custom-fields">
              <?php while(osc_has_item_meta()) { ?>
                <?php
                  $meta = osc_item_meta();
                  $meta_type = @$meta['e_type'];
                  $meta_value = @$meta['s_value'];
                  
                  if($meta_type != 'CHECKBOX') {
                    $meta_value = osc_item_meta_value();
                  }
                ?>
              
                <?php if(osc_item_meta_value() != '') { ?>
                  <div class="field type-<?php echo osc_esc_html($meta_type); ?> name-<?php echo osc_esc_html(strtoupper(str_replace(' ', '-', osc_item_meta_name()))); ?> value-<?php echo osc_esc_html($meta_value); ?>">
                    <span class="name"><?php echo osc_item_meta_name(); ?></span> 
                    <span class="value"><?php echo osc_item_meta_value(); ?></span>
                  </div>
                <?php } ?>
              <?php } ?>
            </div>
          <?php } ?>      

          <div id="item-hook"><?php osc_run_hook('item_detail', osc_item()); ?></div>
        </div>
        
        <?php osc_run_hook('item_meta'); ?>
        
        <?php echo zet_banner('item_description'); ?>
        
        <!-- DESCRIPTION -->
        <div class="row description">
          <h2><?php _e('Description', 'zeta'); ?></h2>

          <div class="desc-parts isDesktop isTablet">
            <div class="desc-text">
              <div class="text visible">
                <?php if(function_exists('show_qrcode')) { ?>
                  <div class="qr-code">
                    <strong><?php _e('Scan QR', 'zeta'); ?></strong>
                    <?php show_qrcode(); ?>
                  </div>
                <?php } ?>
                
                <?php echo osc_item_description(); ?>
              </div>
            </div>
            
            <?php osc_run_hook('item_description'); ?>
          </div>
          
          <div id="location-hook"><?php osc_run_hook('location'); ?></div>
        </div>
        
        
        <!-- SELLER BLOCK -->
        <?php if(osc_item_user_id() > 0) { ?>
          <div class="box" id="meet-seller">
            <h2 class="with-line">
              <span><?php _e('Meet the seller', 'zeta'); ?></span>
              <div class="ln"></div>
            </h2>

            <div class="wrap">
              <img src="<?php echo zet_profile_picture(osc_item_user_id(), 'small'); ?>" alt="<?php echo osc_esc_html($contact_name); ?>" class="uimg" />

              <div class="info1">
                <div class="name1">
                  <a class="name" href="<?php echo zet_user_public_profile_url(osc_item_user_id()); ?>"><?php echo $contact_name; ?></a>
                  
                  <?php if(function_exists('ur_show_rating_link')) { ?>
                    <div class="line-rating">
                      <span class="ur-fdb">
                        <span class="strs"><?php echo ur_show_rating_stars(osc_item_user_id(), osc_contact_email(), osc_item_id()); ?></span>
                      </span>
                    </div>
                  <?php } ?>
                </div>
                
                <?php if($item_user_location != '') { ?>
                  <div class="address"><i class="fas fa-map-marker-alt"></i> <?php echo $item_user_location; ?></div>
                <?php } ?>
                
                <div class="elements">
                  <div class="items"><?php echo sprintf(__('%d active listings', 'zeta'), $user_item_count); ?></div>

                  <?php if($is_company) { ?>
                    <div class="pro"><?php _e('Company', 'zeta'); ?></div>
                  <?php } else { ?>
                    <div class="pro"><?php _e('Peronal seller', 'zeta'); ?></div>
                  <?php } ?>
                  
                  <div class="date"><?php echo $last_online; ?></div>
                  <div class="reg"><?php echo $reg_type; ?></div>
                </div>

                <div class="phones">
                  <?php if($user_phone_mobile_data['found']) { ?>
                    <a class="phone-mobile phone <?php echo $user_phone_mobile_data['class']; ?>" title="<?php echo osc_esc_html($user_phone_mobile_data['title']); ?>" data-prefix="tel" href="<?php echo $user_phone_mobile_data['url']; ?>" data-part1="<?php echo osc_esc_html($user_phone_mobile_data['part1']); ?>" data-part2="<?php echo osc_esc_html($user_phone_mobile_data['part2']); ?>">
                      <i class="fas fa-phone-alt"></i>
                      <span><?php echo $user_phone_mobile_data['masked']; ?></span>
                    </a>
                  <?php } ?>

                  <?php if($user_phone_land_data['found']) { ?>
                    <a class="phone-land phone <?php echo $user_phone_land_data['class']; ?>" title="<?php echo osc_esc_html($user_phone_land_data['title']); ?>" data-prefix="tel" href="<?php echo $user_phone_land_data['url']; ?>" data-part1="<?php echo osc_esc_html($user_phone_land_data['part1']); ?>" data-part2="<?php echo osc_esc_html($user_phone_land_data['part2']); ?>">
                      <i class="fas fa-phone-alt"></i>
                      <span><?php echo $user_phone_land_data['masked']; ?></span>
                    </a>
                  <?php } ?>
                </div>
              </div>
              
              <div class="info2">
                <a href="<?php echo zet_user_public_profile_url(osc_item_user_id()); ?>" class="btn btn-white"><?php echo __('Seller\'s profile', 'zeta'); ?></a>
                <a href="<?php echo osc_search_url(array('page' => 'search', 'userId' => osc_item_user_id())); ?>" class="btn btn-white"><?php echo __('All seller items', 'zeta') . ' (' . $user_item_count . ')'; ?></a>

                <?php if(trim(osc_user_website()) <> '') { ?>
                  <a href="<?php echo osc_user_website(); ?>" target="_blank" rel="nofollow noreferrer" class="btn btn-white">
                    <i class="fas fa-external-link-alt"></i>
                    <span><?php echo rtrim(str_replace(array('https://', 'http://'), '', osc_user_website()), '/'); ?></span>
                  </a>
                <?php } ?>
              
              </div>
            </div>
          </div>
        <?php } ?>
        

        <!-- COMMENTS BLOCK -->
        <?php if(osc_comments_enabled()) { ?>
          <div class="box" id="comments">
            <h2><?php _e('Comments', 'zeta'); ?> <em class="counter">(<?php echo osc_item_total_comments(); ?>)</em></h2>

            <div class="wrap">
              <?php if(osc_item_total_comments() > 0) { ?>
                <?php while(osc_has_item_comments()) { ?>
                  <?php
                    $comment_author = (osc_comment_author_name() == '' ? __('Anonymous', 'zeta') : osc_comment_author_name());
                    $comment_title = trim(osc_comment_title());
                  ?>
                  
                  <div class="comment">
                    <div class="top-block">
                      <a class="author" href="<?php echo (osc_comment_user_id() ? zet_user_public_profile_url(osc_comment_user_id()) : '#'); ?>" <?php echo (osc_comment_user_id() > 0 ? '' : 'onclick="return false;"'); ?>>
                        <img class="img <?php echo (zet_is_lazy() ? 'lazy' : ''); ?>" <?php echo (zet_is_lazy_browser() ? 'loading="lazy"' : ''); ?> src="<?php echo (zet_is_lazy() ? zet_get_load_image() : zet_profile_picture(osc_comment_user_id(), 'medium')); ?>" data-src="<?php echo zet_profile_picture(osc_comment_user_id(), 'medium'); ?>" alt="<?php echo osc_esc_html(osc_comment_author_name()); ?>"/>
                        <strong class="name"><?php echo $comment_author; ?></strong>
                        <div class="date"><?php echo zet_smart_date(osc_comment_pub_date()); ?></div>
                      </a>
                     
                      <?php if(function_exists('osc_enable_comment_rating') && osc_enable_comment_rating()) { ?>
                        <div class="rating">
                          <?php for($i = 1; $i <= 5; $i++) { ?>
                            <?php
                              $class = '';
                              if(osc_comment_rating() >= $i) {
                                $class = ' fill';
                              }
                            ?>
                            <i class="fa fa-star<?php echo $class; ?>"></i>
                          <?php } ?>

                          <span>(<?php echo sprintf(__('%d of 5', 'zeta'), osc_comment_rating()); ?>)</span>
                        </div>
                      <?php } ?>
                    </div>
                    
                    <div class="data">
                      <?php if(osc_comment_title() != '') { ?>
                        <h3><?php echo osc_comment_title(); ?></h3>
                      <?php } ?>

                      <div class="body"><?php echo nl2br(osc_comment_body()); ?></div>
   
                      <?php if(osc_comment_user_id() && (osc_comment_user_id() == osc_logged_user_id())) { ?>
                        <a rel="nofollow" class="remove" href="<?php echo osc_delete_comment_url(); ?>" title="<?php echo osc_esc_html(__('Delete your comment', 'zeta')); ?>">
                          <i class="fas fa-trash-alt"></i> <span><?php _e('Delete', 'zeta'); ?></span>
                        </a>
                      <?php } ?>
                    </div>
                  </div>

                  <?php if(function_exists('osc_enable_comment_reply') && osc_enable_comment_reply()) { ?>
                    <?php osc_get_comment_replies(); ?>
                    <?php if(osc_count_comment_replies() > 0) { ?>
                      <div id="comment-replies">
                        <?php while (osc_has_comment_replies()) { ?>
                          <?php
                            $comment_reply_author = (osc_comment_reply_author_name() == '' ? __('Anonymous', 'zeta') : osc_comment_reply_author_name());
                          ?>
                          
                          <div class="comment">
                            <div class="top-block">
                              <a class="author" href="<?php echo (osc_comment_reply_user_id() ? zet_user_public_profile_url(osc_comment_reply_user_id()) : '#'); ?>" <?php echo (osc_comment_reply_user_id() > 0 ? '' : 'onclick="return false;"'); ?>>
                                <img class="img <?php echo (zet_is_lazy() ? 'lazy' : ''); ?>" <?php echo (zet_is_lazy_browser() ? 'loading="lazy"' : ''); ?> src="<?php echo (zet_is_lazy() ? zet_get_load_image() : zet_profile_picture(osc_comment_reply_user_id(), 'medium')); ?>" data-src="<?php echo zet_profile_picture(osc_comment_reply_user_id(), 'medium'); ?>" alt="<?php echo osc_esc_html(osc_comment_reply_author_name()); ?>"/>
                                <strong class="name"><?php echo $comment_reply_author; ?></strong>
                                <div class="date"><?php echo zet_smart_date(osc_comment_reply_pub_date()); ?></div>
                              </a>
                              
                              <?php if(function_exists('osc_enable_comment_rating') && osc_enable_comment_rating()) { ?>
                                <div class="rating">
                                  <?php for($i = 1; $i <= 5; $i++) { ?>
                                    <?php
                                      $class = '';
                                      if(osc_comment_reply_rating() >= $i) {
                                        $class = ' fill';
                                      }
                                    ?>
                                    <i class="fa fa-star<?php echo $class; ?>"></i>
                                  <?php } ?>

                                  <span>(<?php echo sprintf(__('%d of 5', 'zeta'), osc_comment_reply_rating()); ?>)</span>
                                </div>
                              <?php } ?>
                            </div>
                            
                            <div class="data">
                              <?php if(osc_comment_reply_title() != '') { ?>
                                <h3><?php echo osc_comment_reply_title(); ?></h3>
                              <?php } ?>

                              <div class="body"><?php echo nl2br(osc_comment_reply_body()); ?></div>
           
                              <?php if(osc_comment_reply_user_id() && (osc_comment_reply_user_id() == osc_logged_user_id())) { ?>
                                <a rel="nofollow" class="remove" href="<?php echo osc_delete_comment_reply_url(); ?>" title="<?php echo osc_esc_html(__('Delete your comment', 'zeta')); ?>">
                                  <i class="fas fa-trash-alt"></i> <span><?php _e('Delete', 'zeta'); ?></span>
                                </a>
                              <?php } ?>
                            </div>
                          </div>
                        <?php } ?>
                      </div>
                    <?php } ?>
                  <?php } ?>
                  
                  <?php if(
                    function_exists('osc_enable_comment_reply')
                    && osc_enable_comment_reply() 
                    && (
                      osc_comment_reply_user_type() == ''
                      || osc_comment_reply_user_type() == 'LOGGED' && osc_is_web_user_logged_in()
                      || osc_comment_reply_user_type() == 'OWNER' && (osc_logged_user_id() == osc_item_user_id() && osc_item_user_id() > 0 || osc_logged_user_email() == osc_item_contact_email())
                      || osc_comment_reply_user_type() == 'ADMIN' && osc_is_admin_user_logged_in()
                    )
                  ) { ?>
                    <p class="comment-reply-row">
                      <?php $reply_params = array('replyToCommentId' => osc_comment_id()); ?>
                      <a class="btn btn-secondary comment-reply open-form" href="<?php echo zet_item_fancy_url('comment', $reply_params); ?>" data-type="comment-reply">
                        <i class="fas fa-reply"></i>
                        <?php 
                          if($comment_title != '') {
                            echo sprintf(__('Reply to "%s"', 'zeta'), osc_highlight($comment_title, 30)); 
                          } else {
                            echo __('Reply', 'zeta'); 
                          }
                        ?>
                      </a>
                    </p>
                  <?php } ?>
                <?php } ?>

                <div class="paginate"><?php echo osc_comments_pagination(); ?></div>

              <?php } else { ?>
                <div class="empty-comments"><?php _e('No comments has been added yet', 'zeta'); ?></div>
              <?php } ?>
              
              <?php if(osc_reg_user_post_comments() && osc_is_web_user_logged_in() || !osc_reg_user_post_comments()) { ?>
                <a class="open-form add btn<?php echo (osc_enable_comment_rating() ? ' has-rating' : ''); ?>" href="<?php echo zet_item_fancy_url('comment'); ?>" data-type="comment">
                  <?php _e('Add comment', 'zeta'); ?>
                </a>
              <?php } ?>
            </div>
          </div>
        <?php } ?>
        
        <?php osc_run_hook('item_comment'); ?>

        <div id="shortcuts">
          <a href="#" class="print isDesktop"><i class="fas fa-print"></i> <?php _e('Print', 'zeta'); ?></a>
          <a class="friend open-form" href="<?php echo zet_item_fancy_url('friend'); ?>" data-type="friend"><i class="fas fa-share-square"></i> <?php _e('Send to friend', 'zeta'); ?></a>

          <div class="item-share">
            <a class="whatsapp" href="whatsapp://send?text=<?php echo urlencode(osc_item_url()); ?>" data-action="share/whatsapp/share"><i class="fab fa-whatsapp"></i></a></span>
            <a class="facebook" title="<?php echo osc_esc_html(__('Share on Facebook', 'zeta')); ?>" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(osc_item_url()); ?>"><i class="fab fa-facebook"></i></a> 
            <a class="twitter" title="<?php echo osc_esc_html(__('Share on Twitter', 'zeta')); ?>" target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo urlencode(osc_item_title()); ?>&url=<?php echo urlencode(osc_item_url()); ?>"><i class="fab fa-twitter"></i></a> 
            <a class="pinterest" title="<?php echo osc_esc_html(__('Share on Pinterest', 'zeta')); ?>" target="_blank" href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode(osc_item_url()); ?>&media=<?php echo urlencode($resource_url); ?>&description=<?php echo htmlspecialchars(osc_item_title()); ?>"><i class="fab fa-pinterest"></i></a> 
          </div>
        </div>
      </div>


      <!-- SIDEBAR - RIGHT -->
      <div id="item-side">
        <?php osc_run_hook('item_sidebar_top'); ?>

        <div class="box" id="seller">
          <div class="line1">
            <div class="img">
              <img src="<?php echo zet_profile_picture(osc_item_user_id(), 'small'); ?>" alt="<?php echo osc_esc_html($contact_name); ?>" />

              <?php if(osc_item_user_id() > 0) { ?>
                <?php if(zet_user_is_online(osc_item_user_id())) { ?>
                  <div class="online" title="<?php echo osc_esc_html(__('User is online', 'zeta')); ?>"></div>
                <?php } else { ?>
                  <div class="online off" title="<?php echo osc_esc_html(__('User is offline', 'zeta')); ?>"></div>
                <?php } ?>
              <?php } ?>
            </div>

            <div class="data">
              <?php if(osc_item_user_id() > 0) { ?>
                <a class="name" href="<?php echo zet_user_public_profile_url(osc_item_user_id()); ?>"><?php echo $contact_name; ?></a>
              <?php } else { ?>
                <strong class="name"><?php echo $contact_name; ?></strong>
              <?php } ?>


              <?php if(osc_item_user_id() > 0 && function_exists('ur_show_rating_link')) { ?>
                <div class="line-rating">
                  <span class="ur-fdb">
                    <span class="strs"><?php echo ur_show_rating_stars(osc_item_user_id(), osc_contact_email(), osc_item_id()); ?></span>
                    <span class="lnk"><?php echo zet_add_rating_link(osc_item_user_id(), osc_item_id()); ?></span>
                  </span>
                </div>
                
              <?php } else { ?>
                <div class="items"><?php echo sprintf(__('%d active listings', 'zeta'), $user_item_count); ?></div>
              <?php } ?>
            </div>
          </div>


          <div class="line-contact">
            <?php if(zet_param('messenger_replace_button') == 1 && function_exists('im_contact_button') && im_contact_button(osc_item(), true) !== false) { ?>
              <a href="<?php echo im_contact_button(osc_item(), true); ?>" class="contact master-button">
                <span><?php _e('Send message', 'zeta'); ?></span>
              </a>
            <?php } else if(getBoolPreference('item_contact_form_disabled') != 1) { ?>
              <a href="<?php echo zet_item_fancy_url('contact'); ?>" class="open-form contact master-button" data-type="contact">
                <span><?php _e('Send message', 'zeta'); ?></span>
              </a>
            <?php } ?>
            
            <?php if($phone_data['found']) { ?>
              <a class="master-button white phone <?php echo $phone_data['class']; ?>" title="<?php echo osc_esc_html($phone_data['title']); ?>" data-prefix="tel" href="<?php echo $phone_data['url']; ?>" data-part1="<?php echo osc_esc_html($phone_data['part1']); ?>" data-part2="<?php echo osc_esc_html($phone_data['part2']); ?>">
                <span><?php echo $phone_data['masked']; ?></span>
                
                <div class="help">
                  <i class="fas fa-phone-alt"></i> <?php _e('Show number', 'zeta'); ?>
                </div>
              </a>
            <?php } ?>

            <?php if($email_data['visible']) { ?>
              <a class="master-button white email <?php echo $email_data['class']; ?>" title="<?php echo osc_esc_html($email_data['title']); ?>" href="#" data-prefix="mailto" data-part1="<?php echo osc_esc_html($email_data['part1']); ?>" data-part2="<?php echo osc_esc_html($email_data['part2']); ?>">
                <span><?php echo $email_data['masked']; ?></span>
                
                <div class="help">
                  <i class="fas fa-envelope-open"></i> <?php _e('Show email', 'zeta'); ?>
                </div>
              </a>
            <?php } ?>
            
            <?php osc_run_hook('item_contact'); ?>
          </div>

          <?php echo zet_banner('item_sidebar'); ?>
          
          <div class="line2">
            <div class="date"><?php echo $last_online; ?></div>
          </div>

          <?php if(osc_item_user_id() > 0 && zet_chat_button(osc_item_user_id())) { ?>
            <div class="line-chat"><?php echo zet_chat_button(osc_item_user_id(), 'btn'); ?></div>
          <?php } ?>

          <?php if(osc_item_user_id() > 0) { ?>
            <div class="line3">
              <?php if($item_user_location != '' && 1==2) { ?>
                <div class="address"><i class="fas fa-map-marked-alt"></i> <?php echo $item_user_location; ?></div>
              <?php } ?>

              <?php if($user_phone_mobile_data['found']) { ?>
                <a class="phone-mobile phone <?php echo $user_phone_mobile_data['class']; ?>" title="<?php echo osc_esc_html($user_phone_mobile_data['title']); ?>" data-prefix="tel" href="<?php echo $user_phone_mobile_data['url']; ?>" data-part1="<?php echo osc_esc_html($user_phone_mobile_data['part1']); ?>" data-part2="<?php echo osc_esc_html($user_phone_mobile_data['part2']); ?>">
                  <i class="fas fa-phone-alt"></i>
                  <span><?php echo $user_phone_mobile_data['masked']; ?></span>
                </a>
              <?php } ?>

              <?php if($user_phone_land_data['found']) { ?>
                <a class="phone-land phone <?php echo $user_phone_land_data['class']; ?>" title="<?php echo osc_esc_html($user_phone_land_data['title']); ?>" data-prefix="tel" href="<?php echo $user_phone_land_data['url']; ?>" data-part1="<?php echo osc_esc_html($user_phone_land_data['part1']); ?>" data-part2="<?php echo osc_esc_html($user_phone_land_data['part2']); ?>">
                  <i class="fas fa-phone-alt"></i>
                  <span><?php echo $user_phone_land_data['masked']; ?></span>
                </a>
              <?php } ?>
            </div>
          <?php } ?>
        </div>


        <?php if(function_exists('sp_buttons')) { ?>
          <div class="sms-payments">
            <?php echo sp_buttons(osc_item_id());?>
          </div>
        <?php } ?>

        <?php if(osc_is_web_user_logged_in() && osc_item_user_id() == osc_logged_user_id()) { ?>
          <div class="manage-delimit"></div>
          
          <?php if(osc_item_is_inactive()) { ?>
            <?php if((function_exists('iv_add_item') && osc_get_preference('enable','plugin-item_validation') <> 1) || !function_exists('iv_add_item')) { ?>
              <a class="manage-button activate" target="_blank" href="<?php echo osc_item_activate_url(); ?>"><?php _e('Validate', 'zeta'); ?></a>
            <?php } ?>
          <?php } ?>
          
          <a class="manage-button edit" href="<?php echo osc_item_edit_url(); ?>"><i class="fas fa-edit"></i> <span><?php _e('Edit', 'zeta'); ?></span></a>
          <a class="manage-button delete" href="<?php echo osc_item_delete_url(); ?>" onclick="return confirm('<?php _e('Are you sure you want to delete this listing? This action cannot be undone.', 'zeta'); ?>?')"><i class="fas fa-trash-alt"></i> <span><?php _e('Remove', 'zeta'); ?></span></a>
        <?php } ?>
      
        <?php echo zet_banner('item_sidebar_bottom'); ?>
        
        <?php osc_run_hook('item_sidebar_bottom'); ?>
      </div>
    </div>
  
    <?php 
      if(zet_param('related') == 1) {
        zet_related_ads('category', zet_param('related_design'), zet_param('related_count'));
      }

      echo zet_banner('item_bottom');
      
      if(zet_param('recent_item') == 1) {
        zet_recent_ads(zet_param('recent_design'), zet_param('recent_count'), 'onitem');
      }
    ?>
  </div>

  <?php osc_run_hook('item_bottom'); ?>


  <div id="item-sticky-box" class="isMobile">
    <?php if($phone_data['found']) { ?>
      <a class="btn-regular tel<?php echo ($phone_data['login_required'] ? ' disabled' : ''); ?>" href="<?php echo ($phone_data['login_required'] ? $phone_data['url'] : 'tel:' . $phone_data['phone']); ?>" title="<?php echo osc_esc_html($phone_data['title']); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20" style="transform:scaleX(-1);"><path d="M476.5 22.9L382.3 1.2c-21.6-5-43.6 6.2-52.3 26.6l-43.5 101.5c-8 18.6-2.6 40.6 13.1 53.4l40 32.7C311 267.8 267.8 311 215.4 339.5l-32.7-40c-12.8-15.7-34.8-21.1-53.4-13.1L27.7 329.9c-20.4 8.7-31.5 30.7-26.6 52.3l21.7 94.2c4.8 20.9 23.2 35.5 44.6 35.5C312.3 512 512 313.7 512 67.5c0-21.4-14.6-39.8-35.5-44.6zM69.3 464l-20.9-90.7 98.2-42.1 55.7 68.1c98.8-46.4 150.6-98 197-197l-68.1-55.7 42.1-98.2L464 69.3C463 286.9 286.9 463 69.3 464z"/></svg>
        <span><?php _e('Call', 'zeta'); ?></span>
      </a>

      <a class="btn-regular sms<?php echo ($phone_data['login_required'] ? ' disabled' : ''); ?>" href="<?php echo ($phone_data['login_required'] ? $phone_data['url'] : 'sms:' . $phone_data['phone']); ?>" title="<?php echo osc_esc_html($phone_data['title']); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M448 0H64C28.7 0 0 28.7 0 64v288c0 35.3 28.7 64 64 64h96v84c0 7.1 5.8 12 12 12 2.4 0 4.9-.7 7.1-2.4L304 416h144c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64zm16 352c0 8.8-7.2 16-16 16H288l-12.8 9.6L208 428v-60H64c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16h384c8.8 0 16 7.2 16 16v288zm-96-216H144c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h224c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16zm-96 96H144c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h128c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16z"/></svg>
        <span><?php _e('Sms', 'zeta'); ?></span>
      </a>
    <?php } ?>
    
    <?php if(osc_item_user_id() > 0 && zet_chat_button(osc_item_user_id())) { ?>
      <?php echo zet_chat_button(osc_item_user_id(), 'btn-regular', true); ?>
    <?php } ?>

    <?php if($email_data['visible']) { ?>
      <a class="btn-regular email" href="mailto:<?php echo $email_data['email']; ?>">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M464 64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zm0 48v40.805c-22.422 18.259-58.168 46.651-134.587 106.49-16.841 13.247-50.201 45.072-73.413 44.701-23.208.375-56.579-31.459-73.413-44.701C106.18 199.465 70.425 171.067 48 152.805V112h416zM48 400V214.398c22.914 18.251 55.409 43.862 104.938 82.646 21.857 17.205 60.134 55.186 103.062 54.955 42.717.231 80.509-37.199 103.053-54.947 49.528-38.783 82.032-64.401 104.947-82.653V400H48z"/></svg>
        <span><?php _e('Email', 'zeta'); ?></span>
      </a>
    <?php } ?>
    
    <a class="btn-regular share" href="#">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="20" height="20"><path d="M561.938 190.06L385.94 14.107C355.79-16.043 304 5.327 304 48.047v80.703C166.04 132.9 0 159.68 0 330.05c0 73.75 38.02 134.719 97.63 173.949 37.12 24.43 85.84-10.9 72.19-54.46C145.47 371.859 157.41 330.2 304 321.66v78.28c0 42.64 51.73 64.15 81.94 33.94l175.997-175.94c18.751-18.74 18.751-49.14.001-67.88zM352 400V272.09c-164.521 1.79-277.44 33.821-227.98 191.61C88 440 48 397.01 48 330.05c0-142.242 160.819-153.39 304-154.02V48l176 176-176 176z"/></svg>
      <span><?php _e('Share', 'zeta'); ?></span>
    </a>

    <?php if(zet_param('messenger_replace_button') == 1 && function_exists('im_contact_button') && im_contact_button(osc_item(), true) !== false) { ?>
      <a href="<?php echo im_contact_button(osc_item(), true); ?>" class="contact btn btn-primary">
        <span><?php _e('Send message', 'zeta'); ?></span>
      </a>
    <?php } else if(getBoolPreference('item_contact_form_disabled') != 1) { ?>
      <a href="<?php echo zet_item_fancy_url('contact'); ?>" class="open-form contact btn btn-primary" data-type="contact">
        <span><?php _e('Send message', 'zeta'); ?></span>
      </a>
    <?php } ?>
  </div>
    
  <div class="share-item-data" style="display:none">
    <a class="whatsapp" href="whatsapp://send?text=<?php echo urlencode(osc_item_url()); ?>" data-action="share/whatsapp/share"><i class="fab fa-whatsapp"></i> <?php _e('Share on Whatsapp', 'zeta'); ?></a></span>
    <a class="facebook" title="<?php echo osc_esc_html(__('Share on Facebook', 'zeta')); ?>" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(osc_item_url()); ?>"><i class="fab fa-facebook"></i> <?php _e('Share on Facebook', 'zeta'); ?></a> 
    <a class="twitter" title="<?php echo osc_esc_html(__('Share on Twitter', 'zeta')); ?>" target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo urlencode(osc_item_title()); ?>&url=<?php echo urlencode(osc_item_url()); ?>"><i class="fab fa-twitter"></i> <?php _e('Share on Twitter', 'zeta'); ?></a> 
    <a class="pinterest" title="<?php echo osc_esc_html(__('Share on Pinterest', 'zeta')); ?>" target="_blank" href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode(osc_item_url()); ?>&media=<?php echo urlencode($resource_url); ?>&description=<?php echo htmlspecialchars(osc_item_title()); ?>"><i class="fab fa-pinterest"></i> <?php _e('Share on Pinterest', 'zeta'); ?></a> 
    <a class="friend open-form" href="<?php echo zet_item_fancy_url('friend'); ?>" data-type="friend"><i class="fas fa-user-friends"></i> <?php _e('Send to friend', 'zeta'); ?></a>
  </div>


  <script type="text/javascript">
    $(document).ready(function(){

      // SHARE BUTTON
      $('.mlink.share, #item-sticky-box .btn-regular.share').on('click', () => {
        if(navigator.share) {
          navigator.share({
            title: '<?php echo osc_esc_js(osc_highlight(osc_item_title(), 40) . ' - ' . osc_item_formated_price()); ?>',
            text: '<?php echo osc_esc_js(osc_highlight(osc_item_title(), 40) . ' - ' . osc_item_formated_price()); ?>',
            url: '<?php echo osc_esc_js(osc_item_url()); ?>',
          }).catch((error) => console.log('ERROR: ', error));
        }
        
        return false;
      });

    });
  </script>

  <?php osc_current_web_theme_path('footer.php'); ?>
</body>
</html>				