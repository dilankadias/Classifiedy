</div>

<?php 
  $location_cookie = zet_location_from_cookies();
  $mes_counter = zet_count_messages(osc_logged_user_id()); 
  $fav_counter = zet_count_favorite();
  $alert_counter = zet_count_alerts();
  
  $indicator = '<svg class="indicator" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512" width="20px" height="20px"><path d="M24.707 38.101L4.908 57.899c-4.686 4.686-4.686 12.284 0 16.971L185.607 256 4.908 437.13c-4.686 4.686-4.686 12.284 0 16.971L24.707 473.9c4.686 4.686 12.284 4.686 16.971 0l209.414-209.414c4.686-4.686 4.686-12.284 0-16.971L41.678 38.101c-4.687-4.687-12.285-4.687-16.971 0z"/></svg>';
?>

<?php if(!osc_is_web_user_logged_in()) { ?>
  <section class="promo">
    <div class="container">
      <span><?php _e('Are you a professional seller?', 'zeta'); ?></span>
      <a href="<?php echo osc_register_account_url(); ?>" class="btn btn-transparent"><?php _e('Create an account', 'zeta'); ?></a>
    </div>
  </section>
<?php } ?>

<?php osc_run_hook('footer_pre'); ?>

<footer>
  <?php osc_run_hook('footer_top'); ?>
  
  <div class="container">
    <section class="zero">
      <p class="logo"><?php echo zet_logo(); ?></p>
    </section>
    
    <section class="one">
      <div class="col contact">
        <h4>
          <?php 
            if(zet_param('site_name') <> '') {
              echo zet_param('site_name');
            } else {
              _e('About us', 'zeta'); 
            }  
          ?>
        </h4>

        <?php if(zet_param('site_phone') <> '') { ?><p><?php echo __('Phone', 'zeta') . ': ' . zet_param('site_phone'); ?></p><?php } ?>
        <?php if(zet_param('site_email') <> '') { ?><p><?php echo __('Email', 'zeta') . ': ' . zet_param('site_email'); ?></p><?php } ?>
        <?php if(zet_param('site_address') <> '') { ?><p><?php echo zet_param('site_address'); ?></p><?php } ?>

        <p class="txt"><?php _e('Widely known as Worlds no. 1 online classifieds platform, our is all about you. Our aim is to empower every person in the country to independently connect with buyers and sellers online.', 'zeta'); ?></p>

        <?php if(zet_param('enable_dark_mode') == 1) { ?>
          <a href="#" class="switch-ld1 switch-light-dark-mode mode-<?php echo zet_light_dark_mode(); ?>" data-label-light="<?php echo osc_esc_html(__('Switch to light mode', 'zeta')); ?>" data-label-dark="<?php echo osc_esc_html(__('Switch to dark mode', 'zeta')); ?>">
            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 56c110.549 0 200 89.468 200 200 0 110.549-89.468 200-200 200-110.549 0-200-89.468-200-200 0-110.549 89.468-200 200-200m0-48C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 96c-83.947 0-152 68.053-152 152s68.053 152 152 152V104z"/></svg>
            <span>
              <?php 
                if(zet_light_dark_mode() == 'dark') { 
                  _e('Switch to light mode', 'zeta');
                } else {
                  _e('Switch to dark mode', 'zeta');
                }
              ?>
            </span>
          </a>
        <?php } ?>
      </div>
      
      <div class="col social">
        <h4><?php _e('Social media', 'zeta'); ?></h4>

        <?php osc_reset_resources(); ?>
        
        <?php if(zet_get_social_link('whatsapp') !== false) { ?>
          <a class="whatsapp" href="<?php echo zet_get_social_link('whatsapp'); ?>" data-action="share/whatsapp/share"><i class="fab fa-whatsapp"></i> <?php _e('Whatsapp', 'zeta'); ?></a>
        <?php } ?>

        <?php if(zet_get_social_link('facebook') !== false) { ?>
          <a class="facebook" href="<?php echo zet_get_social_link('facebook'); ?>" title="<?php echo osc_esc_html(__('Share us on Facebook', 'zeta')); ?>" target="_blank"><i class="fab fa-facebook-f"></i> <?php _e('Facebook', 'zeta'); ?></a>
        <?php } ?>

        <?php if(zet_get_social_link('pinterest') !== false) { ?>
          <a class="pinterest" href="<?php echo zet_get_social_link('pinterest'); ?>" title="<?php echo osc_esc_html(__('Share us on Pinterest', 'zeta')); ?>" target="_blank"><i class="fab fa-pinterest-p"></i> <?php _e('Pinterest', 'zeta'); ?></a>
        <?php } ?>

        <?php if(zet_get_social_link('instagram') !== false) { ?>
          <a class="instagram" href="<?php echo zet_get_social_link('instagram'); ?>" title="<?php echo osc_esc_html(__('Share us on Instagram', 'zeta')); ?>" target="_blank"><i class="fab fa-instagram"></i> <?php _e('Instagram', 'zeta'); ?></a>
        <?php } ?>
        
        <?php if(zet_get_social_link('x') !== false) { ?>
          <a class="twitter" href="<?php echo zet_get_social_link('x'); ?>" title="<?php echo osc_esc_html(__('Tweet us', 'zeta')); ?>" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="15px" height="15px"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg>
            <?php _e('Twitter', 'zeta'); ?>
          </a>
        <?php } ?>

        <?php if(zet_get_social_link('linkedin') !== false) { ?>
          <a class="linkedin" href="<?php echo zet_get_social_link('linkedin'); ?>" title="<?php echo osc_esc_html(__('Share us on LinkedIn', 'zeta')); ?>" target="_blank"><i class="fab fa-linkedin"></i> <?php _e('LinkedIn', 'zeta'); ?></a>
        <?php } ?>
      </div>

      <?php if(osc_count_web_enabled_locales() > 1) { ?>
        <div class="col locale">
          <h4><?php _e('Language', 'zeta'); ?></h4>
          <?php osc_goto_first_locale(); ?>

          <?php while(osc_has_web_enabled_locales()) { ?>
            <a class="lang <?php if(osc_locale_code() == osc_current_user_locale()) { ?>active<?php } ?>" href="<?php echo osc_change_language_url(osc_locale_code()); ?>">
              <img src="<?php echo zet_country_flag_image(strtolower(substr(osc_locale_code(), 3))); ?>" alt="<?php echo osc_esc_html(__('Country flag', 'zeta')); ?>" />
              <span><?php echo osc_locale_name(); ?>&#x200E;</span>
            </a>
          <?php } ?>
        </div>
      <?php } ?>

      <div class="col pages">
        <h4><?php _e('Information', 'zeta'); ?></h4>

        <?php osc_reset_static_pages(); ?>
       
        <?php while(osc_has_static_pages()) { ?>
          <a href="<?php echo osc_static_page_url(); ?>"><?php echo osc_static_page_title();?></a>
        <?php } ?>
        
        <?php if(zet_param('footer_link')) { ?>
          <a href="https://osclass-classifieds.com">Osclass Classifieds</a>
        <?php } ?>
      </div>
      
      <div class="footer-hook"><?php osc_run_hook('footer'); ?></div>
      <div class="footer-widgets"><?php osc_show_widgets('footer'); ?></div>
    </section>
    
    <section class="two">
      <?php if(getBoolPreference('web_contact_form_disabled') != 1) { ?>
        <a href="<?php echo osc_contact_url(); ?>"><?php _e('Contact us', 'zeta'); ?></a>
      <?php } ?>
      
      <?php if(zet_param('footer_link')) { ?>
        <a href="https://osclasspoint.com">Osclass Market</a>
      <?php } ?>
      
      <?php osc_run_hook('footer_links'); ?>
      
      <span><?php _e('Copyright', 'zeta'); ?> &copy; <?php echo date('Y'); ?> <?php echo zet_param('site_name'); ?> <?php _e('All rights reserved', 'zeta'); ?>.</span>
    </section>
  </div>
