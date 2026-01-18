<?php
// Router for PHP Built-in Server
if (is_file($_SERVER["SCRIPT_FILENAME"])) {
    return false; // Serve static file
} else {
    // If it's a directory (like /frontend), pass to index.php so CI can handle the controller
    require_once "index.php"; 
}
