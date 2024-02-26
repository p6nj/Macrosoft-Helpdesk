<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/misc.php';
log_prepare();
printlog($_SESSION['client']->getTicketValidés(), 'log_tickets_validés');
