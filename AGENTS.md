# AI Agent Workflow & Beta Branching

## Branching Strategy
- **main**: Stable, production-ready code.
- **beta**: Integration branch for new features and testing. All PRs target `beta` first.
- **feature/xxx**: Short-lived branches for specific tasks (deleted after merge).

## Error Tracking
- Runtime errors are logged to `application/logs/` (or `log.txt` in root if configured).
- Agents should `grep` these logs when debugging.
- Known issues/bugs should be documented in `docs/BUGS.md`.

## Portability Rules
- **Configuration**: Use `getenv()` for all credentials and environment-specific paths.
- **Database**: Do not hardcode connection details. Use `mvc/config/database.php` with ENV vars.
- **Paths**: Use `FCPATH` or `APPPATH` constants, never absolute paths like `/var/www`.

## Performance
- **Lazy Loading**: Use `$this->load->library()` only when needed, not in constructors.
- **Assets**: Minify CSS/JS when deploying.
- **Caching**: Utilize CodeIgniter's caching drivers where appropriate.
