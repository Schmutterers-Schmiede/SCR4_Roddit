<?php 

  namespace Application;

  class CreateThreadCommand{
    public function __construct(
      private Interfaces\ThreadRepository $threadRepository,  
      private \Application\Services\AuthenticationService $authenticationService
    ){}

    public function execute(int $userId, string $title): bool { 
      if($this->authenticationService->getUserId() === $userId){

        //thread does not exist yet
        if(count($this->threadRepository->getThreadsForFilter($title)) === 0){
          $this->threadRepository->createThread($userId, $title);
          return true;
        }
      }
      return false; //user id is not signed in (hacker), or thread already exists
    }     
  }