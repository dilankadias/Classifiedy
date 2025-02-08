<?php

// NOTIFY ADMIN ON NEW FILE
function vrt_email_file_admin($item_id) {
  vrt_include_mailer();

  $mPages = new Page() ;
  $aPage = $mPages->findByInternalName('vrt_email_file_admin') ;
  $locale = osc_current_user_locale() ;
  $content = array();
  
  if(isset($aPage['locale'][$locale]['s_title'])) {
    $content = $aPage['locale'][$locale];
  } else {
    $content = current($aPage['locale'] <> '' ? $aPage['locale'] : array());
  }


  $file = ModelVRT::newInstance()->getLastFileByItemId($item_id, 9);


  $item = Item::newInstance()->findByPrimaryKey($item_id);
  $user = User::newInstance()->findByPrimaryKey($item['fk_i_user_id']);

  $link_validate = '<a href="' . osc_admin_base_url(true) . '?page=plugins&action=renderplugin&file=virtual/admin/configure.php&type=0">' . __('Validate', 'virtual') . '</a>';
  $link_download = '<a href="' . osc_base_url() . 'download-admin/' . $file['pk_i_id'] . '">' . __('Download', 'virtual') . '</a>';
  $link_item = '<a href="' . osc_item_url_ns($item_id) . '">' . $item['s_title'] . '</a>';
  $version = $file['i_version'];



  $words = array();
  $words[] = array('{ITEM_LINK}', '{VERSION}', '{VALIDATE}', '{DOWNLOAD}', '{WEB_URL}', '{WEB_TITLE}');
  $words[] = array($link_item, $version, $link_validate, $link_download, osc_base_url(), osc_page_title());


  $title = osc_mailBeauty($content['s_title'], $words) ;
  $body  = osc_mailBeauty($content['s_text'], $words) ;

  $emailParams = array(
    'subject' => $title,
    'to' => osc_contact_email(),
    'to_name' => __('Admin', 'virtual'),
    'body' => $body,
    'alt_body' => $body
  );

  osc_sendMail($emailParams);
}



// NOTIFY USER ABOUT VALIDATION
function vrt_email_file_validation($file_id, $action) {
  vrt_include_mailer();


  $mPages = new Page() ;
  $aPage = $mPages->findByInternalName('vrt_email_file_validation') ;
  $locale = osc_current_user_locale();
  $content = array();

  if(isset($aPage['locale'][$locale]['s_title'])) {
    $content = $aPage['locale'][$locale];
  } else {
    $content = current($aPage['locale'] <> '' ? $aPage['locale'] : array());
  }


  $file = ModelVRT::newInstance()->getFileById($file_id);
  $item_id = $file['fk_i_item_id'];

  $item = Item::newInstance()->findByPrimaryKey($item_id);

  if($item['fk_i_user_id'] > 0) {
    $user = User::newInstance()->findByPrimaryKey($item['fk_i_user_id']);
  } else {
    $user['s_name'] = $item['s_contact_name'];
    $user['s_email'] = $item['s_contact_email'];
  }

  $link_item = '<a href="' . osc_item_url_ns($item_id) . '">' . $item['s_title'] . '</a>';
  $version = $file['i_version'];

  if($action == 'approved') {
    $status = __('APPROVED', 'virtual');
  } else {
    $status = __('REJECTED', 'virtual');
  }

  $words = array();
  $words[] = array('{ITEM_LINK}', '{VERSION}', '{STATUS}', '{CONTACT_NAME}', '{WEB_URL}', '{WEB_TITLE}');
  $words[] = array($link_item, $version, $status, $user['s_name'], osc_base_url(), osc_page_title());


  $title = osc_mailBeauty($content['s_title'], $words) ;
  $body  = osc_mailBeauty($content['s_text'], $words) ;

  $emailParams = array(
    'subject' => $title,
    'to' => $user['s_email'],
    'to_name' => $user['s_name'],
    'body' => $body,
    'alt_body' => $body
  );

  osc_sendMail($emailParams);
}

?>