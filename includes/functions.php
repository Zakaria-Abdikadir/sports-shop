<?php

/**
 * Redirect to another page
 */
function redirect($page)
{
    header("Location: " . BASE_URL . $page);
    exit();
}

/**
 * Format currency
 */
function money($amount)
{
    return CURRENCY . " " . number_format($amount, 2);
}

/**
 * Escape HTML output
 */
function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Check login status
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * Get current username
 */
function currentUser()
{
    return $_SESSION['username'] ?? 'Guest';
}