# AGENTS.md

Purpose

Define rules and expectations for AI agents working in this repository.

Operating rules (short)

- Always run `scripts/find-domain-lockins.sh` before editing files that may contain domains.
- Make minimal, reversible changes. Prefer adding config-driven behavior over changing many files.
- Update docs under `docs/` and create an ADR for non-trivial decisions in `docs/adr/`.
- Write tests under `tests/` for any behavior you change.
- Never commit secrets. Use `.env` (ignored) or CI secret stores.

Development flow

1. Create or update a spec in `specs/` for the feature/bug.
2. Run `scripts/generate-feature-index.sh` to update `docs/FEATURE_INDEX.md`.
3. Make changes in `mvc/` or `main/` under `src/` mapping if planned.
4. Add or update tests and run `phpunit` (if configured) or local manual checks.

Agent contacts

- Leave a short changelog in commit message with `AGENT:` prefix.
