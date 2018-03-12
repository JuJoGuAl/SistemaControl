<?php
include_once("./clases/class_permisos.php");
$menus = new permisos;
$menu = $menus->get_menu($_SESSION['user_log']);
if(!empty($menu)){
	foreach ($menu as $key => $value){
		//echo "-".$value['MENU']."<br>";
		$tpl->newBlock("menu_principal");
		$tpl->assign("nom_menu",$value['MENU']);
		$tpl->assign("ico_menu",$value['ICON']);
		$submenu = $menus->get_submenu($_SESSION['user_log'],$value['CMENU']);
		if(!empty($submenu)){
			foreach ($submenu as $key1 => $value1){
				$mod = $menus->get_mod($_SESSION['user_log'],$value['CMENU'],$value1['CSUBMENU']);
				$tpl->assign("sub_menu1",'<ul class="nav nav-second-level">');
				$tpl->assign("sub_menu2",'</ul>');
				$tpl->assign("arrow",'<span class="fa arrow">');
				if($value1['CSUBMENU']!=0){
					//echo "--".$value1['SUBMENU']."<br>";
					$tpl->newBlock("submodulo");
					$tpl->assign("mod_name",$value1['SUBMENU']);
					$tpl->assign("mod_url","#");
					if(!empty($mod)){
						$tpl->assign("sub_menu3",'<ul class="nav nav-third-level">');
						$tpl->assign("sub_menu4",'</ul>');
						$tpl->assign("arrow1",'<span class="fa arrow">');
						foreach ($mod as $key2 => $value2){
							//echo "---".$value2['MODULO']."<br>";
							$tpl->newBlock("modulo");
							$tpl->assign("mod_cont",$value2['MENU']);
							$tpl->assign("mod_name",$value2['MODULO']);
							$tpl->assign("mod_url",$value2['MOD_URL']);
						}
					}
				}else{
					if(!empty($mod)){
						foreach ($mod as $key3 => $value3){
							//echo "--".$value3['MODULO']."<br>";
							$tpl->newBlock("submodulo");
							$tpl->assign("mod_name",$value3['MODULO']);
							$tpl->assign("mod_url","?mod=".$value3['MENU']."&submod=".$value3['MOD_URL']);
						}
					}
				}
			}
		}
	}
}
?>