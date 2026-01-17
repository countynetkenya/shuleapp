# ADR 0001 — Initial repository structure

Status: proposed

Context

The repository contains a legacy PHP/CodeIgniter-style application. To enable AI-driven maintenance and multi-domain portability we need a small, stable scaffolding of docs, scripts, and agent artifacts.

Decision

Create the following top-level artifacts:

- `docs/` — architecture, runbooks, features, ADRs
- `agents/` — base prompts and checklists
- `scripts/` — audit and generation scripts
- `specs/` — feature and system specs
- `.env.example` — template for environment configuration

Consequences

- No production code is modified.
- Agents will have a reproducible entrypoint for audits and documentation tasks.
