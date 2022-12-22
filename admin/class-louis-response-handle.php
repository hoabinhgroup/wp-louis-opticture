<?php
class Louis_Response_Handle {
    const STATUS_ENQUEUED = 10;
    const STATUS_SUCCESS = 2;
    const STATUS_SUCCESS_ALL = 3;
    const STATUS_UNCHANGED = 0;
    const STATUS_ERROR = -1;
    const STATUS_FAIL = -2;
    const STATUS_QUOTA_EXCEEDED = -3;
    const STATUS_SKIP = -4;
    const STATUS_NOT_FOUND = -5;
    const STATUS_NO_AUTH = -6;
    const STATUS_RETRY = -7;
    const STATUS_SEARCHING = -8;
    const STATUS_QUEUE_FULL = -404;
    const STATUS_MAINTENANCE = -500;
    const STATUS_CONNECTION_ERROR = -503;
    const STATUS_NOT_API = -1000;
    const STATUS_STOP_ALL = -2000;


    const ERR_FILE_NOT_FOUND = -902;
    const ERR_TIMEOUT = -903;
    const ERR_SAVE = -904;
    const ERR_SAVE_BKP = -905;
    const ERR_INCORRECT_FILE_SIZE = -906;
    const ERR_DOWNLOAD = -907;
    const ERR_POSTMETA_CORRUPT = -909;
    const ERR_UNKNOWN = -999;
    const ERR_AUTHENTICATION = -900;
    const ERR_API_NOT_CORRECT = -1001;
    const ERR_QUOTA_EXCEEDED = 1003;


public static function setResponse($responseCode, $message, $data = ''){
    louis_log( ['Status: ' => $responseCode, 'message' => $message, 'data' => $data] );

        switch($message){
            case 'cURL error 7: Failed to connect to api.louiscms.com port 8080: Connection refused':
            $customMessage = 'Connection Refused';
            $responseCode =  self::STATUS_NOT_API;
            break;
            case 'Error: Input Buffer is empty':
            $customMessage = 'Lỗi file ảnh rỗng';
            $responseCode =  self::STATUS_SKIP;
            break;
            case 'Quota exceed!':
            $customMessage = '<p>Quota exceed! <a target="_blank" href="https://louiscms.com" class=" button-smaller button-primary optimize ">Nâng cấp lưu lượng ngay</a></p> ';
            $responseCode = self::ERR_QUOTA_EXCEEDED;
            break;
            case '-900':
            $customMessage = 'Xin đăng nhập hệ thống';
            break;
            case '-902':
            $customMessage = 'Lỗi không tồn tại ảnh';
            break;
            case '-1001':
            $customMessage = 'Lỗi url API chưa đúng';
            break;
            case self::ERR_QUOTA_EXCEEDED:
            saveSettings('louis_opticture_quota', 0);
            break;
            default:
            $customMessage =  $message;
        }

    wp_send_json(
    [
        'status' => $responseCode,
        'message' => $customMessage,
        'data' => $data

    ]);
  }
}
?>
