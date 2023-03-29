<?php

namespace Tugelsikile\UserLevel\app\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Tugelsikile\UserLevel\app\Models\Menu;
use Tugelsikile\UserLevel\app\Models\UserLevel;
use Tugelsikile\UserLevel\app\Models\UserPrivilege;

class UserLevelController extends Controller
{

    /* @
     * @param User $user
     * @return object|null
     */
    public static function current(User $user) {
        try {
            $app = new UserLevelController();
            $level = UserLevel::where('id', $user->level)->first();
            $response = (object) [
                'value' => $level->id,
                'label' => $level->name,
                'meta' => (object) [
                    'description' => $level->description,
                    'super' => $level->is_super,
                    'allow_delete' => $level->can_delete,
                    'menus' => $app->menu($level),
                    'privileges' => $app->privilegeCollection($level),
                ]
            ];
            return  $response;
        } catch (\Exception $exception) {
            return null;
        }
    }

    /* @
     * @param UserLevel $userLevel
     * @return \Illuminate\Support\Collection
     */
    public function privilegeCollection(UserLevel $userLevel) {
        try {
            $response = collect();
            $menus = Menu::orderBy('order', 'asc')->get();
            foreach ($menus as $menu) {
                $priv = UserPrivilege::where('level', $userLevel->id)->where('route', $menu->route)->first();
                if ($priv != null) {
                    $response->push((object)[
                        'value' => $priv->id,
                        'label' => $menu->name,
                        'meta' => (object) [
                            'route' => $menu->route,
                            'can' => (object) [
                                'read' => $priv->r, 'create' => $priv->c,
                                'update' => $priv->u, 'delete' => $priv->d
                            ],
                        ]
                    ]);
                }
            }
            return  $response;
        } catch (\Exception $exception) {
            return collect();
        }
    }

    /* @
     * @param UserLevel $userLevel
     * @return \Illuminate\Support\Collection
     */
    public function menu(UserLevel  $userLevel) {
        try {
            $app = new UserLevelController();
            $response = collect();
            $menus = Menu::whereNull('parent')->where('is_function', false)->orderBy('order', 'asc')->get();
            foreach ($menus as $menu) {
                $priv = UserPrivilege::where('level', $userLevel->id)->where('route', $menu->route)->first();
                if ($priv != null) {
                    if ($priv->r) {
                        $response->push((object)[
                            'value' => $priv->id,
                            'label' => $menu->name,
                            'meta' => (object) [
                                'icon' => $menu->icon,
                                'route' => $menu->route,
                                'url' => Route::has($menu->route) ? route($menu->route) : null,
                                'can' => (object) [
                                    'read' => $priv->r, 'create' => $priv->c,
                                    'update' => $priv->u, 'delete' => $priv->d
                                ],
                                'childrens' => $app->menuChildren($menu, $userLevel)
                            ]
                        ]);
                    }
                }
            }
            return  $response;
        } catch (\Exception $exception) {
            return collect();
        }
    }

    /* @
     * @param Menu $data
     * @param UserLevel $userLevel
     * @return \Illuminate\Support\Collection
     */
    private function menuChildren(Menu  $data, UserLevel $userLevel) {
        try {
            $app = new UserLevelController();
            $response = collect();
            $menus = Menu::where('parent', $data->id)->where('is_function', false)->orderBy('order', 'asc')->get();
            foreach ($menus as $menu) {
                $priv = UserPrivilege::where('level', $userLevel->id)->where('route', $menu->route)->first();
                if ($priv != null) {
                    if ($priv->r) {
                        $response->push((object)[
                            'value' => $priv->id,
                            'label' => $menu->name,
                            'meta' => (object) [
                                'icon' => $menu->icon,
                                'route' => $menu->route,
                                'url' => Route::has($menu->route) ? route($menu->route) : null,
                                'can' => (object) [
                                    'read' => $priv->r, 'create' => $priv->c,
                                    'update' => $priv->u, 'delete' => $priv->d
                                ],
                                'childrens' => $app->menuChildren($menu, $userLevel)
                            ]
                        ]);
                    }
                }
            }
            return  $response;
        } catch (\Exception $exception) {
            return collect();
        }
    }

    /* @
     * @param Request|null $request
     * @return \Illuminate\Support\Collection
     */
    public static function allLevel(Request  $request = null) {
        try {
            $app = new UserLevelController();
            $response = collect();
            $levels = UserLevel::orderBy('name', 'asc');
            if (strlen($request->id) > 0) $levels = $levels->where('id', $request->id);
            $levels = $levels->get();
            foreach ($levels as $level) {
                $response->push((object)[
                    'value' => $level->id,
                    'label' => $level->name,
                    'meta' => (object) [
                        'description' => $level->description,
                        'super' => $level->is_super,
                        'allow_delete' => $level->can_delete,
                        'menus' => $app->menu($level),
                    ]
                ]);
            }
            return $response;
        } catch (\Exception $exception) {
            return collect();
        }
    }

