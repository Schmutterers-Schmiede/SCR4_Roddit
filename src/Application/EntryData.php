<?php

namespace Application;

class EntryData {
  public function __construct(
    public int $id,
    public string $userName,    
    public \DateTime $timestamp,
    public string $text,
    public bool $deletable
    ){}
}