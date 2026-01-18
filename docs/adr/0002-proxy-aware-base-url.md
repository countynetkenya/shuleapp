# 0002 - Proxy-aware base_url resolution

Date: 2025-01-01

## Status
Accepted

## Context
After moving the app to `app.shulelabs.cloud`, some routes like `/student` appeared broken. When `APP_URL` is unset and the app is behind a reverse proxy, `$_SERVER['HTTPS']` and `$_SERVER['HTTP_HOST']` can reflect internal values. This leads to incorrect `base_url` output and cookies bound to the wrong host.

## Decision
Update `mvc/config/config.php` to prefer `HTTP_X_FORWARDED_PROTO` and `HTTP_X_FORWARDED_HOST` when `APP_URL` is not set, and derive `cookie_domain` from the resolved base URL when `COOKIE_DOMAIN` is empty.

## Consequences
- Links and redirects resolve to the correct external domain behind proxies.
- Cookies default to the correct host when `COOKIE_DOMAIN` is unset.
- Deployments should continue to set `APP_URL` explicitly in production for clarity.
