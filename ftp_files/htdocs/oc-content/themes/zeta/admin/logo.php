<?php
  require_once 'functions.php';


  // Create menu
  $title = __('Category', 'zeta');
  zet_menu($title);


  // GET & UPDATE PARAMETERS
  // $variable = zet_param_update('param_name', 'form_name', 'input_type', 'plugin_var_name');
  // input_type: check, value or code
  $default_logo = zet_param_update('default_logo', 'theme_action', 'check', 'theme-zeta');

  $allowed_ext = array('webp', 'jpg', 'jpeg', 'png', 'gif');


  switch(Params::getParam('theme_action')) {
    case('upload_logo'):
      $type = Params::getParam('logo_type');
      $file = Params::getFiles('logo');
      $save_name = 'logo';

      if($type == 'standard-dark') {
        $save_name = 'logo-dark';
        
      } else if($type == 'square') {
        $save_name = 'logo-square';
        
      } else if($type == 'square-dark') {
        $save_name = 'logo-square-dark';
      }
      
      
      if($file['error'] == UPLOAD_ERR_OK) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        
        if(!in_array($ext, $allowed_ext)) {
          osc_add_flash_error_message(sprintf(__('Image extension is not allowed. Allowed extensions are: %s', 'zeta'), implode(',', $allowed_ext)), 'admin');
        } else {
          if(move_uploaded_file($file['tmp_name'], WebThemes::newInstance()->getCurrentThemePath() . 'images/' . $save_name . '.' . $ext)) {

            // Upload was successful, remove other logo extensions
            foreach($allowed_ext as $e) {
              if($e != $ext) {
                if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/' . $save_name . '.' . $e)) { 
                  @unlink(WebThemes::newInstance()->getCurrentThemePath() . 'images/' . $save_name . '.' . $e);
                }
              }
            }
            
            osc_add_flash_ok_message(__('The logo image has been uploaded correctly', 'zeta'), 'admin');
          } else {
            osc_add_flash_error_message(__('An error has occurred, please try again', 'zeta'), 'admin');
          }
        }
      } else {
        osc_add_flash_error_message(__('An error has occurred, please try again', 'zeta'), 'admin');
      }
      
      header('Location: ' . osc_admin_render_theme_url('oc-content/themes/' . osc_current_web_theme() . '/admin/logo.php'));
      exit;
      break;


    case('remove'):
      foreach($allowed_ext as $e) {
        if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/logo.' . $e)) { 
          @unlink(WebThemes::newInstance()->getCurrentThemePath() . 'images/logo.' . $e);
        }
      }

      //@unlink(WebThemes::newInstance()->getCurrentThemePath() . 'images/' . zet_logo_is_uploaded());
      osc_add_flash_ok_message(__('The logo image has been removed', 'zeta'), 'admin');
      
      header('Location: ' . osc_admin_render_theme_url('oc-content/themes/' . osc_current_web_theme() . '/admin/logo.php')); 
      exit;
      break;

    case('remove_dark'):
      foreach($allowed_ext as $e) {
        if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/logo-dark.' . $e)) { 
          @unlink(WebThemes::newInstance()->getCurrentThemePath() . 'images/logo-dark.' . $e);
        }
      }

      //@unlink(WebThemes::newInstance()->getCurrentThemePath() . 'images/' . zet_logo_is_uploaded());
      osc_add_flash_ok_message(__('The logo image has been removed', 'zeta'), 'admin');
      
      header('Location: ' . osc_admin_render_theme_url('oc-content/themes/' . osc_current_web_theme() . '/admin/logo.php')); 
      exit;
      break;
      
    case('remove_square'):
      foreach($allowed_ext as $e) {
        if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/logo-square.' . $e)) { 
          @unlink(WebThemes::newInstance()->getCurrentThemePath() . 'images/logo-square.' . $e);
        }
      }

      //@unlink(WebThemes::newInstance()->getCurrentThemePath() . 'images/' . zet_logo_square_is_uploaded());
      osc_add_flash_ok_message(__('The logo image has been removed', 'zeta'), 'admin');
      
      header('Location: ' . osc_admin_render_theme_url('oc-content/themes/' . osc_current_web_theme() . '/admin/logo.php')); 
      exit;
      break;
      
    case('remove_square_dark'):
      foreach($allowed_ext as $e) {
        if(file_exists(WebThemes::newInstance()->getCurrentThemePath() . 'images/logo-square-dark.' . $e)) { 
          @unlink(WebThemes::newInstance()->getCurrentThemePath() . 'images/logo-square-dark.' . $e);
        }
      }

      //@unlink(WebThemes::newInstance()->getCurrentThemePath() . 'images/' . zet_logo_square_is_uploaded());
      osc_add_flash_ok_message(__('The logo image has been removed', 'zeta'), 'admin');
      
      header('Location: ' . osc_admin_render_theme_url('oc-content/themes/' . osc_current_web_theme() . '/admin/logo.php')); 
      exit;
      break;
  } 

  if(Params::getParam('theme_action') == 'done') {
    osc_add_flash_ok_message(__('Settings were successfully saved','zeta'), 'admin');
    header('Location:' . osc_admin_render_theme_url('oc-content/themes/zeta/admin/logo.php'));
    exit;
  }
