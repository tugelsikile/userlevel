### HOW TO USE
- add provider to `config/app.php`
 
  `\Tugelsikile\UserLevel\UserLevel::class,`
  
- migrations

  `php artisan vendor:publish --provider="Tugelsikile\UserLevel\UserLevel" --tag="migrations"`
- configs and menu collections

 `php artisan vendor:publish --provider="Tugelsikile\UserLevel\UserLevel" --tag="config"`
- seed the menus and privileges

  `php artisan db:seed --class="Tugelsikile\\UserLevel\\Databases\\Seeders\\Seed"`
  
- add middleware `user-level` to every route that you want to check


### Available Method

- get current level, menu, and privileges of current user
  
  `UserLevelController::current($user);` 
- get all menu of user

  `UserLevelController::menu($level);`
- get all user level

  `UserLevelController::allLevel();`
