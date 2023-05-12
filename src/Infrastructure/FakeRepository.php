<?php

namespace Infrastructure;

class FakeRepository 
implements 
    \Application\Interfaces\UserRepository,
    \Application\Interfaces\ThreadRepository
{
 
  private $mockUsers;
  private $mockThreads;
  private $mockEntries;

  public function __construct()
  {
      // create mock data        

      $this->mockUsers = array(
          array(1, 'scr4', '$2y$10$0dhe3ngxlmzgZrX6MpSHkeoDQ.dOaceVTomUq/nQXV0vSkFojq.VG')
      );
      
      $this->mockThreads = array(
        array(1, "scr4", "PHP is awesome!", "2023-12-4T12:12:12+02:00")
      );

      $this->mockEntries = array(
        array(1, 1, "scr4", "2023-12-4T12:12:12+02:00", "Please help! I am being held against my will!")          
      );
  }


  public function getUserForUserName(string $username): \Application\Entities\User | null {
    foreach($this->mockUsers as $u){
      if($u[1] === $username){
        return new \Application\Entities\User($u[0],$u[2],$u[2]);
      
      }
    }
    return null;
  }

  public function getUser(int $id): ?\Application\Entities\User{
    foreach($this->mockUsers as $u){
      if($u[0] === $id){
        return new \Application\Entities\User($u[0],$u[1],$u[2]);
      
      }
    }
    return null;
  }

  public function getAllThreads(): array {
    $threads = [];
    foreach($this->mockThreads as $t){
      $entries = [];
      foreach($this->mockEntries as $e){
        if($e[1] === $t[0]){
          $entries[] = new \Application\Entities\Entry($e[0], $e[1], $e[2], $e[3], $e[4]);
        }
      }      
      $threads[] = new \Application\Entities\Thread($t[0], $t[1], $t[2], $t[3], $entries);
    }
    return $threads;
  }

  public function getThreadById(int $id): \Application\Entities\Thread {        
    foreach($this->mockThreads as $t){
      if($t[0] === $id){
        $entries = [];
        foreach($this->mockEntries as $e){
          if($e[1] === $id){
            $entries[] = new \Application\Entities\Entry($e[0], $e[1], $e[2], $e[3], $e[4]);
          }
        }      
        $thread = new \Application\Entities\Thread($t[0], $t[1], $t[2], $t[3], $entries);
      }
    }
    return $thread;
  }  
}
