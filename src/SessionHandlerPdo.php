<?php

namespace Webdimension\SessionHandler;

/**
 * A PHP session handler using PDO to keep session data within a MySQL database
 *
 * @author  Jan Lohage <info@j2l4e.de>
 * @link    https://github.com/j2L4e/PHP-PDO-MySQL-Session-Handler
 *
 *
 * Based on PHP-MySQL-Session-Handler (uses mysqli)
 *
 * @author  Manuel Reinhard <manu@sprain.ch>
 * @link    https://github.com/sprain/PHP-MySQL-Session-Handler
 */

use PDO;

final class SessionHandlerPdo implements \SessionHandlerInterface
{
 /**
	* a PDO connection resource
	* @var resource
	*/
 private $con;
 /**
	* the name of the DB table which handles the sessions
	* @var string
	*/
 private $dbTable;

 /**
	* Set db data if no connection is being injected
	* @param  string $dbHost
	* @param  string $dbUser
	* @param  string $dbPassword
	* @param  string $dbDatabase
	* @param  string $dbCharset optional, default 'utf8'
	*/

 public function __construct(PDO $con, $tablename = 'session')
 {
	$this->con = $con;
	$this->dbTable = $tablename;
//	echo 'const<br/>';
 }

 public function open($save_path, $session_name)
 {
//	echo 'open-e<br/>';
	return true;
 }

 /**
	* Close the session
	* @return bool
	*/
 public function close()
 {
//	$this->con = null;
//	echo 'close<br/>';
	return true;
 }

 /**
	* Read the session
	* @param string session id
	* @return string string of the sessoin
	*/
 public function read($id)
 {
//	echo 'read-f<br/>';
//	echo  $this->dbTable.'<br />';
	$sql = "SELECT * FROM ".$this->dbTable." WHERE id=:id";
	$stmt = $this->con->prepare($sql);
	$stmt->bindParam("id",$id);
	$stmt->execute();
//	vardump($stmt);
//	echo 'numRows : '. $count = $stmt->rowCount().'<br />';
	$session = $stmt->fetch(PDO::FETCH_ASSOC);
//	while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
//	 //$resultに格納した連想配列のplanを抽出し、$rowsに格納。planがある限り、$rowsに追加していく
//	 $rows .= $result['data']."</br>";
//	}
//	echo 'test'.$rows.'<br />';
	if ($session) {
	 $ret = $session['data'];
	} else {
	 $ret = '';
	}
//	echo 'read-e<br/>';
	return $ret;
 }

 /**
	* Write the session
	* @param strin session id
	* @param string data of the session
	*/
 public function write($id, $data)
 {
//	echo 'write-f<br/>';
//	echo $id."<br />";
//	echo $data."<br />";
	$sql = "REPLACE INTO {$this->dbTable} (id,updated_on,data) VALUES (:id,:updated_on,:data)";
	$stmt = $this->con->prepare($sql);
	$stmt->bindParam(':id', $id);
	$ins_date = date('Y-m-d H:i:s');
	$stmt->bindParam(':updated_on', $ins_date);
	$stmt->bindParam(':data', $data);
	$ret = $stmt->execute();
//	echo 'write-e<br/>';
	return $ret;
 }

 /**
	* Destroy the session
	* @param int session id
	* @return bool
	*/
 public function destroy($id)
 {
//	echo 'destroy-f<br/>';
	$stmt = $this->con->prepare("DELETE FROM {$this->dbTable} WHERE id=:id");
	$ret = $stmt->execute(array(
		':id' => $id
	));
//	echo 'destroy-e<br/>';

	return $ret;
 }

 /**
	* Garbage Collector
	* @param int life time (sec.)
	* @return bool
	* @see session.gc_divisor      100
	* @see session.gc_maxlifetime 1440
	* @see session.gc_probability    1
	* @usage execution rate 1/100
	*        (session.gc_probability/session.gc_divisor)
	*/
 public function gc($maxlifetime)
 {
//	echo 'gc-f<br/>';
	$stmt = $this->con->prepare("DELETE FROM {$this->dbTable} WHERE updated_on < :limit");
	$ret = $stmt->execute(array(':limit' => date('Y-m-d H:i:s', time() - intval($maxlifetime))));

//	echo 'gc-e<br/>';
	return $ret;
 }
}//class