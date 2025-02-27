<?php
function getDB() {
    global $db;
    
    // Manually read the .env file
    $envPath = __DIR__ . '/.env'; // Ensure correct path
    if (!file_exists($envPath)) {
        die("Error: .env file not found.");
    }

    $envContents = file_get_contents($envPath);
    preg_match('/DB_URL="(.+)"/', $envContents, $matches);

    if (!isset($matches[1])) {
        die("Error: DB_URL not found in .env file.");
    }

    // Parse the DB_URL
    $url = parse_url($matches[1]);
    if (!$url) {
        die("Error: Invalid DB_URL format.");
    }

    $db_host = $url["host"] ?? "localhost";
    $db_user = $url["user"] ?? "root";
    $db_pass = $url["pass"] ?? "";
    $db_name = isset($url["path"]) ? ltrim($url["path"], '/') : "test_db";

    // Check if already connected
    if (!isset($db)) {
        try {
            $connection_string = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
            $db = new PDO($connection_string, $db_user, $db_pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Enable error mode
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Fetch associative arrays
            ]);
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }
    
    return $db;
}
?>
