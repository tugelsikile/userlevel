<?php

namespace Tugelsikile\UserLevel\app\Repository;

use App\Models\User;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Tugelsikile\UserLevel\app\Models\Menu;
use Tugelsikile\UserLevel\app\Models\UserLevel;
use Tugelsikile\UserLevel\app\Models\UserPrivilege;

class UserLevelRepository
{
    /* @
     * @return object
     * @throws \Exception
     */
    public function myLevel() {
        try {
            $response = null;
            $user = auth()->user();
            if ($user == null) $user = auth()->guard('api')->user();
            if ($user == null) throw new \Exception("Forbidden",401);
            $response = (object) [ 'menus' => collect(), 'level' => UserLevel::where('level', $user->level)->first(), 'privileges' => collect()];
            $privs = collect();
            $menus = collect();
            $dbMenus = Menu::whereNull('parent')->orderBy('order', 'asc')->where('is_function', false)->get();
            foreach ($dbMenus as $menu) {
                $childrens = collect();
                $priv = UserPrivilege::where('route', $menu->route)->where('level', $user->level)->first();
                if ($priv != null) {
                    $privs->push((object)[
                        'value' => $priv->id,
                        'label' => $menu->name,
                        'meta' => (object) [
                            'route' => $menu->route,
                            'url' => route($menu->route),
                            'privs' => (object) [
                                'read' => $priv->r, 'create' => $priv->c, 'update' => $priv->u, 'delete' => $priv->d
                            ]
                        ]
                    ]);
                    $dataChilds = Menu::where('parent', $menu->id)->where('is_function', false)->orderBy('order', 'asc')->get();
                    foreach ($dataChilds as $dataChild) {
                        $privChild = UserPrivilege::where('route', $dataChild->route)->where('level', $user->level)->first();
                        if ($privChild != null) {
                            $privs->push((object)[
                                'value' => $privChild->id,
                                'label' => $dataChild->name,
                                'meta' => (object) [
                                    'route' => $dataChild->route,
                                    'url' => route($dataChild->route),
                                    'privs' => (object) [
                                        'read' => $privChild->r, 'create' => $privChild->c, 'update' => $privChild->u, 'delete' => $privChild->d
                                    ]
                                ]
                            ]);
                        }
                        if ($privChild->r) {
                            $childrens->push((object)[
                                'value' => $privChild->id,
                                'label' => $dataChild->name,
                                'meta' => (object) [
                                    'route' => $dataChild->route,
                                    'url' => route($dataChild->route),
                                ]
                            ]);
                        }
                    }
                    if ($priv->r) {
                        $menus->push((object)[
                            'value' => $priv->id,
                            'label' => $menu->name,
                            'meta' => (object) [
                                'route' => $menu->route,
                                'url' => route($menu->route),
                                'childrens' => $childrens
                            ]
                        ]);
                    }
                }
            }
            return $response;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(),401);
        }
    }

    /* @
     * @param UserLevel $userLevel
     * @return object
     * @throws \Exception
     */
    private function data(UserLevel $userLevel) {
        try {
            $response = (object) [ 'menus' => collect(), 'level' => UserLevel::where('level', $userLevel->id)->first(), 'privileges' => collect()];
            $privs = collect();
            $menus = collect();
            $dbMenus = Menu::whereNull('parent')->orderBy('order', 'asc')->where('is_function', false)->get();
            foreach ($dbMenus as $menu) {
                $childrens = collect();
                $priv = UserPrivilege::where('route', $menu->route)->where('level', $userLevel->id)->first();
                if ($priv != null) {
                    $privs->push((object)[
                        'value' => $priv->id,
                        'label' => $menu->name,
                        'meta' => (object) [
                            'route' => $menu->route,
                            'url' => route($menu->route),
                            'privs' => (object) [
                                'read' => $priv->r, 'create' => $priv->c, 'update' => $priv->u, 'delete' => $priv->d
                            ]
                        ]
                    ]);
                    $dataChilds = Menu::where('parent', $menu->id)->where('is_function', false)->orderBy('order', 'asc')->get();
                    foreach ($dataChilds as $dataChild) {
                        $privChild = UserPrivilege::where('route', $dataChild->route)->where('level', $userLevel->id)->first();
                        if ($privChild != null) {
                            $privs->push((object)[
                                'value' => $privChild->id,
                                'label' => $dataChild->name,
                                'meta' => (object) [
                                    'route' => $dataChild->route,
                                    'url' => route($dataChild->route),
                                    'privs' => (object) [
                                        'read' => $privChild->r, 'create' => $privChild->c, 'update' => $privChild->u, 'delete' => $privChild->d
                                    ]
                                ]
                            ]);
                        }
                        if ($privChild->r) {
                            $childrens->push((object)[
                                'value' => $privChild->id,
                                'label' => $dataChild->name,
                                'meta' => (object) [
                                    'route' => $dataChild->route,
                                    'url' => route($dataChild->route),
                                ]
                            ]);
                        }
                    }
                    if ($priv->r) {
                        $menus->push((object)[
                            'value' => $priv->id,
                            'label' => $menu->name,
                            'meta' => (object) [
                                'route' => $menu->route,
                                'url' => route($menu->route),
                                'childrens' => $childrens
                            ]
                        ]);
                    }
                }
            }
            return $response;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(),500);
        }
    }

    /* @
     * @param Request $request
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    public function table(Request  $request) {
        try {
            $response = collect();
            $userLevels = UserLevel::orderBy('name', 'asc')->get();
            foreach ($userLevels as $userLevel) {
                $response->push($this->data($userLevel));
            }
            return $response;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(),500);
        }
    }
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
    public function create(Request  $request) {
        try {
            $response = null;
            $userLevel = new UserLevel();
            $userLevel->id = Uuid::uuid4()->toString();
            $userLevel->name = $request->nama_user_level;
            $userLevel->description = $request->keterangan;
            $userLevel->is_super = $request->super_user;
            $userLevel->can_delete = $request->bisa_dihapus;
            $userLevel->saveOrFail();
            $this->generatePriv($userLevel);
            $response = $this->data($userLevel);
            return $response;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(),500);
        }
    }
    public function update(Request  $request) {
        try {
            $response = null;
            $userLevel = UserLevel::where('id', $request->id)->first();
            $userLevel->name = $request->nama_user_level;
            $userLevel->description = $request->keterangan;
            $userLevel->is_super = $request->super_user;
            $userLevel->can_delete = $request->bisa_dihapus;
            $userLevel->saveOrFail();
            $response = $this->data($userLevel);
            return $response;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(),500);
        }
    }
    public function delete(Request  $request) {
        try {
            UserLevel::where('id', $request->id)->delete();
            return true;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(),500);
        }
    }
}
