<?php
$basePath = __DIR__ . '/..';
$loader = require_once $basePath . '/../vendor/autoload.php';
$loader->setPsr4('Simovative\\Skeleton\\', __DIR__ . '/../bundles');

$masterFactory = new Simovative\Zeus\Dependency\MasterFactory(new \Simovative\Zeus\Configuration\Configuration(array(), $basePath));
$kernel = new \Simovative\Skeleton\Application\SkeletonKernel($masterFactory);
$kernel->run(\Simovative\Zeus\Http\Request\HttpRequest::createFromGlobals());
