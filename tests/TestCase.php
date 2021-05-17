<?php

namespace Macmotp\CodegenLaravel\Tests;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $capsule = new Capsule();

        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:'
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        Capsule::schema()->create('foos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code')->unique();
            $table->timestamps();
        });
        Capsule::schema()->create('bars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('reference')->unique();
            $table->timestamps();
        });
    }
}
