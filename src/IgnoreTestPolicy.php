<?php

namespace MyBuilder\PhpunitAccelerator;

/**
 * Interface IgnoreTestPolicy
 *
 * @package MyBuilder\PhpunitAccelerator
 */
interface IgnoreTestPolicy
{
    /**
     * @param \ReflectionObject $testReflection
     *
     * @return boolean
     */
    public function shouldIgnore(\ReflectionObject $testReflection): bool;
}
