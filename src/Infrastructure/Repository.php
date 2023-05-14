<?php

namespace Infrastructure;

class Repository
implements
    \Application\Interfaces\ThreadRepository,
    \Application\Interfaces\UserRepository
{
    private $server;
    private $userName;
    private $password;
    private $database;

    public function __construct(string $server, string $userName, string $password, string $database)
    {
        $this->server = $server;
        $this->userName = $userName;
        $this->password = $password;
        $this->database = $database;
    }

    // === private helper methods ===

    private function getConnection()
    {
        $con = new \mysqli($this->server, $this->userName, $this->password, $this->database);
        if (!$con) {
            die('Unable to connect to database. Error: ' . mysqli_connect_error());
        }
        return $con;
    }

    private function executeQuery($connection, $query)
    {
        $result = $connection->query($query);
        if (!$result) {
            die("Error in query '$query': " . $connection->error);
        }
        return $result;
    }

    private function executeStatement($connection, $query, $bindFunc)
    {
        $statement = $connection->prepare($query);
        if (!$statement) {
            die("Error in prepared statement '$query': " . $connection->error);
        }
        $bindFunc($statement);
        if (!$statement->execute()) {
            die("Error executing prepared statement '$query': " . $statement->error);
        }
        return $statement;
    }

    // === public methods ===

    public function getUser(int $id): ?\Application\Entities\User
    {
        $user = null;
        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            'SELECT userId, userName, passwordHash FROM users WHERE userId = ?',
            function ($s) use ($id) {
                $s->bind_param('i', $id);
            }
        );

        //I know the line below is unnecessary, but my IDE wouldn't shut up about unassigned variables
        $id = 0; $userName = ''; $passwordHash = '';

        $stat->bind_result($id, $userName, $passwordHash);
        if ($stat->fetch()) {
            $user = new \Application\Entities\User($id, $userName, $passwordHash);
        }
        $stat->close();
        $con->close();
        return $user;
    }

    public function getUserForUserName(string $userName): ?\Application\Entities\User
    {
        $user = null;
        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            'SELECT UserId, userName, passwordHash FROM users WHERE userName = ?',
            function ($s) use ($userName) {
                $s->bind_param('s', $userName);
            }
        );

        //I know the line below is unnecessary, but my IDE wouldn't shut up about unassigned variables
        $id = 0; $userName = ''; $passwordHash = '';

        $stat->bind_result($id, $userName, $passwordHash);
        if ($stat->fetch()) {
            $user = new \Application\Entities\User($id, $userName, $passwordHash);
        }
        $stat->close();
        $con->close();
        return $user;
    }

    public function CreateUser(string $username, string $passwordHash){
        $con = $this->getConnection();
        $con->autocommit(false);
        $stat = $this->executeStatement(
          $con,
          'INSERT INTO users (userName, passwordHash) VALUES (?, ?)',
          function($s) use ($username, $passwordHash){
            $s->bind_param('ss', $username, $passwordHash);
          }
        );
        $stat->close();
        $con->commit();
        $con->close();
    }

    public function getAllThreads(): array{
      $threads = [];

      //I know the line below is unnecessary, but my IDE wouldn't shut up about unassigned variables
      $text = ''; $entryId = 0; $entryTid = 0; $entryUserName = ''; $entryTimestamp = '';

      $con = $this->getConnection();
      //get all threads
      $threadRes = $this->executeQuery($con, 'SELECT threadId, userName, title, timestamp
                                              FROM threads
                                              JOIN users 
                                              ON threads.userId = users.userId
                                              ORDER BY timestamp DESC;'
      );                  
      
      while($t = $threadRes->fetch_object()){
        $tid = $t->threadId;
        //get all entries for each thread
        $entryStat = $this->executeStatement($con, 'SELECT entryId, threadId, userName, timestamp, text
                                                    FROM entries
                                                    JOIN users
                                                    ON entries.userId = users.userId
                                                    WHERE threadId = ?
                                                    ORDER BY timestamp DESC;',
        function($s) use ($tid){$s->bind_param('i', $tid);});
        $entryStat->bind_result($entryId, $entryTid, $entryUserName, $entryTimestamp, $text);
        $entriesForCurrentThread = [];
        while($entryStat->fetch()){
          $entriesForCurrentThread[] = new \Application\Entities\Entry ($entryId, $entryTid, $entryUserName, new \DateTime($entryTimestamp), $text);
        }
        $threads[] = new \Application\Entities\Thread($t->threadId, $t->userName, $t->title, new \DateTime($t->timestamp), $entriesForCurrentThread);
      }
      $con->close();
      $entryStat->close();
      return $threads;
    }

    public function getThreadById(int $id): ?\Application\Entities\Thread {
      $con = $this->getConnection();      
      //I know the line below is unnecessary, but my IDE wouldn't shut up about unassigned variables
      $threadId = 0; $username = ''; $title = ''; $timestamp = ''; $entryId = 0; $entryTid = 0; $entryUserName = ''; $entryTimestamp = ''; $text = '';

      $entryStat = $this->executeStatement($con, 'SELECT entryId, threadId, userName, timestamp, text
                                                    FROM entries
                                                    JOIN users
                                                    ON entries.userId = users.userId
                                                    WHERE threadId = ?
                                                    ORDER BY timestamp DESC;',
        function($s) use ($id){$s->bind_param('i', $id);});
      $entryStat->bind_result($entryId, $entryTid, $entryUserName, $entryTimestamp, $text);  
      $entriesForThread = [];
      while($entryStat->fetch()){
        $entriesForThread[] = new \Application\Entities\Entry ($entryId, $entryTid, $entryUserName, new \DateTime($entryTimestamp), $text);
      }

      $threadStat = $this->executeStatement($con,'SELECT threadId, username, title, timestamp
                                            FROM threads
                                            JOIN users
                                            ON threads.userId = users.userId
                                            WHERE threadId = ?
                                            ORDER BY timestamp DESC;',
      function($s) use ($id){$s->bind_param('i', $id);});      
      $threadStat->bind_result($threadId, $username, $title, $timestamp);

      $con->close();
      $threadStat->close();
      $entryStat->close();
      return new \Application\Entities\Thread($threadId, $username, $title, new \DateTime($timestamp), $entriesForThread);
    }

    public function getThreadsForFilter(string $filter): array {
      $threads = [];
      $filter = "%$filter%";
      //I know the line below is unnecessary, but my IDE wouldn't shut up about unassigned variables
      $text = ''; $entryId = 0; $entryTid = 0; $entryUserName = ''; $entryTimestamp = ''; $threadId = 0; $username = ''; $title = ''; $timestamp = '';

      $con = $this->getConnection();

      $entryStat = $this->executeStatement($con, 'SELECT entryId, entries.threadId, userName, threads.timestamp, text
                                                    FROM entries
                                                    JOIN users ON entries.userId = users.userId
                                                    JOIN threads ON entries.threadId = threads.threadId
                                                    WHERE title LIKE ?
                                                    ORDER BY timestamp DESC;',
        function($s) use ($filter){$s->bind_param('s', $filter);});
      $entryStat->bind_result($entryId, $entryTid, $entryUserName, $entryTimestamp, $text);      
      while($entryStat->fetch()){
        $entriesForThreads[] = new \Application\Entities\Entry ($entryId, $entryTid, $entryUserName, new \DateTime($entryTimestamp), $text);
      }
      $entryStat->close();
      //get all threads
      $threadStat = $this->executeStatement($con, 'SELECT threadId, userName, title, timestamp
                                              FROM threads
                                              JOIN users 
                                              ON threads.userId = users.userId
                                              WHERE title LIKE ?
                                              ORDER BY timestamp DESC;',
      function($s) use ($filter){$s->bind_param('s', $filter);});                  
      $threadStat->bind_result($threadId, $username, $title, $timestamp);
      while($threadStat->fetch()){
        $currentThreadId = $threadId;
        //get all entries for each thread
        $entriesForCurrentThread = [];
        foreach($entriesForThreads as $e){
          if($e->getId() === $threadId){
            $entriesForCurrentThread[] = new \Application\Entities\Entry($e->getId(), $threadId, $e->getUserName(), $e->getTimestamp(), $e->getText());
          }
        }
        $threads[] = new \Application\Entities\Thread($threadId, $username, $title, new \DateTime($timestamp), $entriesForCurrentThread);
      }
      $con->close();
      $threadStat->close();
      return $threads;
    }
    public function createThread(int $userId, string $title){
      $date = date('Y-m-dTH:i:s');
      $con = $this->getConnection();
        $con->autocommit(false);
        $stat = $this->executeStatement(
          $con,
          'INSERT INTO threads (`userId`, `title`, `timestamp`) VALUES (?, ?)',
          function($s) use ($userId, $title, $date){
            $s->bind_param('iss', $userId, $title, $date);
          }
        );
        $stat->close();
        $con->commit();
        $con->close();
    }
}


