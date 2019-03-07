<?php
namespace Webdimension\SessionHandler;
use PDO;
final class EasyFunctionDev  extends EasyFunctionCommon
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

}