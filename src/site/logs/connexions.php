<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/misc.php';
debug();
log_prepare();
foreach ($_SESSION['client']->getConnexionsEchouées() as $line) {
    print '
' . array_shift($line);
    foreach ($line as $field) print ',' . $field;
}
