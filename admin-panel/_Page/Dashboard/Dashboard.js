
// Fungsi Untuk Menampilkan Pemberitahuan Sistem
function ShowPemberitahuanSistem() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/ShowPemberitahuanSistem.php',
        success: function(response) {
            $('#ShowPemberitahuanSistem').hide().html(response).fadeIn(500);
            ShowAnggotaTerbaru();
        }
    });
}
// Fungsi Untuk Menampilkan Anggota Terbaru
function ShowAnggotaTerbaru() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/ShowAnggotaTerbaru.php',
        success: function(response) {
            $('#ShowAnggotaTerbaru').hide().html(response).fadeIn(500);
            ShowSimpananTerbaru();
        }
    });
}
// Fungsi Untuk Menampilkan Simpanan Terbaru
function ShowSimpananTerbaru() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/ShowSimpananTerbaru.php',
        success: function(response) {
            $('#ShowSimpananTerbaru').hide().html(response).fadeIn(500);
            ShowPinjamanTerbaru();
        }
    });
}
// Fungsi Untuk Menampilkan Pinjaman Terbaru
function ShowPinjamanTerbaru() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/ShowPinjamanTerbaru.php',
        success: function(response) {
            $('#ShowPinjamanTerbaru').hide().html(response).fadeIn(500);
        }
    });
}

// Fungsi Untuk Menampilkan Grafik
function ShowGrafikSiimpanPinjam() {
    // Fungsi untuk mengambil data dari file JSON
    $.getJSON("_Page/Dashboard/GrafikTransaksi.json", function (data) {
        // Mengolah data untuk ApexCharts
        const categories = data.map(item => item.x);
        const simpananSeries = data.map(item => parseFloat(item.ySimpanan));
        const pinjamanSeries = data.map(item => parseFloat(item.yPinjaman));

        // Konfigurasi grafik
        var options = {
            chart: {
                type: 'bar',
                height: 400
            },
            series: [
                {
                    name: 'Simpanan',
                    data: simpananSeries
                },
                {
                    name: 'Pinjaman',
                    data: pinjamanSeries
                }
            ],
            xaxis: {
                categories: categories
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
                    }
                }
            },
            dataLabels: {
                enabled: false // Menonaktifkan label nilai pada bar
            }
        };

        // Inisialisasi grafik
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    });
}

// Fungsi untuk menampilkan jam digital
function tampilkanJam() {
    const waktu = new Date();
    let jam = waktu.getHours().toString().padStart(2, '0');
    let menit = waktu.getMinutes().toString().padStart(2, '0');
    let detik = waktu.getSeconds().toString().padStart(2, '0');

    $('#jam_menarik').text(`${jam}:${menit}:${detik}`);
}

// Fungsi untuk menampilkan tanggal
function tampilkanTanggal() {
    const waktu = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const tanggal = waktu.toLocaleDateString('id-ID', options);
    
    $('#tanggal_menarik').text(tanggal);
}

//Ketika Halaman Dashboard MunculPertama Kali
$(document).ready(function () {
    //Menampilkan Data Pertama Kali
    ShowGrafikSiimpanPinjam();

    //Jam Menarik
    tampilkanTanggal(); // Tampilkan tanggal saat halaman dimuat
    tampilkanJam();     // Tampilkan jam pertama kali
    setInterval(tampilkanJam, 1000); // Perbarui jam setiap detik
});