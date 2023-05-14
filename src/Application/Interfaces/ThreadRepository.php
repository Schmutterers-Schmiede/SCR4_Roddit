<?php

namespace Application\Interfaces;

interface ThreadRepository {
  public function getAllThreads():array; //of Thraed entities
  public function getThreadById(int $id): \Application\Entities\Thread; 
  public function getThreadsForFilter(string $filter): array; //of thread entities
  public function createThread(int $userId, string $title);
}