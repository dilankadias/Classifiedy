<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo zet_language_dir(); ?>" mode="<?php echo zet_light_dark_mode(); ?>" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php'); ?>
  <meta name="robots" content="index, follow" />
  <meta name="googlebot" content="index, follow" />
  <script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('jquery.validate.min.js'); ?>"></script>
</head>

<body id="contact" class="pre-account contact has-footer <?php osc_run_hook('body_class'); ?>">
  <?php UserForm::js_validation(); ?>
  <?php osc_current_web_theme_path('header.php'); ?>

  <section class="container">
    <div class="box">
      <h1><?php _e('Contact us', 'zeta'); ?></h1>

      <form action="<?php echo osc_base_url(true); ?>" method="post" name="contact_form" <?php if(osc_contact_attachment()) { ?>enctype="multipart/form-data"<?php } ?>>
        <input type="hidden" name="page" value="contact" />
        <input type="hidden" name="action" value="contact_post" />

        <ul id="error_list"></ul>

        <div class="row r1">
          <label for="yourName"><?php _e('Your name', 'zeta'); ?> <span class="req">*</span></label> 
          <div class="input-box">
            <input type="text" name="yourName" <?php if(osc_is_web_user_logged_in()) { ?>readonly<?php } ?> required value="<?php echo osc_esc_html(osc_logged_user_name()); ?>" />
          </div>
        </div>

        <div class="row r2">
          <label for="yourName"><?php _e('Email', 'zeta'); ?> <span class="req">*</span></label> 
          <div class="input-box">
            <input type="email" name="yourEmail" <?php if(osc_is_web_user_logged_in()) { ?>readonly<?php } ?> required value="<?php echo osc_logged_user_email();?>" />
          </div>
        </div>


        <div class="row r3">
          <label for="subject"><?php _e('Subject', 'zeta'); ?> <span class="req">*</span></label>
          <div class="input-box"><?php ContactForm::the_subject(); ?></div>
        </div>

        <div class="row r4">
          <label for="message"><?php _e('Message', 'zeta'); ?> <span class="req">*</span></label>
          <div class="input-box last"><?php ContactForm::your_message(); ?></div>
        </div>

        <?php if(osc_contact_attachment()) { ?>
          <div class="row r5">
            <label for="attachment"><?php _e('Attachment', 'zeta'); ?></label>
            <div class="input-box last2"><?php ContactForm::your_attachment(); ?></div>
          </div>
        <?php } ?>

        <?php osc_run_hook('contact_form'); ?>
        
        <?php zet_show_recaptcha(); ?>

        <button type="submit" class="btn complete-contact mbBg2"><?php _e('Send message', 'zeta'); ?></button>

        <?php osc_run_hook('admin_contact_form'); ?>
      </form>
    </div>
  </div>

  <?php ContactForm::js_validation(); ?>
  <?php osc_current_web_theme_path('footer.php'); ?>
  
  <script type="text/javascript">
    $(document).ready(function(){
      $('input[name="yourName"]').attr('placeholder', '<?php echo osc_esc_js(__('First name, Last name', 'zeta')); ?>');
      $('input[name="yourEmail"]').attr('placeholder', '<?php echo osc_esc_js(__('your.email@dot.com', 'zeta')); ?>');
      $('input[name="subject"]').attr('placeholder', '<?php echo osc_esc_js(__('Summarize your question', 'zeta')); ?>');
      $('textarea[name="message"]').attr('placeholder', '<?php echo osc_esc_js(__('Your question with all relevant details ...', 'zeta')); ?>');
    });
  </script>
</body>
</html>