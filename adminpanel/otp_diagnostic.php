<?php
/**
 * OTP DIAGNOSTIC SCRIPT - safe, read-only, no session/login logic touched.
 * Upload this into the SAME FOLDER as process_newbeta.php, then open it
 * directly in your browser: https://www.roomstatushub.in/sync/adminpanel/otp_diagnostic.php
 *
 * DELETE THIS FILE once you're done - it can reveal config details.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/plain');

echo "=== PHP / ENVIRONMENT ===\n";
echo "PHP version: " . PHP_VERSION . "\n";
echo "curl extension: " . (extension_loaded('curl') ? 'YES' : 'NO - this breaks CheckCaptcha() / anything using curl_init()') . "\n";
echo "mysqli extension: " . (extension_loaded('mysqli') ? 'YES' : 'NO - fatal, nothing DB-related will work') . "\n";
echo "openssl extension: " . (extension_loaded('openssl') ? 'YES' : 'NO - PHPMailer SMTP over SSL/TLS needs this') . "\n";
echo "mail() function exists: " . (function_exists('mail') ? 'YES' : 'NO') . "\n";
echo "random_int() exists: " . (function_exists('random_int') ? 'YES' : 'NO') . "\n";
echo "\n";

echo "=== INCLUDES (same ones process_newbeta.php uses) ===\n";
try {
	include("../config/data.config.php");
	echo "../config/data.config.php: OK\n";
	echo "  LIB_DIR = " . (isset($LIB_DIR) ? $LIB_DIR : '(not set)') . "\n";
	echo "  DB_HOST_APP = " . (isset($DB_HOST_APP) ? $DB_HOST_APP : '(not set)') . "\n";
} catch (Throwable $e) {
	echo "../config/data.config.php: FAILED -> " . $e->getMessage() . "\n";
}

try {
	include("$LIB_DIR/functions.library.php");
	echo "\$LIB_DIR/functions.library.php: OK\n";
} catch (Throwable $e) {
	echo "\$LIB_DIR/functions.library.php: FAILED -> " . $e->getMessage() . "\n";
}

try {
	include("$LIB_DIR/msgs.inc.php");
	echo "\$LIB_DIR/msgs.inc.php: OK\n";
} catch (Throwable $e) {
	echo "\$LIB_DIR/msgs.inc.php: FAILED -> " . $e->getMessage() . "\n";
}

try {
	include("$LIB_DIR/class.database.php");
	echo "\$LIB_DIR/class.database.php: OK\n";
} catch (Throwable $e) {
	echo "\$LIB_DIR/class.database.php: FAILED -> " . $e->getMessage() . "\n";
}

try {
	include("$LIB_DIR/data.constant.php");
	echo "\$LIB_DIR/data.constant.php: OK\n";
	echo "  TBL_USERS = " . (defined('TBL_USERS') ? TBL_USERS : '(not defined)') . "\n";
} catch (Throwable $e) {
	echo "\$LIB_DIR/data.constant.php: FAILED -> " . $e->getMessage() . "\n";
}
echo "\n";

echo "=== MYSQLI ERROR MODE ===\n";
try {
	mysqli_report(MYSQLI_REPORT_OFF);
	// Deliberately run a broken query to see whether it throws or returns false.
	$testConn = @mysqli_connect($DB_HOST_APP, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
	if ($testConn) {
		$res = mysqli_query($testConn, "SELECT * FROM this_table_does_not_exist_xyz");
		echo "Broken query with MYSQLI_REPORT_OFF: " . ($res === false ? "returned false as expected (good)" : "unexpected result") . "\n";
		echo "mysqli error was: " . mysqli_error($testConn) . "\n";
		mysqli_close($testConn);
	} else {
		echo "Could not connect to DB_HOST_APP for this test: " . mysqli_connect_error() . "\n";
	}
} catch (Throwable $e) {
	echo "Broken query THREW an exception even with MYSQLI_REPORT_OFF: " . $e->getMessage() . "\n";
	echo "(this would mean something else re-enables strict mysqli reporting after this point)\n";
}
echo "\n";

echo "=== fs_users OTP COLUMNS ===\n";
try {
	$testConn2 = @mysqli_connect($DB_HOST_APP, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
	if ($testConn2) {
		$res2 = mysqli_query($testConn2, "SHOW COLUMNS FROM `" . (defined('TBL_USERS') ? TBL_USERS : 'fs_users') . "` LIKE 'otp_expiry'");
		if ($res2 && mysqli_num_rows($res2) > 0) {
			echo "otp_expiry column: FOUND\n";
		} else {
			echo "otp_expiry column: NOT FOUND on this DB (DB_HOST_APP) - if this is the DB your login query actually runs against, the migration needs to be run here.\n";
		}
		mysqli_close($testConn2);
	}
} catch (Throwable $e) {
	echo "Column check failed: " . $e->getMessage() . "\n";
}
echo "\n";

echo "=== PHPMAILER AUTOLOAD ===\n";
$candidatePaths = [
	"../config/auto_loader.php",
	"../../config/auto_loader.php",
	"../config/PHPMailer/PHPMailerAutoload.php",
	"../vendor/autoload.php",
];
foreach ($candidatePaths as $path) {
	echo "$path : " . (file_exists($path) ? "EXISTS" : "not found") . "\n";
}
if (!class_exists('PHPMailer')) {
	foreach ($candidatePaths as $path) {
		if (file_exists($path)) {
			try {
				include_once($path);
			} catch (Throwable $e) {
				echo "Including $path threw: " . $e->getMessage() . "\n";
			}
		}
	}
}
echo "class_exists('PHPMailer') after attempting includes: " . (class_exists('PHPMailer') ? 'YES' : 'NO') . "\n";
echo "class_exists('PHPMailer\\\\PHPMailer\\\\PHPMailer') (namespaced v6): " . (class_exists('PHPMailer\\PHPMailer\\PHPMailer') ? 'YES' : 'NO') . "\n";

echo "\n=== DONE - delete this file now ===\n";