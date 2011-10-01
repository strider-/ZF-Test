<?php

class Application_Model_GuestbookMapper
{
    protected $dbTable;

    public function setDbTable($dbTable) {
        if(is_string($dbTable)) {
            $dbTable = new $dbTable();
        }

        if(!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided!');
        }
        $this->dbTable = $dbTable;
        return $this;
    }

    public function getDbTable() {
        if($this->dbTable === null) {
            $this->setDbTable('Application_Model_DbTable_Guestbook');
        }
        return $this->dbTable;
    }

    public function save(Application_Model_Guestbook $guestbook) {
        $data = array(
            'email' => $guestbook->getEmail(),
            'comment' => $guestbook->getComment(),
            'created' => date('Y-m-d H:i:s'),
        );

        if(($id = $guestbook->getId()) === null) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function find($id, Application_Model_Guestbook $guestbook) {
        $result = $this->getDbTable()->find($id);
        if(count($result) == 0) {
            return;
        }
        $row = $result->current();
        $guestbook->setId($row->id)
                  ->setEmail($row->email)
                  ->setComment($row->comment)
                  ->setCreated($row->created);
    }

    public function fetchAll() {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        foreach($resultSet as $row) {
            $entry = new Application_Model_Guestbook();
            $entry->setId($row->id)
                  ->setEmail($row->email)
                  ->setComment($row->comment)
                  ->setCreated($row->created);
            $entries[] = $entry;
        }
        return $entries;
    }
}

