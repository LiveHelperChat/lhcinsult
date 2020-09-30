<?php

class erLhcoreClassModelLhcinsult
{
	use erLhcoreClassDBTrait;

	public static $dbTable = 'lhc_insult';

	public static $dbTableId = 'id';

	public static $dbSessionHandler = 'erLhcoreClassExtensionLhcinsult::getSession';

	public static $dbSortOrder = 'DESC';

    public function getState()
    {
        return array(
            'id' => $this->id,
        	'chat_id' => $this->chat_id,
        	'msg' => $this->msg,
        	'msg_id' => $this->msg_id,
        	'not_insult' => $this->not_insult,
        	'terminated' => $this->terminated,
        	'ctime' => $this->ctime,
        );
    }

    public function beforeSave()
    {
        if ($this->ctime == 0) {
            $this->ctime = time();
        }
    }

    public function __toString()
    {
    	return $this->msg;
    }

    public function __get($var)
    {
        switch ($var) {

            case 'chat':
                $this->chat = erLhcoreClassModelChat::fetch($this->chat_id);
                return $this->chat;

            case 'chat_nick':
                $this->chat_nick = 'Visitor';
                if ($this->chat instanceof erLhcoreClassModelChat) {
                    $this->chat_nick = $this->chat->nick;
                }
                return $this->chat_nick;
                
            case 'ctime_front':
                $this->ctime_front = '';
                if ($this->ctime > 0) {
                    $this->ctime_front = date('Ymd') == date('Ymd', $this->ctime) ? date(erLhcoreClassModule::$dateHourFormat, $this->ctime) : date(erLhcoreClassModule::$dateFormat, $this->ctime);
                }
                return $this->ctime_front;

            default:
                ;
                break;
        }
    }

    public $id = null;

    public $chat_id = null;
    
    public $msg = '';

    public $msg_id = null;

    public $not_insult = 0;

    public $terminated = 0;

    public $ctime = 0;
}

?>