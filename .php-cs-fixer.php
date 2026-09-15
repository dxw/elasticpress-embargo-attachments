<?php

$finder = \PhpCsFixer\Finder::create()
->exclude('vendor')
->in(__DIR__);

return \Dxw\PhpCsFixerConfig\Config::create()
->setRules([
    '@PSR2' => true,
    'array_syntax' => ['syntax' => 'short'],
])

->setFinder($finder);
