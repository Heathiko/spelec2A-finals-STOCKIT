<?php

declare(strict_types=1);

namespace Core\Contracts;
//to ensure that read-only things dont get mixed up and required tings they dont need
interface Findable {

    /**  where there are integerss */
    public function all():array;

    /**where theres text or no text*/
    public function find(int $id): ?array;


}