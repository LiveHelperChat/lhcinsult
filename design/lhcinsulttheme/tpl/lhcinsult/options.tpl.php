<h1 class="attr-header">Insult detection</h1>

<form action="" method="post">

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <?php if (isset($updated) && $updated == 'done') : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/onlineusers','Settings updated'); ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
    <?php endif; ?>

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

    <input type="submit" class="btn btn-secondary" name="StoreOptions" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Save'); ?>" />

</form>