</footer>

<?php osc_run_hook('footer_after'); ?>

<div id="navi-bar" class="isMobile">
  <?php if(1==2) { ?>
    <a href="<?php echo osc_base_url(); ?>" class="btn-regular <?php if(osc_is_home_page()) { ?>active<?php } ?>">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="20" height="20"><path d="M541 229.16l-61-49.83v-77.4a6 6 0 0 0-6-6h-20a6 6 0 0 0-6 6v51.33L308.19 39.14a32.16 32.16 0 0 0-40.38 0L35 229.16a8 8 0 0 0-1.16 11.24l10.1 12.41a8 8 0 0 0 11.2 1.19L96 220.62v243a16 16 0 0 0 16 16h128a16 16 0 0 0 16-16v-128l64 .3V464a16 16 0 0 0 16 16l128-.33a16 16 0 0 0 16-16V220.62L520.86 254a8 8 0 0 0 11.25-1.16l10.1-12.41a8 8 0 0 0-1.21-11.27zm-93.11 218.59h.1l-96 .3V319.88a16.05 16.05 0 0 0-15.95-16l-96-.27a16 16 0 0 0-16.05 16v128.14H128V194.51L288 63.94l160 130.57z"/></svg>
      <span><?php _e('Home', 'zeta'); ?></span>
    </a>
  <?php } ?>

  <?php if(zet_param('default_location') == 1) { ?>
    <a class="btn-regular location <?php if(@$location_cookie['success'] === true) { ?>selected<?php } ?>" href="#">
      <?php if(@$location_cookie['success'] === true) { ?><i class="mark fas fa-map-marker-alt"></i><?php } ?>
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" width="20" height="20"><path d="M264.97 272.97c9.38-9.37 9.38-24.57 0-33.94-9.37-9.37-24.57-9.37-33.94 0-9.38 9.37-9.38 24.57 0 33.94 9.37 9.37 24.57 9.37 33.94 0zM351.44 125c-2.26 0-4.51.37-6.71 1.16l-154.9 55.85c-7.49 2.7-13.1 8.31-15.8 15.8l-55.85 154.91c-5.65 15.67 10.33 34.27 26.4 34.27 2.26 0 4.51-.37 6.71-1.16l154.9-55.85c7.49-2.7 13.1-8.31 15.8-15.8l55.85-154.9c5.64-15.67-10.33-34.28-26.4-34.28zm-58.65 175.79l-140.1 50.51 50.51-140.11 140.11-50.51-50.52 140.11zM248 8C111.03 8 0 119.03 0 256s111.03 248 248 248 248-111.03 248-248S384.97 8 248 8zm0 464c-119.1 0-216-96.9-216-216S128.9 40 248 40s216 96.9 216 216-96.9 216-216 216z"/></svg>
      
      <span>
        <?php 
          if(@$location_cookie['success'] !== true) {
            _e('Location', 'zeta');
          } else {
            $loc_ = explode(',', osc_location_native_name_selector($location_cookie, 's_name'));
            echo @$loc_[0];
          }
        ?>
      </span>
    </a>

  <?php } else if(getBoolPreference('web_contact_form_disabled') != 1) { ?>
    <a href="<?php echo osc_contact_url(); ?>" class="btn-regular <?php if(osc_is_contact_page()) { ?>active<?php } ?>">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M349.32 52.26C328.278 35.495 292.938 0 256 0c-36.665 0-71.446 34.769-93.31 52.26-34.586 27.455-109.525 87.898-145.097 117.015A47.99 47.99 0 0 0 0 206.416V464c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V206.413a47.989 47.989 0 0 0-17.597-37.144C458.832 140.157 383.906 79.715 349.32 52.26zM464 480H48c-8.837 0-16-7.163-16-16V206.161c0-4.806 2.155-9.353 5.878-12.392C64.16 172.315 159.658 95.526 182.59 77.32 200.211 63.27 232.317 32 256 32c23.686 0 55.789 31.27 73.41 45.32 22.932 18.207 118.436 95.008 144.714 116.468a15.99 15.99 0 0 1 5.876 12.39V464c0 8.837-7.163 16-16 16zm-8.753-216.312c4.189 5.156 3.393 12.732-1.776 16.905-22.827 18.426-55.135 44.236-104.156 83.148-21.045 16.8-56.871 52.518-93.318 52.258-36.58.264-72.826-35.908-93.318-52.263-49.015-38.908-81.321-64.716-104.149-83.143-5.169-4.173-5.966-11.749-1.776-16.905l5.047-6.212c4.169-5.131 11.704-5.925 16.848-1.772 22.763 18.376 55.014 44.143 103.938 82.978 16.85 13.437 50.201 45.69 73.413 45.315 23.219.371 56.562-31.877 73.413-45.315 48.929-38.839 81.178-64.605 103.938-82.978 5.145-4.153 12.679-3.359 16.848 1.772l5.048 6.212z"/></svg>
      <span><?php _e('Contact us', 'zeta'); ?></span>
    </a>
  <?php } ?>
  
  <?php if(function_exists('im_messages')) { ?>
    <a href="<?php echo osc_route_url('im-threads'); ?>" class="btn-regular <?php if(osc_get_osclass_location() == 'im') { ?>active<?php } ?>">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M128 216c-13.3 0-24 10.7-24 24s10.7 24 24 24 24-10.7 24-24-10.7-24-24-24zm128 0c-13.3 0-24 10.7-24 24s10.7 24 24 24 24-10.7 24-24-10.7-24-24-24zm128 0c-13.3 0-24 10.7-24 24s10.7 24 24 24 24-10.7 24-24-10.7-24-24-24zM256 32C114.6 32 0 125.1 0 240c0 47.6 19.9 91.2 52.9 126.3C38 405.7 7 439.1 6.5 439.5c-6.6 7-8.4 17.2-4.6 26S14.4 480 24 480c61.5 0 110-25.7 139.1-46.3C192 442.8 223.2 448 256 448c141.4 0 256-93.1 256-208S397.4 32 256 32zm0 384c-28.3 0-56.3-4.3-83.2-12.8l-15.2-4.8-13 9.2c-23 16.3-58.5 35.3-102.6 39.6 12-15.1 29.8-40.4 40.8-69.6l7.1-18.7-13.7-14.6C47.3 313.7 32 277.6 32 240c0-97 100.5-176 224-176s224 79 224 176-100.5 176-224 176z"/></svg>
      
      <?php if($mes_counter > 0) { ?>
        <span class="counter"><?php echo $mes_counter; ?></span>
      <?php } ?>    
      
      <span><?php _e('Messages', 'zeta'); ?></span>
    </a>
  <?php } else { ?>
    <a href="<?php echo osc_search_url(array('page' => 'search')); ?>" class="btn-regular <?php if(osc_is_search_page()) { ?>active<?php } ?>">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z"/></svg>
      <span><?php _e('Search', 'zeta'); ?></span>
    </a>
  <?php } ?>

  <?php if(1==2) { ?>
  <a href="<?php echo osc_item_post_url(); ?>" class="btn-regular <?php if(osc_is_publish_page() || osc_is_edit_page()) { ?>active<?php } ?>">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M384 250v12c0 6.6-5.4 12-12 12h-98v98c0 6.6-5.4 12-12 12h-12c-6.6 0-12-5.4-12-12v-98h-98c-6.6 0-12-5.4-12-12v-12c0-6.6 5.4-12 12-12h98v-98c0-6.6 5.4-12 12-12h12c6.6 0 12 5.4 12 12v98h98c6.6 0 12 5.4 12 12zm120 6c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-32 0c0-119.9-97.3-216-216-216-119.9 0-216 97.3-216 216 0 119.9 97.3 216 216 216 119.9 0 216-97.3 216-216z"/></svg>
    <span><?php _e('Post an ad', 'zeta'); ?></span>
  </a>
  <?php } ?>
  
  <?php if(function_exists('fi_make_favorite')) { ?>
    <a href="<?php echo osc_route_url('favorite-lists'); ?>" class="btn-regular favorite <?php if(osc_get_osclass_location() == 'fi') { ?>active<?php } ?>">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" height="20" width="20"><path d="M462.3 62.7c-54.5-46.4-136-38.7-186.6 13.5L256 96.6l-19.7-20.3C195.5 34.1 113.2 8.7 49.7 62.7c-62.8 53.6-66.1 149.8-9.9 207.8l193.5 199.8c6.2 6.4 14.4 9.7 22.6 9.7 8.2 0 16.4-3.2 22.6-9.7L472 270.5c56.4-58 53.1-154.2-9.7-207.8zm-13.1 185.6L256.4 448.1 62.8 248.3c-38.4-39.6-46.4-115.1 7.7-161.2 54.8-46.8 119.2-12.9 142.8 11.5l42.7 44.1 42.7-44.1c23.2-24 88.2-58 142.8-11.5 54 46 46.1 121.5 7.7 161.2z"/></svg>

      <?php if($fav_counter > 0) { ?>
        <span class="counter"><?php echo $fav_counter; ?></span>
      <?php } ?>    
      
      <span><?php _e('Favorite', 'zeta'); ?></span>
    </a>

  <?php } else { ?>
    <a href="<?php echo osc_is_web_user_logged_in() ? osc_user_dashboard_url() : osc_user_login_url(); ?>" class="btn-regular <?php if(in_array(osc_get_osclass_location(), array('user','login','recover','forgot','register'))) { ?>active<?php } ?>">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20"><path d="M313.6 304c-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 304 0 364.2 0 438.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-25.6c0-74.2-60.2-134.4-134.4-134.4zM400 464H48v-25.6c0-47.6 38.8-86.4 86.4-86.4 14.6 0 38.3 16 89.6 16 51.7 0 74.9-16 89.6-16 47.6 0 86.4 38.8 86.4 86.4V464zM224 288c79.5 0 144-64.5 144-144S303.5 0 224 0 80 64.5 80 144s64.5 144 144 144zm0-240c52.9 0 96 43.1 96 96s-43.1 96-96 96-96-43.1-96-96 43.1-96 96-96z"/></svg>
      <span><?php echo osc_is_web_user_logged_in() ? __('My Account', 'zeta') : __('Sign in', 'zeta'); ?></span>
    </a>
  <?php } ?>

  <?php if(osc_is_web_user_logged_in()) { ?>
    <a href="<?php echo osc_user_alerts_url(); ?>" class="alerts btn-regular">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20"><path d="M224 480c-17.66 0-32-14.38-32-32.03h-32c0 35.31 28.72 64.03 64 64.03s64-28.72 64-64.03h-32c0 17.65-14.34 32.03-32 32.03zm209.38-145.19c-27.96-26.62-49.34-54.48-49.34-148.91 0-79.59-63.39-144.5-144.04-152.35V16c0-8.84-7.16-16-16-16s-16 7.16-16 16v17.56C127.35 41.41 63.96 106.31 63.96 185.9c0 94.42-21.39 122.29-49.35 148.91-13.97 13.3-18.38 33.41-11.25 51.23C10.64 404.24 28.16 416 48 416h352c19.84 0 37.36-11.77 44.64-29.97 7.13-17.82 2.71-37.92-11.26-51.22zM400 384H48c-14.23 0-21.34-16.47-11.32-26.01 34.86-33.19 59.28-70.34 59.28-172.08C95.96 118.53 153.23 64 224 64c70.76 0 128.04 54.52 128.04 121.9 0 101.35 24.21 138.7 59.28 172.08C421.38 367.57 414.17 384 400 384z"/></svg>

      <?php if($alert_counter > 0) { ?>
        <span class="counter"><?php echo $alert_counter; ?></span>
      <?php } ?>    
      
      <span><?php _e('Searches', 'zeta'); ?></span>
    </a>
    
  <?php } else { ?>
    <a href="<?php echo osc_user_login_url(); ?>" class="btn-regular">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M256 32c61.8 0 112 50.2 112 112s-50.2 112-112 112-112-50.2-112-112S194.2 32 256 32m128 320c52.9 0 96 43.1 96 96v32H32v-32c0-52.9 43.1-96 96-96 85 0 67.3 16 128 16 60.9 0 42.9-16 128-16M256 0c-79.5 0-144 64.5-144 144s64.5 144 144 144 144-64.5 144-144S335.5 0 256 0zm128 320c-92.4 0-71 16-128 16-56.8 0-35.7-16-128-16C57.3 320 0 377.3 0 448v32c0 17.7 14.3 32 32 32h448c17.7 0 32-14.3 32-32v-32c0-70.7-57.3-128-128-128z"/></svg>
      <span><?php _e('Log in', 'zeta'); ?></span>
    </a>
  <?php } ?>
  
  <?php if(osc_is_search_page()) { ?>
    <a href="#" class="btn btn-primary all-filters">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16"><path d="M496 72H288V48c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v24H16C7.2 72 0 79.2 0 88v16c0 8.8 7.2 16 16 16h208v24c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-24h208c8.8 0 16-7.2 16-16V88c0-8.8-7.2-16-16-16zm0 320H160v-24c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v24H16c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h80v24c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-24h336c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16zm0-160h-80v-24c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v24H16c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h336v24c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-24h80c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16z"></path></svg>
      <span><?php _e('Filter', 'zeta'); ?></span>
    </a>

  <?php } else { ?>
    <a href="<?php echo osc_item_post_url(); ?>" class="btn btn-primary <?php if(osc_is_publish_page() || osc_is_edit_page()) { ?>active<?php } ?>">
      <span><?php _e('Post ad', 'zeta'); ?></span>
    </a>
  <?php } ?>
