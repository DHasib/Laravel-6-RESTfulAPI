<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Validator;


class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //fetch all the user data............................
        $users = User::all();
       //return fetch data into json format......................
        return  $this->showAll($users);

       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       //validate user data for request to store/create..............
       $rules = Validator::make($request->all(),[
               'name'     =>  "required",
               'email'    =>  "required|email|unique:users",
               'password' =>  "required|min:6",
       ])->validate();

       $data = $request->all();
       $data['password'] = bcrypt($request->password);
       $data['verified'] = User::UNVERIFIED_USER;
       $data['verification_token'] = User::generateVerificationCode();
       $data['admin'] = User::REGULAR_USER;

       $user = User::create($data);

       return  $this->showOne($user, 201);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return  $this->showOne($user);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    //fetch the user data using auth user id............................
        $user = User::findOrFail($id);
    //validate user data for request to Update..............
        $rules = Validator::make($request->all(),[
            'email'     =>  "email|unique:users,email," . $user->id,
            'password'  =>  "min:6|confirmed",
            'admin'     =>  "in:" . User::ADMIN_USER . "," . User::REGULAR_USER,
        ])->validate();

    //check user name field is filled and then insert name into database..............
      if($request->has('name')){
          $user->name = $request->name;
      }
    //check user email field is filled and also check email is unique or not then insert email into database..............
      if($request->has('email') && $user->email != $request->email){
         $user->verified = User::UNVERIFIED_USER;
         $user->verification_token = User::generateVerificationCode();
         $user->email = $request->email;
      }
    //check password field is filled and then hash password and insert password into database..............
      if($request->has('password')){
        $user->password = bcrypt($request->password);
      }

      //first if condition check user request has admin or not........................
       if ($request->has('admin')) {
           //2nd if condition check user are verified or not to update admin field................................
           if (!$user->isVerified()) {
              return $this->errorResponse('Only verified user can modify the admin field', 409);
           }
         $user->admin = $request->admin;
       }

       //This if condition check user Updated value into database or not and through an message..............................
       if (!$user->isDirty()) {
            return $this->errorResponse( 'You need to specify a different Value to Update', 422);
       }
         
       $user->save();

       return  $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //fetch the user data using auth user id............................
        $user = User::findOrFail($id);

        $user->delete();
        return  $this->showOne($user);

    }
}
