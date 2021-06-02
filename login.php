<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once 'config/connect.php';

$data = $_POST;

if(isset($_POST["login"])){
    if(!empty($data['password']) &&
    !empty($data['username']) ){
        $success = "✅ Logged in successfully!";
        $hash = password_hash($data['password'], PASSWORD_BCRYPT);
        $query = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $query->bindValue(':username', $data["username"]);
        $query->execute();
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if($res === false){
          die('False res');
        } else {
        $validPassword = password_verify(
          !empty($data['password']) ? trim($data['password']) : null, 
          $res['pwd']);
        if($validPassword){
            $_SESSION['user_id'] = $res['id'];
            $_SESSION['username'] = $res['username'];
            $_SESSION['email'] = $res['email'];
            $_SESSION['logged_in'] = time();
            echo "✅ Signed in successfully!";
            header('Location:dashboard.php');
            exit;
        } else {
            die('Incorrect username / password combination!');
          }
        }
      } else {
        $error = "❌ Please fill up all the fields in the form!";       
    }
}

if(isset($_SESSION['logged_in'])){
  header('Location:dashboard.php');
}
?>

<?php include("inc/header.php"); ?>

<div class="container m-auto">
<div class="grid place-items-center mt-24">
  <div class="w-11/12 p-12 bg-white sm:w-8/12 md:w-1/2 lg:w-5/12 rounded-lg shadow-lg">
    <h1 class="text-xl font-semibold">Hello there 👋 <span class="font-normal">please fill in your information to continue</span></h1>
    <?php if(isset($_POST["register"])):?>
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
    <form class="mt-6" action="login.php" method="POST">
    <label for="username" class="block mt-2 text-xs font-semibold text-gray-600 uppercase">Username</label>
      <input id="username" type="username" name="username" placeholder="enter your desired username" autocomplete="username" class="rounded-md transition-all ease-in-out duration-300 outline-none block w-full p-3 mt-2 text-gray-700 bg-gray-200 appearance-none focus:outline-none focus:bg-gray-300 focus:shadow-inner" />
      <label for="password" class="block mt-2 text-xs font-semibold text-gray-600 uppercase">Password</label>
      <input id="password" type="password" name="password" placeholder="enter your password" autocomplete="new-password" class="rounded-md transition-all ease-in-out duration-300 outline-none block w-full p-3 mt-2 text-gray-700 bg-gray-200 appearance-none focus:outline-none focus:bg-gray-300 focus:shadow-inner" />
      <button type="submit" name="login" class="rounded-md transition-all ease-in-out duration-300 w-full py-3 mt-6 font-medium tracking-widest text-white uppercase bg-black shadow-lg outline-none focus:outline-none hover:bg-gray-700 hover:shadow-none">
        Login
      </button>
      <a href="index.php" class="flex justify-between inline-block mt-4 text-xs text-gray-500 cursor-pointer hover:text-black transition-all duration-300 ease-in-out">Don't have an account?</a>
    </form>
</div>
</div>
</div>

<?php include("inc/footer.php"); ?>
