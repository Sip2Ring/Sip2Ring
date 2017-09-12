<?php $__env->startSection('page_title', 'Add User'); ?>

<?php $__env->startSection('content'); ?>
<div class="panel-container col-sm-12">
        <div class="panels panels-default collapsible">
            <div class="panel_header">
                <span class="fa fa-bolt panel-icon"></span>
                Target
                <div class="pull-right iconBar">
                    <span class="minimise cntrlBtnGrp">
                        <span class="fa fa-minus"></span>
                    </span>
                    <span class="closeme cntrlBtnGrp">
                        <span class="fa fa-close"></span>
                    </span>
                </div>
            </div>
            <div class="panel_warper">
                <div class="panel_body">
                    <form  method="post" accept-charset="utf-8" id="form_id" action="<?php echo e(url('/add-target')); ?>">
                        <div class="form responsiveForm col-sm-12">
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="name">Name <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control input-md" id="name"  name="name" autofocus >
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="name">Owner <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control" name="owner" id="owner" required="required">
                                        <option value="">Select</option>
                                        <option value="Harrision">Harrision</option>
                                        <option value="Dwi">Dwi</option>
                                        <option value="Yessh">Yessh</option>
                                    </select>
                                   
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="name">Type <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <div class="btn-group" id="status" data-toggle="buttons">
                                        <label class="btn btn-primary btn-on active">
                                            <input type="radio" value="1" name="track_type" checked="checked">Number</label>
                                        <label class="btn btn-primary btn-off">
                                            <input type="radio" value="0" name="track_type">SIP</label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 formInputContainer sip">
                                <div class="col-sm-3 labelContainer">
                                    <label for="sip_endpoint">SIP Endpoint <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control input-md" id="sip_endpoint"  name="sip_endpoint" autofocus >
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 formInputContainer sip">
                                <div class="col-sm-3 labelContainer">
                                    <label for="sip_username">SIP Username <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control input-md" id="sip_username"  name="sip_username" autofocus >
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 formInputContainer sip">
                                <div class="col-sm-3 labelContainer">
                                    <label for="sip_password">SIP Password <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control input-md" id="sip_password"  name="sip_password" autofocus >
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 formInputContainer number">
                                <div class="col-sm-3 labelContainer">
                                    <label for="number">Number <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="number" class="form-control input-md" id="number"  name="number" autofocus >
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="delay_conversions">Delay Conversions <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control input-md" id="delay_conversions"  name="delay_conversions" autofocus >
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="timeout">Timeout <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control input-md" id="timeout"  name="timeout" autofocus >
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="dial_on_answer">Dail on Answer <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control input-md" id="dial_on_answer"  name="dial_on_answer" autofocus >
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="dial_on_answer">Time Zone <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control" name="time_zone" id="time_zone" required="required">
                                        <option value="">Select</option>
                                        <option value="hawaii">(UTC-10:00)Hawaii</option>
                                        <option value="marquess_island">(UTC-09:30)Marquess Islands</option>
                                        <option value="alaska">(UTC-09:00)Alaska</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="hours_of_operation">Hours of Operation <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <div class="col-sm-3">
                                        <label class="switch">
                                            <input type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control input-md" id="hours_of_operation"  name="hours_of_operation" autofocus >
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="global_call_cap">Global Call Cap <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <div class="col-sm-3">
                                        <label class="switch">
                                            <input type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control input-md" id="global_call_cap"  name="global_call_cap" autofocus >
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="monthly_call_cap">Monthly Call Cap <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <div class="col-sm-3">
                                        <label class="switch">
                                            <input type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control input-md" id="monthly_call_cap"  name="monthly_call_cap" autofocus >
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="daily_call_cap">Daily Call Cap <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <div class="col-sm-3">
                                        <label class="switch">
                                            <input type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control input-md" id="daily_call_cap"  name="daily_call_cap" autofocus >
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="hourly_call_cap">Hourly Call Cap <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <div class="col-sm-3">
                                        <label class="switch">
                                            <input type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control input-md" id="hourly_call_cap"  name="hourly_call_cap" autofocus >
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="concurrency_cap">Concurrency Cap <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <div class="col-sm-3">
                                        <label class="switch">
                                            <input type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control input-md" id="concurrency_cap"  name="concurrency_cap" autofocus >
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="hourly_concurrency">Hourly Concurrency <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <div class="col-sm-3">
                                        <label class="switch">
                                            <input type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control input-md" id="hourly_concurrency"  name="hourly_concurrency" autofocus >
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                            
                            <div class="col-sm-12 text-center m-t-40">
                                <button type="submit" class="btn btn-primary">Save Target</button>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>                     
            </div>
        </div>
    </div>
    <?php $__env->stopSection(); ?>

  
<?php echo $__env->make('layouts.adminDashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>