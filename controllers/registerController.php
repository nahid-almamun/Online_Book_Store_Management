<?php
require_once '../config/database.php';
require_once '../config/helpers.php';
require_once '../models/User.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $role = sanitize($_POST['role']);
    $address = sanitize($_POST['address']);
    $phone = sanitize($_POST['phone']);

    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    if (!in_array($role, ['admin', 'customer'])) {
        $errors[] = "Invalid role selected.";
    }

    if (User::findByEmail($conn, $email)) {
        $errors[] = "Email already exists.";
    }

    if (empty($errors)) {
        $success = User::register($conn, $name, $email, $password, $role, $address, $phone);

        if ($success) {
            $_SESSION['success'] = "Registration successful. Please login.";
            redirect('../views/auth/login.php');
        } else {
            $errors[] = "Registration failed.";
        }
    }

    $_SESSION['errors'] = $errors;
    redirect('../views/auth/register.php');
}
?>