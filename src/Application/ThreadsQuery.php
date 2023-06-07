<?php 

namespace Application;

final class ThreadsQuery {
  public function __construct(
    private Interfaces\ThreadRepository $threadRepository,
    private \Application\SignedInUserQuery $signedInUserQuery
  ){}

  public function execute() : array {
    $res = [];
    $resEntries = [];
    $user = $this->signedInUserQuery->execute();
    foreach ($this->threadRepository->getAllThreads() as $t) {
      $entries = $t->getEntries();      
      foreach($entries as $e){

        $entryDeletable = $user !== null && $user->userName === $e->getUserName();
        $resEntries[] = new \Application\EntryData($e->getId(), $e->getUserName(), $e->getTimeStamp(), $e->getText(), $entryDeletable);
      }

      $threadDeletable = $user !== null && $user->userName === $t->getUserName();
      
      
      if(count($resEntries) === 0)
        $res[] = new ThreadData(  $t->getUserName(), 
                                  $t->getId(), 
                                  $t->getTitle(), 
                                  $t->getTimeStamp(), 
                                  [], 
                                  $threadDeletable);
      else
        $res[] = new ThreadData(  $t->getUserName(), 
                                  $t->getId(), 
                                  $t->getTitle(), 
                                  $t->getTimeStamp(), 
                                  $resEntries, 
                                  $threadDeletable);
      $resEntries = [];
    }
    return $res; //array of ThraedData with EntryData arrays
  }
}