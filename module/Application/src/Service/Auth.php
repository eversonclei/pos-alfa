<?php

namespace Application\Service;

use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;

class Auth implements \Zend\Authentication\Adapter\AdapterInterface {

    private $authAdapter;
    private $identity;
    private $credential;

    public function __construct($adapter, $identity, $credential) {
        $this->authAdapter = new AuthAdapter($adapter, 'users', 'login', 'password');
        $this->identity = $identity;
        $this->credential = $credential;
    }

    public function authenticate() {
        $this->authAdapter->setCredential($this->credential);
        $this->authAdapter->setIdentity($this->identity);
        return $this->authAdapter->authenticate();
    }

}
