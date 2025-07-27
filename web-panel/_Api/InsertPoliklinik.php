<?php
    // Aktifkan error reporting
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header('Content-Type: application/json');

    // Fungsi response JSON
    function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Validasi metode POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendResponse(['status' => 'error', 'message' => 'Metode harus POST'], 405);
    }

    // Load koneksi & fungsi
    require_once '../_Config/Connection.php';
    require_once '../_Config/Function.php';
    require_once '../_Config/log_visitor.php';

    // Koneksi database
    try {
        $Conn = (new Database())->getConnection();
    } catch (Exception $e) {
        sendResponse(['status' => 'error', 'message' => 'Koneksi DB gagal: ' . $e->getMessage()], 500);
    }

    // Validasi x-token
    $headers = getallheaders();
    $token = $headers['x-token'] ?? $headers['X-Token'] ?? '';
    if (empty($token)) {
        sendResponse(['status' => 'error', 'message' => 'Token tidak ditemukan.'], 401);
    }
    $validasi_token = validasi_x_token($Conn, $token);
    if ($validasi_token !== "Valid") {
        sendResponse(['status' => 'error', 'message' => $validasi_token], 401);
    }

    // Ambil dan decode JSON
    $rawInput = file_get_contents("php://input");
    $input = json_decode($rawInput, true);
    if (!is_array($input)) {
        sendResponse(['status' => 'error', 'message' => 'Format JSON tidak valid'], 400);
    }

    // Ambil data input
    $poliklinik = trim($input['poliklinik'] ?? '');
    $deskripsi = trim($input['deskripsi'] ?? '');
    $kode = trim($input['kode'] ?? '');
    $status = trim($input['status'] ?? '');
    $fotoBase64 = trim($input['foto'] ?? '');

    // Validasi data wajib
    if ($poliklinik === '' || strlen($poliklinik) > 255) {
        sendResponse(['status' => 'error', 'message' => 'Nama poliklinik tidak boleh kosong dan maksimal 255 karakter'], 400);
    }
    if (strlen($kode) > 20) {
        sendResponse(['status' => 'error', 'message' => 'Kode maksimal 20 karakter'], 400);
    }
    if (!in_array($status, ['Aktif', 'Non Aktif'])) {
        sendResponse(['status' => 'error', 'message' => 'Status hanya boleh Aktif atau Non Aktif'], 400);
    }

    // Sanitasi deskripsi (mencegah XSS/script injection)
    $deskripsi = strip_tags($deskripsi, '<b><br><i><u><p><strong><em><ul><ol><li>'); // whitelist tag HTML

    // Validasi duplikat kode
    $stmtCheck = $Conn->prepare("SELECT COUNT(*) FROM poliklinik WHERE kode = :kode");
    $stmtCheck->bindParam(':kode', $kode);
    $stmtCheck->execute();
    if ((int)$stmtCheck->fetchColumn() > 0) {
        sendResponse(['status' => 'error', 'message' => 'Kode poliklinik sudah digunakan'], 409);
    }

    // Simpan file foto jika ada
    $namaFileFoto = null;
    if (!empty($fotoBase64)) {
        if (!preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $fotoBase64)) {
            sendResponse(['status' => 'error', 'message' => 'Format foto tidak valid'], 400);
        }

        [$typeMeta, $base64Data] = explode(',', $fotoBase64);
        $mime = explode('/', explode(';', $typeMeta)[0])[1];
        $fotoDecoded = base64_decode($base64Data, true);

        if ($fotoDecoded === false) {
            sendResponse(['status' => 'error', 'message' => 'Base64 foto tidak valid'], 400);
        }

        if (strlen($fotoDecoded) > 2 * 1024 * 1024) {
            sendResponse(['status' => 'error', 'message' => 'Ukuran file foto melebihi 2MB'], 400);
        }

        $folderPath = __DIR__ . '/../assets/img/_Poliklinik/';
        if (!is_dir($folderPath)) {
            if (!mkdir($folderPath, 0755, true)) {
                sendResponse(['status' => 'error', 'message' => 'Gagal membuat folder penyimpanan foto'], 500);
            }
        }

        $namaFileFoto = 'poli_' . uniqid() . '.' . $mime;
        $filePath = $folderPath . $namaFileFoto;

        if (!file_put_contents($filePath, $fotoDecoded)) {
            sendResponse(['status' => 'error', 'message' => 'Gagal menyimpan foto'], 500);
        }
    }

    // Simpan data ke database
    try {
        $stmt = $Conn->prepare("INSERT INTO poliklinik (poliklinik, deskripsi, kode, status, foto, last_update) VALUES (:poliklinik, :deskripsi, :kode, :status, :foto, NOW())");
        $stmt->bindParam(':poliklinik', $poliklinik);
        $stmt->bindParam(':deskripsi', $deskripsi);
        $stmt->bindParam(':kode', $kode);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':foto', $namaFileFoto);
        $stmt->execute();

        sendResponse([
            'status' => 'success',
            'message' => 'Data poliklinik berhasil disimpan',
            'data' => [
                'id_poliklinik' => $Conn->lastInsertId(),
                'poliklinik' => $poliklinik,
                'kode' => $kode,
                'status' => $status,
                'foto' => $namaFileFoto
            ]
        ]);
    } catch (PDOException $e) {
        sendResponse(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
    }
?>
