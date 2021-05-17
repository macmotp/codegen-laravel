<?php

namespace Macmotp;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasCodegen
{
    /**
     * Get the code.
     *
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->getAttribute($this->getCodeColumn());
    }

    /**
     * Fetch Model by Code
     *
     * @param string $code
     *
     * @return self
     */
    public static function findByCode(string $code): ?self
    {
        return self::where((new static())->getCodeColumn(), $code)->first();
    }

    /**
     * Boot trait
     */
    protected static function bootHasCodegen()
    {
        static::creating(static::getCreatingCodegenHandler());
    }

    /**
     * Create a code when a Model is in the creating boot event
     *
     * @return Closure
     */
    protected static function getCreatingCodegenHandler(): Closure
    {
        return function (Model $model) {
            if (empty($model->getCode())) {
                $generator = new Codegen();
                $generator->setSanitizeLevel($model->getCodeSanitizeLevel())
                    ->setMaxAttempts($model->getCodeMaxAttempts())
                    ->setCodeLength($model->getCodeLength())
                    ->prepend($model->prependToCode())
                    ->append($model->appendToCode());
                do {
                    $code = $model->generateCode($generator);
                } while ($model->newQueryWithoutScopes()->where($model->getCodeColumn(), $code)->exists());

                $model->{$model->getCodeColumn()} = $code;
            }
        };
    }

    /**
     * Generate a human readable code from a given string.
     *
     * @param Codegen $generator)
     *
     * @return string
     */
    protected function generateCode(Codegen $generator): string
    {
        return $generator->generate($this->buildCodeFrom());
    }

    /**
     * Attribute of the model used to generate the code
     *
     * @return string
     */
    protected function buildCodeFrom(): string
    {
        return $this->{config('codegen.build-from')} ?? 'name';
    }

    /**
     * Column used to save the unique code
     *
     * @return string
     */
    protected function getCodeColumn(): string
    {
        return config('codegen.code-column') ?? 'code';
    }

    /**
     * Get char length of the  code
     *
     * @return int
     */
    protected function getCodeLength(): int
    {
        return config('codegen.code-length') ?? 6;
    }

    /**
     * Force to prepend this portion of string in the code
     *
     * @return string
     */
    protected function prependToCode(): string
    {
        return config('codegen.prepend') ?? '';
    }

    /**
     * Force to append this portion of string in the code
     *
     * @return string
     */
    protected function appendToCode(): string
    {
        return config('codegen.append') ?? '';
    }

    /**
     * Get the sanitize level to apply
     *
     * @return int
     */
    protected function getCodeSanitizeLevel(): int
    {
        return config('codegen.sanitize-level') ?? 1;
    }

    /**
     * Get the maximum number of attempts
     *
     * @return int
     */
    protected function getCodeMaxAttempts(): int
    {
        return config('codegen.max-attempts') ?? 10000;
    }
}
