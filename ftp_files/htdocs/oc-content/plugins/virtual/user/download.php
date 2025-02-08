<?php
  $item_id = Params::getParam('itemId');
  $user_id = osc_logged_user_id();
  $file = vrt_item_file($item_id);
  $order = vrt_item_purchased($item_id, $user_id);

  $item = Item::newInstance()->findByPrimaryKey($item_id);
  View::newInstance()->_exportVariableToView('item', $item);

  $file_path = osc_content_path() . 'plugins/virtual/files/' . $file['s_file'];

  if($order !== false || osc_is_admin_user_logged_in() || osc_item_user_id() == osc_logged_user_id() || osc_item_price() <= 0) {
    if(file_exists($file_path) && $file['s_file'] <> '') {
      $db_prefix = DB_TABLE_PREFIX;
      vrt_increase_downloads($file['pk_i_id']);

      //classic way of redirect
      //header('Location:' . osc_base_url() . 'oc-content/plugins/virtual/files/' . $file['s_file']);
      //exit;

      header('Content-Description: File Transfer');
      header('Content-Type: application/zip');
      header('Content-Disposition: attachment; filename='.basename($file_path));
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($file_path));
      ob_clean();
      flush();
      readfile($file_path);
      exit;
    } else {
      osc_add_flash_error_message(__('File not found, please try again later', 'virtual'));
    }
  } else {
    osc_add_flash_error_message(__('You are not allowed to download this product, you must purchase it first.', 'virtual'));
  }
 
  header('Location:'.osc_item_url());
  exit;
?>