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
    foreach ($this->threadRepository->getAllThreads() as $t) {
      $entries = $t->getEntries();      
      foreach($entries as $e){
        $resEntries[] = new \Application\EntryData($e->getUserName(), $e->getTimeStamp(), $e->getText());
      }
      $deletable = false;
      $user = $this->signedInUserQuery->execute();
      if($user != null && $user->userName === $t->getUserName()) 
        $deletable = true;
      
      
      if(count($resEntries) === 0)
        $res[] = new ThreadData(  $t->getUserName(), 
                                  $t->getId(), 
                                  $t->getTitle(), 
                                  $t->getTimeStamp(), 
                                  [], 
                                  $deletable);
      else
        $res[] = new ThreadData(  $t->getUserName(), 
                                  $t->getId(), 
                                  $t->getTitle(), 
                                  $t->getTimeStamp(), 
                                  $resEntries, 
                                  $deletable);
      $resEntries = [];
    }
    return $res; //array of ThraedData with EntryData arrays
  }
}