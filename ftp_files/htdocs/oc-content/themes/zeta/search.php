<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo zet_language_dir(); ?>" mode="<?php echo zet_light_dark_mode(); ?>" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php'); ?>
  <?php if(osc_count_items() == 0 || Params::getParam('iPage') > 0 || stripos($_SERVER['REQUEST_URI'], 'search'))  { ?>
    <meta name="robots" content="noindex, nofollow" />
    <meta name="googlebot" content="noindex, nofollow" />
  <?php } else { ?>
    <meta name="robots" content="index, follow" />
    <meta name="googlebot" content="index, follow" />
  <?php } ?>
</head>

<body id="search" class="<?php osc_run_hook('body_class'); ?>">
<?php osc_current_web_theme_path('header.php'); ?>

<?php 
  if(trim(Params::getParam('sPattern')) != '') {
    zet_pattern_to_cookies(trim(osc_esc_html(Params::getParam('sPattern'))));
  }

  $params_spec = zet_search_params();
  $params_all = zet_search_params_all();

  $search_cat_id = osc_search_category_id();
  $search_cat_id = isset($search_cat_id[0]) ? $search_cat_id[0] : '';

  $category = zet_get_category($search_cat_id);
  $hierarchy = Category::newInstance()->toRootTree($search_cat_id);

  $def_cur = (zet_param('def_cur') <> '' ? zet_param('def_cur') : '$');
  $search_params = Params::getParamsAsArray();
  $search_params_remove = zet_search_param_remove();
  $exclude_tr_con = explode(',', zet_param('post_extra_exclude'));

  $only_root = false;

  if($search_cat_id <= 0) {
    $parent = false;
    $categories = Category::newInstance()->findRootCategoriesEnabled();
    $children = false;
  } else {
    $parent = zet_get_category($search_cat_id);
    //$categories = Category::newInstance()->findSubcategoriesEnabled($search_cat_id);
    $categories = zet_find_subcats($search_cat_id);

    if(count($categories) <= 0) {
      if($parent['fk_i_parent_id'] > 0) {
        $parent = zet_get_category($parent['fk_i_parent_id']);
        //$categories = Category::newInstance()->findSubcategoriesEnabled($parent['pk_i_id']);
        $categories = zet_find_subcats($parent['pk_i_id']);

      } else {  // only parent categories exists
        $parent = false;
        $categories = Category::newInstance()->findRootCategoriesEnabled();
        $only_root = true;
      }
    }
  }  
  
  $view = zet_get_search_view();

  // Count usable params for removal
  $filter_check = 0;
  if(is_array($search_params_remove) && count($search_params_remove) > 0) {
    foreach($search_params_remove as $n => $v) { 
      if($v['name'] <> '' && $v['title'] <> '' && $v['to_remove'] === true) { 
        $filter_check++;
      }
    }
  }

  // Count all filters
  $filter_check_all = 0;
  if(is_array($search_params) && count($search_params) > 0) {
    foreach($search_params as $n => $v) {
      if($v != '' && !in_array($n, array('page','sOrder','iOrderType','iPage','sShowAs','sLocation'))) {
        // Skip if value is set to default
        if(in_array($n, array('bPic','bPremium','bPhone','sCondition','sTransaction','iRadius','sPeriod')) && (int)$v == 0) {
          continue;
        }
        
        $filter_check_all++;
      }
    }
  }
  

  // Get search hooks
  GLOBAL $search_hooks;
  ob_start(); 

  if(osc_search_category_id()) { 
    osc_run_hook('search_form', osc_search_category_id());
  } else { 
    osc_run_hook('search_form');
  }

  //$search_hooks = trim(ob_get_clean());
  //ob_end_flush();

  $search_hooks = trim(ob_get_contents());
  ob_end_clean();

  $search_hooks = trim($search_hooks);
  
  $price_selected = '';
  
  if(Params::getParam('sPriceMin') != '' || Params::getParam('sPriceMax') != '') {
    $price_selected = 'VALUE';
  } else if(Params::getParam('bPriceCheckWithSeller') == 1) {
    $price_selected = 'CHECK';
  } else if(Params::getParam('bPriceFree') == 1) {
    $price_selected = 'FREE';
  }

?>

