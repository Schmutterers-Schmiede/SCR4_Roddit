<?php

namespace Application;

class EntryData {
  public function __construct(
    public string $userName,    
    public \DateTime $timeStamp,
    public string $text
    ){}
}