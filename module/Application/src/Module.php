<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

class Module {

    const VERSION = '3.0.2dev';

    public function onBootstrap($e) {
        $eventManager = $e->getApplication()->getServiceManager()->get('EventManager');
        $eventManager->getSharedManager()->attach(
                'Zend\Mvc\Controller\AbstractActionController',
                \Zend\Mvc\MvcEvent::EVENT_DISPATCH, [$this, 'verificaAutenticacao'], 100);
    }

    public function verificaAutenticacao($e) {
        $controller = $e->getTarget();
      
        $rota = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();

        if ($rota != 'login' && $rota != 'login/default') {
           
            $sessao = new \Zend\Session\Container('Auth');
            
            if (!$sessao->admin) {
                return $controller->redirect()->toRoute('login');
            }
        }
    }

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

}
