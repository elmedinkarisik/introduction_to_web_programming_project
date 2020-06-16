<?php
class BaseDao {
  private $pdo;

  private $table_name;

  public function __construct($table_name){
    $this->pdo = new PDO('mysql:host=remotemysql.com;dbname=w1ZZD7VF22', 'w1ZZD7VF22', 'wIPCn0dlQK');
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $this->table_name = $table_name;
  }

  protected function execute_query($sql_query, $params){
    $stmt = $this->pdo->prepare($sql_query);
    $stmt->execute($params);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt->fetchAll();
  }

  public function get_by_id($id){
    return $this->execute_query("SELECT * FROM {$this->table_name} WHERE id = :id", ["id" => $id]);
  }
  public function get_all(){
    return $this->execute_query("SELECT * FROM {$this->table_name} LIMIT 5 ", []);
  }
}

?>