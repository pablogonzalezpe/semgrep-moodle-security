<?php
// Fixture for moodle-db-mutation-audit.
// Audit style: every direct DML mutation is surfaced for review. Mutations
// that are structurally enclosed by a confirm_sesskey() branch or by a
// function that calls confirm_sesskey()/require_sesskey() are excluded,
// because those scopes are bounded and cannot mask an unrelated sibling.

$action = required_param('action', PARAM_ALPHA);
$id = required_param('id', PARAM_INT);

// ruleid: moodle-db-mutation-audit
$DB->update_record('mdl_grade_items', (object)['id' => $id, 'hidden' => 1]);

// ruleid: moodle-db-mutation-audit
$DB->delete_records('mdl_grade_items', ['courseid' => $id]);

// ruleid: moodle-db-mutation-audit
$DB->delete_records_select('mdl_grade_items', 'courseid = :cid', ['cid' => $id]);

// ruleid: moodle-db-mutation-audit
$DB->insert_record('mdl_log', (object)['action' => $action]);

// ruleid: moodle-db-mutation-audit
$DB->set_field('mdl_user', 'confirmed', 1, ['id' => $id]);

// ruleid: moodle-db-mutation-audit
$DB->set_field_select('mdl_user', 'confirmed', 1, 'id = :id', ['id' => $id]);

// ruleid: moodle-db-mutation-audit
$DB->execute("UPDATE {grade_items} SET hidden = 1 WHERE courseid = ?", [$id]);

// Bounded guard: mutation inside the positive confirm_sesskey() branch.
if (confirm_sesskey()) {
    // ok: moodle-db-mutation-audit
    $DB->set_field('mdl_user', 'confirmed', 1, ['id' => $id]);
}

// Bounded guard: mutation inside a method whose body calls require_sesskey().
class grade_action_handler extends external_api {
    public static function execute($id) {
        global $DB;
        require_sesskey();
        // ok: moodle-db-mutation-audit
        $DB->update_record('mdl_grade_items', (object)['id' => $id]);
    }
}

// Bounded guard: mutation inside a function whose body calls confirm_sesskey().
function purge_sessions($userid) {
    global $DB;
    confirm_sesskey();
    // ok: moodle-db-mutation-audit
    $DB->delete_records('mdl_sessions', ['userid' => $userid]);
}

// No sesskey anywhere in this function: still surfaced.
function silent_purge($userid) {
    global $DB;
    // ruleid: moodle-db-mutation-audit
    $DB->delete_records('mdl_sessions', ['userid' => $userid]);
}

// ok: moodle-db-mutation-audit
$row = $DB->get_record('mdl_user', ['id' => $id]);

// ok: moodle-db-mutation-audit
$rows = $DB->get_records('mdl_user', ['confirmed' => 1]);
