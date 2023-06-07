<?php

namespace Application;

class ThreadByIdQuery
{
    public function __construct(
        private Interfaces\ThreadRepository $threadRepository,        
        private \Application\SignedInUserQuery $signedInUserQuery
    ) {
    }

    public function execute(int $id): \Application\ThreadData
    {
      $resEntries = [];
      $thread = $this->threadRepository->getThreadById($id);
      $entries = $thread->getEntries();      
      foreach($entries as $e){
        $entryDeletable = $this->signedInUserQuery->execute()->userName === $e->getUserName();
        $resEntries[] = new \Application\EntryData($e->getId(), $e->getUserName(), $e->getTimeStamp(), $e->getText(), $entryDeletable);
      }
      $threadDeletable = $this->signedInUserQuery->execute()->userName === $thread->getUserName();
      $res = new \Application\ThreadData( $thread->getUserName(), 
                                          $thread->getId(), 
                                          $thread->getTitle(),
                                          $thread->getTimeStamp(),
                                          $resEntries, 
                                          $threadDeletable);
      return $res;
    }
}