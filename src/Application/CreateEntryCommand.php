<?php 

  namespace Application;

  class CreateEntryCommand{
    public function __construct(
      private Interfaces\EntryRepository $entryRepository,        
    ){}

    //exit codes:
    //0 - success
    //1 - entry already exists
    public function execute(int $userId, int $threadId, string $text){                
        $this->entryRepository->createEntry($userId, $threadId, $text);    
    }             
  }
  