<h1 class="attr-header"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler','Insult detection');?></h1>

<form action="" method="post">

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <?php if (isset($updated) && $updated == 'done') : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/onlineusers','Settings updated'); ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
    <?php endif; ?>

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li role="presentation" class="nav-item"><a href="#messages" class="nav-link<?php if ($tab == '') : ?> active<?php endif;?>" aria-controls="messages" role="tab" data-toggle="tab"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler','Messages');?></a></li>
        <li role="presentation" class="nav-item"><a href="#images" class="nav-link<?php if ($tab == 'images') : ?> active<?php endif;?>" aria-controls="images" role="tab" data-toggle="tab"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler','Images');?></a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane <?php if ($tab == '') : ?>active<?php endif;?>" id="messages">
            <div class="form-group">
                <label><input type="checkbox" value="on" name="enabled" <?php isset($lhci_options['enabled']) && ($lhci_options['enabled'] == true) ? print 'checked="checked"' : ''?> /> Enabled</label><br/>
            </div>

            <div class="form-group">
                <label>Query attribute</label>
                <input type="text" class="form-control form-control-sm" name="query_attr" value="<?php isset($lhci_options['query_attr']) ? print htmlspecialchars($lhci_options['query_attr']) : print 'x'?>" />
            </div>

            <div class="form-group">
                <label>Attribute location</label>
                <input type="text" class="form-control form-control-sm" name="attr_loc" value="<?php isset($lhci_options['attr_loc']) ? print htmlspecialchars($lhci_options['attr_loc']) : print '0:0:5'?>" />
            </div>

            <div class="form-group">
                <label>Host</label>
                <input type="text" class="form-control form-control-sm" name="host" value="<?php isset($lhci_options['host']) ? print htmlspecialchars($lhci_options['host']) : print 'http://localhost:5000/model'?>" />
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

    <input type="submit" class="btn btn-secondary" name="StoreOptions" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Save'); ?>" />

</form>
