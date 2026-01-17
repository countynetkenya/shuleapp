# Environment & Configuration

Purpose

Document environment variables, deployment patterns, and how to make the app domain-agnostic.

Recommended environment variables

- APP_ENV=production|staging|development
- APP_URL (application base URL)
- DB_HOST
- DB_PORT
- DB_NAME
- DB_USER
- DB_PASS
- DB_DRIVER
- SESSION_COOKIE_DOMAIN
- COOKIE_DOMAIN
- SMTP_HOST
- SMTP_USER
- SMTP_PASS
- CACHE_DRIVER
- REDIS_URL
- CACHE_HOST

Guidelines

- Avoid hardcoding domains in views, JS, or PHP — use `APP_URL` or runtime-detected host when needed.
- Use `$_ENV`, `getenv()` or a small bootstrap that maps `.env` into `$_ENV`.
- Keep `production` DB credentials out of repo; use `.env` or secret store.

Files to check

- `index.php`
- `mvc/config/config.php`
- `mvc/config/production/database.php`
- Any JS that references absolute URLs under `assets/` or `frontend/`.

Next steps

- Run `scripts/find-domain-lockins.sh` to create an initial list of hardcoded URLs and domains.
- Replace safe-to-change hardcoded strings with `APP_URL` / env-based config (create small bootstrap loader).
