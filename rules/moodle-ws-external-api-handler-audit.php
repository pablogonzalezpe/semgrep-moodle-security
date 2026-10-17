<?php
// Fixture for moodle-ws-external-api-handler-audit.
// Positive cases: any class that extends external_api — modern or legacy.
// Audit style surfaces the whole class for review of validate_context +
// require_capability.

// Modern Moodle 4.x pattern: method named execute().
// ruleid: moodle-ws-external-api-handler-audit
class mod_forum_get_discussions extends \core_external\external_api {
    public static function execute_parameters() { return null; }
    public static function execute_returns() { return null; }
    public static function execute(int $forumid, int $page = 0) {
        $params = self::validate_parameters(self::execute_parameters(), [
            'forumid' => $forumid, 'page' => $page,
        ]);
        return [];
    }
}

// Legacy pre-3.1 pattern: method named after the service.
// ruleid: moodle-ws-external-api-handler-audit
class mod_quiz_results_external extends external_api {
    public static function get_quiz_results_parameters() { return null; }
    public static function get_quiz_results_returns() { return null; }
    public static function get_quiz_results($quizid, $userid) {
        global $DB;
        $params = self::validate_parameters(
            self::get_quiz_results_parameters(),
            ['quizid' => $quizid, 'userid' => $userid]
        );
        $results = $DB->get_records('quiz_grades', ['quiz' => $quizid]);
        return ['grades' => $results];
    }
}

// Backslash-prefixed parent class.
// ruleid: moodle-ws-external-api-handler-audit
class local_myplugin_status extends \external_api {
    public static function execute() { return null; }
}

// Non-WS helper class must not match.
// ok: moodle-ws-external-api-handler-audit
class local_helper {
    public static function execute($a, $b) {
        return $a + $b;
    }
}
