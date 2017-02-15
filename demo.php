<?php
require_once("ShortUrl.php");


if(isset($_POST['submit']))
{
$Url = $_POST['longurl'];
$ShortUrl = new ShortUrl();
$ShortUrl->SaveUrl($Url);	
}
?>

<!DOCTYPE html>
<html>
<title>URL shortener</title>
<meta name="robots" content="noindex, nofollow">
</html>
<body>
<form method="post" action="demo.php" id="shortener">
<label for="longurl">URL to shorten</label>
 <input type="text" name="longurl" id="longurl">
 <input type="submit" value="Shorten" name="submit">
</form>


</body>
</html>