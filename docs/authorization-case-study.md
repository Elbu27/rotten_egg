# Authorization consistency and registration role validation

## Context

Rotten Egg uses Laravel policies for movie management. This change turns two authorization findings into explicit HTTP-level regression tests. It also documents the test setup so another developer can reproduce the results.

## Findings in the original code

1. `MoviePolicy::create()` permits administrators and producers, but the creation-page controller and movie-list button each check only `isProducer()`. An administrator can pass the policy yet be rejected by the page. The POST route does not contain the same additional controller restriction.
2. The registration form offers `user` and `producer`, but the controller writes the submitted `role` without validating its allowed values. Form options are not a server-side authorization boundary.

## Decisions

- Keep `MoviePolicy` as the single source of truth for movie-creation permission. The existing `can:create` route middleware enforces it; the view uses `@can` for the matching button.
- Retain the application's existing self-registration choices (`user`, `producer`). Reject `admin` and malformed/unknown roles on the server. Default an omitted role to `user`.
- Preserve existing ownership/admin permissions for movie updates and deletion.
- Test through application routes, including database state after rejected writes. Do not replace the policy with a test double.

## Regression coverage

| Boundary | Evidence |
| --- | --- |
| Guest attempts to create/edit/delete | Login redirect and no unauthorized database changes |
| Ordinary user attempts to create | 403 and no movie inserted |
| Producer/admin creates | Page/button available, POST succeeds |
| Submitted owner ID differs from session | New movie belongs to the authenticated account |
| Non-owner edits/deletes | 403 and original movie attributes preserved |
| Owner/admin edits/deletes | Allowed and database updated/deleted |
| Public registration roles | User/producer accepted; omitted role defaults to user |
| Privileged or malformed registration role | Validation error, guest session and no account created |

## Reproduce

Follow the README setup, then run:

```bash
php artisan test --filter=MovieAuthorizationTest
php artisan test --filter=RegistrationRoleTest
php artisan test
```

The tests use an in-memory SQLite database. No production data, cloud account or externally hosted target is involved.

## Validation record

Executed locally on 2026-09-20 using PHP 8.3.6, PHPUnit 11.5.43 and the committed dependency lockfiles:

| Check | Result |
| --- | --- |
| New regression tests against the original application code at `1a09643` (with isolated SQLite test configuration) | 5 failed, 13 passed; failures reproduced admin-page denial and missing/invalid registration-role handling |
| Full suite after fixes and test-harness updates | **43 passed, 153 assertions** |
| `npm ci` and `npm run build` | Passed; existing browser-data freshness warnings remain |
| Fresh local SQLite migrations and storage link | Passed |
| `git diff --check` | Passed |

The full-suite run also identified three old test-harness issues: login and registration expected `/dashboard` although the existing controllers redirect to `/movies`, and the homepage smoke test lacked database setup. Their expectations/setup were corrected without changing the application's redirect behaviour.

GitHub Actions results are reported separately on the pull request. These local results are not a claim that CI has already completed.

## Limits

These tests verify selected authorization boundaries. They do not establish full security, MySQL equivalence, production suitability, deployment reliability or coverage of every application feature. Dependency upgrades, email-verification requirements and unfinished comment operations need separate review.
