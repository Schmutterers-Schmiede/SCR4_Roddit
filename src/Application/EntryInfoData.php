<?php
namespace Application;

class EntryInfoData {
  public function __construct(    
    public string $threadTitle,
    public string $userName,
    public \DateTime $timeStamp
    ){}  
}