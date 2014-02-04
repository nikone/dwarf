dwarf
=====

Minimalistic PHP framework with MVC architecture, routing and ORM

![alt text](https://raw.github.com/nikone/dwarf/master/core/Mountain_king.jpg "Mountain King")

### Install
1. Clone the project
2. Edit application/config/database.php
3. Start php server
4. Code your app :D

### Routes
```php
return array(
  'default_route' => array('controller' => 'home', 'method' => 'index'),
  'user'          => array('controller' => 'user', 'method' => 'show')
)
```

### Controllers
```php
use core\mvc\Controller;
use core\mvc\View;

class User_Controller extends Controller {

  public function show()
  {
    $data['user'] = User::find(1);
    $data['products'] = Product::where('title', 'like', '%'.$_POST['product_name'].'%')->get();
    View::make('home.master', $data);
  }

}
```

### Models
```php
class User extends ORM{}

// Update user of id = 10
$user = User::find(10);
$user->firstname = "newname";
$user->save();

// Create new user
$user = new User();
$user->firstname = "name";
....
$user->save();

//Products get
$data['products'] = Product::where('title', 'like', '%'.$_POST['product_name'].'%')->get();
```

