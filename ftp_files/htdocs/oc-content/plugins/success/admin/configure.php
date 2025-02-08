<?php
  // Create menu
  $title = __('Configure', 'success');
  scf_menu($title);


  // GET & UPDATE PARAMETERS
  // $variable = mb_param_update( 'param_name', 'form_name', 'input_type', 'plugin_var_name' );
  // input_type: check or value


  $enabled = mb_param_update( 'enabled', 'plugin_action', 'check', 'plugin-success' );
  $support = mb_param_update( 'support', 'plugin_action', 'check', 'plugin-success' );


  if(Params::getParam('plugin_action') == 'done') {
    message_ok( __('Settings were successfully saved', 'success') );
  }

?>



<div class="mb-body">
  <!-- CONFIGURE SECTION -->
  <div class="mb-box">
    <div class="mb-head"><i class="fa fa-cog"></i> <?php _e('Configure', 'success'); ?></div>

    <div class="mb-inside">
      <form name="promo_form" id="promo_form" action="<?php echo osc_admin_base_url(true); ?>" method="POST" enctype="multipart/form-data" >
        <input type="hidden" name="page" value="plugins" />
        <input type="hidden" name="action" value="renderplugin" />
        <input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>configure.php" />
        <input type="hidden" name="plugin_action" value="done" />


        <div class="mb-row">
          <label for="enabled" class="h1"><span><?php _e('Enable Box', 'success'); ?></span></label> 
          <input name="enabled" id="enabled" class="element-slide" type="checkbox" <?php echo ($enabled == 1 ? 'checked' : ''); ?> />
          
          <div class="mb-explain"><?php _e('When enabled, after successful listing publish, information box is shown with share and promote options.', 'success'); ?></div>
        </div>

        <div class="mb-row">
          <label for="support" class="h2"><span><?php _e('Support Author', 'success'); ?></span></label> 
          <input name="support" id="support" class="element-slide" type="checkbox" <?php echo ($support == 1 ? 'checked' : ''); ?> />
        </div>

        <div class="mb-row">&nbsp;</div>

        <div class="mb-foot">
          <button type="submit" class="mb-button"><?php _e('Save', 'success');?></button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php echo scf_footer(); ?>