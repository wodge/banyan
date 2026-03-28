<?php

$permissions = [
  'public_group-outsider' => [
    'view group',
    'view event group_node entity',
    'view news group_node entity',
  ],
  'public_group-anonymous' => [
    'view group',
    'view event group_node entity',
    'view news group_node entity',
  ],
];

foreach ($permissions as $role_id => $perms) {
  $role = \Drupal\group\Entity\GroupRole::load($role_id);
  if ($role) {
    $role->grantPermissions($perms);
    $role->save();
    echo 'Permissions set for ' . $role_id . PHP_EOL;
  } else {
    echo 'Role not found: ' . $role_id . PHP_EOL;
  }
}
