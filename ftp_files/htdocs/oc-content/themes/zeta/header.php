<?php 
  osc_goto_first_locale(); 
  
  $loc = (osc_get_osclass_location() == '' ? 'home' : osc_get_osclass_location());
  $sec = (osc_get_osclass_section() == '' ? 'default' : osc_get_osclass_section());
  
  $location_cookie = zet_location_from_cookies();

  $mes_counter = zet_count_messages(osc_logged_user_id()); 
  $fav_counter = zet_count_favorite();
  $alert_counter = zet_count_alerts();
  
  // Get item "back" url
  if(osc_is_ad_page()) {
    $item_search_url = osc_search_url(array('page' => 'search', 'sCategory' => osc_item_category_id(), 'sCountry' => osc_item_country_code(), 'sRegion' => osc_item_region_id(), 'sCity' => osc_item_city_id()));

    if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '' && strpos($_SERVER['HTTP_REFERER'], osc_base_url()) !== false) {
      $item_search_url = false;
    }
  }
?>
<header>
  <?php osc_run_hook('header_top'); ?>
  
  <div class="container cmain">
    <?php if(osc_is_ad_page()) { ?>
      <?php if($item_search_url !== false) { ?>
        <a href="<?php echo $item_search_url; ?>" class="back isMobile"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20"><path d="M229.9 473.899l19.799-19.799c4.686-4.686 4.686-12.284 0-16.971L94.569 282H436c6.627 0 12-5.373 12-12v-28c0-6.627-5.373-12-12-12H94.569l155.13-155.13c4.686-4.686 4.686-12.284 0-16.971L229.9 38.101c-4.686-4.686-12.284-4.686-16.971 0L3.515 247.515c-4.686 4.686-4.686 12.284 0 16.971L212.929 473.9c4.686 4.686 12.284 4.686 16.971-.001z"></path></svg></a>
      <?php } else { ?>
        <a href="#" onclick="history.back();return false;" class="back isMobile"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20"><path d="M229.9 473.899l19.799-19.799c4.686-4.686 4.686-12.284 0-16.971L94.569 282H436c6.627 0 12-5.373 12-12v-28c0-6.627-5.373-12-12-12H94.569l155.13-155.13c4.686-4.686 4.686-12.284 0-16.971L229.9 38.101c-4.686-4.686-12.284-4.686-16.971 0L3.515 247.515c-4.686 4.686-4.686 12.284 0 16.971L212.929 473.9c4.686 4.686 12.284 4.686 16.971-.001z"></path></svg></a>
      <?php } ?>
    <?php } ?>

    <a href="<?php echo osc_base_url(); ?>" class="logo normal light"><?php echo zet_logo(); ?></a>
    <a href="<?php echo osc_base_url(); ?>" class="logo square light"><?php echo zet_logo(false, true); ?></a>
    
    <?php if(zet_param('enable_dark_mode') == 1) { ?>
      <a href="<?php echo osc_base_url(); ?>" class="logo normal dark"><?php echo zet_logo(false, false, true); ?></a>
      <a href="<?php echo osc_base_url(); ?>" class="logo square dark"><?php echo zet_logo(false, true, true); ?></a>
    <?php } ?>

    <div class="csearch-init isMobile">
      <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z"></path></svg>
      <input type="text" class="search-init" placeholder="<?php echo osc_esc_html(__('Search for anything', 'zeta')); ?>" value="" autocomplete="off"/>
    </div>

    <div class="links1">
      <?php osc_run_hook('header_links'); ?>
    </div>


    <div class="links">
      <a class="publish btn" href="<?php echo osc_item_post_url(); ?>"><?php _e('Sell', 'zeta'); ?></a>

      <?php if(zet_param('default_location') == 1) { ?>
        <a class="location mini-btn<?php echo ($location_cookie['success'] === true ? ' active' : ''); ?>" href="#">
            <i class="far fa-compass"></i>
            <strong>
            <?php 
              if(@$location_cookie['success'] !== true) {
                _e('Location', 'zeta');
              } else {
                echo @$location_cookie['s_location'] <> '' ? osc_location_native_name_selector($location_cookie, 's_name') : __('Location', 'zeta');
              }
            ?>
          </strong>
          
          <?php if($location_cookie['success'] === true) { ?>
            <span class="counter spec"><i class="fas fa-check"></i></span>
          <?php } ?>
        </a>
      <?php } ?>

      <a class="alerts mini-btn" href="<?php echo osc_user_alerts_url(); ?>">
        <i class="far fa-bell"></i>
        <strong><?php _e('Alerts', 'zeta'); ?></strong>

        <?php if($alert_counter > 0) { ?>
          <span class="counter"><?php echo $alert_counter; ?></span>
        <?php } ?>
      </a>


      <?php if(osc_is_web_user_logged_in()) { ?>
        <?php if(function_exists('im_messages')) { ?>
          <a class="messages mini-btn" href="<?php echo osc_route_url('im-threads'); ?>">
            <i class="far fa-comment"></i>
            <strong><?php _e('Messages', 'zeta'); ?></strong>

            <?php if($mes_counter > 0) { ?>
              <span class="counter"><?php echo $mes_counter; ?></span>
            <?php } ?>
          </a>
        <?php } ?>

        <?php if(function_exists('fi_make_favorite')) { ?>
          <a class="favorite mini-btn" href="<?php echo osc_route_url('favorite-lists'); ?>">
            <i class="far fa-heart"></i>
            <strong><?php _e('Favorite', 'zeta'); ?></strong>

            <?php if($fav_counter > 0) { ?>
              <span class="counter"><?php echo $fav_counter; ?></span>
            <?php } ?>
          </a>
        <?php } ?>

      <?php } ?>
      

      <?php if(!osc_is_web_user_logged_in()) { ?>
        <a class="simple register" href="<?php echo osc_register_account_url(); ?>"><?php _e('Register', 'zeta'); ?></a>
        <a class="simple login" href="<?php echo osc_user_login_url(); ?>"><?php _e('Login', 'zeta'); ?></a>

      <?php } else { ?>
        <div class="my-account-wrap">
          <a class="simple account" href="<?php echo osc_user_dashboard_url(); ?>">
            <img src="<?php echo zet_profile_picture(osc_logged_user_id(), 'small'); ?>" alt="<?php echo osc_esc_html(osc_logged_user_name()); ?>" width="24" height="24"/>
            <?php echo sprintf(__('Hi, <b>%s</b>!', 'zeta'), zet_user_first_name(osc_logged_user_name())); ?>
          </a>
          
          <div class="my-account-menu">
            <a href="<?php echo osc_user_dashboard_url(); ?>"><i class="fas fa-columns"></i> <?php _e('Dashboard', 'zeta'); ?></a>
            <a href="<?php echo osc_user_list_items_url(); ?>"><i class="fas fa-list"></i> <?php _e('My items', 'zeta'); ?></a>
            <a href="<?php echo osc_user_profile_url(); ?>"><i class="fas fa-edit"></i> <?php _e('My profile', 'zeta'); ?></a>
            <a href="<?php echo osc_user_public_profile_url(); ?>"><i class="fas fa-id-card-alt"></i> <?php _e('Public profile', 'zeta'); ?></a>
            <a href="<?php echo osc_user_logout_url(); ?>"><i class="fas fa-sign-out-alt"></i> <?php _e('Logout', 'zeta'); ?></a>
          </div>
        </div>

        
        <a class="search btn btn-white isMobile" href="<?php echo osc_search_page(array('page' => 'search')); ?>">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="18" height="18"><path d="M500.3 443.7l-119.7-119.7c27.22-40.41 40.65-90.9 33.46-144.7C401.8 87.79 326.8 13.32 235.2 1.723C99.01-15.51-15.51 99.01 1.724 235.2c11.6 91.64 86.08 166.7 177.6 178.9c53.8 7.189 104.3-6.236 144.7-33.46l119.7 119.7c15.62 15.62 40.95 15.62 56.57 0C515.9 484.7 515.9 459.3 500.3 443.7zM79.1 208c0-70.58 57.42-128 128-128s128 57.42 128 128c0 70.58-57.42 128-128 128S79.1 278.6 79.1 208z"/></svg>
        </a>

      <?php } ?>
      
    </div>
    
    <div class="menu-links isMobile">
      <a class="account" href="<?php echo (osc_is_web_user_logged_in() ? osc_user_dashboard_url() : osc_user_login_url()); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="28" height="28"><path d="M384 336c-40.6 0-47.6-1.5-72.2 6.8-17.5 5.9-36.3 9.2-55.8 9.2s-38.3-3.3-55.8-9.2c-24.6-8.3-31.5-6.8-72.2-6.8C57.3 336 0 393.3 0 464v16c0 17.7 14.3 32 32 32h448c17.7 0 32-14.3 32-32v-16c0-70.7-57.3-128-128-128zm80 128H48c0-21.4 8.3-41.5 23.4-56.6C86.5 392.3 106.6 384 128 384c41.1 0 41-1.1 56.8 4.2 23 7.8 47 11.8 71.2 11.8 24.2 0 48.2-4 71.2-11.8 15.8-5.4 15.7-4.2 56.8-4.2 44.1 0 80 35.9 80 80zM256 320c88.4 0 160-71.6 160-160S344.4 0 256 0 96 71.6 96 160s71.6 160 160 160zm0-272c61.8 0 112 50.2 112 112s-50.2 112-112 112-112-50.2-112-112S194.2 48 256 48z"/></svg>
      </a>
      
      <a class="menu" href="#">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="28" height="28"><path d="M442 114H6a6 6 0 0 1-6-6V84a6 6 0 0 1 6-6h436a6 6 0 0 1 6 6v24a6 6 0 0 1-6 6zm0 160H6a6 6 0 0 1-6-6v-24a6 6 0 0 1 6-6h436a6 6 0 0 1 6 6v24a6 6 0 0 1-6 6zm0 160H6a6 6 0 0 1-6-6v-24a6 6 0 0 1 6-6h436a6 6 0 0 1 6 6v24a6 6 0 0 1-6 6z"/></svg>
      </a>
    </div>    
  </div>


  <div class="container alt csearch" style="display:none;">
    <a href="#" class="back">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20"><path d="M229.9 473.899l19.799-19.799c4.686-4.686 4.686-12.284 0-16.971L94.569 282H436c6.627 0 12-5.373 12-12v-28c0-6.627-5.373-12-12-12H94.569l155.13-155.13c4.686-4.686 4.686-12.284 0-16.971L229.9 38.101c-4.686-4.686-12.284-4.686-16.971 0L3.515 247.515c-4.686 4.686-4.686 12.284 0 16.971L212.929 473.9c4.686 4.686 12.284 4.686 16.971-.001z"/></svg>
    </a>
    
    <form action="<?php echo osc_base_url(true); ?>" method="GET" class="nocsrf">
      <input type="hidden" name="page" value="search" />

      <div class="picker v2 pattern mobile">
        <div class="input-box">
          <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z"></path></svg>
          <input type="text" name="sPattern" class="pattern" placeholder="<?php _e('Search for anything', 'zeta'); ?>" value="<?php echo osc_esc_html(Params::getParam('sPattern')); ?>" autocomplete="off"/>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="clean mob" width="20" height="20"><path d="M207.6 256l107.72-107.72c6.23-6.23 6.23-16.34 0-22.58l-25.03-25.03c-6.23-6.23-16.34-6.23-22.58 0L160 208.4 52.28 100.68c-6.23-6.23-16.34-6.23-22.58 0L4.68 125.7c-6.23 6.23-6.23 16.34 0 22.58L112.4 256 4.68 363.72c-6.23 6.23-6.23 16.34 0 22.58l25.03 25.03c6.23 6.23 16.34 6.23 22.58 0L160 303.6l107.72 107.72c6.23 6.23 16.34 6.23 22.58 0l25.03-25.03c6.23-6.23 6.23-16.34 0-22.58L207.6 256z"/></svg>
        </div>
        
        <div class="results">
          <div class="loaded"></div>
          <div class="default"><?php echo zet_default_pattern_content(); ?></div>
        </div>
      </div>
      
      <button class="btn" type="submit"><i class="fa fa-search"></i></button>
    </form>
  </div>
  
  <?php if(osc_is_ad_page()) { ?>
    <?php $item_extra = zet_item_extra(osc_item_id()); ?>
    
    <div class="container alt citem hidden" style="display:none;">
      <!--<a href="#" class="back btn btn-white" onclick="history.back();"><i class="fas fa-chevron-left"></i></a>-->
      <img class="img" src="<?php echo (osc_count_item_resources() > 0 ? osc_resource_thumbnail_url() : zet_get_noimage()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" />
      
      <div class="data">
        <strong>
          <span class="title"><?php echo osc_item_title(); ?></span>
          <span class="price"><?php echo osc_item_formated_price(); ?></span>
        </strong>
        
        <div>
          <?php if(!in_array(osc_item_category_id(), zet_extra_fields_hide())) { ?>
            <?php if(zet_get_simple_name($item_extra['i_condition'], 'condition', false) <> '') { ?>
              <span><?php echo zet_get_simple_name($item_extra['i_condition'], 'condition', false); ?></span>
            <?php } ?>

            <?php if(zet_get_simple_name($item_extra['i_transaction'], 'transaction', false) <> '') { ?>
              <span><?php echo zet_get_simple_name($item_extra['i_transaction'], 'transaction', false); ?></span>
            <?php } ?>          
          <?php } ?>
          
          <span><?php echo osc_item_category(); ?></span>
          <span><?php echo zet_item_location(); ?></span>
          <span><?php echo sprintf(__('Id #%s', 'zeta'), osc_item_id()) ?></span>
          <span><?php echo sprintf(__('Posted %s', 'zeta'), zet_smart_date(osc_item_pub_date())); ?></span>
        </div>
      </div>
    </div>
  <?php } ?>
  
  <?php if(osc_is_search_page()) { ?>
    <div class="container alt cresults hidden" style="display:none;">
      <a href="#" class="action open-filters btn btn-white"><i class="fas fa-sliders-h"></i></a>

      <div class="data">
        <strong><?php echo sprintf(__('%s results found', 'zeta'), osc_search_total_items()); ?></strong>
        <div class="filts">
          <?php $params = zet_search_param_remove(); ?>
          <?php if(is_array($params) && count($params) > 0) { ?>
            <?php foreach($params as $p) { ?>
              <?php if(trim((string)$p['name']) != '') { ?>
                <span><?php echo $p['name']; ?></span>
              <?php } ?>
            <?php } ?>
          <?php } ?>
        </div>
      </div>
    </div>
  <?php } ?>
  
  <?php osc_run_hook('header_bottom'); ?>
