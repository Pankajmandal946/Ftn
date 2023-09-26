<?php
require_once '../config/DBConnection.php';

class Background_Photo
{

    public $backgdphoto_id, $title_name, $backgroundPhoto_upload, $is_active, $created_by, $created_on, $updated_by, $updated_on, $table_name, $db, $conn;

    function __construct()
    {
        $this->backgdphoto_id = 0;
        $this->title_name = "";
        $this->backgroundPhoto_upload = 0;
        $this->is_active = 1;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = "backgroundPhoto";
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    function insert()
    {
        $data = [
            "title_name"             => $this->title_name,
            "backgroundPhoto_upload" => $this->backgroundPhoto_upload,
            "is_active"              => $this->is_active,
            "created_by"             => $this->created_by
        ];
        $sql = "INSERT INTO " . $this->table_name . " (title_name, backgroundPhoto_upload, is_active,created_by) VALUES (:title_name, :backgroundPhoto_upload, :is_active, :created_by)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    // Update 
    public function update()
    {
        $data = [
            "title_name"        => $this->title_name,
            "backgroundPhoto_upload"   => $this->backgroundPhoto_upload,
            "is_active"         => $this->is_active,
            "updated_by"        => $this->updated_by,

        ];
        // print_r($data);exit;
        $sql = "UPDATE " . $this->table_name . " SET title_name=:title_name,backgroundPhoto_upload=:backgroundPhoto_upload, is_active = :is_active, updated_by = :updated_by WHERE backgdphoto_id=:backgdphoto_id AND is_active = :is_active";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
    }


    //Delete
    public function delete()
    {
        $data = [
            "backgdphoto_id"  => $this->backgdphoto_id,
            "is_active"     => 2,
            "updated_by"    => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET is_active = :is_active, updated_by = :updated_by WHERE backgdphoto_id = :backgdphoto_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
    }


    // Get
    public function get($Request = [])
    {
        $output = [];
        $data = [
            "is_active"  => 2
        ];

        if (!empty($Request)) {
            $query = "SELECT backgdphoto_id, title_name, backgroundPhoto_upload, is_active FROM " . $this->table_name . "
            WHERE is_active < :is_active";

            if (isset($Request->search->value) && $Request->search->value != '' ) {
                $data['search_value'] = '%'.$Request->search->value.'%';
                $query .= " AND (backgdphoto_id LIKE :search_value";
                $query .= " OR title_name LIKE :search_value";
            } 
        }
        
        
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        // echo $debug_query;exit;
        $results = $stmt->fetchAll();
        // print_r($results);exit;
        $count = $stmt->rowCount();
        $stmt->closeCursor();

        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'backgdphoto_id'    => $row['backgdphoto_id'],
                    'title_name'        => $row['title_name'],
                    'backgroundPhoto_upload'   => $row['backgroundPhoto_upload'],
                    'is_active'         => $row['is_active']
                ];
            }
        }
        return $output;
    }

    // Check
    public function check()
    {
        if (isset($this->backgdphoto_id) && $this->backgdphoto_id > 0) {
            $data = [
                'backgdphoto_id'       => $this->backgdphoto_id,
                'title_name'       => $this->title_name,
                'is_active'     => 1
            ];
            $stmt = $this->conn->prepare("SELECT backgdphoto_id FROM " . $this->table_name . " WHERE title_name = :title_name AND backgdphoto_id !=:backgdphoto_id AND is_active=:is_active");
        }else {
            $data = [
                'title_name'       => $this->title_name,
                'is_active'     => 1
            ];
            $stmt = $this->conn->prepare("SELECT backgdphoto_id FROM " . $this->table_name . " WHERE title_name = :title_name AND is_active=:is_active");
        }

        $stmt->execute($data);
        $count = $stmt->rowCount();
        if ($count > 0) {
            $row = $stmt->fetch();
            return $row['backgdphoto_id'];
        } else{
            return 0;
        }
    }

    function get_total_count()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE is_active < 2");
        $stmt->execute();
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        return $count;
    }

    function last_insert_id()
    {
        $stmt = $this->conn->prepare("SELECT LAST_INSERT_ID() as last_id FROM " . $this->table_name);
        $stmt->execute();
        $result = $stmt->fetch();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        return $result['last_id'];
    }

    function update_bill_path()
    {
        $data = [
            'backgdphoto_id'        => $this->backgdphoto_id,
            'backgroundPhoto_upload'       => $this->backgroundPhoto_upload,
            'updated_by'            => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET bill_path=:bill_path ,updated_by=:updated_by WHERE case_daily_expense_id=:case_daily_expense_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $last_query = $stmt->queryString;
        $debug_query = $stmt->_debugQuery();
        echo $debug_query;exit;
        $stmt->closeCursor();
        return true;
    }
}
