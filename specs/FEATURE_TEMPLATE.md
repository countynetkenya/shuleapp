---
title: "<short descriptive title>"
slug: "<kebab-case-slug>"
summary: "One-line summary of the feature"
owner: "team-or-person"
tags:
  - "ui"
  - "api"
evidence:
  - path: "routes/web.php"
    excerpt: "GET /students -> StudentController@index"
confidence: 0.0
---

## Description
Brief description (2–4 sentences) explaining the feature and why it exists.

## User flows
1. User opens X
2. User clicks Y
3. System shows Z

## Acceptance criteria
- [ ] Criterion 1 (observable)
- [ ] Criterion 2 (observable)

## Related code / tests
- Route: GET /students -> StudentController@index
- Tests: tests/Feature/StudentTest.php::test_student_listing_shows_name

## Notes
Any implementation notes or open questions for reviewers.
