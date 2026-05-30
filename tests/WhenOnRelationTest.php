<?php

namespace Mashirou1234\LaravelWhenOnRelation\Tests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WhenOnRelationTest extends TestCase
{
    public function test_when_on_relation_callback_receives_relation_instance(): void
    {
        $this->seedData();

        $user = WhenOnRelationTestUser::query()->first();

        $articles = $user->articles()
            ->whenOnRelation(true, function (BelongsToMany $relation, bool $value) {
                $this->assertTrue($value);

                return $relation->wherePivotIn('article_id', [1, 3]);
            })
            ->get();

        $this->assertSame([1, 3], $articles->pluck('id')->all());
    }

    public function test_when_on_relation_default_callback_receives_relation_instance(): void
    {
        $this->seedData();

        $user = WhenOnRelationTestUser::query()->first();

        $articles = $user->articles()
            ->whenOnRelation(false, function () {
                $this->fail('The truthy callback should not be executed.');
            }, function (BelongsToMany $relation, bool $value) {
                $this->assertFalse($value);

                return $relation->wherePivotIn('article_id', [2]);
            })
            ->get();

        $this->assertSame([2], $articles->pluck('id')->all());
    }

    public function test_when_on_relation_higher_order_proxy_applies_relation_method(): void
    {
        $this->seedData();

        $user = WhenOnRelationTestUser::query()->first();

        $articles = $user->articles()
            ->whenOnRelation(true)
            ->wherePivotIn('article_id', [1, 3])
            ->get();

        $this->assertSame([1, 3], $articles->pluck('id')->all());
    }

    public function test_when_on_relation_resolves_condition_closure_with_relation_instance(): void
    {
        $this->seedData();

        $user = WhenOnRelationTestUser::query()->first();

        $articles = $user->articles()
            ->whenOnRelation(function (BelongsToMany $relation) {
                $this->assertSame('article_user', $relation->getTable());

                return [1, 3];
            }, function (BelongsToMany $relation, array $articleIds) {
                return $relation->wherePivotIn('article_id', $articleIds);
            })
            ->get();

        $this->assertSame([1, 3], $articles->pluck('id')->all());
    }

    public function test_when_on_relation_returns_relation_when_callback_returns_null(): void
    {
        $this->seedData();

        $user = WhenOnRelationTestUser::query()->first();

        $articles = $user->articles()
            ->whenOnRelation(true, function (BelongsToMany $relation): void {
                $relation->wherePivotIn('article_id', [2]);
            })
            ->get();

        $this->assertSame([2], $articles->pluck('id')->all());
    }

    public function test_when_callback_still_receives_query_builder(): void
    {
        $this->seedData();

        $user = WhenOnRelationTestUser::query()->first();

        $articles = $user->articles()
            ->when(true, function (Builder $query, $value) {
                $this->assertTrue($value);

                return $query->whereKey([1, 3]);
            })
            ->get();

        $this->assertSame([1, 3], $articles->pluck('id')->all());
    }

    protected function seedData(): void
    {
        $user = WhenOnRelationTestUser::create(['id' => 1, 'email' => 'taylorotwell@gmail.com']);

        WhenOnRelationTestArticle::query()->insert([
            ['id' => 1, 'title' => 'Another title'],
            ['id' => 2, 'title' => 'Another title'],
            ['id' => 3, 'title' => 'Another title'],
        ]);

        $user->articles()->sync([3, 1, 2]);
    }
}

class WhenOnRelationTestUser extends Model
{
    protected $table = 'users';

    protected $fillable = ['id', 'email'];

    public $timestamps = false;

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(WhenOnRelationTestArticle::class, 'article_user', 'user_id', 'article_id');
    }
}

class WhenOnRelationTestArticle extends Model
{
    protected $table = 'articles';

    public $timestamps = false;

    protected $fillable = ['id', 'title'];
}
