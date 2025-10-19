<?php
// File: includes/init.php
// Reusable initialization for all pages
session_start();

require_once __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../models/User.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Load user object
$user = User::findById($_SESSION['user_id']);

// Load role (for demo purposes, can fetch real role from DB if needed)
$_SESSION['role_name'] = $user->getRoleName();

// Ensure $page_title is set before including header
if (!isset($page_title)) {
    $page_title = 'Hospital EMR';
}
