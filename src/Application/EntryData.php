<?php

namespace Application;

class EntryData {
  public function __construct(
    public string $userName,    
    public string $dateTime,
    public string $text
    ){}

    public function getUserName(): string {
      return $this->userName;
    }
    
    public function getDateTime(): string {
      return $this->dateTime;
    }

    public function getText(): string {
      return $this->text;
    }    

}