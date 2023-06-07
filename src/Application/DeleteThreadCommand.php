<?php 

  namespace Application;

  class DeleteThreadCommand{
    public function __construct(
      private Interfaces\ThreadRepository $threadRepository,        
    ){}

    public function execute(int $threadId) {       
      $this->threadRepository->deleteThread($threadId);      
    }     
  }