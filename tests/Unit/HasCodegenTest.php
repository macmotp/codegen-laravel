<?php

namespace Macmotp\CodegenLaravel\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Macmotp\CodegenLaravel\Tests\TestCase;
use Macmotp\HasCodegen;

/**
 * @group CodegenLaravel
 */
class HasCodegenTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        config()->set('codegen.build-from', 'name');
        config()->set('codegen.code-column', 'code');
        config()->set('codegen.code-length', 6);
    }

    public function testHasCodegenTrait()
    {
        config()->set('codegen.prepend', 'PR');
        $model = Foo::create([
            'name' => 'Bob McLovin',
        ]);

        $this->assertIsString($model->getCode());
        $this->assertTrue(strlen($model->getCode()) === 6);
        $this->assertTrue(substr($model->getCode(), 0, 2) === 'PR');
    }

    public function testTraitGuaranteesUniqueness()
    {
        Foo::create([
            'name' => 'Bob McLovin',
            'code' => 'BOMCLI',
        ]);

        Foo::create([
            'name' => 'Bob McLovin',
        ]);
        Foo::create([
            'name' => 'Bob McLovin',
        ]);
        Foo::create([
            'name' => 'Bob McLovin',
        ]);

        $this->assertCount(4, Foo::all()->pluck('code')->toArray());
    }

    public function testTraitMaxAttempts()
    {
        config()->set('codegen.max-attempts', 2);
        Foo::create([
            'name' => 'Bob McLovin',
        ]);
        Foo::create([
            'name' => 'Bob McLovin',
        ]);

        $model = Foo::create([
            'name' => 'Bob McLovin',
        ]);
        $this->assertIsString($model->getCode());
        $model = Foo::create([
            'name' => 'Bob McLovin',
        ]);
        $this->assertIsString($model->getCode());
        $model = Foo::create([
            'name' => 'Bob McLovin',
        ]);
        $this->assertIsString($model->getCode());
    }

    public function testTraitIsConfigurableFromModel()
    {
        $model = Bar::create([
            'title' => 'The Great Gatsby',
        ]);

        $this->assertIsString($model->getCode());
        $this->assertTrue(strlen($model->getCode()) === 9);
        $this->assertTrue(substr($model->getCode(), 0, 1) === 'P');
        $this->assertTrue(substr($model->getCode(), -1, 1) === 'A');
    }
}

class Foo extends Model
{
    use HasCodegen;

    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * Attribute of the model used to generate the code
     *
     * @return string
     */
    protected function buildCodeFrom(): string
    {
        return $this->name;
    }
}

class Bar extends Model
{
    use HasCodegen;

    protected $fillable = [
        'title',
        'reference',
    ];

    /**
     * Attribute of the model used to generate the code
     *
     * @return string
     */
    protected function buildCodeFrom(): string
    {
        return $this->title;
    }

    /**
     * Column used to save the unique code
     *
     * @return string
     */
    protected function getCodeColumn(): string
    {
        return 'reference';
    }

    /**
     * Get char length of the  code
     *
     * @return int
     */
    protected function getCodeLength(): int
    {
        return 9;
    }

    /**
     * Force to prepend this portion of string in the code
     *
     * @return string
     */
    protected function prependToCode(): string
    {
        return 'P';
    }

    /**
     * Force to append this portion of string in the code
     *
     * @return string
     */
    protected function appendToCode(): string
    {
        return 'A';
    }

    /**
     * Get the sanitize level to apply
     *
     * @return int
     */
    protected function getCodeSanitizeLevel(): int
    {
        return 2;
    }
}
