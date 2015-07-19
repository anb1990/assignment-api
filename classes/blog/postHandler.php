<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/classes/db/Operations.php';

class postHandler extends Operations {

    public function createPost($values, $userID) {
        //Create Post
        //@return Encrypted string
        if ($this->__userAuthentication($userID) == -2) {
            return -2; //error code {user not logged in}
        }
        $type = 'posts';
        $index = 'blog';
        return $this->insert($type, $index, $values);
    }

    public function updatePost($userID, $postid, $values) {
        //Update Post
        //@return Encrypted string
        if ($this->__userAuthentication($userID) == -2) {
            return -2; //error code {user not logged in}
        }
        $type = 'posts';
        $index = 'blog';
        return $this->update($postid, $type, $index, $values);
    }

    public function deletePost($userID, $postid) {
        //Delete Post
        //@return Encrypted string
        if ($this->__userAuthentication($userID) == -2) {
            return -2; //error code {user not logged in}
        }
        $type = 'posts';
        $index = 'blog';
        return $this->delete($postid, $type, $index);
    }

    public function fetchPosts($searchterms) {
        //Fetch Posts
        //@return Encrypted string
        $client = new Elasticsearch\Client();
        $params['index'] = 'blog';
        $params['type'] = 'posts';

        if (isset($searchterms['author']) && !empty($searchterms['author'])) {
            $params['body']['query']['match']['author'] = $searchterms['author'];
        }
        if (isset($searchterms['title']) && !empty($searchterms['title'])) {
            $params['body']['query']['match']['title'] = $searchterms['title'];
        }
        if (isset($searchterms['content']) && !empty($searchterms['content'])) {
            $params['body']['query']['match']['content'] = $searchterms['content'];
        }

        $allData = array();
        $results = $client->search($params)['hits'];
        $counter = 0;
        foreach ($results['hits'] as $row) {
            if (isset($row['_id'])) {
                $allData[$row['_id']]['id'] = $row['_id'];
            }
            if (isset($row['_source']['author'])) {
                $allData[$row['_id']]['author'] = $row['_source']['author'];
            }
            if (isset($row['_source']['title'])) {
                $allData[$row['_id']]['title'] = $row['_source']['title'];
            }
            if (isset($row['_source']['content'])) {
                $allData[$row['_id']]['content'] = $row['_source']['content'];
            }
        }
        $allData = json_encode($allData);
        return $allData;
    }

    public function fetchPost($id) {
        //Fetch Post
        //@return Encrypted string
        $client = new Elasticsearch\Client();
        $params['index'] = 'blog';
        $params['type'] = 'posts';

        if (isset($id)) {
            $params['id'] = $id;
        }


        $allData = array();
        $results = $client->get($params);

        $allData['id'] = $id;

        if (isset($results['_source']['author'])) {
            $allData['author'] = $results['_source']['author'];
        }
        if (isset($results['_source']['title'])) {
            $allData['title'] = $results['_source']['title'];
        }
        if (isset($results['_source']['content'])) {
            $allData['content'] = $results['_source']['content'];
        }
        

        $allData = json_encode($allData);
        return $allData;
    }

    private function __userAuthentication($userID) {
        //Check user authentication
        //@return Integer
        $ifUser = $this->conn->prepare("SELECT count(*) count FROM user WHERE id = $userID");
        $ifUser->execute();
        $ifUser->bind_result($count);
        $ifUser->fetch();
        if ($count != 1) {
            return -2; //error code {user not logged in}
        }
    }

}
