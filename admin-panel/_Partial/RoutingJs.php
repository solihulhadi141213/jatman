<?php 
    $date_version=date('YmdHis');
    if(empty($_GET['Page'])){
        //Dafault Javascript Diarahkan Ke Dashboard
        echo '<script type="text/javascript" src="_Page/Dashboard/Dashboard.js?V='.$date_version.'"></script>';
    }else{
        $Page=$_GET['Page'];
        // Routing Javascript Berdasarkan Halaman
        $scripts = [
            "MyProfile"                 => "_Page/MyProfile/MyProfile.js",
            "Akses"                     => "_Page/Akses/Akses.js",
            "SettingGeneral"            => "_Page/SettingGeneral/SettingGeneral.js",
            "EntitasAkses"              => "_Page/EntitasAkses/EntitasAkses.js",
            "ApiDoc"                    => "_Page/ApiDoc/ApiDoc.js",
            "Help"                      => "_Page/Help/Help.js",
            "SettingEmail"              => "_Page/SettingService/SettingService.js",
            "Aktivitas"                 => "_Page/Aktivitas/Aktivitas.js"
        ];

        // Cek apakah halaman ada dalam daftar dan sertakan file JS yang sesuai
        if (!empty($_GET['Page']) && isset($scripts[$_GET['Page']])) {
            echo '<script type="text/javascript" src="' . $scripts[$_GET['Page']] . '?V='.$date_version.'"></script>';
        }
    }
    echo '<script type="text/javascript" src="_Partial/Universal.js?V='.$date_version.'"></script>';
?>