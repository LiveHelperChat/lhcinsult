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

        $msg = erLhcoreClassModelmsg::fetch($this->args['id'], false);

        if (!($msg instanceof erLhcoreClassModelmsg)) {
            sleep(3);
            $msg = erLhcoreClassModelmsg::fetch($this->args['id'], false);

            if (!($msg instanceof erLhcoreClassModelmsg)) {
                return;
            }
        }

        // Bye in deeppavlov is an insult...
        if (str_replace('.','',mb_strtolower($msg->msg)) == 'bye') {
            return;
        }

        for ($i = 1; $i <= 3; $i++) {
            $insultData = self::isInsult($msg->msg, $data, $msg->chat_id, $i);
            if ($insultData['insult'] == true || ($insultData['insult'] === false && $insultData['error'] === false)) {
                break;
            } else {
                sleep(1);
            }
        }

        if ($insultData['insult'] == true) {

            // Insult API can take long time sometimes we need to be sure we are still connected to DB
            $db->reconnect();

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

            // Update chat in transaction manner
            $db->beginTransaction();

            $chat = erLhcoreClassModelChat::fetchAndLock($msg->chat_id,false);

            $chat->operation_admin .= "lhinst.updateMessageRowAdmin({$msg->chat_id},{$msg->id});";

            // Update main chat interface if chat is closed.
            if ($closeChat == true) {
                $chat->operation_admin .= "lhinst.updateVoteStatus(" . $msg->chat_id . ");";
            }

            $chat->operation .= "lhinst.updateMessageRow({$msg->id});";

            $chat->updateThis(['update' => ['operation','operation_admin']]);

            $db->commit();

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

    public static function isInsult($message, $options, $chatId = 0, $attempt = 1) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $options['host']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 7);
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
            if ($attempt == 3) {
                erLhcoreClassLog::write(
                    'LHC_INSULT_ERROR: ' . $content . curl_error($ch) . $message ,
                    ezcLog::SUCCESS_AUDIT,
                    array(
                        'source' => 'LHCINSULT',
                        'category' => 'lhcinsult',
                        'line' => __LINE__,
                        'file' => __FILE__,
                        'object_id' => $chatId
                    )
                );
            }

            return ['insult' => false, 'error' => true];
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpcode == 200) {
            $contentJSON = json_decode($content, true);

            $responseAttr = erLhcoreClassGenericBotActionRestapi::extractAttribute($contentJSON,$options['attr_loc']);

            if ($responseAttr['found'] === true && $responseAttr['value'] == 'Insult') {
                return ['insult' => true, 'error' => false];
            }
        } else {
            if ($attempt == 3) {
                erLhcoreClassLog::write(
                    'LHC_INSULT_ERROR_HTTP: ' . '[' . $httpcode . ']' .$content . curl_error($ch) ,
                    ezcLog::SUCCESS_AUDIT,
                    array(
                        'source' => 'LHCINSULT',
                        'category' => 'lhcinsult',
                        'line' => __LINE__,
                        'file' => __FILE__,
                        'object_id' => $chatId
                    )
                );
            }
            return ['insult' => false, 'error' => true];
        }

        return ['insult' => false, 'error' => false];
    }

}

?>