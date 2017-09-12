<?php

namespace App\Http\Controllers;

use App\ConfigAuth;
use App\Models\BucketParams;
use App\Models\User;
use App\DuplicateBuckets;
use App\Models\BucketRegions;
use App\Models\BucketTemplates;
use App\Models\MasterBuckets;
use App\Models\MasterBucketsCounter;
use App\Models\UserActionlog;
use Aws\DirectoryService\DirectoryServiceClient;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Aws\S3\S3Client;
use App\Classes\S3;
use Mockery\CountValidator\Exception;
use Storage;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Session;

class BucketsController extends Controller {

    public function __construct() {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //obj for AWSID and S3Client
        $awsId = $this->getAwsID();
        $s3client = $this->s3clientObject($awsId);

        //initialize counter
        $totalCount = 0;
        $bucketPidArr = array();
        $bucketParamArr = array();
        $contents = array();
        $contents['Buckets'] = array();
        try {
            $activeConfigId = $this->getAwsID();
            // Using operation methods creates command implicitly.
            $contents = $s3client->listBuckets();
            foreach ($contents['Buckets'] as $key => $bucketDetails) {
                $bucketName = $bucketDetails['Name'];
                if (preg_match('/www/', $bucketName)) {
                    //get bucket first string
                    try {
//                        $existStatus = $s3client->doesBucketExist($bucketName);
//                        if(!empty($existStatus)){
                        //get location and add it on Bucket array
                        $location = $s3client->getBucketLocation(array('Bucket' => $bucketName));
                        $locationConstraint = (!empty($location['LocationConstraint'])) ? $location['LocationConstraint'] : 'eu-central-1';
                        $contents['Buckets'][$key]['LocationConstraint'] = $locationConstraint;

                        //create bucket URL and pass it on
                        $urlConcatOperator = ($locationConstraint=='ap-northeast-1') ? '-' : '.';
                        $bucketLinkUrl = "http://".$bucketName.".s3-website".$urlConcatOperator.$locationConstraint.".amazonaws.com";
                        $contents['Buckets'][$key]['bucketLinkUrl'] = $bucketLinkUrl;

//                        //get phone number from XML file
//                        $xmlPhoneUrl = "http://".$bucketName.".s3-website".$urlConcatOperator.$locationConstraint.".amazonaws.com/assests/phonenumber.xml";
//                        $bucketXmlPhone = @simplexml_load_file($xmlPhoneUrl);
//                        $contents['Buckets'][$key]['xmlPhoneNumber'] = $bucketXmlPhone;

                        //get Network name
                        $firstString = substr($bucketName, 0, strcspn($bucketName, '1234567890'));
                        $replaceCommonString = str_replace(array($firstString, '.com'), '', $bucketName);

                        //replace first find string from string
                        $getUniqueNumber = $this->getNumericVal($replaceCommonString);
                        if (!empty($getUniqueNumber)) {
                            $finalString = preg_replace("/$getUniqueNumber/", '', $replaceCommonString, 1);
                            //check if duplicate bucket record exist or not
                            $checkBucketExist = DuplicateBuckets::where('bucket_code', "=", $finalString)->where('aws_server_id', "=", $activeConfigId)->first();
                            if (empty($checkBucketExist)) {
                                //add entry in Duplicate bucket
                                $addDuplicateBucket = new DuplicateBuckets();
                                $addDuplicateBucket->bucket_name = $bucketName;
                                $addDuplicateBucket->bucket_code = $finalString;
                                $addDuplicateBucket->duplicate_bucket_counter = $getUniqueNumber;
                                $addDuplicateBucket->aws_server_id = $activeConfigId;
                                $addDuplicateBucket->save();
                            } else {
                                DuplicateBuckets::where('bucket_code', "=", $finalString)->where('aws_server_id', "=", $activeConfigId)->update(['duplicate_bucket_counter' => $getUniqueNumber]);
                            }
                        }
                        $totalCount++;
//                        }
                    } catch (\Exception $exception) {
                        
                    }
                }
            }
            // bucket PARAMS and create master bucket PID data
            $getBucketParams = BucketParams::get();
            foreach ($getBucketParams as $key => $paramData) {
                $key = $paramData['bucket_short_code'][0] . $paramData['bucket_region'] . $paramData['bucket_short_code'][1];
                $bucketParamArr[$key]['bucket_region'] = $paramData['bucket_region'];
                $bucketParamArr[$key]['bucket_short_code'] = $paramData['bucket_short_code'];
                $bucketParamArr[$key]['startString'] = $paramData['bucket_short_code'][0] . $paramData['bucket_region'];
                $bucketParamArr[$key]['endString'] = $paramData['bucket_short_code'][1];
                $bucketParamArr[$key]['bucket_parameters'] = $paramData['bucket_parameters'];
            }
        } catch (\Exception $exception) {
            flash('There is some error while processing, please try again later! ', 'danger');
        }
        //create master bucket PID data
        $masterBuckets = MasterBuckets::all();
        foreach ($masterBuckets as $key => $masterData) {
            $bucketPidArr[$masterData['bucket_name']]['id'] = $masterData['id'];
            $bucketPidArr[$masterData['bucket_name']]['bucket_name'] = $masterData['bucket_name'];
            $bucketPidArr[$masterData['bucket_name']]['bucket_pid'] = $masterData['bucket_pid'];
            $bucketPidArr[$masterData['bucket_name']]['bucket_phone_number'] = str_replace(' ', '', $masterData['bucket_phone_number']);
            $bucketPidArr[$masterData['bucket_name']]['ringba_code'] = str_replace(' ', '', $masterData['ringba_code']);
            $bucketPidArr[$masterData['bucket_name']]['bucket_region'] = $masterData['bucket_region'];
            $bucketPidArr[$masterData['bucket_name']]['bucket_short_code'] = $masterData['bucket_short_code'];
            //get bucket params
            $getBucketParams = BucketParams::where('bucket_region', "=", $masterData['bucket_region'])->where('bucket_short_code', '=', $masterData['bucket_short_code'])->first();
            $bucketPidArr[$masterData['bucket_name']]['bucket_parameters'] = (!empty($getBucketParams)) ? $getBucketParams['bucket_parameters'] : '';
        }

        //get templates from DB that not to be shown in Buckets
        $templates = DB::table('bucket_templates')->select(DB::raw('group_concat(aws_name) AS template_names'))->first();
        $templateArr = array_filter(explode(',', $templates->template_names));
        //code for het session//$awsId = 3;
        $allAwsServer = ConfigAuth::where('id', "!=", '')->orderBy('id', 'desc')->get();
        //return view session
        return view('adminsOnly.buckets.index', compact('contents', 'masterBuckets', 'bucketPidArr', 'templateArr', 'bucketParamArr', 'allAwsServer', 'totalCount', 'awsId'));
    }

