<?php require_once '../model/addBkPhoto.php';

$background_Photo = new Background_Photo();

$background_Photo->conn->beginTransaction();

try {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $json = file_get_contents('php://input');
        if (isset($json) && !empty($json)) {
            $request = json_decode($json);
            if (isset($request) && !empty($request)) {
                // ADD UPDATE DELETE GET
                if (isset($request->action)) {
                    @session_start();
                    $user_id = $_SESSION['sF_user_id'];
                    if ($request->action == 'add') {
                        // print_r($request);exit;
                        $background_Photo->title_name = $request->title_name;
                        $background_Photo->backgroundPhotoPath = $request->backgroundPhotoPath;
                        $background_Photo->created_by = $user_id;
                        if ($background_Photo->insert()) {
                            $background_Photo_id = $background_Photo->last_insert_id();
                            // getting file extension in base 64
                            print_r($background_Photo_id);die();
                            if (isset($request->backgroundPhoto_base64) && $request->backgroundPhoto_base64 != '') {
                                $file_name = 'BackgroundPhoto' . '_' . $background_Photo_id . '_' . date('YmdHis');
                                print_r($file_name);die();
                                if (createFileFromBase64AllExtn($request->backgroundPhoto_base64, "../../background_Photo/", $file_name)) {

                                    $background_Photo->backgdphoto_id = $backgroundPhoto_id;
                                    $background_Photo->backgroundPhoto_upload = $file_name . '.' . get_string_between($request->backgroundPhoto_base64, '/', ';base64');
                                    $background_Photo->updated_by = $user_id;
                                    print_r($background_Photo);
                                    exit;
                                    if ($background_Photo->update_bill_path()) {
                                        $response = [
                                            'success' => 1,
                                            'code' => 200,
                                            'msg' => 'Case Daily Expense Add successfully!'
                                        ];
                                        $background_Photo->conn->commit();
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
                                $background_Photo->conn->commit();
                                http_response_code(200);
                                echo json_encode($response);
                            }
                        } else {
                            print_r("b");
                            exit;
                            $background_Photo->conn->rollBack();
                            throw new Exception('Error while adding Senior Case Daily Expense details', 400);
                        }
                    } else if ($request->action == 'update') {
                        $backgroundPhoto->backgdphoto_id = $request->backgdphoto_id;
                        $backgroundPhoto->file_no = $request->file_no;
                        $backgroundPhoto->client_code_id = $request->client_code_id;
                        $backgroundPhoto->file_title = $request->file_title;
                        $backgroundPhoto->file_description = $request->file_description;
                        $backgroundPhoto->updated_by = $user_id;
                        if ($backgroundPhoto->check() == 0) {
                            $backgroundPhoto->update();
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'File successfully updated!'
                            ];

                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception('File Already Exists', 400);
                        }
                    } else if ($request->action == 'delete') {
                        $backgroundPhoto->backgdphoto_id = $request->backgdphoto_id;
                        $backgroundPhoto->delete();
                        $response = [
                            'success' => 1,
                            'code' => 200,
                            'msg' => 'File Successfully deleted!',
                        ];

                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'get') {
                        $result = [];
                        if (isset($request->backgdphoto_id) && $request->backgdphoto_id > 0) {
                            $background_Photo->backgdphoto_id = $request->backgdphoto_id;
                            $results = $background_Photo->get();
                        } else {
                            $results = $background_Photo->get($request);
                        }
                        if (isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i = 0;
                            $request->draw = 0;
                        }
                        foreach ($results as $res) {

                            ++$i;
                            $result[] = [
                                's_no'                     => $i,
                                'backgdphoto_id'           => $res['backgdphoto_id'],
                                'title_name'               => $res['title_name'],
                                'backgroundPhoto_upload'   => $res['backgroundPhoto_upload'],
                                'is_active'                => $res['is_active'],
                                "action"                   => "<a class='edit cursor-pointer' data-id='" . $res['backgdphoto_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;
                                <a class='delete cursor-pointer text-danger' data-id='" . $res['backgdphoto_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                            ];
                        }
                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $background_Photo->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'File Fetch Successfully!',
                            'data'              => $result,
                        ];
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
    $response = [

        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage()
    ];
    http_response_code(500);
    echo json_encode($response);
} catch (Exception $e) {
    $response = [
        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage(),
    ];
    // http_response_code($e->getCode());
    echo json_encode($response);
}
