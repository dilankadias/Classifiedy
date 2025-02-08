<?php
  require_once 'functions.php';

  // Create menu
  $title = __('Configure', 'zeta');
  zet_menu($title);


  // GET & UPDATE PARAMETERS
  // $variable = zet_param_update('param_name', 'form_name', 'input_type', 'plugin_var_name');
  // input_type: check, value or code

  $default_location = zet_param_update('default_location', 'theme_action', 'check', 'theme-zeta');

  $enable_custom_color = zet_param_update('enable_custom_color', 'theme_action', 'check', 'theme-zeta');
  $enable_dark_mode = zet_param_update('enable_dark_mode', 'theme_action', 'check', 'theme-zeta');
  $default_mode = zet_param_update('default_mode', 'theme_action', 'value', 'theme-zeta');
  $color_primary = zet_param_update('color_primary', 'theme_action', 'value', 'theme-zeta');
  $color_primary_hover = zet_param_update('color_primary_hover', 'theme_action', 'value', 'theme-zeta');
  $color_secondary = zet_param_update('color_secondary', 'theme_action', 'value', 'theme-zeta');
  $color_secondary_hover = zet_param_update('color_secondary_hover', 'theme_action', 'value', 'theme-zeta');
  
  $enable_custom_font = zet_param_update('enable_custom_font', 'theme_action', 'check', 'theme-zeta');
  $font_name = zet_param_update('font_name', 'theme_action', 'value', 'theme-zeta');
  $font_url = zet_param_update('font_url', 'theme_action', 'value', 'theme-zeta');

  $publish_category = zet_param_update('publish_category', 'theme_action', 'value', 'theme-zeta');
  $publish_location = zet_param_update('publish_location', 'theme_action', 'value', 'theme-zeta');
  $profile_location = zet_param_update('profile_location', 'theme_action', 'value', 'theme-zeta');
  $site_phone = zet_param_update('site_phone', 'theme_action', 'value', 'theme-zeta');
  $site_email = zet_param_update('site_email', 'theme_action', 'value', 'theme-zeta');
  $site_name = zet_param_update('site_name', 'theme_action', 'value', 'theme-zeta');
  $site_address = zet_param_update('site_address', 'theme_action', 'value', 'theme-zeta');
  $def_view = zet_param_update('def_view', 'theme_action', 'value', 'theme-zeta');
  $def_design = zet_param_update('def_design', 'theme_action', 'value', 'theme-zeta');
  $enable_day_offer = zet_param_update('enable_day_offer', 'theme_action', 'check', 'theme-zeta');
  $generate_favicons = zet_param_update('generate_favicons', 'theme_action', 'check', 'theme-zeta');
  $sample_favicons = zet_param_update('sample_favicons', 'theme_action', 'check', 'theme-zeta');
  $day_offer_admin_id = zet_param_update('day_offer_admin_id', 'theme_action', 'value', 'theme-zeta');
  $search_premium_promote_url = zet_param_update('search_premium_promote_url', 'theme_action', 'value', 'theme-zeta');
  
  if(Params::getParam('theme_action') == 'done') {
    //osc_set_preference('day_offer_id', '', 'theme-zeta');
    zet_manage_day_offer();
  }    
  
  $categories_new = zet_param_update('categories_new', 'theme_action', 'value', 'theme-zeta');
  $categories_hot = zet_param_update('categories_hot', 'theme_action', 'value', 'theme-zeta');

  $premium_home = zet_param_update('premium_home', 'theme_action', 'check', 'theme-zeta');
  $location_home = zet_param_update('location_home', 'theme_action', 'check', 'theme-zeta');
  $home_search_cat = zet_param_update('home_search_cat', 'theme_action', 'array', 'theme-zeta');

  $premium_home_count = zet_param_update('premium_home_count', 'theme_action', 'value', 'theme-zeta');
  $premium_search = zet_param_update('premium_search', 'theme_action', 'check', 'theme-zeta');
  $premium_search_count = zet_param_update('premium_search_count', 'theme_action', 'value', 'theme-zeta');
  $premium_home_design = zet_param_update('premium_home_design', 'theme_action', 'value', 'theme-zeta');
  $premium_search_design = zet_param_update('premium_search_design', 'theme_action', 'value', 'theme-zeta');

  $footer_link = zet_param_update('footer_link', 'theme_action', 'check', 'theme-zeta');
  $def_cur = zet_param_update('def_cur', 'theme_action', 'value', 'theme-zeta');
  $latest_random = zet_param_update('latest_random', 'theme_action', 'check', 'theme-zeta');
  $latest_picture = zet_param_update('latest_picture', 'theme_action', 'check', 'theme-zeta');
  $latest_premium = zet_param_update('latest_premium', 'theme_action', 'check', 'theme-zeta');
  $latest_category = zet_param_update('latest_category', 'theme_action', 'value', 'theme-zeta');
  $latest_design = zet_param_update('latest_design', 'theme_action', 'value', 'theme-zeta');

  $search_ajax = zet_param_update('search_ajax', 'theme_action', 'check', 'theme-zeta');
  $post_required = zet_param_update('post_required', 'theme_action', 'value', 'theme-zeta');
  $post_extra_exclude = zet_param_update('post_extra_exclude', 'theme_action', 'value', 'theme-zeta');

  $lazy_load = zet_param_update('lazy_load', 'theme_action', 'value', 'theme-zeta');
  $location_pick = zet_param_update('location_pick', 'theme_action', 'check', 'theme-zeta');
  $public_items = zet_param_update('public_items', 'theme_action', 'value', 'theme-zeta');
  $alert_items = zet_param_update('alert_items', 'theme_action', 'value', 'theme-zeta');
  $preview = zet_param_update('preview', 'theme_action', 'check', 'theme-zeta');
  $def_locations = zet_param_update('def_locations', 'theme_action', 'value', 'theme-zeta');
  $gallery_ratio = zet_param_update('gallery_ratio', 'theme_action', 'value', 'theme-zeta');
  $home_cat_rows = zet_param_update('home_cat_rows', 'theme_action', 'value', 'theme-zeta');

  $asset_versioning = zet_param_update('asset_versioning', 'theme_action', 'check', 'theme-zeta');

  $footer_social_define = zet_param_update('footer_social_define', 'theme_action', 'check', 'theme-zeta');
  $footer_social_whatsapp = zet_param_update('footer_social_whatsapp', 'theme_action', 'value', 'theme-zeta');
  $footer_social_facebook = zet_param_update('footer_social_facebook', 'theme_action', 'value', 'theme-zeta');
  $footer_social_pinterest = zet_param_update('footer_social_pinterest', 'theme_action', 'value', 'theme-zeta');
  $footer_social_instagram = zet_param_update('footer_social_instagram', 'theme_action', 'value', 'theme-zeta');
  $footer_social_x = zet_param_update('footer_social_x', 'theme_action', 'value', 'theme-zeta');
  $footer_social_linkedin = zet_param_update('footer_social_linkedin', 'theme_action', 'value', 'theme-zeta');


  if(Params::getParam('theme_action') == 'done') {
    osc_add_flash_ok_message(__('Settings were successfully saved','zeta'), 'admin');
    header('Location:' . osc_admin_render_theme_url('oc-content/themes/zeta/admin/configure.php'));
    exit;
  }


  $latest_category_array = explode(',', $latest_category);
  $post_extra_exclude_array = explode(',', $post_extra_exclude);
  $post_required_array = explode(',', $post_required);
  $categories_new_array = explode(',', $categories_new);
  $categories_hot_array = explode(',', $categories_hot);

