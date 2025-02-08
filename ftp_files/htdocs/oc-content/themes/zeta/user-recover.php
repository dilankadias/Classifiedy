<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo zet_language_dir(); ?>" mode="<?php echo zet_light_dark_mode(); ?>" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php'); ?>
  <meta name="robots" content="index, follow" />
  <meta name="googlebot" content="index, follow" />
  <script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('jquery.validate.min.js'); ?>"></script>
</head>

<body id="user-recover" class="pre-account recover <?php osc_run_hook('body_class'); ?>">
  <?php UserForm::js_validation(); ?>
  <?php osc_current_web_theme_path('header.php'); ?>

  <section class="container">
    <div class="box">
      <h1><?php _e('Reset password', 'zeta'); ?></h1>
      <h2><?php _e('New randomly generated password will be sent to your email', 'zeta'); ?></h2>

      <form action="<?php echo osc_base_url(true); ?>" method="post" >
        <input type="hidden" name="page" value="login" />
        <input type="hidden" name="action" value="recover_post" />

        <div class="row">
          <label for="email"><?php _e('E-mail', 'zeta'); ?></label> 
          <span class="input-box"><?php UserForm::email_text(); ?></span>
        </div>
        
        <?php osc_run_hook('user_recover_form'); ?>
        <?php zet_show_recaptcha('recover_password'); ?>

        <button type="submit" class="btn"><?php _e('Send a new password', 'zeta'); ?></button>
      </form>
      
      <a class="alt-action" href="<?php echo osc_user_login_url(); ?>"><?php _e('Login to your account', 'zeta'); ?> &#8594;</a>
    </div>
  </div>

  <?php osc_current_web_theme_path('footer.php'); ?>
  
  <script type="text/javascript">
    $(document).ready(function(){
      $('input[name="s_email"]').attr('placeholder', '<?php echo osc_esc_js(__('your.email@dot.com', 'zeta')); ?>').attr('required', true).prop('type', 'email');
    });
  </script>
</body>
</html>