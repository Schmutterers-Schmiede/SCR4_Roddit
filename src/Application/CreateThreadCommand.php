<?php 

  namespace Application;

  class CreateThreadCommand{
    public function __construct(
      private Interfaces\ThreadRepository $threadRepository,  
      private \Application\Services\AuthenticationService $authenticationService
    ){}
    
    // exit codes:
    // 0 - success
    // 1 - thread already exists
    public function execute(int $userId, string $title): int { 
      if($this->authenticationService->getUserId() === $userId){

        //thread does not exist yet
        if(count($this->threadRepository->getThreadsForFilter($title)) === 0){
          $this->threadRepository->createThread($userId, $title);
          return 0;
        }
      }
      return 1; //thread already exists
    }     
  }