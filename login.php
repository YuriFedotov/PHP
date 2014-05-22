<?php
  require_once('connectvars.php');
  session_start();
  $error_msg = "";

  
  if (!isset($_SESSION['user_id'])) {
    if (isset($_POST['submit'])) {
      
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
      $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
      $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));

      if (!empty($username) && !empty($user_password)) {
      
		$user_password = md5($user_password); 
        $query = "SELECT user_id, username FROM user WHERE username = '$username' AND password = '$user_password'";
        $data = mysqli_query($dbc, $query);

        if (mysqli_num_rows($data) == 1) {
          
          $row = mysqli_fetch_array($data);
          $_SESSION['user_id'] = $row['user_id'];
          $_SESSION['username'] = $row['username'];
          setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30));    
          setcookie('username', $row['username'], time() + (60 * 60 * 24 * 30)); 
          $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
          header('Location: ' . $home_url);
        }
        else {
          
          $error_msg = 'К сожалению, необходимо ввести правильное имя пользователя и пароль, чтобы войти.';
        }
      }
      else {
        
        $error_msg = 'К сожалению, необходимо ввести свое имя пользователя и пароль для входа в систему';
      }
    }
  }
$page_title = 'Вход в приложение';
  require_once('header.php');


  if (empty($_SESSION['user_id'])) {
    echo '<p class="error">' . $error_msg . '</p>';
?>

  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <fieldset>
      <legend>Вход в приложение</legend>
      <label for="login">Login:</label>
      <input type="text" name="username"/><br />
      <label for="password">Password:</label>
      <input type="password" name="password" />
    </fieldset>
    <input type="submit" value="Войти" name="submit" />
  </form>

<?php
  }
  else {
  
    echo('<p class="login">Вы вошли как ' . $_SESSION['username'] . '.</p>');
	echo '<br /><a href="index.php">Домашняя страница</a>';
  }
?>

<?php
require_once('footer.php');
?>