    /* @
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     */
    public static function create(Request  $request) {
        try {
            $app = new UserLevelController();
            try {
                $valid = Validator::make($request->all(),[
                    'nama_user_level' => 'required|string|min:3|max:150|unique:user_levels,name',
                    'super_user' => 'required|boolean',
                    'bisa_dihapus' => 'required|boolean'
                ]);
                if ($valid->fails()) throw new \Exception(collect($valid->errors()->all())->join("\n"),400);
                $userLevel = new UserLevel();
                $userLevel->id = Uuid::uuid4()->toString();
                $userLevel->name = $request->nama_user_level;
                $userLevel->description = $request->keterangan;
                $userLevel->is_super = $request->super_user;
                $userLevel->can_delete = $request->bisa_dihapus;
                $userLevel->saveOrFail();
                $app->generatePriv($userLevel);
                $response = $app->allLevel(new Request(['id' => $userLevel->id]))->first();
                return $response;
            } catch (\Exception $exception) {
                throw new \Exception($exception->getMessage(), 400);
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }

    /* @
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public static function update(Request  $request) {
        try {
            $app = new UserLevelController();
            try {
                $valid = Validator::make($request->all(),[
                    'id' => 'required|string|min:20|exists:user_levels,id',
                    'nama_user_level' => 'required|string|min:3|max:150|unique:user_levels,name,' . $request->id . ',id',
                    'super_user' => 'required|boolean',
                    'bisa_dihapus' => 'required|boolean'
                ]);
                if ($valid->fails()) throw new \Exception(collect($valid->errors()->all())->join("\n"),400);

                $userLevel = UserLevel::where('id', $request->id)->first();
                $userLevel->name = $request->nama_user_level;
                $userLevel->description = $request->keterangan;
                $userLevel->is_super = $request->super_user;
                $userLevel->can_delete = $request->bisa_dihapus;
                $userLevel->saveOrFail();
                $response = $app->allLevel(new Request(['id' => $userLevel->id]))->first();
                return $response;
            } catch (\Exception $exception) {
                throw new \Exception($exception->getMessage(), 400);
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }

    /* @
     * @param Request $request
     * @return bool
     * @throws \Exception
     */
    public static function delete(Request  $request) {
        try {
            $app = new UserLevelController();
            try {
                $valid = Validator::make($request->all(),[
                    'id' => 'required|string|min:20|exists:user_levels,id',
                ]);
                if ($valid->fails()) throw new \Exception(collect($valid->errors()->all())->join("\n"),400);

                UserLevel::where('id', $request->id)->delete();
                return true;
            } catch (\Exception $exception) {
                throw new \Exception($exception->getMessage(), 400);
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }

    /* @
     * @param Request $request
     * @return bool
     * @throws \Exception
     */
    public static function checkUncheckPriv(Request  $request) {
        try {
            $app = new UserLevelController();
            try {
                $valid = Validator::make($request->all(),[
                    'privilege' => 'required|string|min:20|exists:user_privileges,id',
                    'type' => 'required|string|in:c,r,u,d',
                    'value' => 'required|boolean'
                ]);
                if ($valid->fails()) throw new \Exception(collect($valid->errors()->all())->join("\n"),400);
                $priv = UserPrivilege::where('id', $request->privilege)->first();
                switch (strtolower($request->type)){
                    case 'c' : $priv->c = $request->value; break;
                    case 'u' : $priv->u = $request->value; break;
                    case 'd' : $priv->d = $request->value; break;
                    case 'r' : $priv->r = $request->value; break;
                }
                $priv->saveOrFail();
                return true;
            } catch (\Exception $exception) {
                throw new \Exception($exception->getMessage(), 400);
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }

    /* @
     * @param UserLevel $userLevel
     * @return void
     * @throws \Throwable
     */
    private function generatePriv(UserLevel  $userLevel) {
        try {
            $menus = Menu::all();
            foreach ($menus as $menu) {
                $priv = new UserPrivilege();
                $priv->id = Uuid::uuid4()->toString();
                $priv->level = $userLevel->id;
                $priv->route = $menu->route;
                $priv->r = false; $priv->c = false; $priv->u = false; $priv->d = false;
                if ($userLevel->is_super) {
                    $priv->r = true; $priv->c = true; $priv->u = true; $priv->d = true;
                }
                $priv->saveOrFail();
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(),500);
        }
    }
}
