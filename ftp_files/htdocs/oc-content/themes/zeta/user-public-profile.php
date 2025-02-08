<?php
  $user = osc_user();

  $user_location_array = array(osc_user_address(), osc_user_zip(), osc_user_city_area(), osc_user_city(), osc_user_region(), osc_user_country());
  $user_location_array = array_filter($user_location_array);
  $user_location = implode(', ', $user_location_array);

  $is_company = false;
  $user_item_count = $user['i_items'];

  if($user['b_company'] == 1) {
    $is_company = true;
  }

  // GET REGISTRATION DATE AND TYPE
  $reg_type = '';
  $last_online = '';

  if($user && $user['dt_reg_date'] <> '') { 
    $reg_type = sprintf(__('Registered for %s', 'zeta'), zet_smart_date2($user['dt_reg_date']));
  } else if($user) { 
    $reg_type = __('Registered user', 'zeta');
  } else {
    $reg_type = __('Unregistered user', 'zeta');
  }

  if($user) {
    $last_online = sprintf(__('Last online %s', 'zeta'), zet_smart_date($user['dt_access_date']));
  }

  $user_about = nl2br(strip_tags(osc_user_info()));
  $contact_name = (osc_user_name() <> '' ? osc_user_name() : __('Anonymous', 'zeta'));

  $user_phone_mobile_data = zet_get_phone($user['s_phone_mobile']);
  $user_phone_land_data = zet_get_phone($user['s_phone_land']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo zet_language_dir(); ?>" mode="<?php echo zet_light_dark_mode(); ?>" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php'); ?>
  <meta name="robots" content="index, follow" />
  <meta name="googlebot" content="index, follow" />
</head>

<body id="public" class="<?php osc_run_hook('body_class'); ?>">
  <?php 
    View::newInstance()->_exportVariableToView('user', $user);
    osc_current_web_theme_path('header.php');
    View::newInstance()->_exportVariableToView('user', $user); 
  ?>

  <div class="container primary">

    <!-- LISTINGS OF SELLER -->
    <div id="public-main">
      <?php echo zet_banner('public_profile_top'); ?>

      <!-- SELLER BLOCK -->
      <div class="box" id="meet-seller">
        <h1>
          <span><?php echo sprintf(__('%s\'s profile', 'zeta'), $contact_name); ?></span>
          <div class="ln"></div>
        </h1>

        <div class="wrap">
          <div class="img-wrap">
            <img src="<?php echo zet_profile_picture(osc_user_id(), 'small'); ?>" alt="<?php echo osc_esc_html($contact_name); ?>" class="uimg" />
            <strong class="isMobile"><?php echo sprintf(__('%s\'s profile', 'zeta'), $contact_name); ?></strong>

            <?php if(function_exists('ur_show_rating_link')) { ?>
              <div class="line-rating isMobile">
                <span class="ur-fdb">
                  <span class="strs"><?php echo ur_show_rating_stars(osc_user_id(), osc_user_email()); ?></span>
                </span>
              </div>
            <?php } ?>
          </div>

          <div id="share">
            <?php osc_reset_resources(); ?>
            <a class="whatsapp isMobile" href="whatsapp://send?text=<?php echo urlencode(osc_user_public_profile_url(osc_user_id())); ?>" data-action="share/whatsapp/share"><i class="fab fa-whatsapp"></i></a></span>
            <a class="facebook" title="<?php echo osc_esc_html(__('Share on Facebook', 'zeta')); ?>" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(osc_user_public_profile_url(osc_user_id())); ?>"><i class="fab fa-facebook-f"></i></a> 
            <a class="twitter" title="<?php echo osc_esc_html(__('Share on X (Twitter)', 'zeta')); ?>" target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo urlencode(meta_title()); ?>&url=<?php echo urlencode(osc_user_public_profile_url(osc_user_id())); ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="15px" height="15px"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg></a> 
            <a class="pinterest" title="<?php echo osc_esc_html(__('Share on Pinterest', 'zeta')); ?>" target="_blank" href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode(osc_user_public_profile_url(osc_user_id())); ?>&media=<?php echo zet_profile_picture(osc_user_id(), 'large'); ?>&description=<?php echo htmlspecialchars(meta_title()); ?>"><i class="fab fa-pinterest-p"></i></a> 
          </div>

          <div class="info1">
            <?php if(function_exists('ur_show_rating_link')) { ?>
              <div class="line-rating">
                <span class="ur-fdb">
                  <span class="strs"><?php echo ur_show_rating_stars(osc_user_id(), osc_user_email()); ?></span>
                </span>
              </div>
            <?php } ?>
            
            <?php if($user_location != '') { ?>
              <div class="address"><i class="fas fa-map-marker-alt"></i> <?php echo $user_location; ?></div>
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

            <?php if($user_about <> '') { ?>
              <div class="box" id="about">
                <strong><?php _e('About seller', 'zeta'); ?></strong>
                <div><?php echo $user_about; ?></div>
              </div>
            <?php } ?>
            
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
            <?php if(getBoolPreference('item_contact_form_disabled') != 1) { ?>
              <a href="<?php echo zet_item_fancy_url('contact_public', array('userId' => osc_user_id())); ?>" class="open-form public-contact master-button" data-type="contact_public"><i class="fas fa-envelope"></i> <?php _e('Send message', 'zeta'); ?></a>
            <?php } ?>
      
            <a href="<?php echo osc_search_url(array('page' => 'search', 'userId' => osc_user_id())); ?>" class="btn btn-white"><?php echo __('All seller items', 'zeta') . ' (' . $user_item_count . ')'; ?></a>

            <?php if(trim(osc_user_website()) <> '') { ?>
              <a href="<?php echo osc_user_website(); ?>" target="_blank" rel="nofollow noreferrer" class="btn btn-white">
                <i class="fas fa-external-link-alt"></i>
                <span><?php echo rtrim(str_replace(array('https://', 'http://'), '', osc_user_website()), '/'); ?></span>
              </a>
            <?php } ?>
          </div>
        </div>
      </div>

      <?php osc_run_hook('user_public_profile_items_top'); ?>
      

      <h2><?php _e('Latest listings', 'zeta'); ?></h2>

      <?php if(osc_count_items() > 0) { ?>
        <div class="products list">
          <?php 
            $c = 1; 
            while(osc_has_items()) {
              zet_draw_item($c);

              if($c == 3 && osc_count_items() > 3) {
                echo zet_banner('public_profile_middle');
              }

              $c++;
            } 
          ?>
        </div>
        
        <div class="paginate"><?php echo zet_fix_arrow(osc_pagination_items()); ?></div>

      <?php } else { ?>
        <div class="empty"><?php _e('User has no active listings', 'zeta'); ?></div>
      <?php } ?>

      <?php echo zet_banner('public_profile_bottom'); ?>
    </div>
    

    <div id="public-side-banner">
      <?php osc_run_hook('user_public_profile_sidebar_top'); ?>
      
      <span class="lab"><?php _e('Advertisement', 'zeta'); ?></span>
      <?php echo zet_banner('public_profile_sidebar'); ?>
      
      <?php osc_run_hook('user_public_profile_sidebar_bottom'); ?>
    </div>
    
  </div>

  <?php osc_current_web_theme_path('footer.php'); ?>
</body>
</html>