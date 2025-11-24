### Changelog — CustomFields Module

#### 1.1.1 — 2025-11-23

- Final security review and release prep
  - Re-ran security checklist: escaping (PHP + Smarty), CSRF (token generation and validation), permissions (anonymous blocked by default, admin-only modules supported), uploads hardening (.htaccess present; extension/MIME/size validation; safe mkdir; high-entropy filenames), database safety (casts/quoting; write helpers + transactions), and debug gating (admin-only; escaped output). No high-risk issues found.
  - Documentation sanity pass and README version bump to 1.1.1.
  - Tasks checklist updated to mark final security review and coverage target as completed.

- Architecture & quality
  - Dependency injection seams (context) for DB, UploadService, Validator integrated across public entry points.

- Testing & coverage
  - Added DI injection tests, render/form smoke test, and transaction rollback test.
  - Coverage uplift for include/functions.php and renderers; full suite green.

Upgrade notes
- No schema changes from 1.1.0 to 1.1.1.
- Keep `publisher_debug.php` admin-only or remove in production.

Release
- Tag: v1.1.1
- Suggested commands:
  - git tag -s v1.1.1 -m "CustomFields 1.1.1"
  - git push --tags
  - zip -r customfields-1.1.1.zip modules/customfields -x "modules/customfields/tests/*"
  - certutil -hashfile customfields-1.1.1.zip SHA256 (Windows) or shasum -a 256 customfields-1.1.1.zip (macOS/Linux)

#### 1.1.0 — 2025-11-23

- Security and robustness
  - Consistent output escaping using `htmlspecialchars(..., ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')` across PHP and templates.
  - CSRF tokens added to forms; validation in `customfields_saveData()`.
  - Permission checks: block anonymous saves by default; optional admin-only modules.
  - Uploads hardened: high-entropy filenames, server rules (`uploads/customfields/.htaccess`), size/extension/MIME validation.
  - Admin-only gate for `publisher_debug.php` and safer debug output.

- Architecture & quality
  - Introduced `class/UploadService.php` (size/extension/MIME checks, unique filenames).
  - Introduced `class/FieldTypes.php` constants to remove magic strings.
  - Introduced `class/Logger.php` (PSR‑3 if available; fallback to `error_log`).
  - Introduced `class/Config.php` accessors (upload dir, size, allowlists, date format).
  - Added `customfields_url()` helper for safe absolute URLs.
  - Decoupled formatting via renderer classes (`Text`, `Textarea`, `Select`, `Radio`, `Checkbox`, `Date`, `Image`, `File`) and factory.

- Database
  - Added best‑effort transactions for multi-field saves.
  - Added indexes via `sql/upgrade_100_to_101.sql`.
  - Added query helpers `customfields_dbBegin/Commit/Rollback` and `customfields_dbExec()` with logging.
  - Audited direct SQL for casting/quoting and updated `publisher_debug.php` accordingly.

- Performance & UX
  - In-memory cache for `getFieldsByModule()`; options decode cached per object.
  - Admin lists now support pagination and filters (`manage.php`, `fields.php`).
  - Templates surface per-field validation errors.

- Testing & tooling
  - Expanded PHPUnit suite (renderers, validators, upload service, save/prepare flows, config/date formatting, negative paths).
  - Added PHPCS ruleset and PHPStan (level 3) config for the module.

Upgrade notes
- After updating the module files:
  1. Execute the SQL in `modules/customfields/sql/upgrade_100_to_101.sql` (adjust `{prefix}` to your XOOPS DB prefix) to add helpful indexes.
  2. Review the new configuration constants in the README (max upload size, allowlists, admin-only modules, date display format) and adjust as needed.
  3. For production, ensure `publisher_debug.php` is not publicly accessible (admin-only or removed).
