<?php

session_start();

function redirect($path)
{
    header("Location: $path");
    exit();
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function sanitize($data)
{
    return htmlspecialchars(trim($data));
}

?>