<?php

header ( 'content-type: application/json; charset=utf-8' );

try {
    $msg = erLhcoreClassModelmsg::fetch($Params['user_parameters']['id']);
    $chat = erLhcoreClassModelChat::fetch($msg->chat_id);

    if ( erLhcoreClassChat::hasAccessToRead($chat) )
    {
        $array = array('error' => false);

        if ($msg->user_id != 0) {
            throw new Exception("You can't mark non user message as insulting!");
        }

        erLhcoreClassLhcinsultWorker::markAsInsult($msg, ['op' => $currentUser->getUserData()]);

        echo json_encode($array);
    }

} catch (Exception $e) {
    echo json_encode(array('error' => true, 'msg' => $e->getMessage()));
}

exit;

?>