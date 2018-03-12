function VentanaCentrada(theURL,winName,features, myWidth, myHeight, isCenter) { //v3.0
  if(window.screen)if(isCenter)if(isCenter=="true"){
    var myLeft = (screen.width-myWidth)/2;
    var myTop = (screen.height-myHeight)/2;
    features+=(features!='')?',':'';
    features+=',left='+myLeft+',top='+myTop;
  }
  window.open(theURL,winName,features+((features!='')?',':'')+'width='+myWidth+',height='+myHeight);
}
function imprimir(ruta,doc){
	VentanaCentrada(ruta,doc,'','1024','700','true');
}
$('.numeric').livequery(function(){
	$(this).keydown(function (e){
		acceptNum(e);
	});
});
$.fn.sumValues = function(){
	var sum = 0; 
	this.each(function() {
		if ( $(this).is(':input') ) {
			var val = $(this).val();
		} else {
			var val = $(this).text();
		}
		sum += parseFloat( ('0' + val).replace(/[^0-9-\.]/g, ''), 10 );		
	});
	return sum.toFixed(2);
};
function mensaje(msj,clase,log = false){
	var log_id='';
	if (log==false){
		log_id='#log';
	}else{
		log_id='#'+log;
	}
	var clas="";
	if(clase=='ERROR'){
		clas="alert-danger";
	}else if (clase=='INFO'){
		clas="alert-info";
	}else if(clase=='WARNING'){
		clas="alert-warning";
	}
	$(log_id).removeClass (function (index, className) {
	    return (className.match (/(^|\s)alert-\S+/g) || []).join(' ');
	});
	$(log_id).addClass(clas);
	$(log_id).html(msj);
	$(log_id).fadeIn('slown');
}
$('.borrar').livequery(function(){
	$(this).click(function(){
		var id = $(this).attr('data-id'), dir = $(this).attr('href'),
		accion = $(this).attr('data-action'), obj = $(this).attr('data-obj');
		$.ajax({
			type: "POST",
			url: dir,
			data: "accion="+accion+"&obj="+obj+"&id="+id,
			success: function(data){
				if(data.titulo=="ERROR"){
		            $('#log').addClass("alert-danger");
		            $('#log').html(data.texto);
		            $('#log').fadeIn('slown');
		          }else if(data.titulo=="OK"){
		            location.reload(true);
		          }else{
		            console.log(data);
		            location.reload(true);
		          }
				//document.location.reload();
			}
		});
		return false;
	});
});
function fecha(date){
	var fecha=date.split("-");
	var nueva_fecha=fecha[2]+"/"+fecha[1]+"/"+fecha[0];
	return nueva_fecha;
}
//Con esta funcion obligo a que un text solo acepte numeros
function acceptNum(e){
	// Allow: backspace, delete, tab, escape, enter and .
	if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
		// Allow: Ctrl+A, Command+A
		(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
		// Allow: home, end, left, right, down, up
		(e.keyCode >= 35 && e.keyCode <= 40)){
			// let it happen, don't do anything
		return;
	}
	// Ensure that it is a number and stop the keypress
	if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		return e.preventDefault();
	}
}
/* LIMPIA EL ULTIMO LOG */
function clear_log(log = false){
	var log_id='';
	if (log==false){
		log_id='#log';
	}else{
		log_id='#'+log;
	}
	$(log_id).fadeOut('slown');
	$('.has-error').removeClass("has-error");
	$(log_id).removeClass (function (index, className){
	    return (className.match (/(^|\s)alert-\S+/g) || []).join(' ');
	});
}
/* VALIDA EL VALOR DE UN CAMPO */
function validar(obj){
	element=$('#'+obj+'_c');
	var val='', label = $("label[for='"+obj+"_c']").text();
	if (element.is(':input')){
		val = element.val();
	 }else{
	 	val = element.text();
	}
	if(val==''){
		$('#'+obj+'_g').addClass("has-error");
		mensaje('EL CAMPO '+label+' ES REQUERIDO!',"ERROR");
		return false;
	}else{
		clear_log();
		return true;
	}
}
//CHEQUEA LA CI EN LA BD
function check_ced(obj,tipo,code){
	console.log(code);
	element=$('#'+obj+'_c');
	var val='', label = $("label[for='"+obj+"_c']").text();
	var response=false;
	if (element.is(':input')){
		val = element.val();
	 }else{
	 	val = element.text();
	}
	if(val!=''){
		$.ajax({
			type: "POST",
			url: "./modulos/maestras/modal.php",
			data: "accion=check_ced&obj="+tipo+"&ced="+val+"&code="+code,
			async: false,
			dataType:'json'
		})
		.done(function(data){
			if(data){
				$('#'+obj+'_g').addClass("has-error");
				mensaje('LA CEDULA '+val+' YA EXISTE EN EL SISTEMA!',"ERROR");
				response=false;
			}else{
				clear_log();
				response=true;
			}
		})
		.fail(function(x,err,msj){
			console.log(msj);
		});
	}
	return response;
}
function IsCed(obj){
	var vRegExp = /^(v|V|e|E)(\d{7,9})$/;
	element=$('#'+obj+'_c');
	var val='', label = $("label[for='"+obj+"_c']").text();
	if (element.is(':input')){
		val = element.val();
	 }else{
	 	val = element.text();
	}
	if(val!=''){
		if(val.match(vRegExp)){
			clear_log();
			return true;
		}else{
			$('#'+obj+'_g').addClass("has-error");
			mensaje('EL CAMPO '+label+' NO POSEE UN FORMATO VALIDO!',"ERROR");
			return false;
		}
	}
}
/*VALIDA SI SE SELECCIONO UN ITEM DE UNA LISTA*/
function list(obj,log=false){
	element=$('#'+obj+'_c');
	var val='', label = $("label[for='"+obj+"_c']").text();
	var log_id='';
	if (log==false){
		log_id='#log';
	}else{
		log_id='#'+log;
	}
	if (element.is(':input')){
		val = element.val();
	 }else{
	 	val = element.text();
	}
	if(val=='-1'){
		$('#'+obj+'_g').addClass("has-error");
		mensaje('DEBE SELECCIONAR UN VALOR PARA : '+label,"ERROR");
		return false;
	}else{
		clear_log();
		return true;
	}
}
function list2(val,log=false){
	var log_id='';
	if (log==false){
		log_id='#log';
	}else{
		log_id='#'+log;
	}
	if(val=='-1'){
		mensaje('DEBE SELECCIONAR UNA SECCION!',"ERROR");
		return false;
	}else{
		clear_log();
		return true;
	}
}
function list3(val,log=false){
	var log_id='';
	if (log==false){
		log_id='#log';
	}else{
		log_id='#'+log;
	}
	if(val=='-1'){
		mensaje('DEBE SELECCIONAR UN PROFESOR!',"ERROR");
		return false;
	}else{
		clear_log();
		return true;
	}
}
/*CUENTA LAS FILAS DE UNA TABLA*/
function count_row(tbl,tipo){
	var id = tbl.attr('id'), table=$('#'+id+' tbody tr').length;
	if(table<=0){
		mensaje('DEBE SELECCIONAR POR LO MENOS '+tipo+'!',"ERROR");
		return false;
	}else{
		clear_log();
		return true;
	}
}
/*VALIDA SI EL CAMPO ES NUMERICO */
function IsNumber(obj,log=false){
	var vRegExp = /[0-9 -()+]+$/;
	element=$('#'+obj+'_c');
	var val='', label = $("label[for='"+obj+"_c']").text();
	var log_id='';
	if (log==false){
		log_id='#log';
	}else{
		log_id='#'+log;
	}
	if (element.is(':input')){
		val = element.val();
	 }else{
	 	val = element.text();
	}
	if(val!=''){
		if(val<=0){
			$('#'+obj+'_g').addClass("has-error");
			mensaje('EL CAMPO '+label+' NO PUEDE SER MENOR O IGUAL A 0',"ERROR");
			return false;
		}else{
			if(val.match(vRegExp)){
				clear_log();
				return true;
			}else{
				$('#'+obj+'_g').addClass("has-error");
				mensaje('EL CAMPO '+label+' DEBE CONTENER SOLO NUMEROS',"ERROR");
				return false;
			}
		}
	}	
}
/*VALIDA 2 NUMEROS */
function NumberEqual(num1,num2,log=false){
	var vRegExp = /[0-9 -()+]+$/, el1=$('#'+num1+'_c'), el2=$('#'+num2+'_c'), val1='',
	lb1 = $("label[for='"+num1+"_c']").text(), lb2 = $("label[for='"+num2+"_c']").text();
	var log_id='';
	if (log==false){
		log_id='#log';
	}else{
		log_id='#'+log;
	}
	if (el1.is(':input')){
		val1 = el1.val();
		val2 = el2.val();
	 }else{
	 	val1 = el1.text();
	 	val2 = el2.text();
	}
	if(val1!='' || val2!=0){
		if(val1!=val2){
			$('#'+num1+'_g').addClass("has-error");
			mensaje('EL CAMPO '+lb1+' ES DIFERENTE DEL '+lb2,"ERROR");
			return false;
		}else{
			clear_log();
			return true;
		}
	}	
}
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};