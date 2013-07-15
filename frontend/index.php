<?php

$page = @$_GET['page'];
if(empty($page)){
	$page = "welcome";
}

//include "header.html";
include $page.".html";
include "footer.html";
