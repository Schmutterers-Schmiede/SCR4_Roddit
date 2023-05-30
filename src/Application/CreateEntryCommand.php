<?php 

  namespace Application;

  class CreateEntryCommand{
    public function __construct(
      private Interfaces\ThreadRepository $threadRepository,  
      private \Application\Services\AuthenticationService $authenticationService
    ){}

    public function execute(int $userId, int $threadId, string $text):bool {  
      if($this->authenticationService->getUserId() === $userId){
        $this->threadRepository->createEntry($userId, $threadId, $text);  
        return true;    
      }             
      else return false;      
    }
  }