<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;

class AuthController extends Controller
{
    use TraitResponseTrait;

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(UserRequest $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $input = $request->validated();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('userToken')->plainTextToken;
        $success['user'] =  $user;
        return $this->sendResponse($success, 'تم التسجيل بنجاح.');
    }
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $users = User::all();
        if (count($users) < 1 ) {
            $user = new User();
            $user->name = "Ahmad";
            $user->email = "arabic.bird.sa@gmail.com";
            $user->password = bcrypt("12345678");
            $user->save();
        }

        $request->validate([
            'email' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        if(Auth::attempt($request->only('email', 'password'))){
            // $user = Auth::user();
            $user = $request->user();
            $success['token']     =  $user->createToken('userToken')->plainTextToken;
            $success['user']      =  $user;

            return $this->sendResponse($success, 'تم تسجيل الدخول بنجاح.', 200);
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'بيانات غير صحيحة'], 401);
        }
    }

    /**
    * Logout function
    *
    * @return \Illuminate\Http\Response
    */
    public function logout(Request $request)
    {
        $logout = $request->user()->currentAccessToken()->delete(); // logout from this device
        // $logout = $request->user()->tokens()->delete(); //  logout from all devices
        // $logout = auth()->logout();
        if ($logout) {
            return response()->json([
                'message' => 'تم تسجيل الخروج',
            ], );
        } else {
            return response()->json([
                'message' => 'فشل تسجيل الخروج',
            ], );
        }
    }
}