?>


<div class="mb-body">

 
  <!-- CONFIGURE SECTION -->
  <div class="mb-box">
    <div class="mb-head"><i class="fa fa-wrench"></i> <?php _e('Configure', 'zeta'); ?></div>

    <div class="mb-inside mb-minify">
      <form action="<?php echo osc_admin_render_theme_url('oc-content/themes/zeta/admin/configure.php'); ?>" method="POST">
        <input type="hidden" name="theme_action" value="done" />


        <div class="mb-row">
          <label for="lazy_load" class=""><span><?php _e('Images Lazy Load', 'zeta'); ?></span></label> 

          <select name="lazy_load" id="lazy_load">
            <option value="0" <?php echo (zet_param('lazy_load') == 0 ? 'selected="selected"' : ''); ?>><?php _e('Disabled', 'zeta'); ?></option>
            <option value="1" <?php echo (zet_param('lazy_load') == 1 ? 'selected="selected"' : ''); ?>><?php _e('Lazy load based on library', 'zeta'); ?></option>
            <option value="2" <?php echo (zet_param('lazy_load') == 2 ? 'selected="selected"' : ''); ?>><?php _e('Lazy load based on browser support', 'zeta'); ?></option>
          </select>
          
          <div class="mb-explain">
            <div class="mb-line"><?php _e('Enable to deffer images loading. Images will be loaded when get into viewable area. This may rapidly improve seo rating of your site.', 'zeta'); ?></div>
            <div class="mb-line"><?php _e('Lazy load should not be disabled for any reason', 'zeta'); ?></div>
          </div>
        </div>

        <div class="mb-row">
          <label for="default_location" class=""><span><?php _e('Default Location Selection', 'zeta'); ?></span></label> 
          <input name="default_location" id="default_location" class="element-slide" type="checkbox" <?php echo (zet_param('default_location') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Enable users to select their default location and use it in search & publish.', 'zeta'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="def_design" class=""><span><?php _e('Default Item Card Design', 'zeta'); ?></span></label> 

          <select name="def_design" id="def_design">
            <option value="" <?php echo (zet_param('def_design') == '' ? 'selected="selected"' : ''); ?>><?php _e('Default', 'zeta'); ?></option>
            
            <?php foreach(zet_card_designs() as $key => $name) { ?>
              <option value="<?php echo $key; ?>" <?php echo (zet_param('def_design') == $key ? 'selected="selected"' : ''); ?>><?php echo $name; ?></option>
            <?php } ?>
          </select>
          
          <div class="mb-explain"><?php _e('Specify default image aspect ratio on listings cards (loop) in grid layout.', 'zeta'); ?></div>
        </div>


        <div class="mb-row"><h3 class="mb-subtitle"><?php _e('Custom color settings', 'zeta'); ?></h3></div>

        <div class="mb-row">
          <label for="enable_custom_color" class=""><span><?php _e('Use Custom Color', 'zeta'); ?></span></label> 
          <input name="enable_custom_color" id="enable_custom_color" class="element-slide" type="checkbox" <?php echo (zet_param('enable_custom_color') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Theme will use bellow defined colors.', 'zeta'); ?></div>
        </div>

        <div class="mb-row mb-color-box">
          <label for="color_primary" class=""><span><?php _e('Theme Color - Primary', 'zeta'); ?></span></label> 
      
          <input name="color_primary" id="color_primary" size="20" minlength="7" maxlength="7" type="text" value="<?php echo osc_esc_html($color_primary); ?>" />
          <span class="color-wrap">
            <input name="color-picker" id="" type="color" value="<?php echo osc_esc_html($color_primary); ?>" />
          </span>
          <div class="mb-explain"><?php _e('Enter color in HEX format or select color with picker.', 'zeta'); ?> <?php echo sprintf(__('Default: %s', 'zeta'), '#ff2636'); ?></div>
        </div>
        
        <div class="mb-row mb-color-box">
          <label for="color_primary_hover" class=""><span><?php _e('Theme Color - Primary on Hover', 'zeta'); ?></span></label> 
      
          <input name="color_primary_hover" id="color_primary_hover" size="20" minlength="7" maxlength="7" type="text" value="<?php echo osc_esc_html($color_primary_hover); ?>" />
          <span class="color-wrap">
            <input name="color-picker" id="" type="color" value="<?php echo osc_esc_html($color_primary_hover); ?>" />
          </span>
          <div class="mb-explain"><?php _e('Enter color in HEX format or select color with picker.', 'zeta'); ?> <?php echo sprintf(__('Default: %s', 'zeta'), '#cc1e2b'); ?></div>
        </div>
        
        <div class="mb-row mb-color-box">
          <label for="color_secondary" class=""><span><?php _e('Theme Color - Secondary', 'zeta'); ?></span></label> 
      
          <input name="color_secondary" id="color_secondary" size="20" minlength="7" maxlength="7" type="text" value="<?php echo osc_esc_html($color_secondary); ?>" />
          <span class="color-wrap">
            <input name="color-picker" id="" type="color" value="<?php echo osc_esc_html($color_secondary); ?>" />
          </span>
          <div class="mb-explain"><?php _e('Enter color in HEX format or select color with picker.', 'zeta'); ?> <?php echo sprintf(__('Default: %s', 'zeta'), '#008f79'); ?></div>
        </div>
        
        <div class="mb-row mb-color-box">
          <label for="color_secondary_hover" class=""><span><?php _e('Theme Color - Secondary on Hover', 'zeta'); ?></span></label> 
      
          <input name="color_secondary_hover" id="color_secondary_hover" size="20" minlength="7" maxlength="7" type="text" value="<?php echo osc_esc_html($color_secondary_hover); ?>" />
          <span class="color-wrap">
            <input name="color-picker" id="" type="color" value="<?php echo osc_esc_html($color_secondary_hover); ?>" />
          </span>
          <div class="mb-explain"><?php _e('Enter color in HEX format or select color with picker.', 'zeta'); ?> <?php echo sprintf(__('Default: %s', 'zeta'), '#026958'); ?></div>
        </div>

        <div class="mb-row">
          <label for="enable_dark_mode" class=""><span><?php _e('Enable Dark Mode', 'zeta'); ?></span></label> 
          <input name="enable_dark_mode" id="enable_dark_mode" class="element-slide" type="checkbox" <?php echo (zet_param('enable_dark_mode') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain">
            <div class="mb-line"><?php _e('Visitors will be able to select preferred theme - dark or light.', 'zeta'); ?></div>
          </div>
        </div>

        <div class="mb-row">
          <label for="default_mode" class=""><span><?php _e('Default Mode', 'zeta'); ?></span></label> 
          <select name="default_mode" id="default_mode">
            <option value="LIGHT" <?php echo (zet_param('default_mode') == 'LIGHT' ? 'selected="selected"' : ''); ?>><?php _e('Light mode', 'zeta'); ?></option>
            <option value="DARK" <?php echo (zet_param('default_mode') == 'DARK' ? 'selected="selected"' : ''); ?>><?php _e('Dark mode', 'zeta'); ?></option>
          </select>
          
          <div class="mb-explain"><?php _e('Select default website mode, when dark mode is enabled.', 'zeta'); ?></div>
        </div>

        <div class="mb-row"><h3 class="mb-subtitle"><?php _e('Custom font settings', 'zeta'); ?></h3></div>

        <div class="mb-row">
          <label for="enable_custom_font" class=""><span><?php _e('Use Custom Font', 'zeta'); ?></span></label> 
          <input name="enable_custom_font" id="enable_custom_font" class="element-slide" type="checkbox" <?php echo (zet_param('enable_custom_font') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Theme will use bellow defined font as default theme font (instead of System-UI font).', 'zeta'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="font_name" class=""><span><?php _e('Font Family Name', 'zeta'); ?></span></label> 
          <input size="30" name="font_name" id="font_name" type="text" value="<?php echo osc_esc_html(zet_param('font_name')); ?>" />

          <div class="mb-explain"><?php _e('Enter font family name that will be used in CSS to set this font. Examples: Roboto, "Open Sans", Tapestry, Montserrat, ...', 'zeta'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="font_url" class=""><span><?php _e('Font URL', 'zeta'); ?></span></label> 
          <input size="100" name="font_url" id="font_url" type="text" value="<?php echo osc_esc_html(zet_param('font_url')); ?>" />

          <div class="mb-explain"><?php _e('Enter URL to your font. You can place multiple URLs delimited by comma when needed.', 'zeta'); ?></div>
        </div>
        
        

        <div class="mb-row"><h3 class="mb-subtitle"><?php _e('About your site', 'zeta'); ?></h3></div>

        <div class="mb-row">
          <label for="site_name" class=""><span><?php _e('Site Name', 'zeta'); ?></span></label> 
          <input size="40" name="site_name" id="site_name" type="text" value="<?php echo osc_esc_html(zet_param('site_name')); ?>" placeholder="<?php echo osc_esc_html(__('Website Name', 'zeta')); ?>" />

          <div class="mb-explain"><?php _e('Your site name, business name or company name', 'zeta'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="site_phone" class=""><span><?php _e('Site Phone Number', 'zeta'); ?></span></label> 
          <input size="40" name="site_phone" id="site_phone" type="text" value="<?php echo osc_esc_html(zet_param('site_phone')); ?>" placeholder="<?php echo osc_esc_html(__('Site Phone Number', 'zeta')); ?>" />

          <div class="mb-explain"><?php _e('Leave blank to disable, will be shown in footer', 'zeta'); ?></div>
        </div>

        <div class="mb-row">
          <label for="site_email" class=""><span><?php _e('Site Support Email', 'zeta'); ?></span></label> 
          <input size="40" name="site_email" id="site_email" type="text" value="<?php echo osc_esc_html(zet_param('site_email')); ?>" placeholder="<?php echo osc_esc_html(__('Site Support Email', 'zeta')); ?>" />

          <div class="mb-explain"><?php _e('Leave blank to disable, will be shown in footer', 'zeta'); ?></div>
        </div>

        <div class="mb-row">
          <label for="site_address" class=""><span><?php _e('Site Address', 'zeta'); ?></span></label> 
          <textarea name="site_address" id="site_address"><?php echo osc_esc_html(zet_param('site_address')); ?></textarea>

          <div class="mb-explain"><?php _e('Enter contact address that will be used in footer', 'zeta'); ?></div>
        </div>



        <div class="mb-row"><h3 class="mb-subtitle"><?php _e('Promotion settings', 'zeta'); ?></h3></div>

        
        <div class="mb-row">
          <label for="enable_day_offer" class=""><span><?php _e('Enable Offer of the Day', 'zeta'); ?></span></label> 
          <input name="enable_day_offer" id="enable_day_offer" class="element-slide" type="checkbox" <?php echo (zet_param('enable_day_offer') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Enable offer of the day functionality. You can select promoted item in bellow box. If no item is selected, random premium item will be picked via daily cron.', 'zeta'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="day_offer_admin_id" class=""><span><?php _e('Offer of the Day Item ID', 'zeta'); ?></span></label> 
          <input size="20" name="day_offer_admin_id" id="day_offer_admin_id" type="text" value="<?php echo osc_esc_html(zet_param('day_offer_admin_id')); ?>"/>

          <div class="mb-explain"><?php _e('Enter item ID that will be promoted as "Offer of the day". Leave blank to use random premium item.', 'zeta'); ?></div>
        </div>
        
        

        <div class="mb-row"><h3 class="mb-subtitle"><?php _e('Home page settings', 'zeta'); ?></h3></div>
        
        <div class="mb-row">
          <label for="location_home" class=""><span><?php _e('Show Location Block on Home', 'zeta'); ?></span></label> 
          <input name="location_home" id="location_home" class="element-slide" type="checkbox" <?php echo (zet_param('location_home') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Show block with listings close to user default location (if defined and enabled).', 'zeta'); ?></div>
        </div>

        
        <div class="mb-row">
          <label for="premium_home" class=""><span><?php _e('Show Premiums Block on Home', 'zeta'); ?></span></label> 
          <input name="premium_home" id="premium_home" class="element-slide" type="checkbox" <?php echo (zet_param('premium_home') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Show premium listings block on home page.', 'zeta'); ?></div>
        </div>

        <div class="mb-row">
          <label for="premium_home_count" class=""><span><?php _e('Number of Premiums on Home', 'zeta'); ?></span></label> 
          <input size="8" name="premium_home_count" id="premium_home_count" type="number" value="<?php echo osc_esc_html(zet_param('premium_home_count')); ?>" />

          <div class="mb-explain"><?php _e('How many premium listings will be shown on home page.', 'zeta'); ?></div>
        </div>
        
        <?php if(1==2) { ?>
        <div class="mb-row">
          <label for="premium_home_design" class=""><span><?php _e('Premium Items Card Design (home)', 'zeta'); ?></span></label> 
          <select name="premium_home_design" id="premium_design">
            <option value="" <?php echo (zet_param('premium_home_design') == '' ? 'selected="selected"' : ''); ?>><?php _e('Standard', 'zeta'); ?></option>

            <?php foreach(zet_card_designs() as $key => $name) { ?>
              <option value="<?php echo $key; ?>" <?php echo (zet_param('premium_home_design') == $key ? 'selected="selected"' : ''); ?>><?php echo $name; ?></option>
            <?php } ?>
          </select>

          <div class="mb-explain"><?php _e('Specify which card design will be used.', 'zeta'); ?></div>
        </div>
        <?php } ?>

        <div class="mb-row mb-row-select-multiple">
          <label for="categories_new" class=""><span><?php _e('Categories with New label', 'zeta'); ?></span></label> 

          <input type="hidden" name="categories_new" id="categories_new" value="<?php echo $categories_new; ?>"/>
          <select id="categories_new_multiple" name="categories_new_multiple" multiple>
            <option value="" <?php if($categories_new == '') { ?>selected="selected"<?php } ?>><?php _e('None', 'zeta'); ?></option>
          
            <?php 
              osc_get_categories(); 
              osc_goto_first_category();
            ?>
            <?php while(osc_has_categories()) { ?>
              <option value="<?php echo osc_category_id(); ?>" <?php if(in_array(osc_category_id(), $categories_new_array)) { ?>selected="selected"<?php } ?>><?php echo osc_category_name(); ?></option>
            <?php } ?>
          </select>

          <div class="mb-explain"><?php _e('Select one or more categories those will have label "NEW" on home page.', 'zeta'); ?></div>
        </div>
        
        <div class="mb-row mb-row-select-multiple">
          <label for="categories_hot" class=""><span><?php _e('Categories with Hot label', 'zeta'); ?></span></label> 

          <input type="hidden" name="categories_hot" id="categories_hot" value="<?php echo $categories_hot; ?>"/>
          <select id="categories_hot_multiple" name="categories_hot_multiple" multiple>
            <option value="" <?php if($categories_hot == '') { ?>selected="selected"<?php } ?>><?php _e('None', 'zeta'); ?></option>
          
            <?php 
              osc_get_categories(); 
              osc_goto_first_category();
            ?>
            <?php while(osc_has_categories()) { ?>
              <option value="<?php echo osc_category_id(); ?>" <?php if(in_array(osc_category_id(), $categories_hot_array)) { ?>selected="selected"<?php } ?>><?php echo osc_category_name(); ?></option>
            <?php } ?>
          </select>

          <div class="mb-explain"><?php _e('Select one or more categories those will have label "HOT" on home page.', 'zeta'); ?></div>
        </div>

        
        <div class="mb-row">
          <label for="" class=""><span><?php _e('Home Search Box', 'zeta'); ?></span></label> 

          <div style="float:left;width:70%;">
            <?php 
              $user_cat_ids = array(); 
              osc_get_categories(); 
            ?>
            <?php for($i=1;$i<=10;$i++) { ?>
              <div class="mb-line" style="margin:0 0 4px 0;float:left;clear:both;width:100%;">
                <?php 
                  osc_goto_first_category();
                  $hs_line = (isset($home_search_cat[$i]) && is_array($home_search_cat[$i])) ? $home_search_cat[$i] : array();
                  $hs_line_cat = (isset($hs_line['catId']) && $hs_line['catId'] != '') ? $hs_line['catId'] : -99;

                  if(!in_array($hs_line_cat, $user_cat_ids) && $hs_line_cat != -99) {
                    $hs_line_filters = (isset($hs_line['filters']) && $hs_line['filters'] != '') ? $hs_line['filters'] : '';
                    $hs_line_filters = implode(',', array_filter(array_unique(array_map('strtolower', array_map('trim', explode(',', $hs_line_filters))))));

                  } else {
                    $hs_line_cat = -99;
                    $hs_line_filters = '';
                  }
                  
                  $user_cat_ids[] = $hs_line_cat;
                ?>
                <strong style="float:left;font-size:12px;display:flex;margin:0 4px 0 0;height: 30px;align-items: center;width:60px;"><?php echo sprintf(__('Tab #%d:', 'zeta'), $i); ?></strong>
                
                <select name="home_search_cat[<?php echo $i; ?>][catId]" style="margin-right:4px;width:120px;min-width:120px;">
                  <option value="-99" <?php if($hs_line_cat == -99) { ?>selected="selected"<?php } ?>><?php _e('Not used', 'zeta'); ?></option>
                  <option value="-9" <?php if($hs_line_cat == -9) { ?>selected="selected"<?php } ?>><?php _e('All categories', 'zeta'); ?></option>

                  <?php while(osc_has_categories()) { ?>
                    <option value="<?php echo osc_category_id(); ?>" <?php if(osc_category_id() == $hs_line_cat) { ?>selected="selected"<?php } ?>><?php echo osc_category_name(); ?></option>
                  <?php } ?>
                </select>
                
                <input size="40" name="home_search_cat[<?php echo $i; ?>][filters]" type="text" value="<?php echo $hs_line_filters; ?>" placeholder="<?php echo osc_esc_html(__('Filters delimted by comma', 'zeta')); ?>"/>
              </div>
            <?php } ?>
          </div>
          
          <div class="mb-explain">
            <div class="mb-line"><?php _e('<u>Supported filters:</u> pattern, seller, category, location, radius, condition, transaction, price_min, price_max', 'zeta'); ?></div>
            <div class="mb-line"><?php _e('Configure layout of your home page search. Order of filters matter! All lowercase. No white spaces. Do not repeat filters.', 'zeta'); ?></div>
            <div class="mb-line"><?php _e('Keep all "not used" to use default configuration.', 'zeta'); ?></div>
          </div>
        </div>

        <div class="mb-row mb-row-select-multiple">
          <label for="home_cat_rows" class=""><span><?php _e('Category rows on home page', 'zeta'); ?></span></label> 
  
          <select id="home_cat_rows" name="home_cat_rows">
            <?php for($i=1; $i<=5; $i++) { ?>
              <option value="<?php echo $i; ?>" <?php if($home_cat_rows == $i) { ?>selected="selected"<?php } ?>><?php echo sprintf(__('%d row(s)', 'zeta'), $i); ?></option>
            <?php } ?>
          </select>
          
          <div class="mb-explain"><?php _e('Select categories that will be used to feed listings into latest items section on home page.', 'zeta'); ?></div>
        </div>





        <div class="mb-row"><h3 class="mb-subtitle"><?php _e('Latest items settings', 'zeta'); ?></h3></div>

        <div class="mb-row">
          <label for="latest_random" class=""><span><?php _e('Show Latest Items in Random Order', 'zeta'); ?></span></label> 
          <input name="latest_random" id="latest_random" class="element-slide" type="checkbox" <?php echo (zet_param('latest_random') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Enable to show latest items in ranodm order each time page is refreshed.', 'zeta'); ?></div>
        </div>

        <div class="mb-row">
          <label for="latest_picture" class=""><span><?php _e('Latest Items Picture Only', 'zeta'); ?></span></label> 
          <input name="latest_picture" id="latest_picture" class="element-slide" type="checkbox" <?php echo (zet_param('latest_picture') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Enable to show in latest section on home page only listings those has at least 1 picture.', 'zeta'); ?></div>
        </div>

        <div class="mb-row">
          <label for="latest_premium" class=""><span><?php _e('Latest Premium Items', 'zeta'); ?></span></label> 
          <input name="latest_premium" id="latest_premium" class="element-slide" type="checkbox" <?php echo (zet_param('latest_premium') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Enable to show in latest section on home page only listings those are premium.', 'zeta'); ?></div>
        </div>

        <div class="mb-row mb-row-select-multiple">
          <label for="latest_category" class=""><span><?php _e('Category for Latest Items', 'zeta'); ?></span></label> 
  
          <input type="hidden" name="latest_category" id="latest_category" value="<?php echo $latest_category; ?>"/>
          <select id="latest_category_multiple" name="latest_category_multiple" multiple>
            <?php echo zet_cat_list($latest_category_array); ?>
          </select>
          
          <div class="mb-explain"><?php _e('Select categories that will be used to feed listings into latest items section on home page.', 'zeta'); ?></div>
        </div>

        <div class="mb-row">
          <label for="latest_design" class=""><span><?php _e('Latest items card design', 'zeta'); ?></span></label> 
          <select name="latest_design" id="latest_design">
            <option value="" <?php echo (zet_param('latest_design') == '' ? 'selected="selected"' : ''); ?>><?php _e('Standard', 'zeta'); ?></option>

            <?php foreach(zet_card_designs() as $key => $name) { ?>
              <option value="<?php echo $key; ?>" <?php echo (zet_param('latest_design') == $key ? 'selected="selected"' : ''); ?>><?php echo $name; ?></option>
            <?php } ?>
          </select>

          <div class="mb-explain"><?php _e('Specify which card design will be used.', 'zeta'); ?></div>
        </div>



        <div class="mb-row"><h3 class="mb-subtitle"><?php _e('Search page settings', 'zeta'); ?></h3></div>

        <div class="mb-row">
          <label for="search_ajax" class=""><span><?php _e('Live Search using Ajax', 'zeta'); ?></span></label> 
          <input name="search_ajax" id="search_ajax" class="element-slide" type="checkbox" <?php echo (zet_param('search_ajax') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Enable live realtime search without reloading of search page.', 'zeta'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="def_view" class=""><span><?php _e('Default View on Search Page', 'zeta'); ?></span></label> 
          <select name="def_view" id="def_view">
            <option value="0" <?php echo (zet_param('def_view') == 0 ? 'selected="selected"' : ''); ?>><?php _e('Gallery view', 'zeta'); ?></option>
            <option value="1" <?php echo (zet_param('def_view') == 1 ? 'selected="selected"' : ''); ?>><?php _e('List view', 'zeta'); ?></option>
          </select>
          
          <div class="mb-explain"><?php _e('Select default design of listing cards on search page.', 'zeta'); ?></div>
        </div>

        <div class="mb-row">
          <label for="premium_search" class=""><span><?php _e('Show Premiums Block on Search', 'zeta'); ?></span></label> 
          <input name="premium_search" id="premium_search" class="element-slide" type="checkbox" <?php echo (zet_param('premium_search') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Show Premium Listings block on Search Page.', 'zeta'); ?></div>
        </div>

        <div class="mb-row">
          <label for="premium_search_count" class=""><span><?php _e('Number of Premiums on Search', 'zeta'); ?></span></label> 
          <input size="8" name="premium_search_count" id="premium_search_count" type="number" value="<?php echo osc_esc_html(zet_param('premium_search_count')); ?>" />

          <div class="mb-explain"><?php _e('How many premium listings will be shown on Search page.', 'zeta'); ?></div>
        </div>

        <div class="mb-row">
          <label for="search_premium_promote_url" class=""><span><?php _e('Premium Placeholder Target URL', 'zeta'); ?></span></label> 
          <input size="60" name="search_premium_promote_url" id="search_premium_promote_url" type="text" value="<?php echo osc_esc_html(zet_param('search_premium_promote_url')); ?>" />

          <div class="mb-explain"><?php _e('Define URL where user is redirected when clicked on "Your listing here" placeholder on search page (premiums block). If no URL is defined, placeholder is not shown.', 'zeta'); ?></div>
        </div>

        <div class="mb-row">
          <label for="premium_search_design" class=""><span><?php _e('Premium Items Card Design (search)', 'zeta'); ?></span></label> 
          <select name="premium_search_design" id="premium_design">
            <option value="" <?php echo (zet_param('premium_search_design') == '' ? 'selected="selected"' : ''); ?>><?php _e('Standard', 'zeta'); ?></option>

            <?php foreach(zet_card_designs() as $key => $name) { ?>
              <option value="<?php echo $key; ?>" <?php echo (zet_param('premium_search_design') == $key ? 'selected="selected"' : ''); ?>><?php echo $name; ?></option>
            <?php } ?>
          </select>

          <div class="mb-explain"><?php _e('Specify which card design will be used.', 'zeta'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="def_cur" class=""><span><?php _e('Currency in Search Box', 'zeta'); ?></span></label> 
          <select name="def_cur" id="def_cur">
            <?php foreach(osc_get_currencies() as $c) { ?>
              <option value="<?php echo $c['s_description']; ?>" <?php echo (zet_param('def_cur') == $c['s_description'] ? 'selected="selected"' : ''); ?>><?php echo $c['s_description']; ?></option>
            <?php } ?>
          </select>

          <div class="mb-explain"><?php _e('Select currency symbol that will be used on search page for min & max price fields.', 'zeta'); ?></div>
        </div>



        <div class="mb-row"><h3 class="mb-subtitle"><?php _e('Publish listing settings', 'zeta'); ?></h3></div>

        <div class="mb-row">
          <label for="publish_category" class=""><span><?php _e('Category selection on Publish page', 'zeta'); ?></span></label> 
          <select name="publish_category" id="publish_category">
            <option value="1" <?php echo (zet_param('publish_category') == 1 ? 'selected="selected"' : ''); ?>><?php _e('Auto-complete box', 'zeta'); ?></option>
            <option value="2" <?php echo (zet_param('publish_category') == 2 ? 'selected="selected"' : ''); ?>><?php _e('Cascading dropdowns', 'zeta'); ?></option>
            <option value="3" <?php echo (zet_param('publish_category') == 3 ? 'selected="selected"' : ''); ?>><?php _e('One select box', 'zeta'); ?></option>
          </select>

          <div class="mb-explain"><?php _e('Select what type of category selection (box) will be used on publish/edit page.', 'zeta'); ?></div>
        </div>
        

        <div class="mb-row">
          <label for="publish_location" class=""><span><?php _e('Location selection on Publish page', 'zeta'); ?></span></label> 
          <select name="publish_location" id="publish_location">
            <option value="0" <?php echo (zet_param('publish_location') == 0 ? 'selected="selected"' : ''); ?>><?php _e('Cascading dropdowns (Country > Region > City)', 'zeta'); ?></option>
            <option value="1" <?php echo (zet_param('publish_location') == 1 ? 'selected="selected"' : ''); ?>><?php _e('Auto-complete box', 'zeta'); ?></option>
          </select>
          
          <div class="mb-explain"><?php _e('Auto-complete box can be right choice in case your regions has thousands of cities and it is hard to scroll to proper city.', 'zeta'); ?></div>
        </div>


        <div class="mb-row mb-row-select-multiple">
          <label for="post_required" class=""><span><?php _e('Required Fields on Publish', 'zeta'); ?></span></label> 

          <input type="hidden" name="post_required" id="post_required" value="<?php echo $post_required; ?>"/>
          <select id="post_required_multiple" name="post_required_multiple" multiple>
            <option value="" <?php if($post_required == '') { ?>selected="selected"<?php } ?>><?php _e('None', 'zeta'); ?></option>
            <option value="country" <?php if(in_array('country', $post_required_array)) { ?>selected="selected"<?php } ?>><?php _e('Country', 'zeta'); ?></option>
            <option value="region" <?php if(in_array('region', $post_required_array)) { ?>selected="selected"<?php } ?>><?php _e('Region', 'zeta'); ?></option>
            <option value="city" <?php if(in_array('city', $post_required_array)) { ?>selected="selected"<?php } ?>><?php _e('City', 'zeta'); ?></option>
            <option value="name" <?php if(in_array('name', $post_required_array)) { ?>selected="selected"<?php } ?>><?php _e('Contact Name', 'zeta'); ?></option>
            <option value="phone" <?php if(in_array('phone', $post_required_array)) { ?>selected="selected"<?php } ?>><?php _e('Phone', 'zeta'); ?></option>
          </select>

          <div class="mb-explain"><?php _e('If you select Location as required, it means that one of following fields must be filled: Country, Region or City', 'zeta'); ?></div>
        </div>

        <div class="mb-row mb-row-select-multiple">
          <label for="post_extra_exclude" class="h26"><span><?php _e('Extra Fields exclude Categories', 'zeta'); ?></span></label> 
  
          <input type="hidden" name="post_extra_exclude" id="post_extra_exclude" value="<?php echo $post_extra_exclude; ?>"/>
          <select id="post_extra_exclude_multiple" name="post_extra_exclude_multiple" multiple>
            <?php echo zet_cat_list($post_extra_exclude_array); ?>
          </select>

          <div class="mb-explain"><?php _e('Select categories where you do not want to show Transaction and Condition on listing publish/edit page', 'zeta'); ?></div>
        </div>



        <div class="mb-row"><h3 class="mb-subtitle"><?php _e('Other settings', 'zeta'); ?></h3></div>

        <div class="mb-row">
          <label for="gallery_ratio" class=""><span><?php _e('Image Gallery Aspect Ratio', 'zeta'); ?></span></label> 
          <select name="gallery_ratio" id="gallery_ratio">
            <option value="" <?php echo (zet_param('gallery_ratio') == '' ? 'selected="selected"' : ''); ?>><?php _e('Calculated based on Image Normal Size', 'zeta'); ?></option>
            <option value="1:1" <?php echo (zet_param('gallery_ratio') == '1:1' ? 'selected="selected"' : ''); ?>><?php _e('Square (1:1)', 'zeta'); ?></option>
            <option value="4:3" <?php echo (zet_param('gallery_ratio') == '4:3' ? 'selected="selected"' : ''); ?>><?php _e('Normal (4:3)', 'zeta'); ?></option>
            <option value="16:9" <?php echo (zet_param('gallery_ratio') == '16:9' ? 'selected="selected"' : ''); ?>><?php _e('Wide (16:9)', 'zeta'); ?></option>
            <option value="2:1" <?php echo (zet_param('gallery_ratio') == '2:1' ? 'selected="selected"' : ''); ?>><?php _e('Very wide (2:1)', 'zeta'); ?></option>
          </select>
          
          <div class="mb-explain"><?php _e('Select image gallery aspect ratio shown on listing page.', 'zeta'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="footer_link" class=""><span><?php _e('Footer Link', 'zeta'); ?></span></label> 
          <input name="footer_link" id="footer_link" class="element-slide" type="checkbox" <?php echo (zet_param('footer_link') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Link to osclass will be shown in footer to support our project.', 'zeta'); ?></div>
        </div>

        <div class="mb-row">
          <label for="public_items" class=""><span><?php _e('Number of Items on Public Profile', 'zeta'); ?></span></label> 
          <input size="8" name="public_items" id="public_items" type="number" value="<?php echo zet_param('public_items'); ?>" />

          <div class="mb-explain"><?php _e('How many listings will be shown on user public profile. Keep in mind that pagination is not available on public profile.', 'zeta'); ?></div>
        </div>

        <div class="mb-row">
          <label for="alert_items" class=""><span><?php _e('Number of Items in Alerts section', 'zeta'); ?></span></label> 
          <input size="8" name="alert_items" id="alert_items" type="number" value="<?php echo zet_param('alert_items'); ?>" />

          <div class="mb-explain"><?php _e('How many listings will be shown under each alert in alerts section of user account. Keep in mind that pagination is not available in alerts section.', 'zeta'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="profile_location" class=""><span><?php _e('Location selection on Profile page', 'zeta'); ?></span></label> 
          <select name="profile_location" id="profile_location">
            <option value="0" <?php echo (zet_param('profile_location') == 0 ? 'selected="selected"' : ''); ?>><?php _e('Cascading dropdowns (Country > Region > City)', 'zeta'); ?></option>
            <option value="1" <?php echo (zet_param('profile_location') == 1 ? 'selected="selected"' : ''); ?>><?php _e('Auto-complete box', 'zeta'); ?></option>
          </select>
          
          <div class="mb-explain"><?php _e('Auto-complete box can be right choice in case your regions has thousands of cities and it is hard to scroll to proper city.', 'zeta'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="generate_favicons" class=""><span><?php _e('Generate Favicons', 'zeta'); ?></span></label> 
          <input name="generate_favicons" id="generate_favicons" class="element-slide" type="checkbox" <?php echo (zet_param('generate_favicons') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain">
            <div class="mb-line"><?php _e('When enabled, favicons are generated in head of website. In some cases you may want to disable this, i.e. if you have it generated by plugin or manually.', 'zeta'); ?></div>
            <div class="mb-line"><?php echo sprintf(__('You can upload your favicons into folder %s. Sample data can be found in /sample. Keep naming convention.', 'zeta'), 'oc-content/themes/zeta/images/favicons/'); ?></div>
          </div>
        </div>

        <div class="mb-row">
          <label for="sample_favicons" class=""><span><?php _e('Use Sample Favicons', 'zeta'); ?></span></label> 
          <input name="sample_favicons" id="sample_favicons" class="element-slide" type="checkbox" <?php echo (zet_param('sample_favicons') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php echo sprintf(__('When enabled, sample favicons from theme folder (%s) will be used.', 'zeta'), 'oc-content/themes/zeta/images/favicons/sample/'); ?></div>
        </div>

        <div class="mb-row">
          <label for="shorten_description" class=""><span><?php _e('Shorten Item Description', 'zeta'); ?></span></label> 
          <input name="shorten_description" id="shorten_description" class="element-slide" type="checkbox" <?php echo (zet_param('shorten_description') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('When enabled, on listing page only first 720 characters of description will be shown. If description is longer, it will be truncated and "Read more" button added.', 'zeta'); ?></div>
        </div>

        <div class="mb-row">
          <label for="asset_versioning" class=""><span><?php _e('Assets Versioning', 'zeta'); ?></span></label> 
          <input name="asset_versioning" id="asset_versioning" class="element-slide" type="checkbox" <?php echo (zet_param('asset_versioning') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('When enabled, version based on current timestamp will be added to each style & script link in order to remove caching. No effect on plugins/osclass scripts & styles.', 'zeta'); ?> (https://yoursite.com/style.css?v=<?php echo date('YmdHis'); ?>)</div>
        </div>




        <div class="mb-row"><h3 class="mb-subtitle"><?php _e('Social Network Links', 'zeta'); ?></h3></div>

        <div class="mb-row">
          <label for="footer_social_define" class=""><span><?php _e('Define Social Links', 'zeta'); ?></span></label> 
          <input name="footer_social_define" id="footer_social_define" class="element-slide" type="checkbox" <?php echo (zet_param('footer_social_define') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('When enabled, social links (in footer) will point to URLs defined here. If empty, link to that network will be hidden. Otherwise link is auto-generated (share link).', 'zeta'); ?></div>
        </div>

        <div class="mb-row">
          <label for="footer_social_whatsapp" class=""><span><?php _e('Whatsapp', 'zeta'); ?></span></label> 
          <input size="80" name="footer_social_whatsapp" id="footer_social_whatsapp" type="text" value="<?php echo osc_esc_html(zet_param('footer_social_whatsapp')); ?>" />

          <div class="mb-explain"><?php _e('Define URL that points to your company URL on network. Keep blank to hide network link.', 'zeta'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="footer_social_facebook" class=""><span><?php _e('Facebook', 'zeta'); ?></span></label> 
          <input size="80" name="footer_social_facebook" id="footer_social_facebook" type="text" value="<?php echo osc_esc_html(zet_param('footer_social_facebook')); ?>" />

          <div class="mb-explain"><?php _e('Define URL that points to your company URL on network. Keep blank to hide network link.', 'zeta'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="footer_social_pinterest" class=""><span><?php _e('Pinterest', 'zeta'); ?></span></label> 
          <input size="80" name="footer_social_pinterest" id="footer_social_pinterest" type="text" value="<?php echo osc_esc_html(zet_param('footer_social_pinterest')); ?>" />

          <div class="mb-explain"><?php _e('Define URL that points to your company URL on network. Keep blank to hide network link.', 'zeta'); ?></div>
        </div>

        <div class="mb-row">
          <label for="footer_social_instagram" class=""><span><?php _e('Instagram', 'zeta'); ?></span></label> 
          <input size="80" name="footer_social_instagram" id="footer_social_instagram" type="text" value="<?php echo osc_esc_html(zet_param('footer_social_instagram')); ?>" />

          <div class="mb-explain"><?php _e('Define URL that points to your company URL on network. Keep blank to hide network link.', 'zeta'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="footer_social_x" class=""><span><?php _e('X (Twitter)', 'zeta'); ?></span></label> 
          <input size="80" name="footer_social_x" id="footer_social_x" type="text" value="<?php echo osc_esc_html(zet_param('footer_social_x')); ?>" />

          <div class="mb-explain"><?php _e('Define URL that points to your company URL on network. Keep blank to hide network link.', 'zeta'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="footer_social_linkedin" class=""><span><?php _e('Linkedin', 'zeta'); ?></span></label> 
          <input size="80" name="footer_social_linkedin" id="footer_social_linkedin" type="text" value="<?php echo osc_esc_html(zet_param('footer_social_linkedin')); ?>" />

          <div class="mb-explain"><?php _e('Define URL that points to your company URL on network. Keep blank to hide network link.', 'zeta'); ?></div>
        </div>

        
        <div class="mb-row">&nbsp;</div>

        <div class="mb-foot">
          <button type="submit" class="mb-button"><?php _e('Save', 'zeta');?></button>
        </div>
      </form>
    </div>
  </div>

</div>


<?php echo zet_footer(); ?>	