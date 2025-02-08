<?php
  require_once 'functions.php';


  // Create menu
  $title = __('Advertisement', 'zeta');
  zet_menu($title);


  // GET & UPDATE PARAMETERS
  // $variable = zet_param_update('param_name', 'form_name', 'input_type', 'plugin_var_name');
  // input_type: check, value or code

  $banners = zet_param_update('banners', 'theme_action', 'check', 'theme-zeta');
  $banner_optimize_adsense = zet_param_update('banner_optimize_adsense', 'theme_action', 'check', 'theme-zeta');
  $banners_demo_ids = zet_param_update('banners_demo_ids', 'theme_action', 'value', 'theme-zeta');

  if(Params::getParam('theme_action') == 'done') {
    foreach(zet_banner_list() as $b) {
      zet_param_update($b['id'], 'theme_action', 'code', 'theme-zeta');
    }
  
    osc_add_flash_ok_message(__('Settings were successfully saved','zeta'), 'admin');
    header('Location:' . osc_admin_render_theme_url('oc-content/themes/zeta/admin/banner.php'));
    exit;
  }
?>


<div class="mb-body">

  <div class="mb-notes">
    <div class="mb-line"><?php _e('If you use Banner Ads Plugin, you can use banner or advert directly in theme banner space by defining its ID in bellow form.', 'zeta'); ?></div>
    <div class="mb-line"><?php _e('Usage examples: {{BANNER-ADS-PLUGIN-HOOK: my_custom_hook}}, {{BANNER-ADS-PLUGIN-BANNER: 123}}, {{BANNER-ADS-PLUGIN-ADVERT: 987}}', 'zeta'); ?></div>
  </div>
 
  <!-- BANNER SECTION -->
  <div class="mb-box">
    <div class="mb-head"><i class="fa fa-clone"></i> <?php _e('Advertisement', 'zeta'); ?></div>

    <div class="mb-inside mb-theme-banners">
      <form action="<?php echo osc_admin_render_theme_url('oc-content/themes/zeta/admin/banner.php'); ?>" method="POST">
        <input type="hidden" name="theme_action" value="done" />

        <div class="mb-row">
          <label for="banners" class="h1"><span><?php _e('Enable Theme Banners', 'zeta'); ?></span></label> 
          <input name="banners" id="banners" class="element-slide" type="checkbox" <?php echo (zet_param('banners') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('When enabled, bellow banners will be shown in front page.', 'zeta'); ?></div>
        </div>
        
        <div class="mb-row">
          <label for="banner_optimize_adsense" class="h2"><span><?php _e('Optimize Banners for Adsense', 'zeta'); ?></span></label> 
          <input name="banner_optimize_adsense" id="banner_optimize_adsense" class="element-slide" type="checkbox" <?php echo (zet_param('banner_optimize_adsense') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('When enabled, default width and height of banners will be removed and will be set to 100%. Fluid/responsive adsense banners should then work better.', 'zeta'); ?></div>
        </div>

        <?php if(zet_is_demo()) { ?>
          <div class="mb-row">
            <label for="banners_demo_ids" class=""><span><?php _e('Banners Visible in Demo Mode', 'zeta'); ?></span></label> 
            <input size="100" name="banners_demo_ids" id="banners_demo_ids" type="text" value="<?php echo osc_esc_html(zet_param('banners_demo_ids')); ?>"/>

            <div class="mb-explain"><?php _e('Enter banner identifiers/locations delimited by comma. No white spaces. Entered banners will be always visible on demo.', 'zeta'); ?></div>
          </div>
        <?php } ?>
        
        <?php foreach(zet_banner_list() as $b) { ?>
          <div class="mb-row">
            <label for="<?php echo $b['id']; ?>" class="h29"><span><?php echo ucwords(str_replace('_', ' ', $b['id'])); ?></span><em><?php echo $b['id']; ?></em></label> 
            <textarea class="mb-textarea mb-textarea-large" name="<?php echo $b['id']; ?>" placeholder="<?php echo osc_esc_html(__('Will be shown', 'zeta')); ?>: <?php echo $b['position']; ?>"><?php echo stripslashes(zet_param($b['id'])); ?></textarea>
          </div>
        <?php } ?>



        <div class="mb-row">&nbsp;</div>

        <div class="mb-foot">
          <button type="submit" class="mb-button"><?php _e('Save', 'zeta');?></button>
        </div>
      </form>
    </div>
  </div>

</div>


<?php echo zet_footer(); ?>