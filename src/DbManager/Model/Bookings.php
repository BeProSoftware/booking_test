<?php

namespace DbManager\Model;


/**
 * Class Bookings
 * @package DbManager\Model
 */
class Bookings
{

    /**
     * @var
     */
    public $id;
    /**
     * @var
     */
    public $username;
    /**
     * @var
     */
    public $reason;
    /**
     * @var
     */
    public $requested_date;
    /**
     * @var
     */
    public $created;

    /**
     * @param $data
     */
    public function exchangeArray($data)
    {
        $this->id        = (isset($data['id']))       ? $data['id']       : null;
        $this->username  = (isset($data['username'])) ? $data['username'] : null;
        $this->reason  = (isset($data['reason'])) ? $data['reason'] : null;
        $this->requested_date    = (isset($data['requested_date']))   ? $data['requested_date']   : null;
        $this->created    = (isset($data['created']))   ? $data['created']   : null;
    }

}