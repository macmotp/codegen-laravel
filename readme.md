# Code Generator - Laravel Extension

[![Latest Version on Packagist](https://img.shields.io/packagist/v/macmotp/codegen-laravel.svg)](https://packagist.org/packages/macmotp/codegen-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/macmotp/codegen-laravel.svg)](https://packagist.org/packages/macmotp/codegen-laravel)

**Generate human friendly codes**

Useful for generation of referral codes based on names, receipt numbers, unique references.

#### This is the Laravel extension for the package [Codegen - Generate Human Friendly Codes](https://github.com/macmotp/codegen)

## Requirements
- PHP >= 7.4
- Laravel >= 5

## Installation

You can install the package via composer:

```bash
composer require macmotp/codegen-laravel
```

## Usage

#### Create semantic and sanitized reference codes from any model by applying the trait
``` php
use Illuminate\Database\Eloquent\Model;
use Macmotp\HasCodegen;

class User extends Model
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
```

#### On the `creating` event, it will generate a human readable code to the specified column
``` php
$user = User::create([
    'name' => 'Bob McLovin',
]);

dump($user->code);
// (string) 'BBMCLV';
```

## Configuration
#### Publish default configuration
Create `config/codegen.php` file, where you can adjust a few settings:

``` php
<?php
// config for Macmotp/HasCodegen
return [
    /*
    |--------------------------------------------------------------------------
    | The attribute of the model to build the code from.
    | For example, if your model has a column 'name' you can build the code from this attribute.
    | If empty, will generate random codes.
    |--------------------------------------------------------------------------
    */
    'build-from' => '',

    /*
    |--------------------------------------------------------------------------
    | The column use to save the code into the model.
    |--------------------------------------------------------------------------
    */
    'code-column' => 'code',

    /*
    |--------------------------------------------------------------------------
    | The length of the code to generate.
    |--------------------------------------------------------------------------
    */
    'code-length' => 6,

    /*
    |--------------------------------------------------------------------------
    | Sanitize level.
    | 1. Low/Default: will filter out anything is not a letter or a digit;
    | 2. Medium: will filter out (O - 0 - Q - I - 1) characters;
    | 3. High: will filter out (2 - Z - 4 - A - 5 - S - 8 - B - U - V - Y) characters;
    | Levels are inclusive, e.g. the highest level will apply also regex of level low and medium.
    |--------------------------------------------------------------------------
    */
    'sanitize-level' => 1,

    /*
    |--------------------------------------------------------------------------
    | Prepend a string.
    |--------------------------------------------------------------------------
    */
    'prepend' => '',
    
    /*
    |--------------------------------------------------------------------------
    | Append a string.
    |--------------------------------------------------------------------------
    */
    'append' => '',

    /*
    |--------------------------------------------------------------------------
    | Maximum accepted number of attempts for the generation.
    |--------------------------------------------------------------------------
    */
    'max-attempts' => 10000,
];

```

#### Custom configuration per model
Override custom configuration for a single model.

```php
use Illuminate\Database\Eloquent\Model;
use Macmotp\HasCodegen;

class Foo extends Model
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
        return 12;
    }
    
    /**
     * Force to prepend this portion of string in the code
     *
     * @return string
     */
    protected function prependToCode(): string
    {
        return 'PR';
    }
    
    /**
     * Force to append this portion of string in the code
     *
     * @return string
     */
    protected function appendToCode(): string
    {
        return 'AP';
    }
    
    /**
     * Get the sanitize level to apply
     *
     * @return int
     */
    protected function getCodeSanitizeLevel(): int
    {
        return 3; // Level High
    }
}
```


## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](changelog.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/contributing.md) for details.

## Security Vulnerabilities

Please review [our security policy](.github/security.md) on how to report security vulnerabilities.

## Credits

- [Marco Gava](https://github.com/macmotp)

## License

The MIT License (MIT). Please see [License File](license.md) for more information.
