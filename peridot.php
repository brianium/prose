<?php
use Evenement\EventEmitterInterface;
use Peridot\Console\Environment;
use Peridot\Plugin\Prophecy\ProphecyPlugin;

return function (EventEmitterInterface $emitter) {
    //add prophecy support
    $prophecy = new ProphecyPlugin($emitter);

    $emitter->on('peridot.start', function (Environment $env) {
        $definition = $env->getDefinition();
        $definition->getArgument('path')->setDefault('specs');
    });
};
