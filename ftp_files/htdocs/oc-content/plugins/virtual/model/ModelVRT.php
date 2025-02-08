<?php
class ModelVRT extends DAO {
private static $instance;

public static function newInstance() {
  if( !self::$instance instanceof self ) {
    self::$instance = new self;
  }
  return self::$instance;
}

function __construct() {
  parent::__construct();
}


public function getTable_virtual() {
  return DB_TABLE_PREFIX.'t_item_virtual';
}

public function getTable_item() {
  return DB_TABLE_PREFIX.'t_item';
}

public function getTable_order() {
  return DB_TABLE_PREFIX.'t_osp_order';
}



public function import($file) {
  $path = osc_plugin_resource($file);
  $sql = file_get_contents($path);

  if(!$this->dao->importSQL($sql) ){
    throw new Exception("Error importSQL::ModelVRT<br>" . $file . "<br>" . $this->dao->getErrorLevel() . " - " . $this->dao->getErrorDesc() );
  }
}


public function install($version = '') {
  $this->import('virtual/model/struct.sql');

  $locales = OSCLocale::newInstance()->listAllEnabled();

  

  // NEW FILE - NOTIFY ADMIN
  foreach($locales as $l) {

    $email_text  = '<p>Hi Admin!</p>';
    $email_text .= '<p>Let us inform you, there has been new file uploaded.</p>';
    $email_text .= '<p>File v{VERSION} on item {ITEM_LINK}</p>';
    $email_text .= '<p>Each file must be validated by admin.</p>';

    $email_text .= '<p>{VALIDATE} {DOWNLOAD}</p>';

    $email_text .= '<p><br/></p>';
    $email_text .= '<p>This is an automatic email, if you already did that, please ignore this email.</p>';
    $email_text .= '<p>Thank you, <br />{WEB_TITLE}</p>';


    $vrt_file_admin = array();
    $vrt_file_admin[$l['pk_c_code']]['s_title'] = '{WEB_TITLE} - New file pending validation';
    $vrt_file_admin[$l['pk_c_code']]['s_text'] = $email_text;
  }


  // NEW FILE - NOTIFY USER ABOUT VALIDATION
  foreach($locales as $l) {

    $email_text  = '<p>Hi {CONTACT_NAME}!</p>';
    $email_text .= '<p>Let us inform you, that your file v{VERSION} on item {ITEM_LINK} has been validated by our team and it has been <strong><u>{STATUS}</u></strong>.</p>';

    $email_text .= '<p>In case you have any questions, feel free to contact us.</p>';

    $email_text .= '<p><br/></p>';
    $email_text .= '<p>This is an automatic email, if you already did that, please ignore this email.</p>';
    $email_text .= '<p>Thank you, <br />{WEB_TITLE}</p>';


    $vrt_file_validation = array();
    $vrt_file_validation[$l['pk_c_code']]['s_title'] = '{WEB_TITLE} - File has been validated';
    $vrt_file_validation[$l['pk_c_code']]['s_text'] = $email_text;
  }


  Page::newInstance()->insert( array('s_internal_name' => 'vrt_email_file_admin', 'b_indelible' => '1'), $vrt_file_admin);
  Page::newInstance()->insert( array('s_internal_name' => 'vrt_email_file_validation', 'b_indelible' => '1'), $vrt_file_validation);

}


public function uninstall() {
  // DELETE ALL TABLES
  $this->dao->query(sprintf('DROP TABLE %s', $this->getTable_virtual()));

  // DELETE ALL PREFERENCES
  $db_prefix = DB_TABLE_PREFIX;
  $query = "DELETE FROM {$db_prefix}t_preference WHERE s_section = 'plugin-virtual'";
  $this->dao->query($query);

  $page_admin = Page::newInstance()->findByInternalName('vrt_email_file_admin');
  $page_validation = Page::newInstance()->findByInternalName('vrt_email_file_validation');

  Page::newInstance()->deleteByPrimaryKey($page_admin['pk_i_id']);
  Page::newInstance()->deleteByPrimaryKey($page_validation['pk_i_id']);
}


// GET FILE BY ID
public function getFileById($id) {
  $this->dao->select('*');
  $this->dao->from($this->getTable_virtual());
  $this->dao->where('pk_i_id', $id);

  $result = $this->dao->get();
  
  if($result) {
    return $result->row();
  }
  
  return false;
}



// GET FILES BY ITEM ID
public function getFilesByItemId($item_id, $status = -1) {
  $this->dao->select('*');
  $this->dao->from($this->getTable_virtual());

  $this->dao->where('fk_i_item_id', $item_id);

  if($status <> -1) {
    $this->dao->where('i_status', $status);
  }

  $this->dao->orderby('pk_i_id DESC');

  $result = $this->dao->get();
  
  if($result) {
    return $result->result();
  }
  
  return false;
}


// GET LAST FILE BY ITEM ID
public function getLastFileByItemId($item_id, $status = 1) {
  $this->dao->select('*');
  $this->dao->from($this->getTable_virtual());

  $this->dao->where('fk_i_item_id', $item_id);

  if($status <> -1) {
    $this->dao->where('i_status', $status);
  }

  $this->dao->orderby('pk_i_id DESC');
  $this->dao->limit(1);

  $result = $this->dao->get();
  
  if($result) {
    return $result->row();
  }
  
  return false;
}


// UPDATE FILE STATUS
public function updateFileStatusById($id, $status, $comment = '') {
  $value = array('i_status' => $status, 's_comment' => $comment);
  $this->dao->update($this->getTable_virtual(), $value, array('pk_i_id' => $id));
}


// GET ALL FILES
public function getFiles($status = -1) {
  $this->dao->select('*');
  $this->dao->from($this->getTable_virtual());

  if($status <> -1) {
    $this->dao->where('i_status', $status);
  }

  $this->dao->orderby('fk_i_item_id DESC, pk_i_id DESC');

  $result = $this->dao->get();
  
  if($result) {
    return $result->result();
  }
  
  return false;
}


// DELETE FILES BY ITEM ID
public function deleteFilesByItemId($item_id) {
  return $this->dao->delete($this->getTable_virtual(), array('fk_i_item_id' => $item_id));
}


// DELETE FILES BY ID
public function deleteFilesById($id) {
  return $this->dao->delete($this->getTable_virtual(), array('pk_i_id' => $id));
}


// INSERT FILE
public function insertFile($values) {
  $this->dao->insert($this->getTable_virtual(), $values);
  return $this->dao->insertedId();
}



// GET USER AVAILABLE DOWNLOADS
public function getDownloadsByUserId($user_id = '') {
  if($user_id == '') {
    $user_id = osc_logged_user_id();
  }

  $this->dao->select('s_item_id');
  $this->dao->from($this->getTable_order());
  $this->dao->where('i_status = 2');
  $this->dao->where('fk_i_user_id', $user_id);

  $result = $this->dao->get();
  $files = array();

  if($result) {
    $orders = $result->result();
    $item_ids = array();

    if(count($orders) > 0) {
      foreach($orders as $o) {
        $data = array_filter(explode(',', $o['s_item_id']));
 
        if(count($data) > 0) {
          foreach($data as $d) {
            if(!in_array($d, $item_ids)) {
              $item_ids[] = $d;
            }
          }
        }
      }

      if(count($item_ids) > 0) {
        foreach($item_ids as $i) {
          $f = $this->getLastFileByItemId($i);

          if($f) {
            $files[] = $f;
          } else {
            $files[] = array('fk_i_item_id' => $i);
          }
        }
      }
    }
  }
  
  return $files;
}



// GET DOWNLOADS FOR FREE PRODUCTS
public function getFreeDownloads() {
  $this->dao->select('pk_i_id');
  $this->dao->from($this->getTable_item());
  $this->dao->where('i_price <= 0');

  $result = $this->dao->get();
  $files = array();

  if($result) {
    $item_ids = $result->result();
    $item_ids = array_column($item_ids, 'pk_i_id');
    
    if(count($item_ids) > 0) {
      foreach($item_ids as $i) {
        $f = $this->getLastFileByItemId($i);

        if($f) {
          $files[] = $f;
        } else {
          $files[] = array('fk_i_item_id' => $i);
        }
      }
    }
  }
  
  return $files;
}


// INCREASE DOWNLOAD STATS
public function increaseDownloads($file_id) {
  $sql = sprintf('UPDATE %s SET i_download = i_download + 1 WHERE pk_i_id = %d', $this->getTable_virtual(), $file_id);
  $this->dao->query($sql);
}


// COUNT HOW MANY FILES PENDING VALIDATION
public function countInactiveFiles() {
  $this->dao->select('count(*) as i_count');
  $this->dao->from($this->getTable_virtual());
  $this->dao->where('i_status', 0);
  $result = $this->dao->get();
  
  if($result) {
    return $result->row()['i_count'];
  }
  
  return 0;
}

}
?>