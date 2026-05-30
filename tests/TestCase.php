<?php

namespace Mashirou1234\LaravelWhenOnRelation\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mashirou1234\LaravelWhenOnRelation\WhenOnRelationServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            WhenOnRelationServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
        });

        Schema::create('article_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id')->unsigned();
            $table->integer('user_id')->unsigned();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('article_user');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('users');

        parent::tearDown();
    }
}
