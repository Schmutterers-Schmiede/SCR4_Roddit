<?php 

  namespace Application;

  class DeleteEntryCommand{
    public function __construct(
      private Interfaces\EntryRepository $entryRepository,        
    ){}

    public function execute(int $entryId) {       
      $this->entryRepository->deleteEntry($entryId);      
    }     
  }