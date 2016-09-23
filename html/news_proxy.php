<?php
	require_once('classes/NaverProxy.php');

	$naverproxy = new NaverProxy();
	echo $naverproxy -> queryNaver($_POST['query'], $_POST['target'], $_POST['count']);