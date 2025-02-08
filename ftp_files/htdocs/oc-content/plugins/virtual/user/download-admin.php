<?php
  $file_id = Params::getParam('fileId');
  $file = ModelVRT::newInstance()->getFileById($file_id);

  $item_id = $file['fk_i_item_id'];
  $item = Item::newInstance()->findByPrimaryKey($item_id);
  View::newInstance()->_exportVariableToView('item', $item);

  $file_path = osc_content_path() . 'plugins/virtual/files/' . $file['s_file'];

  if(osc_is_admin_user_logged_in()) {
    if(file_exists($file_path) && $file['s_file'] <> '') {
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
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