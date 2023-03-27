<?php

namespace Tugelsikile\UserLevel\databases\seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Tugelsikile\UserLevel\app\Models\Menu;
use Tugelsikile\UserLevel\app\Models\UserLevel;
use Tugelsikile\UserLevel\app\Models\UserPrivilege;

class PrivilegeSeeder extends Seeder
{
    public function run() {
        $menus = Menu::all();
        $levels = UserLevel::all();
        $this->command->getOutput()->progressStart($levels->count());
        foreach ($levels as $level) {
            $this->command->getOutput()->progressAdvance(1);
            foreach ($menus as $menu) {
                $priv = UserPrivilege::where('route', $menu->route)->where('level', $level->id)->first();
                if ($priv == null) {
                    $priv = new UserPrivilege();
                    $priv->id = Uuid::uuid4()->toString();
                    $priv->level = $level->id;
                    $priv->route = $menu->route;
                    $priv->c = false; $priv->r = false; $priv->u = false; $priv->d = false;
                    if ($level->is_super && $menu->super) {
                        $priv->c = true; $priv->r = true; $priv->u = true; $priv->d = true;
                    }
                    $priv->saveOrFail();
                }
            }
        }
        $this->command->getOutput()->progressFinish();
    }
}
