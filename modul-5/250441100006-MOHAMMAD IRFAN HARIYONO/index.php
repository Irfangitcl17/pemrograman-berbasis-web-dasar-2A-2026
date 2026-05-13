<?php
session_start();

if (function_exists('opcache_reset')) {
    opcache_reset();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION["riwayat_profil"])) {
    $_SESSION["riwayat_profil"] = [];
}
function prosesDanTampilkanData($dataPost) {
    if (!empty($dataPost['nama']) && !empty($dataPost['frameworks']) && !empty($dataPost['pengalaman'])) {
        $bidang   = isset($dataPost['bidang']) ? htmlspecialchars($dataPost['bidang']) : '-';
        $level    = isset($dataPost['skill_level']) ? htmlspecialchars($dataPost['skill_level']) : '-';
        $cerita   = htmlspecialchars($dataPost['pengalaman']);

        $arr_framework  = array_map('trim', explode(",", $dataPost['frameworks']));
        $count_framework = count($arr_framework);

        $tools_dipilih = isset($dataPost['tools'])
            ? implode(", ", array_map('htmlspecialchars', $dataPost['tools']))
            : "Tidak ada tools dipilih";

        $pesan_skill = "";
        if ($count_framework > 2) {
            $pesan_skill = "
                <div class='skill-notice'>
                    <span>&#10003;</span>
                    <strong>Skill Anda cukup luas di bidang development!</strong>
                </div>";
        }

        return "
        <div class='hasil-box'>
            <h3>Data Berhasil Diproses</h3>
            <table class='hasil-table'>
            <tr><td>Framework / Tools</td><td>" . implode(" &nbsp;|&nbsp; ", $arr_framework) . "</td></tr>
                <tr><td>Bidang Minat</td><td>$bidang</td></tr>
                <tr><td>Tingkat Skill</td><td>$level</td></tr>
                <tr><td>Tools Penunjang</td><td>$tools_dipilih</td></tr>
            </table>
            <h4>Pengalaman Developer</h4>
            <p class='hasil-quote'>\"$cerita\"</p>
            $pesan_skill
        </div>";
    } else {
        return "<div class='error-msg'>Gagal: Pastikan semua kolom wajib telah diisi!</div>";
    }
}

$pesan_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hasil_baru = prosesDanTampilkanData($_POST);
    if (strpos($hasil_baru, 'error-msg') !== false) {
        $pesan_error = $hasil_baru;
    } else {
        $_SESSION['riwayat_profil'] [] = $hasil_baru;
    }
}

$hasil_output = "";

if (!empty($pesan_error)) {
    $hasil_output .= $pesan_error;
}

if (!empty($_SESSION["riwayat_profil"])) {
    foreach (array_reverse($_SESSION["riwayat_profil"]) as $item) {
        $hasil_output .= $item;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Developer - Irfan</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
<div class="wrapper">

    <div class="page-header">
        <h1>Profil Interaktif Developer Pemula</h1>
    </div>

    <table class="info-table">
        <tr><td colspan="2" class="table-head">Profil Interaktif Developer Pemula</td></tr>
        <tr><td>Nama</td><td>Mohammad Irfan Hariyono</td></tr>
        <tr><td>ID Developer</td><td>25-006</td></tr>
        <tr><td>Kota / Tgl Lahir</td><td>Nganjuk / 17 November 2006</td></tr>
        <tr><td>Email</td><td><a href="mailto:irvanhariono278@gmail.com">irvanhariono278@gmail.com</a></td></tr>
        <tr><td>No. WhatsApp</td><td>087715890651</td></tr>
    </table>

    <div class="section-title">Form Isian Dinamis</div>

    <div class="form-card">
        <form method="post" action="">

            <input type="hidden" name="nama"   value="Mohammad Irfan Hariyono">
            <input type="hidden" name="id_dev" value="2026">
            <input type="hidden" name="email"  value="irvanhariono278@gmail.com">

            <div class="form-group">
                <label class="form-label" for="frameworks">
                    Framework / Tools yang Dikuasai <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="frameworks"
                    name="frameworks"
                    class="form-input"
                    placeholder="Contoh: Figma, Canva, Python, SQL"
                    required>
            </div>

            <div class="form-group">
                <label class="form-label" for="pengalaman">
                    Cerita Pengalaman <span class="required">*</span>
                </label>
                <textarea
                    id="pengalaman"
                    name="pengalaman"
                    class="form-textarea"
                    placeholder="Ceritakan pengalamanmu mengelola GDGoC atau proyek lainnya..."
                    required></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Tools Penunjang</label>
                <div class="check-group">
                    <label class="check-label"><input type="checkbox" name="tools[]" value="VS Code"> VS Code</label>
                    <label class="check-label"><input type="checkbox" name="tools[]" value="GitHub"> GitHub</label>
                    <label class="check-label"><input type="checkbox" name="tools[]" value="Figma"> Figma</label>
                    <label class="check-label"><input type="checkbox" name="tools[]" value="Postman"> Postman</label>
                    <label class="check-label"><input type="checkbox" name="tools[]" value="Antigravity"> Antigravity</label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Minat Bidang <span class="required">*</span></label>
                <div class="radio-group">
                    <label class="radio-label"><input type="radio" name="bidang" value="Frontend" required> Frontend</label>
                    <label class="radio-label"><input type="radio" name="bidang" value="Backend"> Backend</label>
                    <label class="radio-label"><input type="radio" name="bidang" value="Fullstack"> Fullstack</label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="skill_level">Tingkat Skill Coding</label>
                <select id="skill_level" name="skill_level" class="form-select">
                    <option value="Dasar">Dasar</option>
                    <option value="Cukup">Cukup</option>
                    <option value="Profesional">Profesional</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Kirim Data Profil &#8594;</button>
        </form>
    </div>

    <?php echo $hasil_output; ?>

    <div class="page-footer">
        <a href="timeline.php" class="btn-nav">Lihat Timeline Belajar &#8594;</a>
    </div>

</div>
</body>
</html>