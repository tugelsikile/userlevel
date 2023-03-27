<?php

namespace Tugelsikile\UserLevel\app\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tugelsikile\UserLevel\app\Repository\UserLevelRepository;
use Tugelsikile\UserLevel\app\Validation\UserLevelValidation;

class UserLevelController extends Controller
{
    protected $repository;
    protected $validation;
    public function __construct()
    {
        $this->validation = new UserLevelValidation();
        $this->repository = new UserLevelRepository();
    }

    /* @
     * @return string
     */
    public function crud(Request  $request)
    {
        try {
            $params = null; $message = 'Undefined method'; $code = 400;
            switch (strtolower($request->method())) {
                case 'get' :
                    $params = $this->repository->myLevel();
                    $code = 200; $message = 'ok';
                    break;
                case 'post' :
                    $params = $this->repository->table();
                    $code = 200; $message = 'ok';
                    break;
                case 'put' :
                    $valid = $this->validation->create($request);
                    $params = $this->repository->create($valid);
                    $code = 200;
                    $message = 'User Level berhasil dibuat';
                    break;
                case 'patch' :
                    $valid = $this->validation->update($request);
                    $params = $this->repository->update($valid);
                    $code = 200;
                    $message = 'User Level berhasil diubah';
                    break;
                case 'delete' :
                    $valid = $this->validation->delete($request);
                    $params = $this->repository->delete($valid);
                    $code = 200;
                    $message = 'User Level berhasil dihapus';
                    break;
            }
            return  response()->json(['mesasge' => $message, 'params' => $params], $code);
        } catch (\Exception $exception) {
            if ($request->isJson()) {
                return  response()->json(['message' => $exception->getMessage()], $exception->getCode());
            } else {
                throw new \Exception($exception->getMessage(), $exception->getCode());
            }
        }
    }
}
