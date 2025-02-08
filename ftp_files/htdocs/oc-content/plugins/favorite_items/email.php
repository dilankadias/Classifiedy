<?php
// ADD VARIABLES TO EMAIL LEGEND
EmailVariables::newInstance()->add('{OLD_PRICE}', 'Old price of item before it was edited.');
EmailVariables::newInstance()->add('{NEW_PRICE}', 'New price of item after edit was complete.');


// CREATE EMAIL WHEN PRICE ON LISTING HAS CHANGED
function fi_email_price_change($user_email, $user_name, $item_id, $old_price, $new_price) {
  $page = new Page() ;
  $page = $page->findByInternalName('fi_email_price_change');
  if(empty($page)) { exit(); }

  $locale = osc_current_user_locale() ;
  $content = array();

  if(isset($page['locale'][$locale]['s_title'])) {
    $content = $page['locale'][$locale];
  } else {
    $content = current($page['locale']);
  }

  $item = Item::newInstance()->findByPrimaryKey($item_id);

  $url  = '<a href="' . osc_item_url_ns($item_id) . '" >' . $item['s_title'] . '</a>';

  $words   = array();
  $words[] = array('{CONTACT_NAME}', '{ITEM_TITLE}', '{WEB_TITLE}', '{ITEM_URL}', '{ITEM_ID}', '{OLD_PRICE}', '{NEW_PRICE}');
  $words[] = array($user_name, stripslashes(strip_tags($item['s_title'])), stripslashes(strip_tags(osc_page_title())), $url, $item_id, $old_price, $new_price) ;

  $title = osc_mailBeauty($content['s_title'], $words) ;
  $body  = osc_mailBeauty($content['s_text'], $words) ;

  $email_build = array(
    'subject'  => $title, 
    'to' => $user_email, 
    'to_name'  => $user_name,
    'body' => $body,
    'alt_body' => $body
  );

  osc_sendMail($email_build);
}


// CREATE EMAIL WHEN LISTING IS REMOVED
function fi_email_item_remove($user_email, $user_name, $item_id, $price) {
  $page = new Page() ;
  $page = $page->findByInternalName('fi_email_item_remove');
  if(empty($page)) { exit(); }

  $locale = osc_current_user_locale() ;
  $content = array();

  if(isset($page['locale'][$locale]['s_title'])) {
    $content = $page['locale'][$locale];
  } else {
    $content = current($page['locale']);
  }

  $item = Item::newInstance()->findByPrimaryKey($item_id);


  $words   = array();
  $words[] = array('{CONTACT_NAME}', '{ITEM_TITLE}', '{WEB_TITLE}', '{ITEM_ID}', '{PRICE}');
  $words[] = array($user_name, stripslashes(strip_tags($item['s_title'])), stripslashes(strip_tags(osc_page_title())), $item_id, $price) ;

  $title = osc_mailBeauty($content['s_title'], $words) ;
  $body  = osc_mailBeauty($content['s_text'], $words) ;

  $email_build = array(
    'subject'  => $title, 
    'to' => $user_email, 
    'to_name'  => $user_name,
    'body' => $body,
    'alt_body' => $body
  );

  osc_sendMail($email_build);
}

?>