<?php
require(dirname(__FILE__)."/global.php");

$db->query("UPDATE {$_pre}home SET hits=hits+1,visitor='$conf[visitor]' WHERE uid='$uid' ");
$db->query("UPDATE {$_pre}company  set hits=hits+1,lastview='$timestamp' WHERE uid='$uid'");

require(style_html("index"));
?>