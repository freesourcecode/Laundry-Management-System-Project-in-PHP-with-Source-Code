<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();

if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == "save_settings"){
	$save = $crud->save_settings();
	if($save)
		echo $save;
}
if($action == "save_category"){
	$save = $crud->save_category();
	if($save)
		echo $save;
}
if($action == "delete_category"){
	$save = $crud->delete_category();
	if($save)
		echo $save;
}
if($action == "save_supply"){
	$save = $crud->save_supply();
	if($save)
		echo $save;
}
if($action == "delete_supply"){
	$save = $crud->delete_supply();
	if($save)
		echo $save;
}
if($action == "save_laundry"){
	$save = $crud->save_laundry();
	if($save)
		echo $save;
}
if($action == "delete_laundry"){
	$save = $crud->delete_laundry();
	if($save)
		echo $save;
}
if($action == "save_inv"){
	$save = $crud->save_inv();
	if($save)
		echo $save;
}
if($action == "delete_inv"){
	$save = $crud->delete_inv();
	if($save)
		echo $save;
}




