<?php
// Fixture for moodle-sql-injection-get-records-sql-audit (interpolation).

$userid = optional_param('userid', 0, PARAM_INT);
$courseid = optional_param('courseid', 0, PARAM_INT);

// ruleid: moodle-sql-injection-get-records-sql-audit
$rows = $DB->get_records_sql("SELECT * FROM {grade_items} WHERE userid = $userid");

// ruleid: moodle-sql-injection-get-records-sql-audit
$rows = $DB->get_records_sql("SELECT * FROM {grade_items} WHERE userid = {$userid}");

// ruleid: moodle-sql-injection-get-records-sql-audit
$count = $DB->count_records_sql("SELECT COUNT(*) FROM {log} WHERE userid = {$userid}");

// ruleid: moodle-sql-injection-get-records-sql-audit
$ids = $DB->get_fieldset_sql("SELECT id FROM {grade_items} WHERE courseid = $courseid");

// ruleid: moodle-sql-injection-get-records-sql-audit
$one = $DB->get_record_sql("SELECT * FROM {user} WHERE id = $userid");

// ok: moodle-sql-injection-get-records-sql-audit
$rows = $DB->get_records_sql(
    "SELECT * FROM {grade_items} WHERE userid = :uid",
    ['uid' => $userid]
);

// ok: moodle-sql-injection-get-records-sql-audit
$rows = $DB->get_records_sql('SELECT * FROM {user} WHERE confirmed = 1');

// ok: moodle-sql-injection-get-records-sql-audit
$msg = "Hello $userid";
