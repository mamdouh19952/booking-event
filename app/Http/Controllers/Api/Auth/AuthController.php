<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
   


    public function register(Request $request) {

      

        $errors = Validator::make($request->all(),[
            'name' => 'required|string|min:6',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]

        );
        if( $errors->fails()){   
            return response()->json([
                'status' => false,
                'success' => false,
                'message' => 'validation error',
                'errors' => $errors->errors()
            ], 422);
        }
        else{
          $hashPassword =  Hash::make($request->password);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' =>$hashPassword 
            ]);
            $token = $user->createToken("API TOKEN")->plainTextToken ;
            return response()->json([
                'status' => true,
                'success' => true,
                'message' => 'User Created Successfully',
                'token' => $token,
                'user' => $user
               
            ], 201);
        }
             

    }

 

    public function login(LoginRequest $request) {
       $request->validated();   

         $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
   
        $token = $user->createToken("API TOKEN")->plainTextToken ;
        return response()->json([
            'status' => true,
            'success' => true,
            'message' => 'Login Successfully',
            'token' => $token,
            'user' => new UserResource  ($user),
        ], 200);        

    }

public function logout(Request $request) {
    // dd($request->user());
    $request->user()->currentAccessToken()->delete();
    return response()->json([
        'status' => true,
        'success' => true,
        'message' => 'Logout Successfully',
    ], 202);
}

//


    
//     public function login(Request $request) {
//                 $validator = Validator::make($request->all(),[
//                 'email' => 'required|email',
//                  'password' => 'required|min:6'
//                                 ]
//                                 );
//         if( $validator->fails()){   
//             return response()->json([
//                 'status' => false,
//                 'success' => false,
//                 'message' => 'validation error',
//                 'errors' => $validator->errors()
//             ], 422);
//         }



//         $user = User::where('email', $request->email)->first();

//         if (!$user || !Hash::check($request->password, $user->password)) {
//             return response()->json([
//                 'status' => false,
//                 'success' => false,
//                 'message' => 'Unauthorized'
//             ], 401);
//         }
   
//         $token = $user->createToken("API TOKEN")->plainTextToken ;
//         return response()->json([
//             'status' => true,
//             'success' => true,
//             'message' => 'Login Successfully',
//             'token' => $token,
//             'user' => $user
//         ], 200);
// //  $vaild=   Auth::attempt(['email' => $request->email, 'password' => $request->password]);
// //  $vaild=   Auth::attempt($request->only('email', 'password'));
// //      if(!$vaild){
// //         return response()->json([
// //             'status' => false,
// //             'success' => false,
// //             'message' => 'Unauthorized'
// //         ], 401);
// //      }
// //      else{
// //         // $user = User::where('email', $request->email)->first(); ??????
// //         $user=Auth::user();
// //         $token = $user->createToken("API TOKEN")->plainTextToken ;
// //         return response()->json([
// //             'status' => true,
// //             'success' => true,
// //             'message' => 'Login Successfully',
// //             'token' => $token,
// //             'user' => $user
// //         ], 200);
// //      }
//     }


   

     // public function logout(Request $request) {
    //     $request->user()->currentAccessToken()->delete();
    //     return response()->json([
    //         'status' => true,
    //         'success' => true,
    //         'message' => 'Logout Successfully',
    //     ], 200);
    // }
}