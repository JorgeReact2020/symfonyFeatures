<?php
declare(strict_types=1);
namespace App\Service\Quiz\Interface;

interface ScalableInterface
{
    public function getMin(): int;
    public function getMax(): int;
}
