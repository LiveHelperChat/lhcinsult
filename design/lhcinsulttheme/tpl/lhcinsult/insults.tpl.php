<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/lhcinsult','Insults');?></h1>

<form class="mb-2" action="<?php echo erLhcoreClassDesign::baseurl('lhcinsult/insults')?>" ng-non-bindable>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-3">
                    <label><input type="checkbox" name="terminated" value="on" <?php if ($input->terminated == true) : ?>checked="checked"<?php endif; ?> /> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/lhcinsult','Show only blocking records');?></label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control form-control-sm" name="chat_id" value="<?php echo htmlspecialchars($input->chat_id)?>" placeholder="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/lhcinsult','Chat ID');?>"/>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Date range from');?></label>
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" class="form-control form-control-sm" name="timefrom" id="id_timefrom" placeholder="E.g <?php echo date('Y-m-d',time()-7*24*3600)?>" value="<?php echo htmlspecialchars($input->timefrom)?>" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Hour and minute from');?></label>
                <div class="row">
                    <div class="col-md-6">
                        <select name="timefrom_hours" class="form-control form-control-sm">
                            <option value="">Select hour</option>
                            <?php for ($i = 0; $i <= 23; $i++) : ?>
                                <option value="<?php echo $i?>" <?php if (isset($input->timefrom_hours) && $input->timefrom_hours === $i) : ?>selected="selected"<?php endif;?>><?php echo str_pad($i,2, '0', STR_PAD_LEFT);?> h.</option>
                            <?php endfor;?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select name="timefrom_minutes" class="form-control form-control-sm">
                            <option value="">Select minute</option>
                            <?php for ($i = 0; $i <= 59; $i++) : ?>
                                <option value="<?php echo $i?>" <?php if (isset($input->timefrom_minutes) && $input->timefrom_minutes === $i) : ?>selected="selected"<?php endif;?>><?php echo str_pad($i,2, '0', STR_PAD_LEFT);?> m.</option>
                            <?php endfor;?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Date range to');?></label>
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" class="form-control form-control-sm" name="timeto" id="id_timeto" placeholder="E.g <?php echo date('Y-m-d')?>" value="<?php echo htmlspecialchars($input->timeto)?>" />
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Hour and minute to');?></label>
                <div class="row">
                    <div class="col-md-6">
                        <select name="timeto_hours" class="form-control form-control-sm">
                            <option value="">Select hour</option>
                            <?php for ($i = 0; $i <= 23; $i++) : ?>
                                <option value="<?php echo $i?>" <?php if (isset($input->timeto_hours) && $input->timeto_hours === $i) : ?>selected="selected"<?php endif;?>><?php echo str_pad($i,2, '0', STR_PAD_LEFT);?> h.</option>
                            <?php endfor;?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select name="timeto_minutes" class="form-control form-control-sm">
                            <option value="">Select minute</option>
                            <?php for ($i = 0; $i <= 59; $i++) : ?>
                                <option value="<?php echo $i?>" <?php if (isset($input->timeto_minutes) && $input->timeto_minutes === $i) : ?>selected="selected"<?php endif;?>><?php echo str_pad($i,2, '0', STR_PAD_LEFT);?> m.</option>
                            <?php endfor;?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="submit" name="doSearch" class="btn btn-secondary btn-sm" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Search');?>" />

    <script>
        $(function() {
            $('#id_timefrom,#id_timeto').fdatepicker({
                format: 'yyyy-mm-dd'
            });
        });
    </script>

</form>


<?php if (isset($items)) : ?>
    <table cellpadding="0" cellspacing="0" class="table table-sm" width="100%">
        <thead>
        <tr>
            <th width="1%">ID</th>
            <th width="1%" nowrap="nowrap"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/lhcinsult','Chat ID');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/lhcinsult','Message');?></th>
            <th width="1%"></th>
            <th width="1%"></th>
        </tr>
        </thead>
        <?php foreach ($items as $item) : ?>
            <tr>
                <td><?php echo $item->id?></td>
                <td nowrap="nowrap">
                    <?php echo $item->ctime_front?> <?php if ($item->terminated == 1) : ?><i class="material-icons text-danger">remove_circle</i><?php endif; ?>
                    <a class="material-icons" onclick="lhc.previewChat(<?php echo $item->chat_id?>)">info_outline</a>
                    <a class="action-image material-icons" data-title="<?php echo htmlspecialchars($item->chat_nick,ENT_QUOTES);?>" onclick="lhinst.startChatNewWindow('<?php echo $item->chat_id;?>',$(this).attr('data-title'))" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/pendingchats','Open in a new window');?>">open_in_new</a>
                    <?php echo $item->chat_id?></td>
                <td>
                    <textarea rows="1" class="form-control fs11"><?php echo htmlspecialchars($item->msg)?></textarea>
                </td>
                <td>
                    <?php if ($item->not_insult == 1) : ?>
                        <a href="<?php echo erLhcoreClassDesign::baseurl('lhcinsult/insults')?>/(action)/insult/(id)/<?php echo $item->id?>" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/lhcinsult','Click to change');?>" class="btn btn-sm btn-secondary"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/lhcinsult','Not insulting');?></a>
                    <?php else : ?>
                        <a href="<?php echo erLhcoreClassDesign::baseurl('lhcinsult/insults')?>/(action)/ninsult/(id)/<?php echo $item->id?>" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/lhcinsult','Click to change');?>" class="btn btn-sm btn-secondary"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/lhcinsult','Insult');?></a>
                    <?php endif; ?>
                </td>
                <td nowrap>
                    <div class="btn-group" role="group" aria-label="..." style="width:60px;">
                        <a class="btn btn-danger btn-xs csfr-required" onclick="return confirm('<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('kernel/messages','Are you sure?');?>')" href="<?php echo erLhcoreClassDesign::baseurl('lhcinsult/delete')?>/<?php echo $item->id?>" ><i class="material-icons me-0">&#xE872;</i></a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php')); ?>

    <?php if (isset($pages)) : ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
    <?php endif;?>

<?php endif;?>