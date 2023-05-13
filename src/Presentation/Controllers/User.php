<?php

namespace Presentation\Controllers;

class User extends \Presentation\MVC\Controller {
  public function __construct(
    private \Application\SignInCommand $signInCommand,
    private \Application\SignOutCommand $signOutCommand,
    private \Application\SignedInUserQuery $signedInUserQuery,
    private \Application\SignUpCommand $signUpCommand
  ){}

  public function GET_Login() : \Presentation\MVC\ViewResult {
    return $this->view('login', [
      'user' => $this->signedInUserQuery->execute(),
      'userName' => ''
    ]);
  }

  public function POST_LogIn() : \Presentation\MVC\ActionResult { //can be anything
    if (!$this->signInCommand->execute($this->getParam('un'), $this->getParam('pwd'))){
      //login failed --> show login form again
      return $this->view('login', [
        'user' => $this->signedInUserQuery->execute(),
        'userName' => $this->getParam('un'),
        'errors' => ['Invalid user name or password']
      ]);
    }
    //login successful --> redirect
    return $this->redirect('Home', 'Index');
  }

  public function POST_LogOut() : \Presentation\MVC\RedirectResult { 
    $this->signOutCommand->execute();
    return $this->redirect('User', 'Login');
  }

  public function GET_Register(): \Presentation\MVC\ViewResult {
    return $this->view('register', [
      'user' => $this->signedInUserQuery->execute(),
      'userName' => ''
    ]);
  }

  public function POST_Create() : \Presentation\MVC\ActionResult {
    
    if($this->signUpCommand->execute($this->getParam('un'), $this->getParam('pwd'))){
    
      //registration successful -> go to login
      return $this->redirect('User', 'Login');
    }
    //registration unsuccessful -> reload registration page with error
    return $this->view('register', [
      'user' => $this->signedInUserQuery->execute(),
      'userName' => $this->getParam('un'),
      'errors' => ['This username already exists']
    ]);
  }
}