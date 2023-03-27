<?php

namespace Tugelsikile\UserLevel\databases\seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Tugelsikile\UserLevel\app\Models\UserLevel;

class UserLevelSeeder extends Seeder
{
    public function run() {
        $levelCollections = collect(config('menus.default_level'));
        $this->command->getOutput()->progressStart($levelCollections->count());
        foreach ($levelCollections as $levelCollection) {
            $this->command->getOutput()->progressAdvance(1);
            $level = UserLevel::where('name', $levelCollection['name'])->first();
            if ($level == null) {
                $level = new UserLevel();
                $level->id = Uuid::uuid4()->toString();
                $level->name = $levelCollection['name'];
                $level->description = $levelCollection['description'];
                $level->is_super = $levelCollection['is_super'];
                $level->can_delete = $levelCollection['can_delete'];
                $level->saveOrFail();
            }
        }
        $this->command->getOutput()->progressFinish();
    }
}
