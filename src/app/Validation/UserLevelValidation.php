<?php

namespace Tugelsikile\UserLevel\app\Validation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserLevelValidation
{
    public function create(Request  $request) {
        try {
            $valid = Validator::make($request->all(),[
                'nama_user_level' => 'required|string|min:3|max:150|unique:user_levels,name',
                'super_user' => 'required|boolean',
                'bisa_dihapus' => 'required|boolean'
            ]);
            if ($valid->fails()) throw new \Exception(collect($valid->errors()->all())->join("\n"),400);
            return $request;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(),400);
        }
    }
    public function update(Request  $request) {
        try {
            $valid = Validator::make($request->all(),[
                'id' => 'required|string|min:20|exists:user_levels,id',
                'nama_user_level' => 'required|string|min:3|max:150|unique:user_levels,name,' . $request->id . ',id',
                'super_user' => 'required|boolean',
                'bisa_dihapus' => 'required|boolean'
            ]);
            if ($valid->fails()) throw new \Exception(collect($valid->errors()->all())->join("\n"),400);
            return $request;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(),400);
        }
    }
    public function delete(Request  $request) {
        try {
            $valid = Validator::make($request->all(),[
                'id' => 'required|string|min:20|exists:user_levels,id',
            ]);
            if ($valid->fails()) throw new \Exception(collect($valid->errors()->all())->join("\n"),400);
            return $request;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(),400);
        }
    }
}
