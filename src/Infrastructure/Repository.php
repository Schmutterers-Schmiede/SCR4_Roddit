<?php

namespace Infrastructure;

class Repository
implements
    \Application\Interfaces\ThreadRepository,
    \Application\Interfaces\UserRepository,
    \Application\Interfaces\EntryRepository
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

    // +----------------------------------------------------------------+
    // |                     DATABASE COMMUNICATION                     |
    // +----------------------------------------------------------------+

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

    // +----------------------------------------------------------------+
    // |                             USERS                              |
    // +----------------------------------------------------------------+

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

    // +----------------------------------------------------------------+
    // |                            THREADS                             |
    // +----------------------------------------------------------------+

    public function getAllThreads(): array{
      $threads = [];
      $entries = [];
      //I know the line below is unnecessary, but my IDE wouldn't shut up about unassigned variables
      $text = ''; $entryId = 0; $entryTid = 0; $entryUserName = ''; $entryTimestamp = '';

      $con = $this->getConnection();
      //get all entries
      $entryRes = $this->executeQuery($con,  'SELECT entryId, threadId, userName, timestamp, text
                                              FROM entries
                                              JOIN users ON entries.userId = users.userId                                                    
                                              ORDER BY timestamp DESC;'
      );               
      while($e = $entryRes->fetch_object()) {
        $entries[] = new \Application\Entities\Entry($e->entryId, $e->threadId, $e->userName, new \DateTime($e->timestamp), $e->text);
      }                               
      //get all threads
      $threadRes = $this->executeQuery($con, 'SELECT threadId, userName, title, timestamp
                                              FROM threads
                                              JOIN users 
                                              ON threads.userId = users.userId
                                              ORDER BY timestamp DESC;'
      );                  
      
      while($t = $threadRes->fetch_object()){
        $entriesForCurrentThread = [];
        //get all entries for each thread
        foreach($entries as $e){
          if ((int)$e->getThreadId() === (int)$t->threadId){
            $entriesForCurrentThread[] = $e;
          }
        }
        if(count($entriesForCurrentThread) === 0)
          $threads[] = new \Application\Entities\Thread($t->threadId, $t->userName, $t->title, new \DateTime($t->timestamp), []);
        else
          $threads[] = new \Application\Entities\Thread($t->threadId, $t->userName, $t->title, new \DateTime($t->timestamp), $entriesForCurrentThread);
        unset($entriesForCurrentThread);
      }
      $con->close();      
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
      $threadStat->fetch();
      if(count($entriesForThread) === 0)
        $result = new \Application\Entities\Thread($threadId, $username, $title, new \DateTime($timestamp), []);
      else
        $result = new \Application\Entities\Thread($threadId, $username, $title, new \DateTime($timestamp), $entriesForThread);

      $con->close();
      $threadStat->close();
      $entryStat->close();
      return $result;
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
        if(count($entriesForCurrentThread) === 0)
          $threads[] = new \Application\Entities\Thread($threadId, $username, $title, new \DateTime($timestamp), []);
        else
          $threads[] = new \Application\Entities\Thread($threadId, $username, $title, new \DateTime($timestamp), $entriesForCurrentThread);
      }
      $con->close();
      $threadStat->close();
      return $threads;
    }
    public function createThread(int $userId, string $title){
      $date = date('Y-m-d\TH:i:s');
      $con = $this->getConnection();
      $con->autocommit(false);
      $stat = $this->executeStatement(
        $con,
        'INSERT INTO threads (`userId`, `title`, `timestamp`) VALUES (?, ?, ?)',
        function($s) use ($userId, $title, $date){
          $s->bind_param('iss', $userId, $title, $date);
        }
      );
      $stat->close();
      $con->commit();
      $con->close();
    }

    public function deleteThread(int $threadId){
      $con = $this->getConnection();
      $con->autocommit(false);

      $stat = $this->executeStatement(
        $con,
        'DELETE FROM entries WHERE threadId = ?',
        function($s) use ($threadId){
          $s->bind_param('i', $threadId);
        }
      );

      $stat = $this->executeStatement(
        $con,
        'DELETE FROM threads WHERE threadId = ?',
        function($s) use ($threadId){
          $s->bind_param('i', $threadId);
        }
      );

      $stat->close();
      $con->commit();
      $con->close();
    }

    // +----------------------------------------------------------------+
    // |                             ENTRIES                            |
    // +----------------------------------------------------------------+

  public function createEntry(int $userId, int $threadId, string $text){    
      $timestamp = date('Y-m-d\TH:i:s');
      $con = $this->getConnection();
      $con->autocommit(false);
      $stat = $this->executeStatement(
        $con,
        'INSERT INTO entries (`userId`,`threadId`,`timestamp`,`text`) VALUES (?,?,?,?)',
        function($s) use ($userId, $threadId, $timestamp, $text){
          $s->bind_param('iiss', $userId, $threadId, $timestamp, $text);
        }
      );
      $stat->close();
      $con->commit();
      $con->close();
  }

  public function getEntryById(int $id): ?\Application\Entities\Entry {
    $con = $this->getConnection();      
    //I know the line below is unnecessary, but my IDE wouldn't shut up about unassigned variables
    $entryId = 0; $entryTid = 0; $entryUserName = ''; $entryTimestamp = ''; $text = '';

    $entryStat = $this->executeStatement($con, 'SELECT entryId, threadId, userName, timestamp, text
                                                FROM entries
                                                JOIN users
                                                ON entries.userId = users.userId
                                                WHERE entryId = ?
                                                ORDER BY timestamp DESC;',
    function($s) use ($id){$s->bind_param('i', $id);});
    $entryStat->bind_result($entryId, $entryTid, $entryUserName, $entryTimestamp, $text);  
    $entryStat->fetch();
    $result = new \Application\Entities\Entry ($entryId, $entryTid, $entryUserName, new \DateTime($entryTimestamp), $text);

    $con->close();    
    $entryStat->close();
    return $result;
  }

  public function getLatestEntry(): ?\Application\Entities\EntryInfo{
    
    $con = $this->getConnection();      

    $entryInfoRes = $this->executeQuery($con, 'SELECT title, userName, entries.timestamp
                                                FROM entries
                                                JOIN users ON entries.userId = users.userId
                                                JOIN threads ON entries.threadId = threads.threadId
                                                ORDER BY entries.timestamp DESC
                                                LIMIT 1;',
    );
    $e = $entryInfoRes->fetch_object();
    if($e !== null)
      $latestEntry = new \Application\Entities\EntryInfo($e->title, $e->userName, new \DateTime($e->timestamp));
    else 
      $latestEntry = null;

    $con->close();      
    return $latestEntry;      
  }

  public function deleteEntry(int $entryId){
    $con = $this->getConnection();
    $con->autocommit(false);

    $stat = $this->executeStatement(
      $con,
      'DELETE FROM entries WHERE entryId = ?',
      function($s) use ($entryId){
        $s->bind_param('i', $entryId);
      }
    );

    $stat->close();
    $con->commit();
    $con->close();
  }

}


