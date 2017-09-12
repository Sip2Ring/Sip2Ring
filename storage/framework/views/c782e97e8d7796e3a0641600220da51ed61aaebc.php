

<?php $__env->startSection('page_title', 'Buckets'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Page Content -->
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Manage Buckets <?php if(!empty(Input::get('type')) && !empty(Input::get('x'))){ echo 'for '.strtoupper(Input::get('x'))."<br/> (".Input::get('bcnt')." Buckets)"; }; ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li class="active">All Buckets</li>
                </ol>
            </div>
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <div class="pull-right"><h2 class="bucket-total"><?php echo e($totalCount); ?><p> Buckets</p></h2></div>
            </div>
        </div>
        <!-- /row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box clearfix">
                    <div class="m-b-10 pull-left">
                        <a href="#"><span class="label label-info" id="bulkDelete">Bulk delete</span></a>
                        <a href="#"><span class="label label-info" id="copyToClipboard">Copy to Clipboard</span></a>
                    </div>
                    <div class="m-b-10 pull-right"><label>Bucket URL: <label>
                                <input type="radio" name="showLink" id="withParams" checked> With Ringba Params
                                <input type="radio" name="showLink" id="withoutParams"> Without Params
                    </div>
                    <div class="table-responsive col-sm-12">
                        <table id="bucketTable" class="table table-striped table_grid">
                            <thead>
                            <tr>
                                <th><input type="checkbox" class="bulkCheckbox"></th>
                                <th>Name</th>
                                <th>URL</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $recordCounter = 1; ?>
                            <?php $__currentLoopData = $contents['Buckets']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                try
                                {
                                $urls = $content['bucketLinkUrl'];
                                $locationConstraint = (!empty($content['LocationConstraint'])) ? $content['LocationConstraint'] : 'eu-central-1';

//                                $urlConcatOperator = ($locationConstraint=='ap-northeast-1') ? '-' : '.';
//                                $urls ="http://".$content['Name'].".s3-website".$urlConcatOperator.$locationConstraint.".amazonaws.com";

                                //replace string, get unique number and get final string of the BUCKET
                                $firstString = substr($content['Name'], 0, strcspn($content['Name'], '1234567890'));
                                $replaceCommonString = str_replace(array($firstString,'.com'), '' , $content['Name']);

                                if (preg_match('#[0-9]#',$replaceCommonString)){
                                    $getUniqueNumber = getNumericVal($replaceCommonString);
                                    //replace first find string from string
                                    $finalString =  preg_replace("/$getUniqueNumber/", '', $replaceCommonString, 1);
                                }else{
                                    $finalString = $replaceCommonString;
                                }
								$customNetworkName = $finalString;
                                //get NETWORK name
                                $networkName = '';
                               /* if (preg_match('#[0-9]#',$finalString)){
                                    $getUniqueNumber = getNumericVal($finalString);
                                    //replace first find string from string
                                    $networkName =  preg_replace("/$getUniqueNumber/", '', $finalString, 1);
                                }else{
                                    $networkName = $finalString;
                                }*/
                                //second string
								$networkName = $finalString;
                                $finalStringNew = substr($finalString, 0, strcspn($finalString, '1234567890'));
                                $regionNetwork = substr($finalStringNew, 0, 3).substr( $finalStringNew, -1 );

                                //add PID as MID from DB in params string
                                $paramsArray = array();
                                if(array_key_exists($finalString, $bucketPidArr)){
                                    $paramsArray[] = 'mid='.$bucketPidArr[$finalString]['bucket_pid'];
                                }
                                $basicParams = (!empty($paramsArray)) ? '/?'.implode('&',$paramsArray) : '';

                                //RINGA params
                                if(array_key_exists($finalString, $bucketPidArr)){
                                    if(!empty($bucketPidArr[$finalString]['ringba_code'])) { $paramsArray[] = 'rb='.$bucketPidArr[$finalString]['ringba_code'];}
                                }
                                //embed params from DB in string
                                if(array_key_exists($regionNetwork, $bucketParamArr)){
                                    $networkName = (!empty($networkName)) ? $networkName : $bucketParamArr[$regionNetwork]['bucket_short_code'];
                                    $paramsArray[] = $bucketParamArr[$regionNetwork]['bucket_parameters'].'&network='.$networkName;
                                }
                                $concatParams = implode('&',$paramsArray);
                                $embedString = (!empty($concatParams)) ? '/?'.$concatParams : '';
                                // add check not to show templates
                                if(!in_array($content['Name'], $templateArr)){
                                ?>
                                <tr>
                                    <td> <input type="checkbox" name="bucketNames[]" value="<?php echo e($content['Name'].'___'.$locationConstraint); ?>" class="bucket_checkbox"></td>
                                    <td class="currentBucketName"><?php echo e($content['Name']); ?></td>
                                    <td class="currentBucketUrl">
                                        <a class="active" href="<?php echo e($urls.$embedString); ?>"><?php echo e($urls.$embedString); ?></a>
                                        <a class="" href="<?php echo e($urls.$basicParams); ?>"><?php echo e($urls.$basicParams); ?></a>
                                        <a class="" href="<?php echo e($urls); ?>"><?php echo e($urls); ?></a></td>
                                    <td><?php echo e(date('Y-m-d H:i:s', strtotime($content['CreationDate']))); ?></td>
                                    <td class="record_actions">
                                        <!--duplicate bucket button-->
                                        <a data-toggle="modal" title="Duplicate Bucket" data-target="#duplicate_bucket_dialog" target="_blank" class="btn btn-primary1 duplicate_bucket"
                                           onclick="$('#dbucket_name').val('');
                                                   $('#duplicateFor').val($(this).parent().parent().find('td.currentBucketName').html());
                                                   $('#dbucket_name').val($(this).parent().parent().find('td.currentBucketName').html());
                                                   $('#bucketDuplicateFor').html($(this).parent().parent().find('td.currentBucketName').html());
                                                   $('#searchItem').val($('div.dataTables_filter input').val());
                                                   $('#duplicateForRegion').val('<?php echo e($locationConstraint); ?>');
                                                   ">
                                            <i class="fa fa-clone"></i>
                                        </a>

                                        <a data-toggle="modal" title="Duplicate Bucket With Custom Counter" data-target="#duplicate_bucket_dialog_with_custom_counter" target="_blank" class="btn btn-primary1 duplicate_bucket"
                                           onclick="$('#dbucket_name').val('');
                                                   $('#duplicateFor').val($(this).parent().parent().find('td.currentBucketName').html());
                                                   $('#dbucket_name').val($(this).parent().parent().find('td.currentBucketName').html());
                                                   $('#bucketDuplicateForCustomCounter').html($(this).parent().parent().find('td.currentBucketName').html());
                                                   $('#searchItem').val($('div.dataTables_filter input').val());
                                                   $('#duplicateForRegion').val('<?php echo e($locationConstraint); ?>');
                                                   ">
                                            <i class="fa fa-bitbucket "></i>
                                        </a>
                                        <!--delete bucket button-->
                                        <a data-toggle="modal" title="Delete Bucket" class="btn btn-danger1 deleteBucket" data-region="<?php echo e($locationConstraint); ?>"><i class="fa fa-trash"></i></a>
                                        <a data-toggle="modal" title="Copy Bucket URL" class="btn btn-danger1 copyBucketUrl"><i class="fa fa-copy"></i></a>
                                        <a data-toggle="modal" title="Preview Bucket URL" class="btn btn-danger1 previewBucketUrl"><i class="fa fa-history"></i></a>
                                        <a data-toggle="modal" title="Copy Bucket To AWS" class="btn btn-danger1 copyBucketToAws" data-target="#copytoaws_bucket_dialog"
                                           onclick="$('#dbucket_name_to_aws').val('');
                                                   $('#duplicateForToAws').val($(this).parent().parent().find('td.currentBucketName').html());
                                                   $('#new_bucket_name').val($(this).parent().parent().find('td.currentBucketName').html());
                                                   $('#duplicateForToAws').val($(this).parent().parent().find('td.currentBucketName').html());
                                                   $('#duplicateToAwsRegion').val('<?php echo e($locationConstraint); ?>');
                                                   ">
                                            <i class="fa fa-exchange"></i></a>
												<!--Duplcaite to Aws Auto-->

                                        <a data-toggle="modal" title="Duplcaite to Aws Auto" data-network = "<?php echo $networkName; ?>" data-target="#duplcaiteto_aws_Auto" target="_blank" class="btn btn-primary1 duplcaitetoawsAuto"
                                           onclick="$('#dbucket_name_to_aws').val('');
                                                   $('#duplicateForToAwsAuto').val($(this).parent().parent().find('td.currentBucketName').html());
                                                   $('#new_bucket_name_auto').val($(this).data('network'));
                                                   $('#duplicateForToAwsAuto').val($(this).parent().parent().find('td.currentBucketName').html());
                                                   $('#duplicateToAwsRegion').val('<?php echo e($locationConstraint); ?>');
                                                   ">
                                            <i class="fa fa-cubes"></i>
                                        </a>
                                        <!--Change Phone number from XML-->
                                        <a data-toggle="modal" title="Change phone number" class="btn btn-danger1" id="<?php echo e('record_'.$recordCounter); ?>"
                                           onclick="return checkPhone('<?php echo e('#record_'.$recordCounter); ?>');" data-xmlLink="<?php echo e($urls."/assests/phonenumber.xml"); ?>" data-bucket="<?php echo e($content['Name']); ?>" data-location="<?php echo e($locationConstraint); ?>">
                                            <i class="fa fa-phone"></i>
                                        </a>
                                        <!--Change Analytic code-->
                                        <a data-toggle="modal" title="Analytics" class="btn btn-danger1 checkanalytics" data-target="#analytics_dialogue" data-url="<?php echo $urls ?>">
                                            <i class="fa fa-google fa-lg"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php }
                                $recordCounter++;
                                }
                                catch(\Exception $exception){
                                }
                                ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
    </div>
    <!--SHOW DIALOG BOX on the basis of total buckets-->
    <?php if(count($totalCount)>=100){ ?>
    <div id="duplicate_bucket_dialog" class="modal fade form-group" role="dialog">
        <input type="hidden" name="awsId" id="awsId" value = "<?php echo e($awsId); ?>">
        <input type="hidden" id="dpass_token" name="_token" value="<?php echo e(csrf_token()); ?>">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Bucket Alert</h4>
                </div>
                <div class="modal-body">
                    <p>You have exceed the limit of buckets, please delete some buckets and process further!!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php  }else{ ?>
    <div id="duplicate_bucket_dialog" class="modal fade form-group" role="dialog">
        <div class="modal-dialog">
            <form name="duplicate_bucket" id="duplicate_bucket" action="<?php echo e(url('/duplicate-bucket')); ?>" method="post">
                <input type="hidden" id="dpass_token" name="_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="awsId" id="awsId" value = "<?php echo e($awsId); ?>">
                <input type="hidden" id="duplicateFor" name="duplicate_for" value="">
                <input type="hidden" id="duplicateForRegion" name="duplicate_for_region" value="">
                <input type="hidden" id="duplicateRegion" name="duplicate_for" value="">
                <input type="hidden" id="searchItem" name="searchItem" value="">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Duplicate bucket details:</h2>
                        <div class="row" style="padding: 0 0 10px 10px;">
                            Are you sure you want to create a duplicate Bucket from : <p id="bucketDuplicateFor"></p>
                        </div>
                        Please select number of buckets:
                        <select name="create_count" id="duplicate_counter">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="duplicateBucket" class="btn btn-primary">Create</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php } ?>
    <!-- Modal -->
    <!--SHOW DIALOG BOX on the basis of total buckets-->
    <?php if(count($totalCount)>=100){ ?>
    <div id="duplicate_bucket_dialog_with_custom_counter" class="modal fade form-group" role="dialog">
        <input type="hidden" name="awsId" id="awsId" value = "<?php echo e($awsId); ?>">
        <input type="hidden" id="dpass_token" name="_token" value="<?php echo e(csrf_token()); ?>">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Bucket Alert</h4>
                </div>
                <div class="modal-body">
                    <p>You have exceed the limit of buckets, please delete some buckets and process further!!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php  }else{ ?>
    <div id="duplicate_bucket_dialog_with_custom_counter" class="modal fade form-group" role="dialog">
        <div class="modal-dialog">
            <form name="duplicate_bucket" id="duplicate_bucket" action="<?php echo e(url('/duplicate-bucket')); ?>" method="post">
                <input type="hidden" id="dpass_token" name="_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="awsId" id="awsId" value = "<?php echo e($awsId); ?>">
                <input type="hidden" id="duplicateFor" name="duplicate_for" value="">
                <input type="hidden" id="duplicateForRegion" name="duplicate_for_region" value="">
                <input type="hidden" id="duplicateRegion" name="duplicate_for" value="">
                <input type="hidden" id="searchItem" name="searchItem" value="">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Duplicate bucket details:</h2>
                        <div class="row" style="padding: 0 0 10px 10px;">
                            Are you sure you want to create a duplicate Bucket from : <p id="bucketDuplicateForCustomCounter"></p>
                        </div>
                        <div class="row" style="padding: 0 0 10px 10px;">
                            <p>Custom Bucket Counter</p>
                            <input type="number" value="" name="custom_conter" id="custom_counter">
                        </div>
                        Please select number of buckets:
                        <select name="create_count" id="duplicate_counter">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="duplicateBucketWithCustomCounter" class="btn btn-primary">Create</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php } ?>
    <!-- Modal  custom Counter End -->
    <a data-toggle="modal" data-target="#bucketAlert" target="_blank" class="btn btn-primary bucketAlert_anchor"></a>
    <div class="modal fade" id="bucketAlert" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Bucket Alert</h4>
                </div>
                <div class="modal-body">
                    <p>You are going to exceed the limit of buckets, please delete some buckets and process further!!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!--- copy to aws popup -->
    <div id="copytoaws_bucket_dialog" class="modal fade form-group " role="dialog">
        <div class="modal-dialog">
            <form name="copytoaws_bucket" id="copytoaws_bucket" action="<?php echo e(url('/duplicate-bucket-to-aws')); ?>" method="post">
                <input type="hidden" id="dpass_token_aws" name="_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="awsId" id="awsId" value = "<?php echo e($awsId); ?>">
                <input type="hidden" id="duplicateForToAws" name="duplicate_for" value="">
                <input type="hidden" id="duplicateToAwsRegion" name="duplicate_aws_region" value="">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Select AWS Server:</h2>
                        <select name="create_count" class="form-control" id="aws_server_id" required>
                            <?php $__currentLoopData = $allAwsServer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allAwsServerVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($allAwsServerVal['id']); ?>"><?php echo e(ucwords($allAwsServerVal['aws_name'])); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <h2>New Bucket Name:</h2>
                        <input type="text" name="new_bucket_name" id="new_bucket_name" required class="form-control">
                        <p style="color:red;">i.e. Just change the url or bucket name as per your requirement</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="duplicateBucketToAws" class="btn btn-primary">Create</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- copy to aws popup -->
	
	<!--- Duplicate  to awsauto popup -->
    <div id="duplcaiteto_aws_Auto" class="modal fade form-group " role="dialog">
        <div class="modal-dialog">
            <form name="copytoaws_bucket" id="copytoaws_bucket">
                <input type="hidden" id="dpass_token_aws" name="_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="awsId" id="awsId" value = "<?php echo e($awsId); ?>">
                <input type="hidden" id="duplicateForToAwsAuto" name="duplicate_for" value="">
                <input type="hidden" id="duplicateToAwsRegion" name="duplicate_aws_region" value="">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Select AWS Server:</h2>
                        <select name="create_count" class="form-control" id="duplicateAwsServerID" required>
                            <?php $__currentLoopData = $allAwsServer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allAwsServerVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($allAwsServerVal['id']); ?>"><?php echo e(ucwords($allAwsServerVal['aws_name'])); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <input type="hidden" name="new_bucket_name" id="new_bucket_name_auto" required class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="duplcaitetoawsAuto" class="btn btn-primary">Create</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Duplicate  to awsauto popup -->
    <!--- phonenumber popup -->
    <div id="update_phone_dialogue" class="modal fade form-group " role="dialog">
        <div class="modal-dialog">
            <form name="phone_number_update_form" id="phone_number_update_form" action="<?php echo e(url('/duplicate-bucket-to-aws')); ?>" method="post">
                <input type="hidden" id="dpass_token_aws" name="_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="awsId" id="awsId" value = "<?php echo e($awsId); ?>">
                <input type="hidden" id="template_name" name="template_name" value="">
                <input type="hidden" id="region" name="region" value="">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Update Phone Number:</h2>
                        <input type="text" name="phone_number" id="phone_number" required class="form-control">
                        <p style='color:red;'>Please add the number in proper format</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="phone_number_update" class="btn btn-primary">Create</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /.container-fluid -->
    <!--- Analytics popup -->
    <div id="analytics_dialogue" class="modal fade form-group " role="dialog">
        <div class="modal-dialog">
            <form name="analytics_form" id="analytics_form"  method="post">
                <input type="hidden" id="dpass_token_aws" name="_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="awsId" id="awsId" value = "<?php echo e($awsId); ?>">
                <input type="hidden" id="template_name" name="template_name" value="">
                <input type="hidden" id="region" name="region" value="">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Analytics Id:</h2>
                        <input type="textarea" rows="10" name="analytics_id" id="analytics_id" required class="form-control">
                        <input type="hidden" name="analytics_url" id="analytics_url" required class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="analytics_id_update" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php $searchValue = (!empty($_GET['x'])) ? $_GET['x'] : '';?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script>
        $(window).load(function() {
            var searchItemValue = '<?php echo $searchValue; ?>';
            $('div.dataTables_filter input').val(searchItemValue).trigger('keyup');
        });
    </script>
    <script>
        //add bucket name as input and add into DB
        $(document).ready(function($){
            $('#withParams').trigger('click');
            $('input:checkbox').removeAttr('checked');
            //show url on the basis of selected by User
            $('#withParams').click(function(){
                $('.currentBucketUrl a').removeClass('active');
                $('.currentBucketUrl a:first-child').addClass('active');
            });
            /* $('#withBasicParams').click(function(){
             $('.currentBucketUrl a').removeClass('active');
             $('.currentBucketUrl a:nth-child(2)').addClass('active');
             }); */
            $('#withoutParams').click(function(){
                $('.currentBucketUrl a').removeClass('active');
                $('.currentBucketUrl a:nth-child(3)').addClass('active');
            });
            //select all checkbox at single click
            $('.bulkCheckbox').click(function() {
                if ($(this).prop('checked')) {
                    $('.bucket_checkbox').prop('checked', true);
                } else {
                    $('.bucket_checkbox').prop('checked', false);
                }
            });
            //Copy Link
            $('.copyBucketUrl').click(function() {
                var bucketUrl = $(this).parent().parent().find('td.currentBucketUrl a.active').html();
                bucketUrl = replaceAll("&amp;", "&", bucketUrl);
                copyToClipboard(bucketUrl);
            });
            //Preview Link
            $('.previewBucketUrl').click(function() {
                var bucketUrl = $(this).parent().parent().find('td.currentBucketUrl a.active').html();
                window.open(bucketUrl, '_blank');
            });
            //check for duplicate event
            $('#dbucket_name, #duplicateFor').val('');
            var totalBuckets = '<?php echo count($contents['Buckets']) + count($masterBuckets)?>';
            if(totalBuckets>95) { jQuery('.bucketAlert_anchor').trigger('click');}
            //duplicate bucket
            $(document).on('click','#duplicateBucket',function(){
                var duplicateFor = $('#duplicateFor').val();
                var awsId = $('#awsId').val();;
                var duplicateForRegion = $('#duplicateForRegion').val();
                var duplicateCounter = $('#duplicate_counter').val();
                var passToken = $('#dpass_token').val();
                var url = '<?php echo e(url('/duplicate-bucket')); ?>';
                var searchItem = $('#searchItem').val();
                var successRedirect = '<?php echo e(url('/buckets')); ?>?x='+searchItem;
                customValid = true;
                var totalError  = [];
                var totalSuccess = [];
                if(totalError.length>0)
                {
                    for (count = 0; count <= totalError.length; count++) {
                        $('#' + totalError[count]).css({"background-color": "#F2C1C1", "border": "1px solid #FF0000"});
                        $('#' + totalError[count]).parent().find('span').html("This field can not be empty!");
                        customValid = false
                    }
                }
                if(totalSuccess.length>0)
                {
                    for (count = 0; count <= totalSuccess.length; count++) {
                        $('#' + totalSuccess[count]).css({"background-color": "#FFFFFF", "border": "1px solid #D7D7D7"});
                        $('#' + totalSuccess[count]).parent().find('span').html('');
                    }
                }
                if (customValid == false){
                    return false;
                }else{
                    $('div#overlay').show();
                    $.ajax({
                        type: 'POST',
                        'url': url,
                        async: false,
                        data: {
                            '_token': passToken,
                            '_token': passToken,
                            'awsId': awsId,
                            'duplicate_counter': duplicateCounter,
                            'duplicate_for': duplicateFor,
                            'duplicate_for_region': duplicateForRegion
                        },
                        success:function(data){
                            $('div#overlay').hide();
                            var res = jQuery.parseJSON(data);
                            if(res.type=='success'){
                                window.location.href = successRedirect;
                                return false;
                            }
                            if(res.type=='error'){
                                alert(res.message);
                                return false;
                            }
                        }
                    });
                    return false;
                }
            });
            //duplicate bucket with custom counter
            $(document).on('click','#duplicateBucketWithCustomCounter',function(){
                var custom_counter = $('#custom_counter').val();
                var duplicateFor        = $('#duplicateFor').val();
                var awsId               = $('#awsId').val();;
                var duplicateForRegion  = $('#duplicateForRegion').val();
                var duplicateCounter    = $('#duplicate_bucket_dialog_with_custom_counter #duplicate_counter').val();
                var passToken           = $('#dpass_token').val();
                var url                 = '<?php echo e(url('/duplicate-bucket-with-custom-counter')); ?>';
                var searchItem          = $('#searchItem').val();
                var successRedirect     = '<?php echo e(url('/buckets')); ?>?x='+searchItem;
                customValid = true;
                var totalError          = [];
                var totalSuccess        = [];
                if(custom_counter==""){
                    totalError.push('custom_counter');
                }else{
                    totalSuccess.push('custom_counter');
                }
                if(totalError.length>0)
                {
                    for (count = 0; count <= totalError.length; count++) {
                        $('#' + totalError[count]).css({"background-color": "#F2C1C1", "border": "1px solid #FF0000"});
                        $('#' + totalError[count]).parent().find('span').html("This field can not be empty!");
                        customValid = false
                    }
                }
                if(totalSuccess.length>0)
                {
                    for (count = 0; count <= totalSuccess.length; count++) {
                        $('#' + totalSuccess[count]).css({"background-color": "#FFFFFF", "border": "1px solid #D7D7D7"});
                        $('#' + totalSuccess[count]).parent().find('span').html('');
                    }
                }
                if (customValid == false){
                    return false;
                }else{
                    $('div#overlay').show();
                    $.ajax({
                        type: 'POST',
                        'url': url,
                        async: false,
                        data: {
                            '_token': passToken,
                            '_token': passToken,
                            'awsId': awsId,
                            'duplicate_counter': duplicateCounter,
                            'duplicate_for': duplicateFor,
                            'new_aws_counter': custom_counter,
                            'duplicate_for_region': duplicateForRegion
                        },
                        success:function(data){
                            $('div#overlay').hide();
                            var res = jQuery.parseJSON(data);
                            if(res.type=='success'){
                                window.location.href = successRedirect;
                                return false;
                            }
                            if(res.type=='error'){
                                alert(res.message);
                                return false;
                            }
                        }
                    });
                    return false;
                }
            });
            //Delete bucket
            $(document).on('click','.deleteBucket',function(){
                var passToken = $('#dpass_token').val();
                var awsId = $('#awsId').val();
                var duplicateForRegion = $(this).attr('data-region');
                var bucketName = $(this).parent().parent().find('td.currentBucketName').html();
                var url = '<?php echo e(url('/delete-bucket')); ?>';
                var successRedirect = '<?php echo e(url('/buckets')); ?>';
                if(confirm("Are you sure to delete bucket?")){
                    $('#overlay').show();
                    $.ajax({
                        type: 'POST',
                        'url': url,
                        async: false,
                        data: {
                            '_token': passToken,
                            'awsId': awsId,
                            'bucket_name': bucketName,
                            'duplicate_for_region': duplicateForRegion
                        },
                        success:function(data){
                            $('#overlay').hide();
                            var res = jQuery.parseJSON(data);
                            if(res.type=='success'){
                                window.location.href = successRedirect;
                                return false;
                            }
                            if(res.type=='error'){
                                alert(res.message);
                                return false;
                            }
                        }
                    });
                }
            });
            //delete buckets in bulk
            $(document).on('click','#bulkDelete',function(){
                var bucketNames = [];
                var atLeastOneIsChecked = $('input[name=\"bucketNames[]\"]:checked').length > 0;
                if (atLeastOneIsChecked) {
                    $('input[name=\"bucketNames[]\"]:checked').each(function () {
                        bucketNames.push($(this).val());
                    });
                }else{
                    alert('Please Select At Least One Bucket');
                    return false;
                }
                //confirm before delete buckets in bulk
                var url = '<?php echo e(url('/delete-multiple-bucket')); ?>';
                var passToken = $('#dpass_token').val();
                var awsId = $('#awsId').val();
                var successRedirect = '<?php echo e(url('/buckets')); ?>';
                if(confirm("Are you sure to delete bucket?")){
                    $('#overlay').show();
                    $.ajax({
                        type: 'POST',
                        'url': url,
                        async: false,
                        data: {
                            'awsId': awsId,
                            '_token': passToken,
                            'bucket_name': bucketNames
                        },
                        success:function(data){
                            $('#overlay').hide();
                            var res = jQuery.parseJSON(data);
                            if(res.type=='success'){
                                window.location.href = successRedirect;
                                return false;
                            }
                            if(res.type=='error'){
                                alert(res.message);
                                return false;
                            }
                        }
                    });
                }
            });
            //copy bucket links to clipboard
            $(document).on('click','#copyToClipboard',function(){
                var bucketNames = [];
                var atLeastOneIsChecked = $('input[name=\"bucketNames[]\"]:checked').length > 0;
                if (atLeastOneIsChecked) {
                    $('input[name=\"bucketNames[]\"]:checked').each(function () {
                        var bucketURL = $(this).parent().parent().find('td.currentBucketUrl a.active').html();
                        bucketURL = replaceAll("&amp;", "&", bucketURL);
                        bucketNames.push(bucketURL);
                    });
                    var newArr = bucketNames.join(',').replace(/,/g, '\r\n').split();
                }else{
                    alert('Please Select At Least One Bucket');
                    return false;
                }
                copyToClipboard(newArr);
            });
            //copy to clipboard
            function copyToClipboard(selectedLink) {
                // alert(selectedLink);
                var $temp = $("<textarea>");
                $("body").append($temp);
                $temp.val(selectedLink).select();
                document.execCommand("copy");
                $temp.remove();
            }
        });
        //duplciate bucket to aws
        $(document).on('click','#duplicateBucketToAws',function(){
            var duplicateFor                = $('#duplicateForToAws').val();
            var duplicateFor                = $('#duplicateForToAws').val();
            var awsId                       = $('#awsId').val();
            var duplicateToAwsRegion        = $('#duplicateToAwsRegion').val();
            var awsServerId                 = $('#aws_server_id').val();
            var passToken                   = $('#dpass_token_aws').val();
            var new_bucket_name             = $('#new_bucket_name').val();
            var url = '<?php echo e(url('/duplicate-bucket-to-aws')); ?>';
            // alert(duplicateFor);
            var successRedirect = '<?php echo e(url('/buckets')); ?>';
            $('div#overlay').show();
            $.ajax({
                type: 'GET',
                'url': url,
                async: false,
                data: {
                    '_token': passToken,
                    'awsId': awsId,
                    'duplicate_for': duplicateFor,
                    'duplicateToAwsRegion': duplicateToAwsRegion,
                    'aws_server_id': awsServerId,
                    'new_bucket_name': new_bucket_name,
                },
                success:function(data){
                    $('div#overlay').hide();
                    var res = jQuery.parseJSON(data);
                    if(res.type=='success'){
                        window.location.href = successRedirect;
                        return false;
                    }
                    if(res.type=='error'){
                        alert(res.message);
                        return false;
                    }
                }
            });
            return false;
        });

        //duplciate bucket to awsAuto
        $(document).on('click','#duplcaitetoawsAuto',function(){
            var duplicateFor                = $('#duplicateForToAwsAuto').val();
            var awsId                       = $('#awsId').val();
            var duplicateToAwsRegion        = $('#duplicateToAwsRegion').val();
            var awsServerId                 = $('#duplicateAwsServerID').val();
            var passToken                   = $('#dpass_token_aws').val();
            var new_bucket_name             = $('#new_bucket_name_auto').val();
            var url = '<?php echo e(url('/duplicate-bucket-to-aws-auto')); ?>';
            // alert(duplicateFor);
            var successRedirect = '<?php echo e(url('/buckets')); ?>';
            $('div#overlay').show();
            $.ajax({
                type: 'POST',
                'url': url,
                async: false,
                data: {
                    '_token': passToken,
                    'awsId': awsId,
                    'duplicate_for': duplicateFor,
                    'new_bucket_name': new_bucket_name,
                    'duplicateToAwsRegion': duplicateToAwsRegion,
                    'aws_server_id': awsServerId,
                },
                success:function(data){
                    $('div#overlay').hide();
                    var res = jQuery.parseJSON(data);
                    if(res.type=='success'){
                        window.location.href = successRedirect;
                        return false;
                    }
                    if(res.type=='error'){
                        alert(res.message);
                        return false;
                    }
                }
            });
            return false;
        });
        //update phone
        $(document).on('click','#phone_number_update',function(){
            var bucket_name                 = $('#template_name').val();
            var region                      = $('#region').val();
            var phone_number                = $('#phone_number').val();
            var passToken                   = $('#dpass_token_aws').val();
            var searchItem                  = $('div.dataTables_filter input').val();
            var url                         = '<?php echo e(url('/update-phone-xml-fie')); ?>';
            // alert(duplicateFor);
            var successRedirect = '<?php echo e(url('/buckets')); ?>?x='+searchItem;
            $('div#overlay').show();
            $.ajax({
                type: 'GET',
                'url': url,
                async: false,
                data: {
                    '_token': passToken,
                    'bucket_name': bucket_name,
                    'region': region,
                    'phone_number': phone_number,
                },
                success:function(data){
                    $('div#overlay').hide();
                    var res = jQuery.parseJSON(data);
                    if(res.type=='success'){
                        window.location.href = successRedirect;
                        return false;
                    }
                    if(res.type=='error'){
                        alert(res.message);
                        return false;
                    }
                }
            });
            return false;
        });
        //update analytics
        $(document).on('click','#analytics_id_update',function(){
            var bucket_name                 = $('#template_name').val();
            var region                      = $('#region').val();
            var file_path                   = $('#analytics_url').val();
            var analytics_id                = $('#analytics_id').val();
            var passToken                   = $('#dpass_token_aws').val();
            var searchItem                  = $('div.dataTables_filter input').val();
            var url                         = '<?php echo e(url('/create_analytics_fie')); ?>';
            // alert(duplicateFor);
            var successRedirect = '<?php echo e(url('/buckets')); ?>?x='+searchItem;
            $.ajax({
                type: 'POST',
                'url': url,
                async: false,
                data: {
                    '_token': passToken,
                    'bucket_name': bucket_name,
                    'region': region,
                    'analytics_id': analytics_id,
                },
                success:function(data){
                    alert(data);return false;
                }
            });
            return false;
        });
        $(document).on("click",".checkanalytics",function(){
            var ajax_url  = '<?php echo e(url('/check-analytics-url')); ?>';
            var passToken = '<?php echo e(csrf_token()); ?>';
            $url = $(this).data('url');
            $.ajax({
                type: 'POST',
                'url': ajax_url,
                async: false,
                data: {
                    '_token': passToken,
                    'url':$url,
                },
                success:function(data){
                    if(data){
                        $('#analytics_id').val(data);
                        //$('#analytics_url').val($url);
                    }else{
                        alert("Analytics Id Not Found");return false;
                    }
                }
            });
        });

        $(document).on('click', '.paginate_button', function(){
            if($('#withoutParams').is(':checked')) { $('#withoutParams').trigger('click'); }
            if($('#withParams').is(':checked')) { $('#withParams').trigger('click'); }
        });
        function replaceAll(find, replace, str)
        {
            while( str.indexOf(find) > -1)
            {
                str = str.replace(find, replace);
            }
            return str;
        }
        function checkPhone(recordID) {
            var url = '<?php echo e(url('/check-phone')); ?>';
            var passToken = $('#dpass_token_aws').val();
            var xmlLink = $(recordID).attr('data-xmlLink');
            var bucketName = $(recordID).attr('data-bucket');
            var bucketRegion = $(recordID).attr('data-location');
            $('div#overlay').show();
            $.ajax({
                type: 'POST',
                'url': url,
                async: false,
                data: {
                    '_token': passToken,
                    'xmlLink': xmlLink,
                },
                success:function(data){
                    $('div#overlay').hide();
                    var res = jQuery.parseJSON(data);
                    if(res.type=='success'){
                        var xmlPhn = res.xmlPhone;
                        $('#phone_number').val(xmlPhn);
                        $('#template_name').val(bucketName);
                        $('#region').val(bucketRegion);
                        $('#update_phone_dialogue').modal('show');
                        return false;
                    }
                    if(res.type=='error'){
                        alert(res.message);
                        return false;
                    }
                }
            });
        }
    </script>
    <style>
        .modal{z-index: 8888;}
        .currentBucketUrl a{display:none;}
        .currentBucketUrl a.active{display:block;}
    </style>
    <?php
    function getNumericVal ($str) {
        preg_match_all('/\d+/', $str, $matches);
        return (!empty($matches[0][0])) ? $matches[0][0] : '';
    }
    ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dataTable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>