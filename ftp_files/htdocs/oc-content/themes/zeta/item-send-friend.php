<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo zet_language_dir(); ?>" mode="<?php echo zet_light_dark_mode(); ?>" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>" class="item-form-html">
<head>
  <?php osc_current_web_theme_path('head.php'); ?>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />
  <script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('jquery.validate.min.js'); ?>"></script>
</head>

<?php 
  $type = (Params::getParam('type') == '' ? 'send_friend' : Params::getParam('type')); 
?>  

<body id="item-forms" class="modal-data <?php osc_run_hook('body_class'); ?>">
  <?php osc_current_web_theme_path('header.php'); ?>

  <?php if($type == 'friend') { ?>

    <!-- SEND TO FRIEND FORM -->
    <form target="_top" id="sendfriend" name="sendfriend" action="<?php echo osc_base_url(true); ?>" method="post" class="wrap-scrollable">
      <input type="hidden" name="action" value="send_friend_post" />
      <input type="hidden" name="page" value="item" />
      <input type="hidden" name="id" value="<?php echo osc_item_id(); ?>" />

      <div class="head"><?php _e('Send to friend', 'zeta'); ?></div>

      <div class="subhead"><?php _e('Listing details will be mailed to your friend', 'zeta'); ?></div>

      <div class="middle ns">
        <div class="row">
          <div id="item-card">
            <?php if(osc_images_enabled_at_items()) { ?> 
              <?php osc_get_item_resources(); ?>
              <?php osc_reset_resources(); ?>

              <?php if(osc_count_item_resources() > 0) { ?>
                <div class="img">
                  <?php for($i = 0;osc_has_item_resources(); $i++) { ?>
                    <img class="<?php echo (zet_is_lazy() ? 'lazy' : ''); ?>" src="<?php echo (zet_is_lazy() ? zet_get_load_image() : osc_resource_thumbnail_url()); ?>" data-src="<?php echo osc_resource_thumbnail_url(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?> - <?php echo $i+1;?>"/>
                    <?php break; ?>
                  <?php } ?>
                </div>
              <?php } ?>
            <?php } ?>
            
            <div class="dsc">
              <strong><?php echo osc_item_title(); ?></strong>
              
              <?php if(zet_check_category_price(osc_item_category_id())) { ?>
                <div><?php echo osc_item_formated_price(); ?></div>
              <?php } ?>
            </div>
          </div>
        </div>

        <?php SendFriendForm::js_validation(); ?>
        <ul id="error_list"></ul>

        <?php if(osc_is_web_user_logged_in()) { ?>
          <input type="hidden" name="yourName" value="<?php echo osc_esc_html(osc_logged_user_name()); ?>" />
          <input type="hidden" name="yourEmail" value="<?php echo osc_logged_user_email();?>" />
        <?php } else { ?>
          <div class="row">
            <label for="yourName"><span><?php _e('Your name', 'zeta'); ?></span> <span class="req">*</span></label> 
            <div class="input-box"><?php SendFriendForm::your_name(); ?></div>

            <label for="yourEmail"><span><?php _e('Your e-mail address', 'zeta'); ?></span> <span class="req">*</span></label>
            <div class="input-box"><?php SendFriendForm::your_email(); ?></div>
          </div>
        <?php } ?>

        <div class="row">
          <label for="friendName"><span><?php _e("Your friend's name", 'zeta'); ?></span> <span class="req">*</span></label>
          <div class="input-box"><?php SendFriendForm::friend_name(); ?></div>

          <label for="friendEmail"><span><?php _e("Your friend's e-mail address", 'zeta'); ?></span> <span class="req">*</span></label>
          <div class="input-box last"><?php SendFriendForm::friend_email(); ?></div>
        </div>

        <div class="row last">
          <label for="message"><span><?php _e('Message', 'zeta'); ?></span> <span class="req">*</span></label>
          <?php SendFriendForm::your_message(); ?>
        </div>

        <?php zet_show_recaptcha(); ?>
  
        <div class="row buttons">
          <button type="<?php echo (zet_param('forms_ajax') == 1 ? 'button' : 'submit'); ?>" id="send-message" class="btn item-form-submit" data-type="friend"><?php _e('Send message', 'zeta'); ?></button>
        </div>
      </div>
    </form>
 
  <?php } else if($type == 'comment' && osc_comments_enabled()) { ?>

    <!-- NEW COMMENT FORM -->
    <?php if(osc_comments_enabled() && (osc_reg_user_post_comments () && osc_is_web_user_logged_in() || !osc_reg_user_post_comments())) { ?>
      <form target="_top" action="<?php echo osc_base_url(true); ?>" method="post" name="comment_form" id="comment_form" class="item-ajax-box wrap-scrollable">
        <input type="hidden" name="action" value="add_comment" />
        <input type="hidden" name="page" value="item" />
        <input type="hidden" name="id" value="<?php echo osc_item_id(); ?>" />
        <?php if(function_exists('osc_enable_comment_reply') && osc_enable_comment_reply()) { ?><input type="hidden" name="replyId" value="<?php echo osc_esc_html(Params::getParam('replyToCommentId')); ?>" /><?php } ?>

        <?php if(function_exists('osc_enable_comment_reply') && osc_enable_comment_reply() && Params::getParam('replyToCommentId') > 0) { ?>
          <?php $original_comment = ItemComment::newInstance()->findByPrimaryKey(Params::getParam('replyToCommentId')); ?>

          <?php if(isset($original_comment['pk_i_id']) && $original_comment['fk_i_item_id'] == osc_item_id()) { ?>
            <div class="head"><?php _e('Reply to comment', 'zeta'); ?></div>
            <div class="subhead">
              <div><?php echo sprintf(__('Original comment: "%s"', 'zeta'), '<i>' . osc_highlight($original_comment['s_title'] . ' ' . $original_comment['s_body'], 300) . '</i>'); ?></div>
              <div><?php _e('Comments are pending moderation', 'zeta'); ?></div>
            </div>
          <?php } else { ?>
            <div class="head"><?php _e('Invalid comment', 'zeta'); ?></div>
            <?php exit; ?>
          <?php } ?>
        <?php } else { ?>
          <div class="head"><?php _e('Add a new comment', 'zeta'); ?></div>
          <div class="subhead"><?php _e('Comments are pending moderation', 'zeta'); ?></div>
        <?php } ?>
        

        <div class="middle ns">
          <div class="row">
            <div id="item-card">
              <?php if(osc_images_enabled_at_items()) { ?> 
                <?php osc_get_item_resources(); ?>
                <?php osc_reset_resources(); ?>

                <?php if(osc_count_item_resources() > 0) { ?>
                  <div class="img">
                    <?php for($i = 0;osc_has_item_resources(); $i++) { ?>
                      <img class="<?php echo (zet_is_lazy() ? 'lazy' : ''); ?>" src="<?php echo (zet_is_lazy() ? zet_get_load_image() : osc_resource_thumbnail_url()); ?>" data-src="<?php echo osc_resource_thumbnail_url(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?> - <?php echo $i+1;?>"/>
                      <?php break; ?>
                    <?php } ?>
                  </div>
                <?php } ?>
              <?php } ?>
              
              <div class="dsc">
                <strong><?php echo osc_item_title(); ?></strong>
                
                <?php if(zet_check_category_price(osc_item_category_id())) { ?>
                  <div><?php echo osc_item_formated_price(); ?></div>
                <?php } ?>
              </div>
            </div>
          </div>
            
          <?php CommentForm::js_validation(); ?>
          <ul id="comment_error_list"></ul>

          <?php if(osc_is_web_user_logged_in()) { ?>
            <input type="hidden" name="authorName" value="<?php echo osc_esc_html(osc_logged_user_name()); ?>" />
            <input type="hidden" name="authorEmail" value="<?php echo osc_logged_user_email();?>" />
          <?php } else { ?>
            <div class="row">
              <label for="authorName"><?php _e('Name', 'zeta'); ?></label> 
              <div class="input-box"><?php CommentForm::author_input_text(); ?></div>
            </div>

            <div class="row">
              <label for="authorEmail"><span><?php _e('E-mail', 'zeta'); ?></span> <span class="req">*</span></label> 
              <div class="input-box"><?php CommentForm::email_input_text(); ?></div>
            </div>                  
          <?php } ?>
          
          <?php if(osc_enable_comment_rating()) { ?>
            <?php if(Params::getParam('replyToCommentId') > 0 && osc_enable_comment_reply_rating() || Params::getParam('replyToCommentId') <= 0) { ?>
              <div class="row">
                <label for=""><?php _e('Rating', 'zeta'); ?></label>
                <div class="comment-stars">
                  <?php //CommentForm::rating_input_text(); ?>
                  <input type="hidden" name="rating" value="" />

                  <div class="comment-leave-rating">
                    <i class="fa fa-star is-rating-item" data-value="1"></i> 
                    <i class="fa fa-star is-rating-item" data-value="2"></i> 
                    <i class="fa fa-star is-rating-item" data-value="3"></i> 
                    <i class="fa fa-star is-rating-item" data-value="4"></i> 
                    <i class="fa fa-star is-rating-item" data-value="5"></i> 
                  </div>
                  
                  <span class="comment-rating-selected"></span>
                </div>
              </div>
            <?php } ?>
          <?php } ?>

          <div class="row" id="last">
            <label for="title"><?php _e('Title', 'zeta'); ?></label>
            <div class="input-box"><?php CommentForm::title_input_text(); ?></div>
          </div>
      
          <?php osc_run_hook('item_comment_form'); ?>
          
          <div class="row">
            <label for="body"><span><?php _e('Message', 'zeta'); ?></span> <span class="req">*</span></label>
            <?php CommentForm::body_input_textarea(); ?>
          </div>

          <?php zet_show_recaptcha(); ?>

          <div class="row buttons">
            <button type="<?php echo (zet_param('forms_ajax') == 1 ? 'button' : 'submit'); ?>" id="send-comment" class="btn item-form-submit" data-type="comment"><?php _e('Submit comment', 'zeta'); ?></button>
          </div>
        </div>
      </form>
    <?php } ?>


  <?php } else if($type == 'contact' && getBoolPreference('item_contact_form_disabled') != 1) { ?>

    <!-- ITEM CONTACT FORM -->
    <form target="_top" action="<?php echo osc_base_url(true); ?>" method="post" name="contact_form" id="contact_form" class="item-ajax-box wrap-scrollable"<?php if(osc_item_attachment()) { ?> enctype="multipart/form-data"<?php } ?>>
      <input type="hidden" name="action" value="contact_post" />
      <input type="hidden" name="page" value="item" />
      <input type="hidden" name="id" value="<?php echo osc_item_id(); ?>" />

      <?php osc_prepare_user_info(); ?>

      <div class="head"><?php _e('Contact seller', 'zeta'); ?></div>

      <div class="subhead"><?php _e('Send message to owner of listing', 'zeta'); ?></div>
      
      <div class="middle ns">
        <div class="row">
          <div id="item-card">
            <?php if(osc_images_enabled_at_items()) { ?> 
              <?php osc_get_item_resources(); ?>
              <?php osc_reset_resources(); ?>

              <?php if(osc_count_item_resources() > 0) { ?>
                <div class="img">
                  <?php for($i = 0;osc_has_item_resources(); $i++) { ?>
                    <img class="<?php echo (zet_is_lazy() ? 'lazy' : ''); ?>" src="<?php echo (zet_is_lazy() ? zet_get_load_image() : osc_resource_thumbnail_url()); ?>" data-src="<?php echo osc_resource_thumbnail_url(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?> - <?php echo $i+1;?>"/>
                    <?php break; ?>
                  <?php } ?>
                </div>
              <?php } ?>
            <?php } ?>
            
            <div class="dsc">
              <strong><?php echo osc_item_title(); ?></strong>
              
              <?php if(zet_check_category_price(osc_item_category_id())) { ?>
                <div><?php echo osc_item_formated_price(); ?></div>
              <?php } ?>
            </div>
          </div>
        </div>
      
        <?php ContactForm::js_validation(); ?>
        <ul id="error_list"></ul>

        <?php if(osc_item_is_expired ()) { ?>
          <div class="problem">
            <?php _e('This listing expired, you cannot contact seller.', 'zeta'); ?>
          </div>
        <?php } else if((osc_logged_user_id() == osc_item_user_id()) && osc_logged_user_id() != 0) { ?>
          <div class="problem">
            <?php _e('It is your own listing, you cannot contact yourself.', 'zeta'); ?>
          </div>
        <?php } else if(osc_reg_user_can_contact() && !osc_is_web_user_logged_in()) { ?>
          <div class="problem">
            <?php _e('You must log in or register a new account in order to contact the advertiser.', 'zeta'); ?>
          </div>
        <?php } else { ?> 

          <?php if(osc_is_web_user_logged_in()) { ?>
            <input type="hidden" name="yourName" value="<?php echo osc_esc_html(osc_logged_user_name()); ?>" />
            <input type="hidden" name="yourEmail" value="<?php echo osc_logged_user_email();?>" />
          <?php } else { ?>
            <div class="row">
              <label for="yourName"><?php _e('Name', 'zeta'); ?> <span class="req">*</span></label> 
              <div class="input-box"><?php ContactForm::your_name(); ?></div>
            </div>

            <div class="row">
              <label for="yourEmail"><span><?php _e('E-mail', 'zeta'); ?></span> <span class="req">*</span></label> 
              <div class="input-box"><?php ContactForm::your_email(); ?></div>
            </div>       
          <?php } ?>
     

          <div class="row">
            <label for="phoneNumber"><span><?php _e('Phone', 'zeta'); ?></span></label> 
            <div class="input-box"><?php ContactForm::your_phone_number(); ?></div>
          </div>          
    
          <div class="row">
            <label for="message"><span><?php _e('Message', 'zeta'); ?></span> <span class="req">*</span></label>
            <div class="input-box no-margin"><?php ContactForm::your_message(); ?></div>
          </div>

          <?php if(osc_item_attachment()) { ?>
            <div class="row has-file">
              <label for="attachment"><?php _e('Attachment', 'zeta'); ?>:</label>
              <div class="input-box"><?php ContactForm::your_attachment(); ?></div>
            </div>
          <?php } ?>

          <?php 
            osc_run_hook('item_contact_form', osc_item_id());
            zet_show_recaptcha();
          ?>

          <div class="row buttons">
            <button type="<?php echo (zet_param('forms_ajax') == 1 ? 'button' : 'submit'); ?>" id="send-message" class="btn item-form-submit" data-type="contact"><?php _e('Send message', 'zeta'); ?></button>
          </div>
        <?php } ?>
      </div>
    </form>


  <?php } else if($type == 'contact_public' && getBoolPreference('item_contact_form_disabled') != 1) { ?>

    <!-- PUBLIC PROFILE CONTACT SELLER -->
    <?php if(osc_reg_user_can_contact() && osc_is_web_user_logged_in() || !osc_reg_user_can_contact()) { ?>
      <?php
        $user_id = Params::getParam('userId');
        $user = osc_get_user_row($user_id);
      ?>

      <form target="_top" action="<?php echo osc_base_url(true); ?>" method="post" name="contact_form" id="contact_form_public" class="item-ajax-box wrap-scrollable">
        <input type="hidden" name="action" value="contact_post" class="nocsrf" />
        <input type="hidden" name="page" value="user" />
        <input type="hidden" name="id" value="<?php echo $user_id; ?>" />
        <?php if(osc_is_web_user_logged_in()) { ?>
        <input type="hidden" id="yourName" name="yourName" value="<?php echo osc_logged_user_name(); ?>">
        <input type="hidden" id="yourEmail" name="yourEmail" value="<?php echo osc_logged_user_email(); ?>">
        <?php } ?>

        <div class="head"><?php _e('Contact seller', 'zeta'); ?></div>
        
        <div class="subhead"><?php _e('Send message to seller', 'zeta'); ?></div>

        <div class="middle ns">
          <div class="row">
            <div id="item-card" class="user">
              <div class="img">
                <img class="<?php echo (zet_is_lazy() ? 'lazy' : ''); ?>" src="<?php echo (zet_is_lazy() ? zet_get_load_image() : zet_profile_picture($user_id, 'small')); ?>" data-src="<?php echo zet_profile_picture($user_id, 'small'); ?>" alt="<?php echo osc_esc_html($user['s_name']); ?>"/>
              </div>
              
              <div class="dsc">
                <strong><?php echo $user['s_name']; ?></strong>
                <span><?php echo sprintf(__('Last online %s', 'zeta'), zet_smart_date($user['dt_access_date'])); ?></span>
              </div>
            </div>
          </div>
        
        
          <?php ContactForm::js_validation(); ?>
          <ul id="error_list"></ul>

          <?php if($user_id == osc_logged_user_id() && osc_is_web_user_logged_in()) { ?>
            <div class="problem"><?php _e('This is your own profile!', 'zeta'); ?></div>
          <?php } else { ?>
            <?php if(!osc_is_web_user_logged_in()) { ?>
              <div class="row">
                <label for="yourName"><?php _e('Name', 'zeta'); ?></label> 
                <div class="input-box"><?php ContactForm::your_name(); ?></div>
              </div>

              <div class="row">
                <label for="yourEmail"><span><?php _e('E-mail', 'zeta'); ?></span> <span class="req">*</span></label> 
                <div class="input-box"><?php ContactForm::your_email(); ?></div>
              </div>
            <?php } ?>              

            <div class="row last">
              <label for="phoneNumber"><span><?php _e('Phone number', 'zeta'); ?></span></label>
              <div class="input-box"><?php ContactForm::your_phone_number(); ?></div>
            </div>

            <div class="row">
              <label for="message"><span><?php _e('Message', 'zeta'); ?></span> <span class="req">*</span></label>
              <?php ContactForm::your_message(); ?>
            </div>

            <?php zet_show_recaptcha(); ?>

            <div class="row buttons">
              <button type="<?php echo (zet_param('forms_ajax') == 1 ? 'button' : 'submit'); ?>" id="send-public-message" class="btn item-form-submit" data-type="contact_public"><?php _e('Send message', 'zeta'); ?></button>
            </div>
          <?php } ?>
        </div>
      </form>
    <?php } ?>
  <?php } ?>

  <script>
    $('#sendfriend #yourName, #sendfriend #yourEmail, #sendfriend #friendName, #sendfriend #friendEmail, #sendfriend #yourName, #sendfriend #message').prop('required', true);
    $('#comment_form #body, #comment_form #yourName').prop('required', true);
    $('#contact_form #yourName, #contact_form #yourEmail, #contact_form #message').prop('required', true);
  </script>

  <?php osc_current_web_theme_path('footer.php'); ?>
</body>
</html>