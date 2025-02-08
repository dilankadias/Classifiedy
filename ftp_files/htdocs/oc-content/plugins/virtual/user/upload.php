<?php
  if(isset($item['pk_i_id']) && $item['pk_i_id'] > 0) {
    $item_id = $item['pk_i_id'];  
  } else {
    $item_id = Params::getParam('itemId');
    $item = Item::newInstance()->findByPrimaryKey($item_id);
  }

  $user_id = osc_logged_user_id();
  $file = ModelVRT::newInstance()->getLastFileByItemId($item_id, -1);
  $files = ModelVRT::newInstance()->getFilesByItemId($item_id);

  if($item['fk_i_user_id'] <> osc_logged_user_id() && !osc_is_admin_user_logged_in()) {
    osc_add_flash_error_message(__('This is not your product, you cannot upload files there.', 'virtual') );
    header('Location:' . osc_base_url());
    exit;
  }


  //FILES UPLOAD
  vrt_upload($item , false);

?>


<form id="upload-file-form" name="upload-file-form" class="nocsrf" action="<?php echo osc_route_url( 'vrt-upload', array('itemId' => $item_id) ); ?>" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="vrtUpload" id="vrtUpload" value="done">

  <?php require_once 'upload-form.php'; ?>

  <button type="submit" class="btn btn-primary btn-upload-file"><?php _e('Submit new version', 'virtual'); ?></button>
</form>


<?php if(count($files) > 0) { ?>
  <div class="file-history">
    <h3><?php _e('Updates history', 'virtual'); ?></h3>
    <h4>
      <?php _e('You cannot remove file versions, to update file please upload new version.', 'virtual'); ?>

      <?php if(vrt_param('remove_older') == 1) { ?>
        <?php _e('Older file versions are automatically removed.', 'virtual'); ?>
      <?php } ?>
    </h4>

    <div class="row head-row">
      <div class="version"><?php _e('Version', 'virtual'); ?></div>
      <div class="comment"><?php _e('Comment', 'virtual'); ?></div>
      <div class="status"><?php _e('Status', 'virtual'); ?></div>
      <div class="date"><?php _e('Upload date', 'virtual'); ?></div>
      <div class="download">&nbsp;</div>
    </div>

    <?php foreach($files as $f) { ?>
      <div class="row">
        <div class="version">v<?php echo $f['i_version']; ?></div>

        <div class="comment"><?php echo ($f['s_comment'] <> '' ? $f['s_comment'] : '-'); ?></div>
        <div class="status">
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
        <div class="date"><?php echo date('j. M Y', strtotime($f['dt_date'])); ?></div>

        <div class="download">
          <?php if(file_exists(osc_content_path() . 'plugins/virtual/files/' . $f['s_file']) && $f['s_file'] <> '') { ?>
            <a href="<?php echo osc_route_url('vrt-download', array('itemId' => $item['pk_i_id'])); ?>"><?php _e('Download', 'virtual'); ?></a>
          <?php } else { ?>
            <a href="#" class="disabled osp-has-tooltip-right" title="File not available" onclick="return false;"><?php _e('Download', 'virtual'); ?></a>
          <?php } ?>
        </div>

      </div>
    <?php } ?>
  </div>
<?php } ?>