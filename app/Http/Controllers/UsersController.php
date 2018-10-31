<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{

    //权限中间件
    public function __construct()
    {
        $this->middleware('auth',['except'=>['show']]);
    }

    //个人中心
    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }

    //编辑资料
    public function edit(User $user)
    {
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    //提交编辑资料
    public function update(UserRequest $request,User $user,ImageUploadHandler $upload)
    {
        $this->authorize('update', $user);
        $data = $request->all();

        if($request->avatar){
            $result = $upload->save($request->avatar,'avatar',$user->id);
            if($result){
                $data['avatar'] = $result['path'];
            }
        }
        $user->update($data);
        return redirect()->route('users.show',$user->id)->with('success','个人资料更新成功');
    }
}
