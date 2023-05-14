<?php 

  namespace Application;

  class CreateThreadCommand{
    public function __construct(
      private Interfaces\ThreadRepository $threadRepository,  
    ){}

    public function execute(int $userId, string $title): bool {      
      //thread does not exist yet
      if(count($this->threadRepository->getThreadsForFilter($title)) === 0){
        $this->threadRepository->createThread($userId, $title);
        return true;
      }
      return false; //user already exists
    }
  }