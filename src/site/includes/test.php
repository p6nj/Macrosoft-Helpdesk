<?php
include "crypto.php";
print 'clé : ' . file_get_contents("key") . '
';
print encrypt(file_get_contents("key"), "4H7nvbyx8g6tfrMwWUNHUtvAeGsvngs9fjZmmf6n5FFCtLrDq6");
print '
';
