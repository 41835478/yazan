<?php
namespace App\Repositories\User;

use App\Role;
use App\RoleUser;
use App\User;
use Auth;
use DB;
use Session;

class UserRepository implements UserRepositoryContract {

	//默认查询数据
	protected $select_columns = ['id', 'name', 'nick_name', 'telephone', 'email', 'wx_number', 'address', 'status', 'pid', 'livel', 'remark'];

	// 获得用户信息
	public function find($id) {
		return User::with(tableUnionDesign('hasManyRoles', ['roles.id', 'name', 'slug']))
			->select($select_columns)
			->findOrFail($id);
	}

	public function getAllUsers($requestData) {
		/*return User::with(['hasOneShop'=>function($query){
			            $query->select('user_id','name','address');
		*/
		$query = new User(); // 返回的是一个User实例

		$query = $query->addCondition($requestData); //根据条件组合语句

		return $query->with(tableUnionDesign('hasManyRoles', ['roles.id', 'name', 'slug']))
			         ->select(['id', 'name', 'nick_name'])
			         ->paginate(10);
		// return User::with('hasOneShop')->paginate(10);
	}

    //根据用户角色获得用户
    public function getAllUsersByRole($role_id){

        /*$users = DB::table('yz_users')
                   // ->leftJoin('role_user', 'yz_users.id', '=', 'role_user.user_id')

                   // ->select('yz_users.*')
                   ->get();*/

        $users = DB::table('yz_users')
                    ->join('role_user', function ($join) use ($role_id){
                        $join->on('yz_users.id', '=', 'role_user.user_id')
                             ->where('role_user.role_id', '=', $role_id);
                        })
                    ->select('yz_users.id', 'yz_users.name', 'yz_users.nick_name')
                    ->get();

        return $users;
    }

	public function getAllUsersWithDepartments() {
		return User::select(array
			('users.name', 'users.id',
				DB::raw('CONCAT(users.name, " (", departments.name, ")") AS full_name')))
			->join('department_user', 'users.id', '=', 'department_user.user_id')
			->join('departments', 'department_user.department_id', '=', 'departments.id')
			->lists('full_name', 'id');
	}

	public function create($requestData) {

		// dd($requestData->all());
		$password = bcrypt($requestData->password);
		$role_id = $requestData->role_id;

		$role_info = Role::findOrFail($role_id);

		/*p($role_id);
		dd(lastSql());*/
		// dd($role_info);

		// 添加用户到用户表
		$input = array_replace($requestData->all(), ['password' => "$password", 'creater_id' => Auth::id(), 'level' => $role_info->level]);

		dd($input);

		$user = User::create($input);

		// 关联用户表与用户-角色表
		$userRole = new RoleUser;
		$userRole->role_id = $role_id;
		$userRole->user_id = $user->id;
		$userRole->save();

		Session::flash('sucess', '添加用户成功');
		return $user;
	}

	public function update($id, $requestData) {
		// dd($requestData->all());
		$user = User::with(tableUnionDesign('hasManyRoles', ['roles.id', 'name', 'slug']))
			->findorFail($id);

		/*p($requestData->role_id);
        dd($user->hasManyRoles[0]->id);*/

		$user->name = $requestData->name;
		$user->nick_name = $requestData->nick_name;
		$user->telephone = $requestData->telephone;
		$user->shop_id = $requestData->shop_id;
		$user->status = $requestData->status;
		$user->address = $requestData->address;
		$user->qq_number = $requestData->qq_number;
		$user->wx_number = $requestData->wx_number;
		$user->email = $requestData->email;

		// 更新用户
		$user->save();

		//如果角色有变化，更新UserRole表
		if ($requestData->role_id != $user->hasManyRoles[0]->id) {

			$user_id = $id; //当前用户ID
			$role_id = $user->hasManyRoles[0]->id; //角色ID
			// 获得需要更新的对象
			$user_role = RoleUser::where('user_id', $user_id)
				->where('role_id', $role_id)
				->first();
			// dd($requestData->role_id);
			$user_role->role_id = $requestData->role_id;

			$user_role->save();
		}

		Session()->flash('sucess', '更新用户成功');

		return $user;
	}

	public function destroy($id) {
		if ($id == 1) {
			return Session()->flash('faill', '超级管理员不允许删除');
		}
		try {
			$user = User::findorFail($id);
			$user->delete();
			Session()->flash('sucess', '删除管理员成功');

		} catch (\Illuminate\Database\QueryException $e) {
			Session()->flash('faill', '删除管理员失败');
		}

	}

	//获得用户角色信息
	public function getRoleInfoById($id = '') {

		$role_id = '';

		if (empty($id)) {
			//若ID为空，则获得当前用户ID

			// dd(Auth::user()->hasManyRoles[0]->id);
			$role_id = Auth::user()->hasManyRoles[0]->id;
		} else {

			// dd(User::findOrFail($id)->hasManyRoles[0]->id);
			$role_id = User::findOrFail($id)->hasManyRoles[0]->id;
		}

		$role_info = Role::find($role_id);
		// dd($role_info);
		return $role_info;
	}
}
