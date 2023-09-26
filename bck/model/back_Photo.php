<?php require_once '../config/DBConnection.php';

class back_PhotoM
{
    public $table_name, $backGphoto_id, $tittle_name, $uploadPhoto_path, $created_by, $is_status, $is_active, $updated_by, $db, $conn;

    function __construct()
    {
        $this->backGphoto_id = 0;
        $this->tittle_name = "";
        $this->uploadPhoto_path = 0;
        $this->created_by = '';
        $this->is_status = 1;
        $this->is_active = 1;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = 'backPhotos';
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    function insert()
    {
        $data = [
            "tittle_name"            => $this->tittle_name,
            "uploadPhoto_path"       => $this->uploadPhoto_path,
            "is_active"              => $this->is_active,
            "created_by"             => $this->created_by
        ];
        $sql = "INSERT INTO " . $this->table_name . " (tittle_name, uploadPhoto_path, is_active,created_by) VALUES (:tittle_name, :uploadPhoto_path, :is_active, :created_by)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function update()
    {
        $data = [
            'backGphoto_id'     =>  $this->backGphoto_id,
            'tittle_name'       =>  $this->tittle_name,
            'uploadPhoto_path'  =>  $this->uploadPhoto_path,
            'is_status'         =>  $this->is_status,
            'is_active'         =>  1,
            'updated_by'        =>  $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET backGphoto_id=:backGphoto_id, tittle_name=:tittle_name,uploadPhoto_path=:uploadPhoto_path,is_status=:is_status, is_active=:is_active, updated_by=:updated_by WHERE backGphoto_id=:backGphoto_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function delete()
    {
        $data = [
            'backGphoto_id'    => $this->backGphoto_id,
            'is_active'             => 2,
            'updated_by'            => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET is_active=:is_active, updated_by=:updated_by WHERE backGphoto_id=:backGphoto_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        // $debug_query = $stmt->_debugQuery();
        // echo $debug_query; exit;
        $stmt->closeCursor();
        return true;
    }

    function get($Request)
    {
        $output = [];
        $data = [
            'is_active'  => 2
        ];
        if (!empty($Request)) {
            // print_r("a");exit;
            $query = "SELECT backGphoto_id, tittle_name, uploadPhoto_path, is_status  FROM " . $this->table_name . "
            WHERE is_active < :is_active";
            if (isset($Request->search->value)) {
                $data['search_value'] = '%' . $Request->search->value . '%';
                $query .= " AND (tittle_name LIKE :search_value)";
            }
            if ($this->backGphoto_id > 0) {
                $data['backGphoto_id'] = $this->backGphoto_id;
                $query .= " AND backGphoto_id = :backGphoto_id";
            }
            if (isset($Request->order) && $Request->order['0']->column > 0) {
                $query .= " ORDER BY " . $Request->order['0']->column . " " . $Request->order['0']->dir;
            } else {
                $query .= ' ORDER BY tittle_name asc ';
            }
            if (isset($Request->length) && $Request->length != -1) {
                $query .= ' LIMIT ' . $Request->start . ', ' . $Request->length;
            }
        } else {
            print_r("b");
            exit;
            if ($this->backGphoto_id > 0) {
                $data = [
                    'backGphoto_id'   => $this->backGphoto_id
                ];
                $query = "SELECT backGphoto_id, tittle_name, uploadPhoto_path  FROM " . $this->table_name . "
                WHERE backGphoto_id =:backGphoto_id";
            } else {
                $query = "SELECT backGphoto_id, tittle_name, uploadPhoto_path  FROM " . $this->table_name . "
                WHERE is_active < :is_active";
            }
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        // echo $debug_query;exit;
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'backGphoto_id'     =>  $row['backGphoto_id'],
                    'uploadPhoto_path'  =>  $row['uploadPhoto_path'],
                    'is_status'         =>  $row['is_status'],
                    'tittle_name'       =>  $row['tittle_name']
                ];
            }
        }
        return $output;
    }

    // function act_dact_update()
    // {
    //     $data = [
    //         'backGphoto_id'     =>  $this->backGphoto_id,
    //         'is_status'         =>  1,
    //         'updated_by'        =>  $this->updated_by
    //     ];
    //     $sql = "UPDATE " . $this->table_name . " SET backGphoto_id=:backGphoto_id,backGphoto_id=:backGphoto_id,is_status=:is_status, updated_by=:updated_by WHERE backGphoto_id=:backGphoto_id";
    //     $stmt = $this->conn->prepare($sql);
    //     $last_query = $stmt->queryString;
    //     $debug_query = $stmt->_debugQuery();
    //     echo $debug_query;exit;
    //     $stmt->execute($data);
    //     $stmt->closeCursor();
    //     return true;
    // }

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

    function update_uploadPhoto_path()
    {
        $data = [
            'backGphoto_id'     => $this->backGphoto_id,
            'uploadPhoto_path'  => $this->uploadPhoto_path,
            'updated_by'        => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET uploadPhoto_path=:uploadPhoto_path ,updated_by=:updated_by WHERE backGphoto_id=:backGphoto_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function getSingle()
    {
        $output = [];

        if ($this->backGphoto_id > 0) {
            $data = [
                'backGphoto_id'   => $this->backGphoto_id
            ];
            $query = "SELECT cde.backGphoto_id, cde.expense_date, cde.file_id,f.file_no, cde.client_code_id, cc.client_code, c.case_no, cde.case_id, cde.photocopy, cde.courier_domestic, cde.courier_international, cde.stay_place, cde.stayWithAss, cde.hotelNarration, cde.hotelCalculat_bas, cde.hotel_stay, cde.oth_expense,cde.conveyance, cde.airStay, cde.airAss, cde.airNarration, cde.airCalculat_bas, cde.air_ticket, cde.bill_path FROM " . $this->table_name . " cde
            INNER JOIN file_master f ON (f.file_id=cde.file_id) 
            INNER JOIN client_code cc ON cc.client_code_id = cde.client_code_id and cc.is_active = 1  
            LEFT JOIN case_master c ON c.case_id = cde.case_id and c.is_active = 1
            WHERE backGphoto_id =:backGphoto_id";
        } else {
            $query = "SELECT cde.backGphoto_id, cde.expense_date, cde.file_id,f.file_no, cde.client_code_id, cc.client_code, c.case_no, cde.case_id, cde.photocopy, cde.courier_domestic, cde.courier_international, cde.stay_place, cde.stayWithAss, cde.hotelNarration, cde.hotelCalculat_bas, cde.hotel_stay, cde.oth_expense,cde.conveyance, cde.airStay, cde.airAss, cde.airNarration, cde.airCalculat_bas, cde.air_ticket, cde.bill_path FROM " . $this->table_name . " la
            INNER JOIN file_master f ON (f.file_id=cde.file_id) 
            INNER JOIN client_code cc ON cc.client_code_id = cde.client_code_id and cc.is_active = 1  
            LEFT JOIN case_master c ON c.case_id = cde.case_id and c.is_active = 1
            WHERE cde.is_active < :is_active";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'backGphoto_id'     =>  $row['backGphoto_id'],
                    'expense_date'              =>  date('Y-m-d', strtotime($row['expense_date'])),
                    'file_id'                   =>  $row['file_id'],
                    'file_no'                   =>  $row['file_no'],
                    'client_code_id'            =>  $row['client_code_id'],
                    'client_code'               =>  $row['client_code'],
                    'case_id'                   =>  $row['case_id'],
                    'case_no'                   =>  $row['case_no'],
                    'photocopy'                 =>  $row['photocopy'],
                    'courier_domestic'          =>  $row['courier_domestic'],
                    'courier_international'     =>  $row['courier_international'],
                    'hotel_stay'                =>  $row['hotel_stay'],
                    'stay_place'                =>  $row['stay_place'],
                    'stayWithAss'               =>  $row['stayWithAss'],
                    'hotelNarration'            =>  $row['hotelNarration'],
                    'hotelCalculat_bas'         =>  $row['hotelCalculat_bas'],
                    'conveyance'                =>  $row['conveyance'],
                    'air_ticket'                =>  $row['air_ticket'],
                    'airStay'                   =>  $row['airStay'],
                    'airAss'                    =>  $row['airAss'],
                    'airNarration'              =>  $row['airNarration'],
                    'airCalculat_bas'           =>  $row['airCalculat_bas'],
                    'oth_expense'               =>  $row['oth_expense'],
                    'bill_path'                 =>  $row['bill_path']
                ];
            }
        }
        return $output;
    }

    // function get_daily_expense_by_time($Obj)
    // {
    //     $output = [];

    //     if (!empty($Obj)) {
    //         if (isset($Obj->invoice_id)) {
    //             if ($Obj->invoice_id > 0 && $Obj->is_final > 0) {
    //                 $data = [
    //                     'is_active'     => 1,
    //                     'file_id'       => $Obj->file_id,
    //                     'invoice_id'    => $Obj->invoice_id,
    //                     'start_date'    => date('Y-m-d', strtotime($Obj->start_date)),
    //                     'end_date'      => date('Y-m-d', strtotime($Obj->end_date))
    //                 ];
    //                 $query = "SELECT cde.backGphoto_id, cde.expense_date, cde.file_id,f.file_no, cde.client_code_id, cc.client_code, cde.invoice_id, c.case_no, cde.case_id, cde.photocopy, cde.courier_domestic, cde.courier_international, cde.stay_place, cde.stayWithAss, cde.hotelNarration, cde.hotelCalculat_bas, cde.hotel_stay, cde.oth_expense,cde.conveyance, cde.airStay, cde.airAss, cde.airNarration, cde.airCalculat_bas, cde.air_ticket, cde.bill_path 
    //                 FROM " . $this->table_name . " cde
    //                 INNER JOIN file_master f ON (f.file_id=cde.file_id)
    //                 INNER JOIN client_code cc ON (cc.client_code_id = cde.client_code_id and cc.is_active = 1)  
    //                 LEFT JOIN case_master c ON (c.case_id = cde.case_id and c.is_active = 1)
    //                 WHERE cde.file_id =:file_id AND (DATE(cde.expense_date) >= :start_date AND DATE(cde.expense_date) <= :end_date) and cde.invoice_id =:invoice_id and cde.is_active=:is_active";
    //             } else {
    //                 $data = [
    //                     'is_active'     => 1,
    //                     'file_id'       => $Obj->file_id,
    //                     'start_date'    => date('Y-m-d', strtotime($Obj->start_date)),
    //                     'end_date'      => date('Y-m-d', strtotime($Obj->end_date))
    //                 ];
    //                 $query = "SELECT cde.backGphoto_id, cde.expense_date, cde.file_id,f.file_no, cde.client_code_id, cc.client_code, c.case_no, cde.case_id, cde.photocopy, cde.courier_domestic, cde.courier_international, cde.stay_place, cde.stayWithAss, cde.hotelNarration, cde.hotelCalculat_bas, cde.hotel_stay, cde.oth_expense,cde.conveyance, cde.airStay, cde.airAss, cde.airNarration, cde.airCalculat_bas, cde.air_ticket, cde.bill_path 
    //                 FROM " . $this->table_name . " cde
    //                 INNER JOIN file_master f ON (f.file_id=cde.file_id)
    //                 INNER JOIN client_code cc ON (cc.client_code_id = cde.client_code_id and cc.is_active = 1)  
    //                 LEFT JOIN case_master c ON (c.case_id = cde.case_id and c.is_active = 1)
    //                 WHERE cde.file_id =:file_id AND (DATE(cde.expense_date) >= :start_date AND DATE(cde.expense_date) <= :end_date) and cde.is_active=:is_active";
    //             }
    //         } else {
    //             $data = [
    //                 'is_active'     => 1,
    //                 'file_id'       => $Obj->file_id,
    //                 'start_date'    => date('Y-m-d', strtotime($Obj->start_date)),
    //                 'end_date'      => date('Y-m-d', strtotime($Obj->end_date))
    //             ];
    //             $query = "SELECT cde.backGphoto_id, cde.expense_date, cde.file_id,f.file_no, cde.client_code_id, cc.client_code, c.case_no, cde.case_id, cde.photocopy, cde.courier_domestic, cde.courier_international, cde.stay_place, cde.stayWithAss, cde.hotelNarration, cde.hotelCalculat_bas, cde.hotel_stay, cde.oth_expense,cde.conveyance, cde.airStay, cde.airAss, cde.airNarration, cde.airCalculat_bas, cde.air_ticket, cde.bill_path 
    //             FROM " . $this->table_name . " cde
    //             INNER JOIN file_master f ON (f.file_id=cde.file_id)
    //             INNER JOIN client_code cc ON (cc.client_code_id = cde.client_code_id and cc.is_active = 1)  
    //             LEFT JOIN case_master c ON (c.case_id = cde.case_id and c.is_active = 1)
    //             WHERE cde.file_id =:file_id AND (DATE(cde.expense_date) >= :start_date AND DATE(cde.expense_date) <= :end_date) and cde.is_active=:is_active";
    //         }


    //         $stmt = $this->conn->prepare($query);
    //         $stmt->execute($data);
    //         $results = $stmt->fetchAll();
    //         $count = $stmt->rowCount();
    //         // $last_query = $stmt->queryString;
    //         // $debug_query = $stmt->_debugQuery();
    //         // echo $debug_query; exit;
    //         $stmt->closeCursor();
    //         if ($count > 0) {
    //             foreach ($results as $row) {
    //                 $output[] = [
    //                     'backGphoto_id'     =>  $row['backGphoto_id'],
    //                     'expense_date'              =>  date('Y-m-d', strtotime($row['expense_date'])),
    //                     'file_id'                   =>  $row['file_id'],
    //                     'file_no'                   =>  $row['file_no'],
    //                     'client_code_id'            =>  $row['client_code_id'],
    //                     'client_code'               =>  $row['client_code'],
    //                     'case_id'                   =>  $row['case_id'],
    //                     'case_no'                   =>  $row['case_no'],
    //                     'photocopy'                 =>  $row['photocopy'],
    //                     'courier_domestic'          =>  $row['courier_domestic'],
    //                     'courier_international'     =>  $row['courier_international'],
    //                     'hotel_stay'                =>  $row['hotel_stay'],
    //                     'stay_place'                =>  $row['stay_place'],
    //                     'stayWithAss'               =>  $row['stayWithAss'],
    //                     'hotelNarration'            =>  $row['hotelNarration'],
    //                     'hotelCalculat_bas'         =>  $row['hotelCalculat_bas'],
    //                     'conveyance'                =>  $row['conveyance'],
    //                     'air_ticket'                =>  $row['air_ticket'],
    //                     'airStay'                   =>  $row['airStay'],
    //                     'airAss'                    =>  $row['airAss'],
    //                     'airNarration'              =>  $row['airNarration'],
    //                     'airCalculat_bas'           =>  $row['airCalculat_bas'],
    //                     'oth_expense'               =>  $row['oth_expense'],
    //                     'bill_path'                 =>  $row['bill_path']
    //                 ];
    //             }
    //         }
    //     }
    //     return $output;
    // }
    // function update_invoice_no()
    // {
    //     $data = [
    //         'invoice_id'    => $this->invoice_id,
    //         'start_time'    => $this->start_time,
    //         'end_time'      => $this->end_time,
    //         'file_id'       => $this->file_id,
    //         'updated_by'    => $this->updated_by
    //     ];
    //     $sql = "UPDATE " . $this->table_name . " SET invoice_id=:invoice_id, updated_by=:updated_by WHERE file_id=:file_id AND DATE(expense_date) >= :start_time AND DATE(expense_date) <= :end_time AND is_active =1;";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute($data);
    //     // $last_query = $stmt->queryString;
    //     // $debug_query = $stmt->_debugQuery();
    //     $stmt->closeCursor();
    //     return true;
    // }
}
