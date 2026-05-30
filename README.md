# Laravel When On Relation

This package adds a `whenOnRelation()` macro to Eloquent relationship instances.

## Installation

```bash
composer require mashirou1234/laravel-when-on-relation
```

The service provider is registered automatically by Laravel package discovery.

## Usage

It is intended for cases where a conditional callback needs the relationship
instance itself:

```php
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

$user->posts()
    ->whenOnRelation($condition, function (BelongsToMany $relation) {
        return $relation->wherePivotIn('post_id', [1, 2]);
    });
```

The callback receives the relationship instance and the resolved condition
value:

```php
$user->posts()
    ->whenOnRelation($roleId, function (BelongsToMany $relation, int $roleId) {
        return $relation->wherePivot('role_id', $roleId);
    });
```

This package does not add a query builder method that accepts a relationship
name:

```php
// Not supported:
User::query()->whenOnRelation('posts', ...);
```

## Why

Calling `when()` on an Eloquent relationship is forwarded to the underlying
Eloquent builder. That keeps existing callbacks working, but it means the
callback receives an `Illuminate\Database\Eloquent\Builder` instead of the
relationship instance.

`whenOnRelation()` leaves the existing `when()` behavior unchanged and provides
an opt-in helper for relation-specific methods such as `wherePivotIn()`.

## Requirements

- PHP 8.3 or higher
- Laravel 13.x / Illuminate 13.x

## Testing

```bash
composer test
```

Run `composer lint` to check formatting.

Please see [CONTRIBUTING.md](CONTRIBUTING.md) before proposing behavior changes.
Security reporting is covered in [SECURITY.md](SECURITY.md).

## License

The MIT License (MIT).
