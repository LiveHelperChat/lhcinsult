<?php

/**
 * Example of worker usage
 * 
 * */
class erLhcoreClassLhcinsultWorker {
     
    public function perform()
    {
        $db = ezcDbInstance::get();
        $db->reconnect(); // Because it timeouts automatically, this calls to reconnect to database, this is implemented in 2.52v

        $lhcinsultOptions = erLhcoreClassChat::getSession()->load( 'erLhcoreClassModelChatConfig', 'lhcinsult_options' );
        $data = (array)$lhcinsultOptions->data;

        if (!isset($data['enabled']) || $data['enabled'] == 0) {
            return;
        }

        $msg = erLhcoreClassModelmsg::fetch($this->args['id']);

        if (!($msg instanceof erLhcoreClassModelmsg)) {
            sleep(1);
            $msg = erLhcoreClassModelmsg::fetch($this->args['id']);

            if (!($msg instanceof erLhcoreClassModelmsg)) {
                return;
            }
        }

        if (self::isInsult($msg->msg, $data)) {

            $presentInsults = erLhcoreClassModelLhcinsult::getCount(['filter' => ['chat_id' => $msg->chat_id]]);

            $insult = new erLhcoreClassModelLhcinsult();
            $insult->chat_id = $msg->chat_id;
            $insult->msg = $msg->msg;
            $insult->msg_id = $msg->id;
            $insult->saveThis();

            $closeChat = false;
            $appendOpMessage = '';

            if ($presentInsults == 0) {
                $msgText = "Message not sent.\n Our system has detected potentially offensive language. Please rephrase your query and try again.";
            } elseif ($presentInsults == 1) {
                $msgText = "Message not sent.\n Our system has detected potentially offensive language. Please rephrase your query and try again.\n[b]⚠ This chat will be terminated at the next occurrence.[/b]";
            } elseif ($presentInsults >= 2) {
                $closeChat = true;
                $appendOpMessage = ' Chat terminated due to repeated insults.';
                $msgText = "[b]⛔ This chat has been terminated.[/b]\nOur system has detected potentially offensive language.\n\nYou must not use any language that could be considered offensive, racist, obscene or otherwise inappropriate while using our Live Chat service.\nWe appreciate your understanding.";
            }

            $msg->meta_msg = json_encode([
                'content' => [
                    'text_conditional' => [
                        'intro_us' => $msgText,
                        'full_us' => '',
                        'readmore_us' => '',
                        'intro_op' => 'This message is insulting.' . $appendOpMessage,
                        'full_op' => $msg->msg . ' [button_action=not_insult]Not offensive[/button_action]',
                        'readmore_op' => 'See a message',
                    ]
                ]
            ]);
            $msg->msg = '';
            $msg->saveThis();

            $chat = erLhcoreClassModelChat::fetch($msg->chat_id);

            $chat->operation_admin .= "lhinst.updateMessageRowAdmin({$msg->chat_id},{$msg->id});";

            // Update main chat interface if chat is closed.
            if ($closeChat == true) {
                $chat->operation_admin .= "lhinst.updateVoteStatus(" . $msg->chat_id . ");";
            }

            $chat->operation .= "lhinst.updateMessageRow({$msg->id});";

            $chat->updateThis(['update' => ['operation','operation_admin']]);

            if ($closeChat === true) {
                // Close chat default way
                erLhcoreClassChatHelper::closeChat(array(
                    'chat' => & $chat,
                    'bot' => true
                ));
            }

            erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.message_updated', array('msg' => & $msg, 'chat' => & $chat));
        }
    }

    public static function isInsult($message, $options) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $options['host']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $headers = array('Accept: application/json');

        curl_setopt($ch, CURLOPT_POST,1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([$options['query_attr'] => [$message]]));
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Expect:';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $content = curl_exec($ch);

        if (curl_errno($ch))
        {
            return false;
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpcode == 200) {
            $contentJSON = json_decode($content, true);

            $responseAttr = erLhcoreClassGenericBotActionRestapi::extractAttribute($contentJSON,$options['attr_loc']);

            if ($responseAttr['found'] === true && $responseAttr['value'] == 'Insult') {
                return true;
            }
        }

        return false;
    }

}

?>