<?php

define('SB2_CONFIG_MAIN', WP_CONTENT_DIR."/spam-byebye.config.php");

if (!defined('WP_UNINSTALL_PLUGIN')) exit;

unlink(SB2_CONFIG_MAIN);

?>