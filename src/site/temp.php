<?php
include 'includes/crypto.php';
print encrypt(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/includes/key'), 'adminweb');
print '
';
