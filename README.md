HOW TO USE
- add provider to config/app.php
 ==== PROVIDERS ====
  \Tugelsikile\UserLevel\UserLevel::class,
- migrations
  php artisan vendor:publish --provider="Tugelsikile\UserLevel\UserLevel" --tag="migrations"
- configs and menu collections
 php artisan vendor:publish --provider="Tugelsikile\UserLevel\UserLevel" --tag="config"
- seed the menus and privileges
  php artisan db:seed --class="Tugelsikile\\UserLevel\\Databases\\Seeders\\Seed"
- add middleware "user-level" to every route that you want to check
- 
