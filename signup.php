<?php
  
  $page_title = 'Регистрация';
  require_once('header.php');
  require_once('appvars.php');
  require_once('connectvars.php');

  
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if (isset($_POST['submit'])) {
    
    $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
    $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
    $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));
	$email = mysqli_real_escape_string($dbc, trim($_POST['email']));

    if (!empty($username) && !empty($password1) && !empty($password2) && ($password1 == $password2)) {
      
      $query = "SELECT * FROM user WHERE username = '$username'";
      $data = mysqli_query($dbc, $query);
      
      if (mysqli_num_rows($data) == 0) {
        $password = md5($password1);
        $query = "INSERT INTO user (email, username, password, join_date) VALUES ('$email', '$username', '$password', NOW())";
        mysqli_query($dbc, $query);

        
        echo '<p>Ваш новый кабинет был успешно создан. <a href="login.php">Войти</a>.</p>';

        mysqli_close($dbc);
        exit();
      }
      else {
        
        echo '<p class="error">Учетная запись уже существует для этого пользователя. Пожалуйста, используйте другой адрес.</p>';
        $username = "";
      }
    }
    else {
      echo '<p class="error">Необходимо ввести все данные в систему, в том числе желаемого пароля дважды!.</p>';
    }
  }

  mysqli_close($dbc);
?>

    <p>Пожалуйста, введите ваш логин и желаемый пароль, чтобы войти на сайт</p>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <fieldset>
      <legend>Регистрация</legend>
      <label for="username">Логин:</label>
      <input type="text" id="username" name="username"/><br />
      
      
       <label for="email">Email:</label>
      <input type="text" class = "email" id="email" name="email"/><br />
      
      <label for="password1">Пароль:</label>
      <input type="password" id="password1" name="password1" /><br />
      
      
      <label for="password2">Пароль(повторить):</label>
      <input type="password" id="password2" name="password2" /><br />
    </fieldset>
    <input type="submit" value="Зарегистрироваться" name="submit" />
  </form>

<?php
   require_once('footer.php');
?>