</header>

<div id="header-search">
  <form action="<?php echo osc_base_url(true); ?>" method="GET" class="nocsrf container">
    <input type="hidden" name="page" value="search" />

    <?php zet_advanced_pattern(); ?>
    <?php zet_advanced_location(); ?>
    <?php echo zet_simple_radius(); ?>
    
    <div class="btns">
      <button class="btn btn-secondary" type="submit"><?php _e('Search', 'zeta'); ?></button>
    </div>
  </form>
</div>

<?php osc_run_hook('header_after'); ?>

<?php
  osc_show_widgets('header');
  $breadcrumb = osc_breadcrumb('>', false);
  $breadcrumb = str_replace('<span itemprop="title">' . osc_page_title() . '</span>', '<span itemprop="title" class="home"><span>' . __('Home', 'zeta') . '</span></span>', $breadcrumb);
  $breadcrumb = str_replace('<span itemprop="name">' . osc_page_title() . '</span>', '<span itemprop="name" class="home"><span>' . __('Home', 'zeta') . '</span></span>', $breadcrumb);

  if(osc_is_ad_page()) {
    $breadcrumb = str_replace('<span itemprop="name">' . osc_item_title() . '</span>', '<span itemprop="name">' . osc_item_title() . ', <b>' . osc_item_formated_price() . '</b></span>', $breadcrumb);
  }
