<?php

namespace Tugelsikile\UserLevel\databases\seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Tugelsikile\UserLevel\app\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run() {
        $menuCollections = collect(config('menus.menu_collections'));

        $this->command->getOutput()->progressStart($menuCollections->count());
        foreach ($menuCollections as $indexParent => $menuCollection) {
            $this->command->getOutput()->progressAdvance(1);
            $menu = Menu::where('route', $menuCollection['route'])->first();
            if ($menu == null) {
                $menu = new Menu();
                $menu->id = Uuid::uuid4()->toString();
            }
            $menu->order = $indexParent;
            $menu->name = $menuCollection['name'];
            $menu->description = $menuCollection['description'];
            $menu->route = $menuCollection['route'];
            $menu->is_function = $menuCollection['is_function'];
            $menu->super = $menuCollection['super'];
            $menu->saveOrFail();
        }
        $this->command->getOutput()->progressFinish();
    }
}
