<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo zet_language_dir(); ?>" mode="<?php echo zet_light_dark_mode(); ?>" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php'); ?>
  <meta name="robots" content="index, follow" />
  <meta name="googlebot" content="index, follow" />
</head>

<body id="page" class="<?php osc_run_hook('body_class'); ?>">
  <?php osc_current_web_theme_path('header.php'); ?>
  <?php osc_reset_static_pages(); ?>

  <?php if(zet_banner('static_page_top') !== false) { ?>
    <div class="container banner-box<?php if(zet_is_demo()) { ?> is-demo<?php } ?>"><div class="inside"><?php echo zet_banner('static_page_top'); ?></div></div>
  <?php } ?>
    
  <div class="page-text container">
    <h1><?php echo osc_static_page_title(); ?></h1>
    <section class="text"><?php echo osc_static_page_text(); ?></section>
    <section class="bottom"><?php _e('Do you have more questions?', 'zeta'); ?> <a href="<?php echo osc_contact_url(); ?>"><?php _e('Contact us', 'zeta'); ?> &#8594;</a></div>
  </div>

  <?php if(zet_banner('static_page_bottom') !== false) { ?>
    <div class="container banner-box<?php if(zet_is_demo()) { ?> is-demo<?php } ?>"><div class="inside"><?php echo zet_banner('static_page_bottom'); ?></div></div>
  <?php } ?>
    
  <?php osc_current_web_theme_path('footer.php'); ?>
</body>
</html>