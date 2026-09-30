<?php
require_once dirname(__DIR__) . '/bootstrap.php';

logout_user();
api_response(true, null, 'Đã đăng xuất thành công.');
