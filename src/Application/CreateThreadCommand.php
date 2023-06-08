<?php 

  namespace Application;

  class CreateThreadCommand{
    public function __construct(
      private Interfaces\ThreadRepository $threadRepository,  
      private \Application\SignedInUserQuery $signedInUserQuery
    ){}
    
    // exit codes:
    // 0 - success
    // 1 - thread already exists
    public function execute(int $userId, string $title): int { 
      if(count($this->threadRepository->getThreadsForFilter($title)) === 0){
        //thread does not exist yet
        $this->threadRepository->createThread($userId, $title);
        return 0;
      }
      return 1;
    }
  }     