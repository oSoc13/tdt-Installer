<?php

$page = @$_GET['page'];
if(empty($page)){
	$page = "packages";
}

include "header.html";
include $page.".html";
include "footer.html";
