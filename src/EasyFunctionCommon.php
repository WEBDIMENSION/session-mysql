<?php
namespace Webdimension\SessionHandler;

use PDO;
class EasyFunctionCommon{
 private $con;
 public function __construct(PDO $con)
 {
	$this->con = $con;
 }
 public function func_check_extend(){
	return 'function-extend-OK!';
 }

function password_hush($password){
 $hash = password_hash($password, PASSWORD_BCRYPT);
 return $hash;
}
 function deb_mess($mess_array)
 {
	if(DEVELOP_MODE)
	{
	 echo '<div>'.$mess_array[0].' : '.$mess_array[1].'</div>';
	}
 }
 function get_random_password($length = 16)
 {
	$rdm = array_reduce(range(1, $length), function($p){ return $p.str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz')[0]; });
	return $rdm;
//	$hush = $this->password_hush($rdm);
//	return $hush;
 }
}