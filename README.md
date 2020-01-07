# Mini-Framework
A mini php framework made for educational purposes, but is stable enough to use in production or actual use cases.

### The frmaework has the following :
- Models
- Controllers
- Middlewares
- Specific Route Middlewares
- Views (with templating engine similar to laravel-blade)

### Usage:
###### Install using composer :
`composer create-project amahe/mini-framework your_project_name`

###### The framework is simple :
* Go to the Settings/router folder and add your route
```Route::Get('/', 'YourController@index');```

* Then add a new controller using the console (handler):
`php console create controller YourController`

* In the controller you can add the method name :
```
public function index() {
	return "My first route response";
}

```

* You can also return View :
```
public function index() {
	return View::Render('myview.php');
}

```