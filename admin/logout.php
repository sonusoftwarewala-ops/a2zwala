<?php
require __DIR__ . '/_bootstrap.php';
$_SESSION = [];
session_destroy();
redirect('login.php');
