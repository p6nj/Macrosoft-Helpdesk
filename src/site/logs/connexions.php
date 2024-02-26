<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/misc.php';
log_prepare();
printlog($_SESSION['client']->getConnexionsEchouées(), 'log_connexions');
