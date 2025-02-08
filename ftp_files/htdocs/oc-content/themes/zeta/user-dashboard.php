<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo zet_language_dir(); ?>" mode="<?php echo zet_light_dark_mode(); ?>" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php'); ?>
  <meta name="robots" content="index, follow" />
  <meta name="googlebot" content="index, follow" />
</head>

<body id="user-dashboard" class="body-ua <?php osc_run_hook('body_class'); ?>">
  <?php osc_current_web_theme_path('header.php'); ?>

  <div class="container primary">
    <div id="user-menu"><?php zet_user_menu(); ?></div>

    <?php 
      $user_id = osc_logged_user_id();
      $user = User::newInstance()->findByPrimaryKey($user_id); 
    ?>
    
    <div id="user-main">
      <?php echo zet_banner('user_account_top'); ?>

      <div class="headers">
        <a href="<?php echo osc_user_profile_url(); ?>" class="img-container" title="<?php echo osc_esc_html(__('Upload profile picture', 'zeta')); ?>">
          <img src="<?php echo zet_profile_picture($user_id, 'medium'); ?>" alt="<?php echo osc_esc_html(osc_logged_user_name()); ?>" width="36" height="36"/>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="28" height="28"><path d="M256 408c-66.2 0-120-53.8-120-120s53.8-120 120-120 120 53.8 120 120-53.8 120-120 120zm0-192c-39.7 0-72 32.3-72 72s32.3 72 72 72 72-32.3 72-72-32.3-72-72-72zm-24 72c0-13.2 10.8-24 24-24 8.8 0 16-7.2 16-16s-7.2-16-16-16c-30.9 0-56 25.1-56 56 0 8.8 7.2 16 16 16s16-7.2 16-16zm110.7-145H464v288H48V143h121.3l24-64h125.5l23.9 64zM324.3 31h-131c-20 0-37.9 12.4-44.9 31.1L136 95H48c-26.5 0-48 21.5-48 48v288c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V143c0-26.5-21.5-48-48-48h-88l-14.3-38c-5.8-15.7-20.7-26-37.4-26z"/></svg>
        </a>

        <h1>
          <?php echo sprintf(__('Hi %s', 'zeta'), osc_logged_user_name()); ?>
        </h1>
        <h2><?php _e('Manage your listings, saved searches or profile', 'zeta'); ?></h2>
      </div>
      
      <?php osc_run_hook('user_dashboard_top'); ?>
      
      <div class="card-box">
        <?php if(function_exists('bpr_call_after_install') && (bpr_param('only_company_users') == 0 || (bpr_param('only_company_users') == 1 && $user['b_company'] == 1)) && bpr_company_url($user_id) !== false) { ?>
          <a class="card public" href="<?php echo bpr_company_url($user_id); ?>">
            <div class="icon">
              <i class="fas fa-briefcase"></i>
            </div>

            <div class="header"><?php _e('Business profile', 'zeta'); ?></div>
            <div class="description"><?php _e('Your business profile visible to customers', 'zeta'); ?></div>
          </a>
        <?php } else { ?>
          <a class="card public" href="<?php echo osc_user_public_profile_url($user_id); ?>">
            <div class="icon">
              <i class="far fa-address-card"></i>
            </div>

            <div class="header"><?php _e('Public profile', 'zeta'); ?></div>
            <div class="description"><?php _e('Your business profile visible to customers', 'zeta'); ?></div>
          </a>
        <?php } ?>
        
        <a class="card active" href="<?php echo zet_user_items_url('active'); ?>">
          <div class="icon">
            <i class="fas fa-check-double"></i>
            <span class="count"><?php echo Item::newInstance()->countItemTypesByUserID($user_id, 'active'); ?></span>
          </div>

          <div class="header"><?php _e('Active listings', 'zeta'); ?></div>
          <div class="description"><?php _e('Your listings visible in search', 'zeta'); ?></div>
        </a>


        <a class="card not-validated" href="<?php echo zet_user_items_url('pending_validate'); ?>">
          <div class="icon">
            <i class="fas fa-history"></i>
            <span class="count"><?php echo Item::newInstance()->countItemTypesByUserID($user_id, 'pending_validate'); ?></span>
          </div>

          <div class="header"><?php _e('Pending validation', 'zeta'); ?></div>
          <div class="description"><?php _e('Listings waiting for admin validation', 'zeta'); ?></div>
        </a>


        <a class="card expired" href="<?php echo zet_user_items_url('expired'); ?>">
          <div class="icon">
            <i class="fas fa-hourglass-end"></i>
            <span class="count"><?php echo Item::newInstance()->countItemTypesByUserID($user_id, 'expired'); ?></span>
          </div>

          <div class="header"><?php _e('Expired listings', 'zeta'); ?></div>
          <div class="description"><?php _e('Expired listings are not visible in search', 'zeta'); ?></div>
        </a>


        <a class="card alerts" href="<?php echo osc_user_alerts_url(); ?>">
          <div class="icon">
            <svg width="32" height="32" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M439.39 362.29c-19.32-20.76-55.47-51.99-55.47-154.29 0-77.7-54.48-139.9-127.94-155.16V32c0-17.67-14.32-32-31.98-32s-31.98 14.33-31.98 32v20.84C118.56 68.1 64.08 130.3 64.08 208c0 102.3-36.15 133.53-55.47 154.29-6 6.45-8.66 14.16-8.61 21.71.11 16.4 12.98 32 32.1 32h383.8c19.12 0 32-15.6 32.1-32 .05-7.55-2.61-15.27-8.61-21.71zM67.53 368c21.22-27.97 44.42-74.33 44.53-159.42 0-.2-.06-.38-.06-.58 0-61.86 50.14-112 112-112s112 50.14 112 112c0 .2-.06.38-.06.58.11 85.1 23.31 131.46 44.53 159.42H67.53zM224 512c35.32 0 63.97-28.65 63.97-64H160.03c0 35.35 28.65 64 63.97 64z"/></svg>
            <span class="count"><?php echo zet_count_alerts(); ?></span>
          </div>

          <div class="header"><?php _e('Saved searches', 'zeta'); ?></div>
          <div class="description"><?php _e('Receive notifications of new listings matching criteria', 'zeta'); ?></div>
        </a>


        <a class="card profile" href="<?php echo osc_user_profile_url(); ?>">
          <?php 
            $c = 0;
            if($user['s_phone_land'] == '' && $user['s_phone_mobile'] == '') { $c++; }
            if($user['s_website'] == '') { $c++; }
            if($user['s_country'] == '' && $user['s_region'] == '' && $user['s_city'] == '') { $c++; }
            if($user['s_address'] == '' && $user['s_zip'] == '') { $c++; }
          ?>

          <div class="icon">
            <svg width="32" height="32" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M358.9 433.3l-6.8 61c-1.1 10.2 7.5 18.8 17.6 17.6l60.9-6.8 137.9-137.9-71.7-71.7-137.9 137.8zM633 268.9L595.1 231c-9.3-9.3-24.5-9.3-33.8 0l-41.8 41.8 71.8 71.7 41.8-41.8c9.2-9.3 9.2-24.4-.1-33.8zM223.9 288c79.6.1 144.2-64.5 144.1-144.1C367.9 65.6 302.4.1 224.1 0 144.5-.1 79.9 64.5 80 144.1c.1 78.3 65.6 143.8 143.9 143.9zm-4.4-239.9c56.5-2.6 103 43.9 100.4 100.4-2.3 49.2-42.1 89.1-91.4 91.4-56.5 2.6-103-43.9-100.4-100.4 2.3-49.3 42.2-89.1 91.4-91.4zM134.4 352c14.6 0 38.3 16 89.6 16 51.7 0 74.9-16 89.6-16 16.7 0 32.2 5 45.5 13.3l34.4-34.4c-22.4-16.7-49.8-26.9-79.9-26.9-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 304 0 364.2 0 438.4V464c0 26.5 21.5 48 48 48h258.3c-3.8-14.6-2.2-20.3.9-48H48v-25.6c0-47.6 38.8-86.4 86.4-86.4z"/></svg>
            <span class="count">
              <?php if($c == 0) { ?><i class="fas fa-check"></i><?php } else { ?><i class="fas fa-exclamation"></i><?php } ?>
            </span>
          </div>

          <div class="header"><?php _e('My profile', 'zeta'); ?></div>
          <div class="description">
            <?php if($c == 0) { ?>
              <?php _e('Edit your profile details', 'zeta'); ?>
            <?php } else { ?>
              <?php echo osc_esc_html(sprintf(__('Your profile is not complete! (%s issues)', 'zeta'), $c)); ?>
            <?php } ?>
          </div>
        </a>

        <?php if(function_exists('bpr_call_after_install') && (bpr_param('only_company_users') == 0 || (bpr_param('only_company_users') == 1 && $user['b_company'] == 1))) { ?>
          <a class="card business-profile" href="<?php echo osc_route_url('bpr-profile'); ?>">
            <div class="icon">
              <i class="fas fa-user-edit"></i>
            </div>

            <div class="header"><?php _e('Business profile', 'zeta'); ?></div>
            <div class="description"><?php _e('Profile details, payment methods, opening hours, gallery', 'zeta'); ?></div>
          </a>
        <?php } ?>

        <?php if(function_exists('im_messages')) { ?>
          <a class="card messages" href="<?php echo osc_route_url('im-threads'); ?>">
            <div class="icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="24" height="24"><path d="M448 0H64C28.7 0 0 28.7 0 64v288c0 35.3 28.7 64 64 64h96v84c0 7.1 5.8 12 12 12 2.4 0 4.9-.7 7.1-2.4L304 416h144c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64zm16 352c0 8.8-7.2 16-16 16H288l-12.8 9.6L208 428v-60H64c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16h384c8.8 0 16 7.2 16 16v288zm-96-216H144c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h224c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16zm-96 96H144c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h128c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16z"/></svg>
              <span class="count"><?php echo zet_count_messages($user_id); ?></span>
            </div>

            <div class="header"><?php _e('Messages', 'zeta'); ?></div>
            <div class="description"><?php _e('Instant messages you have recieved & sent', 'zeta'); ?></div>
          </a>
        <?php } ?>

        <?php if(function_exists('fi_make_favorite')) { ?>
          <a class="card favorite" href="<?php echo osc_route_url('favorite-lists'); ?>">
            <div class="icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" height="24" width="24"><path d="M458.4 64.3C400.6 15.7 311.3 23 256 79.3 200.7 23 111.4 15.6 53.6 64.3-21.6 127.6-10.6 230.8 43 285.5l175.4 178.7c10 10.2 23.4 15.9 37.6 15.9 14.3 0 27.6-5.6 37.6-15.8L469 285.6c53.5-54.7 64.7-157.9-10.6-221.3zm-23.6 187.5L259.4 430.5c-2.4 2.4-4.4 2.4-6.8 0L77.2 251.8c-36.5-37.2-43.9-107.6 7.3-150.7 38.9-32.7 98.9-27.8 136.5 10.5l35 35.7 35-35.7c37.8-38.5 97.8-43.2 136.5-10.6 51.1 43.1 43.5 113.9 7.3 150.8z"/></svg>
              <span class="count"><?php echo zet_count_favorite($user_id); ?></span>
            </div>

            <div class="header"><?php _e('Favorite listings', 'zeta'); ?></div>
            <div class="description"><?php _e('Listings you\'ve marked as your favorite', 'zeta'); ?></div>
          </a>
        <?php } ?>

        <?php if(function_exists('osp_param')) { ?>
          <a class="card promote" href="<?php echo osc_route_url('osp-item'); ?>">
            <div class="icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="24" height="24"><path d="M288 0C305.7 0 320 14.33 320 32V96C320 113.7 305.7 128 288 128H208V160H424.1C456.6 160 483.5 183.1 488.2 214.4L510.9 364.1C511.6 368.8 512 373.6 512 378.4V448C512 483.3 483.3 512 448 512H64C28.65 512 0 483.3 0 448V378.4C0 373.6 .3622 368.8 1.083 364.1L23.76 214.4C28.5 183.1 55.39 160 87.03 160H143.1V128H63.1C46.33 128 31.1 113.7 31.1 96V32C31.1 14.33 46.33 0 63.1 0L288 0zM96 48C87.16 48 80 55.16 80 64C80 72.84 87.16 80 96 80H256C264.8 80 272 72.84 272 64C272 55.16 264.8 48 256 48H96zM80 448H432C440.8 448 448 440.8 448 432C448 423.2 440.8 416 432 416H80C71.16 416 64 423.2 64 432C64 440.8 71.16 448 80 448zM112 216C98.75 216 88 226.7 88 240C88 253.3 98.75 264 112 264C125.3 264 136 253.3 136 240C136 226.7 125.3 216 112 216zM208 264C221.3 264 232 253.3 232 240C232 226.7 221.3 216 208 216C194.7 216 184 226.7 184 240C184 253.3 194.7 264 208 264zM160 296C146.7 296 136 306.7 136 320C136 333.3 146.7 344 160 344C173.3 344 184 333.3 184 320C184 306.7 173.3 296 160 296zM304 264C317.3 264 328 253.3 328 240C328 226.7 317.3 216 304 216C290.7 216 280 226.7 280 240C280 253.3 290.7 264 304 264zM256 296C242.7 296 232 306.7 232 320C232 333.3 242.7 344 256 344C269.3 344 280 333.3 280 320C280 306.7 269.3 296 256 296zM400 264C413.3 264 424 253.3 424 240C424 226.7 413.3 216 400 216C386.7 216 376 226.7 376 240C376 253.3 386.7 264 400 264zM352 296C338.7 296 328 306.7 328 320C328 333.3 338.7 344 352 344C365.3 344 376 333.3 376 320C376 306.7 365.3 296 352 296z"/></svg>
            </div>

            <div class="header"><?php _e('Promotions', 'zeta'); ?></div>
            <div class="description"><?php _e('Highlight listings, buy credits or membership', 'zeta'); ?></div>
          </a>
        <?php } ?>
        
        <a class="card contact" href="<?php echo osc_contact_url(); ?>">
          <div class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="24" height="24"><path d="M494.586 164.516c-4.697-3.883-111.723-89.95-135.251-108.657C337.231 38.191 299.437 0 256 0c-43.205 0-80.636 37.717-103.335 55.859-24.463 19.45-131.07 105.195-135.15 108.549A48.004 48.004 0 0 0 0 201.485V464c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V201.509a48 48 0 0 0-17.414-36.993zM464 458a6 6 0 0 1-6 6H54a6 6 0 0 1-6-6V204.347c0-1.813.816-3.526 2.226-4.665 15.87-12.814 108.793-87.554 132.364-106.293C200.755 78.88 232.398 48 256 48c23.693 0 55.857 31.369 73.41 45.389 23.573 18.741 116.503 93.493 132.366 106.316a5.99 5.99 0 0 1 2.224 4.663V458zm-31.991-187.704c4.249 5.159 3.465 12.795-1.745 16.981-28.975 23.283-59.274 47.597-70.929 56.863C336.636 362.283 299.205 400 256 400c-43.452 0-81.287-38.237-103.335-55.86-11.279-8.967-41.744-33.413-70.927-56.865-5.21-4.187-5.993-11.822-1.745-16.981l15.258-18.528c4.178-5.073 11.657-5.843 16.779-1.726 28.618 23.001 58.566 47.035 70.56 56.571C200.143 320.631 232.307 352 256 352c23.602 0 55.246-30.88 73.41-45.389 11.994-9.535 41.944-33.57 70.563-56.568 5.122-4.116 12.601-3.346 16.778 1.727l15.258 18.526z"/></svg>
          </div>

          <div class="header"><?php _e('Contact us', 'zeta'); ?></div>
          <div class="description"><?php _e('Feel free to send us a message', 'zeta'); ?></div>
        </a>

        <a class="card logout" href="<?php echo osc_user_logout_url(); ?>">
          <div class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="24" height="24"><path d="M96 64h84c6.6 0 12 5.4 12 12v24c0 6.6-5.4 12-12 12H96c-26.5 0-48 21.5-48 48v192c0 26.5 21.5 48 48 48h84c6.6 0 12 5.4 12 12v24c0 6.6-5.4 12-12 12H96c-53 0-96-43-96-96V160c0-53 43-96 96-96zm231.1 19.5l-19.6 19.6c-4.8 4.8-4.7 12.5.2 17.1L420.8 230H172c-6.6 0-12 5.4-12 12v28c0 6.6 5.4 12 12 12h248.8L307.7 391.7c-4.8 4.7-4.9 12.4-.2 17.1l19.6 19.6c4.7 4.7 12.3 4.7 17 0l164.4-164c4.7-4.7 4.7-12.3 0-17l-164.4-164c-4.7-4.6-12.3-4.6-17 .1z"/></svg>
          </div>

          <div class="header"><?php _e('Logout', 'zeta'); ?></div>
          <div class="description"><?php _e('Sign-out from your account', 'zeta'); ?></div>
        </a>
        
        <?php osc_run_hook('user_dashboard_links'); ?>
      </div>
      
      <?php osc_run_hook('user_dashboard_bottom'); ?>
    </div>
  </div>

  <?php osc_current_web_theme_path('footer.php'); ?>
</body>
</html>