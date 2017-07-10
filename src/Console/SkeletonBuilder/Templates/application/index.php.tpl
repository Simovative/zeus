<?php
$basePath = realpath(__DIR__ . '/..');
$loader = require_once $basePath . '/vendor/autoload.php';
$loader->setPsr4('{{escapedNamespace}}\\', __DIR__ . '/../bundles');

$masterFactory = new Simovative\Zeus\Dependency\MasterFactory(new \Simovative\Zeus\Configuration\Configuration(array(), $basePath));
$kernel = new \{{namespace}}\Application\{{prefix}}Kernel($masterFactory);
$kernel->run(\Simovative\Zeus\Http\Request\HttpRequest::createFromGlobals());
