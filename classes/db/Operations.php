<?php

include_once dirname(__FILE__) . '/DBConnect.php';
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

class Operations {

    public $conn;

    public function __construct() {
        $db = DbConnect::getInstance();
        $this->conn = $db->getConnection();
        if (!$this->conn) {
            throw new Exception("cannot connect to server");
        }
        return $this->conn;
    }

    public function insert($type, $index, $values) {
        //Insert into Elastic
        //@return bool
        $client = new Elasticsearch\Client();
        $params = array();
        $params['body'] = array('author' => $values->author, 'title' => $values->title, 'content' => $values->content);
        $params['index'] = $index;
        $params['type'] = $type;
        if ($ret = $client->index($params)) {
            return 1;
        }
    }

    public function update($id, $type, $index, $values) {
        //Update single value in Elastic
        //@return bool
        $client = new Elasticsearch\Client();
        $params = array();
        $delParams = array();
        $params['body'] = array('author' => $values->author, 'title' => $values->title, 'content' => $values->content);
        $params['type'] = $type;
        $params['index'] = $index;
        $params['id'] = $id;
        $delParams['type'] = $type;
        $delParams['index'] = $index;
        $delParams['id'] = $id;
        $retDelete = $client->delete($delParams);
        if ($ret = $client->index($params)) {
            return 1;
        }
    }

    public function delete($postid, $type, $index) {
        //Delete single value from Elastic
        //@return bool
        $client = new Elasticsearch\Client();
        $params = array();
        $params['type'] = $type;
        $params['index'] = $index;
        $params['id'] = $postid;
        if ($ret = $client->delete($params)) {
            return 1;
        }
    }

}

?>
