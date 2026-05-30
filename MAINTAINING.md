# Maintaining

## GitHub Actions

This repository runs a small PHPUnit workflow for pushes to `main` and pull
requests.

Before enabling Actions on a public repository, use these repository settings:

- Set workflow permissions to read-only.
- Disable "Allow GitHub Actions to create and approve pull requests".
- Require approval for workflow runs from all outside collaborators.
- Do not use self-hosted runners for pull requests from forks.

The workflow intentionally avoids `pull_request_target`, does not use secrets,
sets `permissions: contents: read`, pins third-party actions to commit SHAs,
cancels older runs for the same branch or pull request, and has a short timeout.

When reviewing a pull request from a fork, inspect changes to `.github/workflows`
before approving workflow runs.

## Releases

Before tagging a release, run:

```bash
COMPOSER_ROOT_VERSION=dev-main composer validate --strict
COMPOSER_ROOT_VERSION=dev-main composer lint
COMPOSER_ROOT_VERSION=dev-main composer test
COMPOSER_ROOT_VERSION=dev-main composer audit
```

Update `CHANGELOG.md` with the released version and date, then tag the release
from `main`.
