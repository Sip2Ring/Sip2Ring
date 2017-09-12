
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
                                <h1 class="section-title">User</h1>
                                <from action="{{ url('/user-insert') }}"  id="add_user" />
                              
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?php if(!empty($name)){?>
                                            <label class="sr-only">Name</label>
                                            <input type="text" name="name" class="form-control" value="{{$name}}"  placeholder="" id="name">
                                            <?php }else{?>
                                                <input type="text" name="name" class="form-control"  placeholder="" id="name">
                                                    <?php }?>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <select class="form-control" name="country" id="country">
                                                <option value="">---- Pilih Kotama----</option>
                                                @foreach($getCountrylist as $country)
                                                    <option value="{{$country->id}}">{{$country->name}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?php if(!empty($business_name)){?>
                                                <label class="sr-only">Buisness Name</label>
                                            <input type="text" name="buisename" class="form-control" value="{{$business_name}}"  placeholder="" id="buis_name">
                                            <?php }else{?>
                                                <input type="text" name="buisename" id="buis_name" class="form-control" >
                                            <?php }?>
                                        </div>

                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?php if(!empty($street_address)){?>
                                            <label class="sr-only">Street Address</label>

                                            <input type="text" name="street_address" id="street_addr" value="{{$street_address}}" class="form-control"  >
                                            <?php }else{?>
                                                <input type="text" name="street_address" id="street_addr" class="form-control" >

                                            <?php }?>
                                        </div>

                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?php if(!empty($city)){?>
                                                <label class="sr-only">City</label>
                                                <input type="text" name="city" id="city" class="form-control" value="{{$city}}" >
                                            <?php }else{?>
                                                <input type="text" name="city" id="city" class="form-control" >

                                            <?php }?>
                                        </div>

                                    </div>
                                    <div class="col-sm-6">
                                    <div class="form-group">
                                        <?php if(!empty($state_region)){?>
                                        <label class="sr-only">State Region</label>
                                            <input type="text" name="state" id="state" value="{{$state_region}}" class="form-control" >
                                        <?php }else{?>
                                            <input type="text" name="state" id="state" class="form-control" >

                                        <?php }?>
                                    </div>

                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?php if(!empty($zip_code)){?>
                                                <label class="sr-only">Zip Postal Code</label>
                                                <input type="text" name="postalcode" id="postalcode" value="{{$zip_code}}" class="form-control">
                                            <?php }else{?>
                                                <input type="text" name="postalcode" id="postalcode" class="form-control">

                                            <?php }?>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                        <input type="button" name="" value="submit">
                                        </div>
                                    </div>
                                </div>{{-- Row End--}}
                               </from>
                            </div>

                        </div>
                    </div>


                </div>
            </section>






<script type="text/javascript">
    $(function(){

       
    });
</script>