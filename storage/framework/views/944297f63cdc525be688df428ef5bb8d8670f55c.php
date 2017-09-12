
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
            <section id="contact3" class="contact3">
                <div class="container">
                    <div class="row">
                        <div class="contact-overlay">
                            <div class="col-md-6">  
                                <h1 class="section-title">Make a Call</h1>
                                <?php echo Form::open(['route' => 'call.initiate','id'=>'add_user']); ?>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?php if(!empty($name)){?>
                                            <label class="sr-only">Name</label>
                                            <input type="text" name="name" class="form-control" value="<?php echo e($name); ?>"  placeholder="" id="name">
                                            <?php }else{?>
                                                <input type="text" name="name" class="form-control"  placeholder="" id="name">
                                                    <?php }?>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <select class="form-control" name="country" id="country">
                                                <option value="">---- Pilih Kotama----</option>
                                                <?php $__currentLoopData = $getCountrylist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($country->id); ?>"><?php echo e($country->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?php if(!empty($business_name)){?>
                                                <label class="sr-only">Buisness Name</label>
                                            <input type="text" name="buisename" class="form-control" value="<?php echo e($business_name); ?>"  placeholder="" id="buis_name">
                                            <?php }else{?>
                                                <input type="text" name="buisename" id="buis_name" class="form-control" >
                                            <?php }?>
                                        </div>

                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?php if(!empty($street_address)){?>
                                            <label class="sr-only">Street Address</label>

                                            <input type="text" name="street_address" id="street_addr" value="<?php echo e($street_address); ?>" class="form-control"  >
                                            <?php }else{?>
                                                <input type="text" name="street_address" id="street_addr" class="form-control" >

                                            <?php }?>
                                        </div>

                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?php if(!empty($city)){?>
                                                <label class="sr-only">City</label>
                                                <input type="text" name="city" id="city" class="form-control" value="<?php echo e($city); ?>" >
                                            <?php }else{?>
                                                <input type="text" name="city" id="city" class="form-control" >

                                            <?php }?>
                                        </div>

                                    </div>
                                    <div class="col-sm-6">
                                    <div class="form-group">
                                        <?php if(!empty($state_region)){?>
                                        <label class="sr-only">State Region</label>
                                            <input type="text" name="state" id="state" value="<?php echo e($state_region); ?>" class="form-control" >
                                        <?php }else{?>
                                            <input type="text" name="state" id="state" class="form-control" >

                                        <?php }?>
                                    </div>

                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?php if(!empty($zip_code)){?>
                                                <label class="sr-only">Zip Postal Code</label>
                                                <input type="text" name="postalcode" id="postalcode" value="<?php echo e($zip_code); ?>" class="form-control">
                                            <?php }else{?>
                                                <input type="text" name="postalcode" id="postalcode" class="form-control">

                                            <?php }?>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                        <?php echo e(Form::submit('Add Address!', ['class' => ''])); ?>

                                        </div>
                                    </div>
                                </div>
                                <?php echo Form::close(); ?>

                            </div>

                        </div>
                    </div>


                </div>
            </section>





<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<script type="text/javascript" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#dataTable').DataTable();
    });


</script>
<script type="text/javascript">
    $(function(){

        $('#add_user').on('submit',function(e){
        // alert("ggg");
            var name                        = $('#name').val();
            var country                      = $('#country').val();
            var buis_name                   = $('#buis_name').val();
            var street_addrress                   = $('#street_addr').val();
             var city                         = $('#city').val();
             var state                         = $('#state').val();
             var postalcode                         = $('#postalcode').val();

            $.ajax({

                type:"POST",
                url:'/user-insert',
                data: {
                    'name': name,
                    'country': country,
                    'buis_name': buis_name,
                    'street_addrress': street_addrress,
                    'state': state,
                    'postalcode': postalcode,
                    'city': city,
                },
                async: false,
                success: function(data){
                    console.log(data);
                },
                error: function(data){

                }
            })
        });
    });
</script>