<?php

	error_reporting(E_ALL);
	
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: noreply@uthgroup.co.za' . "\r\n";
    mail("gary@uthgroup.co.za","test TruServ","test body",$headers);