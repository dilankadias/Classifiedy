<?php
  // Create menu
  $title = __('Configure', 'virtual');
  vrt_menu($title);


  // GET & UPDATE PARAMETERS
  // $variable = mb_param_update( 'param_name', 'form_name', 'input_type', 'plugin_var_name' );
  // input_type: check or value

  $require_validation = mb_param_update( 'require_validation', 'plugin_action', 'check', 'plugin-virtual' );
  $remove_older = mb_param_update( 'remove_older', 'plugin_action', 'check', 'plugin-virtual' );
  $file_required = mb_param_update( 'file_required', 'plugin_action', 'check', 'plugin-virtual' );
  $download_hook = mb_param_update( 'download_hook', 'plugin_action', 'check', 'plugin-virtual' );
  $osclasspay_link = mb_param_update( 'osclasspay_link', 'plugin_action', 'check', 'plugin-virtual' );
  $style_button = mb_param_update( 'style_button', 'plugin_action', 'check', 'plugin-virtual' );

  $allowed_extensions = mb_param_update( 'allowed_extensions', 'plugin_action', 'value', 'plugin-virtual' );
  $max_file_size = mb_param_update( 'max_file_size', 'plugin_action', 'value', 'plugin-virtual' );

  $category = mb_param_update( 'category', 'plugin_action', 'value', 'plugin-virtual' );

  $category_all = Category::newInstance()->listAll();
  $category_array = explode(',', $category);

  if(Params::getParam('plugin_action') == 'done') {
    message_ok( __('Settings were successfully saved', 'virtual') );
  }


  $type = Params::getParam('type') <> '' ? Params::getParam('type') : -1;


  if(Params::getParam('what') == 'approve') {
    $id = Params::getParam('id');
    $review = Params::getParam('review');
    $file = ModelVRT::newInstance()->getFileById($id);

    ModelVRT::newInstance()->updateFileStatusById($id, 1, $review);

    vrt_email_file_validation($file['pk_i_id'], 'approved');


    // remove old versions
    $files = ModelVRT::newInstance()->getFilesByItemId($file['fk_i_item_id']);

    if(count($files) > 0 && $remove_older == 1) {
      foreach($files as $f) {
        if($f['pk_i_id'] < $id) {
          ModelVRT::newInstance()->updateFileStatusById($f['pk_i_id'], 2, $f['s_comment']);

          if(file_exists(osc_content_path() . 'plugins/virtual/files/' . $f['s_file'])) {
            unlink(osc_content_path() . 'plugins/virtual/files/' . $f['s_file']);
          }
        }
      }
    }
  }



  if(Params::getParam('what') == 'reject') {
    $id = Params::getParam('id');
    $review = Params::getParam('review');

    ModelVRT::newInstance()->updateFileStatusById($id, 9, $review);
    $file = ModelVRT::newInstance()->getFileById($id);
    $file_path = osc_content_path() . 'plugins/virtual/files/' . $file['s_file'];

    vrt_email_file_validation($file['pk_i_id'], 'rejected');

    if(file_exists($file_path)) {
      unlink($file_path);
    }
  }



?>



