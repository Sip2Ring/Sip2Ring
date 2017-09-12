<?php
namespace App\Http\Controllers;
use App\ConfigAuth;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;

class ConfigAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /*
     * function to add config credentials
     * created by BK
     */
    public function addConfig()
    {
        if(!empty($_POST['key']) && !empty($_POST['secret'])){
            $awsServerName = Input::get('aws_server_name');
            $key = $_POST['key'];
            $secret = $_POST['secret'];
            $aws_counter = (!empty($_POST['aws_counter'])) ? $_POST['aws_counter'] : "";
            //check config exist
            $checkConfigExist = ConfigAuth::where('key', "=", $key)->where('secret', "=", $secret)->first();
    		try {
                if(empty($checkConfigExist)){
                    //add entry into table
                    $configAuth = new ConfigAuth();
                    $configAuth->key  = $key;
                    $configAuth->aws_name 		  = $awsServerName;
                    $configAuth->secret  		  = $secret;
                    $configAuth->aws_counter      = $aws_counter;
                    $configAuth->save();
                    flash('Auth Configuration added successfully!');
                    return Redirect::to('list-config');
                }else{
                    $message = "Config auth with '$key' already exist in system, please create with another name!";
                    flash($message);
                    return Redirect::to('add-auth');
                }
    		}
			catch (\Aws\S3\Exception\S3Exception $e) {
				$message = $e->getMessage();
				$errorMessage = 'There is some error while adding new AWS server!';
				flash($errorMessage, "danger");
				return Redirect::to('list-config');
			}
        }
        $configAuth = ConfigAuth::all();
        return view('adminsOnly.configAuth.add', compact('configAuth'));
    }

    /*
    * function to edit config credentials
    * created by BK
    */
    public function editConfig($id)
    {
        if(!empty($_POST['key']) && !empty($_POST['secret'])){
			$awsServerName = Input::get('aws_server_name');
            $key = $_POST['key'];
            $secret = $_POST['secret'];
            $aws_counter = (!empty($_POST['aws_counter'])) ? $_POST['aws_counter'] : "";
            //update config auth
            $configAuth = ConfigAuth::find($id);
			$configAuth->aws_name 		  = $awsServerName;
            $configAuth->key  = $key;			
            $configAuth->secret  = $secret;
            $configAuth->aws_counter = $aws_counter;
            $configAuth->save();
            flash('Auth Configuration updated successfully!');
            return redirect('/list-config');
        }
        $config = ConfigAuth::findOrFail($id);
        $activeCheck = $config->status=='active' ? 'checked' : '';
        $inActiveCheck = $config->status=='inactive' ? 'checked' : '';
        return view('adminsOnly.configAuth.edit', compact('config','activeCheck','inActiveCheck'));
    }

   /*
   * function to list config credentials
   * created by BK
   */
    public function listConfig()
    {
        $configAuth = ConfigAuth::all();
        return view('adminsOnly.configAuth.list', compact('configAuth'));
    }

    /*
    * function to delete config credentials
    * created by BK
    */
    public function deleteConfig($id)
    {
        ConfigAuth::findOrFail($id)->delete();
        flash('Auth Configuration deleted successfully!');
        return redirect('/list-config');
    }

    /*
     * function to active config credentials
     * created by BK
     */
    public function updateStatus($recordID, $status)
    {
        if(!empty($recordID) && !empty($status)){
            //if delete case found
            if($status=='delete'){
                ConfigAuth::findOrFail($recordID)->delete();
                flash('Auth Configuration deleted successfully!');
                return redirect('/list-config');
            }
            //update config auth
            $configAuth = ConfigAuth::find($recordID);
            $bucketKey = $configAuth->key;
            $bucketSecret = $configAuth->secret;
            $awsName = $configAuth->aws_name;
            //create object of selected AWS network
            $s3client = new S3Client([
                'version'     => 'latest',
                'region'      => 'eu-central-1',
                'credentials' => [
                    'key'    => $bucketKey,
                    'secret' => $bucketSecret
                ]
            ]);
            try{
                //get list of all buckets and check if bucket name already exist
                $s3client->listBuckets();
                //if status is active or inactive
                if($status=='active'){ ConfigAuth::query()->update(['status' => 'inactive']); }
                $configAuth->status  = $status;
                $configAuth->save();
                flash("Auth Configuration $status successfully!");
                return redirect('/list-config');
            }
            catch (\Aws\S3\Exception\S3Exception $e) {
                flash("There is some error while processing with '$awsName' server, please try again later!", "danger");
                return redirect('/list-config');
            }
        }
    }

    /*
    * function to active config credentials selected by combo box
    * created by BK
    */
    public function activateConfig($recordID)
    {
        if(!empty($recordID)){
            //update config auth
            $configAuth = ConfigAuth::find($recordID);
            $bucketKey = $configAuth->key;
            $bucketSecret = $configAuth->secret;
            $awsName = $configAuth->aws_name;
            $awsId = $configAuth->id;
			
            //create object of selected AWS network
            $s3client = new S3Client([
                'version'     => 'latest',
                'region'      => 'eu-central-1',
                'credentials' => [
                    'key'    => $bucketKey,
                    'secret' => $bucketSecret
                ]
            ]);
            try{
                //get list of all buckets and check if bucket name already exist
                $contents = $s3client->listBuckets();
				session()->put('awsId', $awsId);
                flash('AWS server switch successfully!');
                //return response
                $return = array(
                    'type' => 'success',
                );
                return json_encode($return);
            }
            catch (\Aws\S3\Exception\S3Exception $e) {
                $message = $e->getMessage();
                //return response
                $return = array(
                    'type' => 'error',
                    'message' => "There is some error while processing with '$awsName' server, please try again later!"
                );
                return json_encode($return);
            }
        }else{
            $return = array(
                'type' => 'error',
                'message' => 'There is some error in params, please try again later!',
            );
            return json_encode($return);
        }
    }
    /*
    * function to manage Primary Network
    * created by BK
    */
    public function updatePrimaryNetwork($recordID, $status)
    {
        if(!empty($recordID) && !empty($status)){
            //if status is active or inactive
            if($status=='active'){ ConfigAuth::query()->update(['primary_network' => 'no']); }
            //update config auth
            $configAuth = ConfigAuth::find($recordID);
            $configAuth->primary_network  = ($status=='active') ? 'yes' : 'no';
            $configAuth->primary_network;
            $configAuth->save();
            flash("Auth Configuration updated successfully!");
            return redirect('/list-config');
        }
    }
}
