<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;


class LoginController extends AbstractActionController {

    public $tableGateway;

    private function getForm()
    {
        $form = new \Application\Form\Login;
        foreach ($form->getElements() as $element) {
            if (! $element instanceof \Zend\Form\Element\Submit) {
                $element->setAttributes([
                    'class' => 'form-control'
                ]);
            }
        }
        return $form;
    }

    
    public function indexAction() {   

        $request = $this->getRequest();
      
        $form = $this->getForm();

        $view = new ViewModel();
             
        if ($request->isPost()) {
            
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
               
                $login = $form->getData();
          
                $adapter = $this->getEvent()->getApplication()->
                    getServiceManager()->get(\Application\Factory\DbAdapter::class);
              
                $auth = new \Application\Service\Auth($adapter, 
                                                      $login['login'], 
                                                      $login['password']);
             
                // faz a validação do formulário de login
                if ($auth->authenticate()->isValid()) {
                    $sessao = new Container('Auth');
                    $sessao->admin = true;
                    $sessao->identity = $auth->authenticate()->getIdentity();
                    return $this->redirect()->toRoute('home'); 
                } 
                else 
                {
                    $errorCode = $auth->authenticate()->getCode();
                    switch ($errorCode) {
                        case \Zend\Authentication\Result::FAILURE_CREDENTIAL_INVALID:
                            $view->setVariable('error', "Senha não confere!");
                            break;
                        case \Zend\Authentication\Result::FAILURE_IDENTITY_NOT_FOUND:
                            $view->setVariable('error', "Login não confere!");
                            break;                               
                        default :
                            $view->setVariable('error', "Erro ao efetuar login!");
                            break;
                    }
                }
            } else {
                $view->setVariable('error', "");
            }
        }

        $view->setTerminal(true);
      
        $view = new ViewModel(['form' => $form]);
     
        return $view;
    }

    public function logoutAction() {
        $sessao = new Container;
        $sessao->getManager()->getStorage()->clear();
        return $this->redirect()->toRoute('login');
    }

}
