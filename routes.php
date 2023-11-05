<?php

require_once './config/constants.php'

require_once __DIR__.'/router.php';

// Static GET
get(BASE_URL, 'views/index.php');

// Dynamic GET. Example with 1 variable
get(BASE_URL . 'user/$id', 'views/user');

// Dynamic GET. Example with 2 variables
get(BASE_URL . 'user/$name/$last_name', 'views/full_name.php');

// Dynamic GET. Example with 2 variables with static
get(BASE_URL . 'product/$type/color/$color', 'product.php');

// A route with a callback
get(BASE_URL . 'callback', function(){
  echo 'Callback executed';
});

// A route with a callback passing a variable
get(BASE_URL . 'callback/$name', function($name){
  echo "Callback executed. The name is $name";
});

// Route where the query string happens right after a forward slash
get(BASE_URL . 'product', '');

// A route with a callback passing 2 variables
get(BASE_URL . 'callback/$name/$last_name', function($name, $last_name){
  echo "Callback executed. The full name is $name $last_name";
});

// Route that will use POST data
post(BASE_URL . 'user', '/api/save_user');

// For GET or POST
// The 404.php which is inside the views folder will be called
// The 404.php has access to $_GET and $_POST
any(BASE_URL . '404','views/404.php');
