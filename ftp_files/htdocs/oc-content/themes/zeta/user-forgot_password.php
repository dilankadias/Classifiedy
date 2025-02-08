<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo zet_language_dir(); ?>" mode="<?php echo zet_light_dark_mode(); ?>" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php'); ?>
  <meta name="robots" content="index, follow" />
  <meta name="googlebot" content="index, follow" />
  <script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('jquery.validate.min.js'); ?>"></script>
</head>

<body id="user-forgot" class="pre-account forgot <?php osc_run_hook('body_class'); ?>">
  <?php UserForm::js_validation(); ?>
  <?php osc_current_web_theme_path('header.php'); ?>

  <section class="container">
    <div class="box">
      <h1><?php _e('Change password', 'zeta'); ?></h1>
      <h2><?php _e('Please enter a new secure password for your account', 'zeta'); ?></h2>

      <form action="<?php echo osc_base_url(true); ?>" method="post">
        <input type="hidden" name="page" value="login" />
        <input type="hidden" name="action" value="forgot_post" />
        <input type="hidden" name="userId" value="<?php echo osc_esc_html(Params::getParam('userId')); ?>" />
        <input type="hidden" name="code" value="<?php echo osc_esc_html(Params::getParam('code')); ?>" />
        
        <div class="row">
          <label for="new_email"><?php _e('New password', 'zeta'); ?></label>
          <span class="input-box"><input type="password" name="new_password" value="" />
        </div>
        
        <div class="row">
          <label for="new_email"><?php _e('Repeat password', 'zeta'); ?></label>
          <span class="input-box"><input type="password" name="new_password2" value="" />
        </div>
        
        <?php osc_run_hook('user_forgot_password_form'); ?>

        <button type="submit" class="btn"><?php _e('Submit', 'zeta'); ?></button>
      </form>
      
      <a class="alt-action" href="<?php echo osc_user_login_url(); ?>"><?php _e('Login to your account', 'zeta'); ?> &#8594;</a>
    </div>
  </div>

  <?php osc_current_web_theme_path('footer.php'); ?>
</body>
</html>