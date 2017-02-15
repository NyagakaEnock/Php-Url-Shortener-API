<?php
require_once("ShortUrl.php");
$ShortUrl = new ShortUrl();
$link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if($link)
{
$Array = explode('/',$link);
$Id = $Array[4];	

if($ShortUrl->GetLongUrl($Id)!=null)
{
$Url = $ShortUrl->GetLongUrl($Id);
header("Location: ".$Url);	
}else{
	echo "The page you requested was not found on this server";
}	
}
?>