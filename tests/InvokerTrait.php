<?php
namespace Affinity4\Migrate\Test;

trait InvokerTrait
{
    public function invokeInaccessibleMethod(&$object, $methodName, array $args = [])
    {
        $reflection = new ReflectionMethod(get_class($object), $methodName);
        $reflection->setAccessible(true);

        return $reflection->invokeArgs($args);
    }
}
