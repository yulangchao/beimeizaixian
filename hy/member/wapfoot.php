<?php
require_once(ROOT_PATH."member/wapfoot.php");
$content=ob_get_contents();
$content=str_replace("<!---->","",$content);
$content=str_replace('<!--include','',$content);
$content=str_replace('include-->','',$content);
ob_end_clean();
echo $content;
?>