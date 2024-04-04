<?php

namespace App\Http\Controllers\admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Entities\User;
use Modules\User\Entities\UserType;
use Modules\User\Entities\UserAddresses;
use Modules\User\Entities\ClientType;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Modules\User\Notifications\NewUserRegistration;
use Mail;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\Media;
use Modules\Transaction\Entities\TransactionCommission;
use Modules\Transaction\Entities\SalesAdvice;
use Modules\Transaction\Entities\Transaction;
use Modules\Project\Entities\Projects;
use Modules\Project\Entities\Units;
use Modules\Transaction\Entities\TransactionHistory;

use DB;
use Notification;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Token;

use Modules\Transaction\Entities\Allocation;
use Modules\Transaction\Entities\TransactionReservation;
use Modules\Transaction\Entities\ReservationRequest;
use Modules\Transaction\Entities\SalesAdviceStatus;
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['except' => ['signUpOnboarding','updateSignUpOnboarding','signUpUsers','externalSignUpEmail']]);
    }
    use ValidatesRequests;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try{
        $data = User::with('userType','clientType','UserAddresses')->withTrashed()->get();
        $user_types = UserType::all();
        $token = encrypt(env('APP_SECRET'));

        $auth_user = Auth::user();

        $shareable_link = URL::temporarySignedRoute(
            'users.sign_up_users',
            now()->addHours(24),
            ['token' => $token]
        );
            return view('user::users.index')->with('users', $data)->with('user_types', $user_types)->with('shareable_link', $shareable_link)->with('auth_user', $auth_user);
        }
        catch(\Exception $e) {
            $message = 'Error : ' . $e->getMessage();
            return response()->json(["status" => "error","message" => $message,"data" =>null,
            "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()]);
        }
    }

    /**
     * @param Request $request, $token
     * API is used to show view pafe for users to sign up using dynamic link.
    */
    public function signUpUsers(Request $request, $token)
    {
        try {
            if (decrypt($token) == env('APP_SECRET')) {
                return view('user::users.signup');
            } else {
                $message = "The link has expired";
                return response()->json(["status" => "error","message" => $message,"data" =>null]);
                // abort(422, "The link has expired");
            }
        } catch(\Exception $e) {
            $message = 'Error : ' . $e->getMessage();
            return response()->json(["status" => "error","message" => $message,"data" =>null,
            "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()]);
            // $message = "The link has expired";
            // return response()->json(["status" => "error","message" => $message,"data" =>null]);
        }
        
    }

    /**
     * @param Request $request
     * API is used to send email to users to register using a dynamic link.
    */
    public function signUpEmail(Request $request)
    {
        $this->validate($request, [
            'emails' => 'required',
            'type' => 'required'
        ]);
        try {
            DB::beginTransaction();
            foreach (json_decode($request->emails) as $email) {

                $user = User::where('email', '=', $email)->first();
                if ($user && $user->password != '') {
                    $token = encrypt(env('APP_SECRET'));

                $onboarding_link = URL::temporarySignedRoute(
                    'users.sign_up_onboarding',
                    now()->addHours(24),
                    ['token' => $token, 'email' => $user->email]
                );

                    
                }
                else if($user && $user->password){
                    $message = "A User account with $email already exists.";
                    return response()->json(["status" => "error","message" => $message,"data" =>null]);
                } else {
                    $user = new User;
                }
                
                $user->email = $email;
                $user_type = UserType::where('name', '=', $request->type)->first();
                if($request->type == 'Agent'){
                    $user->user_type_id = 2;
                    $user->is_readonly = 1;
                    $user->user_type_label = 'Agent';
                }
                else if($request->type == 'Stakeholder'){
                    $user->user_type_id = 1;
                    $user->is_readonly = 1;
                    $user->user_type_label = 'Stakeholder';
                }
                else if($request->type == 'Master Agent'){
                    $user->user_type_id = 2;
                    $user->user_type_label = 'MasterAgent';
                }
                else{
                    $user->user_type_id  = $user_type->id;
                } 

                $user->save();
                DB::commit();

                $token = encrypt(env('APP_SECRET'));

                $onboarding_link = URL::temporarySignedRoute(
                    'users.sign_up_onboarding',
                    now()->addHours(24),
                    ['token' => $token, 'email' => $user->email]
                );

                $newUserNotiifcationData = [
                    'url' => $onboarding_link,
                    'user' => $user
                ];
                $user->notify(new NewUserRegistration($newUserNotiifcationData));
                
            }
            $message = 'sign-up emails sent to users';
            return response()->json(["status" => "success","message" => $message,"data" =>null]);
            // return response()->json(['status' => 'sign-up emails sent to users'], 200);
        }
        catch(\Exception $e) {
            DB::rollBack();
            $message = 'Error : ' . $e->getMessage();
            return response()->json(["status" => "error","message" => $message,"data" =>null,
            "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()]);
            
        }

        
    }

    /**
     * @param Request $request
     * API is used to send email to users to register using a sahared link in the application.
    */
    public function externalSignUpEmail(Request $request)
    {

        $this->validate($request, [
            'emails' => 'required',
            'type' => 'required'
        ]);

        try 
        {
            DB::beginTransaction();
            foreach ($request->emails as $email) {
                $user = User::where('email', '=', $email)->first();
                if ($user && !$user->password) {
                    $token = encrypt(env('APP_SECRET'));

                $onboarding_link = URL::temporarySignedRoute(
                    'users.sign_up_onboarding',
                    now()->addHours(24),
                    ['token' => $token, 'email' => $user->email]
                );
                    
                }
                else if($user && $user->password){
                    $message = "A User account with $email already exists.";
                    return response()->json(["status" => "error","message" => $message,"data" =>null]);
                } else {
                    $user = new User;
                }
                
                $user->email = $email;
                $user_type = UserType::where('name', '=', $request->type)->first();
                if($request->type == 'Agent'){
                    $user->user_type_id = 2;
                    $user->is_readonly = 1;
                    $user->user_type_label = 'Agent';
                }
                else if($request->type == 'Stakeholder'){
                    $user->user_type_id = 1;
                    $user->is_readonly = 1;
                    $user->user_type_label = 'Stakeholder';
                }
                else if($request->type == 'Master Agent'){
                    $user->user_type_id = 2;
                    $user->user_type_label = 'MasterAgent';
                }
                else{
                    $user->user_type_id  = $user_type->id;
                } 

                $user->save();
                DB::commit();

                $token = encrypt(env('APP_SECRET'));

                $onboarding_link = URL::temporarySignedRoute(
                    'users.sign_up_onboarding',
                    now()->addHours(24),
                    ['token' => $token, 'email' => $user->email]
                );;

                $newUserNotiifcationData = [
                    'url' => $onboarding_link,
                    'user' => $user
                ];
                $user->notify(new NewUserRegistration($newUserNotiifcationData));
            }
            $message = 'sign-up emails sent to users';
            return response()->json(["status" => "success","message" => $message,"data" =>null]);
            // return response()->json(['status' => 'sign-up emails sent to users'], 200);
        }
        catch(\Exception $e) {
            DB::rollBack();
            $message = 'Error : ' . $e->getMessage();
            return response()->json(["status" => "error","message" => $message,"data" =>null,
            "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()]);
            
        }
    }

    /**
     * @param Request $request
     * API is used to show view page for a new user to register.
    */
    public function signUpOnboarding(Request $request, $token, $email)
    {

        try {
            if (decrypt($token) == env('APP_SECRET')) {
                $user = User::where('email','=',$email)->first();
                return view('user::users.onboard')->with(['email'=> $email, 'type' => $user->user_type_id]);
            } else {
                $message = 'The link has expired';
                return response()->json(["status" => "success","message" => $message,"data" =>null]);
                // abort(422, "The link has expired");
            }
        } catch (Exception $e) {
            $message = 'Error : ' . $e->getMessage();
            return response()->json(["status" => "error","message" => $message,"data" =>null,
            "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()]);
            // abort(422, "The link has expired");
        }
    }

    /**
     * @param Request $request
     * API is used to register new user.
    */
    public function updateSignUpOnboarding(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $user  = User::where('email', '=', $request->email)->firstOrFail();
            // $stakeholder;
            // if($request->checked == "true"){
            //     $stakeholder = 1;
            // }
            // else{
            //     $stakeholder = 0;
            // }
            User::where('uuid', '=', $user->uuid)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
                'company_name' => $request->company_name,
                'abn_or_can' => $request->abn_or_can,
                'licence_number' => $request->licence_number,
                'status'  => 'active'
            ]);

            if ($request->has('address_line_1')) {
                $address = new UserAddresses;
                $address->line_one = $request->address_line_1;
                $address->line_two = $request->address_line_2;
                $address->city = $request->city;
                $address->state = $request->state;
                $address->zipcode = $request->postcode;
                $address->user_id = $user->id;
                $address->save();
                // $user->address_id = $address->id;
                // $user->save();
            }

            if ($request->hasfile('profile_image')) {
                try {
                    $media = Media::where('model_id', '=', $user->id)->first();
                    if ($media !== null) {
                        $media->delete();
                        $user->addMedia($request->profile_image)->toMediaCollection('profile_image');
                    } else {
                        $user->addMedia($request->profile_image)->toMediaCollection('profile_image');
                    }
                } catch (Exception $e) {
                    $message = 'Error : ' . $e->getMessage();
                        return response()->json(["status" => "error","message" => $message,"data" =>null,
                        "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()]);
                }
            }
            DB::commit();
            $message = 'user account creation complete';
            return response()->json(["status" => "success","message" => $message,"data" =>null]);
            // return response()->json(['status' => 'user account creation complete'], 201);
        }
        catch (Exception $e) {
            DB::rollBack();
            $message = 'Error : ' . $e->getMessage();
            return response()->json(["status" => "error","message" => $message,"data" =>null,
            "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()]);
        }

    }

    /**
     * @param Request $request
     * API is used to check whether the email already exists in the application or not.
    */
    public function userEmailExists(Request $request, $email)
    {
        try
        {
            $user = User::where('email', '=', $email)->first();
            if ($user) {
                // abort(403, "A User account with $email already exists.");
                $message = "A User account with $email already exists.";
                return response()->json(["status" => "error","message" => $message,"data" =>null]);
            }
            
            return response()->json(["status" => "success","message" => null,"data" =>null]);
            // return response(200);
        }
        catch (Exception $e) {
            $message = 'Error : ' . $e->getMessage();
            return response()->json(["status" => "error","message" => $message,"data" =>null,
            "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('user::users.show',compact('id'));
    }

    

    
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

   
    /**
     * @param Request $request,$uuid
     * API is used to delete the user.
     */

    public function deleteUser(Request $request,$uuid){
        try{
            DB::beginTransaction();
            $current_user = Auth::user();
            $salesAdvicecount = 0;
            $count = 0;
            $user = User::where('uuid', '=', $uuid)->firstOrFail();
            $type = UserType::where('id','=',$user->user_type_id)->firstOrFail();
            if($type->name == 'Admin'){
                $count = User::where('user_type_id', $type->id)->count();
                if ($count == 1 || ($uuid == Auth::user()->uuid)) {
                    $message  = "You cannot delete this user";
                    return response()->json(["status" => "error","message" => $message,"data" =>null]);
                }
            }

            //Checking any salesadvice in approved state.
            if($user->user_type_id == 2){
                $salesAdvicecount = DB::table('ecp_tran_sales_advice')->where('ecp_tran_sales_advice.user_id',$user->id)
                ->join('ecp_tran_sale_advice_sts','ecp_tran_sales_advice.id','=','ecp_tran_sale_advice_sts.sales_advice_id')
                ->whereIn('ecp_tran_sale_advice_sts.status',['pending','approved'])
                ->count();

                if($salesAdvicecount){
                   User::where('id',$user->id)->update(['status' => 'suspended']);  
                }else{
                    $count += Allocation::where('user_id',$user->id)->where('user_type','2')->count();
                    $count += ReservationRequest::where('creator_id',$user->id)->count();
                    $count += TransactionReservation::where('agent_id',$user->id)->count();
                    $count += DB::table('ecp_tran_sales_advice')->where('ecp_tran_sales_advice.user_id',$user->id)
                    ->join('ecp_tran_sale_advice_sts','ecp_tran_sales_advice.id','=','ecp_tran_sale_advice_sts.sales_advice_id')
                    ->whereIn('ecp_tran_sale_advice_sts.status',['draft','declined'])
                    ->count();

                    if($count > 0){
                        Allocation::where('user_id',$user->id)->where('user_type','2')->update(['deleted_id' => $current_user->id]);
                        Allocation::where('user_id',$user->id)->where('user_type','2')->delete();
                        //transaction status check and update code
                       $reservedProjects = TransactionReservation::where('agent_id',$user->id)->get();
                       foreach($reservedProjects as $project){
                            $countAgent = TransactionReservation::where('property_id',$project->property_id)->where('property_type',$project->property_type)->where('agent_id','!=',$user->id)->count();
                            if($countAgent == 0){
                                $statusChange = Transaction::where('property_id',$project->property_id)->where('property_type',$project->property_type)->where('transaction_status','=','2')->first();
                                $statusChange->transaction_status = 1;
                                if($statusChange->save()){
                                    $transactionhistory = new TransactionHistory();
                                    $transactionhistory->transaction_id = $statusChange->id;
                                    $transactionhistory->project_id = $statusChange->project_id;
                                    $transactionhistory->property_id = $statusChange->property_id;
                                    $transactionhistory->property_type = $statusChange->property_type;
                                    $transactionhistory->transaction_status = 1;
                                    $transactionhistory->creator_id = $current_user->id;                    
                                    $transactionhistory->save();
                                }
                            }
                        }
                       //end
                        ReservationRequest::where('creator_id',$user->id)->delete();
                        TransactionReservation::where('agent_id',$user->id)->update(['deleted_id' => $current_user->id]);
                        TransactionReservation::where('agent_id',$user->id)->delete();
                    }
                    $user->delete();
                }                
            }
            
            if($user->user_type_id == 4){
                
                $salesAdvicecount = DB::table('ecp_tran_sales_advice')->where('ecp_tran_sales_advice.solicitor_id',$user->id)
                ->join('ecp_tran_sale_advice_sts','ecp_tran_sales_advice.id','=','ecp_tran_sale_advice_sts.sales_advice_id')
                ->count();
                
                if($salesAdvicecount){
                    User::where('id',$user->id)->update(['status' => 'suspended']);
                }else{
                    $count += Allocation::where('user_id',$user->id)->where('user_type','4')->count();
                    if($count > 0){
                        Allocation::where('user_id',$user->id)->where('user_type','4')->update(['deleted_id' => $current_user->id]);
                        Allocation::where('user_id',$user->id)->where('user_type','4')->delete();
                    }
                    $user->delete();
                }      
            }
            
            if($user->user_type_id == 1){
                $address = UserAddresses::where('user_id',$user->id)->first();
                if($address){
                    $address->delete();
                }
                $user->delete();
            }
            
            DB::commit();
            $message = 'User deleted successfully';
            return response()->json(["status" => "success","message" => $message,"data" =>null]);
        }
        catch(\Exception $e) {
            DB::rollBack();
            $message = 'Error : ' . $e->getMessage();
            return response()->json(["status" => 500,"message" => $message,"data" =>null,
            "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()]);
        }
    }

    public function checkDeleteUser(Request $request,$uuid){
        try{

            $current_user = Auth::user();
            $message = 'Are you sure you want to delete this User?';
            $deleteUser = true;
            $salesAdvicecount = 0;
            $count = 0;

            $user = User::where('uuid', '=', $uuid)->firstOrFail();
            $type = UserType::where('id','=',$user->user_type_id)->firstOrFail();
            
            //Checking any salesadvice in approved state.
            if($user->user_type_id == 2){
                $salesAdvicecount = DB::table('ecp_tran_sales_advice')->where('ecp_tran_sales_advice.user_id',$user->id)
                ->join('ecp_tran_sale_advice_sts','ecp_tran_sales_advice.id','=','ecp_tran_sale_advice_sts.sales_advice_id')
                ->whereIn('ecp_tran_sale_advice_sts.status',['pending','approved'])
                ->count();

                if($salesAdvicecount){
                    // $message = 'Deletion is not possible for this user since he has active sales advice; But you can suspend which will restrict him from login and further activities, Click confirm to continue';
                    $message = 'Cannot delete this user as he has an active Sales Advice';
                    $deleteUser = false;
                }else{
                    $count += Allocation::where('user_id',$user->id)->where('user_type','2')->count();
                    $count += ReservationRequest::where('creator_id',$user->id)->count();
                    $count += TransactionReservation::where('agent_id',$user->id)->count();
                    $count += DB::table('ecp_tran_sales_advice')->where('ecp_tran_sales_advice.user_id',$user->id)
                    ->join('ecp_tran_sale_advice_sts','ecp_tran_sales_advice.id','=','ecp_tran_sale_advice_sts.sales_advice_id')
                    ->whereIn('ecp_tran_sale_advice_sts.status',['draft','declined'])
                    ->count();

                    if($count > 0){
                        // $message = "Deletion is possible for this user; But this agent will losts his  allocation, reservation requests, reservations, Unapproved sales advice and also he/she will be restricted from login and further activities. Click confirm to continue";
                        $message = 'Are you sure you want to delete this User?';
                        $deleteUser = true;
                    }
                }                
            }
            
            if($user->user_type_id == 4){
                $salesAdvicecount = DB::table('ecp_tran_sales_advice')->where('ecp_tran_sales_advice.solicitor_id',$user->id)
                ->join('ecp_tran_sale_advice_sts','ecp_tran_sales_advice.id','=','ecp_tran_sale_advice_sts.sales_advice_id')
                ->where('ecp_tran_sale_advice_sts.status','=','approved')
                ->count();
                
                if($salesAdvicecount){
                    // $message = 'Deletion is not possible for this user since he has active sales advice; But you can suspend which will restrict him from login and further activities, Click confirm to continue';
                    $message = 'Cannot delete this user as he has an active Sales Advice.';
                    $deleteUser = false;
                }else{
                    
                    $count += Allocation::where('user_id',$user->id)->where('user_type','4')->count();
                    if($count > 0){
                        // $message = "Deletion is possible for this user; But this solicitor will losts his allocation and also he/she will be restricted from login and further activities. Click confirm to continue";
                        $message = 'Are you sure you want to delete this User?';
                        $deleteUser = true;
                    }
                }      
            }
            return response()->json(["status" => "success","message" => $message,"deleteUser" => $deleteUser]);
        }
        catch(\Exception $e){
            DB::rollBack();
            $message = 'Error : ' . $e->getMessage();
            return response()->json(["status" => 500,"message" => $message,"data" =>null,
            "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()]);
        }
    }
    
    /**
     * @param Request $request,$uuid
     * API is used to undo the deleted the user.
     */

    public function undoDeleteUser(Request $request,$uuid) {
        try{
           
            $user = User::withTrashed()->where('uuid', '=', $uuid)->firstOrFail();
            if($user) {
                DB::beginTransaction();
                $address = UserAddresses::withTrashed()->where('user_id',$user->id)->first();
                if($address){
                    $address->restore();
                }
                $user->restore();
                DB::commit();
                $message = 'User deleted successfully';
            } else {

                $message = 'User not found';

            }
            
            
            return response()->json(["status" => "success","message" => $message,"data" =>null]);
        }
        catch(\Exception $e) {
            DB::rollBack();
            $message = 'Error : ' . $e->getMessage();
            return response()->json(["status" => 500,"message" => $message,"data" =>null,
            "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()]);
        }
    }
    public function create()
    {
        return view('user::create');
    }


     /**
     * @param Request $request
     * API is used to save and update the user.
     */

    public function save(Request $request){
        if($request->id){
            try{
                $user_data = User::where('id','=',$request->id)->first();
                if($user_data->email != $request->email){
                    $user = User::where('email', '=', $request->email)->first();
                    if ($user) {
                        $message = "A User account with this email already exists";
                        return response()->json(["status" => 500,"message" => $message,"data" =>null]);
                    }
                }
                if(empty($request->first_name) && empty($request->last_name) ){
                    $fname= explode("@",$request->email) ;
                    $request->first_name=$fname[0];
                    $request->last_name=" ";
             }else if(empty($request->first_name) && !empty($request->last_name) ){
                 $request->first_name=" ";
             }else if(!empty($request->first_name) && empty($request->last_name) ){
                 $request->last_name=" ";
             }
                DB::beginTransaction();
                $updateRecord                      =   User::findOrFail($request->id);
                $updateRecord->first_name          =   $request->first_name;
                $updateRecord->last_name           =   $request->last_name;
                $updateRecord->email               =   $request->email;
                $updateRecord->phone               =   $request->phone;
                $updateRecord->company_name        =   $request->company_name;
                $updateRecord->abn_or_can          =   $request->abn_or_can;
                $updateRecord->licence_number      =   isset($request->licence_number) ? $request->licence_number : null;

                if($request->password){
                    $updateRecord->password        =   bcrypt($request->password);
                }
                if($request->has('user_type')){
                    $usertype = UserType::where('name','=',$request->user_type)->first();
                    if($request->user_type == 'Agent'){
                        $updateRecord->user_type_id = 2;
                        $updateRecord->is_readonly = 1;
                        $updateRecord->user_type_label = 'Agent';
                    }
                    else if($request->user_type == 'Stakeholder'){
                        $updateRecord->user_type_id = 1;
                        $updateRecord->is_readonly = 1;
                        $updateRecord->user_type_label = 'Stakeholder';
                    }
                    else if($request->user_type == 'MasterAgent' || $request->user_type == 'Master Agent'){
                        $updateRecord->user_type_id = 2;
                        $updateRecord->is_readonly = 0;
                        $updateRecord->user_type_label = 'MasterAgent';
                    }
                    else{
                        $updateRecord->user_type_id  = $usertype->id;
                        $updateRecord->user_type_label = 'Other';
                        $updateRecord->is_readonly = 0;
                    }                
                }
                if($request->has('client_type')){
                    $clienttype = ClientType::where('name','=',$request->client_type)->first();
                    $updateRecord->client_type_id = $clienttype->id; 
                }
                if ($request->has('status')) {
                    $updateRecord->status = $request->status;
                }
                if ($request->hasfile('profile_image')) {
                    try {
                        $media = Media::where('model_id', '=', $updateRecord->id)->first();
                        if ($media !== null) {
                            $media->delete();
                            $updateRecord->addMedia($request->profile_image)->toMediaCollection('profile_image');
                        } else {
                            $updateRecord->addMedia($request->profile_image)->toMediaCollection('profile_image');
                        }
                    } catch (\Exception $e) {
                        $message = 'Error : ' . $e->getMessage();
                        return response()->json(["status" => "error","message" => $message,"data" =>null,
                        "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()]);
                    }
                }
                // if($request->checked == "true"){
                //     $updateRecord->is_readonly = 1;
                // }
                // else{
                //     $updateRecord->is_readonly = 0;
                // }
                $updateRecord->save();

                if ($request->has('address_line_1')) {
                    $address                       = UserAddresses::where('user_id',$updateRecord->id)->first();
                    if($address == null){
                        $address = new UserAddresses;
                        $address->user_id = $updateRecord->id;
                    };
                    $address->line_one             = $request->address_line_1;
                    $address->line_two             = isset($request->address_line_2) ? $request->address_line_2 : null;
                    $address->city                 = $request->city;
                    $address->state                = $request->state;
                    $address->zipcode              = $request->postcode;
                    $address->save();
                }
                
                DB::commit();
                $message = 'user ' . $updateRecord->name . ' was successfully updated!';
                return response()->json(["status" => "success","message" => $message,"data" =>$updateRecord],200);
            }catch(\Exception $e) {
                DB::rollback();
                $message = 'Error : ' . $e->getMessage();
                return response()->json(["status" => "error","message" => $message,"data" => null,
                "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()], 500);
            }
        }
        else{
            try{
                DB::beginTransaction();
                $user = User::where('email', '=', $request->email)->first();
                $deleteduser = User::onlyTrashed()->where('email', '=', $request->email)->first();
                if ($user) {
                    $message = "A User account with this email already exists";
                    return response()->json(["status" => "error","message" => $message,"data" =>null]);
                } else if ($deleteduser) {
                    $message = "A user with this email id is deleted from the system already. You can reactivate from the deleted users list.";
                    return response()->json(["status" => "error","message" => $message,"data" =>null]);
                }
                if(empty($request->first_name) && empty($request->last_name) ){
                       $fname= explode("@",$request->email) ;
                       $request->first_name=$fname[0];
                       $request->last_name=" ";
                }else if(empty($request->first_name) && !empty($request->last_name) ){
                    $request->first_name=" ";
                }else if(!empty($request->first_name) && empty($request->last_name) ){
                    $request->last_name=" ";
                }
                $user = new User;
                $user->first_name  = $request->first_name;
                $user->last_name  = $request->last_name;
                $user->email  = $request->email;
                $user->password  = isset($request->password) ? bcrypt($request->password) : null;
        
                if ($request->has('status')) {
                    $user->status = $request->status;
                }
        
                $user->phone  = $request->phone;
                $user->company_name  = $request->company_name;
                $user->abn_or_can  = $request->abn_or_can;
                $user->licence_number  = isset($request->licence_number) ? $request->licence_number : null;
                if ($request->has('user_type')) {
                    $usertype = UserType::where('name','=',$request->user_type)->first();
                    if($request->user_type == 'Agent'){
                        $user->user_type_id = 2;
                        $user->is_readonly = 1;
                        $user->user_type_label = 'Agent';
                    }
                    else if($request->user_type == 'Stakeholder'){
                        $user->user_type_id = 1;
                        $user->is_readonly = 1;
                        $user->user_type_label = 'Stakeholder';
                    }
                    else if($request->user_type == 'Master Agent'){
                        $user->user_type_id = 2;
                        $user->user_type_label = 'MasterAgent';
                    }
                    else{
                        $user->user_type_id  = $usertype->id;
                    }
                }
                if ($request->has('client_type')) {
                    $clienttype = ClientType::where('name','=',$request->client_type)->first();
                    $user->client_type_id = $clienttype->id;
                }
                // if($request->checked == "true"){
                //     $user->is_readonly = 1;
                // }
        
                if ($request->hasfile('profile_image')) {
                    $user->addMedia($request->profile_image)->toMediaCollection('profile_image');
                }
        
                if ($request->has('address_line_1')) {
                    $address = new UserAddresses;
                    $address->line_one = $request->address_line_1;
                    $address->line_two = isset($request->address_line_2) ? $request->address_line_2 : null;
                    $address->city = $request->city;
                    $address->state = $request->state;
                    $address->zipcode = $request->postcode;
                    $user->save();
                    $address->user_id = $user->id;
                    $address->save();
                }
                else{
                    $user->save();
                }

                $token = Str::random(60);

                $encrypted_token = bcrypt($token);

                DB::table('password_resets')->insert([
                    'email' => $user->email,
                    'token' => $encrypted_token,
                    'created_at' => Carbon::now()
                ]);

                $reset_url = route('password.reset', $token) . "?email=" . $user->email . "&type=create";

                $name = $user->first_name . ' ' . $user->last_name;
                $data = [
                    'user' => $user,
                    'url' => $reset_url
                ];
        
                $user->notify(new NewUserRegistration($data));
                DB::commit();
                $message = 'user '. $user['first_name']. ' successfully added!';
                return response()->json(["status" => "success","message" => $message,"data" => $user,"address" =>$address],200);
               
            }catch(\Exception $e) {
                DB::rollBack();
                $message = 'Error : ' . $e->getMessage();
                return response()->json(["status" => 500,"message" => $message,"data" =>null,
                "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()], 500);
            }
        }
    }

    /**
     * @param $uuid
     * API is used to fetch the data of a user for edit purpose.
     */
    public function edit($uuid)
    {
        try{
            $current_user = Auth::user();
            $user = User::where('uuid', '=', $uuid)->first();
            $usertype = UserType::where('id','=',$user->user_type_id)->first();
            $responseData = ['registered' => false];
            if ($user->password || $usertype->name == 'Client' || $current_user->user_type_id == 1) {
                $address = UserAddresses::where('user_id', '=', $user->id)->first();
                $client  = ClientType::where('id','=',$user->client_type_id)->first();
                $responseData = [
                    'user_type' => isset($usertype->name) ? $usertype->name : null,
                    'client_type' => isset($client->name) ? $client->name : null,
                    'status' => $user->status,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'user_type_id' => $user->user_type_id,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'company_name' => $user->company_name,
                    'abn_or_can' => $user->abn_or_can,
                    'licence_number' => $user->licence_number,
                    'media_url' => $user->media_url,
                    'address_line_1' => isset($address->line_one) ? $address->line_one : null,
                    'address_line_2' => isset($address->line_two) ? $address->line_two : null,
                    'city' =>  isset($address->city) ? $address->city : null,
                    'state' =>  isset($address->state) ? $address->state : null,
                    'postcode' => isset($address->zipcode) ? $address->zipcode : null,
                    'uuid' => $user->uuid,
                    'registered' => true,
                    'id'=>$user->id,
                    'user_type_label'=> $user->user_type_label
                ];
                $salesAdvicesArray = SalesAdvice::where('user_id','=', $user->id)
                                                ->with('salesadvicestatus','transaction.transactionstatus','transactioncommission')
                                                ->orderBy('ecp_tran_sales_advice.updated_at', 'desc')
                                                ->get();
                $salesAdvices = array();
                for($i=0;$i<count($salesAdvicesArray);$i++){
                    if($salesAdvicesArray[$i]->salesadvicestatus->status != 'declined'){
                        $project = DB::table('projects')->select('name','uuid')->whereNull('deleted_at')->find($salesAdvicesArray[$i]->transaction->project_id);
                        if($project) {
                            $salesAdvicesArray[$i]->project = $project;
                            if($salesAdvicesArray[$i]->project_type == '1'){
                                $unit = DB::table('units')->select('lot_no','uuid')->whereNull('deleted_at')->find($salesAdvicesArray[$i]->property_id);
                                $salesAdvicesArray[$i]->unit_property = $unit;
                            }
                            if($salesAdvicesArray[$i]->project_type != '1'){
                                $salesAdvices[] = $salesAdvicesArray[$i];
                            }
                            else if($salesAdvicesArray[$i]->project_type == '1' && $salesAdvicesArray[$i]->unit_property != null){
                                $salesAdvices[] = $salesAdvicesArray[$i];
                            }
                        }
                    }
                }

                usort($salesAdvices, function($a, $b) {
                    $start_date = $a->transactioncommission[1]->commission_date;
                    $end_date = $b->transactioncommission[1]->commission_date;
                    return strtotime($end_date) - strtotime($start_date);
                });
                $salesAdvices=json_encode($salesAdvices);
                $responseData['commissions_plan'] = $salesAdvices;
                // $responseData['commissions_plan'] = TransactionCommission::where('agent_id', '=', $user->id)
                // //->with('salesadvice','transaction')
                //    ->with(['salesadvice','transaction.unit.buildings.project', 'transaction.land', 'transaction.house', 'transaction.commercials', 'transaction.other'])
                //     ->get();
            }
            $user_types = UserType::all();
            $client_types = ClientType::all();
            return view('user::users.edit')->with("user", json_encode($responseData))->with("user_types",$user_types)->with("client_types",$client_types)->with("current_user",$current_user);
        }
        catch(\Exception $e) {
            $message = 'Error : ' . $e->getMessage();
            return response()->json(["status" => "error","message" => $message,"data" =>null,
            "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()], 500);
        }
            
    }

    /**
     * @param 
     * API is used to fetch the user types and client types for adding a user.
     */

    public function new()
    {
        try{
            $user_types   = UserType::whereIn('id', array(1, 2,4,5,6))->get();
            $client_types = ClientType::all();
            return view('user::users.create',compact('user_types','client_types'));
        }
        catch(\Exception $e) {
            $message = 'Error : ' . $e->getMessage();
            return response()->json(["status" => "error","message" => $message,"data" =>null,
            "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()]);
        }
        
    }

    /**
     * Method to update password
     */
    public function updatePassword(Request $request)
    {
        try{
            DB::beginTransaction();
            $updatePassword = User::where('uuid', '=', $request->user_uuid)->update([
                'password' => bcrypt($request->password)
            ]);
            DB::commit();
            $message = 'Password Updated Successfully';
            return response()->json(["status" => "success","message" => $message,"data" =>null]);
        }
        catch(\Exception $e) {
            DB::rollBack();
            $message = 'Error : ' . $e->getMessage();
            return response()->json(["status" => "error","message" => $message,"data" =>null,
            "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()]);
        }
        
    }
    
    /**
     * Method to send mail
     */
    public function resend_mail(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
        ]);

        $user = User::findOrFail($request->user_id);

        if (!$user->password && $user->status == 'active' && $user->status == 'inactive') {
            $token = encrypt(env('APP_SECRET'));

            $onboarding_link = URL::temporarySignedRoute(
                'users.sign_up_onboarding',
                now()->addHours(24),
                ['token' => $token, 'email' => $user->email]
            );

            $newUserNotiifcationData = [
                'url' => $onboarding_link,
                'user' => $user
            ];
            $user->notify(new NewUserRegistration($newUserNotiifcationData));
            return 'success - onboarding link sent';
        } else {

            //cleaning up old password reset tokens
            DB::table('password_resets')->where('email', '=', $user->email)->delete();

            // $token = Str::random(60);
            $token = encrypt(env('APP_SECRET'));

            $encrypted_token = bcrypt($token);

            DB::table('password_resets')->insert([
                'email' => $user->email,
                'token' => $encrypted_token,
                'created_at' => Carbon::now()
            ]);

            // $reset_url = route('password.reset', $token) . "?email=" . $user->email . "&type=create";

            $name = $user->first_name . ' ' . $user->last_name;
           

            $reset_url = URL::temporarySignedRoute(
                'password.reset',
                now()->addHours(1),
                ['token' => $token, 'email' => $user->email, 'type' =>'create']
            );

            $data = [
                'user' => $user,
                'url' => $reset_url
            ];

            $user->notify(new NewUserRegistration($data));
            
            return "success";
        }
    }

    /**
     * Method to update data
     */
    public function updateAll(Request $request){
        try{
            $user_id=$request->user_id;
            $user = User::where('id','=',$user_id)->firstOrFail();
            $date = Carbon::now();
            $all_notification = DB::table('notifications')->where('notifiable_id','=',$user->id)->update(array('read_at' => $date));
            return response()->json(['status' => 'All Notifications Marked as Read'], 200);
        }
        catch(\Exception $e) {
            $message = 'Error : ' . $e->getMessage();
            return response()->json(["status" => 500,"message" => $message,"data" =>null,
            "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()], 500);
        }
       

    }

    /**
     * Method to send notification for delete data
     */
    public function deleteNotification(Request $request){
        try{
            $date = Carbon::now();
            $notification = DB::table('notifications')->where('id',$request->id)->update(array('deleted_at' => $date));

            return response()->json(['status' => 'Notification deleted Successfully'], 200);
        }
        catch(\Exception $e) {
            $message = 'Error : ' . $e->getMessage();
            return response()->json(["status" => 500,"message" => $message,"data" =>null,
            "error_details" => 'on line : ' . $e->getLine() . 'on file ' . $e->getFile()], 500);
        }
    }

    /**
     * Method to fetch data
     */
    public function fetch(Request $request , $uuid)
    {
        $user = User::where('uuid','=',$uuid)->firstOrFail();
        $notification = $user->notifications->where('deleted_at','')->all();
        return response()->json(['notifications' => $notification], 200);
    }
    

}
