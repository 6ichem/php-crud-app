<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/connect.php';

session_start();

if(!isset($_SESSION['logged_in'])){
    header('Location:login.php');
}

$url = $_SERVER['PHP_SELF'];
$seg = explode('/', $url);
$path = 'http://' . $_SERVER['SERVER_NAME'] . $seg[0] . '/' . $seg[1];
$fullPath = $path . '/' . 'img' . '/' . 'avatar.png';

include("config/config.php");
if(isset($_FILES['feat_image'])){
  $title = $_POST['title'];
  $description = $_POST['description'];
  $post_id = $_POST['postid'];
    if(!empty($title)){
        $upload = 1;
        $file_name = $_FILES['feat_image']['name'];
        $file_size = $_FILES['feat_image']['size'];
        $file_tmp = $_FILES['feat_image']['tmp_name'];
        $file_type = $_FILES['feat_image']['type'];
        $target_dir = "assets/uploads";
        $check = getimagesize($_FILES['feat_image']['tmp_name']);
        $target_file = $target_dir . basename($_FILES['feat_image']['name']);
        $tmp = explode('.', $_FILES['feat_image']['name']);
        $file_ext = end($tmp);
        /*$data = [
            'file_name' => $file_name,
            'file_size' => $file_size,
            'file_tmp' => $file_tmp,
            'file_type' => $file_type,
            'target_dir' => $target_dir,
            'file_ext' => $file_ext
        ];
        print_r($data);
        exit();*/
        $extensions = ['jpeg', 'jpg', 'png'];
        if(in_array($file_ext, $extensions) == false){
            $error = "Images have to be in jpeg, jpg and png only";
        } else if(file_exists($target_file)){
            $error = "File already exists";
        } else if($check == false){
            $error = "File is not an image!";
        } else if(empty($error) == true) {
            move_uploaded_file($file_tmp, "assets/uploads/" . $file_name);
            $url = $_SERVER['HTTP_REFERER'];
            $seg = explode('/', $url);
            $path = $seg[0] . '/' . $seg[1] . '/' . $seg[2] . '/' . $seg[3];
            $full_url = $path . '/' . 'assets/uploads/' . $file_name;
            $id = $_SESSION['user_id'];
            $query = $pdo->prepare("UPDATE posts SET title = ':title', description = ':description', feat_image = ':feat_image' WHERE post_id = :postid");
            $query->execute([
            ':title' => $title,
            ':description' => $description,
            ':feat_image' => $full_url,
            ':postid' => $post_id
            ]);
            $success = "Updated successfully!";
        }
    } else {
        $error = "Please fill in the inputs";
    }
}
?>