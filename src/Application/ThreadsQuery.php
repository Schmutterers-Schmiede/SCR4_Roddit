<?php 

namespace Application;

final class ThreadsQuery {
  public function __construct(
    private Interfaces\ThreadRepository $threadRepository
  ){}

  public function execute() : array {
    $res = [];
    foreach ($this->threadRepository->getAllThreads() as $t) {
      $res[] = new ThreadData($t->getUserName(), $t->getTitle(), $t->getDateTime(), $t->getEntries());
    }
    return $res; //array of ThraedData
  }
}