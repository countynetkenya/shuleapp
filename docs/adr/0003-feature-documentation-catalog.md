# ADR 0003: Feature Documentation Catalog

## Status
Accepted

## Context
The repository contains per-controller feature documents under `docs/features/`, but
there is no single entry point that links every feature document for easy discovery.
Maintainers need a stable, human-friendly catalog to browse feature documentation
and keep it aligned with controller changes.

## Decision
Add a top-level `docs/FEATURE_CATALOG.md` that lists and links every feature document
in `docs/features/`. Maintain a short `docs/features/README.md` that explains how to
update and extend feature documentation.

## Consequences
- Documentation consumers gain a single catalog to navigate feature docs.
- Contributors have a clear workflow for maintaining feature documentation.
- The catalog should be regenerated or updated when new controllers are added.
