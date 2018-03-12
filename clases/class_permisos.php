<?php
class permisos{
	private $db;
	public $table;
	public $tId;
	//USUARIO
	private $db2;
	public $table2;
	public $tId2;
	//MODULOS
	private $db3;
	public $table3;
	public $tId3;
	//PERMISOS
	private $db4;
	public $table4;
	public $tId4;
	
	public function __construct(){
		include_once('class_bd.php');
		$this->table = "adm_mod_usu mu INNER JOIN adm_mod m ON mu.cmodulo=m.cmodulo INNER JOIN adm_menu am ON m.cmenu=am.cmenu INNER JOIN adm_menu_sub ams ON m.csubmenu=ams.csubmenu";
		$this->tId = "am.orden";//No existe actualmente
		$this->db = new data($this->table, $this->tId);
		$this->db->fields = array (
			array ('public',	'mu.cusuario'),
			array ('public',	'mu.cmodulo'),
			array ('public',	'm.modulo'),
			array ('public',	'm.mod_url'),
			array ('public',	'am.menu'),
			array ('public',	'am.icon'),
			array ('public',	'ams.submenu')
		);
		$this->table2 = "ADM_USUARIOS";
		$this->tId2 = "CUSUARIO";
		$this->db2 = new data($this->table2, $this->tId2);
		$this->db2->fields = array (
			array ('public',	"CUSUARIO"),
			array ('public',	'CLAVE'),
			array ('public',	'EMPLEADO'),
			array ('public',	'ESTATUS'),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION'),
		);
		$this->table3 = "ADM_MOD M INNER JOIN ADM_MENU ME ON M.CMENU=ME.CMENU";
		$this->tId3 = "M.CMODULO";
		$this->db3 = new data($this->table3, $this->tId3);
		$this->db3->fields = array (
			array ('system',	"LPAD(M.CMODULO,10,'0') AS CMODULO"),
			array ('system',	'M.MODULO'),
			array ('system',	'ME.MENU'),
			array ('system',	'M.USUARIO_CREADO'),
			array ('system',	'M.USUARIO_MODIFICACION'),
		);
		$this->table4 = "ADM_MOD_USU";
		$this->tId4 = "CMODULO";
		$this->db4 = new data($this->table4, $this->tId4);
		$this->db4->fields = array (
			array ('public',	"CUSUARIO"),
			array ('public',	'CMODULO'),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION'),
		);
	}
	public function val_log($user,$pass){
		$usuario=strtoupper($user);
		$where = "CUSUARIO = '$usuario'";
		$result=0;
		$user = $this->db2->getRecords(false,$where);
		if(empty($user)){
			$result=2;
		}else{
			$where = "CUSUARIO = '$usuario' AND ESTATUS='1'";
			$user = $this->db2->getRecords(false,$where);
			if(empty($user)){
				$result=5;
			}else{
				if($user[0]['CLAVE'] == md5($pass)){
					//VERIFICO QUE POSEE MODULOS PARA VER
					$mods=$this->db->getRecords("DISTINCT(am.cmenu),am.menu,am.icon","mu.cusuario='$usuario'","am.cmenu asc");
					if(empty($mods)){
						$result=4;
					}else{
						$_SESSION['user_log'] = strtoupper($user[0]['CUSUARIO']);
						$result=1;
					}
				}else{
					$result=3;
				}
			}
		}
		return $result;
	}
	public function val_mod($user,$mod){
		$where = "mu.cusuario = '$user' and m.mod_url='$mod'";
		$user = $this->db->getRecords(false,$where);
		if(empty($user)){
			return false;
		}else{
			return $user;
		}
	}
	public function get_module($mod){
		$mod = $this->db->getRecords(false,"m.mod_url='$mod'");
		if(empty($mod)){
			return false;
		}else{
			return $mod;
		}
	}
	public function get_menu($user){
		//$user = $this->db->getRecords("DISTINCT(am.cmenu),am.menu,am.icon,ams.csubmenu","mu.cusuario='$user'");
		$user = $this->db->getRecords("DISTINCT(am.cmenu),am.menu,am.icon","mu.cusuario='$user'","am.cmenu asc");
		if(empty($user)){
			return false;
		}else{
			return $user;
		}
	}
	public function get_submenu($user,$menu){
		$user = $this->db->getRecords("DISTINCT(ams.csubmenu),ams.submenu","mu.cusuario='$user' AND am.cmenu='$menu'","ams.csubmenu asc");
		if(empty($user)){
			return false;
		}else{
			return $user;
		}
	}
	public function get_mod($user,$menu,$submenu){
		$user = $this->db->getRecords(false,"mu.cusuario='$user' AND am.cmenu='$menu' AND ams.csubmenu='$submenu'","m.cmodulo asc");
		if(empty($user)){
			return false;
		}else{
			return $user;
		}
	}
	public function get_user($user){
		$usuario = $this->db2->getRecords(false,"CUSUARIO='$user'");
		if(empty($usuario)){
			return false;
		}else{
			$permisos = $this->db4->getRecords(false,"CUSUARIO = '$user'");
			$usuario[1] = $permisos;
			return $usuario;
		}
	}
	public function list_users(){
		$result = $this->db2->getRecords();
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
	public function list_mods(){
		$result = $this->db3->getRecords(false,false,"ME.CMENU");
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
	public function new_user($data,$permisos){
		$usuario=$data[0];
		$log=$_SESSION['user_log'];
		$this->db2->fields[7]=array("0" => "public","1" => "USUARIO_CREADO");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$log;
		$result=$this->db2->insertRecord($data,0);
		if(empty($result)){
			//NO SE INSERTO
			$response['titulo']="ERROR";
			$response['texto']="OCURRIO UN ERROR AL REGISTRAR EL USUARIO";
		}else{
			$response['titulo']="OK";
			if(!empty($permisos)){
				for ($i=0;$i<count($permisos);$i++){
					$per[] = $usuario;
					$per[] = $permisos[$i];
					$this->db4->fields[5]=array("0" => "public","1" => "USUARIO_CREADO");
					$per[]=$log;
					$this->db4->insertRecord($per,0); 
					$per = "";
				}
			}
		}
		return $response;
	}
	public function edit_user($id,$data,$permisos){
		$log=$_SESSION['user_log'];
		$this->db2->fields[7]=array("0" => "public","1" => "USUARIO_MODIFICACION");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$log;
		$result=$this->db2->updateRecord($id,$data);
		if(empty($result)){
			//NO SE INSERTO
			$response['titulo']="ERROR";
			$response['texto']="OCURRIO UN ERROR AL ACTUALIZAR EL USUARIO";
		}else{
			$response['titulo']="OK";
			if(!empty($permisos)){
				$this->db4->deleteRecords("CUSUARIO = '$id'");
				for ($i=0;$i<count($permisos);$i++){
					$per[] = $id;
					$per[] = $permisos[$i];
					$this->db4->fields[5]=array("0" => "public","1" => "USUARIO_CREADO");
					$per[]=$log;
					$this->db4->insertRecord($per,0); 
					$per = "";
				}
			}
		}
		return $response;
	}
}
?>