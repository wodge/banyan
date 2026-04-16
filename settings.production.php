<?php
$databases['default']['default'] = [
  'database' => 'bandroom',
  'username' => 'bandroom',
  'password' => 'Ge0rg1@1234#!',
  'host' => 'bandroom-bandroom-db-1',
  'port' => '3306',
  'driver' => 'mysql',
  'prefix' => '',
];
$settings['file_private_path'] = '/opt/drupal/private';
$settings['hash_salt'] = 'bandroom-wabb-unique-salt-change-this';
$config['system.logging']['error_level'] = 'hide';
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
$settings['config_sync_directory'] = '/opt/drupal/config/sync';
$settings['trusted_host_patterns'] = [
  '^bandroom\.wabb\.co\.uk$',
];
$databases['default']['default']['init_commands'] = [
  'isolation_level' => 'SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED',
];
