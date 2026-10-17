# semgrep-moodle-security

Semgrep rules for auditing security patterns in Moodle plugins and core modules.

## Rules

| Rule | What it surfaces | Severity | CWE |
|---|---|---|---|
| `moodle-ws-external-api-handler-audit` | Any class extending `external_api` (web service handler) lacking `validate_context` / `require_capability` | WARNING | CWE-862, CWE-639 |
| `moodle-require-login-audit` | Every `require_login` / `require_course_login` call-site for manual capability-check review | INFO | CWE-862 |
| `moodle-db-mutation-audit` | DML mutations (`update_record`, `delete_records`, `insert_record`, ...) outside a `confirm_sesskey()` guard | INFO | CWE-352 |
| `moodle-sql-injection-get-records-sql-audit` | `$DB->get_records_sql()` and siblings with PHP variable interpolation in the query string | WARNING | CWE-89 |
| `moodle-sql-injection-get-records-sql-concat-audit` | `$DB->get_records_sql()` and siblings with string concatenation in the query string | WARNING | CWE-89 |
| `moodle-exception-message-leak-audit` | `$e->getMessage()` written to `header()`, `echo`, `print`, or `die()` directly or via intermediate variable | WARNING | CWE-209 |

All rules use `subcategory: audit` — they are presence lenses that surface patterns for manual review, not high-confidence detectors. False positives are expected and intentional: the goal is to ensure no security-relevant call-site goes unreviewed.

## Usage

```bash
# Run all rules against a Moodle plugin directory
semgrep --config rules/ /path/to/moodle-plugin/

# Run Semgrep's built-in test suite on the fixtures
semgrep --test rules/
```

## Requirements

- [Semgrep CLI](https://semgrep.dev/docs/getting-started/) (open-source engine, no Pro features required)
- PHP files to scan (Moodle plugin or core module source)

## Rule design

These rules target four vulnerability families common in Moodle plugins:

1. **Broken access control** (CWE-862 / CWE-639) — web service handlers and page scripts that authenticate the caller (`require_login`) but skip capability enforcement (`require_capability`) on user-controlled contexts.
2. **Cross-site request forgery** (CWE-352) — database mutations via the Moodle DML API that execute outside a `confirm_sesskey()` guard.
3. **SQL injection** (CWE-89) — `$DB->get_records_sql()` and sibling methods whose first argument contains PHP interpolation or string concatenation instead of parameterized placeholders.
4. **Information exposure** (CWE-209) — exception messages leaked to HTTP responses via `header()`, `echo`, `print`, or `die()`.

Each rule includes:
- Complete OWASP and CWE metadata
- Moodle-specific path exclusions (vendor, tests, behat, phpunit, CLI scripts, upgrade files)
- Actionable messages explaining what the reviewer should verify
- Test fixtures with annotated true-positive (`# ruleid:`) and true-negative (`# ok:`) cases

## Test results

```
$ semgrep --test rules/
6/6: ✓ All tests passed
```

## License

MIT — see [LICENSE](LICENSE).

## Disclaimer

These rules are audit-oriented and produce false positives by design. A clean scan does not guarantee the absence of vulnerabilities. Use the results as input for manual security review, not as a substitute for it. The author assumes no responsibility for decisions made based on the output of these rules.

## Author

Pablo González Pérez ([@pablogonzalezpe](https://github.com/pablogonzalezpe))
