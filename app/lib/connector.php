<?php
/**
 * Class sql_connector
 *
 * A singleton class to manage MySQL database connections using PDO.
 *
 * @package app/lib
 */
class sql_connector
{
  /**
   * @var sql_connector|null The single instance of the class.
   */
  private static $instance = null;

  /**
   * @var PDO The PDO instance for database connection.
   */
  private $pdo;

  /**
   * sql_connector constructor.
   *
   * Initializes the PDO connection.
   *
   * @param string $db_name The name of the database.
   * @param string $server The database server address.
   * @param string $user The database user.
   * @param string $password The database password.
   *
   * @throws PDOException If the connection fails.
   */
  private function __construct($db_name, $server, $user, $password)
  {
    if ($db_name == '' || $server == '' || $user == '' || $password == '') {
      echo 'Error: Missing parameters';
      exit();
    }
    try {
      $connection_string = "mysql:host=$server;dbname=$db_name";
      $this->pdo = new PDO($connection_string, $user, $password);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo 'Failed to connect to MySQL: ' . $e->getMessage();
      exit();
    }
  }

  /**
   * Get the single instance of the class.
   *
   * @return sql_connector The single instance of the class.
   */
  public static function getInstance()
  {
    if (self::$instance === null) {
      self::$instance = new self(
        'R301_projet',
        '127.0.0.1',
        'R301_projet',
        'R301_projet'
      );
    }
    return self::$instance;
  }

  /**
   * Run a SQL query with bound parameters.
   *
   * @param string $sql The SQL query with placeholders.
   * @param mixed ...$params The parameters to bind to the query.
   *
   * @return array|false The result set as an associative array, or false on failure.
   *
   * @throws InvalidArgumentException If the number of placeholders does not match the number of parameters.
   * @throws PDOException If the query execution fails.
   */
  public function run_query($sql, ...$params)
  {
    try {
      // Ensure the number of placeholders matches the number of parameters
      if (substr_count($sql, '?') !== count($params)) {
        throw new InvalidArgumentException(
          'Number of bound variables does not match number of tokens'
        );
      }
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($params);

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo 'Query failed: ' . $e->getMessage();
      return false;
    }
  }

  /**
   * Prevent cloning of the instance.
   */
  private function __clone()
  {
    // Prevent cloning
  }

  /**
   * Prevent unserializing of the instance.
   */
  public function __wakeup()
  {
    // Prevent unserializing
  }

  /**
   * Destructor to close the PDO connection.
   */
  public function __destruct()
  {
    $this->pdo = null;
  }
}

?>
