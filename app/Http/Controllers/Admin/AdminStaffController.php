<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Hash;
use Illuminate\Support\Facades\Log;
use Session;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\ForgotRequest;
use App\Models\Admin;
use App\Models\AdminStaffRole;
use DataTables;
use Validator;
use App\Traits\UploadTrait;

class AdminStaffController extends Controller
{

  /*
  * Method to display list of admin staff
  * @author Vimal Patel
  * @param $request
  * @since 18/09/2019
  */
  public function index(Request $request)
  {
    $staffData = [];
    $session = Auth::guard('admin')->user();

    if ($request->ajax()) {
      $data = Admin::whereNotIn('id', ['1', $session->id])->where('status', '1')->get();
      return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('role', function ($row) {
            $rolename = '-';
            $role = AdminStaffRole::where('id',$row->role_id)->get()->toArray();

            if( count( $role ) > 0 ) {
              $rolename = '<small class="label label-primary">'.$role[0]['title'].'</small>';
            }
            return $rolename;
      })
      ->addColumn('action', function ($row) {
          $btn = "<a href='".route('admin.adminstaff.edit', $row->id)."' data-toggle='tooltip'  data-id='".$row->id."' data-original-title='Edit' class='edit btn btn-primary btn-sm editProduct'><i class='fa fa-pencil'></i></a>";
          $btn = $btn. '  <a onclick="return confirm(\'Are you sure want to inactive selected staff?\')" href="'.route('admin.adminstaff.delete', $row->id).'" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="edit btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></a>';
          return $btn;
      })
      ->rawColumns(['role','action'])
      ->make(true);
    }
    return view('admin.admin-staff.list', compact('staffData'));
  }

  /*
  * Method to add data of admin staff
  * @author Vimal Patel
  * @param $id
  * @since 18/09/2019
  */
  public function add(){
    $data = [];
    return view('admin.admin-staff.edit', compact('data'));
  }

  /*
  * Method to edit data of admin staff
  * @author Vimal Patel
  * @param $id
  * @since 18/09/2019
  */
  public function edit($id = null){
    $admindata = Admin::where('id', $id)->get();
    $data = $admindata[0];
    return view('admin.admin-staff.edit', compact('data'));
  }

  /*
  * Method to save data of admin staff
  * @author Vimal Patel
  * @param $id
  * @since 18/09/2019
  */
  public function saveStaff(Request $request)
  {
      if (!empty($request->input())) {
         $validateFormResult = $this->validateForm($request);
          if ($validateFormResult->fails()) {
              $request->flashExcept('password', 'profile_img');
              return redirect()->back()->withErrors($validateFormResult->errors());
          }
          if (!empty($request->input('id'))) {
              $adminStaffObj =  Admin::find($request->input('id'));
              if( !empty( $request->input('password') ) ) {
                $adminStaffObj->password =  Hash::make($request->input('password'));
              }
          } else {
              $adminStaffObj =  new Admin();
              $adminStaffObj->username = $request->input('username');
              $adminStaffObj->password =  Hash::make($request->input('password'));
          }

          $adminStaffObj->first_name = $request->input('first_name');
          $adminStaffObj->last_name = $request->input('last_name');
          $adminStaffObj->email = $request->input('email');
          $adminStaffObj->phone = $request->input('phone');

          if ($request->input('actionname') == 'profile' && !empty($request->input('password'))) {
              $adminStaffObj->password =  Hash::make($request->input('password'));
          }
          if ($request->input('actionname') != 'profile') {
              $adminStaffObj->role_id = $request->input('role_id');
          }
          if ($request->has('profile_img')) {
              // Get image file
              $image = $request->file('profile_img');
              $name = time().'.'.$image->getClientOriginalExtension();
              $destinationPath = public_path('/images/profile_img');
              $image->move($destinationPath, $name);
              if (!empty($adminStaffObj->profile_img) && file_exists($destinationPath.'/'.$adminStaffObj->profile_img)) {
                  unlink($destinationPath.'/'.$adminStaffObj->profile_img);
              }
              // Set user profile image path in database to filePath
              $adminStaffObj->profile_img = $name;
          }

          $adminStaffObj->save();
          Session::flash('message', 'Data has been saved successfully!');
          Session::flash('alert-class', 'alert-success');

          if ($request->input('actionname') == 'profile') {
              return redirect()->to('admin/admin-staff-profile');
          }
          return redirect()->to('admin/admin_stafflist');
      }
  }

  /*
  * Method to validate data of admin staff form
  * @author Vimal Patel
  * @param $request
  * @since 18/09/2019
  */
  private function validateForm($request){
      $validFields = [
        'first_name' => 'required|max:255',
        'last_name' => 'required|max:255',
        'email' => 'required|email',
        'role_id' => 'required',
        'profile_img' => 'image|mimes:jpeg,png,bmp,jpg',
      ];
      $addField = [];
      if (empty($request->input('id'))) {
          $addField = [
            'username' => 'required|unique:admin',
            'password' => 'required|between:8,255|confirmed'
          ];
      } else {
        if (!empty($request->input('password'))) {
            $addField = [
              'password' => 'required|between:8,255|confirmed'
            ];
        }        
      }
      $validFields = $validFields + $addField;
      if ($request->input('actionname') == 'profile') {
          unset($validFields['role_id']);        
      }
      return Validator::make($request->all(), $validFields);
  }

  /*
  * Method to delete data of admin staff
  * @author Vimal Patel
  * @param $id
  * @since 18/09/2019
  */
  public function delete($id = null){
    if (!empty($id)) {
        $adminStaffObj =  Admin::find($id);
        $adminStaffObj->status = 0;
        $adminStaffObj->save();
        Session::flash('message', 'Data has been deleted successfully!');
        Session::flash('alert-class', 'alert-success');
    } else {
        Session::flash('message', 'User has been not found!');
        Session::flash('alert-class', 'alert-error');
    }
    return redirect()->to('admin/admin_stafflist');
  }

  /*
  * Method to send email
  * @author Vimal Patel
  * @param $request
  * @since 19/09/2019
  */
  public function sendEmailNow(Request $request){
    try {
      $to = $request->email_to;
      $subject = $request->subject;
      $body = $request->body;

      \Mail::send('emails.default', ['body' => $body ,'title' => $subject], function ($message) use ($to ,$subject) {
          $message->to($to, 'MyVol Admin')->subject($subject);
      });

      session()->flash('app_message', 'your email has been sent successfully.');

      return back();
    } catch (\Exception $e) {
      session()->flash('app_error', 'Error..!'.$e->getMessage());
      return back();
    }
  }
}
