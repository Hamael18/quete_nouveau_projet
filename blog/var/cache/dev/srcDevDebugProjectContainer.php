<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerHpNT7H0\srcDevDebugProjectContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerHpNT7H0/srcDevDebugProjectContainer.php') {
    touch(__DIR__.'/ContainerHpNT7H0.legacy');

    return;
}

if (!\class_exists(srcDevDebugProjectContainer::class, false)) {
    \class_alias(\ContainerHpNT7H0\srcDevDebugProjectContainer::class, srcDevDebugProjectContainer::class, false);
}

return new \ContainerHpNT7H0\srcDevDebugProjectContainer(array(
    'container.build_hash' => 'HpNT7H0',
    'container.build_id' => '3eeb0884',
    'container.build_time' => 1540822280,
), __DIR__.\DIRECTORY_SEPARATOR.'ContainerHpNT7H0');
