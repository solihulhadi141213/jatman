<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Buka Pengaturan Koneksi Web
        try {
        // Query untuk mengambil data setting email gateway
        $id = 1; // Create variable to hold the value
        $stmt = $Conn->prepare("SELECT * FROM setting_koneksi  WHERE id_setting_koneksi  = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Fetch data
        $DataKoneksiWeb = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($DataKoneksiWeb) {
            // Assign variabel
            $base_url = $DataKoneksiWeb['base_url'];
            $user_key = $DataKoneksiWeb['user_key'];
            $access_key = $DataKoneksiWeb['access_key'];
        } else {
            // Handle case when no data found
            $base_url = "";
            $user_key = "";
            $access_key = "";
            
            error_log("No connection settings found with ID 1");
        }
    } catch (PDOException $e) {
        // Handle database errors
        error_log("PDO Error: " . $e->getMessage());
        
        // Set default empty values
        $base_url = "";
        $user_key = "";
        $access_key = "";
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => ''.$base_url.'/_Api/_GetToken.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "user_key" : "'.$user_key.'",
            "access_key" : "'.$access_key.'"
        }'
    ));

    $response = curl_exec($curl);
    $response_arry=json_decode($response,true);
    $status=$response_arry['status'];
    $message=$response_arry['message'];
    curl_close($curl);
?>

<div class="row mb-2">
    <div class="col-4 col-sm-5 col-md-4 col-lg-2">
        <small>Base URL</small>
    </div>
    <div class="col-1"><small>:</small></div>
    <div class="col-7 col-sm-6 col-md-7 col-lg-9">
        <small class="text text-grayish">
            <?php echo "$base_url"; ?>
        </small>
    </div>
</div>
<div class="row mb-2">
    <div class="col-4 col-sm-5 col-md-4 col-lg-2">
        <small>User Key</small>
    </div>
    <div class="col-1"><small>:</small></div>
    <div class="col-7 col-sm-6 col-md-7 col-lg-9">
        <small class="text text-grayish">
            <?php echo "$user_key"; ?>
        </small>
    </div>
</div>
<div class="row mb-2">
    <div class="col-4 col-sm-5 col-md-4 col-lg-2">
        <small>Access Key</small>
    </div>
    <div class="col-1"><small>:</small></div>
    <div class="col-7 col-sm-6 col-md-7 col-lg-9">
        <small class="text text-grayish">
            <?php echo "$access_key"; ?>
        </small>
    </div>
</div>
<div class="row mb-2">
    <div class="col-4 col-sm-5 col-md-4 col-lg-2">
        <small>Status</small>
    </div>
    <div class="col-1"><small>:</small></div>
    <div class="col-7 col-sm-6 col-md-7 col-lg-9">
        <?php
            if($status=="success"){
                echo '<small class="text text-success">Connected <i class="bi bi-check-circle"></i></small>';
            }else{
                echo '<small class="text text-danger">'.$message.' <i class="bi bi-x"></i></small>';
            }
        ?>
    </div>
</div>