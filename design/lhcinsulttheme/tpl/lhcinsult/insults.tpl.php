<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/lhcinsult','Insults');?></h1>

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
                <td nowrap="nowrap"><a class="material-icons" onclick="lhc.previewChat(<?php echo $item->chat_id?>)">info_outline</a>
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
                        <a class="btn btn-danger btn-xs csfr-required" onclick="return confirm('<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('kernel/messages','Are you sure?');?>')" href="<?php echo erLhcoreClassDesign::baseurl('lhcinsult/delete')?>/<?php echo $item->id?>" ><i class="material-icons mr-0">&#xE872;</i></a>
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