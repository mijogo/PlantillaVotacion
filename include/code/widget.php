<?php
function widget()
{
	$text = "";
	$text .= "<ul class=\"nav nav-sidebar\"><li>".widget1()."</li></ul>";
	$text .= "<ul class=\"nav nav-sidebar\"><li>".widget2()."</li></ul>";
	return $text;	
}
function widget1()
{
	return "Hola";
}
function widget2()
{
	return "Woooo";
}
?>