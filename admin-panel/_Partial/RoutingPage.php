<?php
    if(empty($_GET['Page'])){
        include "_Page/Dashboard/Dashboard.php";
    }else{
        $Page=$_GET['Page'];
        //Index Halaman
        $page_arry=[
            "MyProfile"         =>  "_Page/MyProfile/MyProfile.php",
            "AksesFitur"        =>  "_Page/AksesFitur/AksesFitur.php",
            "AksesEntitas"        =>  "_Page/AksesEntitas/AksesEntitas.php",
            "Akses"             =>  "_Page/Akses/Akses.php",
            "SettingGeneral"    =>  "_Page/SettingGeneral/SettingGeneral.php",
            "EntitasAkses"      =>  "_Page/EntitasAkses/EntitasAkses.php",
            "Help"              =>  "_Page/Help/Help.php",
            "SettingEmail"      =>  "_Page/SettingService/SettingService.php",
            "Aktivitas"         =>  "_Page/Aktivitas/Aktivitas.php",
            "Error"             =>  "_Page/Error/Error.php"
        ];

        //Tangkap 'Page'
        $Page = !empty($_GET['Page']) ? $_GET['Page'] : "";

        //Kondisi Pada masing-masing Page
        if (array_key_exists($Page, $page_arry)) { 
            include $page_arry[$Page]; 
        } else { 
            include "_Page/Dashboard/Dashboard.php";
        }
    }
?>