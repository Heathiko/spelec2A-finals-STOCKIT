<?php

declare(strict_types=1);

namespace Core\Contracts;

interface Persistable{
    //for the things that can be manipulated--basically non read-only actions/items//
    public function create(array $data): int;
    public function update(int $id, array $data): bool;  //if successful change
    public function delete(int $id): bool; //of nadelete ba o wa
}
