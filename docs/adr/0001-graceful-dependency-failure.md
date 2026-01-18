# 0001 - Graceful Failure for Missing Dependencies

## Context
The application relies on `composer` dependencies, specifically for the `Student` controller which uses QuickBooks SDK.
After migration or fresh deployment, if `composer install` is not run, the application crashes with a PHP Fatal Error because `vendor/autoload.php` is missing.

## Decision
We implemented a check in `mvc/controllers/Student.php` (and should apply to others if found) to verify the existence of `vendor/autoload.php` before requiring it.

If the file is missing:
- In `development` environment, we simply `die()` with a clear message to run `composer install`.
- In `production`, we use CodeIgniter's `show_error()` to show a 500 error page, which is more user-friendly than a raw PHP fatal error stack trace.

## Consequences
- **Positive**: Better error experience for admins/devs deploying the app. Prevents "White Screen of Death" or raw PHP errors.
- **Negative**: Adds a file check on every request to `Student` controller (negligible performance impact).

## Status
Accepted
