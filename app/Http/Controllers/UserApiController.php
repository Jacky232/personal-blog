<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Auth;

class UserApiController extends Controller
{
    //get method
    public function showUser($id=null){
        if($id==''){
            $users = User::get();
            return response()->json(['users'=>$users],200);
        }
        else{
            $users = User::find($id);
            return response()->json(['users'=>$users],200);
        }
    }

    //Post method add single user
    public function addUser(Request $request){
        if($request->ismethod('post')){
            $data = $request->all();
            //return $data;

            //validation
            $rules = [
                'name'=>'required',
                'email'=>'required|email|unique:users',
                'password'=>'required'
            ];
            $customMessage = [
                'name.required'=>'Name is required',
                'email.required'=>'Email is required',
                'email.email'=>'Email must be valid email',
                'password.required'=>'Password is required',
            ];
            $validator = Validator::make($data,$rules,$customMessage);
            if($validator->fails()){
                return response()->json($validator->errors(),422);
            }
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->save(); 
            // $message='User added succesfully';
            return response()->json(['user'=>$user],201);
        }
    }              


    //Post method add multiple user
    public function addMultipleUser(Request $request){
        if($request->ismethod('post')){
            $data = $request->all();
            //return $data;

            //validation
            $rules = [
                'users.*.name'=>'required',
                'users.*.email'=>'required|email|unique:users',
                'users.*.password'=>'required'
            ];
            $customMessage = [
                'users.*.name.required'=>'Name is required',
                'users.*.email.required'=>'Email is required',
                'users.*.email.email'=>'Email must be valid email',
                'users.*.password.required'=>'Password is required',
            ];
            $validator = Validator::make($data,$rules,$customMessage);
            if($validator->fails()){
                return response()->json($validator->errors(),422);
            }
            foreach($data['users'] as $adduser){
                $user = new User();
                $user->name = $adduser['name'];
                $user->email = $adduser['email'];
                $user->password = bcrypt($adduser['password']);
                $user->save();
                $message='User added succesfully';
            }
            return response()->json(['message'=>$message],201);
        }
    }


    //update user details put api
    public function updateUserDetails(Request $request,$id){
        if($request->ismethod('put')){
            $data = $request->all();
            //return $data;

            //validation
            $rules = [
                'name'=>'required',
                'password'=>'required'
            ];
            $customMessage = [
                'name.required'=>'Name is required',
                'password.required'=>'Password is required',
            ];
            $validator = Validator::make($data,$rules,$customMessage);
            if($validator->fails()){
                return response()->json($validator->errors(),422);
            }
            $user =  User::findOrFail($id);
            $user->name = $data['name'];
            $user->password = bcrypt($data['password']);
            $user->save();
            // $message='User update succesfully';
            return response()->json(['user'=>$user],202);
        }
    }


    //update user single record patch api
    public function updateSingleRecord(Request $request,$id){
        if($request->ismethod('patch')){
            $data = $request->all();
            //return $data;

            //validation
            $rules = [
                'name'=>'required',
            ];
            $customMessage = [
                'name.required'=>'Name is required',
            ];
            $validator = Validator::make($data,$rules,$customMessage);
            if($validator->fails()){
                return response()->json($validator->errors(),422);
            }
            $user =  User::findOrFail($id);
            $user->name = $data['name'];
            $user->save();
            // $message='User update succesfully';
            return response()->json(['user'=>$user],202);
        }
    }


    //delete user parameter
    public function deleteUser($id=null){
        User::findOrFail($id)->delete();
        $message='User delete succesfully';
        return response()->json(['message'=>$message],200);
    }


    //delete user with json
    public function deleteUserJson(Request $request){
        if($request->ismethod('delete')){
            $data = $request->all();
            User::where('id',$data['id'])->delete();
            $message='User delete succesfully';
            return response()->json(['data'=>$data],200);
        }
    }


    //delete multiple user
    public function deleteMultipleUser($ids){
        $ids = explode(',',$ids);
        User::whereIn('id',$ids)->delete();
        $message='User delete succesfully';
        return response()->json(['message'=>$message],200);
    }


    //delete multiple user with json
    public function deleteMultipleUserJson(Request $request){

        $header = $request->header('Authorization');
        if($header==''){
            $message='Authorization is required';
            return response()->json(['message'=>$message],422);
        }else{
            if($header=='eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6InBlcnNvbmFsIGJsb2ciLCJpYXQiOjE1MTYyMzkwMjJ9.ZP00Y3W_tB6eCFQGfBCeEk0VH4muYM-tIy-BZDPepsc'){
                if($request->ismethod('delete')){
                    $data = $request->all();
                    User::whereIn('id',$data['ids'])->delete();
                    // $message='User delete succesfully';
                    return response()->json(['data'=>$data],200);
                }
            }
            else{
                $message='Authorization does not match';
            return response()->json(['message'=>$message],422);
            }
        }
    }

    //register api using passport
    public function registerUserUsingPassport(Request $request){
        if($request->ismethod('post')){
            $data = $request->all();
            //return $data;

            //validation
            $rules = [
                'name'=>'required',
                'email'=>'required|email|unique:users',
                'password'=>'required'
            ];
            $customMessage = [
                'name.required'=>'Name is required',
                'email.required'=>'Email is required',
                'email.email'=>'Email must be valid email',
                'password.required'=>'Password is required',
            ];
            $validator = Validator::make($data,$rules,$customMessage);
            if($validator->fails()){
                return response()->json($validator->errors(),422);
            }
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->save(); 
            if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){
                $user = User::where('email',$data['email'])->first();
                $access_token = $user->createToken($data['email'])->accessToken;
                User::where('email',$data['email'])->update(['access_token'=>$access_token]);
                return response()->json(['user'=>$user,'access_token'=>$access_token],201);
            }else{
                $message='Opps! Something is wrong';
                return response()->json(['message'=>$message],201);
            }
        }
    }


    //login api using passport
    public function loginUserUsingPassport(Request $request){
        if($request->ismethod('post')){
            $data = $request->all();
            //return $data;

            //validation
            $rules = [
                'email'=>'required|email|exists:users',
                'password'=>'required'
            ];
            $customMessage = [
                'email.required'=>'Email is required',
                'email.email'=>'Email must be valid email',
                'email.exists'=>'Email does not exists',
                'password.required'=>'Password is required',
            ];
            $validator = Validator::make($data,$rules,$customMessage);
            if($validator->fails()){
                return response()->json($validator->errors(),422);
            }
            // $user = new User();
            // $user->name = $data['name'];
            // $user->email = $data['email'];
            // $user->password = bcrypt($data['password']);
            // $user->save(); 
            if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){
                $user = User::where('email',$data['email'])->first();
                $access_token = $user->createToken($data['email'])->accessToken;
                User::where('email',$data['email'])->update(['access_token'=>$access_token]);
                return response()->json(['user'=>$user,'access_token'=>$access_token],201);
            }else{
                $message='Invaliad email or password';
                return response()->json(['message'=>$message],201);
            }
        }
    }
}