    /*
     * function to make bucket duplicate
     * created by BK
     * created on 2nd June'17
     */
    public function duplicator() {
        if(!empty($_POST['awsId'])){
            //set params
            $awsId = $_POST['awsId'];
            $dateTime = date_create('now')->format('Y-m-d H:i:s');

            //update Auto after 5mints
            $this->updateFlag();
            $flagValue = $this->getFlagVal($awsId, $dateTime);

            if ($flagValue == 0) {
                DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 1, 'created_at' => $dateTime));
                if (!empty($_POST['duplicate_for']) && !empty($_POST['duplicate_counter'])) {
                    //get bucket details
                    $bucket = $_POST['duplicate_for'];
                    $bucketCounter = $_POST['duplicate_counter'];
                    $bucketRegion = $_POST['duplicate_for_region'];

                    //get bucket first string
                    $firstString = substr($bucket, 0, strcspn($bucket, '1234567890'));
                    $replaceCommonString = str_replace(array($firstString, '.com'), '', $bucket);

                    //replace first find string from string
                    $getUniqueNumber = $this->getNumericVal($replaceCommonString);
                    $finalString = preg_replace("/$getUniqueNumber/", '', $replaceCommonString, 1);

                    //get region code
                    $regionCode = (!empty($bucketRegion)) ? $bucketRegion : "eu-central-1";
                    if (empty($regionCode)) {
                        //return response
                        $return = array(
                            'type' => 'error',
                            'message' => "Region code cannot be empty for $bucketRegion.",
                        );
                        return json_encode($return);
                    }

                    //get S3 object on the basis of current AWS ID
                    $s3client = $this->s3clientObject($awsId, $regionCode);

                    // Using operation methods creates command implicitly.
                    $bucketArray = $s3client->listBuckets();
                    $bucketSuccessResponse = array();
                    for ($counter = 1; $counter <= $bucketCounter; $counter++) {
                        $activeConfigId   = $awsId;
                        $checkBucketExist = DuplicateBuckets::where('bucket_code', "=", $finalString)->where('aws_server_id', "=", $activeConfigId)->first();

                        $duplicateExist = false;
                        if (!empty($checkBucketExist)) {
                            $checkBucketExist['duplicate_bucket_counter'] = $checkBucketExist['duplicate_bucket_counter'] + 1;
                            $newCounter = ($checkBucketExist['duplicate_bucket_counter'] < 10) ? '0' . $checkBucketExist['duplicate_bucket_counter'] : $checkBucketExist['duplicate_bucket_counter'];
                            $duplicateExist = true;
                        } else {
                            //get array of matches string in Bucket name
                            $matchCases = array();
                            foreach ($bucketArray['Buckets'] as $bucketDetail) {
                                if (strpos($bucketDetail['Name'], $finalString) !== false) {
                                    $matchCases[] = $bucketDetail;
                                }
                            }
                            $getLastRecord = end($matchCases);

                            $getLastEntry = str_replace(array($firstString, '.com', $finalString), '', $getLastRecord['Name']);
                            $incrementRecord = $getLastEntry + 1;
                            $newCounter = ($incrementRecord < 10) ? '0' . $incrementRecord : $incrementRecord;
                        }
                        //create next new bucket name
                        $newBucketName = $firstString . $newCounter . $finalString . '.com';
                        //create string policy for Bucket
                        $stringPolicy = '{
                    "Version": "2012-10-17",
                    "Statement": [
                        {
                            "Sid": "Allow Public Access to All Objects",
                            "Effect": "Allow",
                            "Principal": "*",
                            "Action": "s3:GetObject",
                            "Resource": "arn:aws:s3:::' . $newBucketName . '/*"
                        }
                    ]
                }';
                        //get list of all buckets and check if bucket name already exist
                        $existName = false;
                        $contents = $s3client->listBuckets();
                        foreach ($contents['Buckets'] as $bucketDetails) {
                            if ($newBucketName == $bucketDetails['Name']) {
                                $existName = true;
                            }
                        }
                        //if name already exist, then return error message
                        if ($existName) {
                            DB::table('buckets_flag_aws')
                                ->where('aws_id', '=', $awsId)
                                ->update(array('aws_status' => 0, 'created_at' => $dateTime));
                            $message = "'$newBucketName' bucket already exist, please try with some other name!";
                            //return response
                            $return = array(
                                'value' => '100',
                                'type' => 'error',
                                'message' => $message,
                            );
                            return json_encode($return);
                        } else {
                            //check index.html file for existing bucket
                            $existIndex = false;
                            $existingBucket = $s3client->listObjects(array('Bucket' => $bucket));
                            foreach ($existingBucket['Contents'] as $existFiles) {
                                if ($existFiles['Key'] == 'index.html') {
                                    $existIndex = true;
                                } else {
                                    $existIndex = false;
                                }
                            }
                            //if index file exist, then create bucket
                            if ($existIndex) {
                                try {
                                    //trigger exception in a "try" block
                                    $s3client->createBucket([
                                        'Bucket' => $newBucketName,
                                    ]);
                                    $stp = $s3client->listObjects(array('Bucket' => $bucket));
                                    foreach ($stp['Contents'] as $object) {
                                        $s3client->copyObject(array(
                                            'Bucket' => $newBucketName,
                                            'Key' => $object['Key'],
                                            'CopySource' => $bucket . '/' . $object['Key']
                                        ));
                                    }
                                    $arg = array(
                                        'Bucket' => $newBucketName,
                                        'WebsiteConfiguration' => array(
                                            'ErrorDocument' => array('Key' => 'error.html',),
                                            'IndexDocument' => array('Suffix' => 'index.html',),
                                        ),
                                    );
                                    $s3client->putBucketWebsite($arg);
                                    $s3client->putBucketPolicy([
                                        'Bucket' => $newBucketName,
                                        'Policy' => $stringPolicy,
                                    ]);

                                    //if already exist, update the counter, else add new entry
                                    if ($duplicateExist) {
                                        DuplicateBuckets::where('bucket_code', "=", $finalString)->where('aws_server_id', "=", $activeConfigId)->update(['duplicate_bucket_counter' => $newCounter]);
                                    } else {
                                        //add entry in Duplicate bucket
                                        $addDuplicateBucket = new DuplicateBuckets();
                                        $addDuplicateBucket->bucket_name = $newBucketName;
                                        $addDuplicateBucket->bucket_code = $finalString;
                                        $addDuplicateBucket->aws_server_id = $activeConfigId;
                                        $addDuplicateBucket->duplicate_bucket_counter = $newCounter;
                                        $addDuplicateBucket->save();
                                    }
                                    //get location for new bucket url
                                    $location = $s3client->getBucketLocation(array(
                                        'Bucket' => $newBucketName
                                    ));

                                    //For  User Action Log
                                    $actionData['user_id'] = Auth::user()->id;
                                    $actionData['aws_id'] = $awsId;
                                    $actionData['action_performed'] = 'Duplicatior';
                                    $actionData['bucket_name'] = $newBucketName;
                                    $this->user_action_log($actionData);

                                    $newBucketUrl = "http://" . $newBucketName . ".s3-website." . $location['LocationConstraint'] . ".amazonaws.com";
                                    $bucketSuccessResponse[] = "$newBucketUrl";
                                    //response in case of success if counter match!
                                    if ($counter == $bucketCounter) {
                                        $finalMessage = implode(' , ', $bucketSuccessResponse) . ' bucket successfully created!';
                                        flash($finalMessage);
                                        //return response
                                        $return = array(
                                            'type' => 'success',
                                            'message' => $bucketSuccessResponse,
                                        );
                                        DB::table('buckets_flag_aws')
                                            ->where('aws_id', '=', $awsId)
                                            ->update(array('aws_status' => 0, 'created_at' => $dateTime));

                                        return json_encode($return);
                                    }
                                } catch (\Exception $exception) {
                                    DB::table('buckets_flag_aws')
                                        ->where('aws_id', '=', $awsId)
                                        ->update(array('aws_status' => 0, 'created_at' => $dateTime));
                                    $xmlResponse = $exception->getAwsErrorCode();
                                    if ($xmlResponse == "BucketAlreadyExists") {
                                        $message = "Bucket already exists. Please change the URL.";
                                    } else {
                                        $message = $xmlResponse;
                                    }
                                    $return = array(
                                        'value' => '2',
                                        'type' => 'error',
                                        'message' => $message,
                                    );
                                    return json_encode($return);
                                }
                            } else {
                                $message = "Index.html file must be in your existing bucket, please add and try again later!";
                                //return response
                                $return = array(
                                    'value' => '100',
                                    'type' => 'error',
                                    'message' => $message,
                                );
                                DB::table('buckets_flag_aws')
                                    ->where('aws_id', '=', $awsId)
                                    ->update(array('aws_status' => 0, 'created_at' => $dateTime));
                                return json_encode($return);
                            }
                        }
                    }
                } else {
                    DB::table('buckets_flag_aws')
                        ->where('aws_id', '=', $awsId)
                        ->update(array('aws_status' => 0, 'created_at' => $dateTime));
                    //return response
                    $message = "There is some error in the params posted by you, please check!";
                    $return = array(
                        'value' => '100',
                        'type' => 'error',
                        'message' => $message,
                    );
                    return json_encode($return);
                }
            } else {
                $message = "Another process is running. Please wait! Don't refresh the browser";
                //return response
                $return = array(
                    'value' => '100',
                    'type' => 'error',
                    'message' => $message,
                );
                return json_encode($return);
            }
        }else{
            $message = "AWS not found, please check and try again later!";
            //return response
            $return = array(
                'value' => '100',
                'type' => 'error',
                'message' => $message,
            );
            return json_encode($return);
        }

    }

    /*
     * function to delete bucket
     * created by BK
     * created on 2nd June'17
     */

    public function deleteBucket() {
        if(!empty($_POST['awsId'])){
            //set params
            $awsId = $_POST['awsId'];
            $dateTime = date_create('now')->format('Y-m-d H:i:s');

            //update Auto after 5mints
            $this->updateFlag();
            $flagValue = $this->getFlagVal($awsId, $dateTime);

            if ($flagValue == 0) {
                DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 1, 'created_at' => $dateTime));
                if (!empty($_POST)) {
                    $bucketName = $_POST['bucket_name'];
                    $bucketRegion = $_POST['duplicate_for_region'];
                    if (!empty($bucketName) && !empty($bucketRegion)) {
                        try {
                            //get region code
                            $regionCode = (!empty($bucketRegion)) ? $bucketRegion : "eu-central-1";
                            if (empty($regionCode)) {
                                DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));
                                //DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0,'created_at' => date('Y-m-d h:i:s')]
                                //return response
                                $return = array(
                                    'type' => 'error',
                                    'message' => "Region code cannot be empty for $bucketRegion.",
                                );
                                return json_encode($return);
                            }
                            //get S3 object on the basis of current AWS ID
                            $s3client = $this->s3clientObject($awsId, $regionCode);
                            $cont = $s3client->getIterator('ListObjects', array('Bucket' => $bucketName));
                            foreach ($cont as $fileDetails) {
                                $fileName = $fileDetails['Key'];
                                $result = $s3client->deleteObject(array(
                                    'Bucket' => $bucketName,
                                    'Key' => $fileName
                                ));
                            }
                            $s3client->deleteBucket(array(
                                'Bucket' => $bucketName
                            ));
                            //For  User Action Log
                            $actionData['user_id'] = Auth::user()->id;
                            $actionData['aws_id'] = $awsId;
                            $actionData['action_performed'] = 'Delete Bucket';
                            $actionData['bucket_name'] = $bucketName;
                            $this->user_action_log($actionData);

                            $message = "Success ";
                            DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));

                            //return response
                            $return = array(
                                'type' => 'success',
                                'message' => $message,
                            );
                            flash("$bucketName deleted successfully!");
                            return json_encode($return);
                        } catch (Exception $e) {
                            //return response
                            $return = array(
                                'value' => '100',
                                'type' => 'error',
                                'message' => $e->getMessage(),
                            );
                            return json_encode($return);
                        }
                    } else {
                        DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));
                        $message = "Bucket name cannot be empty, please check!";
                        //return response
                        $return = array(
                            'value' => '100',
                            'type' => 'error',
                            'message' => $message,
                        );
                        return json_encode($return);
                    }
                } else {
                    DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));
                    $message = "There is some error in the params posted by you, please check!";
                    //return response
                    $return = array(
                        'value' => '100',
                        'type' => 'error',
                        'message' => $message,
                    );
                    return json_encode($return);
                }
            } else {
                $message = "Another process is running. Please wait! Don't refresh the browser";
                //return response
                $return = array(
                    'value' => '100',
                    'type' => 'error',
                    'message' => $message,
                );
                return json_encode($return);
            }
        }else{
            $message = "AWS not found, please check and try again later!";
            //return response
            $return = array(
                'value' => '100',
                'type' => 'error',
                'message' => $message,
            );
            return json_encode($return);
        }

    }

    /*
     * function to delete bucket in BULK
     * created by BK
     * created on 2nd June'17
     */

    public function deleteMultipleBuckets() {
        if(!empty($_POST['awsId'])){
            //set params
            $awsId = $_POST['awsId'];
            $dateTime = date_create('now')->format('Y-m-d H:i:s');

            //update Auto after 5mints
            $this->updateFlag();
            $flagValue = $this->getFlagVal($awsId, $dateTime);

            if ($flagValue == 0) {
                DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 1, 'created_at' => $dateTime));
                if (!empty($_POST['awsId']) && !empty($_POST['bucket_name'])) {
                    //get buckets
                    $awsId = $_POST['awsId'];
                    $buckets = $_POST['bucket_name'];
                    $postBucketNames = array();

                    if (!empty($buckets)) {
                        foreach ($buckets as $key => $bucketName) {
                            $bucketRegion = '';
                            if (strpos($bucketName, '___') !== false) {
                                $explodeBucketNameRegion = explode('___', $bucketName);
                                $bucketName = $explodeBucketNameRegion[0];
                                $bucketRegion = $explodeBucketNameRegion[1];
                            }

                            //For Action Log
                            $actionData['user_id'] = Auth::user()->id;
                            $actionData['aws_id'] = $awsId;
                            $actionData['action_performed'] = 'Bulk Delete';
                            $actionData['bucket_name'] = $bucketName;
                            $this->user_action_log($actionData);
                            $postBucketNames[] = $bucketName;

                            //get region code
                            $regionCode = (!empty($bucketRegion)) ? $bucketRegion : "eu-central-1";
                            if (empty($regionCode)) {
                                DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));

                                //return response
                                $return = array(
                                    'type' => 'error',
                                    'message' => "Region code cannot be empty for $bucketRegion.",
                                );
                                return json_encode($return);
                            }
                            //get S3 object on the basis of current AWS ID
                            $s3client = $this->s3clientObject($awsId, $regionCode);
                            $cont = $s3client->getIterator('ListObjects', array('Bucket' => $bucketName));
                            foreach ($cont as $fileDetails) {
                                $fileName = $fileDetails['Key'];
                                $s3client->deleteObject(array(
                                    'Bucket' => $bucketName,
                                    'Key' => $fileName
                                ));
                            }
                            $s3client->deleteBucket(array(
                                'Bucket' => $bucketName
                            ));
                        }
                        DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));
                        $message = "Success ";
                        $return = array(
                            'type' => 'success',
                            'message' => $message,
                        );
                        $bucketNames = implode(' , ', $postBucketNames);
                        flash("$bucketNames bucket deleted successfully!");
                        return json_encode($return);
                    } else {
                        DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));
                        $message = "Bucket name cannot be empty, please check!";
                        //return response
                        $return = array(
                            'value' => '100',
                            'type' => 'error',
                            'message' => $message,
                        );
                        return json_encode($return);
                    }
                } else {
                    DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));
                    $message = "There is some error in the params posted by you, please check!";
                    //return response
                    $return = array(
                        'value' => '100',
                        'type' => 'error',
                        'message' => $message,
                    );
                    return json_encode($return);
                }
            } else {
                $message = "Another process is running. Please wait! Don't refresh the browser!";
                //return response
                $return = array(
                    'value' => '100',
                    'type' => 'error',
                    'message' => $message,
                );
                return json_encode($return);
            }
        }else{
            $message = "AWS not found, please check and try again later!";
            //return response
            $return = array(
                'value' => '100',
                'type' => 'error',
                'message' => $message,
            );
            return json_encode($return);
        }

    }

    public function getNumericVal($str) {
        preg_match_all('/\d+/', $str, $matches);
        return (!empty($matches[0][0])) ? $matches[0][0] : '';
    }

    public function duplicateListBuckets() {
        $buckets = DuplicateBuckets::all();
        return view('adminsOnly.buckets.view', compact('buckets'));
    }



    /********************************CREATE CHILD BUCKET FROM MASTER BUCKET********************* */
    /*
     * function to create bucket from master bucket
     * created by BK
     * created on 8th June'17
     */

    public function createChildBucket() {
        if (!empty($_POST['awsId'])) {
            //set params
            $awsId = $_POST['awsId'];
            $dateTime = date_create('now')->format('Y-m-d H:i:s');

            //update Auto after 5mints
            $this->updateFlag();
            $flagValue = $this->getFlagVal($awsId, $dateTime);

            if ($flagValue == 0) {
                DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 1, 'created_at' => $dateTime));
                $masterBucketID = $_POST['master_bucket'];
                $masterBucketDetails = MasterBuckets::find($masterBucketID);

                //master bucket var
                $masterBucketName = $masterBucketDetails['bucket_name'];
                $bucketRegion = $masterBucketDetails['bucket_region'];

                $bucketShortCode = $masterBucketDetails['bucket_short_code'];
                $bucketBrowser = $masterBucketDetails['bucket_browser'];
                $bucketPhoneNumber = $masterBucketDetails['bucket_phone_number'];
                $bucketPID = $masterBucketDetails['bucket_pid'];
                $bucketAnalyticsId = $masterBucketDetails['bucket_analytics_id'];

                //get region code from - required
                $regionCode = BucketRegions::where('region_value', "=", $bucketRegion)->first();
                $regionCode = (!empty($regionCode['region_code'])) ? $regionCode['region_code'] : "eu-central-1";

                if (empty($regionCode)) {
                    //return response
                    DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));
                    $return = array(
                        'type' => 'error',
                        'message' => "Region code cannot be empty for $bucketRegion.",
                    );
                    return json_encode($return);
                }

                //get bucket template details
                $bucketTemplate = $masterBucketDetails['bucket_template'];
                $templateDetails = BucketTemplates::find($bucketTemplate);
                $awsName = $templateDetails['aws_name'];

                //get counter and add on 1
                $childBucketCounter = $masterBucketDetails['child_bucket_counter'];
                $incrementCounter = $childBucketCounter + 1;
                $newCounter = ($incrementCounter < 10) ? '0' . $incrementCounter : $incrementCounter;

                //sete params tp create bucket
                $bucketParams = array();
                $bucketParams['duplicate_for'] = $awsName;
                $bucketParams['region_code'] = $regionCode;
                $bucketParams['bucket_counter'] = $newCounter;
                $bucketParams['bucket_basic_name'] = $masterBucketName;
                $bucketParams['bucket_phone_number'] = $bucketPhoneNumber;
                $bucketParams['bucket_analytics_id'] = $bucketAnalyticsId;
                $bucketParams['aws_id'] 			= $awsId;

                // $createBucketResponse = json_decode($this->duplicatorMaster($bucketParams));
                $createBucketResponse = json_decode($this->duplicateUsingMasterTemplate($bucketParams));

                if ($createBucketResponse->type == 'success') {
                    $updatedCounter = $createBucketResponse->bucket_updated_counter;
                    $childBucketName = $createBucketResponse->bucket_url;
                    $serverName = $createBucketResponse->bucket_created_server_name;
                    //update counter in master bucket table
                    MasterBuckets::where('id', $masterBucketID)->update(['child_bucket_counter' => $updatedCounter]);

                    $activeConfigId = $this->getActiveConfig();
                    DuplicateBuckets::where('bucket_code', $masterBucketName)->where('aws_server_id', "=", $activeConfigId)->update(['duplicate_bucket_counter' => $updatedCounter]);


                    //For Log Action
                    $actionData['user_id'] = Auth::user()->id;
                    $actionData['aws_id'] = $awsId;
                    $actionData['action_performed'] = 'Add Bucket';
                    $actionData['bucket_name'] = $masterBucketName;
                    $this->user_action_log($actionData);
                    DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));
                    $message = "$childBucketName bucket has been added successfully on Sever : $serverName !";
                    flash($message);
                    //return response
                    $return = array(
                        'type' => 'success',
                        'message' => $message,
                    );

                    return json_encode($return);
                } else {
                    DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));
                    return json_encode($createBucketResponse);
                }
            } else {
                $message = "Another process is running. Please wait! Don't refresh the browser";
                //return response
                $return = array(
                    'value' => '100',
                    'type' => 'error',
                    'message' => $message,
                );
                return json_encode($return);
            }
        }
    }

    /*
     * function to get the region array
     * created by BK
     * created on 27th June
     * return : array
     */

    public function getRegions() {
        $bucketRegions = BucketRegions::all();
        $regionArr = array();
        foreach ($bucketRegions as $regions) {
            $regionArr[$regions->region_value] = $regions->region_code;
        }
        return $regionArr;
    }

    /*
     * function to make bucket duplicate
     * created by NK
     * created on 30 June'17
     */

    public function duplicateToAws() {
        if(!empty($_REQUEST['awsId'])){
            //set params
            $awsId = $_REQUEST['awsId'];
            $dateTime = date_create('now')->format('Y-m-d H:i:s');

            //update Auto after 5mints
            $this->updateFlag();
            $flagValue = $this->getFlagVal($awsId, $dateTime);

            if ($flagValue == 0) {
                DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 1, 'created_at' => $dateTime));
                $duplicateFrom = Input::get('duplicate_for');
                $newBucketName = Input::get('new_bucket_name');
                $region = Input::get('duplicateToAwsRegion');

                //create S3 object from where to create
                $s3clientActive = $this->s3clientObject($awsId, $region);

                //create S3 object to where to create
                $copyToServerId = Input::get('aws_server_id');
                $s3clientToMove = $this->s3clientObject($copyToServerId, $region);

                //AWS server name to which we are going to create
                $toServerName = $this->getAwsName($copyToServerId);
                $bucket = $duplicateFrom;


                if ($newBucketName == "") {
                    $newBucketName = $duplicateFrom;
                }
                //create string policy for Bucket
                $stringPolicy = '{
					"Version": "2012-10-17",
					"Statement": [
						{
							"Sid": "Allow Public Access to All Objects",
							"Effect": "Allow",
							"Principal": "*",
							"Action": "s3:GetObject",
							"Resource": "arn:aws:s3:::' . $newBucketName . '/*"
						}
					]
				}';

                //get list of all buckets and check if bucket name already exist
                $existName = false;
                $contents = $s3clientToMove->listBuckets();
                foreach ($contents['Buckets'] as $bucketDetails) {
                    if ($newBucketName == $bucketDetails['Name']) {
                        $existName = true;
                    }
                }

                //if name already exist, then return error message
                if ($existName) {
                    DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));
                    $message = "'$newBucketName' bucket already exist, please try with some other name!";
                    //return response
                    $return = array(
                        'value' => '2',
                        'type' => 'error',
                        'message' => $message,
                    );
                    return json_encode($return);
                } else {
                    //check index.html file for existing bucket
                    $existIndex = false;
                    $existingBucket = $s3clientActive->listObjects(array('Bucket' => $bucket));
                    foreach ($existingBucket['Contents'] as $existFiles) {
                        if ($existFiles['Key'] == 'index.html') {
                            $existIndex = true;
                        } else {
                            $existIndex = false;
                        }
                    }
                    //if index file exist, then create bucket
                    if ($existIndex) {
                        try {
                            //create instance of NEW server, where we have to move/copy
                            $result3 = $s3clientToMove->createBucket([
                                'Bucket' => $newBucketName,
                            ]);
                            //list the current bucket from active AWS server, from where we have to move/copy
                            $stp = $s3clientActive->listObjects(array('Bucket' => $bucket)); // to

                            foreach ($stp['Contents'] as $object) {
                                //create instance of NEW server, where we have to move/copy
                                $s3clientToMove->copyObject(array(
                                    'Bucket' => $newBucketName,
                                    'Key' => $object['Key'],
                                    'CopySource' => $bucket . '/' . $object['Key']
                                ));
                            }
                            $arg = array(
                                'Bucket' => $newBucketName,
                                'WebsiteConfiguration' => array(
                                    'ErrorDocument' => array('Key' => 'error.html',),
                                    'IndexDocument' => array('Suffix' => 'index.html',),
                                ),
                            );

                            //create instance of NEW server, where we have to move/copy
                            $s3clientToMove->putBucketWebsite($arg);
                            $s3clientToMove->putBucketPolicy([
                                'Bucket' => $newBucketName,
                                'Policy' => $stringPolicy,
                            ]);

                            //get location for new bucket url
                            //create instance of NEW server, where we have to move/copy
                            $location = $s3clientToMove->getBucketLocation(array(
                                'Bucket' => $newBucketName
                            ));
                            $newBucketUrl = "http://" . $newBucketName . ".s3-website." . $location['LocationConstraint'] . ".amazonaws.com";


                            //For Log Action
                            $actionData['user_id'] = Auth::user()->id;
                            $actionData['aws_id'] = $awsId;
                            $actionData['action_performed'] = 'Copy To Aws';
                            $actionData['bucket_name'] = $newBucketName;
                            $this->user_action_log($actionData);

                            //response in case of success if counter match!
                            DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));
                            $finalMessage = $newBucketUrl . ' bucket successfully created on new server ' . $toServerName;
                            flash($finalMessage);
                            //return response
                            $return = array(
                                'value' => '1',
                                'type' => 'success',
                                'message' => $finalMessage,
                                'b_url' => $newBucketUrl,
                                'b_name' => $newBucketName,
                            );
                            return json_encode($return);
                        } catch (\Exception $exception) {

                            $xmlResposne = $exception->getAwsErrorCode();
                            if ($xmlResposne == "BucketAlreadyExists") {
                                $message = "Bucket already exists. Please change the URL.";
                            } else {
                                $message = $xmlResposne;
                            }
                            DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));
                            $return = array(
                                'value' => '2',
                                'type' => 'error',
                                'message' => $message,
                            );
                            return json_encode($return);
                        }
                    }
                }
            } else {
                $message = "Another process is running. Please wait! Don't refresh the browser";
                //return response
                $return = array(
                    'value' => '100',
                    'type' => 'error',
                    'message' => $message,
                );
                return json_encode($return);
            }
        }else{
            $message = "Some parameters are not defined, please check!";
            //return response
            $return = array(
                'value' => '100',
                'type' => 'error',
                'message' => $message,
            );
            return json_encode($return);
        }
    }

    /**
     * Display a listing of the resource.
     * TEST BUCKETS
     * @return \Illuminate\Http\Response
     */
    public function testBuckets() {
        $bucketAuthCredentials = $this->getAuthCredentials();
        $bucketKey = $bucketAuthCredentials['key'];
        $bucketSecret = $bucketAuthCredentials['secret'];
        $s3client = new S3Client([
            'version' => 'latest',
            'region' => 'eu-central-1',
            'credentials' => [
                'key' => $bucketKey,
                'secret' => $bucketSecret
            ]
        ]);
        $params = [
            'Bucket' => 'foo',
            'Key' => 'baz',
            'Body' => 'bar'
        ];
        $contents = $s3client->listBuckets();
        //create master bucket PID data
        $masterBuckets = MasterBuckets::all();
        $bucketPidArr = array();
        foreach ($masterBuckets as $key => $masterData) {
            $bucketPidArr[$masterData['bucket_name']]['id'] = $masterData['id'];
            $bucketPidArr[$masterData['bucket_name']]['bucket_name'] = $masterData['bucket_name'];
            $bucketPidArr[$masterData['bucket_name']]['bucket_pid'] = $masterData['bucket_pid'];
            $bucketPidArr[$masterData['bucket_name']]['bucket_region'] = $masterData['bucket_region'];
            $bucketPidArr[$masterData['bucket_name']]['bucket_short_code'] = $masterData['bucket_short_code'];
            //get bucket params
            $getBucketParams = BucketParams::where('bucket_region', "=", $masterData['bucket_region'])->where('bucket_short_code', '=', $masterData['bucket_short_code'])->first();
            $bucketPidArr[$masterData['bucket_name']]['bucket_parameters'] = (!empty($getBucketParams)) ? $getBucketParams['bucket_parameters'] : '';
        }
        // bucket PARAMS
        $getBucketParams = BucketParams::all();
        //create master bucket PID data
        $bucketParamArr = array();
        foreach ($getBucketParams as $key => $paramData) {
            $key = $paramData['bucket_short_code'][0] . $paramData['bucket_region'] . $paramData['bucket_short_code'][1];
            $bucketParamArr[$key]['bucket_region'] = $paramData['bucket_region'];
            $bucketParamArr[$key]['bucket_short_code'] = $paramData['bucket_short_code'];
            $bucketParamArr[$key]['startString'] = $paramData['bucket_short_code'][0] . $paramData['bucket_region'];
            $bucketParamArr[$key]['endString'] = $paramData['bucket_short_code'][1];
            $bucketParamArr[$key]['bucket_parameters'] = $paramData['bucket_parameters'];
        }
        //get templates from DB that not to be shown in Buckets
        $templates = DB::table('bucket_templates')->select(DB::raw('group_concat(aws_name) AS template_names'))->first();
        $templateArr = array_filter(explode(',', $templates->template_names));
        //list of all aws server
        $status = "Inactive";
        $allAwsServer = ConfigAuth::where('status', "=", $status)->get();
        return view('adminsOnly.buckets.testBuckets', compact('contents', 's3client', 'masterBuckets', 'bucketPidArr', 'templateArr', 'bucketParamArr', 'allAwsServer'));
    }

    public function googleBuckets() {

        /* $disk = Storage::disk('gcs');
          $url = $disk->url('assests/xe-ie.png'); */

        //putenv('GOOGLE_APPLICATION_CREDENTIALS=/crmstaging/my-service2.json');

        $storage = new StorageClient([
            'projectId' => 'original-folio-171317'
        ]);

        $buckets = $storage->buckets();


        foreach ($buckets as $bucket) {
            echo $bucket->name();
            echo '<br>';
        }

        die;
        echo '<pre>';
        print_r($storage);
        die;


        //$storage->setAuthConfig('/crmstaging/my-service2.json');

        $bucket = $storage->createBucket('aurobucket');

        $url = "Good";

        return view('adminsOnly.buckets.googleBuckets', compact('contents', 'url'));
    }

    public function recurse_copy($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    /*
     * function to make bucket duplicate
     * created by NK
     * created on 11 July'17
     * $s3clientActive means from where we copy the master template robert
     * $s3clientToMove is the object of active server
     */

    public function duplicateUsingMasterTemplate($bucketParams) {

        $duplciateFrom = $bucketParams['duplicate_for'];
        $region = $bucketParams['region_code'];
        $bucket_counter = $bucketParams['bucket_counter'];
        $bucketBasicName = $bucketParams['bucket_basic_name'];
        $bucketPhoneNumber = $bucketParams['bucket_phone_number'];
        $analyticsId = $bucketParams['bucket_analytics_id'];
        $aws_id = $bucketParams['aws_id'];
        $primary = "yes";
        $status = "active";
        //$awsServerActive       = ConfigAuth::where('status', "=", $status)->first();
        $awsServerActive = ConfigAuth::where('primary_network', "=", $primary)->first();
        $activeServerKey = $awsServerActive['key'];
        $actvieServerSecretKey = $awsServerActive['secret'];
        //create object for "S3Client"
        $s3clientActive = new S3Client([
            'version' => 'latest',
            'region' => $region,
            'credentials' => [
                'key' => $activeServerKey,
                'secret' => $actvieServerSecretKey
            ]
        ]);

        //AWS server - where we have to move/create the Bucket
        $allAwsServer = ConfigAuth::where('id', "=", $aws_id)->first();
        $toServerKey = $allAwsServer['key'];
        $toServerSecretKey = $allAwsServer['secret'];
        $toServerName = $allAwsServer['aws_name'];
        $bucket = $duplciateFrom;
        $s3clientToMove = new S3Client([
            'version' => 'latest',
            'region' => $region,
            'credentials' => [
                'key' => $toServerKey,
                'secret' => $toServerSecretKey
            ]
        ]);

        /* code to final the bucekt name */
        $contents = $s3clientToMove->listBuckets();
        //get array of matches string in Bucket name
        $matchCases = array();
        foreach ($contents['Buckets'] as $bucketDetail) {
            if (strpos($bucketDetail['Name'], $bucketBasicName) !== false) {
                $matchCases[] = $bucketDetail;
            }
        }
        //get last bucket counter
        if (!empty($matchCases)) {
            $getLastRecord = end($matchCases);
            $firstString = substr($getLastRecord['Name'], 0, strcspn($getLastRecord['Name'], '1234567890'));
            $getLastEntry = str_replace(array($firstString, '.com', $bucketBasicName), '', $getLastRecord['Name']);
            $incrementRecord = $getLastEntry + 1;
            $newCounter = ($incrementRecord < 10) ? '0' . $incrementRecord : $incrementRecord;
        } else {
            $firstString = 'www.support.microsoft';
            $bucketCounter = $this->getConfigCounterAwsID($aws_id);
            $newCounter = ($bucketCounter < 10) ? '0' . $bucketCounter : $bucketCounter;
        }
        //create final bucket name
        $childBucketName = $newCounter . $bucketBasicName;
        $newBucketName = "$firstString$childBucketName.com";
        /* code to final the bucket name */
        if ($newBucketName == "") {
            $newBucketName = $duplciateFrom;
        }
        //create string policy for Bucket
        $stringPolicy = '{
					"Version": "2012-10-17",
					"Statement": [
						{
							"Sid": "Allow Public Access to All Objects",
							"Effect": "Allow",
							"Principal": "*",
							"Action": "s3:GetObject",
							"Resource": "arn:aws:s3:::' . $newBucketName . '/*"
						}
					]
				}';

        //get list of all buckets and check if bucket name already exist
        $existName = false;
        $contents = $s3clientToMove->listBuckets();
        foreach ($contents['Buckets'] as $bucketDetails) {
            if ($newBucketName == $bucketDetails['Name']) {
                $existName = true;
            }
        }

        //if name already exist, then return error message
        if ($existName) {
            $message = "'$newBucketName' bucket already exist, please try with some other name!";
            //return response
            $return = array(
                'value' => '2',
                'type' => 'error',
                'message' => $message,
            );
            return json_encode($return);
        } else {
            //check index.html file for existing bucket
            $existIndex = false;
            $existingBucket = $s3clientActive->listObjects(array('Bucket' => $bucket));
            foreach ($existingBucket['Contents'] as $existFiles) {
                if ($existFiles['Key'] == 'index.html') {
                    $existIndex = true;
                } else {
                    $existIndex = false;
                }
            }
            //if index file exist, then create bucket
            if ($existIndex) {
                try {
                    //create instance of NEW server, where we have to move/copy
                    $result3 = $s3clientToMove->createBucket([
                        'Bucket' => $newBucketName,
                    ]);
                    //list the current bucket from active AWS server, from where we have to move/copy
                    $stp = $s3clientActive->listObjects(array('Bucket' => $bucket)); // to

                    foreach ($stp['Contents'] as $object) {
                        //create instance of NEW server, where we have to move/copy
                        $s3clientToMove->copyObject(array(
                            'Bucket' => $newBucketName,
                            'Key' => $object['Key'],
                            'CopySource' => $bucket . '/' . $object['Key']
                        ));
                    }
                    $arg = array(
                        'Bucket' => $newBucketName,
                        'WebsiteConfiguration' => array(
                            'ErrorDocument' => array('Key' => 'error.html',),
                            'IndexDocument' => array('Suffix' => 'index.html',),
                        ),
                    );

                    //create instance of NEW server, where we have to move/copy
                    $s3clientToMove->putBucketWebsite($arg);
                    $s3clientToMove->putBucketPolicy([
                        'Bucket' => $newBucketName,
                        'Policy' => $stringPolicy,
                    ]);

                    //get location for new bucket url
                    //create instance of NEW server, where we have to move/copy
                    $location = $s3clientToMove->getBucketLocation(array(
                        'Bucket' => $newBucketName
                    ));
                    $newBucketUrl = "http://" . $newBucketName . ".s3-website." . $location['LocationConstraint'] . ".amazonaws.com";
                    $this->create_save_xml_fie($bucketPhoneNumber);

                    $awsFolderPath = "assests/phonenumber.xml";
                    $tmp_name = public_path('template_data') . DIRECTORY_SEPARATOR . "phonenumber.xml";
                    $result = $s3clientToMove->putObject(array(
                        'Bucket' => $newBucketName,
                        'Key' => $awsFolderPath,
                        'SourceFile' => $tmp_name,
                        'ContentType' => 'application/xml',
                        'ACL' => 'public-read',
                        'StorageClass' => 'REDUCED_REDUNDANCY',));

                    $this->create_analytics_fie($analyticsId);
                    $awsFolderPath = "assests/analytics.js";
                    $tmp_name = public_path('template_data') . DIRECTORY_SEPARATOR . "analytics.js";
                    $result = $s3clientToMove->putObject(array(
                        'Bucket' => $newBucketName,
                        'Key' => $awsFolderPath,
                        'SourceFile' => $tmp_name,
                        'ContentType' => 'application/javascript',
                        'ACL' => 'public-read',
                        'StorageClass' => 'REDUCED_REDUNDANCY',));
                    //return response
                    $return = array(
                        'value' => '1',
                        'type' => 'success',
                        'bucket_url' => $newBucketUrl,
                        'bucket_updated_counter' => $newCounter,
                        'bucket_created_server_name' => $toServerName,
                    );
                    return json_encode($return);
                } catch (\Exception $exception) {

                    $xmlResposne = $exception->getAwsErrorCode();
                    $message = $xmlResposne;
                    $return = array(
                        'value' => '2',
                        'type' => 'error',
                        'message' => $message,
                    );
                    return json_encode($return);
                }
            }
        }
    }

    /*
     * function to create xml file
     * created by NK
     * created on 19 July'17
     * $phoneNumber : From the master bucket table
     */

    public function create_save_xml_fie($phoneNumber = '9780058718') {
        $xml = "<?xml version='1.0' encoding='UTF-8'?><phone>$phoneNumber</phone>";
        $xmlFilePath = public_path('template_data') . DIRECTORY_SEPARATOR;
        $file = fopen($xmlFilePath . "phonenumber.xml", "w");
        fwrite($file, $xml);
        fclose($file);
    }

    /*
     * function to create xml file
     * created by NK
     * created on 19 July'17
     * $phoneNumber : From the master bucket table
     */

    public function update_phone_xml_fie() {
        $phoneNumber = input::get('phone_number');
        $bucketName  = input::get('bucket_name');
        $region 	 = input::get('region');
		$aws_id 	 = input::get('aws_id');
        $this->create_save_xml_fie($phoneNumber);
        $awsFolderPath = "assests/phonenumber.xml";
        $tmp_name = public_path('template_data') . DIRECTORY_SEPARATOR . "phonenumber.xml";
        $status = "Active";
        $allAwsServer = ConfigAuth::where('id', "=", $aws_id)->first();
        $toServerKey = $allAwsServer['key'];
        $toServerSecretKey = $allAwsServer['secret'];
        $s3clientToMove = new S3Client([
            'version' => 'latest',
            'region' => $region,
            'credentials' => [
                'key' => $toServerKey,
                'secret' => $toServerSecretKey
            ]
        ]);
        $result = $s3clientToMove->putObject(array(
            'Bucket' => $bucketName,
            'Key' => $awsFolderPath,
            'SourceFile' => $tmp_name,
            'ContentType' => 'application/xml',
            'ACL' => 'public-read',
            'StorageClass' => 'REDUCED_REDUNDANCY',
        ));
        if ($result['ObjectURL'] != "") {
            $message = "Phone Number has been updated successfully";
            flash($message);
            //return response
            $return = array(
                'type' => 'success',
                'message' => $message,
            );
            return json_encode($return);
        } else {
            $message = "Error in the system. Please wait.";
            flash($message);
            $return = array(
                'type' => 'success',
                'message' => $message,
            );
            return json_encode($return);
        }
    }

    /*
    * function to make bucket duplicateToAWsAuto
    * created on 31 June'17
    */
    public function duplicateToAwsAuto(){
        if(!empty($_REQUEST)){
            //bucket name
            $bucket = $_REQUEST['duplicate_for'];
            //bucket region
            $region = $_REQUEST['duplicateToAwsRegion'];

            //from AWS
            $fromAWS = $_REQUEST['awsId'];
            $fromS3client = $this->s3clientObject($fromAWS, $region);

            //to AWS
            $toAWS = $_REQUEST['aws_server_id'];
            $toS3client = $this->s3clientObject($toAWS, $region);
            $toServerName = $this->getAwsName($toAWS);

            //network name
            $finalString = $_REQUEST['new_bucket_name'];
            //get bucket first string
            $firstString = substr($bucket, 0, strcspn($bucket, '1234567890'));
            //check network name
            $checkBucketExist = DuplicateBuckets::where('bucket_code', "=", $finalString)->where('aws_server_id', "=", $toAWS)->first();
            if (!empty($checkBucketExist)) {
                $checkBucketExist['duplicate_bucket_counter'] = $checkBucketExist['duplicate_bucket_counter'] + 1;
                $newCounter = ($checkBucketExist['duplicate_bucket_counter'] < 10) ? '0' . $checkBucketExist['duplicate_bucket_counter'] : $checkBucketExist['duplicate_bucket_counter'];
            } else {
                $bucketArray = $toS3client->listBuckets();
                //get array of matches string in Bucket name
                $matchCases = array();
                foreach ($bucketArray['Buckets'] as $bucketDetail) {
                    if (strpos($bucketDetail['Name'], $finalString) !== false) {
                        $matchCases[] = $bucketDetail;
                    }
                }
                if(!empty($matchCases)){
                    $getLastRecord = end($matchCases);
                    $getLastEntry = str_replace(array($firstString, '.com', $finalString), '', $getLastRecord['Name']);
                    $incrementRecord = $getLastEntry + 1;
                    $newCounter = ($incrementRecord < 10) ? '0' . $incrementRecord : $incrementRecord;
                }else{
                    $newCounter = $this->getConfigCounterAwsID($toAWS);
                }
            }
            //create next new bucket name
            $newBucketName = $firstString . $newCounter . $finalString . '.com';
            $stringPolicy = '{
                    "Version": "2012-10-17",
                    "Statement": [
                        {
                            "Sid": "Allow Public Access to All Objects",
                            "Effect": "Allow",
                            "Principal": "*",
                            "Action": "s3:GetObject",
                            "Resource": "arn:aws:s3:::' . $newBucketName . '/*"
                        }
                    ]
                }';

            //check index.html file for existing bucket
            $existIndex = false;
            $existingBucket = $fromS3client->listObjects(array('Bucket' => $bucket));
            foreach ($existingBucket['Contents'] as $existFiles) {
                if ($existFiles['Key'] == 'index.html') {
                    $existIndex = true;
                } else {
                    $existIndex = false;
                }
            }
            //if index file exist, then create bucket
            if ($existIndex) {
                try {
                    //trigger exception in a "try" block
                    $toS3client->createBucket([
                        'Bucket' => $newBucketName,
                    ]);
                    $stp = $fromS3client->listObjects(array('Bucket' => $bucket));
                    foreach ($stp['Contents'] as $object) {
                        $toS3client->copyObject(array(
                            'Bucket' => $newBucketName,
                            'Key' => $object['Key'],
                            'CopySource' => $bucket . '/' . $object['Key']
                        ));
                    }
                    $arg = array(
                        'Bucket' => $newBucketName,
                        'WebsiteConfiguration' => array(
                            'ErrorDocument' => array('Key' => 'error.html',),
                            'IndexDocument' => array('Suffix' => 'index.html',),
                        ),
                    );
                    $toS3client->putBucketWebsite($arg);
                    $toS3client->putBucketPolicy([
                        'Bucket' => $newBucketName,
                        'Policy' => $stringPolicy,
                    ]);

                    //get location for new bucket url
                    $location = $toS3client->getBucketLocation(array(
                        'Bucket' => $newBucketName
                    ));

                    $newBucketUrl = "http://" . $newBucketName . ".s3-website." . $location['LocationConstraint'] . ".amazonaws.com";
                    $finalMessage = "$newBucketUrl bucket successfully created to $toServerName account!";
                    flash($finalMessage);
                    //return response
                    $return = array(
                        'type' => 'success',
                        'message' => $finalMessage,
                    );
                    return json_encode($return);
                } catch (\Exception $exception) {
                    $xmlResponse = $exception->getAwsErrorCode();
                    if ($xmlResponse == "BucketAlreadyExists") {
                        $message = "Bucket already exists. Please change the URL.";
                    } else {
                        $message = $xmlResponse;
                    }
                    $return = array(
                        'value' => '2',
                        'type' => 'error',
                        'message' => $message,
                    );
                    return json_encode($return);
                }
            } else {
                $message = "Index.html file must be in your existing bucket, please add and try again later!";
                //return response
                $return = array(
                    'value' => '100',
                    'type' => 'error',
                    'message' => $message,
                );
                return json_encode($return);
            }
        }
    }

    /*
     *  create duplcaite with custom counter
     *  @author : nk
     */
    public function duplicate_with_custom_counter() {
        //set params
        $awsId = $_POST['awsId'];
        $dateTime = date_create('now')->format('Y-m-d H:i:s');

        //update Auto after 5mints
        $this->updateFlag();
        $flagValue = $this->getFlagVal($awsId, $dateTime);

        if ($flagValue == 0) {
            DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 1, 'created_at' => $dateTime));
            if (!empty($_POST['duplicate_for']) && !empty($_POST['duplicate_counter'])) {
                //get bucket details
                $bucket = $_POST['duplicate_for'];
                $bucketCounter = $_POST['duplicate_counter'];
                $bucketRegion = $_POST['duplicate_for_region'];
                $awsId = $_POST['awsId'];

                //get bucket first string
                $firstString = substr($bucket, 0, strcspn($bucket, '1234567890'));
                $replaceCommonString = str_replace(array($firstString, '.com'), '', $bucket);
                //replace first find string from string
                $getUniqueNumber = $this->getNumericVal($replaceCommonString);
                $finalString = preg_replace("/$getUniqueNumber/", '', $replaceCommonString, 1);
                if (empty($bucketRegion)) {
                    DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));
                    $return = array(
                        'type' => 'error',
                        'message' => "Region code cannot be empty for $bucketRegion.",
                    );
                    return json_encode($return);
                }

                //create S3 object from where to create
                $s3client = $this->s3clientObject($awsId, $bucketRegion);

                // Using operation methods creates command implicitly.
                //$bucketArray            = $s3client->listBuckets();
                $bucketSuccessResponse = array();
                for ($counter = 1; $counter <= $bucketCounter; $counter++) {
                    $newCounter = input::get('new_aws_counter');
                    $setCounter = $counter - 1;
                    $newCounter = $newCounter + $setCounter;
                    //create next new bucket name
                    $newBucketName = $firstString . $newCounter . $finalString . '.com';
                    //create string policy for Bucket
                    $stringPolicy = '{
                    "Version": "2012-10-17",
                    "Statement": [
                        {
                            "Sid": "Allow Public Access to All Objects",
                            "Effect": "Allow",
                            "Principal": "*",
                            "Action": "s3:GetObject",
                            "Resource": "arn:aws:s3:::' . $newBucketName . '/*"
                        }
                                ]
                                        }';
                    //get list of all buckets and check if bucket name already exist
                    $existName = false;
                    $result = json_encode($s3client->doesBucketExist($newBucketName));
                    if ($result == 'true') {
                        $existName = true;
                    }

                    //For Log Action
                    $actionData['user_id'] = Auth::user()->id;
                    $actionData['aws_id'] = $awsId;
                    $actionData['action_performed'] = 'Duplicate with Customer Counter';
                    $actionData['bucket_name'] = $newBucketName;
                    $this->user_action_log($actionData);


                    //if name already exist, then return error message
                    if ($existName) {
                        DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));
                        $message = "'$newBucketName' bucket already exist, please try with some other name!";
                        //return response
                        $return = array(
                            'value' => '100',
                            'type' => 'error',
                            'message' => $message,
                        );

                        return json_encode($return);
                    } else {
                        //check index.html file for existing bucket
                        $existIndex = false;
                        $existingBucket = $s3client->listObjects(array('Bucket' => $bucket));
                        foreach ($existingBucket['Contents'] as $existFiles) {
                            if ($existFiles['Key'] == 'index.html') {
                                $existIndex = true;
                            } else {
                                $existIndex = false;
                            }
                        }
                        //if index file exist, then create bucket
                        if ($existIndex) {
                            try {
                                //trigger exception in a "try" block
                                $s3client->createBucket([
                                    'Bucket' => $newBucketName,
                                ]);
                                $stp = $s3client->listObjects(array('Bucket' => $bucket));
                                foreach ($stp['Contents'] as $object) {
                                    $s3client->copyObject(array(
                                        'Bucket' => $newBucketName,
                                        'Key' => $object['Key'],
                                        'CopySource' => $bucket . '/' . $object['Key']
                                    ));
                                }
                                $arg = array(
                                    'Bucket' => $newBucketName,
                                    'WebsiteConfiguration' => array(
                                        'ErrorDocument' => array('Key' => 'error.html',),
                                        'IndexDocument' => array('Suffix' => 'index.html',),
                                    ),
                                );
                                $s3client->putBucketWebsite($arg);
                                $s3client->putBucketPolicy([
                                    'Bucket' => $newBucketName,
                                    'Policy' => $stringPolicy,
                                ]);
                                //get location for new bucket url
                                $location = $s3client->getBucketLocation(array(
                                    'Bucket' => $newBucketName
                                ));
                                $newBucketUrl = "http://" . $newBucketName . ".s3-website." . $location['LocationConstraint'] . ".amazonaws.com";
                                $bucketSuccessResponse[] = "$newBucketUrl";
                                //response in case of success if counter match!
                                if ($counter == $bucketCounter) {
                                    DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));
                                    $finalMessage = implode(' , ', $bucketSuccessResponse) . ' bucket successfully created!';
                                    flash($finalMessage);
                                    //return response
                                    $return = array(
                                        'type' => 'success',
                                        'message' => $bucketSuccessResponse,
                                    );

                                    return json_encode($return);
                                }
                            } catch (\Exception $exception) {
                                DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));
                                $xmlResponse = $exception->getAwsErrorCode();
                                if ($xmlResponse == "BucketAlreadyExists") {
                                    $message = "Bucket already exists. Please change the URL.";
                                } else {
                                    $message = $xmlResponse;
                                }
                                $return = array(
                                    'value' => '2',
                                    'type' => 'error',
                                    'message' => $message,
                                );

                                return json_encode($return);
                            }
                        } else {
                            DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));
                            $message = "Index.html file must be in your existing bucket, please add and try again later!";
                            //return response
                            $return = array(
                                'value' => '100',
                                'type' => 'error',
                                'message' => $message,
                            );

                            return json_encode($return);
                        }
                    }
                }
            } else {
                DB::table('buckets_flag_aws')->where('aws_id', $awsId)->update(array('aws_status' => 0, 'created_at' => $dateTime));
                $message = "There is some error in the params posted by you, please check!";
                //return response
                $return = array(
                    'value' => '100',
                    'type' => 'error',
                    'message' => $message,
                );
                return json_encode($return);
            }
        } else {

            $message = "Another process is running. Please wait! Don't refresh the browser";
            //return response
            $return = array(
                'value' => '100',
                'type' => 'error',
                'message' => $message,
            );
            return json_encode($return);
        }
    }

    /*
      * function to create analytics file
      * created by NK
      * created on 10 aug'17
      * $phoneNumber : From the master bucket table
      */
    public function create_analytics_fie($analyticsId) {
        $script = "";
        $script .= "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');";
        $script .= "ga('create', '$analyticsId', 'auto');";
        $script .= "ga('send', 'pageview');";
        $jsFilePath = public_path('template_data') . DIRECTORY_SEPARATOR;
        $file = fopen($jsFilePath . "analytics.js", "w");
        fwrite($file, $script);
        fclose($file);
    }
  
    /*
     *  @author : nk 23 aug 2015
     *  @var : aws_id,user_id,action_performed
     *  @Desc : To captuer each user action on each server
     */

    public function user_action_log($actionData) {
        $user = new UserActionlog;
        $user_id = $actionData['user_id'];
        $aws_id = $actionData['aws_id'];
        $action_performed = $actionData['action_performed'];
        $bucket_name = $actionData['bucket_name'];
        $user->user_id = $user_id;
        $user->aws_id = $aws_id;
        $user->bucket_name = $bucket_name;
        $user->action_performed = $action_performed;
        $user->save();
    }

    /*
     * function to Check phone number of Bucket
     * created by BK
     * created on 2nd Sept'17
     */
    public function checkPhone(){
        if(!empty($_POST['xmlLink'])){
            $exists = $this->remoteFileExists($_POST['xmlLink']);
            if ($exists) {
                $xmlPhone = simplexml_load_file($_POST['xmlLink']);
                if(!empty($xmlPhone)){
                    $xmlPhone =  (string)$xmlPhone[0];
                    $message = "Phone number found!";
                    //return response
                    $return = array(
                        'type' => 'success',
                        'message' => $message,
                        'xmlPhone' => $xmlPhone,
                    );
                    return json_encode($return);
                }else{
                    $message = "Phone number found!";
                    //return response
                    $return = array(
                        'type' => 'success',
                        'message' => $message,
                        'xmlPhone' => $xmlPhone,
                    );
                }
            } else {
                $message = "You can't change the number, due to XML not found for this Bucket!";
                //return response
                $return = array(
                    'type' => 'error',
                    'message' => $message,
                );
            }
        }else{
            $message = "XML link for bucket is not found, please check!";
            //return response
            $return = array(
                'type' => 'error',
                'message' => $message,
            );
        }
        return json_encode($return);
    }
    /*
     * function to Check whether File exit or not - CURL
     * created by BK
     * created on 2nd Sept'17
     */
    public function remoteFileExists($url) {
        $curl = curl_init($url);
        //don't fetch the actual page, you only want to check the connection is ok
        curl_setopt($curl, CURLOPT_NOBODY, true);
        //do request
        $result = curl_exec($curl);
        $ret = false;
        //if request did not fail
        if ($result !== false) {
            //if request was ok, check response code
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($statusCode == 200) {
                $ret = true;
            }
        }
        curl_close($curl);
        return $ret;
    }
	public function check_analytics_url(){
        if(!empty($_POST['analyticsLink'])){
            $exists = $this->remoteFileExists($_POST['analyticsLink']);
            if ($exists) {
                $content = file_get_contents($_POST['analyticsLink']);
                if(!empty($content)){
                    $ua_regex    = "/UA-[0-9]{5,}-[0-9]{1,}/";
                    preg_match_all($ua_regex, $content, $ua_id);
                    $u_id       = $ua_id[0][0];
                    $message = "Analytics Id found!";
                    //return response
                    $return = array(
                        'type' => 'success',
                        'message' => $message,
                        'aId' => $u_id,
                    );
                    return json_encode($return);
                }else{
                    $message = "Analytics Id found!";
                    //return response
                    $return = array(
                        'type' => 'success',
                        'message' => $message,
                        'aId' => $content,
                    );
                }
            } else {
                $message = "You can't change the Analytics Id, due to Analytics Id found for this Bucket!";
                //return response
                $return = array(
                    'type' => 'error',
                    'message' => $message,
                );
            }
        }else{
            $message = "XML link for bucket is not found, please check!";
            //return response
            $return = array(
                'type' => 'error',
                'message' => $message,
            );
        }
        return json_encode($return);
    }
	
	/*
     *  Function to update Analytics Id per bucket
     *  @author : N.k
     *  @date   : sept/02/2017
     */
    public function update_analytics_fie()
    {
        $aid                = input::get('analytics_id');
        $awsId              = input::get('analytics_aws_id');
        $bucketName         = input::get('analytics_bucket_name');
        $region             = input::get('analytics_bucket_region');
        $this->create_analytics_fie($aid);
        $awsFolderPath      = "assests/analytics.js";
        $tmp_name           = public_path('template_data') . DIRECTORY_SEPARATOR . "analytics.js";
        $allAwsServer       = ConfigAuth::where('id', "=", $awsId)->first();
        $toServerKey        = $allAwsServer['key'];
        $toServerSecretKey  = $allAwsServer['secret'];
        $s3clientToMove = new S3Client([
            'version' => 'latest',
            'region' => $region,
            'credentials' => [
                'key' => $toServerKey,
                'secret' => $toServerSecretKey
            ]
        ]);
        $result = $s3clientToMove->putObject(array(
            'Bucket' => $bucketName,
            'Key' => $awsFolderPath,
            'SourceFile' => $tmp_name,
            'ContentType' => 'application/javascript',
            'ACL' => 'public-read',
            'StorageClass' => 'REDUCED_REDUNDANCY',
        ));
        if ($result['ObjectURL'] != "") {
            $message = "Analytics Id has been updated successfully";
            flash($message);
            //return response
            $return = array(
                'type' => 'success',
                'message' => $message,
            );
            return json_encode($return);
        } else {
            $message = "Error in the system. Please wait.";
            flash($message);
            $return = array(
                'type' => 'success',
                'message' => $message,
            );
            return json_encode($return);
        }
    }
	
	
	
	
}
