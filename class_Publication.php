<?php

abstract class Publication
{
   
    protected $table;
    
    
    protected $properties = array();
    
   
    public function __construct($id) {
      
	  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 

         
      $query = 'SELECT * FROM `'.$this->table.'` WHERE `id`="'.$id.'" LIMIT 1';
      $data = mysqli_query($dbc, $query);
	
        
	 
      $this->properties = mysqli_fetch_array($data);
    
    }
    
    public function get_property($name)
    {
        if (isset($this->properties[$name]))
            return $this->properties[$name];
            
        return false;
    }
    
   
    public function set_property($name, $value)
    {
        if (!isset($this->properties[$name]))
            return false;
            
        $this->properties[$name] = $value;
        
        return $value;
    }
    
    
    abstract public function do_print();
}

 
class News extends Publication
{
    
    public function __construct($id)
    {
     
        $this->table = 'news_table';
   
        parent::__construct($id);
    }
    
    
    public function do_print()
    {
        echo $this->properties['title'];
        echo '<br /><br />';
        echo $this->properties['text'];
        echo '<br />Источник: '.$this->properties['source'];
    }
}

class Announcement extends Publication
{
    
    public function __construct($id)
    {
     
        $this->table = 'announcements_table';
        
        parent::__construct($id);
    }
    
   
    public function do_print()
    {
        echo $this->properties['title'];
        echo '<br />Внимание! Объявление действительно до '.$this->properties['end_date'];
        echo '<br /><br />'.$this->properties['text'];
    }
}

class Article extends Publication
{
    
    public function __construct($id)
    {
        
        $this->table = 'articles_table';
      
        parent::__construct($id);
    }
    
  
    public function do_print()
    {
        echo $this->properties['title'];
        echo '<br /><br />';
        echo $this->properties['text'];
        echo '<br />&copy; '.$this->properties['author'];
    }
}




  

  define('DB_HOST', 'localhost');
  define('DB_USER', 'root');
  define('DB_PASSWORD', '');
  define('DB_NAME', 'db');

$news_id = 1;
$announcement_id = 1;
$article_id = 1;


$publications[] = new News($news_id);
$publications[] = new Announcement($announcement_id);
$publications[] = new Article($article_id);

foreach ($publications as $publication) {

    
    if ($publication instanceof Publication) {
        $publication->do_print(); 
    } else {
        
    }
}
