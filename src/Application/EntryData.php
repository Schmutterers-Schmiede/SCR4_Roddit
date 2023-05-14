<?php

namespace Application;

class EntryData {
  public function __construct(
    public string $userName,    
    public \DateTime $timestamp,
    public string $text
    ){}
}