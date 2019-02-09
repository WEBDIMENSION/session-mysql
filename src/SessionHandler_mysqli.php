<?php

namespace Webdimension\SessionHandler;

use mysqli;
final class SessionHandler implements \SessionHandlerInterface
{
 /**
	* @var mysqli
	*/
 private $con;

 /**
	* @var string
	*/
 private $table;

 /**
	* @param mysqli $con
	* @param string $table
	*/
 public function __construct(mysqli $con, $table = 'sessions')
 {
	$this->con = $con;
	$this->table = $table;
 }

 /**
	* Close the session
	* @return bool
	*/
 public function close()
 {
	return true;
 }

 /**
	* Destroy a session
	* @param int $id The session ID being destroyed.
	* @return bool
	*/
 public function destroy($id)
 {
	 $sql = sprintf(
		"DELETE FROM `%s` WHERE id = '%s'",
		$this->table,
		$this->con->real_escape_string($id)
	);
	return $this->con->query($sql);
 }

 /**
	* Cleanup old sessions
	* @param int $maxlifetime
	* @return bool
	*/
 public function gc($maxlifetime)
 {

	$sql = sprintf(
		"DELETE FROM `%s` WHERE updated_on < '%s'",
		$this->table,
		date('Y-m-d H:i:s', time() - intval($maxlifetime))
	);
	return $this->con->query($sql);

 }

 /**
	* Initialize session
	* @param string $save_path The path where to store/retrieve the session.
	* @param string $session_id The session id.
	* @return bool
	*/
 public function open($save_path, $session_id)
 {

	// ここで毎回テーブルの有無をチェックしたくない場合は、trueを返すだけの関数にしてください。
//	return $this->createTable();
	return true;
 }

 /**
	* Read session data
	* @param string $session_id The session id to read data for.
	* @return string
	*/
 public function read($session_id)
 {
	 $sql = sprintf(
		"SELECT data FROM `%s` WHERE id = '%s'",
		$this->table,
		$this->con->real_escape_string($session_id)
	);
	$result = $this->con->query($sql);

	if ($result === false) {
	 return '';
	}

	if ($result->num_rows === 0) {
	 return '';
	}

	$data = $result->fetch_assoc()['data'];
	$result->free();

	return $data;
 }

 /**
	* Write session data
	* @param string $id The session id.
	* @param string $data
	* @return bool
	*/
 public function write($id, $data)
 {
	 $sql = sprintf(
		"REPLACE INTO `%s` (id, data, updated_on) VALUES('%s', '%s', '%s')",
		$this->table,
		$this->con->real_escape_string($id),
		$this->con->real_escape_string($data),
		date('Y-m-d H:i:s')
	);
	return $this->con->query($sql);
 }

 /**
	* @return bool
	*/
 private function createTable()
 {
	$result = $this->con->query(sprintf("SHOW TABLES LIKE '%s'", $this->con->real_escape_string($this->table)));

	if ($result === false) {
	 return false;
	}

	if ($result->num_rows > 0) {
	 return true;
	}

	$sql = <<< 'EOD'
CREATE TABLE `sessions` (
  `id` varchar(32) NOT NULL DEFAULT '',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOD;
	$sql = str_replace('`session`', "`$this->table`", $sql);
	return $this->con->query($sql);
 }
}