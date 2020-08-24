<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler','Insult detection');?></h1>
<ul>
    <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhlhcinsult','configure')) : ?>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('lhcinsult/options')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler','Options');?></a></li>
    <?php endif; ?>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('lhcinsult/insults')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler','Insults');?></a></li>
</ul>