?>

<div class="content loc-<?php echo $loc; ?> sec-<?php echo $sec; ?><?php if($breadcrumb == '') { ?> no-breadcrumbs<?php } ?>">

<?php if($breadcrumb != '') { ?>
  <div id="breadcrumbs" class="container">
    <div class="bread-text"><?php echo $breadcrumb; ?></div>
    
    <?php if(osc_is_ad_page()) { ?>
      <?php
        $next_link = zet_next_prev_item('next', osc_item_category_id(), osc_item_id());
        $prev_link = zet_next_prev_item('prev', osc_item_category_id(), osc_item_id());
      ?>
      
      <div class="navlinks">
        <?php if($prev_link !== false) { ?><a href="<?php echo $prev_link; ?>" class="prev"><i class="fas fa-angle-left"></i> <?php _e('Previous', 'zeta'); ?></a><?php } ?>
        <?php if($next_link !== false) { ?><a href="<?php echo $next_link; ?>" class="next"><?php _e('Next', 'zeta'); ?> <i class="fas fa-angle-right"></i></a><?php } ?>
      </div>
    <?php } else if(osc_get_osclass_location() == 'user' && osc_get_osclass_section() == 'pub_profile') { ?>
      <?php
        $next_link = zet_next_prev_user('next', osc_user_id());
        $prev_link = zet_next_prev_user('prev', osc_user_id());
      ?>
      
      <div class="navlinks">
        <?php if($prev_link !== false) { ?><a href="<?php echo $prev_link; ?>" class="prev"><i class="fas fa-angle-left"></i> <?php _e('Previous', 'zeta'); ?></a><?php } ?>
        <?php if($next_link !== false) { ?><a href="<?php echo $next_link; ?>" class="next"><?php _e('Next', 'zeta'); ?> <i class="fas fa-angle-right"></i></a><?php } ?>
      </div>
    <?php } ?>
  </div>
<?php } ?>

<div id="flashbox" class="container"><div class="wrap"><div class="wrap2"><?php osc_show_flash_message(); ?></div></div></div>