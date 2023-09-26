<?php
require_once '../model/back_Photo.php';
require_once '../helper/common.php';

$backGround_pH = new back_PhotoM();

$backGround_pH->conn->beginTransaction();
try {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $json = file_get_contents('php://input');
        if (isset($json) && !empty($json)) {
            $request = json_decode($json);
            if (isset($request) && !empty($request)) {
                // ADD UPDATE DELETE GET
                if (isset($request->action)) {
                    @session_start();
                    $user_id = $_SESSION["sF_user_id"];
                    if ($request->action == 'add') {
                        $backGround_pH->backGphoto_id = $request->backGphoto_id;
                        $backGround_pH->tittle_name = $request->tittle_name;
                        $backGround_pH->created_by = $user_id;
                        if ($backGround_pH->insert()) {
                            print_r($backGround_pH);exit;
                            $backGround_pH_id = $backGround_pH->last_insert_id();
                            // getting file extension in base 64
                            if (isset($request->bill_file_base64) && $request->bill_file_base64 != '') {
                                $file_name = 'ExpenseBill'.'_'.$backGround_pH_id . '_' . date('YmdHis');
                                if (createFileFromBase64AllExtn($request->bill_file_base64, "../../upload_Photo_path_files/", $file_name)) {
                                    $backGround_pH->backGphoto_id = $backGround_pH_id;
                                    $backGround_pH->uploadPhoto_path = $file_name . '.' . get_string_between($request->bill_file_base64, '/', ';base64');
                                    $backGround_pH->updated_by = $user_id;
                                    if ($backGround_pH->update_uploadPhoto_path()) {
                                        $response = [
                                            'success' => 1,
                                            'code' => 200,
                                            'msg' => 'Case Daily Expense Add successfully!'
                                        ];
                                        $backGround_pH->conn->commit();
                                        http_response_code(200);
                                        echo json_encode($response);
                                    } else {
                                        throw new Exception("Error while saving bill file", 400);
                                    }
                                } else {
                                    throw new Exception("Error while uploading bill file", 400);
                                }
                            } else {
                                $response = [
                                    'success' => 1,
                                    'code' => 200,
                                    'msg' => 'Senior Case Daily Expense details successfully added!'
                                ];
                                $backGround_pH->conn->commit();
                                http_response_code(200);
                                echo json_encode($response);
                            }
                        } else {
                            $backGround_pH->conn->rollBack();
                            throw new Exception('Error while adding Senior Case Daily Expense details', 400);
                        }
                    } else if ($request->action == 'update') {
                        $backGround_pH->backGphoto_id = $request->backGphoto_id;
                        $backGround_pH->backGphoto_id = $request->backGphoto_id;
                        $backGround_pH->tittle_name = $request->tittle_name;
                        $backGround_pH->updated_by = $user_id;
                        if ($backGround_pH->update()) {
                            if (isset($request->bill_file_base64) && $request->bill_file_base64 != '') {
                                $file_name = 'ExpenseBill'.'_'.$request->backGphoto_id . '_' . date('YmdHis');
                                if (createFileFromBase64AllExtn($request->bill_file_base64, "../../upload_Photo_path_files/", $file_name)) {
                                    $backGround_pH->backGphoto_id = $request->backGphoto_id;
                                    $backGround_pH->uploadPhoto_path = $file_name . '.' . get_string_between($request->bill_file_base64, '/', ';base64');
                                    $backGround_pH->updated_by = $user_id;
                                    if ($backGround_pH->update_uploadPhoto_path()) {
                                        $response = [
                                            'success' => 1,
                                            'code' => 200,
                                            'msg' => 'Case Daily Expense updated successfully!'
                                        ];
                                        $backGround_pH->conn->commit();
                                        http_response_code(200);
                                        echo json_encode($response);
                                    } else {
                                        $backGround_pH->conn->rollBack();
                                        throw new Exception("Error while saving bill file", 400);
                                    }
                                } else {
                                    $backGround_pH->conn->rollBack();
                                    throw new Exception("Error while uploading bill file", 400);
                                }
                            } else {
                                $backGround_pH->uploadPhoto_path = $request->uploadPhoto_path;
                                if ($backGround_pH->update_uploadPhoto_path()) {
                                    $response = [
                                        'success' => 1,
                                        'code' => 200,
                                        'msg' => 'Case Daily Expense updated successfully!'
                                    ];
                                    $backGround_pH->conn->commit();
                                    http_response_code(200);
                                    echo json_encode($response);
                                } else {
                                    $backGround_pH->conn->rollBack();
                                    throw new Exception("Error while saving bill file", 400);
                                }
                            }
                        } else {
                            $backGround_pH->conn->rollBack();
                            throw new Exception("Error while Updating Case Daily Expense", 400);
                        }
                    } else if ($request->action == 'delete') {
                        $backGround_pH->backGphoto_id = $request->backGphoto_id;
                        $backGround_pH->updated_by = $user_id;
                        if ($backGround_pH->delete()) {
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'Case Daily Expense successfully deleted!'
                            ];
                            $backGround_pH->conn->commit();
                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception("Error while deleting Case Daily Expense", 1);
                        }
                    } else if ($request->action == 'get') {
                        $result = [];
                        if (isset($request->backGphoto_id) && $request->backGphoto_id > 0) {
                            $backGround_pH->backGphoto_id = $request->backGphoto_id;
                        }
                        $results = $backGround_pH->get($request);
                        
                        if (isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i = 0;
                            $request->draw = 0;
                        }
                        foreach ($results as $res) {
                            // print_r($ab);exit;
                            ++$i;
                            // echo '../../upload_Photo_path_files/'.$res['uploadPhoto_path'];exit;
                            if(file_exists('../../upload_Photo_path_files/'.$res['uploadPhoto_path'])){
                                $a = "<a class='edit cursor-pointer' data-id='" . $res['backGphoto_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true' style='font-size:17px;'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['backGphoto_id'] . "'><i class='fa fa-trash' aria-hidden='true' style='font-size:17px;'></i></a>&nbsp;&nbsp;&nbsp;<a style='cursor:pointer;' href='../upload_Photo_path_files/".$res['uploadPhoto_path']."' target='_blank' class='cursor-pointer text-warning' style='font-size:17px;'><i class='fa fa-download' aria-hidden='true'></i></a>";
                            } else {
                                $a = "<a class='edit cursor-pointer' data-id='" . $res['backGphoto_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true' style='font-size:17px;'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['backGphoto_id'] . "'><i class='fa fa-trash' aria-hidden='true' style='font-size:17px;'></i></a>";
                            }
                            $result[] = [
                                's_no'              =>  $i,
                                'backGphoto_id'     =>  $res['backGphoto_id'],
                                'uploadPhoto_path'  =>  $res['uploadPhoto_path'],
                                'tittle_name'       =>  $res['tittle_name'],
                                'is_status'         =>  $res['is_status'],
                                'action'            =>  $a
                            ];
                        }
                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $backGround_pH->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Background Photo Upload Data Fetch Successfully!',
                            'data'              => $result
                        ];
                        $backGround_pH->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'getSingle') {
                        $result = [];
                        $backGround_pH->backGphoto_id = $request->backGphoto_id;

                        $results = $backGround_pH->getSingle();
                        $i = 0;
                        $request->draw = 0;

                        foreach ($results as $res) {
                            ++$i;
                            $result[] = [
                                's_no'              =>  $i,
                                'backGphoto_id'     =>  $res['backGphoto_id'],
                                'uploadPhoto_path'  =>  $res['uploadPhoto_path'],
                                'tittle_name'       =>  $res['tittle_name'],
                                'uploadPhoto_path'  =>  $res['uploadPhoto_path'],
                                'action'            => "<a class='edit cursor-pointer' data-id='" . $res['backGphoto_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['backGphoto_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                            ];
                        }
                        $response = [
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $backGround_pH->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Background Photo Upload Data Fetch Successfully!',
                            'data'              => $result
                        ];
                        $backGround_pH->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } else {
                        throw new Exception('Invalid action type', 400);
                    }
                } else {
                    throw new Exception('action key missing in request body', 400);
                }
            } else {
                throw new Exception('Invalid JSON', 400);
            }
        } else {
            throw new Exception('Request body missing', 400);
        }
    } else {
        throw new Exception('Invalid Request METHOD - METHOD must be POST', 400);
    }
} catch (PDOException $e) {
    $backGround_pH->conn->rollBack();
    $response = [

        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage()
    ];
    http_response_code(500);
    echo json_encode($response);
} catch (Exception $e) {
    $backGround_pH->conn->rollBack();
    $response = [
        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage(),
    ];
    http_response_code($e->getCode());
    echo json_encode($response);
}
