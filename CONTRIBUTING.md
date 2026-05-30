# Contributing

Thank you for considering a contribution.

## Scope

This package intentionally stays small. It only adds a `whenOnRelation()` macro
to Eloquent relationship instances.

Please avoid changes that:

- alter Laravel's existing `when()` behavior;
- add `whenRelation()` as an alias;
- add a query builder API that accepts a relationship name.

Those are separate design choices and should be discussed before implementation.

## Local checks

Run these before opening a pull request:

```bash
composer validate --strict
composer lint
composer test
```

## Pull requests

Keep pull requests focused and include tests for behavior changes. Documentation
changes should include an example when the behavior can be misread from the
method name alone.
