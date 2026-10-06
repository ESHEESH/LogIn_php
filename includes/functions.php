<?php
session_start();
define('USERS_FILE', __DIR__ . '/../data/users.json');

function clean($v) { return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8'); }

function getUsers() {
    if (!file_exists(USERS_FILE)) return [];
    return json_decode(file_get_contents(USERS_FILE), true) ?: [];
}
function saveUsers($users) {
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT), LOCK_EX);
}
function findUser($email) {
    foreach (getUsers() as $u) {
        if (strcasecmp($u['email'], $email) === 0) return $u;
    }
    return null;
}
function setFlash($msg) { $_SESSION['flash'] = $msg; }
function getFlash() {
    $m = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $m;
}
