<?php
namespace Application\Entities;

class EntryInfo {
  public function __construct(    
    private string $threadTitle,
    private string $userName,
    private \DateTime $timeStamp
    ){
      
    }

  public function getThreadTitle():string {
    return $this->threadTitle;
  }

  public function getUserName(): string {
    return $this->userName;
  }
  
  public function getTimestamp(): \DateTime {
    return $this->timeStamp;
  }
}