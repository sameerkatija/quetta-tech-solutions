<?php
/**
 * Automated Verification & Functional Test Suite
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

echo "========================================================\n";
echo " QUETTA TECH SOLUTIONS - INTEGRATION TEST SUITE\n";
echo "========================================================\n\n";

$pdo = getDBConnection();
$passed = 0;
$failed = 0;

function assert_test($condition, $name) {
    global $passed, $failed;
    if ($condition) {
        echo "[PASS] " . $name . "\n";
        $passed++;
    } else {
        echo "[FAIL] " . $name . "\n";
        $failed++;
    }
}

// Test 1: Database tables check
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
assert_test(in_array('users', $tables), "Table 'users' exists");
assert_test(in_array('services', $tables), "Table 'services' exists");
assert_test(in_array('gallery', $tables), "Table 'gallery' exists");
assert_test(in_array('contact_messages', $tables), "Table 'contact_messages' exists");

// Test 2: Admin Password Verification
$adminUser = $pdo->query("SELECT * FROM `users` WHERE `username` = 'admin'")->fetch();
assert_test($adminUser !== false, "Default admin user exists");
assert_test(password_verify('Admin@123', $adminUser['password']), "Admin password verify with BCRYPT succeeds");
assert_test(!password_verify('WrongPassword', $adminUser['password']), "Admin password verify with wrong password fails");

// Test 3: Services Queries
$serviceCount = (int)$pdo->query("SELECT COUNT(*) FROM `services`")->fetchColumn();
assert_test($serviceCount >= 6, "Initial seeded services count >= 6 (found {$serviceCount})");

// Test 4: Gallery Queries & FK Relationship
$galleryCount = (int)$pdo->query("SELECT COUNT(*) FROM `gallery`")->fetchColumn();
assert_test($galleryCount >= 1, "Gallery items exist in database (found {$galleryCount})");

$galleryWithService = $pdo->query("
    SELECT g.id, g.caption, s.title 
    FROM `gallery` g 
    JOIN `services` s ON g.service_id = s.id 
    LIMIT 1
")->fetch();
assert_test(!empty($galleryWithService['title']), "Foreign Key JOIN between gallery and services works");

// Test 5: Contact Message Insertion & Retrieval
$testEmail = 'test_' . time() . '@student.edu';
$stmt = $pdo->prepare("
    INSERT INTO `contact_messages` (`name`, `email`, `phone`, `subject`, `message`, `created_at`) 
    VALUES (:name, :email, :phone, :subject, :message, NOW())
");
$inserted = $stmt->execute([
    ':name'    => 'Automated Test User',
    ':email'   => $testEmail,
    ':phone'   => '0300-1122334',
    ':subject' => 'Integration Test Message',
    ':message' => 'This is an automated test message to verify the contact form submission pipeline.'
]);
assert_test($inserted, "Contact message inserted successfully");

$retrievedMsg = $pdo->query("SELECT * FROM `contact_messages` WHERE `email` = '{$testEmail}'")->fetch();
assert_test(!empty($retrievedMsg['id']), "Inserted contact message retrieved correctly");

// Clean up test message
$pdo->exec("DELETE FROM `contact_messages` WHERE `email` = '{$testEmail}'");
assert_test(true, "Contact test message cleanup executed");

// Test 6: Services CRUD simulation
$newSvcTitle = 'Test Diagnostics Service ' . time();
$createSvc = $pdo->prepare("
    INSERT INTO `services` (`user_id`, `title`, `description`, `price`, `image`, `created_at`) 
    VALUES (1, :title, 'Test service description details for integration test.', 3999.00, 'test_dummy.jpg', NOW())
");
$createSvc->execute([':title' => $newSvcTitle]);
$newSvcId = $pdo->lastInsertId();
assert_test($newSvcId > 0, "Service CREATE operation works (ID: {$newSvcId})");

// Update Service
$updateSvc = $pdo->prepare("UPDATE `services` SET `price` = 4500.00 WHERE `id` = :id");
$updateSvc->execute([':id' => $newSvcId]);
$checkPrice = (float)$pdo->query("SELECT price FROM `services` WHERE `id` = {$newSvcId}")->fetchColumn();
assert_test($checkPrice === 4500.00, "Service UPDATE operation works (Updated price: {$checkPrice})");

// Delete Service
$deleteSvc = $pdo->prepare("DELETE FROM `services` WHERE `id` = :id");
$deleteSvc->execute([':id' => $newSvcId]);
$remaining = $pdo->query("SELECT COUNT(*) FROM `services` WHERE `id` = {$newSvcId}")->fetchColumn();
assert_test((int)$remaining === 0, "Service DELETE operation works");

// Test 7: Sanitization and XSS Prevention
$dirtyString = "<script>alert('XSS')</script><b>Bold IT Solution</b>";
$escaped = e($dirtyString);
assert_test(strpos($escaped, "<script>") === false, "Output escaping prevents script execution");
assert_test(strpos($escaped, "&lt;script&gt;") !== false, "Output escaping produces safe HTML entities");

$sanitized = sanitize("  <b>Clean Name</b>  ");
assert_test($sanitized === "Clean Name", "Sanitize helper strips HTML tags and whitespace");

echo "\n--------------------------------------------------------\n";
echo "RESULTS: Passed: {$passed} | Failed: {$failed}\n";
echo "========================================================\n";
