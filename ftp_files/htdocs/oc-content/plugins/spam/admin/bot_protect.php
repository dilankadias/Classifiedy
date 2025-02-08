<?php
  // Create menu
  $title = __('Bot protection', 'spam');
  ans_menu($title);


  // GET & UPDATE PARAMETERS
  // $variable = mb_param_update( 'param_name', 'form_name', 'input_type', 'plugin_var_name' );
  // input_type: check or value

  $allow_triple = mb_param_update('allow_triple', 'plugin_action', 'check', 'plugin-spam');
  $honeypot_enabled = mb_param_update('honeypot_enabled', 'plugin_action', 'check', 'plugin-spam');
  $ban_triple = mb_param_update('ban_triple', 'plugin_action', 'check', 'plugin-spam');
  $submask_triple = mb_param_update('submask_triple', 'plugin_action', 'check', 'plugin-spam');
  $domains_triple = mb_param_update('domains_triple', 'plugin_action', 'value', 'plugin-spam');
  $white_domains = mb_param_update('white_domains', 'plugin_action', 'value', 'plugin-spam');


  $dots_triple = mb_param_update('dots_triple', 'plugin_action', 'value', 'plugin-spam');
  $stopforumspam_triple = mb_param_update('stopforumspam_triple', 'plugin_action', 'check', 'plugin-spam');
  $upper_triple = mb_param_update('upper_triple', 'plugin_action', 'check', 'plugin-spam');
  $number_triple = mb_param_update('number_triple', 'plugin_action', 'check', 'plugin-spam');
  $track_reffer = mb_param_update('track_reffer', 'plugin_action', 'check', 'plugin-spam');
  $multiple_user_ip = mb_param_update('multiple_user_ip', 'plugin_action', 'value', 'plugin-spam');
  $min_confidence = mb_param_update('min_confidence', 'plugin_action', 'value', 'plugin-spam');


  // Check prerequisites for StopForumSpam check 
  $check_curl = function_exists('curl_version') ? 'Enabled' : 'Disabled';
  $check_xml = class_exists('SimpleXMLElement') ? 'Enabled' : 'Disabled';
  $check_content = ini_get('allow_url_fopen') ? 'Enabled' : 'Disabled';


  if(Params::getParam('plugin_action') == 'done') {
    message_ok(__('Settings were successfully saved', 'spam'));
  }
?>


