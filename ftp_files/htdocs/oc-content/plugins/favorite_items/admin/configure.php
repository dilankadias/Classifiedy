<?php
  // Create menu
  $title = __('Configure', 'favorite_items');
  fi_menu($title);


  // GET & UPDATE PARAMETERS
  // $variable = fi_param_update( 'param_name', 'form_name', 'input_type', 'plugin_var_name' );
  // input_type: check or value

  $max_per_list = mb_param_update('max_per_list', 'plugin_action', 'value', 'plugin-fi');
  $per_page = mb_param_update('per_page', 'plugin_action', 'value', 'plugin-fi');
  $quick_message = mb_param_update('quick_message', 'plugin_action', 'check', 'plugin-fi');

  if(Params::getParam('plugin_action') == 'done') {
    message_ok( __('Settings were successfully saved', 'favorite_items') );
  }

?>


<div class="mb-body">

  <!-- CONFIGURE SECTION -->
  <div class="mb-box">
    <div class="mb-head"><i class="fa fa-wrench"></i> <?php _e('Configure', 'favorite_items'); ?></div>

    <div class="mb-inside mb-minify">
      <form name="promo_form" action="<?php echo osc_admin_base_url(true); ?>" method="POST" enctype="multipart/form-data" >
        <?php if(!fi_is_demo()) { ?>
        <input type="hidden" name="page" value="plugins" />
        <input type="hidden" name="action" value="renderplugin" />
        <input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>configure.php" />
        <input type="hidden" name="plugin_action" value="done" />
        <?php } ?>


        <div class="mb-row">
          <label for="max_per_list" class="h1"><span><?php _e('List Limit', 'favorite_items'); ?></span></label> 
          <input name="max_per_list" style="width:150px;" type="number" min="1" value="<?php echo $max_per_list; ?>" />
          <div class="mb-input-desc"><?php _e('items', 'favorite_items'); ?></div>

          <div class="mb-explain"><?php _e('Set how many items can be added (favorited) into one user list. Default: 24', 'favorite_items'); ?></div>
        </div>

        <div class="mb-row">
          <label for="per_page" class="h2"><span><?php _e('Listings per Page', 'favorite_items'); ?></span></label> 
          <input name="per_page" style="width:150px;" type="number" min="1" value="<?php echo $per_page; ?>" />
          <div class="mb-input-desc"><?php _e('items', 'favorite_items'); ?></div>

          <div class="mb-explain"><?php _e('Set how many items can be added (favorited) into one user list. Default: 24', 'favorite_items'); ?></div>
        </div>


        <div class="mb-row">
          <label for="quick_message" class="h3"><span><?php _e('Quick Messages', 'favorite_items'); ?></span></label> 
          <input name="quick_message" type="checkbox" class="element-slide" <?php echo ($quick_message == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('Enable plugin quick messages in top right corner.', 'favorite_items'); ?></div>
        </div>



        <div class="mb-row">&nbsp;</div>

        <div class="mb-foot">
          <?php if(fi_is_demo()) { ?>
            <a class="mb-button mb-has-tooltip disabled" onclick="return false;" style="cursor:not-allowed;opacity:0.5;" title="<?php echo osc_esc_html(__('This is demo site', 'favorite_items')); ?>"><?php _e('Save', 'favorite_items');?></a>
          <?php } else { ?>
            <button type="submit" class="mb-button"><?php _e('Save', 'favorite_items');?></button>
          <?php } ?>
        </div>
      </form>
    </div>
  </div>


  <!-- PLUGIN INTEGRATION -->
  <div class="mb-box">
    <div class="mb-head"><i class="fa fa-wrench"></i> <?php _e('Plugin Setup', 'favorite_items'); ?></div>

    <div class="mb-inside">
      <div class="mb-row">
        <div class="mb-row">
          <div class="mb-line"><?php _e('All themes bought at OsclassPoint.com has already plugin pre-integrated, however for themes from other authors modifications may be required.', 'favorite_items'); ?></div>

          <div class="mb-line"><strong><?php _e('Make favorite button', 'favorite_items'); ?></strong></div>
          <div class="mb-line"><?php _e('Code must be entered inside item-loop.php or inside while(osc_has_items()) { ... } loop:', 'favorite_items'); ?></div>
          <span class="mb-code">&lt;?php echo fi_make_favorite(); ?&gt;</span>
        </div>

        <div class="mb-row">
          <div class="mb-line"><strong><?php _e('Show items in current list', 'favorite_items'); ?></strong></div>
          <div class="mb-line"><?php _e('Show listings that are in currently selected user\'s favorite list. Code can be placed anywhere:', 'favorite_items'); ?></div>
          <span class="mb-code">&lt;?php fi_list_items(); ?&gt;</span>
        </div>

        <div class="mb-row">
          <div class="mb-line"><strong><?php _e('Block with most favorited listings', 'favorite_items'); ?></strong></div>
          <div class="mb-line"><?php _e('Show list of most favorited listings on your site. Code can be placed anywhere:', 'favorite_items'); ?></div>
          <span class="mb-code">&lt;?php fi_most_favorited_items(); ?&gt;</span>
        </div>

      </div>
    </div>
  </div>
</div>


<?php echo fi_footer(); ?>