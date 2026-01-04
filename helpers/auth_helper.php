<?php
function requireLogin() {
    global $auth;
    $auth->checkAccess();
}

function isAdmin() {
    global $auth;
    return $auth->isAdmin();
}
?>