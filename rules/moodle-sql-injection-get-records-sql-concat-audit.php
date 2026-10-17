<?php
// Fixture for moodle-sql-injection-get-records-sql-concat-audit (string concat).

$search = optional_param('search', '', PARAM_RAW);
$where = 'WHERE confirmed = 1';

// ruleid: moodle-sql-injection-get-records-sql-concat-audit
$rows = $DB->get_record_sql("SELECT * FROM {user} WHERE username = '" . $search . "'");

// ruleid: moodle-sql-injection-get-records-sql-concat-audit
$rows = $DB->get_records_sql('SELECT * FROM {user} ' . $where);

// ruleid: moodle-sql-injection-get-records-sql-concat-audit
$ids = $DB->get_fieldset_sql("SELECT id FROM {grade_items} " . $where);

// ok: moodle-sql-injection-get-records-sql-concat-audit
$rows = $DB->get_records_sql(
    'SELECT * FROM {user} WHERE username = :u',
    ['u' => $search]
);

// ok: moodle-sql-injection-get-records-sql-concat-audit
$rows = $DB->get_records_sql('SELECT * FROM {user} WHERE confirmed = 1');
