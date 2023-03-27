<?php
return [
    'default_level' => [
        [
            'name' => 'User', 'description' => 'Default Users', 'is_super' => false, 'can_delete' => false
        ]
    ],
    'menu_collections' => [
        [
            'name' => 'User Levels', 'description' => 'Customize User Level', 'route' => 'auth.users.levels', 'is_function' => false, 'super' => false,
            'childrens' => [
                //strukturnya sama dengan parent
            ]
        ]
    ],
];
