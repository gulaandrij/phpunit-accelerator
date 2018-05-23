<?php

namespace MyBuilder\PhpunitAccelerator;

/**
 * Class NeverIgnoreTestPolicy
 *
 * @package MyBuilder\PhpunitAccelerator
 */
class NeverIgnoreTestPolicy implements IgnoreTestPolicy
{
    /**
     *
     * @param \ReflectionObject $testReflection
     *
     * @return bool
     */
    public function shouldIgnore(\ReflectionObject $testReflection): bool
    {
        return false;
    }
}