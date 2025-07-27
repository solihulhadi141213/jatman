<?php
    header("Content-Type: application/json");
    require_once "../_Config/Connection.php";
    require_once "../_Config/Function.php";

    function respond($data, $status = 200) {
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Validasi metode
    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
        respond(['status' => 'error', 'message' => 'Metode harus PUT'], 405);
    }

    // Validasi token
    $headers = getallheaders();
    $token = $headers['x-token'] ?? $headers['X-Token'] ?? '';
    if (!$token) respond(['status' => 'error', 'message' => 'Token tidak ditemukan'], 401);

    $Conn = (new Database())->getConnection();
    if (validasi_x_token($Conn, $token) !== 'Valid') {
        respond(['status' => 'error', 'message' => 'Token tidak valid'], 403);
    }

    // Ambil input JSON body
    $input = json_decode(file_get_contents("php://input"), true);
    if (!is_array($input)) {
        respond(['status' => 'error', 'message' => 'Request body tidak valid'], 400);
    }

    // Validasi dan sanitasi input
    $title = trim(strip_tags($input['title'] ?? ''));
    $subtitle = trim(strip_tags($input['subtitle'] ?? ''));
    $limit = (int)($input['limit'] ?? 0);

    if ($limit < 1) {
        respond(['status' => 'error', 'message' => 'Limit tidak boleh kosong dan minimal 1'], 422);
    }
    if (strlen($title) > 200) {
        respond(['status' => 'error', 'message' => 'Title maksimal 200 karakter'], 422);
    }
    if (strlen($subtitle) > 200) {
        respond(['status' => 'error', 'message' => 'Subtitle maksimal 200 karakter'], 422);
    }

    // Ambil data setting layout_static
    $sql_select = "SELECT setting_value FROM setting WHERE setting_parameter = 'layout_static' LIMIT 1";
    $stmt = $Conn->prepare($sql_select);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        respond(['status' => 'error', 'message' => 'Data setting layout_static tidak ditemukan'], 404);
    }

    $data_setting = json_decode($row['setting_value'], true);
    if (!is_array($data_setting)) {
        respond(['status' => 'error', 'message' => 'Format setting_value tidak valid'], 500);
    }

    // Update bagian ruang_rawat
    $data_setting['ruang_rawat'] = [
        'title' => $title,
        'subtitle' => $subtitle,
        'limit' => $limit
    ];

    // Simpan ke database
    $sql_update = "UPDATE setting SET setting_value = :setting_value WHERE setting_parameter = 'layout_static'";
    $stmt_update = $Conn->prepare($sql_update);
    $setting_json = json_encode($data_setting, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $stmt_update->bindParam(':setting_value', $setting_json);

    if ($stmt_update->execute()) {
        respond(['status' => 'success', 'message' => 'Ruang rawat berhasil diperbarui']);
    } else {
        respond(['status' => 'error', 'message' => 'Gagal menyimpan perubahan'], 500);
    }
?>