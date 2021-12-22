<?php
declare(strict_types=1);


namespace Setono\SyliusGoogleOptimizePlugin\ValueObject;


final class Variant
{
    private string $code;

    private int $position;

    private bool $original;

    public function __construct(string $code, int $position, bool $original)
    {
        $this->code = $code;
        $this->position = $position;
        $this->original = $original;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function isOriginal(): bool
    {
        return $this->original;
    }
}
