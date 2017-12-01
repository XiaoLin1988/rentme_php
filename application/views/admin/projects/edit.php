<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<div class="content-wrapper">
    <section class="content-header">
        <?php echo $pagetitle; ?>
        <?php echo $breadcrumb; ?>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo lang('projects_edit'); ?></h3>
                    </div>
                    <div class="box-body">
                        <?php echo $message;?>

                        <?php echo form_open(uri_string(), array('class' => 'form-horizontal', 'id' => 'form-edit_project')); ?>
                        <div class="form-group">
                            <?php echo lang('projects_name', 'name', array('class' => 'col-sm-2 control-label')); ?>
                            <div class="col-sm-10">
                                <?php echo form_input($name);?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo lang('projects_description', 'description', array('class' => 'col-sm-2 control-label')); ?>
                            <div class="col-sm-10">
                                <?php echo form_textarea($description);?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo lang('projects_timeframe', 'timeframe', array('class' => 'col-sm-2 control-label')); ?>
                            <div class="col-sm-10">
                                <?php echo form_input($timeframe);?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo lang('projects_consumer_score', 'consumer_score', array('class' => 'col-sm-2 control-label')); ?>
                            <div class="col-sm-10">
                                <?php echo form_input($consumer_score);?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo lang('projects_consumer_review', 'consumer_review', array('class' => 'col-sm-2 control-label')); ?>
                            <div class="col-sm-10">
                                <?php echo form_textarea($consumer_review);?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo lang('projects_talent_score', 'talent_score', array('class' => 'col-sm-2 control-label')); ?>
                            <div class="col-sm-10">
                                <?php echo form_input($talent_score);?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo lang('projects_talent_review', 'talent_review', array('class' => 'col-sm-2 control-label')); ?>
                            <div class="col-sm-10">
                                <?php echo form_textarea($talent_review);?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo lang('projects_status');?></label>
                            <div class="col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="status" value="" <?php echo $status['value']==1 ? 'checked' : ''; ?>>
                                        <?php echo htmlspecialchars('Completed', ENT_QUOTES, 'UTF-8'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <?php echo form_hidden('id', $project->id);?>
                                <div class="btn-group">
                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_submit'))); ?>
                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                    <?php echo anchor('admin/projects', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close();?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
