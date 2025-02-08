<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo zet_language_dir(); ?>" mode="<?php echo zet_light_dark_mode(); ?>" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php'); ?>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />
</head>

<body id="p404" class="<?php osc_run_hook('body_class'); ?>">
  <?php osc_current_web_theme_path('header.php'); ?>

  <div class="container">
    <div class="error404">
      <div class="error errbox">
        <div class="number">4</div>
        <div class="illustration">
          <div class="circle"></div>
          <div class="clip">
            <div class="paper">
              <div class="face">
                <div class="eyes">
                  <div class="eye eye-left"></div>
                  <div class="eye eye-right"></div>
                </div>
                <div class="rosyCheeks rosyCheeks-left"></div>
                <div class="rosyCheeks rosyCheeks-right"></div>
                <div class="mouth"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="number">4</div>
      </div>
      
      <h1><?php _e('OOPS! Page Not Found!', 'zeta'); ?></h1>
      <h2><?php _e('Either something get wrong or the page doesn\'t exist anymore.', 'zeta'); ?></h2>

      <a href="<?php echo osc_base_url(); ?>" class="btn mbBg2"><?php _e('Back Home', 'zeta'); ?></a>
    </div>
  </div>

  <?php osc_current_web_theme_path('footer.php'); ?>
</body>
</html>