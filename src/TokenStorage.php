<?php

namespace Drupal\tokenized_auth;

class TokenStorage {

  public function generateToken($uid) {
    $uuid_service = \Drupal::service('uuid');
    $uuid = $uuid_service->generate();
    db_merge('tokenized_auth')
        ->key(array('uid' => $uid))
        ->fields([
          'uid' => $uid,
          'token' => $uuid,
          'expires' => strtotime('+2 months'),
        ])
        ->execute();
    return $uuid;
  }

  public function getToken($token) {
    $query = \Drupal::database()->select('tokenized_auth', 'ta');
    $query->fields('ta', ['uid']);
    $query->condition('token', $token);
    $query->condition('expires', REQUEST_TIME, '>');
    $query->range(0, 1);
    $result = $query->execute()->fetchAssoc();
    return $result;
  }
  
  public function deleteToken($uid) {
    $query = \Drupal::database()->delete('tokenized_auth');
    $query->condition('uid', $uid);
    $query->execute();
  }

}