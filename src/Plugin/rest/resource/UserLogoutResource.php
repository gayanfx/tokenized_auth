<?php

namespace Drupal\tokenized_auth\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * Represents user registration as a resource.
 *
 * @RestResource(
 *   id = "tokenized_auth_user_logout",
 *   label = @Translation("Tokenized auth user logout"),
 *   uri_paths = {
 *     "https://www.drupal.org/link-relations/create" = "/tokenized_auth/user/logout",
 *   },
 * )
 */
class UserLogoutResource extends ResourceBase {
  public function post($args) {
    $uid = \Drupal::currentUser()->id();
    if($uid){
      \Drupal::service('tokenized_auth.token_storage')->deleteToken($uid);
      return new ResourceResponse(['message' => 'Logout successfull']);
    }
    return new ResourceResponse(['message' => 'Unable to logout']);
  }
}
