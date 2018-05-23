<?php

namespace MyBuilder\PhpunitAccelerator;

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListenerDefaultImplementation;

if (!interface_exists('\PHPUnit\Framework\Test')) {
    class_alias('\PHPUnit_Framework_Test', '\PHPUnit\Framework\Test');
}

/**
 * Class TestListener
 * @package MyBuilder\PhpunitAccelerator
 */
class TestListener implements \PHPUnit\Framework\TestListener
{
    use TestListenerDefaultImplementation;

    /**
     * @var IgnoreTestPolicy
     */
    private $ignorePolicy;

    public const PHPUNIT_PROPERTY_PREFIX = 'PHPUnit_';

    /**
     * TestListener constructor.
     * @param IgnoreTestPolicy|null $ignorePolicy
     */
    public function __construct(IgnoreTestPolicy $ignorePolicy = null)
    {
        $this->ignorePolicy = $ignorePolicy ?? new NeverIgnoreTestPolicy();
    }

    /**
     * @param Test $test
     * @param float $time
     */
    public function endTest(Test $test, $time): void
    {
        var_dump($test);

        $testReflection = new \ReflectionObject($test);

        if ($this->ignorePolicy->shouldIgnore($testReflection)) {
            return;
        }

        $this->safelyFreeProperties($test, $testReflection->getProperties());
    }

    /**
     * @param Test $test
     * @param \ReflectionProperty[] $properties
     */
    private function safelyFreeProperties(Test $test, array $properties): void
    {
        array_walk($properties, function (\ReflectionProperty $property) use ($test) {
            if ($this->isSafeToFreeProperty($property)) {
                $this->freeProperty($test, $property);
            }
        });
    }

    /**
     * @param \ReflectionProperty $property
     * @return bool
     */
    private function isSafeToFreeProperty(\ReflectionProperty $property): bool
    {
        return !$property->isStatic() && $this->isNotPhpUnitProperty($property);
    }

    /**
     * @param \ReflectionProperty $property
     * @return bool
     */
    private function isNotPhpUnitProperty(\ReflectionProperty $property): bool
    {
        return 0 !== strpos($property->getDeclaringClass()->getName(), self::PHPUNIT_PROPERTY_PREFIX);
    }

    /**
     * @param Test $test
     * @param \ReflectionProperty $property
     */
    private function freeProperty(Test $test, \ReflectionProperty $property): void
    {
        $property->setAccessible(true);
        $property->setValue($test, null);
    }
}