<?php
require "./db.php";
// api 
$data = ['ok'=>false,'code'=>null,'message'=>null,'result'=>[]];
$get = explode("/", $_SERVER['REDIRECT_QUERY_STRING']);
$action = $get[1];

if($action == "signin"){
    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = realstring($_POST['username']);
        $password = realstring($_POST['password']);
        // check db 
        $slt = "SELECT * FROM 'admins' WHERE username = '{$username}' AND pass = '{$password}'";
        $query = mysqli_query($conn, $slt);
        if(mysqli_num_rows($query)>0){  
            $data['ok'] = true;
            $data['code'] = 200;
            $data['message'] = "All correct";
            foreach($query as $key => $val){
                $data['result'][] = $val;
            }
        }else {
            $data['message'] = "Password or username didn't match";
        } 
    }

} else if($action == "addblog"){
    if(isset($_POST['token'])){
        $token = realstring(trim($_REQUEST['token']));
        $slt = "SELECT * FROM `admin` WHERE unique_id = '{$token}'";
        $query = mysqli_query($conn, $slt);
        if(mysqli_num_rows($query)>0){
          if(isset($_POST['title']) && isset($_POST['text']) && isset($_POST['date']) && isset($_FILES['image'])){
               $title = realstring($_POST['title']);                                                  
               $text = realstring($_POST['text']);                                                  
               $date = realstring($_POST['date']);                                                  
               $file = realstring($_FILES['image']['tmp_name']);                                                  
               $file_name = realstring($_FILES['image']['name']);
               # check folder for image
               if(file_exists("../uploads/".$file_name)){
                    #file found in folder
                    $changed = time() . $file_name ;
                    move_uploaded_file($file, "../uploads/".$changed); #file downloaded to ../uploads/

                    $ins = "INSERT INTO 'blog' ('title','text','date','image') VALUES ('{$title}','{$text}','{$date}','{$changed}')";
                    $query= mysqli_query($conn, $ins);
                    if($query){
                        $date['ok'] = true;
                        $date['code'] = 200;
                        $date['message'] = "All correct, Blog has been added";
                    } else {
                        $date['message'] = "OPSS.... Something wrong went, please try again later or connect with developers";
                    }
                } else {
                    #file not found
                    move_uploaded_file($file, "../uploads/".$file_namele); #file downloaded to ../uploads/
                    $ins = "INSERT INTO 'blog' ('title','text','date','image') VALUES ('{$title}','{$text}','{$date}','{$file_namele}')";
                    $query= mysqli_query($conn, $ins);
                    if($query){
                        $date['ok'] = true;
                        $date['code'] = 200;
                        $date['message'] = "All correct, Blog has been added";
                    } else {
                        $date['message'] = "OPSS.... Something wrong went, please try again later or connect with developers";
                    }
                }
          }
        }
    }else {
        $data['code'] = 404;
        $data['message'] = "Admmin unique id not found";
    }
} else if($action == "deleteblog"){
    if(isset($_POST['token'])){
        $token = realstring(trim($_REQUEST['token']));
        $slt = "SELECT * FROM `admin` WHERE unique_id = '{$token}'";
        $query = mysqli_query($conn, $slt);
        if(mysqli_num_rows($query)>0){
          if(isset($_POST['id'])){
               $id = realstring($_POST['id']);
               #do request for get file name
               $slt = "SELECT * FROM `blog` WHERE id = '{$id}'";
               $query = mysqli_query($conn, $slt);
               if(mysqli_num_rows($query)>0){
                  foreach($query as $key => $val){
                    #file deleted
                    unlink("../uploads/".$val['image']);
                  }
               } else {
                $data['message'] = "ID not found in DB";
               }
          }
        }
        #delete blog
        $del = "DELETE FROM 'blog' WHERE id = '{$id}'";
        $query = mysqli_query($conn, $del);
        if($query){
            $data['ok'] = true;
            $data['code'] = 200;
            $data['message'] = "Item deleted successfuly";
        } else {
            $data['message'] = "OOPPSS.. Something wrong went";
        }
    }else {
        $data['code'] = 404;
        $data['message'] = "Admmin unique id not found";
    }
}

$api = json_encode($data);
print_r($api);
?>