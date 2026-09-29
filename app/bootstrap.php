<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/EventRepository.php';
require_once __DIR__ . '/ControllerRepository.php';

$db = new Database(__DIR__ . '/../data/soyal.sqlite');

$events = new EventRepository($db->pdo());
$events->seedDemoData();

$controllers = new ControllerRepository($db->pdo());
$controllers->seedFromLegacyScreens();
