<?php
session_start();

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

$script_path_parts = explode('/', dirname($_SERVER['SCRIPT_NAME']));
$project_root_segment = "";
if (isset($script_path_parts[1]) && !empty($script_path_parts[1]) && is_dir($_SERVER['DOCUMENT_ROOT'] . '/' . $script_path_parts[1])) {
    $project_root_segment = '/' . $script_path_parts[1];
}
$main_page_url = $project_root_segment . "/frontend/main/main.php";

header("Location: " . $main_page_url);
exit();
