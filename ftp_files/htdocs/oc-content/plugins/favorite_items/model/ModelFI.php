<?php
class ModelFI extends DAO {
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

public function getTable_User() {
  return DB_TABLE_PREFIX.'t_user';
}

public function getTable_Item() {
  return DB_TABLE_PREFIX.'t_item';
}

public function getTable_FavoriteList() {
  return DB_TABLE_PREFIX.'t_favorite_list';
}

public function getTable_FavoriteItems() {
  return DB_TABLE_PREFIX.'t_favorite_items';
}

public function getTable_Page() {
  return DB_TABLE_PREFIX.'t_pages';
}

public function import($file) {
  $path = osc_plugin_resource($file);
  $sql = file_get_contents($path);

  if(!$this->dao->importSQL($sql) ){
    throw new Exception("Error importSQL::ModelOSM<br>" . $file . "<br>" . $this->dao->getErrorLevel() . " - " . $this->dao->getErrorDesc() );
  }
}

public function uninstall() {
  $this->dao->query('DROP TABLE '.$this->getTable_FavoriteList());
  $this->dao->query('DROP TABLE '.$this->getTable_FavoriteItems());

  // DELETE ALL PREFERENCES
  $db_prefix = DB_TABLE_PREFIX;
  $query = "DELETE FROM {$db_prefix}t_preference WHERE s_section = 'plugin-fi'";
  $this->dao->query($query);
}


public function getFavoriteListById( $id ) {
  $this->dao->select();
  $this->dao->from( $this->getTable_FavoriteList() );
  $this->dao->where('list_id', $id );

  $result = $this->dao->get();
  if( !$result ) { return false; }
  return $result->row();
}
  
public function getCurrentFavoriteListByUserId( $user_id ) {
  $this->dao->select();
  $this->dao->from( $this->getTable_FavoriteList() );
  $this->dao->where('user_id', $user_id );
  $this->dao->where('current', 1 );

  $result = $this->dao->get();
  if( !$result ) { return false; }
  return $result->row();
}


public function getLastFavoriteListByUserId( $user_id ) {
  $this->dao->select();
  $this->dao->from( $this->getTable_FavoriteList() );
  $this->dao->where('user_id', $user_id );
  $this->dao->orderBy('list_id DESC');

  $result = $this->dao->get();
  if( !$result ) { return false; }
  return $result->row();
}


public function getAllFavoriteListsByUserId( $user_id ) {
  $this->dao->select();
  $this->dao->from( $this->getTable_FavoriteList() );
  $this->dao->where('user_id', $user_id );
  $result = $this->dao->get();

  if( !$result ) { return array(); }
  $prepare = $result->result();
  return $prepare;
}


public function getFavoriteItems( $list_id, $item_id ) {
  $this->dao->select();
  $this->dao->from( $this->getTable_FavoriteItems());
  $this->dao->where('list_id', $list_id);
  $this->dao->where('item_id', $item_id );

  $result = $this->dao->get();
  if( !$result ) { return false; }
  return $result->row();
}


public function getFavoriteItemsByListId( $list_id ) {
  $this->dao->select();
  $this->dao->from( $this->getTable_FavoriteItems());
  $this->dao->where('list_id', $list_id);
  $result = $this->dao->get();

  if( !$result ) { return array(); }
  $prepare = $result->result();
  return $prepare;
}


public function getActiveFavoriteItemsByListId( $list_id ) {
  $this->dao->select();
  $this->dao->from( $this->getTable_FavoriteItems() .' f, ' . $this->getTable_Item() . ' i');
  $this->dao->where('f.list_id', $list_id);
  $this->dao->where('f.item_id = i.pk_i_id');
  $this->dao->where('i.b_spam = 0');
  $this->dao->where('i.b_active = 1');
  $this->dao->where('i.b_enabled = 1');
  $this->dao->where('i.dt_expiration >= current_date');

  $result = $this->dao->get();

  if( !$result ) { return array(); }
  $prepare = $result->result();
  return $prepare;
}


public function getFavoriteAll( $item_id, $user_id ) {
  $this->dao->select('a.*, b.*');
  $this->dao->from( $this->getTable_FavoriteList() . ' a, ' . $this->getTable_FavoriteItems() . ' b');
  $this->dao->where('a.list_id = b.list_id');
  $this->dao->where('a.current', 1 );
  $this->dao->where('a.user_id', $user_id );
  $this->dao->where('b.item_id', $item_id );

  $result = $this->dao->get();
  if( !$result ) { return false; }
  return $result->row();
}


public function getUserFavoriteItems($user_id) {
  $this->dao->select('a.*, b.*');
  $this->dao->from( $this->getTable_FavoriteList() . ' a, ' . $this->getTable_FavoriteItems() . ' b');
  $this->dao->where('a.list_id = b.list_id');
  $this->dao->where('a.current', 1 );
  $this->dao->where('a.user_id', $user_id );

  $result = $this->dao->get();
  if( !$result ) { return array(); }
  
  $data = $result->result();
  $output = array();

  if(is_array($data) && count($data) > 0) {
    foreach($data as $d) {
      $output[$d['item_id']] = $d;
    }
  }

  return $output;
}


public function getUserListByItemId( $item_id ) {
  $this->dao->select('distinct u.pk_i_id as user_id, u.s_name as user_name, u.s_email as user_email');
  $this->dao->from( $this->getTable_FavoriteList() . ' a, ' . $this->getTable_FavoriteItems() . ' b, ' . $this->getTable_User() . ' u');
  $this->dao->where('a.list_id = b.list_id');
  $this->dao->where('a.user_logged', 1 );
  $this->dao->where('a.notification', 1 );
  $this->dao->where('a.user_id = u.pk_i_id');
  $this->dao->where('b.item_id', $item_id );
  $result = $this->dao->get();

  if( !$result ) { return array(); }
  $prepare = $result->result();
  return $prepare;

}
public function deleteFavoriteListById( $id ) {
  return $this->dao->delete($this->getTable_FavoriteList(), array('list_id' => $id) ) ;
}

public function deleteFavoriteListByUserId( $user_id ) {
  return $this->dao->delete($this->getTable_FavoriteList(), array('user_id' => $user_id) ) ;
}

public function deleteFavoriteItemByRecordId( $id ) {
  return $this->dao->delete($this->getTable_FavoriteItems(), array('record_id' => $id) ) ;
}

public function addFavoriteList( $name, $current, $user_id, $user_logged, $notification ) {
  $aSet = array(
    'list_name' => $name,
    'current' => $current,
    'user_id' => $user_id,
    'user_logged' => $user_logged,
    'notification' => $notification,
    'last_access' => date('Y-m-d H:i:s')
  );

  return $this->dao->insert( $this->getTable_FavoriteList(), $aSet);
}

public function updateFavoriteList( $id, $name, $current, $user_id, $user_logged, $notification ) {
  $aSet = array(
    'list_name' => $name,
    'current' => $current,
    'user_id' => $user_id,
    'user_logged' => $user_logged,
    'notification' => $notification,
    'last_access' => date('Y-m-d H:i:s')
  );

  $aWhere = array( 'list_id' => $id);
  return $this->_update($this->getTable_FavoriteList(), $aSet, $aWhere);
}

public function updateFavoriteListAccessDate( $id ) {
  $aSet = array(
    'last_access' => date('Y-m-d H:i:s')
  );

  $aWhere = array( 'list_id' => $id);
  return $this->_update($this->getTable_FavoriteList(), $aSet, $aWhere);
}

public function addFavoriteItem( $list_id, $item_id ) {
  $aSet = array(
    'list_id' => $list_id,
    'item_id' => $item_id,
    'added_date' => date('Y-m-d H:i:s')
  );

  return $this->dao->insert( $this->getTable_FavoriteItems(), $aSet);
}

public function updateFavoriteItem( $id, $list_id, $item_id ) {
  $aSet = array(
    'list_id' => $list_id,
    'item_id' => $item_id,
    'added_date' => date('Y-m-d H:i:s')
  );

  $aWhere = array( 'record_id' => $id);
  return $this->_update($this->getTable_FavoriteItems(), $aSet, $aWhere);
}

public function updateListToLogged( $old_id, $new_id, $current ) {
  $aSet = array(
    'user_id' => $new_id,
    'user_logged' => 1,
    'current' => $current
  );

  $aWhere = array( 'user_id' => $old_id);

  if($old_id <> $new_id) {
    return $this->_update($this->getTable_FavoriteList(), $aSet, $aWhere);
  }
}


public function updateListCurrentByListId( $list_id, $current ) {
  $aSet = array(
    'current' => $current
  );

  $aWhere = array( 'list_id' => $list_id);

  return $this->_update($this->getTable_FavoriteList(), $aSet, $aWhere);
}


public function updateListNotificationByListId( $list_id, $notif ) {
  $aSet = array(
    'notification' => $notif
  );

  $aWhere = array( 'list_id' => $list_id);

  return $this->_update($this->getTable_FavoriteList(), $aSet, $aWhere);
}


public function updateAllListCurrentByUserId( $user_id, $current ) {
  $aSet = array(
    'current' => $current
  );

  $aWhere = array( 'user_id' => $user_id);

  return $this->_update($this->getTable_FavoriteList(), $aSet, $aWhere);
}


public function getPages() {
  $this->dao->select('pk_i_id');
  $this->dao->from( $this->getTable_Page() );
  $this->dao->where('s_internal_name like "fi_%"');

  $result = $this->dao->get();

  if( !$result ) { return array(); }
  $prepare = $result->result();
  return $prepare;
}


          
// update
function _update($table, $values, $where) {
  $this->dao->from($table);
  $this->dao->set($values);
  $this->dao->where($where);
  return $this->dao->update();
}
}
?>