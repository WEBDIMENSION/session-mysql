<?php
namespace Webdimension\SessionHandler;

final class AdministratorInfo
{
 public $auth_result;
 public $id;
 public $name;
 public $key;
 public $date_created;
 public $date_modifiy;
 public $date_lastlogin;

 public function __construct()
 {
  $this->auth_result = false;
 }

 public function login($info_array){
	$this->auth_result = $info_array['auth_result'];
	$this->id = $info_array[CULMUN_NAME_ADMINISTRATOR_ID];
	$this->name = $info_array[CULMUN_NAME_ADMINISTRATOR_NAME];
	$this->key = $info_array[CULMUN_NAME_ADMINISTRATOR_KEY];
	$this->date_created = $info_array[CULMUN_NAME_ADMINISTRATOR_DATE_CREATED];
	$this->date_modifiy = $info_array[CULMUN_NAME_ADMINISTRATOR_DATE_MODIFIY];
	$this->date_lastlogin = $info_array[CULMUN_NAME_ADMINISTRATOR_DATE_LASTLOGIN];
 }
 /**
	* @param mixed $id
	*/
 public function setId($id)
 {
	$this->id = $id;
 }
}