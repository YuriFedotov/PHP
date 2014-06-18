<?php
require_once('db_fns.php');

function get_user_urls($username) {
  
  $conn = db_connect();
  $result = $conn->query( "select bm_URL
                           from bookmark
                           where username = '$username'");
  if (!$result)
    return false;

 
  $url_array = array();
  for ($count = 1; $row = $result->fetch_row(); ++$count) {
    $url_array[$count] = $row[0];
  }
  return $url_array;
};
  
function add_bm($new_url) {
 

  echo "Попытка добавления ".htmlspecialchars($new_url).'<br />';
  $valid_user = $_SESSION['valid_user'];
  
  $conn = db_connect();
  $result = $conn->query("select * from bookmark
                         where username='$valid_user' 
                         and bm_URL='$new_url'");
  if ($result && ($result->num_rows>0))
    throw new Exception('Такая закладка уже существует.');

 
  if (!$conn->query( "insert into bookmark values
                     ('$valid_user', '$new_url')"))
    throw new Exception('Не удается вставить закладку в базу данных.'); 

  return true;
} 

function delete_bm($user, $url) {
  
  $conn = db_connect();

  
  if (!$conn->query( "delete from bookmark
                      where username='$user' and bm_url='$url'"))
    throw new Exception('Закладка не может быть удалена.');
  return true;
}

function recommend_urls($valid_user, $popularity = 1) {
  
  $conn = db_connect();
  $query = "select bm_URL
            from bookmark
            where username in (
              select distinct(b2.username)
              from bookmark b1, bookmark b2
              where b1.username = '".$valid_user."'
                and b1.username != b2.username )
            and bm_URL not in (
              select bm_URL
              from bookmark
              where username='".$valid_user."' )
            group by bm_URL
            having count(bm_URL) > ".$popularity;

  if (!($result = $conn->query($query)))
    throw new Exception('Не удается найти закладки для рекомендации.');
  if ($result->num_rows==0)
    throw new Exception('Не удается найти закладки для рекомендации.');

  $urls = array();
  for ($count=0; $row = $result->fetch_object(); $count++) {
    $urls[$count] = $row->bm_URL; 
  }
                              
  return $urls; 
}
