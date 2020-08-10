<?php

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
            erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcphpresque')->enqueue('lhc_insult', 'erLhcoreClassLhcinsultWorker', array('id' => $params['msg']->id));
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

        // If chat is closed just ignore a button.
        if ($params['chat']->status == erLhcoreClassModelChat::STATUS_CLOSED_CHAT) {
            return array(
                'status' => erLhcoreClassChatEventDispatcher::STOP_WORKFLOW
            );
        }

        // Restore original message
        if (isset($params['msg']->meta_msg_array['content']['text_conditional']['full_op'])) {
            $params['msg']->msg = preg_replace('/\[button_action=not_insult\](.*?)\[\/button_action\]/is','', $params['msg']->meta_msg_array['content']['text_conditional']['full_op']);
        }

        $params['msg']->meta_msg = '';
        $params['msg']->updateThis(['update' => ['msg','meta_msg']]);

        // This we need for frontend visitor to update UI
        $params['chat']->operation .= "lhinst.updateMessageRow({$params['msg']->id});\n";
        $params['chat']->updateThis(['update' => ['operation']]);

        // For the admin we render new message instantly.
        $tpl = erLhcoreClassTemplate::getInstance ( 'lhchat/syncadmin.tpl.php' );
        $tpl->set ( 'messages', array (
            $params['msg']->getState()
        ) );
        $tpl->set ( 'chat', $params['chat'] );

        // Remove insult message
        $insultMessage = erLhcoreClassModelLhcinsult::findOne(['filter' => ['msg_id' => $params['msg']->id]]);
        $insultMessage->removeThis();

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


