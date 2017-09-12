

<?php $__env->startSection('page_title', 'Add User'); ?>

<?php $__env->startSection('content'); ?>
<?php

if(!empty($getDetails)){
$name = $getDetails->name;
$country = $getDetails->country;
$business_name= $getDetails->business_name;
$street_address= $getDetails->street_address;
$city= $getDetails->city;
$state_region= $getDetails->state_region;
$zip_code= $getDetails->zip_code;
}

?>
<div ng-controller="dashboardController">
    <div class="panel-container col-sm-12">
        <div class="panels panels-default collapsible">
            <div class="panel_header">
                <span class="fa fa-bolt panel-icon"></span>
                Demo Form
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
                    <form  method="post" accept-charset="utf-8" id="add_user" action="<?php echo e(url('/user-insert')); ?>">
                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                        <div class="form responsiveForm col-sm-12">
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="email">Name <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <?php if(!empty($name)){?>
                                  <input type="text" name="name" class="form-control" value="<?php echo e($name); ?>"  placeholder="" id="name">
                                    <?php }else{?>
                                    <input type="text" name="name" class="form-control"  placeholder="" id="name">
                                    <?php }?>
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="email">Contry<span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control" name="country" id="country">
                                        <option value="">---- Pilih Kotama----</option>
                                        <?php $__currentLoopData = $getCountrylist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($country->id); ?>"><?php echo e($country->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="email">Buisness Name <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <?php if(!empty($business_name)){?>

                                    <input type="text" name="buisename" class="form-control" value="<?php echo e($business_name); ?>"  placeholder="" id="buis_name">
                                    <?php }else{?>
                                    <input type="text" name="buisename" id="buis_name" class="form-control" >
                                <?php }?>
                                    <!--<label class="switch">
                                        <input type="checkbox">
                                        <span class="slider round"></span>
                                    </label>-->
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="email">City<span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <?php if(!empty($city)){?>

                                    <input type="text" name="city" class="form-control" value="<?php echo e($city); ?>"  placeholder="" id="buis_name">
                                    <?php }else{?>
                                    <input type="text" name="city" id="buis_name" class="form-control" >
                                <?php }?>
                                <!--<label class="switch">
                                        <input type="checkbox">
                                        <span class="slider round"></span>
                                    </label>-->
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="email">Street Address <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">

                                    <?php if(!empty($street_address)){?>
                                        <input type="text" name="street_address" id="street_addr" value="<?php echo e($street_address); ?>>" class="form-control"  >
                                        <?php }else{?>
                                        <input type="text" name="street_address" id="street_addr" class="form-control" >

                                        <?php }?>

                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="email">State Region <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">

                                    <?php if(!empty($state_region)){?>
                                    <label class="sr-only">State Region</label>
                                    <input type="text" name="state" id="state" value="<?php echo e($state_region); ?>" class="form-control" >
                                        <?php }else{?>
                                            <input type="text" name="state" id="state" class="form-control" >

                                        <?php }?>

                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="email">Zip Postal Code<span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">

                                    <?php if(!empty($zip_code)){?>

                                    <input type="text" name="postalcode" id="postalcode" value="<?php echo e($zip_code); ?>" class="form-control">
                                            <?php }else{?>
                                                <input type="text" name="postalcode" id="postalcode" class="form-control">

                                            <?php }?>

                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 text-center m-t-40">
                                <button type="submit" class="btn btn-primary">Add Publisher</button>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    <?php $__env->stopSection(); ?>







<?php echo $__env->make('layouts.adminDashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>