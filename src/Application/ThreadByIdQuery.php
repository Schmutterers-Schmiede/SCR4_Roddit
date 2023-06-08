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
      $signedInUser = $this->signedInUserQuery->execute();
      foreach($entries as $e){
        if($signedInUser === null) $entryDeletable = false;
        else $entryDeletable = $this->signedInUserQuery->execute()->userName === $e->getUserName();
        $resEntries[] = new \Application\EntryData($e->getId(), $e->getUserName(), $e->getTimeStamp(), $e->getText(), $entryDeletable);
      }
      if($signedInUser === null) $threadDeletable = false;
      else $threadDeletable = $signedInUser->userName === $thread->getUserName();
      $res = new \Application\ThreadData( $thread->getUserName(), 
                                          $thread->getId(), 
                                          $thread->getTitle(),
                                          $thread->getTimeStamp(),
                                          $resEntries, 
                                          $threadDeletable);
      return $res;
    }
}