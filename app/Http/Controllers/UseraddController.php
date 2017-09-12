<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Plivo;
use DB;
use Form;
use App\Models\UserAddress;
use App\Models\UserAdd;
use App\Models\User;
class UseraddController extends Controller
{
	/*
		Route : /view-call-page
	*/

	public function aduserTemplate(){

        $getCountrylist = UserAddress::get();
        return view('user.addUser',compact('getCountrylist'));


    }

    public function aduserInsert(){

           if(isset($_POST) && is_array($_POST) && count($_POST) > 0){

            try{$getCountrylist = UserAddress::get();

                    $addUSer = new UserAdd();
                    $addUSer->name = $_POST['name'];
                    $addUSer->country = $_POST['country'];
                    $addUSer->city = $_POST['city'];

                    $addUSer->business_name = $_POST['buisename'];
                    $addUSer->street_address = $_POST['street_address'];
                    $addUSer->state_region = $_POST['state'];
                    $addUSer->zip_code = $_POST['postalcode'];

                    $addUSer->save();


            }
            catch (\Aws\S3\Exception\S3Exception $e)  {
                $message = $e->getMessage();
                $errorMessage = 'There is some error while adding Users. Please try again later!';

            }
        }else{
            $getCountrylist = UserAddress::get();
            $getDetails= DB::table('user_address_add')->orderBy('sl_no', 'desc')->first();

            //print_r($getDetails);exit;


            return view('user.addUser', compact('getDetails','getCountrylist'));


        }
        //return view('Users.addUser', compact('roles'));



    }

     public function userprofileUpdate(){


         $getuserList= DB::table('users')->orderBy('id', 'desc')->first();




         return view('user.userProfile', compact('getuserList'));


     }

}
