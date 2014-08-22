#Usage:

##Install from composer
```json
{
    "require": {
        "nicklasos/router": "dev-master"
    }

}
```

##Router
```php

require 'vendor/autoload.php';

use Nicklasos\Router\App,
    Nicklasos\Router\View;

$app = new App;

$view = new View;
$view->setViewsPath(__DIR__ . '/views');
$view->setLayout('layout');

$app->get('/', function () {
    return 'home ';
});

$app->get('test/views', function () use ($view) {
    return $view->render('index', [
        'viewName' => 'This is index.php view file',
        'title' => 'Layout'
    ]);
});

$app->get('user/:id', function () {
    return $_GET['id'];
});

$app->get('test/:param/view/:test', function () {
    return $_GET['param'] . $_GET['test'];
});

$app->get('test', function () {
    return 'test';
});

$app->get('user/profile', function () {
    return 'user/profile';
});

$app->get('test/1/2', function () {
    return 'test/1/2';
});

$app->notFound(function () {
    return 'Not found';
});

$app->run();
```
##Template
```html
<!-- layout.php -->
<h3><?= $title ?></h3>
<?= $this->render($view, $data) ?>

<!-- index.php -->
<p><?= viewName ?></p>
```
