<?php

namespace harpya\kdb\adapters;

interface AdapterInterface
{
    public function defineClass($name, $attributes=[], $props=[]);

    public function defineAssociationType($classesOrigin, $classesTarget, $type, $attributes=[], $props=[]);
}
