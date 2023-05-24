<?php

namespace Presentation\Controllers;

class User extends \Presentation\MVC\Controller {
  public function __construct(
    private \Application\SignInCommand $signInCommand,
    private \Application\SignOutCommand $signOutCommand,
    private \Application\SignedInUserQuery $signedInUserQuery,
    private \Application\SignUpCommand $signUpCommand,
    private \Application\LatestEntryQuery $latestEntryQuery
  ){}

  public function GET_Login() : \Presentation\MVC\ViewResult {
    return $this->view('login', [
      'user' => $this->signedInUserQuery->execute(),
      'userName' => '',
      'latestEntry' => $this->latestEntryQuery->execute()
    ]);
  }

  public function POST_LogIn() : \Presentation\MVC\ActionResult { //can be anything
    if (!$this->signInCommand->execute($this->getParam('un'), $this->getParam('pwd'))){
      //login failed --> show login form again
      return $this->view('login', [
        'user' => $this->signedInUserQuery->execute(),
        'userName' => $this->getParam('un'),
        'errors' => ['Invalid user name or password'],
        'latestEntry' => $this->latestEntryQuery->execute()
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
      'userName' => '',
      'latestEntry' => $this->latestEntryQuery->execute()
    ]);
  }

  public function GET_RegisterSuccess(): \Presentation\MVC\ViewResult {
    return $this->view('registerSuccess',[
      'latestEntry' => $this->latestEntryQuery->execute()
    ]);
  }


  public function POST_Create() : \Presentation\MVC\ActionResult {
    //password missmatch -> reload with errors
    if($this->getParam('pwd1') !== $this->getParam('pwd2')){
      return $this->view('register', [
        'user' => $this->signedInUserQuery->execute(),
        'userName' => $this->getParam('un'),
        'errors' => ["Password mismatch"],
        'latestEntry' => $this->latestEntryQuery->execute()
      ]);
    }
    if($this->signUpCommand->execute($this->getParam('un'), $this->getParam('pwd1'))){
    
      //registration successful -> go to login
      return $this->view('registerSuccess', [
        'user' => $this->signedInUserQuery->execute(),
        'latestEntry' => $this->latestEntryQuery->execute()
      ]);
    }
    //registration unsuccessful -> reload registration page with error
    return $this->view('register', [
      'user' => $this->signedInUserQuery->execute(),
      'userName' => $this->getParam('un'),
      'errors' => ['This username already exists'],
      'latestEntry' => $this->latestEntryQuery->execute()
    ]);
  }
}