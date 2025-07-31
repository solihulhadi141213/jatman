<!-- <section class="hero-section d-flex align-items-center text-white text-center">
    <div class="container">
        <h3 class="lead fs-2">Selamat Datang</h3>
        <h1 class="display-4 fw-bold title_segment">Di RSU El-Syifa Kuningan</h1>
        <h2 class="lead fs-2 mt-3">
            IGD 24 JAM : (0232) 876240 / +6289601154726
        </h2>
        <div class="social-icons mt-4 d-flex justify-content-center gap-3">
            <a href="https://wa.me/62xxxxxxxxxxx" target="_blank" class="btn-social">
                <i class="bi bi-whatsapp"></i>
            </a>
            <a href="https://www.instagram.com/rsuelsyifa" target="_blank" class="btn-social">
                <i class="bi bi-instagram"></i>
            </a>
            <a href="https://www.youtube.com/@rsuelsyifa" target="_blank" class="btn-social">
                <i class="bi bi-youtube"></i>
            </a>
            <a href="https://www.facebook.com/rsuelsyifa" target="_blank" class="btn-social">
                <i class="bi bi-facebook"></i>
            </a>
        </div>
    </div>
</section> -->


<div id="carouselHero" class="carousel slide" data-bs-ride="carousel">
    <!-- Indicators -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselHero" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#carouselHero" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#carouselHero" data-bs-slide-to="2"></button>
    </div>
    
    <!-- Slides -->
    <div class="carousel-inner">
        <?php
            // Menampilkan Hero 
            if(!empty($arry_static['hero'])){
                $no=1;
                foreach($arry_static['hero'] as $hero_list){
                    $hero_order=$hero_list['order'];
                    $hero_image=$hero_list['image'];
                    $hero_title=$hero_list['title'];
                    $hero_sub_title=$hero_list['sub_title'];
                    $hero_button=$hero_list['button'];
                    //Buka Button
                    $hero_show_button=$hero_button['show_button'];
                    $hero_button_url=$hero_button['button_url'];
                    $hero_button_label=$hero_button['button_label'];
                    //Jika Baris 1
                    if($no==1){
                        $active="active";
                    }else{
                        $active="";
                    }
                    //Tampilkan Berdasarkan tipe
                    if($hero_show_button==false){
                        echo '
                            <div class="carousel-item '.$active.'" data-bs-interval="5000">
                                <img src="assets/img/_component/'.$hero_image.'" class="d-block w-100" alt="Slide '.$hero_order.'">
                                <div class="carousel-caption">
                                    <h5>'.$hero_title.'</h5>
                                    <h3>'.$hero_sub_title.'</h3></p>
                                </div>
                            </div>
                        ';
                    }else{
                        echo '
                            <div class="carousel-item '.$active.'" data-bs-interval="5000">
                                <img src="assets/img/_component/'.$hero_image.'" class="d-block w-100" alt="Slide '.$hero_order.'">
                                <div class="carousel-caption">
                                    <h5>'.$hero_title.'</h5>
                                    <h3>'.$hero_sub_title.'</h3></p>
                                    <a href="'.$base_url.''.$hero_button_url.'" class="btn-jkn mt-3 d-inline-block show_transisi" target-link="">
                                        '.$hero_button_label.'
                                    </a>
                                </div>
                            </div>
                        ';
                    }
                    $no++;
                }
            }
        ?>
    </div>
    
    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselHero" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselHero" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>
<!-- VISI MISI -->
<div class="section bg-white">
    <div class="container my-5">
        <div class="row mb-3">
            <div class="col-12 text-center">
                <h2 class="h1 mb-4 title_segment_dark">
                    <?php echo "$setting_visi_misi_title"; ?>
                </h2>
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-md-6 show_transisi">
                <p>
                    <i>
                        <b>Visi : </b><br>
                        <?php echo "$setting_visi_misi_visi"; ?>
                    </i>
                </p>
            </div>
            <div class="col-md-6 show_transisi">
                <p>
                    <i>
                        <b>Misi :</b>
                        <?php echo "$setting_visi_misi_misi"; ?>
                    </i>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- BERITA DAN ARTIKEL -->
<div class="section">
    <div class="container my-5">
        <div class="row mb-3">
            <div class="col-12 text-center">
                <span class="h1 mb-4 title_segment_dark">
                    <?php echo "$setting_berita_artikel_title"; ?>
                </span>
                <p><i><?php echo "$setting_berita_artikel_subtitle"; ?></i></p>
            </div>
        </div>
        <div class="row mb-5">
            <?php
                $limit_berita = $setting_berita_artikel_limit;
                $sql_berita = "SELECT * FROM  blog WHERE publish=1 ORDER BY datetime_creat DESC LIMIT :limit";
                $stmt_berita = $Conn->prepare($sql_berita);
                $stmt_berita->bindParam(':limit', $limit_berita, PDO::PARAM_INT);
                $stmt_berita->execute();
                $berita_list = $stmt_berita->fetchAll();
                if (count($berita_list) > 0) {
                    foreach ($berita_list as $berita_artikel) {
                        $date_time_creat_blog=date('d/m/Y',strtotime($berita_artikel['datetime_creat']));
                        echo '
                            <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-3">
                                <div class="card h-100 d-flex flex-column show_transisi" style="width: 100%;">
                                    <div class="img-square-wrapper">
                                        <img src="'.$base_url.'image_proxy.php?segment=Artikel&image_name='.$berita_artikel['cover'].'" class="card-img-top" alt="...">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="'.$base_url.'/Blog?id='.$berita_artikel['id_blog'].'" class="text text-decoration-none">'.$berita_artikel['title_blog'].'</a>
                                        </h5>
                                        <p class="card-text">'.$date_time_creat_blog.'</p>
                                    </div>
                                </div>
                            </div>
                        ';
                    }
                }
            ?>
        </div>
        <div class="row mb-3">
            <div class="col-12 text-center align-content-center">
                <button type="button" class="baca_selengkapnya" target-link="<?php echo $base_url; ?>Blog">
                    Lihat Selengkapnya
                </button>
            </div>
        </div>
    </div>
</div>
<!-- TAUTAN/LINK PENDAFTARAN -->
<div class="section pendaftaran_antrian">
    <div class="container my-5 py-4">
        <div class="row align-items-center">
            <div class="col-md-12 text-center mb-3">
                <p class="lead text-dark fw-normal show_transisi">
                    Bergabung Dengan Jatman Jabar Dan Ikuti Berbagai Kegiatan Dan Program Menarik
                </p>
            </div>
            <div class="col-md-12 text-center mb-3">
                <a href="/Pendaftaran" class="btn-jkn mt-3 d-inline-block show_transisi">
                    Bergabung Sekarang
                </a>
            </div>
        </div>
    </div>
</div>

<!-- GOOGLE MAP -->
 <?php
    if(!empty($arry_static['google_map'])){
        echo '
            <div class="container-fluid px-0 g-0 mb-0">
                <iframe src="'.$arry_static['google_map']['src'].'" 
                width="'.$arry_static['google_map']['width'].'" 
                height="'.$arry_static['google_map']['height'].'" 
                style="'.$arry_static['google_map']['style'].'" 
                allowfullscreen="'.$arry_static['google_map']['allowfullscreen'].'" 
                loading="'.$arry_static['google_map']['loading'].'" 
                referrerpolicy="'.$arry_static['google_map']['referrerpolicy'].'" class="'.$arry_static['google_map']['class'].'">
                </iframe>
            </div>
        ';
    }
 ?>
