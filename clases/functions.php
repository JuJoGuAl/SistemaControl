<?php
date_default_timezone_set('America/Caracas');
//VARIALES GLOBALES
$array_sexo=array();
$array_sexo['M']="MASCULINO";
$array_sexo['F']="FEMENINO";
$array_edo=array();
$array_edo[]="SOLTERO";
$array_edo[]="CASADO";
$array_edo[]="DIVORCIADO";
$array_edo[]="VIUDO";
$array_plantel_tipo=array();
$array_plantel_tipo['OFICIAL']="OFICIAL";
$array_plantel_tipo['PRIVADO']="PRIVADO";
$array_clase=array();
$array_clase['A']="ALUMNOS";
$array_clase['P']="PROFESORES";
$array_status=array();
$array_status['1']="ACTIVA";
$array_status['0']="INACTIVA";
$array_status1=array();
$array_status1['1']="ACTIVO";
$array_status1['0']="INACTIVO";
$array_con=array();
$array_con['1']="ORDINARIO";
$array_con['2']="CONTRATADO";
$array_per=array();
$array_per['A']="A";
$array_per['B']="B";
$array_per['C']="C";
$array_per['I']="I";
$array_cuenta=array();
$array_cuenta['C']="CORRIENTE";
$array_cuenta['A']="AHORRO";
$selected = 'selected="selected"';

function parse_timestamp($timestamp, $format = 'd/m/Y')
{
    $formatted_timestamp = date($format, strtotime($timestamp));
    return $formatted_timestamp;
}
function CadenaLimpia($event){
	$contenido = eregi_replace("<[^>]*>","",$event);	
	return $contenido;
}
function fecha($date){
	$nueva_fecha=str_replace(".","/",$date);
	return $nueva_fecha;
}
function date_to_mysql($date){
	$fecha=explode("/",$date);
	$nueva_fecha=$fecha[2]."-".$fecha[1]."-".$fecha[0];
	return $nueva_fecha;
}
function numeros($num,$dec=2){
	$num_fix=number_format($num, $dec, ',', '.');
	return $num_fix;
}
function Get_Mes($dato){
	$mes = $dato;
	if($mes == "01"){
		$mes = "Enero";
	}elseif($mes == "02"){
		$mes = "Febrero";
	}elseif($mes == "03"){
		$mes = "Marzo";
	}elseif($mes == "04"){
		$mes = "Abril";
	}elseif($mes == "05"){
		$mes = "Mayo";
	}elseif($mes == "06"){
		$mes = "Junio";
	}elseif($mes == "07"){
		$mes = "Julio";
	}elseif($mes == "08"){
		$mes = "Agosto";
	}elseif($mes == "09"){
		$mes = "Septiembre";
	}elseif($mes == "10"){
		$mes = "Octubre";
	}elseif($mes == "11"){
		$mes = "Noviembre";
	}elseif($mes == "12"){
		$mes = "Diciembre";
	}
	return ($mes);
}
// Para la Clave 8 Caracteres
function generate_hash($length = 8){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++){
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function generateRandomString1($length = 8){ 
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
}
function fecha_reports($date=false){
	$fecha = ($date) ? $date :  date('d-m-Y');
	$mes = array(1 => "Enero",2 => "Febrero",3 => "Marzo",4 => "Abril",5 => "Mayo",6 => "Junio",7=> "Julio",8=> "Agosto",9=> "Septiembre",10=> "Octubre",11=> "Noviembre",12=> "Diciembre");
	$nueva_fecha = 'a los '.date("d",strtotime($fecha)).' dias del mes de '.$mes[date("n",strtotime($fecha))].' del AÑO '.date("Y",strtotime($fecha));
	return $nueva_fecha;
}
?>