</div>

<?php if(zet_banner('body_left') !== false) { ?>
  <div id="body-banner" class="bleft">
    <?php echo zet_banner('body_left'); ?>
  </div>
<?php } ?>

<?php if(zet_banner('body_right') !== false) { ?>
  <div id="body-banner" class="bright">
    <?php echo zet_banner('body_right'); ?>
  </div>
<?php } ?>

<?php if(zet_param('scrolltop') == 1) { ?>
  <a id="scroll-to-top"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="20" height="20"><path d="M24 32h336c13.3 0 24 10.7 24 24v24c0 13.3-10.7 24-24 24H24C10.7 104 0 93.3 0 80V56c0-13.3 10.7-24 24-24zm232 424V320h87.7c17.8 0 26.7-21.5 14.1-34.1L205.7 133.7c-7.5-7.5-19.8-7.5-27.3 0L26.1 285.9C13.5 298.5 22.5 320 40.3 320H128v136c0 13.3 10.7 24 24 24h80c13.3 0 24-10.7 24-24z"/></svg></a>
<?php } ?>

<div id="menu-cover" class="mobile-box">
  <svg class="close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="32px" height="32px"><path fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z" class=""></path></svg>
</div>

<div id="side-menu" class="mobile-box<?php if(osc_is_web_user_logged_in()) { ?> logged<?php } ?>">
  <svg class="close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="32px" height="32px"><path fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z" class=""></path></svg>

  <div class="wrap wmain wrap-scrollable">
    <div class="section lead">
      <a href="<?php echo (!osc_is_web_user_logged_in() ? osc_user_login_url() : osc_user_profile_url()); ?>" target="_blank">
        <img src="<?php echo zet_profile_picture(osc_is_web_user_logged_in() ? osc_logged_user_id() : NULL, 'medium'); ?>" alt="<?php echo osc_esc_html(osc_logged_user_name() <> '' ? osc_logged_user_name() : __('Non-logged user', 'zeta')); ?>" width="36" height="36"/>
        <strong><?php echo osc_is_web_user_logged_in() ? sprintf(__('Hi %s!', 'zeta'), osc_logged_user_name()) : __('Welcome!', 'zeta'); ?></strong>
      </a>
    </div>

    <div class="section">
      <a href="<?php echo osc_item_post_url(); ?>" class="publish">
        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M384 240v32c0 6.6-5.4 12-12 12h-88v88c0 6.6-5.4 12-12 12h-32c-6.6 0-12-5.4-12-12v-88h-88c-6.6 0-12-5.4-12-12v-32c0-6.6 5.4-12 12-12h88v-88c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v88h88c6.6 0 12 5.4 12 12zm120 16c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-48 0c0-110.5-89.5-200-200-200S56 145.5 56 256s89.5 200 200 200 200-89.5 200-200z"/></svg>
        <?php _e('Post an ad', 'zeta'); ?>
      </a>
      
      <a href="<?php echo osc_search_url(array('page' => 'search')); ?>" class="search">
        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z"/></svg>
        <?php _e('Search', 'zeta'); ?>
      </a>
    </div>
      
    <div class="section delim-top">
      <?php if(!osc_is_web_user_logged_in()) { ?>
        <a href="<?php echo osc_user_login_url(); ?>" class="login">
          <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M144 112v51.6H48c-26.5 0-48 21.5-48 48v88.6c0 26.5 21.5 48 48 48h96v51.6c0 42.6 51.7 64.2 81.9 33.9l144-143.9c18.7-18.7 18.7-49.1 0-67.9l-144-144C195.8 48 144 69.3 144 112zm192 144L192 400v-99.7H48v-88.6h144V112l144 144zm80 192h-84c-6.6 0-12-5.4-12-12v-24c0-6.6 5.4-12 12-12h84c26.5 0 48-21.5 48-48V160c0-26.5-21.5-48-48-48h-84c-6.6 0-12-5.4-12-12V76c0-6.6 5.4-12 12-12h84c53 0 96 43 96 96v192c0 53-43 96-96 96z"/></svg>
          <?php _e('Log in', 'zeta'); ?>
        </a>
        
        <a href="<?php echo osc_register_account_url(); ?>" class="register">
          <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M224 288c79.5 0 144-64.5 144-144S303.5 0 224 0 80 64.5 80 144s64.5 144 144 144zm0-240c52.9 0 96 43.1 96 96s-43.1 96-96 96-96-43.1-96-96 43.1-96 96-96zm89.6 256c-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 304 0 364.2 0 438.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-25.6c0-74.2-60.2-134.4-134.4-134.4zM400 464H48v-25.6c0-47.6 38.8-86.4 86.4-86.4 14.6 0 38.3 16 89.6 16 51.7 0 74.9-16 89.6-16 47.6 0 86.4 38.8 86.4 86.4V464zm224-248h-72v-72c0-8.8-7.2-16-16-16h-16c-8.8 0-16 7.2-16 16v72h-72c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h72v72c0 8.8 7.2 16 16 16h16c8.8 0 16-7.2 16-16v-72h72c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16z"/></svg>
          <?php _e('Register account', 'zeta'); ?>
        </a>

      <?php } else { ?>
        <a href="<?php echo osc_user_public_profile_url(osc_logged_user_id()); ?>" class="public">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="24" height="24"><path d="M528 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm0 400H48V80h480v352zM208 256c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm-89.6 128h179.2c12.4 0 22.4-8.6 22.4-19.2v-19.2c0-31.8-30.1-57.6-67.2-57.6-10.8 0-18.7 8-44.8 8-26.9 0-33.4-8-44.8-8-37.1 0-67.2 25.8-67.2 57.6v19.2c0 10.6 10 19.2 22.4 19.2zM360 320h112c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8H360c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8zm0-64h112c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8H360c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8zm0-64h112c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8H360c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8z"/></svg>
          <?php _e('My public profile', 'zeta'); ?>
        </a>

        <a href="<?php echo osc_user_dashboard_url(); ?>" class="dash">
          <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M568 368c-19.1 0-36.3 7.6-49.2 19.7L440.6 343c4.5-12.2 7.4-25.2 7.4-39 0-61.9-50.1-112-112-112-8.4 0-16.6 1.1-24.4 2.9l-32.2-69c15-13.2 24.6-32.3 24.6-53.8 0-39.8-32.2-72-72-72s-72 32.2-72 72 32.2 72 72 72c.9 0 1.8-.2 2.7-.3l33.5 71.7C241.5 235.9 224 267.8 224 304c0 61.9 50.1 112 112 112 30.7 0 58.6-12.4 78.8-32.5l82.2 47c-.4 3.1-1 6.3-1 9.5 0 39.8 32.2 72 72 72s72-32.2 72-72-32.2-72-72-72zM232 96c-13.2 0-24-10.8-24-24s10.8-24 24-24 24 10.8 24 24-10.8 24-24 24zm104 272c-35.3 0-64-28.7-64-64s28.7-64 64-64 64 28.7 64 64-28.7 64-64 64zm232 96c-13.2 0-24-10.8-24-24s10.8-24 24-24 24 10.8 24 24-10.8 24-24 24zm-54.4-261.2l-19.2-25.6-48 36 19.2 25.6 48-36zM576 192c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zM152 320h48v-32h-48v32zm-88-80c-35.3 0-64 28.7-64 64s28.7 64 64 64 64-28.7 64-64-28.7-64-64-64z"/></svg>
          <?php _e('My dashboard', 'zeta'); ?>
        </a>
        
        <a href="<?php echo zet_user_items_url('active'); ?>" class="items">
          <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M512 128V96c0-6.6-5.4-12-12-12H204c-6.6 0-12 5.5-12 12.2V128c0 6.6 5.4 12 12 12h296c6.6 0 12-5.4 12-12zm0 160v-32c0-6.6-5.4-12-12-12H204c-6.6 0-12 5.4-12 12v32c0 6.6 5.4 12 12 12h296c6.6 0 12-5.4 12-12zm0 160v-32c0-6.6-5.4-12-12-12H204c-6.6 0-12 5.4-12 12v32c0 6.6 5.4 12 12 12h296c6.6 0 12-5.4 12-12zM162.1 64.8l-92 91.8c-4.7 4.7-12.3 4.7-17 0l-12.4-12.4-37.2-37.5c-4.7-4.7-4.7-12.3 0-17l12.4-12.4c4.7-4.7 12.3-4.7 17 0l28.8 29.2 71.1-71.1c4.7-4.7 12.3-4.7 17 0l12.4 12.4c4.6 4.8 4.6 12.4-.1 17zm0 159.9l-92 92c-4.7 4.7-12.3 4.7-17 0l-12.4-12.4-37.2-37.7c-4.7-4.7-4.7-12.3 0-17l12.4-12.4c4.7-4.7 12.3-4.7 17 0l28.8 29.2 71.1-71.1c4.7-4.7 12.3-4.7 17 0l12.4 12.4c4.6 4.7 4.6 12.3-.1 17zM64 384c-26.5 0-47.6 21.5-47.6 48s21.1 48 47.6 48 48-21.5 48-48-21.5-48-48-48z"/></svg>
          <?php _e('My active listings', 'zeta'); ?>
          <span class="counter"><?php echo Item::newInstance()->countItemTypesByUserID(osc_logged_user_id(), 'all'); ?></span>
        </a>

        <a href="<?php echo osc_user_alerts_url(); ?>" class="alert">
          <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M439.39 362.29c-19.32-20.76-55.47-51.99-55.47-154.29 0-77.7-54.48-139.9-127.94-155.16V32c0-17.67-14.32-32-31.98-32s-31.98 14.33-31.98 32v20.84C118.56 68.1 64.08 130.3 64.08 208c0 102.3-36.15 133.53-55.47 154.29-6 6.45-8.66 14.16-8.61 21.71.11 16.4 12.98 32 32.1 32h383.8c19.12 0 32-15.6 32.1-32 .05-7.55-2.61-15.27-8.61-21.71zM67.53 368c21.22-27.97 44.42-74.33 44.53-159.42 0-.2-.06-.38-.06-.58 0-61.86 50.14-112 112-112s112 50.14 112 112c0 .2-.06.38-.06.58.11 85.1 23.31 131.46 44.53 159.42H67.53zM224 512c35.32 0 63.97-28.65 63.97-64H160.03c0 35.35 28.65 64 63.97 64z"/></svg>
          <?php _e('My saved searches', 'zeta'); ?>
          <span class="counter"><?php echo count(Alerts::newInstance()->findByUser(osc_logged_user_id())); ?></span>
        </a>
        
        <a href="<?php echo osc_user_profile_url(); ?>" class="profile">
          <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M358.9 433.3l-6.8 61c-1.1 10.2 7.5 18.8 17.6 17.6l60.9-6.8 137.9-137.9-71.7-71.7-137.9 137.8zM633 268.9L595.1 231c-9.3-9.3-24.5-9.3-33.8 0l-41.8 41.8 71.8 71.7 41.8-41.8c9.2-9.3 9.2-24.4-.1-33.8zM223.9 288c79.6.1 144.2-64.5 144.1-144.1C367.9 65.6 302.4.1 224.1 0 144.5-.1 79.9 64.5 80 144.1c.1 78.3 65.6 143.8 143.9 143.9zm-4.4-239.9c56.5-2.6 103 43.9 100.4 100.4-2.3 49.2-42.1 89.1-91.4 91.4-56.5 2.6-103-43.9-100.4-100.4 2.3-49.3 42.2-89.1 91.4-91.4zM134.4 352c14.6 0 38.3 16 89.6 16 51.7 0 74.9-16 89.6-16 16.7 0 32.2 5 45.5 13.3l34.4-34.4c-22.4-16.7-49.8-26.9-79.9-26.9-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 304 0 364.2 0 438.4V464c0 26.5 21.5 48 48 48h258.3c-3.8-14.6-2.2-20.3.9-48H48v-25.6c0-47.6 38.8-86.4 86.4-86.4z"/></svg>
          <?php _e('My profile', 'zeta'); ?>
        </a>
        
        <?php if(function_exists('bpr_companies_url')) { ?>
          <a class="your-business-profile" href="<?php echo osc_route_url('bpr-profile'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="24" height="24"><path d="M528 32H48C21.5 32 0 53.5 0 80v16h576V80c0-26.5-21.5-48-48-48zM0 432c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V128H0v304zm352-232c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16zm0 64c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16zm0 64c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16zM176 192c35.3 0 64 28.7 64 64s-28.7 64-64 64-64-28.7-64-64 28.7-64 64-64zM67.1 396.2C75.5 370.5 99.6 352 128 352h8.2c12.3 5.1 25.7 8 39.8 8s27.6-2.9 39.8-8h8.2c28.4 0 52.5 18.5 60.9 44.2 3.2 9.9-5.2 19.8-15.6 19.8H82.7c-10.4 0-18.8-10-15.6-19.8z"/></svg>
            <?php _e('My business profile', 'zeta'); ?>
          </a>
        <?php } ?>

        <?php if(function_exists('im_messages')) { ?>
          <a href="<?php echo osc_route_url('im-threads'); ?>" class="messenger">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="24" height="24"><path d="M448 0H64C28.7 0 0 28.7 0 64v288c0 35.3 28.7 64 64 64h96v84c0 7.1 5.8 12 12 12 2.4 0 4.9-.7 7.1-2.4L304 416h144c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64zm16 352c0 8.8-7.2 16-16 16H288l-12.8 9.6L208 428v-60H64c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16h384c8.8 0 16 7.2 16 16v288zm-96-216H144c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h224c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16zm-96 96H144c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h128c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16z"/></svg>
            <?php _e('My messages', 'zeta'); ?>

            <?php if($mes_counter > 0) { ?>
              <span class="counter"><?php echo $mes_counter; ?></span>
            <?php } ?> 
          </a>
        <?php } ?>
        
        <?php if(function_exists('fi_make_favorite')) { ?>
          <a href="<?php echo osc_route_url('favorite-lists'); ?>" class="favorite">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" height="18" width="18"><path d="M458.4 64.3C400.6 15.7 311.3 23 256 79.3 200.7 23 111.4 15.6 53.6 64.3-21.6 127.6-10.6 230.8 43 285.5l175.4 178.7c10 10.2 23.4 15.9 37.6 15.9 14.3 0 27.6-5.6 37.6-15.8L469 285.6c53.5-54.7 64.7-157.9-10.6-221.3zm-23.6 187.5L259.4 430.5c-2.4 2.4-4.4 2.4-6.8 0L77.2 251.8c-36.5-37.2-43.9-107.6 7.3-150.7 38.9-32.7 98.9-27.8 136.5 10.5l35 35.7 35-35.7c37.8-38.5 97.8-43.2 136.5-10.6 51.1 43.1 43.5 113.9 7.3 150.8z"/></svg>
            <?php _e('My favorite listings', 'zeta'); ?>

            <?php if($fav_counter > 0) { ?>
              <span class="counter"><?php echo $fav_counter; ?></span>
            <?php } ?> 
          </a>
        <?php } ?>
        
        <?php if(function_exists('osp_user_sidebar')) { ?>
          <a href="<?php echo osc_route_url('osp-item'); ?>" class="pay">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="24" height="24"><path d="M168 296h-16c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h16c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16zm-32-48c0-8.8-7.2-16-16-16h-16c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h16c8.8 0 16-7.2 16-16v-16zm96 0c0-8.8-7.2-16-16-16h-16c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h16c8.8 0 16-7.2 16-16v-16zm128 48h-16c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h16c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16zm48-64h-16c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h16c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16zm103.4 147.5l-25.5-178.3c-3.4-23.6-23.6-41.2-47.5-41.2H208v-32h96c8.8 0 16-7.2 16-16V16c0-8.8-7.2-16-16-16H48c-8.8 0-16 7.2-16 16v96c0 8.8 7.2 16 16 16h96v32H73.6c-23.9 0-44.1 17.6-47.5 41.2L.6 379.5c-.4 3-.6 6-.6 9.1V464c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48v-75.5c0-3-.2-6-.6-9zM80 80V48h192v32H80zm-6.4 128h364.7l22.9 160H50.8l22.8-160zM464 464H48v-48h416v48zM328 248c0-8.8-7.2-16-16-16h-16c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h16c8.8 0 16-7.2 16-16v-16zm-64 48h-16c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h16c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16z"/></svg>
            <?php _e('My promotions', 'zeta'); ?>
          </a>
        <?php } ?>
      
        <div class="menu-hooks">
          <?php zet_user_menu_side(); ?>
        </div>
      <?php } ?>
    </div>
    
    <div class="section delim-top">
      <?php if(function_exists('bpr_companies_url')) { ?>
        <a class="company" href="<?php echo bpr_companies_url(); ?>">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="24" height="24"><path d="M464 128h-80V80c0-26.51-21.49-48-48-48H176c-26.51 0-48 21.49-48 48v48H48c-26.51 0-48 21.49-48 48v256c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V176c0-26.51-21.49-48-48-48zM176 80h160v48H176V80zM54 176h404c3.31 0 6 2.69 6 6v74H48v-74c0-3.31 2.69-6 6-6zm404 256H54c-3.31 0-6-2.69-6-6V304h144v24c0 13.25 10.75 24 24 24h80c13.25 0 24-10.75 24-24v-24h144v122c0 3.31-2.69 6-6 6z"/></svg>
          <?php _e('Companies', 'zeta'); ?>
        </a>
      <?php } ?>
      
      <?php if(function_exists('frm_home')) { ?>
        <a class="forum" href="<?php echo frm_home(); ?>">
          <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M512 160h-96V64c0-35.3-28.7-64-64-64H64C28.7 0 0 28.7 0 64v160c0 35.3 28.7 64 64 64h32v52c0 7.1 5.8 12 12 12 2.4 0 4.9-.7 7.1-2.4l76.9-43.5V384c0 35.3 28.7 64 64 64h96l108.9 61.6c2.2 1.6 4.7 2.4 7.1 2.4 6.2 0 12-4.9 12-12v-52h32c35.3 0 64-28.7 64-64V224c0-35.3-28.7-64-64-64zM96 240H64c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16h288c8.8 0 16 7.2 16 16v160c0 8.8-7.2 16-16 16H211.4l-11 6.2-56.4 31.9V240H96zm432 144c0 8.8-7.2 16-16 16h-80v38.1l-56.4-31.9-11-6.2H256c-8.8 0-16-7.2-16-16v-96h112c35.3 0 64-28.7 64-64v-16h96c8.8 0 16 7.2 16 16v160z"/></svg>
          <?php _e('Forums', 'zeta'); ?>
        </a>
      <?php } ?>
    
      <?php if(function_exists('blg_home_link')) { ?>
        <a class="blog" href="<?php echo blg_home_link(); ?>">
          <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M493.25 56.26l-37.51-37.51C443.25 6.25 426.87 0 410.49 0s-32.76 6.25-45.26 18.74l-67.87 67.88-39.59-39.59c-15.62-15.62-40.95-15.62-56.56 0L82.42 165.81c-6.25 6.25-6.25 16.38 0 22.62l11.31 11.31c6.25 6.25 16.38 6.25 22.62 0L229.49 86.62l33.94 33.94-7.42 7.42L93.95 290.03A327.038 327.038 0 0 0 .17 485.12l-.03.23C-1.45 499.72 9.88 512 23.95 512c.89 0 1.78-.05 2.69-.15a327.077 327.077 0 0 0 195.34-93.8L384.02 256l34.74-34.74 74.49-74.49c25-25 25-65.52 0-90.51zM188.03 384.11c-37.02 37.02-83.99 62.88-134.74 74.6 11.72-50.74 37.59-97.73 74.6-134.74l162.05-162.05 7.42-7.42 60.14 60.14-7.42 7.42-162.05 162.05zm271.28-271.29l-67.88 67.88-48.82-48.83-11.31-11.31 67.87-67.87c4.08-4.08 8.84-4.69 11.31-4.69 2.47 0 7.24.61 11.31 4.69L459.3 90.2c4.08 4.08 4.69 8.84 4.69 11.31s-.6 7.24-4.68 11.31z"/></svg>
          <?php _e('Blog', 'zeta'); ?>
        </a>
      <?php } ?>
     
      <?php if(function_exists('faq_home_link')) { ?>
        <a class="faq" href="<?php echo faq_home_link(); ?>">
          <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 504c136.967 0 248-111.033 248-248S392.967 8 256 8 8 119.033 8 256s111.033 248 248 248zm-103.398-76.72l53.411-53.411c31.806 13.506 68.128 13.522 99.974 0l53.411 53.411c-63.217 38.319-143.579 38.319-206.796 0zM336 256c0 44.112-35.888 80-80 80s-80-35.888-80-80 35.888-80 80-80 80 35.888 80 80zm91.28 103.398l-53.411-53.411c13.505-31.806 13.522-68.128 0-99.974l53.411-53.411c38.319 63.217 38.319 143.579 0 206.796zM359.397 84.72l-53.411 53.411c-31.806-13.505-68.128-13.522-99.973 0L152.602 84.72c63.217-38.319 143.579-38.319 206.795 0zM84.72 152.602l53.411 53.411c-13.506 31.806-13.522 68.128 0 99.974L84.72 359.398c-38.319-63.217-38.319-143.579 0-206.796z"/></svg>
          <?php _e('FAQ', 'zeta'); ?>
        </a>
      <?php } ?>
    </div>

    <div class="section delim-top">
      <?php if(osc_count_web_enabled_locales() > 1) { ?>
        <a href="#" class="open-box language" data-box="language">
          <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm-32 50.8v11.3c0 11.9-12.5 19.6-23.2 14.3l-24-12c14.9-6.4 30.7-10.9 47.2-13.6zm32 369.8V456c-110.3 0-200-89.7-200-200 0-29.1 6.4-56.7 17.6-81.7 9.9 14.7 25.2 37.4 34.6 51.1 5.2 7.6 11.2 14.6 18.1 20.7l.8.7c9.5 8.6 20.2 16 31.6 21.8 14 7 34.4 18.2 48.8 26.1 10.2 5.6 16.5 16.3 16.5 28v32c0 8.5 3.4 16.6 9.4 22.6 15 15.1 24.3 38.7 22.6 51.3zm42.7 22.7l17.4-46.9c2-5.5 3.3-11.2 4.8-16.9 1.1-4 3.2-7.7 6.2-10.7l11.3-11.3c8.8-8.7 13.7-20.6 13.7-33 0-8.1-3.2-15.9-8.9-21.6l-13.7-13.7c-6-6-14.1-9.4-22.6-9.4H232c-9.4-4.7-21.5-32-32-32s-20.9-2.5-30.3-7.2l-11.1-5.5c-4-2-6.6-6.2-6.6-10.7 0-5.1 3.3-9.7 8.2-11.3l31.2-10.4c5.4-1.8 11.3-.6 15.5 3.1l9.3 8.1c1.5 1.3 3.3 2 5.2 2h5.6c6 0 9.8-6.3 7.2-11.6l-15.6-31.2c-1.6-3.1-.9-6.9 1.6-9.3l9.9-9.6c1.5-1.5 3.5-2.3 5.6-2.3h9c2.1 0 4.2-.8 5.7-2.3l8-8c3.1-3.1 3.1-8.2 0-11.3l-4.7-4.7c-3.1-3.1-3.1-8.2 0-11.3L264 112l4.7-4.7c6.2-6.2 6.2-16.4 0-22.6l-28.3-28.3c2.5-.1 5-.4 7.6-.4 78.2 0 145.8 45.2 178.7 110.7l-13 6.5c-3.7 1.9-6.9 4.7-9.2 8.1l-19.6 29.4c-5.4 8.1-5.4 18.6 0 26.6l18 27c3.3 5 8.4 8.5 14.1 10l29.2 7.3c-10.8 84-73.9 151.9-155.5 169.7z"/></svg>
          <?php _e('Select language', 'zeta'); ?>
          <?php echo $indicator; ?>
        </a>
      <?php } ?>
      
      <?php if(zet_param('default_location') == 1) { ?>
        <a href="#" class="open-box location" data-box="location">
          <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path d="M347.94 129.86L203.6 195.83a31.938 31.938 0 0 0-15.77 15.77l-65.97 144.34c-7.61 16.65 9.54 33.81 26.2 26.2l144.34-65.97a31.938 31.938 0 0 0 15.77-15.77l65.97-144.34c7.61-16.66-9.54-33.81-26.2-26.2zm-77.36 148.72c-12.47 12.47-32.69 12.47-45.16 0-12.47-12.47-12.47-32.69 0-45.16 12.47-12.47 32.69-12.47 45.16 0 12.47 12.47 12.47 32.69 0 45.16zM248 8C111.03 8 0 119.03 0 256s111.03 248 248 248 248-111.03 248-248S384.97 8 248 8zm0 448c-110.28 0-200-89.72-200-200S137.72 56 248 56s200 89.72 200 200-89.72 200-200 200z"/></svg>
          <?php _e('Change location', 'zeta'); ?>
          <?php echo $indicator; ?>
        </a>
      <?php } ?>
      
      <a href="#" class="open-box pages" data-box="pages">
        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm0-338c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"/></svg>
        <?php _e('Help', 'zeta'); ?>
        <?php echo $indicator; ?>
      </a>
      
      <?php if(getBoolPreference('web_contact_form_disabled') != 1) { ?>
        <a href="<?php echo osc_contact_url(); ?>" class="cont">
          <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M464 64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zm0 48v40.805c-22.422 18.259-58.168 46.651-134.587 106.49-16.841 13.247-50.201 45.072-73.413 44.701-23.208.375-56.579-31.459-73.413-44.701C106.18 199.465 70.425 171.067 48 152.805V112h416zM48 400V214.398c22.914 18.251 55.409 43.862 104.938 82.646 21.857 17.205 60.134 55.186 103.062 54.955 42.717.231 80.509-37.199 103.053-54.947 49.528-38.783 82.032-64.401 104.947-82.653V400H48z"/></svg>
          <?php _e('Contact us', 'zeta'); ?>
        </a>
      <?php } ?>
    </div>

    <?php if(zet_param('enable_dark_mode') == 1) { ?>
      <div class="section delim-top dark-mode">
        <a href="#" class="switch-light-dark-mode mode-<?php echo zet_light_dark_mode(); ?>" data-label-light="<?php echo osc_esc_html(__('Switch to light mode', 'zeta')); ?>" data-label-dark="<?php echo osc_esc_html(__('Switch to dark mode', 'zeta')); ?>">
          <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 56c110.549 0 200 89.468 200 200 0 110.549-89.468 200-200 200-110.549 0-200-89.468-200-200 0-110.549 89.468-200 200-200m0-48C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 96c-83.947 0-152 68.053-152 152s68.053 152 152 152V104z"/></svg>
          <span>
            <?php 
              if(zet_light_dark_mode() == 'dark') { 
                _e('Switch to light mode', 'zeta');
              } else {
                _e('Switch to dark mode', 'zeta');
              }
            ?>
          </span>
        </a>
      </div>
    <?php } ?>
    
    <?php if(osc_is_web_user_logged_in()) { ?>
      <div class="section delim-top">
        <a class="logout" href="<?php echo osc_user_logout_url(); ?>">
          <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M272 112v51.6h-96c-26.5 0-48 21.5-48 48v88.6c0 26.5 21.5 48 48 48h96v51.6c0 42.6 51.7 64.2 81.9 33.9l144-143.9c18.7-18.7 18.7-49.1 0-67.9l-144-144C323.8 48 272 69.3 272 112zm192 144L320 400v-99.7H176v-88.6h144V112l144 144zM96 64h84c6.6 0 12 5.4 12 12v24c0 6.6-5.4 12-12 12H96c-26.5 0-48 21.5-48 48v192c0 26.5 21.5 48 48 48h84c6.6 0 12 5.4 12 12v24c0 6.6-5.4 12-12 12H96c-53 0-96-43-96-96V160c0-53 43-96 96-96z"/></svg>
          <?php _e('Log out', 'zeta'); ?>
        </a>
      </div>
    <?php } ?>
  </div>
  
  <div class="box pages" data-box="pages">
    <div class="nav">
      <a href="#" class="back"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20"><path d="M229.9 473.899l19.799-19.799c4.686-4.686 4.686-12.284 0-16.971L94.569 282H436c6.627 0 12-5.373 12-12v-28c0-6.627-5.373-12-12-12H94.569l155.13-155.13c4.686-4.686 4.686-12.284 0-16.971L229.9 38.101c-4.686-4.686-12.284-4.686-16.971 0L3.515 247.515c-4.686 4.686-4.686 12.284 0 16.971L212.929 473.9c4.686 4.686 12.284 4.686 16.971-.001z"></path></svg></a>
      <span><?php _e('Support pages', 'zeta'); ?></span>
    </div>
    
    <?php osc_reset_static_pages(); ?>
   
    <div class="section wrap-scrollable">
      <?php while(osc_has_static_pages()) { ?>
        <a href="<?php echo osc_static_page_url(); ?>"><?php echo osc_static_page_title();?></a>
      <?php } ?>
    </div>
  </div>
  
  <?php if(zet_param('default_location') == 1) { ?>
    <div class="box location" data-box="location">
      <div class="nav">
        <a href="#" class="back"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20"><path d="M229.9 473.899l19.799-19.799c4.686-4.686 4.686-12.284 0-16.971L94.569 282H436c6.627 0 12-5.373 12-12v-28c0-6.627-5.373-12-12-12H94.569l155.13-155.13c4.686-4.686 4.686-12.284 0-16.971L229.9 38.101c-4.686-4.686-12.284-4.686-16.971 0L3.515 247.515c-4.686 4.686-4.686 12.284 0 16.971L212.929 473.9c4.686 4.686 12.284 4.686 16.971-.001z"></path></svg></a>
        <span><?php _e('Change location', 'zeta'); ?></span>
      </div>
    
      <div class="section wrap-scrollable">
        <div class="head isDesktop isTablet"><?php _e('Default location', 'zeta'); ?></div>
        <div class="wrap ns">
          <div class="subhead isDesktop isTablet"><?php _e('Select your preferred location to search and sell faster.', 'zeta'); ?></div>

          <?php if(@$location_cookie['s_location'] <> '') { ?>
            <div class="row current">
              <strong><?php _e('Your location', 'zeta'); ?>:</strong> <?php echo $location_cookie['s_location']; ?>
            </div>
          <?php } ?>
            
          <div class="row picker">
            <div class="input-box picker location">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z"/></svg>
              <input name="location-pick" class="location-pick" type="text" placeholder="<?php echo osc_esc_html(__('Search location...', 'zeta')); ?>" autocomplete="off"/>
              <i class="clean fas fa-times-circle"></i>
              <div class="results nice-scroll"></div>
            </div>
          </div>
          
          <div class="row navigator">
            <a href="#" class="locate-me">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 168c-48.6 0-88 39.4-88 88s39.4 88 88 88 88-39.4 88-88-39.4-88-88-88zm0 128c-22.06 0-40-17.94-40-40s17.94-40 40-40 40 17.94 40 40-17.94 40-40 40zm240-64h-49.66C435.49 145.19 366.81 76.51 280 65.66V16c0-8.84-7.16-16-16-16h-16c-8.84 0-16 7.16-16 16v49.66C145.19 76.51 76.51 145.19 65.66 232H16c-8.84 0-16 7.16-16 16v16c0 8.84 7.16 16 16 16h49.66C76.51 366.81 145.19 435.49 232 446.34V496c0 8.84 7.16 16 16 16h16c8.84 0 16-7.16 16-16v-49.66C366.81 435.49 435.49 366.8 446.34 280H496c8.84 0 16-7.16 16-16v-16c0-8.84-7.16-16-16-16zM256 400c-79.4 0-144-64.6-144-144s64.6-144 144-144 144 64.6 144 144-64.6 144-144 144z"/></svg>
              <strong data-alt-text="<?php echo osc_esc_html(__('Click to refresh', 'zeta')); ?>"><?php _e('Use current location', 'zeta'); ?></strong>
              <span class="status">
                <span class="init"><?php _e('Click to find closest city to your location', 'zeta'); ?></span>
                <span class="not-supported" style="display:none;"><?php _e('Geolocation is not supported by your browser', 'zeta'); ?></span>
                <span class="failed" style="display:none;"><?php _e('Unable to retrieve your location, it may be blocked', 'zeta'); ?></span>
                <span class="failed-unfound" style="display:none;"><?php _e('Unable to retrieve your location, no close city found', 'zeta'); ?></span>
                <span class="loading" style="display:none;"><?php _e('Locating...', 'zeta'); ?></span>
                <span class="success" style="display:none;"></span>
                <span class="refresh" style="display:none;"><?php _e('Refresh page to take effect', 'zeta'); ?></span>
              </span>
            </a>
          </div>
          
          <?php $recent = array_reverse(zet_get_recent_locations());?>
          
          <?php if(is_array($recent) && count($recent) > 0) { ?>
            <div class="row recent">
              <div class="lead"><?php _e('Recent locations', 'zeta'); ?></div>

              <?php foreach($recent as $p) { ?>
                <?php $hash = rawurlencode(base64_encode(json_encode(array('fk_i_city_id' => @$p['fk_i_city_id'], 'fk_i_region_id' => @$p['fk_i_region_id'], 'fk_c_country_code' => @$p['fk_c_country_code'], 's_name' => @$p['s_name'], 's_name_native' => @$p['s_name_native'], 's_name_top' => @$p['s_name_top'], 's_name_top_native' => @$p['s_name_top_native'], 'd_coord_lat' => @$p['d_coord_lat'], 'd_coord_long' => @$p['d_coord_long'])))); ?>
                <a href="<?php echo zet_create_url(array('manualCookieLocation' => 1, 'hash' => $hash)); ?>" class="location-elem"><?php echo osc_location_native_name_selector($p, 's_name'); ?></a>
              <?php } ?>
            </div>
          <?php } ?>
          
          <?php $cities = ModelZET::newInstance()->getPopularCities(6, 0); ?>

          <?php if(is_array($cities) && count($cities) > 0) { ?>
            <div class="row popular">
              <div class="lead"><?php _e('Popular cities', 'zeta'); ?></div>

              <?php foreach($cities as $c) { ?>
                <?php $hash = rawurlencode(base64_encode(json_encode(array('fk_i_city_id' => $c['fk_i_city_id'], 'fk_i_region_id' => $c['fk_i_region_id'], 'fk_c_country_code' => $c['fk_c_country_code'], 's_name' => $c['s_name'], 's_name_native' => @$c['s_name_native'], 's_name_top' => @$c['s_name_top'], 's_name_top_native' => @$c['s_name_top_native'], 'd_coord_lat' => @$c['d_coord_lat'], 'd_coord_long' => @$c['d_coord_long'])))); ?>
                <a href="<?php echo zet_create_url(array('manualCookieLocation' => 1, 'hash' => $hash)); ?>" class="location-elem"><?php echo osc_location_native_name_selector($c, 's_name') . (osc_location_native_name_selector($c, 's_name_top') <> '' ? ', ' . osc_location_native_name_selector($c, 's_name_top') : '') . ($c['i_num_items'] > 0 ? ' <em>' . $c['i_num_items'] . ' ' . ($c['i_num_items'] == 1 ? __('item', 'zeta') : __('items', 'zeta')) . '</em>' : ''); ?></a>
              <?php } ?>
            </div>
          <?php } ?>
          
          <div class="row buttons">
            <a class="btn btn-secondary" href="<?php echo zet_create_url(array('cleanCookieLocation' => 1)); ?>"><?php _e('Clean default location', 'zeta'); ?></a>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>

  <?php if(osc_count_web_enabled_locales() > 1) { ?>
    <div class="box language" data-box="language">
      <div class="nav">
        <a href="#" class="back"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20"><path d="M229.9 473.899l19.799-19.799c4.686-4.686 4.686-12.284 0-16.971L94.569 282H436c6.627 0 12-5.373 12-12v-28c0-6.627-5.373-12-12-12H94.569l155.13-155.13c4.686-4.686 4.686-12.284 0-16.971L229.9 38.101c-4.686-4.686-12.284-4.686-16.971 0L3.515 247.515c-4.686 4.686-4.686 12.284 0 16.971L212.929 473.9c4.686 4.686 12.284 4.686 16.971-.001z"></path></svg></a>
        <span><?php _e('Select language', 'zeta'); ?></span>
      </div>
      
      <?php osc_goto_first_locale(); ?>

      <div class="section wrap-scrollable">
        <?php while(osc_has_web_enabled_locales()) { ?>
          <a class="lang <?php if(osc_locale_code() == osc_current_user_locale()) { ?>active<?php } ?>" href="<?php echo osc_change_language_url(osc_locale_code()); ?>">
            <img src="<?php echo zet_country_flag_image(strtolower(substr(osc_locale_code(), 3))); ?>" alt="<?php echo osc_esc_html(__('Country flag', 'zeta')); ?>" />
            <span><?php echo osc_locale_name(); ?>&#x200E;</span>
          </a>
        <?php } ?>
      </div>
    </div>
  <?php } ?>
  
  <div class="box filter" data-box="filter">
    <div class="nav">
      <a href="#" class="back close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20"><path d="M229.9 473.899l19.799-19.799c4.686-4.686 4.686-12.284 0-16.971L94.569 282H436c6.627 0 12-5.373 12-12v-28c0-6.627-5.373-12-12-12H94.569l155.13-155.13c4.686-4.686 4.686-12.284 0-16.971L229.9 38.101c-4.686-4.686-12.284-4.686-16.971 0L3.515 247.515c-4.686 4.686-4.686 12.284 0 16.971L212.929 473.9c4.686 4.686 12.284 4.686 16.971-.001z"></path></svg></a>
      <span><?php _e('Filter results', 'zeta'); ?></span>
    </div>
  
    <div class="section filter-menu"></div>
  </div>
</div>

<?php if(zet_is_demo()) { ?>
  <a id="showcase-button" href="#" class="isMobile">
    <i class="fas fa-cogs"></i>
    <span><?php _e('CONFIG', 'zeta'); ?></span>
  </a>
  
  <div id="showcase-box">
    <div class="container nice-scroll no-visible-scroll">
      <a target="_blank" href="<?php echo osc_admin_render_theme_url(zet_oc_content_folder() . '/themes/zeta/admin/configure.php'); ?>"><?php _e('Backoffice', 'zeta'); ?></a>
      <a href="#" class="show-banners" data-alt-text="<?php echo osc_esc_html(__('Hide Banners', 'zeta')); ?>"><?php _e('Show All Banners', 'zeta'); ?></a>

      <div class="switch-color primary">
        <span class="lab"><?php _e('Primary color', 'zeta'); ?></span>
        
        <div class="colors">
          <?php $colors = array('ff2636','008f79','3b49df','ad3bdf','54b50d','d99f0c','272727'); ?>
          <?php foreach($colors as $c) { ?>
            <a href="<?php echo osc_base_url(true); ?>?setCustomColor=1&customColorPrimary=<?php echo $c; ?>" style="background-color:#<?php echo $c; ?>;" class="<?php if(str_replace('#', '', zet_get_theme_color_primary()) == $c || zet_get_theme_color_primary() == '' && $c == 'ff2636') { ?> active<?php } ?>"></a>
          <?php } ?>
        </div>
      </div>
      
      <div class="switch-color secondary">
        <span class="lab"><?php _e('Secondary color', 'zeta'); ?></span>
        
        <div class="colors">
          <?php $colors = array('ff2636','008f79','3b49df','ad3bdf','54b50d','d99f0c','272727'); ?>
          <?php foreach($colors as $c) { ?>
            <a href="<?php echo osc_base_url(true); ?>?setCustomColor=1&customColorSecondary=<?php echo $c; ?>" style="background-color:#<?php echo $c; ?>;" class="<?php if(str_replace('#', '', zet_get_theme_color_secondary()) == $c || zet_get_theme_color_secondary() == '' && $c == '008f79') { ?> active<?php } ?>"></a>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if(osc_is_admin_user_logged_in() && ((defined('OSC_DEBUG') && OSC_DEBUG == true) || (defined('OSC_DEBUG_DB') && OSC_DEBUG_DB == true))) { ?>
  <div id="debug-mode" class="noselect"><?php _e('You have enabled DEBUG MODE, autocomplete may not work! Disable debug mode in config.php.', 'zeta'); ?></div>
<?php } ?>

<style>
a.fi_img-link.fi-no-image > img {content:url("<?php echo osc_base_url() . '/' . zet_oc_content_folder(); ?>/themes/zeta/images/no-image.png");}
</style>