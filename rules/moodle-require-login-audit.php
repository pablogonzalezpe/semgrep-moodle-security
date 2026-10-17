<?php
// Fixture for moodle-require-login-audit.
// Positive cases: top-level scripts calling require_login (variants).
// Audit style: each call must be surfaced for the reviewer to confirm
// require_capability follows.

$courseid = required_param('id', PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$cm = get_coursemodule_from_id('assign', $courseid, 0, false, MUST_EXIST);

// ruleid: moodle-require-login-audit
require_login($course, true, $cm);

$context = context_module::instance($cm->id);
$renderer = $PAGE->get_renderer('mod_assign');
echo $renderer->render_submissions_page($page);

// ruleid: moodle-require-login-audit
require_login();

// ruleid: moodle-require-login-audit
require_course_login($course, true, $cm);

// ok: moodle-require-login-audit
// Function definition is not a call-site; must not match.
function describe_require_login_contract() {
    return 'documented';
}
