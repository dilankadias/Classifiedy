<?php
  // Create menu
  $title = __('Bad words', 'spam');
  ans_menu($title);


  // GET & UPDATE PARAMETERS
  // $variable = mb_param_update( 'param_name', 'form_name', 'input_type', 'plugin_var_name' );
  // input_type: check or value

  $enable_badwords = mb_param_update('enable_badwords', 'plugin_action', 'check', 'plugin-spam');
  $list_badwords = mb_param_update('list_badwords', 'plugin_action', 'value', 'plugin-spam');


  if(Params::getParam('plugin_action') == 'done') {
    message_ok(__('Settings were successfully saved', 'spam'));
  }
?>


<div class="mb-body">
 
  <!-- CONFIGURE SECTION -->
  <div class="mb-box">
    <div class="mb-head"><i class="fa fa-wrench"></i> <?php _e('Badwords settings', 'spam'); ?></div>

    <div class="mb-inside mb-minify">
      <form name="promo_form" action="<?php echo osc_admin_base_url(true); ?>" method="POST" enctype="multipart/form-data" >
        <?php if(!ans_is_demo()) { ?>
        <input type="hidden" name="page" value="plugins" />
        <input type="hidden" name="action" value="renderplugin" />
        <input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>badwords.php" />
        <input type="hidden" name="plugin_action" value="done" />
        <?php } ?>

        <div class="mb-row">
          <label for="enable_badwords" class="h1"><span><?php _e('Enable Bad Words', 'spam'); ?></span></label> 
          <input name="enable_badwords" type="checkbox" class="element-slide" <?php echo ($enable_badwords == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('When enabled, bad words listed bellow will be masked', 'spam'); ?></div>
        </div>
        
        
        <div class="mb-row">
          <label for="list_badwords" class="h2"><span><?php _e('Badwords List', 'spam'); ?></span></label> 
          <textarea name="list_badwords"><?php echo osc_esc_html($list_badwords); ?></textarea>

          <div class="mb-explain"><?php _e('Use comma as delimiter, no white spaces needed. Badwords are case insensitive. Example: word1,Word2,wORD3,WORD4 . Result for title/description "I use word4 now." will be "I use ***** now."', 'spam'); ?></div>
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
  <div class="mb-box">
    <div class="mb-head"><i class="fa fa-wrench"></i> <?php _e('Plugin Setup', 'spam'); ?></div>

    <div class="mb-inside">

      <div class="mb-row">
        <div class="mb-line"><?php _e('Bad words feature does not require any modifications in theme files.', 'spam'); ?></div>
        <div class="mb-line"><?php _e('In some cases, you may want to replace function osc_item_title() with ans_clear_title() and osc_item_description() with ans_clear_description().', 'spam'); ?></div>
      </div>
    </div>
  </div>
</div>

<?php echo ans_footer(); ?>