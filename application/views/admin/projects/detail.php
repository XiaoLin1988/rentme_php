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
            <div class="col-md-10">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo htmlspecialchars($project_info->name, ENT_QUOTES, 'UTF-8'); ?></h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-hover">
                            <tbody>
                                <!--
                                <tr>
                                    <th><?php echo lang('projects_name'); ?></th>
                                    <td><?php echo htmlspecialchars($project_info->name, ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                                -->
                                <tr>
                                    <th><?php echo lang('projects_description'); ?></th>
                                    <td><?php echo htmlspecialchars($project_info->description, ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo lang('projects_timeframe'); ?></th>
                                    <td><?php echo htmlspecialchars($project_info->timeframe, ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo lang('projects_consumer'); ?></th>
                                    <td><?php echo htmlspecialchars($project_info->consumer, ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo lang('projects_consumer_score'); ?></th>
                                    <td><?php echo htmlspecialchars($project_info->consumer_score, ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo lang('projects_consumer_review'); ?></th>
                                    <td><?php echo htmlspecialchars($project_info->consumer_review, ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo lang('projects_talent'); ?></th>
                                    <td><?php echo htmlspecialchars($project_info->talent, ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo lang('projects_talent_score'); ?></th>
                                    <td><?php echo htmlspecialchars($project_info->talent_score, ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo lang('projects_status'); ?></th>
                                    <td><?php echo ($project_info->status) ? '<span class="label label-success">'.lang('projects_completed').'</span>' : '<span class="label label-default">'.lang('projects_inprogress').'</span>'; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
