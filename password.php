<?php
  require_once('connectvars.php');  
  $page_title = 'Востановление пароля';
  require_once('header.php');
  
  
if (isset($_POST['submit'])){     
    $username = $_POST['username'];
    $email = $_POST['email'];
	if (empty($username)){
		echo "Введите логин!";
	}
	elseif (empty($email)){
		echo "Введите e-mail!";
	}
   else{        
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT * FROM user WHERE username = '$username' AND email = '$email'";
		$resultat = mysqli_query($dbc, $query);
		$array = mysqli_fetch_array($resultat);
		if (empty($array)){
			echo 'Ошибка! Такого пользователя не существует';
		}
		elseif (mysqli_num_rows($resultat) > 0){
			$chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP"; 
			$max=10; 
			$size=StrLen($chars)-1; 
			$password=null; 
			
			while($max--){
			$password.=$chars[rand(0,$size)]; 
			}
			$newmdPassword = md5($password); 
			$title = 'Востановления пароля пользователю '.$username.' для сайта testjop.com';
			$from = 'admin@testjop.com';
			$letter =   'Вы запросили восстановление пароля для аккаунта '.$username.' на сайте ... \r\nВаш новый пароль: '.$password;
                        if (mail($email, $title, $letter, 'Админестрация сайта:' . $from)) {
			   mysqli_query($dbc, "UPDATE user SET password = '$newmdPassword' WHERE username = '$username'  AND email = '$email'");
			   echo 'Новый пароль отправлен на ваш e-mail!<br><a href="index.php">Главная страница</a>';
			}
		}		
	}
        mysqli_close($dbc);
}
?>
<fieldset>
<table>
    <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <legend>Востановление пароля</legend>
      <form method="POST">
      <tr>
      <td>Логин:</td>
      <td><input type="text" size="20" name="username" ></td>
      </tr>
      <tr>
      <td>E-mail:</td>
      <td><input type="text" size="20" name="email"></td>
      </tr>
      <tr>
      <td></td>
      </fieldset>
      <td colspan="2"><input type="submit" value="Восстановить пароль" name="submit" ></td>
      </tr>
     <br>
      </form>
   </table>
     </fieldset>
      </body>
</html>
