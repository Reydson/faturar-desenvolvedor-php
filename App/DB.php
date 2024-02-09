<?php

namespace App;

use \PDO;

/** 
 * Singleton para acesso ao banco de dados.
 */
class DB
{
  private static $pdo;

  const HOST = 'localhost';
  const DATABASE = 'faturar-desenvolvedor-php';
  const USERNAME = 'root';
  const PASSWORD = '';

  static function pdo()
  {
    if (!isset(self::$pdo)) {
      $dsn = 'mysql:dbname=' . self::DATABASE . ';host=' . self::HOST . ';charset=utf8';
      self::$pdo = new PDO(
        $dsn,
        self::USERNAME,
        self::PASSWORD,
        array(PDO::ATTR_PERSISTENT => true));
    }
    return self::$pdo;
  }

}
?>