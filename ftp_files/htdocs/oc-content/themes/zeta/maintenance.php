<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo zet_language_dir(); ?>" mode="<?php echo zet_light_dark_mode(); ?>" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
  <head>
    <?php osc_current_web_theme_path('head.php'); ?>
    <meta name="robots" content="noindex, nofollow" />
    <meta name="googlebot" content="noindex, nofollow" />
  </head>

  <body id="maintenanc" class="<?php osc_run_hook('body_class'); ?>">
    <?php osc_goto_first_locale(); ?>

    <section class="content loc-error sec-default">
      <div class="inside">
        <div class="maintenance">
          <div class="logo">
            <a href="<?php echo osc_base_url(); ?>"><?php echo zet_logo(); ?></a>
          </div>
          
          <h1><?php _e('Maintenance', 'zeta'); ?></h1>
          <h2><?php _e('OOOPS! We are sorry, page is undergoing maintenance.', 'zeta'); ?></h2>
          <h3><?php _e('Please come back later. Thank you!', 'zeta'); ?></h3>
        </div>
      </div>
    </section>
  </body>
</html>