<div class="container primary">
  <div id="search-menu" class="filter-menu">
    <div class="head">
      <a href="#" class="subbox-close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20"><path d="M229.9 473.899l19.799-19.799c4.686-4.686 4.686-12.284 0-16.971L94.569 282H436c6.627 0 12-5.373 12-12v-28c0-6.627-5.373-12-12-12H94.569l155.13-155.13c4.686-4.686 4.686-12.284 0-16.971L229.9 38.101c-4.686-4.686-12.284-4.686-16.971 0L3.515 247.515c-4.686 4.686-4.686 12.284 0 16.971L212.929 473.9c4.686 4.686 12.284 4.686 16.971-.001z"></path></svg></a>
      <?php _e('Refine search', 'zeta'); ?>
    </div>
    <div class="outer-wrap">
      <?php osc_run_hook('search_sidebar_pre'); ?>
      
      <form action="<?php echo osc_base_url(true); ?>" method="GET" class="search-side-form ssfrm nocsrf">
        <input type="hidden" class="ajaxRun" value=""/>
        <input type="hidden" name="page" value="search"/>
        <input type="hidden" name="sOrder" value="<?php echo osc_esc_html(osc_search_order()); ?>"/>
        <input type="hidden" name="iOrderType" value="<?php $allowedTypesForSorting = Search::getAllowedTypesForSorting(); echo isset($allowedTypesForSorting[osc_search_order_type()]) ? $allowedTypesForSorting[osc_search_order_type()] : ''; ?>" />
        <input type="hidden" name="sCategory" id="sCategory" value="<?php echo osc_esc_html(Params::getParam('sCategory')); ?>"/>
        <input type="hidden" name="sCountry" id="sCountry" value="<?php echo osc_esc_html(Params::getParam('sCountry')); ?>"/>
        <input type="hidden" name="sRegion" id="sRegion" value="<?php echo osc_esc_html(Params::getParam('sRegion')); ?>"/>
        <input type="hidden" name="sCity" id="sCity" value="<?php echo osc_esc_html(Params::getParam('sCity')); ?>"/>
        <input type="hidden" name="sLocation" id="sLocation" value="<?php echo osc_esc_html(Params::getParam('sLocation')); ?>"/>
        <input type="hidden" name="iPage" id="iPage" value=""/>
        <input type="hidden" name="sShowAs" id="sShowAs" value="<?php echo osc_esc_html(Params::getParam('sShowAs')); ?>"/>
        <input type="hidden" name="userId" value="<?php echo osc_esc_html(Params::getParam('userId')); ?>"/>
        <input type="hidden" name="notFromUserId" value="<?php echo osc_esc_html(Params::getParam('notFromUserId')); ?>"/>

        <div class="wrap nice-scroll wrap-scrollable">
          <?php osc_run_hook('search_sidebar_top'); ?>
          
          <div class="row kw">
            <label for="sPattern"><?php _e('Keyword', 'zeta'); ?></label>

            <div class="input-box has-icon">
              <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z"></path></svg>
              <input type="text" name="sPattern" id="sPattern" placeholder="<?php echo osc_esc_html(__('Keyword...', 'zeta')); ?>" value="<?php echo osc_esc_html(Params::getParam('sPattern')); ?>" autocomplete="off"/>
              <i class="clean fas fa-times-circle"></i>
            </div>
          </div>

          
          <div class="row lc">
            <label for="sLocation-XX"><?php _e('Location', 'zeta'); ?></label>
            <?php echo zet_advanced_location(false); ?>
          </div>


          <!-- RADIUS --> 
          <div class="row radius">
            <label for=""><?php _e('Radius', 'zeta'); ?></label>
            <div class="input-box"><?php echo zet_simple_radius('auto'); ?></div>
          </div>


          <div class="row ct">
            <label for="sCategory"><?php _e('Category', 'zeta'); ?></label>

            <div class="input-box">
              <a href="#" class="open-category-box btn btn-white">
                <?php
                  if(empty($hierarchy)) {
                    _e('All categories', 'zeta');
                  } else {
                    echo implode(' > ', array_column($hierarchy, 's_name'));
                  }
                ?>
              </a>
            </div>
          </div>
          
          <?php echo zet_cat_box(); ?>



          <!-- CONDITION --> 
          <?php if($search_cat_id <= 0 || @!in_array($search_cat_id, $exclude_tr_con)) { ?>
            <div class="row condition">
              <label for=""><?php _e('Condition', 'zeta'); ?></label>

              <?php if(zet_is_mobile()) { ?>
                <div class="input-box"><?php echo zet_simple_condition_list('auto'); ?></div>
              <?php } else { ?>
                <div class="input-box"><?php echo zet_simple_condition('auto'); ?></div>
              <?php } ?>
            </div>
          <?php } ?>


          <!-- TRANSACTION --> 
          <?php if($search_cat_id <= 0 || @!in_array($search_cat_id, $exclude_tr_con)) { ?>
            <div class="row transaction">
              <label for=""><?php _e('Transaction', 'zeta'); ?></label>
              
              <?php if(zet_is_mobile()) { ?>
                <div class="input-box"><?php echo zet_simple_transaction_list('auto'); ?></div>
              <?php } else { ?>
                <div class="input-box"><?php echo zet_simple_transaction('auto'); ?></div>
              <?php } ?>
            </div>
          <?php } ?>


          <!-- PRICE -->
          <?php if(zet_check_category_price($search_cat_id)) { ?>
            <div class="row price">
              <label for="sPriceMin"><?php _e('Price range', 'zeta'); ?> (<?php echo $def_cur; ?>)</label>

              <div class="line input-box">
                <input type="number" class="priceMin" name="sPriceMin" id="sPriceMin" value="<?php echo osc_esc_html(Params::getParam('sPriceMin')); ?>" size="6" maxlength="6" placeholder="<?php echo osc_esc_js(__('Min', 'zeta')); ?>"/>
                <span class="delim"></span>
                <input type="number" class="priceMax" name="sPriceMax" id="sPriceMax" value="<?php echo osc_esc_html(Params::getParam('sPriceMax')); ?>" size="6" maxlength="6" placeholder="<?php echo osc_esc_js(__('Max', 'zeta')); ?>"/>
              </div>
              
              <div class="row check-only checkboxes">
                <div class="input-box-check">
                  <input type="checkbox" name="bPriceCheckWithSeller" id="bPriceCheckWithSeller" value="1" <?php echo ($price_selected == 'CHECK' ? 'checked="checked"' : ''); ?> />
                  <label for="bPriceCheckWithSeller" class="only-check-label"><?php _e('Check with seller', 'zeta'); ?></label>
                </div>
              </div>
              
              <div class="row free-only checkboxes">
                <div class="input-box-check">
                  <input type="checkbox" name="bPriceFree" id="bPriceFree" value="1" <?php echo ($price_selected == 'FREE' ? 'checked="checked"' : ''); ?> />
                  <label for="bPriceFree" class="only-free-label"><?php _e('Free', 'zeta'); ?></label>
                </div>
              </div>
            </div>
          <?php } ?>


          <!-- PERIOD--> 
          <div class="row period">
            <label for="sPriceMin"><?php _e('Period', 'zeta'); ?></label>
            
            <?php if(zet_is_mobile()) { ?>
              <div class="input-box"><?php echo zet_simple_period_list('auto'); ?></div>
            <?php } else { ?>
              <div class="input-box"><?php echo zet_simple_period('auto'); ?></div>
            <?php } ?>
          </div>

          <!-- COMPANY --> 
          <div class="row company isMobile">
            <label for="sCompany"><?php _e('Seller type', 'zeta'); ?></label>
            
            <?php if(zet_is_mobile()) { ?>
              <div class="input-box"><?php echo zet_simple_seller_list('auto'); ?></div>
            <?php } else { ?>
              <div class="input-box"><?php echo zet_simple_seller('auto'); ?></div>
            <?php } ?>
          </div>


          <?php if(osc_images_enabled_at_items()) { ?>
            <div class="row with-picture checkboxes">
              <div class="input-box-check">
                <input type="checkbox" name="bPic" id="bPic" value="1" <?php echo (osc_search_has_pic() ? 'checked="checked"' : ''); ?> />
                <label for="bPic" class="only-picture-label"><?php _e('With picture only', 'zeta'); ?></label>
              </div>
            </div>
          <?php } ?>

          <div class="row premiums-only checkboxes">
            <div class="input-box-check">
              <input type="checkbox" name="bPremium" id="bPremium" value="1" <?php echo (Params::getParam('bPremium') == 1 ? 'checked="checked"' : ''); ?> />
              <label for="bPremium" class="only-premium-label"><?php _e('Premium items only', 'zeta'); ?></label>
            </div>
          </div>

          <?php if(1==1) { ?>
            <div class="row phone-only checkboxes">
              <div class="input-box-check">
                <input type="checkbox" name="bPhone" id="bPhone" value="1" <?php echo (Params::getParam('bPhone') == 1 ? 'checked="checked"' : ''); ?> />
                <label for="bPhone" class="only-phone-label"><?php _e('With phone number', 'zeta'); ?></label>
              </div>
            </div>
          <?php } ?>

          <?php if($search_hooks <> '') { ?>
            <div class="row sidebar-hooks"><?php echo $search_hooks; ?></div>
          <?php } ?>
        </div>

        <div class="row buttons srch">
          <a href="<?php echo osc_search_url(array('page' => 'search')); ?>" class="btn btn-white reset"><?php echo sprintf(__('Reset (%d)', 'zeta'), $filter_check_all); ?></a>
          <button type="submit" class="btn btn-secondary init-search" id="search-button"><?php _e('Search', 'zeta'); ?></button>
        </div>
        
        <?php osc_run_hook('search_sidebar_bottom'); ?>
      </form>
      
      <?php osc_run_hook('search_sidebar_after'); ?>
    </div>
  </div>


  <div id="search-main" class="<?php echo $view; ?>">
    <?php osc_run_hook('search_items_top'); ?>
    
    <div class="top-bar">
      <h1>
        <?php 
          $loc = @array_values(array_filter(array(osc_search_city(), osc_search_region(), osc_search_country())))[0];
          $cat = (isset($category['s_name']) ? $category['s_name'] : '');
          $cat_desc = (isset($category['s_description']) ? $category['s_description'] : '');
          $tit = implode(', ', array_filter(array($cat, $loc)));

          if(osc_search_total_items() <= 0) { 
            if($tit != '') {
              echo sprintf(__('No listings found in %s', 'zeta'), $tit);
            } else {
              echo __('No listings found', 'zeta');
            }
            
          } elseif($tit != '') {
            echo sprintf(__('%s results found in %s', 'zeta'), osc_search_total_items(), $tit);
          } else {
            echo sprintf(__('%s results found', 'zeta'), osc_search_total_items());
          }
        ?>
      </h1>
      
      <div class="hdesc">
        <?php 
          if($cat_desc <> '') {
            echo trim($cat_desc);
            
          } else if(osc_search_total_items() > 0) { 
            echo sprintf(__('Buy new or used items, we have %d listings available for you.', 'zeta'), osc_search_total_items());
            
          } else {
            echo __('There are no listings matching your search criteria.', 'zeta');
          }
        ?>
      </div>
    </div>

    <?php zet_save_search_section('top'); ?>

    <div id="search-cat">
      <h2><?php _e('Categories', 'zeta'); ?></h2>

      <div class="cat-list nice-scroll">
        <?php if($search_cat_id > 0) { ?>
          <a href="<?php echo osc_search_url(array('page' => 'search')); ?>" class="all">
            <div class="icon"><?php echo ICON_ALL; ?></div>
            <span class="name"><span><?php _e('All', 'zeta'); ?></span></span>
            
            <?php if(osc_total_active_items() > 0) { ?>
              <em class="count"><?php echo osc_total_active_items(); ?></em>
            <?php } ?>
          </a>

          <?php if(is_array($hierarchy) && count($hierarchy) > 0) { ?>
            <?php foreach($hierarchy as $c) { ?>
              <?php if(!in_array($c['pk_i_id'], array_column($categories, 'pk_i_id'))) { ?>
                <?php 
                  $search_params['sCategory'] = $c['pk_i_id']; 
                  $color = zet_get_cat_color($c['pk_i_id'], $c);
                ?>

                <a href="<?php echo osc_search_url($search_params); ?>" class="serv<?php if($c['pk_i_id'] == $search_cat_id) { ?> active<?php } ?>" data-name="sCategory" data-val="<?php echo $c['pk_i_id']; ?>">
                  <div class="icon">
                    <?php if(zet_param('cat_icons') == 1) { ?>
                      <?php 
                        $icon = zet_get_cat_icon($c['pk_i_id'], $c, true);
                        $icon_ = explode(' ', $icon);
                        
                        $has_type = false;
                        if(in_array($icon_, array('fas', 'far', 'fab'))) {
                          $has_type = true;
                        }
                      ?>
                      <i class="<?php echo ($has_type ? '' : 'fas'); ?> <?php echo $icon; ?>" <?php if($color <> '') { ?>style="color:<?php echo $color; ?>;"<?php } ?>></i>
                    <?php } else { ?>
                      <img src="<?php echo zet_get_cat_image($c['pk_i_id']); ?>" alt="<?php echo osc_esc_html($c['s_name']); ?>" />
                    <?php } ?>
                  </div>
                  
                  <span class="name"><span><?php echo $c['s_name']; ?></span></span>
                  
                  <?php if($c['i_num_items'] > 0) { ?>
                    <em class="count" <?php if($color <> '') { ?>style="background:<?php echo $color; ?>;"<?php } ?>><?php echo $c['i_num_items']; ?></em>
                  <?php } ?>
                </a>
              <?php } ?>
            <?php } ?>


            <div class="del"><span></span></div>
          <?php } ?>
        <?php } ?>
        
        <?php $i = 1; ?>
        <?php foreach($categories as $c) { ?>
          <?php 
            $search_params['sCategory'] = $c['pk_i_id']; 
            $color = zet_get_cat_color($c['pk_i_id'], $c);
          ?>

          <a href="<?php echo osc_search_url($search_params); ?>" class="child<?php if($i > 11 && $c['pk_i_id'] != $search_cat_id && count($categories) > 13) { ?> hide<?php } ?><?php if($c['pk_i_id'] == $search_cat_id) { ?> active<?php } ?>" data-name="sCategory" data-val="<?php echo $c['pk_i_id']; ?>">
            <div class="icon">
              <?php if(zet_param('cat_icons') == 1) { ?>
                <?php 
                  $icon = zet_get_cat_icon($c['pk_i_id'], $c, true);
                  $icon_ = explode(' ', $icon);
                  
                  $has_type = false;
                  if(in_array($icon_, array('fas', 'far', 'fab'))) {
                    $has_type = true;
                  }
                ?>
                <i class="<?php echo ($has_type ? '' : 'fas'); ?> <?php echo $icon; ?>" <?php if($color <> '') { ?>style="color:<?php echo $color; ?>;"<?php } ?>></i>
              <?php } else { ?>
                <img src="<?php echo zet_get_cat_image($c['pk_i_id']); ?>" alt="<?php echo osc_esc_html($c['s_name']); ?>" />
              <?php } ?>
            </div>
            
            <span class="name"><span><?php echo $c['s_name']; ?></span></span>
            
            <?php if($c['i_num_items'] > 0) { ?>
              <em class="count" <?php if($color <> '') { ?>style="background:<?php echo $color; ?>;"<?php } ?>><?php echo $c['i_num_items']; ?></em>
            <?php } ?>
          </a>
          
          <?php $i++; ?>
        <?php } ?>


        <?php if($i > 10 && count($categories) > 13) { ?>
          <a href="#" class="show-more">
            <div class="icon"><i class="fas fa-ellipsis-h"></i></div>
            <span class="name"><span><?php _e('Show more', 'zeta'); ?></span></span>
          </a>
        
        <?php } ?>
      </div>
    </div>

    
    <div id="filter-line">
      <div class="filter-list nice-scroll">
        <a href="#" class="all-filters">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M496 72H288V48c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v24H16C7.2 72 0 79.2 0 88v16c0 8.8 7.2 16 16 16h208v24c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-24h208c8.8 0 16-7.2 16-16V88c0-8.8-7.2-16-16-16zm0 320H160v-24c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v24H16c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h80v24c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-24h336c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16zm0-160h-80v-24c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v24H16c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h336v24c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-24h80c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16z"/></svg>
          <span><?php _e('All filters', 'zeta'); ?></span>
          
          <?php if($filter_check_all > 0) { ?>
            <em class="counter"><?php echo $filter_check_all; ?></em>
          <?php } ?>
        </a>

        <div class="del"><span></span></div>

        <?php 
          //echo zet_simple_category(); 
          
          if(!zet_is_mobile()) {
            echo zet_simple_location();
            
          } else if(zet_param('search_ajax') == 1) {
            echo zet_simple_country(true);
            echo zet_simple_region(true);
            echo zet_simple_city(true);
          }
          
          echo zet_simple_radius('auto');
          
          if(!zet_is_mobile()) {
            echo zet_simple_pattern();
            echo zet_simple_price_min_max();
          }
          
          echo zet_simple_seller('auto');
          echo zet_simple_condition('auto');
          echo zet_simple_transaction('auto');
          echo zet_simple_period('auto');
        ?>
      </div>
    </div>


    <?php if($filter_check_all > 0) { ?>
      <div id="filters-remove">
        <a class="remove-all" href="<?php echo osc_search_url(array('page' => 'search')); ?>">
          <i class="far fa-trash-alt"></i>
          <span><?php echo sprintf(__('Clear filters (%d)', 'zeta'), $filter_check_all); ?></span>
        </a>

        <?php if($filter_check > 0) { ?>
          <?php foreach($search_params_remove as $n => $v) { ?>
            <?php if($v['name'] <> '' && $v['title'] <> '' && $v['to_remove'] === true) { ?>
              <?php
                $rem_param = $params_all;
                unset($rem_param[$n]);
                
                if(in_array($n, array('sCity','city','sRegion','region','sCountry','country'))) {
                  unset($rem_param['sLocation']);
                }
              ?>

              <a href="<?php echo osc_search_url($rem_param); ?>" data-param="<?php echo $v['param']; ?>"><?php echo '<em>' . $v['title'] . ': </em>' . $v['name']; ?></a>
            <?php } ?>
          <?php } ?>
        <?php } ?>
      </div>
    <?php } ?>

    
    <?php
      osc_get_premiums(20); //zet_param('premium_search_count')
    ?>

    <?php if(osc_count_premiums() > 0 && zet_param('premium_search') == 1) { ?>
      <div id="search-premium-items">
        <h2><?php echo __('Premium listings', 'zeta'); ?></h2>

        <?php
          $default_items = View::newInstance()->_get('items'); 
          View::newInstance()->_exportVariableToView('items', View::newInstance()->_get('premiums'));
        ?>
        
        <div class="nice-scroll-wrap">
          <div class="nice-scroll-prev"><?php echo ICON_SCROLL_PREV; ?></div>
          
          <div class="products grid nice-scroll no-visible-scroll style3">
            <?php 
              $c = 1;

              while(osc_has_items()) {
                zet_draw_item($c, false, 'premium-loop ' . zet_param('premium_search_design'));
                $c++;
              }
              
              if(zet_param('search_premium_promote_url') != '') { 
                zet_draw_placeholder_item($c, 'premium-loop ' . zet_param('premium_search_design')); 
              }
            ?>
          </div>
          
          <div class="nice-scroll-next"><?php echo ICON_SCROLL_NEXT; ?></div>
        </div>
        
        <?php View::newInstance()->_exportVariableToView('items', $default_items); ?>
      </div>
    <?php } ?>
    

    <div class="ajax-load-failed flashmessage flashmessage-error" style="display:none;">
      <p><?php _e('There was problem loading your listings, please try to refresh this page', 'zeta'); ?></p>
      <a class="btn mini" onClick="window.location.reload();"><i class="fas fa-redo"></i> <?php _e('Refresh', 'zeta'); ?></a>
    </div>
    
    <?php if(osc_count_items() > 0) { ?>
      <div id="search-quick-bar">
        <div class="simple-sort simple-select">
          <div class="text">
            <?php 
              $orders = osc_list_orders(); 
              $current_order = osc_search_order();  
            ?>

            <?php foreach($orders as $label => $params) { ?>
              <?php $orderType = ($params['iOrderType'] == 'asc') ? '0' : '1'; ?>
              
              <?php if(osc_search_order() == $params['sOrder'] && osc_search_order_type() == $orderType) { ?>
                <span class="lab"><?php _e('Sort:', 'zeta'); ?></span>
                <strong class="kind"><?php echo $label; ?></strong>
                <svg xmlns="http://www.w3.org/2000/svg" class="caret" viewBox="0 0 320 512" width="20" height="20"><path d="M151.5 347.8L3.5 201c-4.7-4.7-4.7-12.3 0-17l19.8-19.8c4.7-4.7 12.3-4.7 17 0L160 282.7l119.7-118.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17l-148 146.8c-4.7 4.7-12.3 4.7-17 0z"></path></svg>
              <?php } ?>
            <?php } ?>
          </div>

          <div class="list nice-scroll">
            <?php $i = 0; ?>
            <?php foreach($orders as $label => $params) { ?>
              <?php $orderType = ($params['iOrderType'] == 'asc') ? '0' : '1'; ?>
              <?php if(osc_search_order() == $params['sOrder'] && osc_search_order_type() == $orderType) { ?>
                <a class="option selected" href="<?php echo osc_update_search_url($params); ?>"><?php echo $label; ?></a>
              <?php } else { ?>
                <a class="option" href="<?php echo osc_update_search_url($params) ; ?>"><?php echo $label; ?></a>
              <?php } ?>
              <?php $i++; ?>
            <?php } ?>
          </div>
        </div>
        
        <?php zet_save_search_section('mid isMobile'); ?>
        
        <div class="view-type">
          <a href="<?php echo osc_update_search_url(array('sShowAs' => 'grid')); ?>" title="<?php echo osc_esc_html(__('Grid view', 'zeta')); ?>" class="<?php echo ($view == 'grid' ? 'active' : ''); ?> grid" data-view="grid">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><mask id="path-1-inside-1_15740_7700" fill="white"> <rect width="7" height="7" rx="1"/> </mask> <rect width="7" height="7" rx="1" fill="currentColor" stroke="currentColor" stroke-width="3" mask="url(#path-1-inside-1_15740_7700)"/> <mask id="path-2-inside-2_15740_7700" fill="white"> <rect y="9" width="7" height="7" rx="1"/> </mask> <rect y="9" width="7" height="7" rx="1" fill="currentColor" stroke="currentColor" stroke-width="3" mask="url(#path-2-inside-2_15740_7700)"/> <mask id="path-3-inside-3_15740_7700" fill="white"> <rect x="9" width="7" height="7" rx="1"/> </mask> <rect x="9" width="7" height="7" rx="1" fill="currentColor" stroke="currentColor" stroke-width="3" mask="url(#path-3-inside-3_15740_7700)"/> <mask id="path-4-inside-4_15740_7700" fill="white"> <rect x="9" y="9" width="7" height="7" rx="1"/> </mask> <rect x="9" y="9" width="7" height="7" rx="1" fill="currentColor" stroke="currentColor" stroke-width="3" mask="url(#path-4-inside-4_15740_7700)"/></svg>
            <span><?php _e('Grid', 'zeta'); ?></span>
          </a>
          
          <a href="<?php echo osc_update_search_url(array('sShowAs' => 'list')); ?>" title="<?php echo osc_esc_html(__('List view', 'zeta')); ?>" class="<?php echo ($view == 'list' ? 'active' : ''); ?> list" data-view="list">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.75 1C0.75 0.861929 0.861929 0.75 1 0.75H6C6.13807 0.75 6.25 0.861929 6.25 1V6C6.25 6.13807 6.13807 6.25 6 6.25H1C0.861929 6.25 0.75 6.13807 0.75 6V1Z" fill="currentColor" stroke="currentColor" stroke-width="1.5"/> <rect x="9" y="1.19995" width="7" height="1.3" rx="0.65" fill="currentColor"/> <rect x="9" y="4.5" width="7" height="1.3" rx="0.65" fill="currentColor"/> <mask id="path-4-inside-1_15740_8274" fill="white"> <rect y="9" width="7" height="7" rx="1"/> </mask> <rect y="9" width="7" height="7" rx="1" fill="currentColor" stroke="currentColor" stroke-width="3" mask="url(#path-4-inside-1_15740_8274)"/> <rect x="9" y="10.2" width="7" height="1.3" rx="0.65" fill="currentColor"/> <rect x="9" y="13.5" width="7" height="1.3" rx="0.65" fill="currentColor"/> </svg>
            <span><?php _e('List', 'zeta'); ?></span>
          </a>
        </div>
      </div>
    <?php } ?>
    

    <div id="search-items">     
      <?php if(osc_count_items() == 0) { ?>
        <div class="list-empty round3" >
          <span class="titles"><?php _e('We could not find any results for your search...', 'zeta'); ?></span>

          <div class="tips">
            <div class="row"><?php _e('Following tips might help you to get better results', 'zeta'); ?></div>
            <div class="row"><i class="fa fa-circle"></i><?php _e('Use more general keywords', 'zeta'); ?></div>
            <div class="row"><i class="fa fa-circle"></i><?php _e('Check spelling of position', 'zeta'); ?></div>
            <div class="row"><i class="fa fa-circle"></i><?php _e('Reduce filters, use less of them', 'zeta'); ?></div>
            <div class="row last"><a href="<?php echo osc_search_url(array('page' => 'search'));?>"><?php _e('Reset filter', 'zeta'); ?> &#8594;</a></div>
          </div>
        </div>

      <?php } else { ?>
        <?php echo zet_banner('search_top'); ?>

        <div class="products with-border <?php echo $view; ?>">
          <?php 
            $c = 1; 
            while(osc_has_items()) {
              zet_draw_item($c, false, zet_param('def_design'));

              if($c == 4 && osc_count_items() > 4) {
                echo zet_banner('search_middle');
              }

              $c++;
            } 
          ?>
        </div>
      <?php } ?>
      
      <?php echo zet_banner('search_bottom'); ?>

      
      <?php osc_run_hook('search_items_bottom'); ?>
      
      <?php $pagi_links = zet_create_next_prev_btn(); ?>
      
      <?php if($pagi_links['prev'] != '' || $pagi_links['next'] != '') { ?>
        <div class="paginate-alt">
          <a href="<?php echo ($pagi_links['prev'] != '' ? $pagi_links['prev'] : '#'); ?>" class="btn btn-white pagi-prev <?php echo ($pagi_links['prev'] == '' ? 'disabled' : ''); ?>" <?php echo ($pagi_links['prev'] == '' ? 'onclick="return false;"' : ''); ?>><?php _e('Previous', 'zeta'); ?></a>
          <a href="<?php echo ($pagi_links['next'] != '' ? $pagi_links['next'] : '#'); ?>" class="btn btn-primary pagi-next <?php echo ($pagi_links['next'] == '' ? 'disabled' : ''); ?>" <?php echo ($pagi_links['next'] == '' ? 'onclick="return false;"' : ''); ?>><?php _e('Next page', 'zeta'); ?></a>
        </div>
      <?php } ?>
      
      <div class="paginate"><?php echo zet_fix_arrow(osc_search_pagination()); ?></div>


      <?php if(osc_count_items() > 0) { ?>
        <?php zet_get_latest_searches(32) ?>
        <?php if(osc_count_latest_searches() > 0) { ?>
          <div id="latest-search">
            <h3><?php _e('Other people searched', 'zeta'); ?></h3>
            <div class="wrap">
              <?php $i = 0; ?>
              <?php while(osc_has_latest_searches()) { ?>
                <?php 
                  if($i > 16) { break; } 
                  $i++;
                ?>
               
                <a href="<?php echo osc_search_url(array('page' => 'search', 'sPattern' => osc_latest_search_text())); ?>"><?php echo osc_highlight(osc_latest_search_text(), 20); ?></a>
              <?php } ?>
            </div>
          </div>
        <?php } ?>
      <?php } ?>

      <?php 
        if(zet_param('recent_search') == 1) {
          zet_recent_ads(zet_param('recent_design'), zet_param('recent_count'), 'onsearch');
        }
      ?>

    </div>
  </div>
  
  <div id="search-side-banner"><span class="lab"><?php _e('Advertisement', 'zeta'); ?></span><?php echo zet_banner('search_side'); ?></div>
</div>

<?php osc_current_web_theme_path('footer.php'); ?>

</body>
</html>