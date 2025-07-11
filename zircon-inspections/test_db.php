<?php

echo "Testing database connections...\n\n";

// Test 1: Default XAMPP settings
echo "Test 1: root with no password\n";
try {
    $pdo = new PDO('mysql:host=localhost;dbname=zirconinsp', 'root', '');
    echo "✓ Connection successful!\n";
} catch (Exception $e) {
    echo "✗ Connection failed: " . $e->getMessage() . "\n";
}

// Test 2: root with password
echo "\nTest 2: root with password 'oil1234'\n";
try {
    $pdo = new PDO('mysql:host=localhost;dbname=zirconinsp', 'root', 'oil1234');
    echo "✓ Connection successful!\n";
} catch (Exception $e) {
    echo "✗ Connection failed: " . $e->getMessage() . "\n";
}

// Test 3: Using socket file
echo "\nTest 3: Using socket file\n";
try {
    $pdo = new PDO('mysql:unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock;dbname=zirconinsp', 'root', 'oil1234');
    echo "✓ Connection successful!\n";
} catch (Exception $e) {
    echo "✗ Connection failed: " . $e->getMessage() . "\n";
}

// Test 4: Try to connect without specifying database
echo "\nTest 4: Connect without database\n";
try {
    $pdo = new PDO('mysql:host=localhost', 'root', 'oil1234');
    echo "✓ Connection successful!\n";
    
    // Try to create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS zirconinsp");
    echo "✓ Database created/verified!\n";
} catch (Exception $e) {
    echo "✗ Connection failed: " . $e->getMessage() . "\n";
}

echo "\nDone testing.\n"; 