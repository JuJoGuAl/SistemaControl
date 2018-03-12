<?php
function connect(){
	$dbname = "D:/xampp/htdocs/estudios/ESTUDIOS.FDB";
	$host = "localhost";
	$user = "SYSDBA";
	$pass = "masterkey";
	$pat = "";
	$dbh; //Conexión
	//$dbh= new ibase_connect($host.":".$dbname, $user, $pass) or die (ibase_errmsg());
	$dbh = ibase_connect($host.":".$dbname, $user, $pass);
	if (!$dbh){
		echo "Error al Conectarse a la Base de Datos: <strong>".(ibase_errmsg())."</strong>";
		exit();
	}

	return $dbh;
}
class data {
	private $linkid;      // Link de la BD PostgreSQL
	private $result;      // Resultado del QUERY
	public $table;		  // Nombre de la Tabla a trabajar
	/** Campos de la Tabla
	fieldName: nombre del campo en la tabla
	class: tipo de campo (public, private, system)
	*/
	public $fields;
	public $campoId;	  // Campo llave de la Tabla

	public function __construct ($table, $campoId){
		$this->table = $table;
		$this->campoId = $campoId;
		$this->fields = array ();
		//$this->linkid = new Database;
		$this->linkid = connect();
	}
	//Metodos Publicos
	/** Devuelve los registros de la tabla
	* @param $where_str: Cadena=''. Condición para filtrar resultados.
	* @param $order_str: Cadena=''. Campo sobre el que se ordenarán los registros.
	* @param $count: Entero =false . Número de registros a devolver. Si es false, toda la tabla
	* @param $start: Entero =0. Indica a partir de qué registros se devuelven datos, por default 0.
	*/
	public function getRecords ($campos_srt=false,$where_str=false, $order_str=false, $count=false, $start=0){
		$where =$where_str ? "WHERE $where_str" : "";
		$order =$order_str ? "ORDER BY $order_str" : "ORDER BY {$this->campoId} ASC";
		$limit = $count ? "LIMIT $start, $count" : "";
		$campos =$campos_srt ? "$campos_srt" : $this->getAllFields();
		$query ="SELECT $campos FROM {$this->table} $where $order $limit";
		//echo $query;
		$psql = ibase_prepare($query);
		$result = ibase_execute($psql);
		$resultado =array();
		while ($consF=ibase_fetch_assoc($result))
			array_push($resultado, $consF);
		return $resultado;
	}
	/** Devuelve un registro de la tabla
	* @param $id: Entero. Id del registro a devolver.
	*/
	public function getRecord ($id){
		return $this->getRecords(false,"{$this->campoId}=$id");
	}
	/** Inserta un Registro en la BD
	* @param $sysData_str: Int, Si es 1, el sistema Decide los valores segun el valor por defecto, si es 0 el Usuario especifica los Valores
	*/
	public function insertRecord ($data){
		$campos =$this->getTableFields();
		$data = implode ("', '", $data);
		$sql ="INSERT INTO {$this->table} ($campos) VALUES (";
		$sysData =$this->getDefaultValues();
		if($sysData){
			$sysData .= ",";
			$sql .="$sysData ";
		}
		$sql .="'$data')";
		//echo $sql;
		$psql = ibase_prepare($sql);
		$result = ibase_execute($psql);
		return $this->validateOperation();
	}	
	public function updateRecord ($id, $data, $where_str=false){
		$campos =$this->getEditableFields(true);
		$datos =array ();
		foreach ($campos as $ind => $campo){
			$current_data =@$data[$ind];
			if($current_data != ""){
				array_push ($datos, "$campo='$current_data'"); 
			}
		}
		$datos =implode (", ", $datos);
		$where =$where_str ? "$where_str" : "{$this->campoId}='$id'";
		$query = "UPDATE {$this->table} SET $datos WHERE $where";
		//echo $query;
		$psql = ibase_prepare($query);
		$result = ibase_execute($psql);
		return $this->validateOperation();
	}
	/*Inserta o Actualiza un Registro en la BD*/
	public function insertUpdate ($data,$match){
		$campos =$this->getTableFields();
		$valores="";
		foreach ($data as $key => $value){
			if ($value==''){
				$valores.="NULL,";//ASIGNO NULL PARA LOS DATOS VACIOS (PARA EVITAR EL REBOTE)
			}else{
				$valores.="'".$value."',";
			}
		}
		$valores = substr($valores,0,-1);//QUITO LA ULTIMA ,
		$sql ="UPDATE OR INSERT INTO {$this->table} ($campos) VALUES (";
		$sysData =$this->getDefaultValues();
		if($sysData){
			$sysData .= ",";
			$sql .="$sysData ";
		}
		$sql .="$valores) MATCHING ($match) RETURNING {$this->campoId}";
		//echo $sql;
		$psql = ibase_prepare($sql);
		$result = ibase_execute($psql);
		$resultado =array();
		while ($consF=ibase_fetch_assoc($result))
			array_push($resultado, $consF);
		return $resultado;
		//return $this->validateOperation();
	}
	public function deleteRecord ($id){
		$query = "DELETE FROM {$this->table} WHERE {$this->campoId}=$id";
		//echo $query;
		$psql = ibase_prepare($query);
		$result = ibase_execute($psql);
		return $this->validateOperation();
	}
	
