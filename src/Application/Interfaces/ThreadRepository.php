<?php

namespace Application\Interfaces;

interface ThreadRepository {
  public function getAllThreads():array; //of Thraed entities
}