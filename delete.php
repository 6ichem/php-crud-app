<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/connect.php';

session_start();

if(!isset($_SESSION['logged_in'])){
    header('Location:login.php');
}

$postID = $_GET['id'];

$delete = $pdo->prepare("DELETE FROM posts WHERE post_id = :postid");
$delete->execute([
  ':postid' => $postID
]);

$success = "Deleted successfully!";

header('Location:dashboard.php');

?>