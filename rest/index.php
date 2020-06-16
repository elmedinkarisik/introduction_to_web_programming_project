<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS, PATCH');

require_once('../vendor/autoload.php');
require_once('dao/UserDao.class.php');

Flight::register('db', 'PDO', array('mysql:host=remotemysql.com;dbname=w1ZZD7VF22','w1ZZD7VF22','wIPCn0dlQK'));
Flight::register('user_dao', 'UserDao');

Flight::route('GET /reservations', function(){
    $reservations = Flight::db()->query('SELECT * FROM reservations', PDO::FETCH_ASSOC)->fetchAll();
    Flight::json($reservations);
});

Flight::route('POST /reservations', function(){
    $request = Flight::request()->data->getData();
    $insert = "INSERT INTO reservations (hotel_name, check_in_date, check_out_date, room_number) VALUES(:hotel, :check_in_date, :check_out_date, :room_number)";  // fname, lname, user_email and phone are names of input fields
    $stmt= Flight::db()->prepare($insert);
    $stmt->execute($request);
    Flight::json('Reservation has been made');
});

Flight::route('DELETE /reservation/@id', function($id){
    $delete = "DELETE FROM reservations WHERE id = :id";
    $stmt= Flight::db()->prepare($delete);
    $stmt->execute([":id" => $id]);
});

Flight::route('GET /reservation', function(){
    $id = Flight::request()->query['id'];
    $stmt = Flight::db()->prepare("SELECT * FROM reservations WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $reservation = $stmt->fetch();
    Flight::json($reservation);
});

Flight::route('POST /login', function(){
    $user = Flight::request()->data->getData();
    $db_user = Flight::user_dao()->get_user_by_email($user['email']);
  
    if ($db_user){
      if ($db_user['password'] == $user['password']){
        //Flight::json($db_user); wrong
        $token_user = [
          'id' => $db_user['id'],
          'email' => $db_user['email'],
          'is_admin' => $db_user['is_admin']
        ];
        $jwt = JWT::encode($token_user, Config::JWT_SECRET);
        Flight::json(['id' => $db_user['id'],'token' => $jwt]);
      }else{
        Flight::halt(404, 'Password Incorrect');
      }
    }else{
      Flight::halt(404, 'User not found');
    }
});

Flight::start();

?>
   