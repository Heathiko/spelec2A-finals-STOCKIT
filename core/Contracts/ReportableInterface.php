<?php

declare(strict_types=1);

namespace Core\Contracts;
// This interface defines a contract for generating reports--og gamiton need mureport sharo
interface ReportableInterface
{
    public function generateReport(): array;
}


