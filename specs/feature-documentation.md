# Spec: Feature Documentation Catalog

## Goal
Create a single, navigable catalog that links to every feature document under
`docs/features/`, making it easy to discover and maintain documentation for all
controllers in the app.

## Scope
- Generate a new `docs/FEATURE_CATALOG.md` containing links to all feature docs.
- Add guidance in `docs/features/README.md` on how to maintain feature docs.
- Refresh `docs/FEATURE_INDEX.md` via `scripts/generate-feature-index.sh`.

## Out of scope
- Deep content updates for every individual feature document in this change.
- Behavioral or runtime code changes.

## Acceptance criteria
- `docs/FEATURE_CATALOG.md` lists every `docs/features/*.md` document with a link.
- `docs/features/README.md` explains how to update feature docs.
- `docs/FEATURE_INDEX.md` is up to date after running the script.
