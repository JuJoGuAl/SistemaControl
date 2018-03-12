<?php
class parametros{
	private $db;
	public $table;
	public $tId;
	
	public function __construct(){
		include_once('class_bd.php');
		$this->table = "ADM_PARAMETRO";
		$this->tId = "CPARAMETRO";
		$this->db = new data($this->table, $this->tId);
		$this->db->fields = array (
			array ('system',	$this->tId),
			array ('public',	'PARAMETRO'),
			array ('public',	'VALOR'),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION')
		);
	}
	public function get_parameter($parameter){
		$result = $this->db->getRecord($parameter);
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
	public function get_parameters(){
		$result = $this->db->getRecords();
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
}
?>