<div class="mb-body">


  <!-- FILES VALIDATION -->
  <div class="mb-box mb-file">
    <div class="mb-head"><i class="fa fa-archive"></i> <?php _e('Files validation', 'virtual'); ?></div>

    <div class="mb-inside">
      <div class="mb-row mb-notes">
        <div class="mb-line"><?php _e('Bellow are shown all files that are pending validation.', 'virtual'); ?></div>
      </div>

      <div class="mb-row">
        <a class="mb-file-accept mb-button-white" href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=virtual/admin/configure.php&type=0">Show only files pending validation</a>
        <a class="mb-file-accept mb-button-white" href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=virtual/admin/configure.php">Show all files</a>
      </div>

      <div class="mb-row">&nbsp;</div>

      <div class="mb-table mb-table-transfer">
        <div class="mb-table-head">
          <div class="mb-col-1"><?php _e('ID', 'virtual');?></div>
          <div class="mb-col-3 mb-align-left"><?php _e('Item', 'virtual');?></div>
          <div class="mb-col-2 mb-align-left"><?php _e('Author', 'virtual');?></div>
          <div class="mb-col-1"><?php _e('File ID', 'virtual'); ?></div>
          <div class="mb-col-3 mb-align-left"><?php _e('File', 'virtual'); ?></div>
          <div class="mb-col-2"><?php _e('Version', 'virtual'); ?></div>
          <div class="mb-col-3 mb-align-left"><?php _e('Upload Date', 'virtual'); ?></div>
          <div class="mb-col-2 mb-align-left"><?php _e('Status', 'virtual'); ?></div>
          <div class="mb-col-3 mb-align-left"><?php _e('Comment (from author)', 'virtual'); ?></div>
          <div class="mb-col-4 mb-align-left">&nbsp;</div>
        </div>

        <?php $files = ModelVRT::newInstance()->getFiles($type); ?>

        <?php if(count($files) <= 0) { ?>
          <div class="mb-table-row mb-row-empty">
            <i class="fa fa-warning"></i><span><?php _e('No files has been found', 'virtual'); ?></span>
          </div>
        <?php } else { ?>
          <?php foreach($files as $f) { ?>
            <?php 
              $item = Item::newInstance()->findByPrimaryKey($f['fk_i_item_id']); 
              $seller = User::newInstance()->findByPrimaryKey($item['fk_i_user_id']); 
            ?>

            <div class="mb-table-row">
              <div class="mb-col-1"><?php echo $item['pk_i_id']; ?></div>
              <div class="mb-col-3 mb-align-left"><a target="_blank" href="<?php echo osc_item_url_ns($item['pk_i_id']); ?>"><?php echo $item['s_title']; ?></a></div>
              <div class="mb-col-2 mb-align-left"><?php echo (@$seller['s_name'] <> '' ? @$seller['s_name'] : __('Unregistered', 'virtual')); ?></div>
              <div class="mb-col-1"><?php echo $f['pk_i_id']; ?></div>
              <div class="mb-col-3 mb-align-left mb-nw">
                <?php if(file_exists(osc_content_path() . 'plugins/virtual/files/' . $f['s_file'])) { ?>
                  <a href="<?php echo osc_route_url('vrt-download-admin', array('fileId' => $f['pk_i_id'])); ?>"><?php echo $f['s_file']; ?></a>
                <?php } else { ?>
                  <span class="mb-ol mb-strike" title="<?php echo osc_esc_html(__('File removed', 'virtual')); ?>"><?php echo $f['s_file']; ?></span>
                <?php } ?>
              </div>
              <div class="mb-col-2"><?php echo $f['i_version']; ?></div>
              <div class="mb-col-3 mb-align-left"><?php echo $f['dt_date']; ?></div>
              <div class="mb-col-2 mb-align-left mb-file-status">
                <span class="st<?php echo $f['i_status']; ?>">
                  <?php
                    if($f['i_status'] == 0) {
                      echo '<i class="fa fa-hourglass-half"></i> ' . __('Pending', 'virtual');
                    } else if($f['i_status'] == 1) {
                      echo '<i class="fa fa-check"></i> ' . __('Approved', 'virtual');
                    } else if($f['i_status'] == 2) {
                      echo '<i class="fa fa-calendar"></i> ' . __('Outdated', 'virtual');
                    } else {
                      echo '<i class="fa fa-times"></i> ' . __('Rejected', 'virtual');
                    }
                  ?>
                </span>
              </div>

              <div class="mb-col-3 mb-align-left"><input type="text" name="review" id="review" value="<?php echo osc_esc_js($f['s_comment']); ?>"/></div>

              <div class="mb-col-4 mb-align-left mb-file-buttons">
                <?php if($f['i_status'] == 1 || $f['i_status'] == 0) { ?>
                  <a class="mb-file-accept mb-button-red" href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=virtual/admin/configure.php&type=<?php echo $type; ?>&what=reject&id=<?php echo $f['pk_i_id']; ?>"><i class="fa fa-times"></i> Reject</a>
                <?php } ?>

                <?php if($f['i_status'] == 0) { ?>
                  <a class="mb-file-accept mb-button-green" href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=virtual/admin/configure.php&type=<?php echo $type; ?>&what=approve&id=<?php echo $f['pk_i_id']; ?>"><i class="fa fa-check"></i> Approve</a>
                <?php } ?>
              </div>

            </div>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
  </div>




  <!-- CONFIGURATION SECTION -->
  <div class="mb-box">
    <div class="mb-head">
      <i class="fa fa-wrench"></i> <?php _e('Configuration', 'virtual'); ?>
    </div>

    <div class="mb-inside">
      <form name="promo_form" id="promo_form" action="<?php echo osc_admin_base_url(true); ?>" method="POST" enctype="multipart/form-data" >
        <input type="hidden" name="page" value="plugins" />
        <input type="hidden" name="action" value="renderplugin" />
        <input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>configure.php" />
        <input type="hidden" name="plugin_action" value="done" />


        <div class="mb-row">
          <label for="require_validation" class="h1"><span><?php _e('Require Validation', 'virtual'); ?></span></label> 
          <input name="require_validation" id="require_validation" class="element-slide" type="checkbox" <?php echo ($require_validation == 1 ? 'checked' : ''); ?> />
          
          <div class="mb-explain"><?php _e('Admin must validate each newly added file.', 'virtual'); ?></div>
        </div>

        <div class="mb-row">
          <label for="remove_older" class="h2"><span><?php _e('Remove Older Versions', 'virtual'); ?></span></label> 
          <input name="remove_older" id="remove_older" class="element-slide" type="checkbox" <?php echo ($remove_older == 1 ? 'checked' : ''); ?> />
          
          <div class="mb-explain"><?php _e('Enable to automatically remove older files, when new version is uploaded.', 'virtual'); ?></div>
        </div>

        <div class="mb-row">
          <label for="download_hook" class="h6"><span><?php _e('Hook Download Link', 'virtual'); ?></span></label> 
          <input name="download_hook" id="download_hook" class="element-slide" type="checkbox" <?php echo ($download_hook == 1 ? 'checked' : ''); ?> />
          
          <div class="mb-explain"><?php _e('Enable to automatically hook download link into item page using hook item_detail.', 'virtual'); ?></div>
        </div>

        <div class="mb-row">
          <label for="style_button" class="h6"><span><?php _e('Style Download link as Button', 'virtual'); ?></span></label> 
          <input name="style_button" id="style_button" class="element-slide" type="checkbox" <?php echo ($style_button == 1 ? 'checked' : ''); ?> />
          
          <div class="mb-explain"><?php _e('When enabled, download link is styled as button.', 'virtual'); ?></div>
        </div>

        <div class="mb-row">
          <label for="osclasspay_link" class="h7"><span><?php _e('Connect to Osclass Pay', 'virtual'); ?></span></label> 
          <input name="osclasspay_link" id="osclasspay_link" class="element-slide" type="checkbox" <?php echo ($osclasspay_link == 1 ? 'checked' : ''); ?> />
          
          <div class="mb-explain"><?php echo sprintf(__('When enabled, user can download product just in case it has been purchased with %s or it is free/check with seller.', 'virtual'), '<a href="https://osclasspoint.com/osclass-plugins/payments-and-shopping/osclass-pay-payment-plugin_i46">Osclass Pay Plugin</a>'); ?></div>
        </div>

        <div class="mb-row">
          <label for="file_required" class="h3"><span><?php _e('File Required', 'virtual'); ?></span></label> 
          <input name="file_required" id="file_required" class="element-slide" type="checkbox" <?php echo ($file_required == 1 ? 'checked' : ''); ?> />
          
          <div class="mb-explain"><?php _e('User must upload file when publishing new listing.', 'virtual'); ?></div>
        </div>


        <div class="mb-row">
          <label for="allowed_extensions" class="h4"><span><?php _e('Allowed Extensions', 'virtual'); ?></span></label> 
          <input name="allowed_extensions" id="allowed_extensions" type="text" value="<?php echo $allowed_extensions; ?>" />

          <div class="mb-explain"><?php _e('What are extensions user can upload. Example: zip,rar,doc', 'virtual'); ?></div>
        </div>


        <div class="mb-row">
          <label for="max_file_size" class="h5"><span><?php _e('Max. File Size', 'virtual'); ?></span></label> 
          <input name="max_file_size" id="max_file_size" type="number" value="<?php echo $max_file_size; ?>" />

          <div class="mb-explain"><?php _e('Maximum size of uploaded file in megabytes. Example: 20', 'virtual'); ?></div>
        </div>


        <div class="mb-line mb-row-select-multiple">
          <label for="category_multiple" class="h6"><span class=""><?php _e('Category', 'virtual'); ?></span></label> 

          <input type="hidden" name="category" id="category" value="<?php echo $category; ?>"/>
          <select id="category_multiple" name="category_multiple" multiple>
            <?php echo vrt_cat_list($category_array, $category_all); ?>
          </select>

          <div class="mb-explain"><?php _e('If not category selected, advert is shown in all categories.', 'virtual'); ?></div>
        </div>

        <div class="mb-row">&nbsp;</div>

        <div class="mb-foot">
          <?php if(vrt_is_demo()) { ?>
            <a class="mb-button mb-has-tooltip disabled" onclick="return false;" style="cursor:not-allowed;opacity:0.5;" title="<?php echo osc_esc_html(__('This is demo site', 'virtual')); ?>"><?php _e('Save', 'virtual');?></a>
          <?php } else { ?>
            <button type="submit" class="mb-button"><?php _e('Save', 'virtual');?></button>
          <?php } ?>
        </div>

      </form>
    </div>
  </div>




  <!-- PLUGIN INTEGRATION -->
  <div class="mb-box">
    <div class="mb-head"><i class="fa fa-wrench"></i> <?php _e('Plugin Setup', 'virtual'); ?></div>

    <div class="mb-inside">

      <div class="mb-row"><?php _e('To show file upload link on item (item.php, user-items.php), use following code:', 'virtual'); ?></div>
      <div class="mb-row">
        <span class="mb-code">&lt;?php if(function_exists('vrt_upload_file')) { echo vrt_upload_file(); } ?&gt;</span>
      </div>

      <div class="mb-row">&nbsp;</div>

      <div class="mb-row"><?php _e('To show file download link on item (item.php, user-items.php), use following code:', 'virtual'); ?></div>
      <div class="mb-row">
        <span class="mb-code">&lt;?php if(function_exists('vrt_download')) { echo vrt_download(); } ?&gt;</span>
      </div>

    </div>
  </div>

</div>


<script>
  $(document).ready(function(){
    $('body').on('click', 'a.mb-button-green, a.mb-button-red', function(e){
      e.preventDefault();
      var review = $(this).closest('.mb-table-row').find('input[name="review"]').val();
      location.href = $(this).attr('href') + "&review=" + review;
    });
  });
</script>


<?php echo vrt_footer(); ?>