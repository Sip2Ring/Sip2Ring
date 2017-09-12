

<?php $__env->startSection('page_title', 'Buckets'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Page Content -->
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Manage Fields for Bucket <?php echo e($selectedOptions[$fieldType]); ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li class="active">All Bucket Fields</li>
                </ol>

            </div>

        </div>
        <!-- /row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    Please select field Type:
                    <select name="manageFieldsFor" id="manageFieldsFor">
                        <?php $__currentLoopData = $selectedOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionKey => $optionValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $selected = ($optionKey==$fieldType) ? 'selected' : '';?>
                            <option value="/<?php echo e($optionKey); ?>" <?php echo $selected; ?>> <?php echo e($optionValue); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <a href="<?php echo e(url('/add-field').DIRECTORY_SEPARATOR.$fieldType); ?>">
                        <button type="button" class="btn btn-info waves-effect waves-light m-r-10" id="addFiles">Add <?php echo e($selectedOptions[$fieldType]); ?> Option</button>
                    <a>
                    <div class="table-responsive">
                        <table id="example23" class="table table-striped table_grid">
                            <thead>
                            <tr>
                                
                                <th></th>
                                <th>Name</th>
                                <th>Value</th>
                                <?php if($fieldType=='region') echo '<th>Code</th>';?>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $counter = 1;
                                $fieldName = $fieldType.'_name';
                                $fieldValue = $fieldType.'_value';
                                $fieldCode = $fieldType.'_code';
                            ?>
                            <?php $__currentLoopData = $fieldsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fieldData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    
                                    <td><?php echo e($counter); ?></td>
                                    <td><?php echo e($fieldData->$fieldName); ?></td>
                                    <td><?php echo e($fieldData->$fieldValue); ?></td>
                                    <?php if($fieldType=='region') echo '<td>'.(!empty($fieldData->$fieldCode) ? $fieldData->$fieldCode : '-').'</td>';?>
                                    <td class="record_actions">
                                        <a href="<?php echo e(url('/edit-field/'.$fieldType.DIRECTORY_SEPARATOR.$fieldData->id)); ?>" title="edit" class="btn btn-primary-btn"><i class="fa fa-edit"></i></a>
                                        <a href="<?php echo e(url('/delete-field/'.$fieldType.DIRECTORY_SEPARATOR.$fieldData->id)); ?>" title="delete" class="btn btn-danger-btn"
                                           onclick="return confirm('Are you sure you want to delete <?php echo e($fieldData->$fieldName); ?>?')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php $counter++; ?>
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
    <script>
        $(document).ready(function(){
            $(document).on('change','#manageFieldsFor',function(){
                var selectedField = $(this).val();
                var redirectFieldPage = '<?php echo e(url('/manage-bucket-fields/')); ?>'+selectedField;
                window.location.href = redirectFieldPage;
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dataTable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>