<?php
class entidades{	
	//para pais
	private $db;
	public $table;
	public $Id;

	//para estados
	private $db2;
	public $table2;
	public $Id2;

	//para ciudad
	private $db3;
	public $table3;
	public $Id3;

	public function __construct(){
		include_once('class_bd.php');
		$this->table = "DATA_PAIS";
		$this->tId = "CPAIS";
		$this->db = new data($this->table, $this->tId);
		$this->db->fields = array (
			array ('private',	$this->tId, "''"),
			array ('public',    'NOMBRE')
		);
		$this->table2 = "DATA_ESTADO";
		$this->tId2 = "CESTADO";
		$this->db2 = new data($this->table2, $this->tId2);
		$this->db2->fields = array (
			array ('private',	$this->tId2, "''"),
			array ('public',    'NOMBRE'),
			array ('public',    'CPAIS'),
		);
		$this->table3 = "DATA_CIUDAD";
		$this->tId3 = "CCIUDAD";
		$this->db3 = new data($this->table3, $this->tId3);
		$this->db3->fields = array (
			array ('private',	$this->tId3, "''"),
			array ('public',    'NOMBRE'),
			array ('public',    'CESTADO'),
		);
	} //public function __construct()

	public function get_pais(){
		$pais = $this->db->getRecords();
		if(empty($pais)){
			return false;
		}else{
			return $pais;
		}
	}
	public function get_estados($pais){
		$edo = $this->db2->getRecords(false,"CPAIS='$pais'");
		if(empty($edo)){
			return false;
		}else{
			return $edo;
		}
	}
	public function get_ciudad($estado){
		$ciudad = $this->db3->getRecords(false,"CESTADO='$estado'");
		if(empty($ciudad)){
			return false;
		}else{
			return $ciudad;
		}
	}

} //class alumnos
?>