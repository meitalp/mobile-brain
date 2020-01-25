<?php

// $link = mysqli_connect('5.153.13.148', 'kfkfk_user_test', 'muY0VCYxW8H50vw6', 'kfkfk_test_db');
// mysqli_query($link, "SET NAMES utf8");
// $sql = "SELECT id,email,phone,ip FROM users";
// $users =  mysqli_query($link, $sql);
// print_r($users);

$dbcon = 'mysql:host=5.153.13.148;dbname=kfkfk_test_db;charset=utf8';
$db = new PDO($dbcon, 'kfkfk_user_test', 'muY0VCYxW8H50vw6');

$users = $db->query("SELECT id,email,phone,ip FROM users")->fetchAll(PDO::FETCH_OBJ);


function getCode($ip){
$curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "appslabs.net/mobile-brain-test/cudade.php",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "ip=$ip",
    CURLOPT_HTTPHEADER => array(
      "ip: 5.153.13.148",
      "Content-Type: application/x-www-form-urlencoded"
    ),
  ));
  $response = curl_exec($curl);
  curl_close($curl);
  $data = json_decode($response);

return <<<EOT
$data->theCity, $data->theCountry
<br>
<img src="http://appslabs.net/mobile-brain-test/images/flags/$data->countryCode.gif">
EOT; 
}


if (isset($_POST['submit'])){
  $token = $_POST['token'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $ip = $_POST['ip'];
  $db->exec("INSERT INTO users(id,email,phone,token,ip) VALUES(null,'$email','$phone','$token','$ip')");
 // $db->prepare("INSERT INTO users (id,email,phone,token,id) VALUES(?,?,?,?,?)")->execute([null,$email,$phone,$token,$ip]);
}

?> 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


  <title>Mobile Brain</title>

</head>
<body>
<div class="container">
        <div class="row">
            <div class="col">
            <h1 class="mt-5 text-center">Mobile Brain Assignment</h1>

                <table class="table table-bordered m-5 text-center">
                    <thead style="background-color: #e3fcff;">
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                   <?php foreach($users as $user) : ?>
                        <tr>
                            <td><?=$user->id?></td>
                            <td><?=$user->email?></td>
                            <td><?=$user->phone?></td>
                            <td><?=getCode($user->ip);?></td>
                        </tr>  
                   <?php endforeach;?>              
                    </tbody>
                </table>

<div class="toggle" style="display: block;">
<form class="mx-auto mt-5" method="POST" action="" novalidate="novalidate" autocomplete="off" id="addForm">
  
          <input type="hidden" name="token" value="<?= bin2hex(random_bytes(16)) ?> 
">
        <div class="form-group">
            <label for="email" class="font-weight-bold">Email:</label>
            <input class="form-control" type="email" name="email" id="email" value="">
        </div>
  
          <div class="form-group">
            <label for="phone" class="font-weight-bold">Phone:</label>
            <input class="form-control" type="phone" name="phone" id="phone">
          </div>

            
          <div class="form-group">
            <label for="ip" class="font-weight-bold">IP:</label>
            <input class="form-control" type="text" name="ip" id="ip">  
          </div>
        <br>
          <input type="submit" name="submit" value="Submit" class="btn btn-info btn-block">
        </form>
      </div>
      
        
        <button type="button" class="btn btn-info btn-block togglelink"><i class="fas fa-plus-circle"></i> Add New User</button>
        <br>
        <br>
        <br>
  </div>
  </div>
</div>

    <script>
      $(document).ready(function() {
        $(".toggle").hide();
        $(".togglelink").on("click", function() {
         $(".toggle").show(1000);
         $(".togglelink").hide(50);
        });
      });

    document.getElementById("addForm").onsubmit = function(){
    location.reload(true);
}
    </script>
</body>
</html>


