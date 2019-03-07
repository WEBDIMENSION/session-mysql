<?php

namespace Webdimension\SessionHandler;

use PDO;

final class EasyFunctionAdministrator extends EasyFunctionCommon
{
 private $con;

 public function __construct(PDO $con)
 {
	$this->con = $con;
 }

 public function disp_mess($value)
 {
	return $value;
 }
public function administrator_get_login_key($key){
 $sql = 'SELECT * FROM ' . TABLE_NAME_ADMINISTRATOR . ' WHERE ' . CULMUN_NAME_ADMINISTRATOR_KEY . ' = :key';
 $stmt = $this->con->prepare($sql);
 $stmt->bindParam("key", $key);
 $stmt->execute();
 $user = $stmt->fetch(PDO::FETCH_ASSOC);
 return $user;
}
 public function administrator_auth($key, $pass)
 {
	$user = $this->administrator_get_login_key($key);
	$administrator_auth_result = array(
		'auth_result' => false,
		'message' => 'Login Faild.',
		CULMUN_NAME_ADMINISTRATOR_ID => '',
		CULMUN_NAME_ADMINISTRATOR_NAME => '',
		CULMUN_NAME_ADMINISTRATOR_KEY => '',
		CULMUN_NAME_ADMINISTRATOR_ROLE => '',
		CULMUN_NAME_ADMINISTRATOR_DATE_CREATED => '',
		CULMUN_NAME_ADMINISTRATOR_DATE_MODIFIY => '',
		CULMUN_NAME_ADMINISTRATOR_DATE_LASTLOGIN => ''
	);
	//  Password Auth
	if (password_verify($pass, $user[CULMUN_NAME_ADMINISTRATOR_PASSWORD])) {
	 $administrator_auth_result['auth_result'] = true;
	 $administrator_auth_result['message'] = 'Login Succcess.';
	 $administrator_auth_result[CULMUN_NAME_ADMINISTRATOR_ID] = $user[CULMUN_NAME_ADMINISTRATOR_ID];
	 $administrator_auth_result[CULMUN_NAME_ADMINISTRATOR_NAME] = $user[CULMUN_NAME_ADMINISTRATOR_NAME];
	 $administrator_auth_result[CULMUN_NAME_ADMINISTRATOR_KEY] = $user[CULMUN_NAME_ADMINISTRATOR_KEY];
	 $administrator_auth_result[CULMUN_NAME_ADMINISTRATOR_ROLE] = $user[CULMUN_NAME_ADMINISTRATOR_ROLE];
	 $administrator_auth_result[CULMUN_NAME_ADMINISTRATOR_DATE_CREATED] = $user[CULMUN_NAME_ADMINISTRATOR_DATE_CREATED];
	 $administrator_auth_result[CULMUN_NAME_ADMINISTRATOR_DATE_MODIFIY] = $user[CULMUN_NAME_ADMINISTRATOR_DATE_MODIFIY];
	 $administrator_auth_result[CULMUN_NAME_ADMINISTRATOR_DATE_LASTLOGIN] = $user[CULMUN_NAME_ADMINISTRATOR_DATE_LASTLOGIN];
	}
	return $administrator_auth_result;
 }
 function remake_administrator_password($key){
	$new_pass = $this->get_random_password();
	$hush = $this->password_hush($new_pass);
	$sql = "update ".TABLE_NAME_ADMINISTRATOR.
		" set ".CULMUN_NAME_ADMINISTRATOR_PASSWORD."=:password where ".CULMUN_NAME_ADMINISTRATOR_KEY."=:key";
	$query = $this->con->prepare($sql);
	$query->bindParam("password", $hush);
	$query->bindParam("key", $key);
	$query->execute();
	return $new_pass;
 }
}