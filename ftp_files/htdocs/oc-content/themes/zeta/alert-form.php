<?php 
  $active = false;
  if(function_exists('osc_search_alert_subscribed') && osc_search_alert_subscribed()) { 
    $active = true;
  }
?>

<a class="open-alert-box <?php echo ($active ? ' active' : ''); ?>" href="#" title="<?php echo osc_esc_html($active ? __('You have already saved this search', 'zeta') : __('You will receive email notification once new listing matching search criteria is published', 'zeta')); ?>">
  <?php if($active) { ?>
    <i class="fas fa-bell"></i>
    <i class="fas fa-check active-badge"></i>
    <span><?php _e('Search is saved', 'zeta'); ?></span>
  <?php } else { ?>
    <i class="far fa-bell"></i>
    <span><?php _e('Save this search', 'zeta'); ?></span>
  <?php } ?>
</a>

<div class="alert-box <?php if(osc_is_web_user_logged_in()) { ?>logged<?php } else { ?>nonlogged<?php } ?>" style="display:none;">
  <div class="head"><?php _e('Save this search', 'zeta'); ?></div>

  <form action="<?php echo osc_base_url(true); ?>" method="post" name="sub_alert" id="alert-form" class="wrap nocsrf">
    <?php AlertForm::page_hidden(); ?>
    <?php AlertForm::alert_hidden(); ?>
    <?php AlertForm::user_id_hidden(); ?>
    
    <div class="text">
      <?php _e('Save search to receive email notification when new listing matching search criteria is published.', 'zeta'); ?>
    </div>

    <div class="inputs" <?php if($active) { ?>style="display:none;"<?php } ?>>
      <input id="alert_email" type="email" name="alert_email" required value="<?php echo osc_esc_html(osc_is_web_user_logged_in() ? osc_logged_user_email() : ''); ?>" placeholder="<?php echo osc_esc_html(__('Enter your email...', 'zeta')); ?>" <?php echo osc_is_web_user_logged_in() ? 'readonly' : ''; ?>/>
      <button type="submit" class="btn btn-secondary create-alert"><?php _e('Save', 'zeta'); ?></button>
    </div>
    
    <div class="response ok" <?php if($active) { ?>style="display:block;"<?php } ?>><?php echo sprintf(__('You have successfully saved this search! You will receive %s notifications to email %s.', 'zeta'), '<strong class="res-frequency">' . __('daily', 'zeta') . '</strong>', '<strong class="res-email"></strong>'); ?></div>
    <div class="response duplicate"><?php echo sprintf(__('You already had saved this search for email %s.', 'zeta'), '<strong class="res-email"></strong>'); ?></div>
    <div class="response signature"><?php echo __('Error: Invalid signature - server signature does not match to alert signature.', 'zeta'); ?></div>
    <div class="response error"><?php echo __('Error: Search was not saved, please try it again later.', 'zeta'); ?></div>

    <div class="line">
      <a href="<?php echo osc_user_alerts_url(); ?>"><?php _e('Manage saved searches', 'zeta'); ?> &#8594;</a>
    </div>
  </form>
</div>

<script type="text/javascript">
$(document).on('submit', 'form[name="sub_alert"]', function(e){
  e.preventDefault();

  var form = $(this);
  var button = $(this).find('button');
  
  form.addClass('loading');
  
  $.ajax({
    url: form.attr('action'),
    type: "POST",
    data: {
      email: form.find('input[name="alert_email"]').val(), 
      userid: form.find('input[name="alert_userId"]').val(), 
      alert: form.find('input[name="alert"]').val(), 
      page: 'ajax', 
      action: 'alerts'
    },
    success: function(response){
      form.removeClass('loading');
      form.find('.inputs').hide(0);
      form.find('.response .res-email').text(form.find('input[name="alert_email"]').val());
      form.find('.response').hide(0);
      
      if(response == 1) {
        form.find('.response.ok').show(0);
      } else if(response == 0) {
        form.find('.response.duplicate').show(0);
      } else if(response == -2) {
        form.find('.response.signature').show(0);
      } else {  // response == -1
        form.find('.response.error').show(0);
      }
    },
    error: function(response) {
      console.log(response);

      form.removeClass('loading');
      form.find('.inputs').hide(0);
      form.find('.response.error').show(0);
    }
  });
});
</script>