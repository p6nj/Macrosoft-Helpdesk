<?php
include 'includes/crypto.php';
print encrypt(file_get_contents('includes/key'), 'adminweb');
print '
';
