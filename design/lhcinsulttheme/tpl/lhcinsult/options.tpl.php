<h1 class="attr-header"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler','Insult detection');?></h1>

<form action="" method="post" ng-non-bindable>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <?php if (isset($updated) && $updated == 'done') : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/onlineusers','Settings updated'); ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
    <?php endif; ?>

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li role="presentation" class="nav-item"><a href="#messages" class="nav-link<?php if ($tab == '') : ?> active<?php endif;?>" aria-controls="messages" role="tab" data-bs-toggle="tab"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler','Messages');?></a></li>
        <li role="presentation" class="nav-item"><a href="#images" class="nav-link<?php if ($tab == 'images') : ?> active<?php endif;?>" aria-controls="images" role="tab" data-bs-toggle="tab"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler','Images');?></a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane <?php if ($tab == '') : ?>active<?php endif;?>" id="messages">
            <div class="form-group">
                <label><input type="checkbox" value="on" name="enabled" <?php isset($lhci_options['enabled']) && ($lhci_options['enabled'] == true) ? print 'checked="checked"' : ''?> /> Enabled</label><br/>
            </div>

            <div class="form-group">
                <label>Query attribute</label>
                <input type="text" class="form-control form-control-sm" placeholder="x" name="query_attr" value="<?php isset($lhci_options['query_attr']) ? print htmlspecialchars($lhci_options['query_attr']) : print 'x'?>" />
            </div>

            <div class="form-group">
                <label>Attribute location</label>
                <input type="text" class="form-control form-control-sm" placeholder="0:0" name="attr_loc" value="<?php isset($lhci_options['attr_loc']) ? print htmlspecialchars($lhci_options['attr_loc']) : print '0:0'?>" />
            </div>

            <div class="form-group">
                <label>Host</label>
                <input type="text" class="form-control form-control-sm" name="host" value="<?php isset($lhci_options['host']) ? print htmlspecialchars($lhci_options['host']) : print 'http://localhost:5000/model'?>" />
            </div>

            <div class="form-group">
                <label>Declare rules for messages matching which is considered not an insults.</label>
                <textarea name="safe_comb" rows="5" placeholder="delete,close && my && account [params max_words=5]&#10;new rule in new line" class="form-control form-control-sm"><?php isset($lhci_options['safe_comb']) ? print htmlspecialchars($lhci_options['safe_comb']) : ''?></textarea>
            </div>

        </div>
        <div role="tabpanel" class="tab-pane <?php if ($tab == 'images') : ?>active<?php endif;?>" id="images">

            <p><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler','Adult images detection requires');?> <a href="https://github.com/notAI-tech/NudeNet">https://github.com/notAI-tech/NudeNet</a> Rest API &quot;docker run -it -p8080:8080 notaitech/nudenet:classifier&quot;</p>

            <div class="form-group">
                <label><input type="checkbox" value="on" name="enabled_img" <?php isset($lhci_options['enabled_img']) && ($lhci_options['enabled_img'] == true) ? print 'checked="checked"' : ''?> /> Enabled</label><br/>
            </div>

            <div class="form-group">
                <label>Host</label>
                <input type="text" class="form-control form-control-sm" name="host_img" value="<?php isset($lhci_options['host_img']) ? print htmlspecialchars($lhci_options['host_img']) : print 'http://localhost:8080/sync'?>" />
            </div>

        </div>
    </div>

    <hr/>

    <h4><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler','Failover');?></h4>

    <div class="form-group">
        <label><input type="checkbox" value="on" name="auto_enable" <?php isset($lhci_options['auto_enable']) && ($lhci_options['auto_enable'] == true) ? print 'checked="checked"' : ''?> /> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler','Enable services automatically.');?></label><br/>
    </div>

    <div class="form-group">
        <label><input type="checkbox" value="on" name="disable_in_msg" <?php isset($lhci_options['disable_in_msg']) && ($lhci_options['disable_in_msg'] == true) ? print 'checked="checked"' : ''?> /> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler','Disable messages detection. Systems set this automatically if cronjob detects that service is down for whatever reason.');?></label><br/>
    </div>

    <div class="form-group">
        <label><input type="checkbox" value="on" name="disable_in_img" <?php isset($lhci_options['disable_in_img']) && ($lhci_options['disable_in_img'] == true) ? print 'checked="checked"' : ''?> /> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler','Disable images detection. Systems set this automatically if cronjob detects that service is down for whatever reason.');?></label><br/>
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler','Report about unavailable services to these e-mails. Separated by comma');?></label>
        <input type="text" class="form-control" name="report_email_in" value="<?php isset($lhci_options['report_email_in']) ? print htmlspecialchars($lhci_options['report_email_in']) : ''?>" />
    </div>

    <?php if (isset($lhci_options['fail_reason']) && !empty($lhci_options['fail_reason'])) : ?>
        <p><?php echo htmlspecialchars($lhci_options['fail_reason'])?></p>
    <?php endif; ?>

    <input type="submit" class="btn btn-secondary" name="StoreOptions" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Save'); ?>" />




</form>
