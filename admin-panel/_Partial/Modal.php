<?php
    include "_Page/Logout/ModalLogout.php";
    if(!empty($_GET['Page'])){
        $Page=$_GET['Page'];
        
        // Daftar halaman dan modal yang terkait
        $modals = [
            "MyProfile"             => "_Page/MyProfile/ModalMyProfile.php",
            "AksesFitur"            => "_Page/AksesFitur/ModalAksesFitur.php",
            "AksesEntitas"          => "_Page/AksesEntitas/ModalAksesEntitas.php",
            "Akses"                 => "_Page/Akses/ModalAkses.php",
            "SettingGeneral"        => "_Page/SettingGeneral/ModalSettingGeneral.php",
            "SettingEmail"          => "_Page/SettingEmail/ModalSettingEmail.php",
            "SettingKoneksiWeb"     => "_Page/SettingKoneksiWeb/ModalSettingKoneksiWeb.php",
            "Metatag"               => "_Page/Metatag/ModalMetatag.php",
            "Favicon"               => "_Page/Favicon/ModalFavicon.php",
            "Help"                  => "_Page/Help/ModalHelp.php",
            "Aktivitas"             => "_Page/Aktivitas/ModalAktivitas.php"
        ];

        // Cek apakah halaman memiliki modal terkait dan sertakan file modalnya
        if (!empty($_GET['Page']) && isset($modals[$_GET['Page']])) {
            include $modals[$_GET['Page']];
        }
    }
?>