	public function deleteRecords ($where){
		$query = "DELETE FROM {$this->table} WHERE $where";
		//echo $query;
		$psql = ibase_prepare($query);
		$result = ibase_execute($psql);
		return $this->validateOperation();
	}
	//ME DEVUELVE UN NUMERO A PARTIR DE UN GENERADOR
	public function getGen($gen,$val=1){
		$query ="SELECT gen_id($gen,$val) FROM rdb\$database";
		//echo $query;
		$psql = ibase_prepare($query);
		$result = ibase_execute($psql);
		$resultado =array();
		while ($consF=ibase_fetch_assoc($result))
			$resultado = $consF['GEN_ID'];
		return $resultado;
	}
	/*Ejecuta un Query directamente en la BD
	*/
	public function query($consulta=false){
		$resultado =array();
		if($consulta==false){
			$resultado[]="Query is Empty!";
		}else{
			//$consulta=pg_escape_string($consulta);
			//echo $consulta;
			$psql = ibase_prepare($consulta);
			$result = ibase_execute($psql);
			while ($consF=ibase_fetch_assoc($result))
				array_push($resultado, $consF);
		}
		return $resultado;
	}
	// Metodos privados
	private function getFieldsByType ($type=''){
		$return =array ();
		$types =explode ('|', $type);
		foreach ($this->fields as $field){
			$includeField =false;
			foreach ($types as $t){
				if ($field[0] == $t){
					array_push ($return, $field);
				}
			}
		}
		return $return;
	}
	private function getNameFields ($type){
		$return =array ();
		$fields =$this->getFieldsByType ($type);
		foreach ($fields as $field){
			array_push ($return, $field[1]);
		}
		return $return;
	}
	private function getEditableFields ($asArray=false){
		$return =$this->getNameFields ('public');
		return $asArray ? $return : implode (', ', $return);
	}
	private function getTableFields ($asArray=false){
		$temp =$this->getNameFields ('private');
		foreach($temp as $r)$return[] = $r;
		$temp =$this->getNameFields ('public');
		foreach($temp as $r)$return[] = $r;
		return $asArray ? $return : implode (', ', $return);
	}
	private function getAllFields ($asArray=false){
		$return =$this->getNameFields ('public|private|system');
		return $asArray ? $return : implode (', ', $return);
	}
	private function getDefaultValues ($asArray=false){
		$return =array ();
		$fields =$this->getFieldsByType ('private');
		foreach ($fields as $field){
			array_push ($return, $field[2]);
		}
		return $asArray ? $return : implode (', ', $return);
	}
	private function validateOperation (){
		return ibase_errmsg()=='' ? true :ibase_errmsg();
	}
}?>