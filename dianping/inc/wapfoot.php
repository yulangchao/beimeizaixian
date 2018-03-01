<?php


require(ROOT_PATH."inc/wapfoot.php");

$content=ob_get_contents();
$content=str_replace('<!--include','',$content);
$content=str_replace('include-->','',$content);

ob_end_clean();
ob_start();
echo $content;


?>