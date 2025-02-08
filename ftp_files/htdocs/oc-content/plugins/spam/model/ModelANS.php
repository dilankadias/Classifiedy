<?php
class ModelANS extends DAO {
private static $instance ;

public static function newInstance() {
  if( !self::$instance instanceof self ) {
    self::$instance = new self ;
  }
  return self::$instance ;
}

function __construct() {
  parent::__construct();
}


// Get Tables
public function getTable_item() {
  return DB_TABLE_PREFIX.'t_item' ;
}

public function getTable_item_desc() {
  return DB_TABLE_PREFIX.'t_item_description' ;
}

public function getTable_ban() {
  return DB_TABLE_PREFIX.'t_ban_rule' ;
}

public function getTable_user() {
  return DB_TABLE_PREFIX.'t_user' ;
}


public function import($file) {
  $path = osc_plugin_resource($file);
  $sql = file_get_contents($path);

  if(!$this->dao->importSQL($sql) ){
    throw new Exception("Error importSQL::ModelANS<br>" . $file . "<br>" . $this->dao->getErrorLevel() . " - " . $this->dao->getErrorDesc() );
  }
}


public function install($version = '') {
  if($version == '') {
    //$this->import('spam/model/struct.sql');

    osc_set_preference('version', 100, 'plugin-spam', 'INTEGER');
  }
}


public function uninstall() {
  // DELETE ALL TABLES
  //$this->dao->query(sprintf('DROP TABLE %s', $this->getTable_attribute()));


  // DELETE ALL PREFERENCES
  $db_prefix = DB_TABLE_PREFIX;
  $query = "DELETE FROM {$db_prefix}t_preference WHERE s_section = 'plugin-spam'";
  $this->dao->query($query);
}


public function countItems() {
  $this->dao->select('COUNT(*) total_count');
  $this->dao->from( $this->getTable_item() );

  $result = $this->dao->get();

  if(!$result) { return array(); }

  return $result->row();
}

public function countUsersByIP( $ip ) {
  $this->dao->select('COUNT(*) user_count');
  $this->dao->from( $this->getTable_user() );
  $this->dao->where('s_access_ip', $ip);

  $result = $this->dao->get();
            
  if(!$result) { return array(); }
            
  return $result->row();
}
       
public function getAllItems( $start = NULL, $num_list = NULL, $locale = null ) {
  $this->dao->select('i.*, d.*, @curRow:=@curRow+1 AS rownum');
  $this->dao->from($this->getTable_item() . ' i, ' . $this->getTable_item_desc() . ' d, (SELECT @curRow := 0) r');
  $this->dao->where('i.pk_i_id = d.fk_i_item_id');
  $this->dao->orderby('i.s_contact_email ASC, i.pk_i_id ASC');
  $this->dao->limit(1000);

  if(!is_null($locale)) {
    $this->dao->where('d.fk_c_locale_code', $locale);
  }

  if($start == '') { $start = 1; }
  if($num_list == '') { $num_list = 50; }
  $end = $start + $num_list*2 - 1;

  $this->dao->having('rownum between ' . $start . ' and ' . $end);

  $result = $this->dao->get();
  if(!$result) { return array(); }

  $prepare = $result->result();

  return $prepare;
}

public function getItemsByEmail( $email, $locale = null, $item_id = null ) {
  $this->dao->select();
  $this->dao->from($this->getTable_item() . ' i, ' . $this->getTable_item_desc() . ' d');
  $this->dao->where('i.pk_i_id = d.fk_i_item_id');
  $this->dao->orderby('i.pk_i_id ASC');

  $this->dao->where('i.s_contact_email', $email);

  if($locale <> '') {
    $this->dao->where('d.fk_c_locale_code', $locale);
  }

  if($item_id <> 0 && $item_id <> '') {
    $this->dao->where('i.pk_i_id <> ' . $item_id);
  }

  $result = $this->dao->get();
  if(!$result) { return array(); }
  $prepare = $result->result();
  return $prepare;
}

public function insertEmailBan($reason = null, $email = '') {
  $data = array(
    's_name' => sprintf(__('Spam > Email (%s)', 'spam'), $reason),
    's_ip' => '',
    's_email' => $email
  );

  return $this->dao->insert($this->getTable_ban(), $data );
}

public function insertIpBan($reason = null, $ip = '') {
  $data = array(
    's_name' => sprintf(__('Spam > IP (%s)', 'spam'), $reason),
    's_ip' => $ip,
    's_email' => ''
  );

  return $this->dao->insert($this->getTable_ban(), $data );
}

public function insertBan($reason = null, $email = '', $ip = '') {
  $data = array(
    's_name' => $reason,
    's_ip' => $ip,
    's_email' => $email
  );

  return $this->dao->insert($this->getTable_ban(), $data );
}

public function updateItemBlock($email) {
  $data = array('b_enabled' => 0);
  $where = array('s_contact_email' => $email );

  return $this->dao->update($this->getTable_item(), $data, $where );
}

public function updateItemSpam($email) {
  $data = array('b_spam' => 1);
  $where = array('s_contact_email' => $email );

  return $this->dao->update($this->getTable_item(), $data, $where );
}

public function updateItemActivate($email) {
  $data = array('b_active' => 0);
  $where = array('s_contact_email' => $email );

  return $this->dao->update($this->getTable_item(), $data, $where );
}

public function updateItemBlockByID($id) {
  $data = array('b_enabled' => 0);
  $where = array('pk_i_id' => $id );

  return $this->dao->update($this->getTable_item(), $data, $where );
}

public function updateItemSpamByID($id) {
  $data = array('b_spam' => 1);
  $where = array('pk_i_id' => $id );

  return $this->dao->update($this->getTable_item(), $data, $where );
}

public function updateItemActivateByID($id) {
  $data = array('b_active' => 0);
  $where = array('pk_i_id' => $id );

  return $this->dao->update($this->getTable_item(), $data, $where );
}

public function updateItemDuplicate($item_id) {
  $data = array(
    'b_spam' => 1,
    'b_enabled' => 0
  );

  $where = array('pk_i_id' => $item_id );

  return $this->dao->update($this->getTable_item(), $data, $where );
}

public function updateUserIP($user_id, $user_ip){
  $data = array('s_access_ip' => $user_ip);
  $where = array('pk_i_id' => $user_id );

  return $this->dao->update($this->getTable_user(), $data, $where );
}

public function getItemsByBanword($word) {
  $this->dao->select('distinct i.*, d.*' );
  $this->dao->from($this->getTable_item() . ' i, ' . $this->getTable_item_desc() . ' d' );
  $this->dao->where('i.pk_i_id = d.fk_i_item_id' );
  //$this->dao->where('i.b_spam', 0 );
  //$this->dao->where('i.b_active', 1 );
  //$this->dao->where('i.b_enabled', 1 );
  $this->dao->where('(lower(d.s_title) like \'%' . $word . '%\' or lower(d.s_description) like \'%' . $word . '%\')' );

  $result = $this->dao->get( );
  if(!$result) { return array( ); }
  $prepare = $result->result( );
  return $prepare;
}

}
?>