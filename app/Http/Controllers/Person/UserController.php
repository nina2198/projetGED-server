<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Person\User;
use App\Models\Service\Service;
use App\Models\APIError;
use App\Helpers\Helper;

class UserController extends Controller
{
    public function index(Request $req)
    {
        $data = User::simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($data);
    }

    public function search(Request $req)
    {
        $this->validate($req->all(), [
            'q' => 'present',
            'field' => 'present'
        ]);
        $data = User::where($req->field, 'like', "%$req->q%")->simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($data);
    }

    public function create(Request $req)
    {
        $data = $req->except('avatar');

        $this->validate($data, [
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required',
            'job' => 'required|in:VISITOR, EMPLOYEE, ADMINISTRATOR, SUPERADMIN',
            'login' => 'required|unique:users',
            'email' => 'required|unique:users'
        ]);
       
        $data['password'] = bcrypt($data['password']);
        $data['avatar'] = '';
        //upload image
        if ($file = $req->file('avatar')) {
            $filePath = $this->uploadSingleFile($req, 'avatar', 'users-avatar', ['file', 'mimes:jpg,png,gif,jpeg']);
            $data['avatar'] = $filePath['saved_file_path'];
        }
        $user = new User();
        $user->login = $data['login'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->gender = $data['gender'];
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        if (isset($data['avatar'])) 
            $user->avatar = $data['avatar'];
        if (isset($data['tel'])) 
            $user->tel = $data['tel'];
        if (isset($data['birth_date'])) 
            $user->birth_date = $data['birth_date'];
        if (isset($data['birth_place'])) 
            $user->birth_place = $data['birth_place'];
        if (isset($data['service_id'])) 
            $user->service_id = $data['service_id'];
        if (isset($data['language'])) 
            $user->language = $data['language'];
        $user->save();

        return response()->json($user);
    }

    public function createInternalUser(Request $req)
    {
        $data = $req->only(['first_name','last_name','job','email','gender','birth_date','birth_place','language','tel', 'service_id' ]);

        $this->validate($data, [
            'first_name' => 'required',
            'last_name' => 'required',
            'job' => 'required|in:EMPLOYEE,ADMINISTRATOR',
            'email' => 'required|unique:users',
            'gender' => 'required',
            'service_id' => 'required:exist:services:id'
        ]);
       
        $user = new User();
        $user->email = $data['email'];
        $user->gender = $data['gender'];
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->job = $data['job'];
        $user->service_id = $data['service_id'];
        if (isset($data['tel'])) 
            $user->tel = $data['tel'];
        if (isset($data['birth_date'])) 
            $user->birth_date = $data['birth_date'];
        if (isset($data['birth_place'])) 
            $user->birth_place = $data['birth_place'];
        if (isset($data['language'])) 
            $user->language = $data['language'];
        $user->password = Helper::generate_password();
        $safe_password = $user->password;
        $user->login = Helper::generate_login();
        $user->password = bcrypt($user->password);
        $user->save();
        $service = Service::find($user->service_id);
        $user->password = $safe_password;
        if($user->job == 'EMPLOYEE')
            Helper::send_connexion_data_to_employee($user, $service);
        if($user->job == 'ADMINISTRATOR')
            Helper::send_connexion_data_to_admin($user, $service);
        return response()->json($user);
    }


    public function find($id) {
        if(!$user = User::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("l'utilisateur d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        $user->permissions;
        $user->roles;
        return response()->json($user);
    }

    public function findByEmail($email) {
        if(!$user = User::whereEmail($email)->first()) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("l'utilisateur d'email $email n'existe pas");
            return response()->json($apiError, 404);
        }
        return response()->json($user);
    }

    public function update(Request $req, $id)
    {
        $user = User::find($id);
        if (!$user) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("l'utilisateur d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }

        $data = $req->except('files');

        if (isset($data['password']) && strlen($data['password']) >= 4) {
            $data['password'] = bcrypt($data['password']);
        }

        //upload image
        if ($file = $req->file('files')) {
            $filePath = $this->uploadSingleFile($req, 'files', 'users-avatar', ['file', 'mimes:jpg,png,gif,jpeg']);
            $data['files'] = $filePath['saved_file_path'];
        }

        if (isset($data['login'])) 
            $user->login = $data['login'];
        if (isset($data['email'])) 
            $user->email = $data['email'];
        if (isset($data['password'])) 
            $user->password = $data['password'];
        if (isset($data['gender'])) 
            $user->gender = $data['gender'];
        if (isset($data['first_name'])) 
            $user->first_name = $data['first_name'];
        if (isset($data['last_name'])) 
            $user->last_name = $data['last_name'];
        if (isset($data['birth_date'])) 
            $user->birth_date = $data['birth_date'];
        if (isset($data['birth_place'])) 
            $user->birth_place = $data['birth_place'];
        if (isset($data['files'])) 
            $user->avatar = $data['files'];
        if (isset($data['language'])) 
            $user->language = $data['language'];
        if (isset($data['tel']))
             $user->tel = $data['tel'];

        $user->update();
        $user->avatar = url($user->avatar);

        return response()->json($user);
    }

    public function destroy($id)
    {
        if (!$user = User::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("l'utilisateur d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        $user->delete();

        return response()->json(null);
    }

    public function getUserRolesAndPermisssions($id) {
        $user = new User();
        if(!$user = User::find($id)) {
            $apiError = new APIError();
            $apiError->setStatus("404");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("l'utiisateur d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        $user->roles;
        foreach($user->roles as $role) {
            $role->permissions;
        }
        return response()->json($user);
    }

   // $permission_user = PermissionUser::whereUserIdAndPermissionId($user_id, $permission_id)->first();
   // if($permission_user) //creer une apiError avec code 400 badREquest
   // je cree les relations

   /**
    * @author Ulrich Bertrand
    *Get all the work( the instances of activity) who is waiting for treatment for this users
    */
   public function instances_waiting(Request $req, $id){
        $activityInstances = User::simplePaginate($req->has('limit') ? $req->limit : 15)
        ->find($id)->activitiesInstances
        ->where('status','WAITING') ;
                              
        return response()->json($activityInstances);
   }

   /**
    * @author Ulrich Bertrand
    *Get all the work( the instances of activity) who is Hanging for treatment for this user
    */
    public function instances_hanging(Request $req, $id){
        $activityInstances = User::simplePaginate($req->has('limit') ? $req->limit : 15)
        ->find($id)->activitiesInstances
        ->where('status','HANGING');
                              
        return response()->json($activityInstances);
   }

   public function reinitializePassword(Request $req) {
    $data = $req->only(['email']);
    $user = User::whereEmail($data['email'])->first();
    if(!$user) {
        $apiError = new APIError;
        $apiError->setStatus("404");
        $apiError->setCode("EMAIL_NOT_FOUND");
        $apiError->setMessage("L'adresse email " . $data['email'] . "n'existe pas");
        return response()->json($apiError, 404);
    }

    $password = Helper::generate_password();
    $user->password = bcrypt($password);
    $user->update();

    Helper::send_password_to_user($user, $password);

    $success['status'] = true;
    $success['password'] = $password;
    return response()->json($success);
}

public function getUserAvatar($user_id) {
    if(!$user = User::find($user_id)) {
        $apiError = new APIError;
        $apiError->setStatus("404");
        $apiError->setCode("USER_NOT_FOUND");
        $apiError->setMessage("l'utilisateur d'id $user_id n'existe pas");
        return response()->json($apiError, 404);
    }
    $path = url($user->avatar);
    return response()->json($path);
}

}
