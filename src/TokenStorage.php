<?php

namespace Drupal\tokenized_auth;

class TokenStorage {

  public function generateToken($uid) {
    $uuid_service = \Drupal::service('uuid');
    $uuid = $uuid_service->generate();
    $query = \Drupal::database()->delete('tokenized_auth');
    $query->condition('uid', $uid);
    $query->execute();

    $query = \Drupal::database()->insert('tokenized_auth')
        ->fields([
      'token' => $uuid,
      'uid' => $uid,
      'expires' => strtotime('+2 months'),
    ]);
    $query->execute();
    
    return $uuid;
  }

  public function getToken($token){
    $query = \Drupal::database()->select('tokenized_auth', 'ta');
    $query->fields('ta', ['uid']);
    $query->condition('token', $token);
    $query->condition('expires', REQUEST_TIME, '>');
    $query->range(0, 1);
    $result = $query->execute()->fetchAssoc();
    return $result;
  }
}