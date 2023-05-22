<?php 

namespace Application;

final class ThreadsQuery {
  public function __construct(
    private Interfaces\ThreadRepository $threadRepository
  ){}

  public function execute() : array {
    $res = [];
    foreach ($this->threadRepository->getAllThreads() as $t) {
      $entries = $t->getEntries();      
      foreach($entries as $e){
        $resEntries[] = new \Application\EntryData($e->getUserName(), $e->getTimeStamp(), $e->getText());
      }
      if(count($resEntries) === 0)
        $res[] = new ThreadData($t->getUserName(), $t->getId(), $t->getTitle(), $t->getTimeStamp(), []);
      else
        $res[] = new ThreadData($t->getUserName(), $t->getId(), $t->getTitle(), $t->getTimeStamp(), $resEntries);

      $resEntries = [];
    }
    return $res; //array of ThraedData with EntryData arrays
  }
}