?>

<div class="mb-body">

  <!-- LOGO PREVIEW -->
  <div class="mb-box">
    <div class="mb-head"><i class="fa fa-display"></i> <?php _e('Logo preview', 'zeta'); ?></div>

    <div class="mb-inside">
      <form action="<?php echo osc_admin_render_theme_url('oc-content/themes/zeta/admin/logo.php');?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="theme_action" value="done" />

        <div class="mb-row">
          <label for="default_logo" class=""><span><?php _e('Use Default Logo', 'zeta'); ?></span></label> 
          <input name="default_logo" id="default_logo" class="element-slide" type="checkbox" <?php echo (zet_param('default_logo') == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('If you did not upload any logo yet, osclass default logo will be used.', 'zeta'); ?></div>
        </div>
      
        <div class="mb-row">
          <label><?php _e('Current standard logo', 'zeta'); ?></label> 

          <div class="mb-image-preview">
            <?php if(zet_logo_is_uploaded(false)) { ?>
              <?php echo zet_logo(); ?>
            <?php } else { ?>
              <span><?php _e('No standard logo found, default logo will be used instead.', 'zeta'); ?></span>
            <?php } ?>
          </div>
        </div>
        
        <div class="mb-row">
          <label><?php _e('Current standard logo for dark mode', 'zeta'); ?></label> 

          <div class="mb-image-preview">
            <?php if(zet_logo_is_uploaded(false, 'logo-dark')) { ?>
              <?php echo zet_logo(false, false, true); ?>
            <?php } else { ?>
              <span><?php _e('No dark standard logo found, default logo will be used instead.', 'zeta'); ?></span>
            <?php } ?>
          </div>
        </div>
        
        <div class="mb-row">
          <label><?php _e('Current square logo', 'zeta'); ?></label> 

          <div class="mb-image-preview">
            <?php if(zet_logo_is_uploaded(false, 'logo-square')) { ?>
              <?php echo zet_logo(false, true); ?>
            <?php } else { ?>
              <span><?php _e('No square logo found, default logo will be used instead.', 'zeta'); ?></span>
            <?php } ?>
          </div>
        </div>
        
        <div class="mb-row">
          <label><?php _e('Current square logo for dark mode', 'zeta'); ?></label> 

          <div class="mb-image-preview">
            <?php if(zet_logo_is_uploaded(false, 'logo-square-dark')) { ?>
              <?php echo zet_logo(false, true, true); ?>
            <?php } else { ?>
              <span><?php _e('No dark square logo found, default logo will be used instead.', 'zeta'); ?></span>
            <?php } ?>
          </div>
        </div>
        
        
        <div class="mb-foot">
          <?php if(zet_logo_is_uploaded()) { ?>
            <a href="<?php echo osc_admin_render_theme_url('oc-content/themes/zeta/admin/logo.php?theme_action=remove');?>" class="mb-button remove"><?php _e('Remove standard logo', 'zeta');?></a>
          <?php } ?>

          <?php if(zet_logo_is_uploaded(true, 'logo-square')) { ?>
            <a href="<?php echo osc_admin_render_theme_url('oc-content/themes/zeta/admin/logo.php?theme_action=remove_square');?>" class="mb-button remove"><?php _e('Remove square logo', 'zeta');?></a>
          <?php } ?>
          
          <?php if(zet_logo_is_uploaded(true, 'logo-dark')) { ?>
            <a href="<?php echo osc_admin_render_theme_url('oc-content/themes/zeta/admin/logo.php?theme_action=remove_dark');?>" class="mb-button remove"><?php _e('Remove standard dark logo', 'zeta');?></a>
          <?php } ?>

          <?php if(zet_logo_is_uploaded(true, 'logo-square-dark')) { ?>
            <a href="<?php echo osc_admin_render_theme_url('oc-content/themes/zeta/admin/logo.php?theme_action=remove_square_dark');?>" class="mb-button remove"><?php _e('Remove square dark logo', 'zeta');?></a>
          <?php } ?>
          
          <button type="submit" class="mb-button"><?php _e('Save', 'zeta');?></button>
        </div>
      </form>

    </div>
  </div>


  <!-- LOGO UPLOAD -->
  <div class="mb-box">
    <div class="mb-head"><i class="fa fa-upload"></i> <?php _e('Logo upload', 'zeta'); ?></div>

    <form action="<?php echo osc_admin_render_theme_url('oc-content/themes/zeta/admin/logo.php'); ?>" method="post" enctype="multipart/form-data">
      <input type="hidden" name="theme_action" value="upload_logo" />

      <div class="mb-inside">
        <?php if(is_writable(WebThemes::newInstance()->getCurrentThemePath() . 'images/')) { ?>
          <div class="mb-points">
            <div class="mb-row">- <strong><?php _e('When new logo is uploaded, do not forget to clean your browser cache (CTRL + R or CTRL + F5)', 'zeta'); ?></strong></div>
            <div class="mb-row">- <?php _e('The preferred size of the logo is 240x64px.', 'zeta'); ?></div>
            <div class="mb-row">- <?php _e('The preferred size of the square logo is 64x64px.', 'zeta'); ?></div>
            <div class="mb-row">- <?php echo sprintf(__('Following formats are allowed: %s', 'zeta'), implode(', ', $allowed_ext)); ?></div>

            <?php if(zet_logo_is_uploaded() && 1==2) { ?>
              <div class="mb-row">- <?php _e('Uploading another logo will overwrite the current logo.', 'zeta'); ?></div>
            <?php } ?>
          </div>

          <div class="mb-row" style="margin:15px 0 0 0;">
            <select name="logo_type">
              <option value="standard"><?php _e('Standard logo', 'zeta'); ?></option>
              <option value="standard-dark"><?php _e('Standard logo - for dark mode', 'zeta'); ?></option>
              <option value="square"><?php _e('Square logo', 'zeta'); ?></option>
              <option value="square-dark"><?php _e('Square logo - for dark mode', 'zeta'); ?></option>
            </select>
          </div>
          
          <div class="mb-row" style="margin:10px 0 30px 0;">
            <input type="file" name="logo" id="package" />
          </div>
          
        <?php } else { ?>
          <div class="mb-warning">
            <div class="mb-row">
              <?php
                $msg  = sprintf(__('The images folder <strong>%s</strong> is not writable on your server', 'zeta'), WebThemes::newInstance()->getCurrentThemePath() ."images/") .", ";
                $msg .= __("OSClass can't upload the logo image from the administration panel.", 'zeta') . ' ';
                $msg .= __('Please make the aforementioned image folder writable.', 'zeta') . ' ';
                echo $msg;
              ?>
            </div>

            <div class="mb-row">
              <?php _e('To make a directory writable under UNIX execute this command from the shell:','zeta'); ?>
            </div>

            <div class="mb-row">
              chmod a+w <?php echo WebThemes::newInstance()->getCurrentThemePath() . 'images/'; ?>
            </div>
          </div>
        <?php } ?>
      </div>

      <div class="mb-foot">
        <button type="submit" class="mb-button"><?php _e('Upload', 'zeta');?></button>
      </div>
    </form>
  </div>
</div>


<?php echo zet_footer(); ?>