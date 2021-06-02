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
            $query = $pdo->prepare("INSERT INTO posts (title, description, feat_image, user_id) VALUES (:title, :description, :feat_image, :user_id)");
            $query->execute([
            ':title' => $title,
            ':description' => $description,
            ':feat_image' => $full_url,
            ':user_id' => $id,
            ]);
            $success = "Posted successfully!";
        }
    } else {
        $error = "Please fill in the inputs";
    }
}

$id = $_SESSION['user_id'];
?>

<?php include("inc/header.php"); ?>

<!-- This is an example component -->

<div class="h-full md:px-32 px-4 mx-auto">
 
  <div class="border-b-2 block">

    <form class="w-full p-4 sm:p-6 lg:p-8 bg-white lg:ml-4 shadow-md" method="POST" action="new-post.php" enctype="multipart/form-data">
<div>
    <?php if(isset($_POST["title"])):?>
    <?php if(isset($error)):?>
        <div class="mt-6 block text-sm text-red-600 bg-red-200 border border-red-400 h-12 flex items-center p-4 rounded-sm relative" role="alert">
   <?php echo $error ?>
    <button type="button" data-dismiss="alert" aria-label="Close" onclick="this.parentElement.remove();">
        <span class="absolute top-0 bottom-0 right-0 text-2xl px-3 py-1 hover:text-red-900" aria-hidden="true" >×</span>
    </button>
  </div>
  <?php elseif(isset($success)) :?>
    <div class="mt-6 block text-sm text-left text-yellow-600 bg-yellow-200 border border-yellow-400 h-12 flex items-center p-4 rounded-sm" role="alert">
   <?php echo $success ?>
  </div>
  <?php endif;?>
  <?php endif;?>
</div>

      <div class="rounded">
        <div class="pb-4">
          <label for="title" class="font-semibold text-gray-700 block pb-1">Title</label>
          <input placeholder="Title" id="title" name="title" class="border-2 border-gray-400 transition-all ease-in-out duration-300 focus:bg-gray-200 focus:outline-none outline-none rounded-lg text-gray-700 px-4 py-2 w-full" type="text" />
          <label class="w-full my-3 flex flex-col items-center px-4 py-6 bg-white text-blue rounded-lg tracking-wide uppercase border border-gray-400 cursor-pointer">
        <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
        </svg>
        <span class="mt-2 text-base leading-normal">Select a file</span>
        <input type='file' name="feat_image" class="hidden" />
    </label>
    <label for="description" class="font-semibold text-gray-700 block pb-1">Description</label>
    <textarea placeholder="Description" id="description" name="description" class="h-40 resize-none border-2 border-gray-400 transition-all ease-in-out duration-300 focus:bg-gray-200 focus:outline-none outline-none rounded-lg text-gray-700 px-4 py-2 w-full" type="text"></textarea>
        </div>
      </div>
      <button type="submit" name="post" class="rounded-md transition-all ease-in-out duration-300 w-full py-3 font-medium tracking-widest text-white uppercase bg-black shadow-lg outline-none focus:outline-none hover:bg-gray-700 hover:shadow-none">
        Post
      </button>
    </form>

  </div>
 
</div>

<?php include("inc/footer.php"); ?>
