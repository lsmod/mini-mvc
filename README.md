# mini-mvc
Minimalist php mvc library under GNU LESSER GENERAL PUBLIC LICENSE

**deprecated prject - here only for historical reasons - don't try to use this in production**

## Usage example

### Controller:
simpleController.php (in inc/controllers/)
```php
require_once('../../controller.php');
require_once('inc/models/name.php');

class simpleController extends controller
{
   protected static $action_get = ['index' => '', 'hello' => ''];
   protected static $action_post = array();
   
   public function index()
   {               
      $this->setView('index'); // the default view is now 'inc/views/index.php'
   }   
   
   public function hello()
   {
      $model = $this->getModel('name');
      $this->view_data->name = $model->getName();
      
      $this->setView('hello');  
   }
}
```

Every action that user are allowed to call has to be declare in **$action_get** or **$action_get** member.<br/>
action_get is for GET resquet <br/>
action_post is for POST request<br/>
(index.php?execute=hello)<br/>
The HTTP param name is define at controller instanciation.
```php
// here user would use index.php?action=hello to call hello function
$controller = new simpleController("action", $db); 
```
### Model:
name.php (in inc/models/)
```php
<?php
class nameModel extends Model
{
   public function getName()
   {
      return $this->db->loadResult("select * FROM `name`;");
   }
}
?>
```

### Views:
index.php (in inc/views/)
```php
Hello you!
```
name.php (in inc/views/)
```php
Hello <?php echo $this->view_data->name; ?>
```

### Html Page:
index.php (directly executed/visited by users)
```php
require_once('inc/assert.php');
require_once('inc/mysqli.php');
require_once('inc/controller.php');
require_once('inc/controllers/simpleController.php');

try {
  $db = new DBmySQLi("login", "pass", "databasename", "hostname");
  $controller = new simpleController("action", $db);
  content =  $controller->display();
   
  // include template and show the page
  require_once('template/index.php');
}
catch (Exception $e) {
  echo $e;
}
```

### Html template:
template/index.php
```php
<!DOCTYPE html>
<html>
<head>
   <title>Your name!</title>
</head>
<body>
  <?php echo $content; ?>
</body>
</html>
```
