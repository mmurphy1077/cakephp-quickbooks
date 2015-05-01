<?php
	$responseText['error'] = $error; 
	$responseText['success'] = $success;
	$responseText['data'] = $data;
	echo json_encode($responseText);
?>