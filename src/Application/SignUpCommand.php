<?php 

  namespace Application;

  class SignUpCommand{
    public function __construct(
      private Interfaces\UserRepository $userRepository,
      private Services\AuthenticationService $authenticationService
    ){}

    public function execute(string $userName, string $password): bool {
      $this->authenticationService->signOut();
      //user does not exist yet
      if($this->userRepository->getUserForUserName($userName) === null){
        $this->userRepository->CreateUser($userName, password_hash($password, PASSWORD_DEFAULT));
        return true;
      }
      return false; //user already exists
    }
  }