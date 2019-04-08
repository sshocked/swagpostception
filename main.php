<?php
$loader = require 'vendor/autoload.php';
$loader->add('swagpostception\\', __DIR__);

/** @todo реализовать CLI комманду */
$manager = new \swagpostception\TestGenerationManager(
    __DIR__ . '/swagpostception/example.json',
    \swagpostception\StrategyPostman::STRATEGY
);