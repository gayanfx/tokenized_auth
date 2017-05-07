<?php

namespace Drupal\tokenized_auth\Authentication\Provider;

use Drupal\Core\Entity\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Authentication\AuthenticationProviderInterface;

/**
 * HTTP Basic authentication provider.
 */
class TokenizedAuth implements AuthenticationProviderInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * Constructs an IP consumer authentication provider object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity manager service.
   */
  public function __construct(EntityManagerInterface $entity_manager) {
    $this->entityManager = $entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(Request $request) {
    $token = $request->headers->get('X-CSRF-Token');
    return isset($token);
  }

  /**
   * {@inheritdoc}
   */
  public function authenticate(Request $request) {
    $token = $request->headers->get('X-CSRF-Token');
    $result = \Drupal::service('tokenized_auth.token_storage')->getToken($token);
    if(isset($result['uid'])){
      return $this->entityManager->getStorage('user')->load($result['uid']);
    }
    return [];
  }

}
