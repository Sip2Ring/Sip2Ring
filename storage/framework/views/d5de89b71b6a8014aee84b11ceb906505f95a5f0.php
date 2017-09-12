<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse slimscrollsidebar">
        <div class="user-profile">
            <div class="dropdown user-pro-body">
                <div><img src="<?php echo e(URL::asset('/')); ?>assests/plugins/images/users/admin.png" alt="user-img" class="img-circle"></div>
                <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo ucwords('Admin')?> <span class="caret"></span></a>
                <ul class="dropdown-menu animated flipInY">
                    <?php //if(Auth::user()->role == 1) {?>
                    <li><a href="<?php echo e(url('/update-password')); ?>"><i class="ti-settings"></i> Update Password</a></li> 
                    <?php //} ?> 
                    <li role="separator" class="divider"></li>
                    <li><a href="<?php echo e(url('/logout')); ?>" data-method="post" data-token="<?php echo e(csrf_token()); ?>"><i class="fa fa-power-off"></i> Logout</a></li>
                </ul>
            </div>
        </div>

        <ul class="nav" id="side-menu">

            <li class="sidebar-search hidden-sm hidden-md hidden-lg">
                <!-- input-group -->
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
            <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
            </span> </div>
                <!-- /input-group -->
            </li>
            <li class="nav-small-cap m-t-10 margin-left-10">&nbsp;&nbsp;&nbsp;&nbsp;Main Menu</li>
            <li> <a href="<?php echo e(url('/')); ?>" class="waves-effect"><i class="linea-icon linea-basic fa-fw" data-icon="v"></i> <span class="hide-menu"> Dashboard </span></a></li>
        <!--bucket menu-->


            <li>
                <a href="#" class="waves-effect">
                    <i class="icon-handbag fa-fw" data-icon="v"></i>
                    <span class="hide-menu"> Buckets </span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo e(url('/buckets')); ?>">Buckets</a> </li>
                    <li> <a href="#" data-toggle="modal" data-target="#master_bucket_dialog" target="_blank" onclick="$('#bucket_name').val('')">Add Bucket</a> </li>
                    <li> <a href="<?php echo e(url('/multiple-buckets')); ?>">Multiple Buckets</a> </li>
<!--                    <li> <a href="<?php echo e(url('/list-master-buckets')); ?>">Master Buckets</a> </li>-->
                    <li> <a href="<?php echo e(url('/manage-bucket-fields')); ?>">Manage Bucket Fields</a> </li>
                </ul>
            </li>
            <li>
                <a href="<?php echo e(url('/list-master-buckets')); ?>" class="waves-effect">
                    <i class="linea-icon linea-basic fa-fw" data-icon="v"></i>
                    <span class="hide-menu"> Master Bucket </span>
                </a>
            </li>

            <li>
                <a href="#" class="waves-effect">
                    <i class="fa fa-retweet" data-icon="v"></i>
                    <span class="hide-menu"> Bucket Backup </span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo e(url('/export-buckets')); ?>">Export Backup</a> </li>
                    <li> <a href="<?php echo e(url('/import-buckets')); ?>">Import Buckets</a> </li>
                </ul>
            </li>

            <li>
                <a href="#" class="waves-effect">
                    <i class="linea-icon linea-basic fa-fw" data-icon="v"></i>
                    <span class="hide-menu"> Configurations </span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo e(url('/add-auth')); ?>">Add Configuration</a> </li>
                    <li> <a href="<?php echo e(url('/list-config')); ?>" >View Configurations</a> </li>
                </ul>
            </li>

            <li>
                <a href="#" class="waves-effect">
                    <i class="icon-docs fa-fw" data-icon="v"></i>
                    <span class="hide-menu"> Templates </span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
					<li> <a href="<?php echo e(url('/add-template')); ?>">Add Templates</a> </li>
					<li> <a href="<?php echo e(url('/list-crm-templates')); ?>" >View Templates</a> </li>
					<li> <a href="<?php echo e(url('/export-template')); ?>" >Export Templates</a> </li>
                </ul>
            </li>

            <li>
                <a href="#" class="waves-effect">
                    <i class="linea-icon linea-software fa-fw" data-icon="v"></i>
                    <span class="hide-menu"> Parameters </span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo e(url('/add-bucket-params')); ?>">Add Parameters</a> </li>
                    <li> <a href="<?php echo e(url('/list-bucket-params')); ?>" >View Parameters</a> </li>
                </ul>
            </li>

            <li>
                <a href="<?php echo e(url('/script-backup')); ?>" class="waves-effect">
                    <i class="linea-icon linea-basic fa-fw" data-icon="v"></i>
                    <span class="hide-menu"> Backup </span>
                </a>
            </li>

             <li>
                <a href="#" class="waves-effect">
                    <i class="icon-docs fa-fw" data-icon="v"></i>
                    <span class="hide-menu"> User</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo e(url('/add-users')); ?>">Add User</a> </li>
                    <li> <a href="<?php echo e(url('/list-users')); ?>">View User</a> </li>
                    <li> <a href="<?php echo e(url('/add-user-role')); ?>">Add Role</a> </li>
                    <li> <a href="<?php echo e(url('/list-user-roles')); ?>" >View Roles</a> </li>
					     <li> <a href="<?php echo e(url('/user-log')); ?>" >User Log</a> </li>
                </ul>
            </li>

            <li><a href="<?php echo e(url('/logout')); ?>" class="waves-effect"><i class="icon-logout fa-fw"></i> <span class="hide-menu">Log out</span></a></li>
        </ul>
    </div>
</div>
<!--add bucket option under left sidebar menu-->
