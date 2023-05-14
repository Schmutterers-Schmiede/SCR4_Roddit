<?php 

  namespace Application;

  class CreateEntryCommand{
    public function __construct(
      private Interfaces\ThreadRepository $threadRepository,  
    ){}

    public function execute(int $userId, int $threadId, string $text) {                  
        $this->threadRepository->createEntry($userId, $threadId, $text);      
    }
  }