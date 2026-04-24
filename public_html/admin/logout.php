<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Destroy session
session_destroy();
$_SESSION = [];

// Redirect to login page
redirect('/admin/login.php');

