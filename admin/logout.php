<?php
require_once __DIR__ . '/../includes/auth.php';
logout_user();
header("Location: /test/web_cty/admin/login.php");
exit;
