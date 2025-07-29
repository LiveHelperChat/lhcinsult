<?php
#[\AllowDynamicProperties]
class erLhcoreClassExtensionLhcinsult
{
    public function __construct()
    {}

    public function run()
    {
        $this->registerAutoload();
    }

    public function registerAutoload()
    {

        $dispatcher = erLhcoreClassChatEventDispatcher::getInstance();

        $dispatcher->listen('chat.addmsguser', array( $this, 'messageReceived' ));
        $dispatcher->listen('chat.abstract_click', array( $this, 'notInsult' ));
        $dispatcher->listen('chat.archived', array( $this, 'archiveChat' ));
        $dispatcher->listen('chat.chat_started', array( $this, 'chatStarted' ));

        spl_autoload_register(array(
            $this,
            'autoload'
        ), true, false);
    }

    public function chatStarted($params)
    {
        if (isset($params['msg']) && is_object($params['msg']) && $params['msg']->msg != '') {
            $this->messageReceived($params);
        }
    }

    public function messageReceived($params)
    {
        if (class_exists('erLhcoreClassExtensionLhcphpresque') && $params['chat']->status !== erLhcoreClassModelChat::STATUS_BOT_CHAT && $params['msg']->msg != '') {
            if (!isset($params['file']) && (!isset($params['files']) || empty($params['files']))) {
                erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcphpresque')->enqueue('lhc_insult', 'erLhcoreClassLhcinsultWorker', array('id' => $params['msg']->id));
            } else {
                $lhcinsultOptions = erLhcoreClassModelChatConfig::fetch('lhcinsult_options');
                if ($lhcinsultOptions instanceof erLhcoreClassModelChatConfig) {
                    $data = (array)$lhcinsultOptions->data;
                    if (isset($data['enabled_img']) && $data['enabled_img'] == 1 && (!isset($data['disable_in_img']) || $data['disable_in_img'] == false)) {
                        erLhcoreClassLhcinsultWorker::isNudity($params, $data['host_img']);
                    }
                }
            }
        }
    }

    public function archiveChat($params) {
        $q = ezcDbInstance::get()->createDeleteQuery();

        // Delete insult messages
        $q->deleteFrom( 'lhc_insult' )->where( $q->expr->eq( 'chat_id', $params['chat']->id ) );
        $stmt = $q->prepare();
        $stmt->execute();
    }

    public function notInsult($params) {

        $metaMessageArray = $params['msg']->meta_msg_array;

        if ($params['msg']->msg == '' && isset($metaMessageArray['content']['text_conditional']['full_op'])) {

            // Restore original message
            if (isset($params['msg']->meta_msg_array['content']['text_conditional']['full_op'])) {
                $params['msg']->msg = preg_replace('/\[button_action=not_insult\](.*?)\[\/button_action\]/is','', $params['msg']->meta_msg_array['content']['text_conditional']['full_op']);
            }

            unset($metaMessageArray['content']['text_conditional']);
            $params['msg']->meta_msg_array = $metaMessageArray;
            $params['msg']->meta_msg = json_encode($metaMessageArray);
            $params['msg']->updateThis(['update' => ['msg','meta_msg']]);

            // Remove insult message
            $insultMessage = erLhcoreClassModelLhcinsult::findOne(['filter' => ['not_insult' => 0, 'msg_id' => $params['msg']->id]]);

            if ($insultMessage instanceof erLhcoreClassModelLhcinsult) {

                $insultMessage->not_insult = 1;
                $insultMessage->updateThis(['update' => ['not_insult']]);

                if (empty($params['msg']->msg)) {
                    $params['msg']->msg = $insultMessage->msg;
                    $params['msg']->updateThis(['update' => ['msg']]);
                }
            }
        }

        // This we need for frontend visitor to update UI
        $params['chat']->operation .= "lhinst.updateMessageRow({$params['msg']->id});\n";
        $params['chat']->updateThis(['update' => ['operation']]);

        // For the admin we render new message instantly.
        $tpl = erLhcoreClassTemplate::getInstance ( 'lhchat/syncadmin.tpl.php' );
        $tpl->set ( 'messages', array (
            $params['msg']->getState()
        ) );
        $tpl->set ( 'chat', $params['chat'] );

        erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.message_updated', array('msg' => & $params['msg'], 'chat' => & $params['chat']));

        return array(
            'status' => erLhcoreClassChatEventDispatcher::STOP_WORKFLOW,
            'response' => ['chat_id' => $params['msg']->chat_id, 'replace_id' => '#msg-' . $params['msg']->id, 'html' => trim($tpl->fetch())]
        );
    }

    public function autoload($className)
    {
        $classesArray = array(
            'erLhcoreClassLhcinsultWorker' => 'extension/lhcinsult/classes/lhqueuelhcinsultworker.php',
            'erLhcoreClassModelLhcinsult'  => 'extension/lhcinsult/classes/erlhcoreclassmodelhcinsult.php'
        );

        if (key_exists($className, $classesArray)) {
            include_once $classesArray[$className];
        }
    }

    public static function getSession() {
        if (! isset ( self::$persistentSession )) {
            self::$persistentSession = new ezcPersistentSession ( ezcDbInstance::get (), new ezcPersistentCodeManager ( './extension/lhcinsult/pos' ) );
        }
        return self::$persistentSession;
    }

    private static $persistentSession;

    public function __get($var)
    {
        switch ($var) {

            default:
                ;
                break;
        }
    }
}


