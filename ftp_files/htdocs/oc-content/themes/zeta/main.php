<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo zet_language_dir(); ?>" mode="<?php echo zet_light_dark_mode(); ?>" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php'); ?>
  <meta name="robots" content="index, follow" />
  <meta name="googlebot" content="index, follow" />
</head>

<body id="home" class="layout-<?php echo (zet_param('home_layout') <> '' ? zet_param('home_layout') : 'default'); ?> <?php osc_run_hook('body_class'); ?>">
  <?php osc_current_web_theme_path('header.php'); ?>
  
  <?php osc_run_hook('home_top'); ?>

  <?php if(zet_banner('home_top') !== false) { ?>
    <div class="container banner-box<?php if(zet_is_demo()) { ?> is-demo<?php } ?>"><div class="inside"><?php echo zet_banner('home_top'); ?></div></div>
  <?php } ?>
  
  <?php 
    $location_cookie = zet_location_from_cookies(); 
    $conf = zet_home_search_config();
    $new_categories = explode(',', zet_param('categories_new'));
    $hot_categories = explode(',', zet_param('categories_hot'));
  ?>

  <?php osc_run_hook('home_search_pre'); ?>

  <section id="home-search-box">
    <div class="container">
      <div id="main-search">
        <div class="box">
          <h1><?php _e('What would you like to search?', 'zeta'); ?></h1>

          <div class="tabs">
            <?php $j = 0; ?>
            <?php foreach($conf as $cat_id => $fields) { ?>
              <?php 
                if($cat_id == 0) {
                  $category['pk_i_id'] = 0;
                  $category['s_name'] = __('Search in all categories', 'zeta');
                  
                } else {
                  $category = osc_get_category_row($cat_id); 
                }
              ?>
              
              <?php if(isset($category['pk_i_id']) && $category['pk_i_id'] >= 0) { ?>
                <a href="#" data-id="<?php echo $category['pk_i_id']; ?>" class="<?php if($j==0) { ?>active<?php } ?>" data-order="<?php echo $j+1; ?>">
                  <?php 
                    // Use only icons here
                    $icon = zet_get_cat_icon($category['pk_i_id'], $category, true, true);
                    $icon_ = explode(' ', $icon);
                    
                    $has_type = false;
                    if(in_array($icon_, array('fas', 'far', 'fab'))) {
                      $has_type = true;
                    }
                  ?>
                  
                  <i class="<?php echo ($has_type ? '' : 'fas'); ?> <?php echo $icon; ?>"></i>
                  <span><?php echo $category['s_name']; ?></span>
                  
                  <?php if(in_array($cat_id, $hot_categories)) { ?>
                    <span class="label hot"><?php _e('Hot', 'zeta'); ?></span>
                  <?php } else if(in_array($cat_id, $new_categories)) { ?>
                    <span class="label new"><?php _e('New', 'zeta'); ?></span>
                  <?php } ?>
                </a>
                
                <?php $j++; ?>
              <?php } ?>
            <?php } ?>
          </div>


          <?php $j = 0; ?>
          
          <?php foreach($conf as $cat_id => $fields) { ?>
            <?php 
              if($cat_id == 0) {
                $category['pk_i_id'] = 0;
                $category['s_name'] = __('Search in all categories', 'zeta');
                
              } else {
                $category = osc_get_category_row($cat_id); 
              }
              
              if(!is_array($fields) || empty($fields)) {
                $fields = array('category', 'location', 'pattern');
              }
            ?>
            
            <?php if(isset($category['pk_i_id']) && $category['pk_i_id'] >= 0) { ?>
              <div class="tab-data<?php if($j==0) { ?> active<?php } ?>" data-id="<?php echo $category['pk_i_id']; ?>" data-order="<?php echo $j+1; ?>">
                <form action="<?php echo osc_base_url(true); ?>" method="GET" class="nocsrf">
                  <div class="inbox">
                    <input type="hidden" name="page" value="search" />

                    <?php if(!in_array('category', $fields)) { ?>
                      <input type="hidden" name="sCategory" value="<?php echo ($category['pk_i_id'] > 0 ? $category['pk_i_id'] : ''); ?>" />
                    <?php } ?>
                    
                    <?php 
                      $def_cur = (zet_param('def_cur') <> '' ? zet_param('def_cur') : '$');

                      foreach($fields as $field) { 
                        switch($field) {
                          case 'seller': 
                            echo zet_simple_seller();
                            break;

                          case 'transaction': 
                            echo zet_simple_transaction();
                            break;

                          case 'condition': 
                            echo zet_simple_condition();
                            break;
                            
                          case 'radius': 
                            echo zet_simple_radius();
                            break;
                            
                          case 'location': 
                            zet_advanced_location();
                            break;
                            
                          case 'pattern': 
                            zet_advanced_pattern('simple'); 
                            break;
                            
                          case 'category': 
                            echo zet_simple_category(false, 2, 'sCategory', $category['pk_i_id']); 
                            break;
                            
                          case 'price_min':
                            ?>
                            <div class="input-box price min has-icon">
                              <div class="icon-curr"><?php echo $def_cur; ?></div>
                              <input type="number" class="priceMin" name="sPriceMin" value="<?php echo osc_esc_html(Params::getParam('sPriceMin')); ?>" size="6" maxlength="6" placeholder="<?php echo osc_esc_html(__('Min', 'zeta')); ?>"/>
                            </div>
                            <?php
                            break;
                            
                          case 'price_max':
                            ?>
                            <div class="input-box price max has-icon">
                              <div class="icon-curr"><?php echo $def_cur; ?></div>
                              <input type="number" class="priceMax" name="sPriceMax" value="<?php echo osc_esc_html(Params::getParam('sPriceMax')); ?>" size="6" maxlength="6" placeholder="<?php echo osc_esc_html(__('Max', 'zeta')); ?>"/>
                            </div>
                            <?php
                            break;
                            
                        }
                      
                      }
                    ?>

                    <div class="btns">
                      <button class="btn btn-secondary" type="submit"><?php _e('Search', 'zeta'); ?></button>
                    </div>
                  </div>

                  <?php osc_run_hook('home_search_top', $category['pk_i_id']); ?>
                  <?php osc_run_hook('home_search_bottom', $category['pk_i_id']); ?>
                </form>              
              </div>
              
              <?php $j++; ?>
            <?php } ?>
          <?php } ?>


          <?php 
            zet_get_latest_searches(12); 
            $i = 0;
          ?>

          <?php if(osc_count_latest_searches() > 0) { ?>
            <div class="latest-search">
              <strong><?php _e('Trending', 'zeta'); ?>:</strong>
              <?php while(osc_has_latest_searches()) { ?>
                <a href="<?php echo osc_search_url(array('page' => 'search', 'sPattern' => osc_esc_html(osc_latest_search_text()))); ?>" data-text="<?php echo osc_esc_html(osc_latest_search_text()); ?>"><?php echo osc_highlight(osc_latest_search_text(), 18); ?></a><?php if($i < osc_count_latest_searches() - 1) { echo ', '; } ?>
                <?php $i++; ?>
              <?php } ?>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </section>


  <section class="home-action">
    <div class="container">
      <div class="head-line">
        <div class="h-img"></div>

        <div class="h-text">
          <h2>
            <?php 
              if(osc_is_web_user_logged_in()) {
                echo sprintf(__('Hello, %s!', 'zeta'), '<u>' . osc_logged_user_name() . '</u>'); 
              } else {
                echo sprintf(__('Welcome to %s!', 'zeta'), zet_param('site_name')); 
              }
            ?>
          </h2>
          <div class="row"><?php _e('Thank you for using our classifieds website.', 'zeta'); ?></div>
          <strong class="row"><?php _e('What service would you like to use today?', 'zeta'); ?></strong>
        </div>
      </div>
      
      <div class="actions">
        <a href="<?php echo osc_item_post_url(); ?>" class="a-pub">
          <i class="fas fa-plus-circle"></i>
          <span><?php _e('Add a new item', 'zeta'); ?></span>
        </a>
        
        <a href="<?php echo osc_search_url(array('page' => 'search')); ?>" class="a-src">
          <i class="fas fa-search"></i>
          <span><?php _e('Browse listings', 'zeta'); ?></span>
        </a>

        <a href="<?php echo (osc_is_web_user_logged_in() ? osc_user_dashboard_url() : osc_user_login_url()); ?>" class="a-act">
          <i class="fas fa-user-edit"></i>
          <span><?php _e('Manage account', 'zeta'); ?></span>
        </a>
        
        <?php if(function_exists('bpr_companies_url')) { ?>
          <a href="<?php echo bpr_companies_url(); ?>" class="a-bpr">
            <i class="fas fa-briefcase"></i>
            <span><?php _e('Business users', 'zeta'); ?></span>
          </a>
        <?php } ?>

        <?php if(function_exists('frm_home')) { ?>
          <a href="<?php echo frm_home(); ?>" class="a-frm">
            <i class="far fa-comments"></i>
            <span><?php _e('Explore forums', 'zeta'); ?></span>
          </a>
        <?php } ?>

        <?php if(function_exists('blg_home_link')) { ?>
          <a href="<?php echo blg_home_link(); ?>" class="a-blg">
            <i class="fas fa-book"></i>
            <span><?php _e('Read blog', 'zeta'); ?></span>
          </a>
        <?php } ?>
        
        <?php if(function_exists('faq_home_link')) { ?>
          <a href="<?php echo faq_home_link(); ?>" class="a-faq">
            <i class="fas fa-life-ring"></i>
            <span><?php _e('FAQ', 'zeta'); ?></span>
          </a>
        <?php } ?>
      </div>
    </div>
  </section>
  

  <?php 
    $has_day_offer = 0;
    if(zet_param('enable_day_offer') == 1 && zet_param('day_offer_id') > 0) {
      $day_offer = Item::newInstance()->findByPrimaryKey(zet_param('day_offer_id'));
      
      if($day_offer !== false && isset($day_offer['pk_i_id'])) {
        $has_day_offer = 1;
      }
    }

    //osc_get_premiums(zet_param('premium_home_count') - $has_day_offer); 
    $premium_items = zet_premium_items(zet_param('premium_home_count') - $has_day_offer, @$day_offer['pk_i_id']);
  ?>

  <?php if(zet_param('premium_home') == 1 && $premium_items > 0) { ?>
    <?php
      $default_items = View::newInstance()->_get('items'); 
      View::newInstance()->_exportVariableToView('items', $premium_items);
    ?>
    <section class="home-premium">
      <div class="container">
        <div class="block">
          <h2 class="spec">
            <strong><?php _e('Premium listings', 'zeta'); ?></strong>
            <span><?php _e('Hand-picked exclusive selection of offers', 'zeta'); ?></span>
          </h2>

          <div class="nice-scroll-wrap">
            <div class="nice-scroll-prev"><?php echo ICON_SCROLL_PREV; ?></div>
            
            <div id="premium-items" class="products grid nice-scroll no-visible-scroll">
              <?php 
                $c = 1; 

                if($has_day_offer == 1) {
                  View::newInstance()->_exportVariableToView('item', $day_offer);
                  zet_draw_item($c, false, zet_param('premium_home_design'));
                  $c++;
                }
                
                while(osc_has_items()) {
                  zet_draw_item($c, false, zet_param('premium_home_design'));
                  $c++;
                }
              ?>
            </div>
            
            <div class="nice-scroll-next"><?php echo ICON_SCROLL_NEXT; ?></div>
          </div>
        </div>
      </div>
    </section>
    
    <?php View::newInstance()->_exportVariableToView('items', $default_items); ?>
  <?php } ?>

  <?php osc_run_hook('home_premium'); ?>



  <section class="home-cat">
    <div class="container">
      <h2>
        <strong><?php _e('Browse categories', 'zeta'); ?></strong>
        <span><?php _e('Select a category you are interested in', 'zeta'); ?></span>
      </h2>

      <?php 
        osc_goto_first_category(); 
        $new_categories = explode(',', zet_param('categories_new'));
        $hot_categories = explode(',', zet_param('categories_hot'));
        $home_cat_rows = (zet_param('home_cat_rows') > 1 ? (int)zet_param('home_cat_rows') : 1);
        $cols = floor(osc_count_categories()/$home_cat_rows);
      ?>
      
      <div id="home-cat" class="nice-scroll" data-cols="<?php echo $cols; ?>">
        <?php while(osc_has_categories()) { ?>
          <?php $color = zet_get_cat_color(osc_category_id(), osc_category()); ?>
   
          <a href="<?php echo osc_search_url(array('page' => 'search', 'sCategory' => osc_category_id())); ?>" data-id="<?php echo osc_category_id(); ?>" <?php if($color <> '') { ?>style="border-bottom-color:<?php echo $color; ?>;"<?php } ?>>
            <div>
              <?php if(in_array(osc_category_id(), $new_categories)) { ?>
                <span class="lab new"><?php _e('New', 'zeta'); ?></span>
              <?php } else if(in_array(osc_category_id(), $hot_categories)) { ?>
                <span class="lab hot"><?php _e('Hot', 'zeta'); ?></span>
              <?php } ?>
              
              <?php if(zet_param('cat_icons') == 1) { ?>
                <?php 
                  $icon = zet_get_cat_icon(osc_category_id(), osc_category(), true);
                  $icon_ = explode(' ', $icon);
                  
                  $has_type = false;
                  if(in_array($icon_, array('fas', 'far', 'fab'))) {
                    $has_type = true;
                  }
                ?>
                <i class="<?php echo ($has_type ? '' : 'fas'); ?> <?php echo $icon; ?>" <?php if($color <> '') { ?>style="color:<?php echo $color; ?>;"<?php } ?>></i>
              <?php } else { ?>
                <img src="<?php echo zet_get_cat_image(osc_category_id()); ?>" alt="<?php echo osc_esc_html(osc_category_name()); ?>" class="<?php echo (zet_is_lazy() ? 'lazy' : ''); ?>"/>
              <?php } ?>
            </div>

            <h3><span><?php echo osc_category_name(); ?></span></h3>
          </a>
        <?php } ?>
      </div>
      
      <div class="home-link-all-wrap">
        <a href="<?php echo osc_search_url(array('page' => 'search')); ?>" class="home-link-all"><?php _e('Search in all categories', 'zeta'); ?></a>
      </div>
    </div>
  </section>

  <?php osc_run_hook('home_search_after'); ?>

  <?php if(zet_param('location_home') == 1 && $location_cookie['success'] === true) { ?>
    <?php
      $default_items = View::newInstance()->_get('items'); 
      View::newInstance()->_exportVariableToView('items', zet_location_items($location_cookie));
    ?>
    
    <section class="home-location">
      <div class="container">
        <div class="block">
          <h2>
            <span><?php echo sprintf(__('Listings near <u>%s</u>', 'zeta'), osc_location_native_name_selector($location_cookie, 's_name')); ?></span>
            <a href="#" class="change-location nobtn">
              <i class="fas fa-edit"></i>
              <?php _e('Change location', 'zeta'); ?>
            </a>  
          </h2>

          <?php if(osc_count_items() > 0) { ?>
            <div class="nice-scroll-wrap">
              <div class="nice-scroll-prev"><?php echo ICON_SCROLL_PREV; ?></div>
              
              <div id="location-items" class="products grid nice-scroll style2">
                <?php 
                  $c = 1; 
                  
                  while(osc_has_items()) {
                    zet_draw_item($c, false, 'tall ' . zet_param('loc_design'));
                    $c++;
                  }
                  
                  View::newInstance()->_erase('items');
                ?>
              </div>
              
              <div class="nice-scroll-next"><?php echo ICON_SCROLL_NEXT; ?></div>
            </div>
          <?php } else { ?>
            <div class="empty-alt"><?php _e('No listings found close to your location', 'zeta'); ?></div>
          <?php } ?>
        </div>
      </div>
    </section>
    
    <?php View::newInstance()->_exportVariableToView('items', $default_items); ?>
  <?php } ?>
  


  <?php if(function_exists('blg_param') && zet_param('blog_home') == 1) { ?>
    <?php $blogs = ModelBLG::newInstance()->getActiveBlogs(); ?>

    <?php if(is_array($blogs) && count($blogs) > 0) { ?>
      <?php $i = 1; ?>
      <?php $blog_limit = zet_param('blog_home_count'); ?>

      <section class="home-blog">
        <div class="container">
          <div class="block">
            <h2><?php _e('News on blog', 'zeta'); ?></h2>

            <div class="blog-box <?php echo (zet_param('blog_home_design') <> 'grid' ? 'list' : 'grid'); ?>">
              <?php foreach($blogs as $b) { ?>
                <?php if($i <= $blog_limit) { ?>
                  <a href="<?php echo osc_route_url('blg-post', array('blogSlug' => osc_sanitizeString(blg_get_slug($b)), 'blogId' => $b['pk_i_id'])); ?>">
                    <img src="<?php echo blg_img_link($b['s_image']); ?>" alt="<?php echo osc_esc_html(strip_tags(blg_get_title($b))); ?>"/>

                    <div class="data">
                      <h3><?php echo strip_tags(blg_get_title($b)); ?></h3>
                      <div class="desc"><?php echo strip_tags(osc_highlight(blg_get_subtitle($b) <> '' ? blg_get_subtitle($b) : blg_get_description($b), 240)); ?></div>
                      <div class="read"><?php _e('Read article', 'zeta'); ?> &#8594;</div>
                    </div>
                  </a>
                <?php } ?>

                <?php $i++; ?>
              <?php } ?>
            </div>
            
            <div class="home-link-all-wrap">
              <a href="<?php echo blg_home_link(); ?>" class="home-link-all"><?php _e('Read more articles', 'zeta'); ?></a>
            </div>
          </div>
        </div>
      </section>
    <?php } ?>
  <?php } ?>

  <?php if(zet_banner('home_middle') !== false) { ?>
    <div class="container banner-box<?php if(zet_is_demo()) { ?> is-demo<?php } ?>"><div class="inside"><?php echo zet_banner('home_middle'); ?></div></div>
  <?php } ?>
  
  <?php if(function_exists('fi_most_favorited_items') && zet_param('favorite_home') == 1) { ?>
    <?php $favorite_items = zet_favorited_items(8); ?>
    
    <?php if(count($favorite_items) > 0) { ?>
      <?php
        $default_items = View::newInstance()->_get('items'); 
        View::newInstance()->_exportVariableToView('items', $favorite_items);
      ?>
      
      <section class="home-favorite">
        <div class="container">
          <div class="block">
            <h2>
              <span><?php _e('Most favorited listings', 'zeta'); ?></span>
              
              <a href="<?php echo osc_route_url('favorite-lists'); ?>" class="nobtn">
                <i class="fas fa-edit"></i>
                <?php _e('Manage favorites', 'zeta'); ?> (<?php echo zet_count_favorite(); ?>)
              </a>
            </h2>

            <div id="favorite-items" class="products grid style3 with-border">
              <?php 
                $c = 1; 
                
                while(osc_has_items()) {
                  zet_draw_item($c, false, zet_param('favorite_design'));
                  $c++;
                }
              ?>
            </div>
          </div>
        </div>
      </section>
      
      <?php View::newInstance()->_exportVariableToView('items', $default_items); ?>
    <?php } ?>
  <?php } ?>
  

  <?php if(function_exists('bpr_companies_block') && zet_param('company_home') == 1) { ?>
    <?php $sellers = ModelBPR::newInstance()->getSellers(1, -1, -1, 10, '', '', '', 'NEW'); ?>
    
    <?php if(is_array($sellers) && count($sellers) > 0) { ?>
      <section class="home-business">
        <div class="container">
          <div class="block">
            <h2>
              <span><?php _e('Recommended companies', 'zeta'); ?></span>

              <a href="<?php echo osc_route_url('bpr-list'); ?>" class="btn btn-secondary mini">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="18" height="18"><path d="M464 128h-80V80c0-26.51-21.49-48-48-48H176c-26.51 0-48 21.49-48 48v48H48c-26.51 0-48 21.49-48 48v256c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V176c0-26.51-21.49-48-48-48zM176 80h160v48H176V80zM54 176h404c3.31 0 6 2.69 6 6v74H48v-74c0-3.31 2.69-6 6-6zm404 256H54c-3.31 0-6-2.69-6-6V304h144v24c0 13.25 10.75 24 24 24h80c13.25 0 24-10.75 24-24v-24h144v122c0 3.31-2.69 6-6 6z"></path></svg>
                <?php _e('Explore companies', 'zeta'); ?>
              </a>
            </h2>
            
            <div class="business-box">
              <?php echo bpr_companies_block(zet_param('company_home_count'), 'NEW'); ?>
            </div>
          </div>
        </div>
      </section>
    <?php } ?>
  <?php } ?>  
  


  <?php View::newInstance()->_exportVariableToView('latestItems', zet_random_items()); ?>
  
  <?php if(osc_count_latest_items() > 0) { ?>
    <section class="home-latest">
      <div class="container">
        <div class="block">
          <h2><?php _e('Latest listings', 'zeta'); ?></h2>

          <div id="latest-items" class="products grid style4 with-border-mobile">
            <?php 
              $c = 1; 
              
              while(osc_has_latest_items()) {
                zet_draw_item($c, false, 'medium ' . zet_param('latest_design'));
                $c++;
              }
            ?>
          </div>
        </div>
        
        <div class="home-link-all-wrap">
          <a href="<?php echo osc_search_url(array('page' => 'search')); ?>" class="home-link-all"><?php _e('More latest items', 'zeta'); ?></a>
        </div>
      </div>
    </section>
  <?php } ?>
  
  <?php osc_run_hook('home_latest'); ?>


  <?php if(zet_param('recent_home') == 1) { ?>
    <?php $recent_items = zet_recent_ads(zet_param('recent_design'), zet_param('recent_count'), 'onhome', true); ?>
    
    <?php if(is_array($recent_items) && count($recent_items) > 0) { ?>
      <?php
        $default_items = View::newInstance()->_get('items'); 
        View::newInstance()->_exportVariableToView('items', $recent_items);
      ?>
      
      <section class="home-recent">
        <div class="container">
          <div id="recent-ads" class="block onhome">
            <h2>
              <span><?php _e('Recently viewed listings', 'zeta'); ?></span>
            </h2>

            <div class="nice-scroll-wrap">
              <div class="nice-scroll-prev"><?php echo ICON_SCROLL_PREV; ?></div>
              
              <div id="recent-items" class="products grid nice-scroll no-visible-scroll style1">
                <?php 
                  $c = 1; 
                  
                  while(osc_has_items()) {
                    zet_draw_item($c, false, zet_param('recent_design'));
                    $c++;
                  }
                ?>
              </div>
              
              <div class="nice-scroll-next"><?php echo ICON_SCROLL_NEXT; ?></div>
            </div>
          </div>
        </div>
      </section>
      
      <?php View::newInstance()->_exportVariableToView('items', $default_items); ?>
    <?php } ?>
  <?php } ?>



  <?php if(zet_banner('home_bottom') !== false) { ?>
    <div class="container banner-box<?php if(zet_is_demo()) { ?> is-demo<?php } ?>"><div class="inside"><?php echo zet_banner('home_bottom'); ?></div></div>
  <?php } ?>
  
  <?php osc_run_hook('home_bottom'); ?>

  <?php osc_current_web_theme_path('footer.php'); ?>
</body>
</html>	