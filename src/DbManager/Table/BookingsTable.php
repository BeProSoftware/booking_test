<?php

namespace DbManager\Table;

use DbManager\Table\Helper\DbHydratorFactory;
use DbManager\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\ServiceManager;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;

use Zend\Stdlib\RequestInterface;
use Zend\Stdlib\ResponseInterface;
use Zend\Validator\EmailAddress;


use RuntimeException;

/**
 * Class UsersTable
 * @package DbManager\Table
 */
class BookingsTable extends DbHydratorFactory
{

    /**
     *   user prefix to validate into account, this can be replaced with another prefix, no problem
     */
    const USERNAME_PREFIX      = "user";
    /**
     *
     */
    const CHECK_AUTH_DEVICE_ID = null;

    /**
     * @var array
     */
    private $insert_date;

    /**
     * @var array
     */
    private $update_date;

    /**
     * @var string
     */
    private $id = "id";

    /**
     * @param $string
     * @return mixed
     */
    private function formatRemoveSymbols($string) {
        $contain  = ['.','-','_','~','!','@','#','$','%','^','&','*','(',')',';',':',"'",'"','{','}','[',']','+',"-"];
        $removed  = str_replace($contain,'',$string);
        return $removed;
    }

    /**
     * @param null $len
     * @param null $hasInt
     * @return string
     */
    private function getNewUsername($len = null , $hasInt = null )
    {

        if ( is_null($len) ){
            $len = 6;
        }

        $prefix    = self::USERNAME_PREFIX;
        $new_array = [];
        $string    = "ABCDEFGHIJKLMNOPQRSTUVXZ";
        $int       = "0123456789";
        if ( !is_null($hasInt) ) {
            $string = $string . $int;
        }
        for ($i=0; $i<=$len; $i++) :
            $new_array[$i] = $string[rand()%strlen($string)];
        endfor;
        shuffle($new_array);
        return $prefix.join('',$new_array);

    }

    /**
     * UsersTable constructor.
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->primary_id   = 'id';
        $this->insert_date  = ['bookings_insert_date'=>$this->getCurrentDate()];
        $this->update_date  = ['bookings_update_date'=>$this->getCurrentDate()];
    }


    /**
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /**
     * @param array $where
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchWhere(Array $where)
    {
        $rowSet = $this->tableGateway->select($where);
        return $rowSet;
    }

    /**
     * @param $id
     * @return object
     */
    public function getById($id)
    {
        $id     = (int) $id;
        return $this->tableGateway->select(array('id' => $id)); 
	}
	
	
    /**
     * @param $username
     * @return null|object
     */
    public function getRowByUsername($username)
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(
            ['a'=> $this->table ]
        );
     
        $select->where(function(Where $where) use ($username) {
            $where->equalTo('a.username',$username);
        });

        $prepare    = $sql->prepareStatementForSqlObject($select);
        $res        = $prepare->execute();

        if($res->count() <= 0){
            return null;
        } else {
            $current    = $res->current();
            return $current;
        }

    }

    /**
     * @param $type
     * @param $val
     * @return null|object|\Zend\Db\ResultSet\HydratingResultSet
     */
    public function getUserBy($type, $val)
    {
        return self::checkUserBy($type,$val);
    }

    /**
     * @param $data
     * @return bool|int|null
     */
    public function save($data)
    {
        @$id = ( isset($data["id"]) ) ? (int)$data["id"] : false; 
        if ($id === false) {
            $checkUsername = $this->getRowByUsername( $data['username']);
            if( !isset($checkUsername) ) {
                #$data = array_merge($data, $this->insert_date);
                $this->tableGateway->insert($data);
                //$this->logs(5,'bookings','insert',$data);
                return $this->tableGateway->lastInsertValue;
            }
            else {
                //$this->logs(5,'bookings',"not-inserted::username-already-exist",$data);
                return null;
            }
        }
        else {
            if (!$this->getById($id)) {
                throw new RuntimeException(sprintf(
                    'Cannot update with identifier %d; does not exist',
                    $id
                ));
            }
            #$data = array_merge($data, $this->update_date);
            $this->tableGateway->update($data, array($this->id => $id));
            //$this->logs(5,'user','update',$data);
            return $id;
        }

    }

    /**
     * @param array $data
     * @return bool
     */
    public function delete(Array $data) {
        if ( $this->tableGateway->delete([ $data[0] => (int) $data[1]]) ){
            return true;
        } else {
            return null;
        }
    }

}