<div class="mb-body">
 
  <!-- CONFIGURE SECTION -->
  <div class="mb-box">
    <div class="mb-head"><i class="fa fa-wrench"></i> <?php _e('Bot protection settings', 'spam'); ?></div>

    <div class="mb-inside">
      <form name="promo_form" action="<?php echo osc_admin_base_url(true); ?>" method="POST" enctype="multipart/form-data" >
        <?php if(!ans_is_demo()) { ?>
        <input type="hidden" name="page" value="plugins" />
        <input type="hidden" name="action" value="renderplugin" />
        <input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>bot_protect.php" />
        <input type="hidden" name="plugin_action" value="done" />
        <?php } ?>

        <div class="mb-row">
          <label for="allow_triple" class="h1"><span><?php _e('Enable Bot Protection Features', 'spam'); ?></span></label> 
          <input name="allow_triple" type="checkbox" class="element-slide" <?php echo ($allow_triple == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('When enabled, plugin features to protect your site against bots will be enabled.', 'spam'); ?></div>
        </div>

        <div class="mb-row">
          <label for="honeypot_enabled" class=""><span><?php _e('Enable HoneyPot Protection', 'spam'); ?></span></label> 
          <input name="honeypot_enabled" type="checkbox" class="element-slide" <?php echo ($honeypot_enabled == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('When enabled, additional fields  are added into form that are invisible for regular user, but behave like required for spam bots.', 'spam'); ?></div>
        </div>

        <div class="mb-row">
          <label for="ban_triple" class="h2"><span><?php _e('Ban Detected Bot', 'spam'); ?></span></label> 
          <input name="ban_triple" type="checkbox" class="element-slide" <?php echo ($ban_triple == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('When not enabled, new listing / comment / message will not be added or send, but user will not be banned. It is recommended to enable it so bot is also banned that leads to lower resource requirement on your server.', 'spam'); ?></div>
        </div>


        <div class="mb-row">
          <label for="submask_triple" class="h3"><span><?php _e('Ban IP Submask', 'spam'); ?></span></label> 
          <input name="submask_triple" type="checkbox" class="element-slide" <?php echo ($submask_triple == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('When enabled, whole IP submask is banned. This is recommended as spam bots usually switch last digit of IP address and trying to spam again.', 'spam'); ?></div>
        </div>


        <div class="mb-row">
          <label for="stopforumspam_triple" class="h4"><span><?php _e('Use StopForumSpam.com Service', 'spam'); ?></span></label> 
          <input name="stopforumspam_triple" type="checkbox" class="element-slide" <?php echo ($stopforumspam_triple == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain">
            <div class="mb-line"><?php _e('When enabled, plugin automatically validate user email & IP on stopforumspam.com - online database of spam bots. When match is found, user is banned as spam bot.', 'spam'); ?></div>
            <div class="mb-line"><?php _e('It is strongly recommended to have this option enabled and correct minimum confidence set to avoid fake user registrations and spam.', 'spam'); ?></div>
          </div>
        </div>
        
        <div class="mb-row">
          <label for="min_confidence" class="h7"><span><?php _e('Min. Confidence', 'spam'); ?></span></label> 
          <input type="number" size="10" min=0 max=100 name="min_confidence" value="<?php echo $min_confidence; ?>" style="text-align:right;"/>
          <div class="mb-input-desc">%</div>

          <div class="mb-explain">
            <div class="mb-line"><?php _e('When response from StopForumSpam is received, enter minimum confidence when this user will be considered as spammer and banned.', 'spam'); ?></div>
            <div class="mb-line"><?php _e('Example: You set minimum confidence to 80 and record from StopForumSpam.com has confidence 60 that user is spammer, then this user will not be banned.', 'spam'); ?></div>
            <div class="mb-line"><?php _e('Setting higher confidence will avoid banning regular users those just publish a lot. Recommended value is 70-90.', 'spam'); ?></div>
          </div>
        </div>   

        <div class="mb-row">
          <label for="track_reffer" class="h8"><span><?php _e('Identify Refferal URL', 'spam'); ?></span></label> 
          <input name="track_reffer" type="checkbox" class="element-slide" <?php echo ($track_reffer == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Enable to recognize refferal URL of user. If user comes directly to Post Listing page and referral is not Google, Bing, Yahoo or your website, it will be banned.', 'spam'); ?></div>
        </div>


        <div class="mb-row">
          <label for="upper_triple" class="h5"><span><?php _e('Ban for too many Uppercase', 'spam'); ?></span></label> 
          <input name="upper_triple" type="checkbox" class="element-slide" <?php echo ($upper_triple == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('When enabled, visitor using email with too many uppercase characters will be banned.', 'spam'); ?></div>
        </div>


        <div class="mb-row">
          <label for="number_triple" class="h6"><span><?php _e('Ban for too many Digits', 'spam'); ?></span></label> 
          <input name="number_triple" type="checkbox" class="element-slide" <?php echo ($number_triple == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('When enabled, visitor using email with too many digits will be banned.', 'spam'); ?></div>
        </div>


        <div class="mb-row">
          <label for="dots_triple" class="h7"><span><?php _e('Max. Dots in Email', 'spam'); ?></span></label> 
          <input type="number" size="10" name="dots_triple" value="<?php echo $dots_triple; ?>" style="text-align:right;"/>
          <div class="mb-input-desc"><?php echo __('dots', 'spam'); ?></div>

          <div class="mb-explain"><?php _e('When email contains more dots, it will be banned. Example: ad.do.bo.de.co@mail.com contains 5 dots.', 'spam'); ?></div>
        </div>     


        <div class="mb-row">
          <label for="domains_triple" class="h9"><span><?php _e('Banned Email Domains', 'spam'); ?></span></label> 
          <input type="text" size="120" name="domains_triple" value="<?php echo $domains_triple; ?>"/>

          <div class="mb-explain"><?php _e('Use comma as delimiter, no white spaces needed.', 'spam'); ?></div>
        </div>

        <div class="mb-row">
          <label for="white_domains" class="h9"><span><?php _e('Whitelisted Email Domains', 'spam'); ?></span></label> 
          <input type="text" size="120" name="white_domains" value="<?php echo $white_domains; ?>"/>

          <div class="mb-explain"><?php _e('Use comma as delimiter, no white spaces needed. Keep in mind when you enter domain here, all others will be blocked !', 'spam'); ?></div>
        </div>

        <div class="mb-row">
          <label for="multiple_user_ip" class="h10"><span><?php _e('Mutliple account from same IP', 'spam'); ?></span></label> 

          <select name="multiple_user_ip">
            <option <?php if($multiple_user_ip == 0) { echo 'selected';} ?> value="0"><?php _e('Allow multiple accounts','spam'); ?></option>
            <option <?php if($multiple_user_ip == 1) { echo 'selected';} ?> value="1"><?php _e('Allow maximum 3 accounts per IP','spam'); ?></option>
            <option <?php if($multiple_user_ip == 2) { echo 'selected';} ?> value="2"><?php _e('Deny creating multiple accounts','spam'); ?></option>
          </select>

          <div class="mb-explain"><?php _e('Allow in case you think there are some suspicious users that register multiple account to avoid duplicate control. When enabled, user cannot create accounts (or can create just 3 accounts) from same IP address. Use only in case you are sure there are not many people coming to your site using same IP address. When account creation is restricted and limit was reached, new account will not be created, user will be informed via flash message, but will not be banned.', 'spam'); ?></div>
        </div>
        

        <div class="mb-row">&nbsp;</div>

        <div class="mb-foot">
          <?php if(ans_is_demo()) { ?>
            <a class="mb-button mb-has-tooltip disabled" onclick="return false;" style="cursor:not-allowed;opacity:0.5;" title="<?php echo osc_esc_html(__('This is demo site', 'spam')); ?>"><?php _e('Save', 'spam');?></a>
          <?php } else { ?>
            <button type="submit" class="mb-button"><?php _e('Save', 'spam');?></button>
          <?php } ?>
        </div>
      </form>
    </div>
  </div>


  <!-- PLUGIN INTEGRATION -->
  <div class="mb-box mb-setup">
    <div class="mb-head"><i class="fa fa-wrench"></i> <?php _e('Plugin Setup', 'spam'); ?></div>

    <div class="mb-inside">
      <div class="mb-row">
        <div class="mb-line"><strong><?php _e('Honeypot Protection Integration', 'spam'); ?></strong></div>
        <div class="mb-line"><?php _e('To enable Bot Protect JavaScript Control when user add or validate new listing, add new comment or contact seller, place following code into form that manage this action.', 'spam'); ?></div>
        <div class="mb-line"><?php _e('Plugin tries to push code via hooks. If you can see it in page source code (form code), there is no action required.', 'spam'); ?></div>
        <span class="mb-code">&lt;?php osc_run_hook('ans_bot_protect'); ?&gt;</span>

        <div class="mb-line">&nbsp;</div>
        <div class="mb-line"><?php _e('Files where to place above code:', 'spam'); ?></div>
    
        <ul class="mb-ul">
          <li><strong><?php _e('Publish new listing', 'spam'); ?></strong> - <?php _e('in', 'spam'); ?> oc-content/themes/<?php echo osc_current_web_theme(); ?>/item-post.php <?php _e('into form with', 'spam');?> name="item"</li>
          <li><strong><?php _e('Add new comment', 'spam'); ?></strong> - <?php _e('in', 'spam'); ?> oc-content/themes/<?php echo osc_current_web_theme(); ?>/item.php <?php _e('into form with', 'spam');?> name="comment_form"</li>
          <li><strong><?php _e('Contact seller', 'spam'); ?></strong> - <?php _e('in', 'spam'); ?> oc-content/themes/<?php echo osc_current_web_theme(); ?>/item.php <?php _e('into form with', 'spam');?> name="contact_form"</li>
        </ul>

        <div class="mb-line">&nbsp;</div>

        <div class="mb-line"><?php _e('Note: Code needs to be placed between <strong>&lt;form&gt;</strong> and <strong>&lt;/form&gt;</strong> tags.','spam'); ?></div>
        <div class="mb-line"> <?php _e('Note: Adding this code is not required action, but if you have still difficulties with spam, it is recommended to do this.','spam'); ?></div>

      </div>

      <div class="mb-row">&nbsp;</div>

      <div class="mb-row">
        <div class="mb-line"><strong><?php _e('SpamForumSpam.com requirement', 'spam'); ?></strong></div>

        <span class="mb-line"><span class="mb-left"><?php echo __('Curl', 'spam'); ?>: </span><span class="mb-right <?php echo $check_curl; ?>"><?php echo $check_curl; ?></span>
        <span class="mb-line"><span class="mb-left"><?php echo __('SimpleXMLElement', 'spam'); ?>: </span><span class="mb-right <?php echo $check_xml; ?>"><?php echo $check_xml; ?></span>
        <span class="mb-line"><span class="mb-left"><?php echo __('allow_url_fopen', 'spam'); ?>: </span><span class="mb-right <?php echo $check_content; ?>"><?php echo $check_content; ?></span>

        <span class="mb-line">&nbsp;</span>
        <span class="mb-line"><?php _e('If something is <u>disabled</u>, you should disable this function. If you are getting white page after posting new listing, disable this function as well.', 'spam'); ?></span>

      </div>
    </div>
  </div>
</div>

<?php echo ans_footer(); ?>	