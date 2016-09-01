<?php

use Slim\Middleware\JwtAuthentication;
use Slim\Middleware\HttpBasicAuthentication;
use Firebase\JWT\JWT;

require 'vendor/autoload.php';
require 'lib/mysql.php';

$app = new Slim\App();

$app->add(new HttpBasicAuthentication([
  "path" => "/token",
  "secure" => false,
  "users" => [
      "test" => "test"
  ]
]));

$app->add(new JwtAuthentication([
  "path" => "/",
  "secure" => false,  
  "passthrough" => "/token",
  "secret" => "supersecretkeyyoushouldnotcommittogithub"
]));

$app->get('/', get_employee);

$app->get('/api', function($request, $response, $args) {
  print "Here be dragons";
});

$app->post('/token', function($request, $response, $args) {
  $now = new DateTime();
  
  $payload = [
    "iat" => $now->getTimeStamp()  
  ];
  
  $secret = "supersecretkeyyoushouldnotcommittogithub";

  $token = JWT::encode($payload, $secret, "HS256");

  $data["status"] = "ok";
  $data["token"] = $token;

  return $response->withStatus(201)
    ->withHeader("Content-Type", "application/json")
    ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

$app->get('/employee/{id}', function($request, $response, $args) {
  get_employee_id($args['id']);
});

$app->post('/employee_add', function($request, $response, $args) {
  add_employee($request->getParsedBody());
});

$app->put('/update_employee', function($request, $response, $args) {
  update_employee($request->getParsedBody());
});

$app->delete('/delete_employee', function($request, $response, $args) {
  delete_employee($request->getParsedBody());
});

$app->run();

function get_employee() {
  $db = connect_db();
  $sql = "SELECT * FROM employee ORDER BY `employee_id`";
  $exe = $db->query($sql);
  $data = $exe->fetch_all(MYSQLI_ASSOC);
  $db = null;
  echo json_encode($data);
}

function get_employee_id($employee_id) {
  $db = connect_db();
  $sql = "SELECT * FROM employee WHERE `employee_id` = '$employee_id'";
  $exe = $db->query($sql);
  $data = $exe->fetch_all(MYSQLI_ASSOC);
  $db = null;
  echo json_encode($data);
}

function add_employee($data) {
  $db = connect_db();
  $sql = "insert into employee (employee_id,emp_name,emp_contact,emp_role)"
       . " VALUES('$data[employee_id]','$data[emp_name]','$data[emp_contact]','$data[emp_role]')";
  $exe = $db->query($sql);
  $last_id = $db->insert_id;
  $db = null;
  if (!empty($last_id)) {
    echo $last_id;
  } else {
    echo false;
  }
}

function update_employee($data) {
  $db = connect_db();
  $sql = "update employee SET emp_name = '$data[emp_name]',emp_contact = '$data[emp_contact]',emp_role='$data[emp_role]'"
       . " WHERE employee_id = '$data[employee_id]'";
  $exe = $db->query($sql);
  $last_id = $db->affected_rows;
  $db = null;
  if (!empty($last_id)) {
    echo $last_id;
  } else {
    echo false;
  }
}

function delete_employee($employee) {
  $db = connect_db();
  $sql = "DELETE FROM employee WHERE employee_id = '$employee[employee_id]'";
  $exe = $db->query($sql);
  $last_id = $db->affected_rows;  
  $db = null;
  if (!empty($last_id)) {
    echo $last_id;
  } else {
    echo false;
  }
}

?>