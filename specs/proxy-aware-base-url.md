# Spec: Proxy-aware base_url for domain migration

## Summary
Ensure `base_url` and cookie domain resolve correctly behind reverse proxies so links like `/student` work after moving to `app.shulelabs.cloud`.

## Problem
When `APP_URL` is not set and the app is deployed behind a proxy, `$_SERVER['HTTPS']` and `$_SERVER['HTTP_HOST']` can reflect upstream/internal values. This can generate incorrect absolute URLs and cookies bound to the wrong host, leading to broken links or login loops.

## Goals
- Honor `HTTP_X_FORWARDED_PROTO` and `HTTP_X_FORWARDED_HOST` when `APP_URL` is unset.
- Derive a safe cookie domain from the resolved base URL when `COOKIE_DOMAIN` is unset.
- Add a regression test to validate the configuration behavior.

## Non-Goals
- Changing application routing or auth logic.
- Updating any domain values in existing content or data stores.

## Implementation Notes
- Keep changes limited to configuration logic in `mvc/config/config.php`.
- Include a small PHP test under `tests/` to validate expected outputs.
- Update environment configuration docs with proxy guidance.

## Testing
- Run `php tests/base_url_test.php`.
