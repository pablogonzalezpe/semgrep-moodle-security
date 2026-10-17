<?php
// Fixture for moodle-exception-message-leak-audit.

// Direct variant: getMessage() concatenated into header.
try {
    do_something();
} catch (Exception $e) {
    // ruleid: moodle-exception-message-leak-audit
    header('HTTP/1.0 500 Internal Server Error. ' . $e->getMessage());
}

try {
    do_something_else();
} catch (Exception $e) {
    // ruleid: moodle-exception-message-leak-audit
    echo 'Error: ' . $e->getMessage();
}

try {
    do_more();
} catch (Exception $e) {
    // ruleid: moodle-exception-message-leak-audit
    print 'Failure: ' . $e->getMessage();
}

try {
    do_final();
} catch (Exception $e) {
    // ruleid: moodle-exception-message-leak-audit
    die($e->getMessage());
}

try {
    safe_path();
} catch (Exception $e) {
    // ok: moodle-exception-message-leak-audit
    debugging($e->getMessage(), DEBUG_DEVELOPER);
    header('HTTP/1.0 500 Internal Server Error');
}

try {
    other_safe_path();
} catch (Exception $e) {
    // ok: moodle-exception-message-leak-audit
    log_internal($e->getMessage());
}

// Indirect assignment variant: getMessage() stored in variable then used outside catch.
// ruleid: moodle-exception-message-leak-audit
try {
    process_webhook();
} catch (Exception $e) {
    $msg = 'HTTP/1.0 500 Internal Server Error. ' . $e->getMessage();
    debugging($msg, DEBUG_DEVELOPER);
}
header($msg);
