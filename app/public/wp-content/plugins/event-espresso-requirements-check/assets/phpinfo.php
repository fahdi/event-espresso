<?php
if (session_status()!= PHP_SESSION_ACTIVE) session_start ();
if ($_SESSION['EE_REQUIREMENTS_IS_ADMIN'] == TRUE) 
	phpinfo();
?>