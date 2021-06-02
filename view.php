<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/connect.php';

session_start();

$postID = $_GET['id'];
$userID = $_GET['user'];

$post = $pdo->prepare("SELECT * FROM posts WHERE post_id = :postid");
$post->execute([
  ':postid' => $postID,
]);

$profile = $pdo->prepare("SELECT * FROM profile WHERE id = :userid");
$profile->execute([
  ':userid' => $userID
]);

$user = $pdo->prepare("SELECT * FROM users WHERE id = :userid");
$user->execute([
  ':userid' => $userID
]);

$res = $post->fetch(PDO::FETCH_ASSOC);
$res2 = $profile->fetch(PDO::FETCH_ASSOC);
$res3 = $user->fetch(PDO::FETCH_ASSOC);

$postId = $res['post_id'];
$postImg = $res['feat_image'];
$postTitle = $res['title'];
$postDescription = $res['description'];
$userId = $res2['id'];
$userImage = $res2['avatar'];
$userProfession = $res2['profession'];
$userName = $res3['username'];
?>

<?php include("inc/header.php"); ?>

    <div class='flex max-w-xl my-10 bg-white shadow-md rounded-lg overflow-hidden mx-auto'>
        <div class='flex items-center w-full'>
            <div class='w-full'>
                <div class="flex flex-row mt-2 px-2 py-3 mx-3">
                    <div class="w-auto h-auto rounded-full border-2 border-pink-500">
                        <img class='w-12 h-12 object-cover rounded-full shadow cursor-pointer' alt='User avatar' src='<?php echo $userImage ?>'>
                    </div>
                    <div class="flex flex-col mb-2 ml-4 mt-1">
                        <div class='text-gray-600 text-sm font-semibold my-auto'><?php echo $userName ?></div>
                        <div class='text-blue-700 font-base text-xs mr-1 mt-1 cursor-pointer'>
                                <?php echo $userProfession ?>
                        </div> 
                    </div>
                </div>
                <div class="border-b border-gray-100"></div> 
                <div class='text-gray-400 font-medium text-sm mb-3 mt-1 mx-3 px-2'><img class="rounded" src="<?php echo $postImg ?>"></div>
                <div class='text-gray-600 font-semibold text-lg mb-2 mx-3 px-2'><?php echo $postTitle ?></div>
                <div class='text-gray-500 font-thin text-sm mb-6 mx-3 px-2'><?php echo $postDescription ?></div>
            </div>
        </div>
</div>

<?php include("inc/footer.php"); ?>
