<?php
date_default_timezone_set('Asia/Jakarta');
$timeline_irfan = [
    ["tahun" => 2023, "kegiatan" => "Saya masuk ke kelas 11 SMA dan dulu belum tertarik dengan coding"],
    ["tahun" => 2024, "kegiatan" => "Pertama kali belajar coding dengan melihat tutorial video youtube"],
    ["tahun" => 2025, "kegiatan" => "Saya memutuskan masuk Kuliah di Sistem Informasi Universitas Trunodjoyo Madura"],
    ["tahun" => 2025, "kegiatan" => "Mempelajari Python dasar pada saat semester 1"],
    ["tahun" => 2025, "kegiatan" => "Proyek pertama kalkulator sederhana dengan HTML, CSS, dan JavaScript dasar"],
    ["tahun" => 2026, "kegiatan" => "Mempelajari dan memperdalam cara membuat web dan manajemen basis data pada saat semester 2"],
    ["tahun" => 2026, "kegiatan" => "Sekarang belajar menekuni tools Figma untuk desain grafis/UI"]
];

function getBadgeClass($tahun) {
    return $tahun == 2026? "badge-2026" : "badge-2025";
}

function getItemClass($tahun) {
    return $tahun == 2026? "timeline-item item-2026" : "timeline-item";
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeline Perjalanan Belajar Coding</title>
    <link rel="stylesheet" href="style/style2.css">
</head>
<body>
    <div class="wrapper">

        <div class="header">
            <h1>Perjalanan Belajar <span>Irfan</span></h1>
        </div>

        <div class="timeline">
            <?php
            $prevTahun = null;
            foreach ($timeline_irfan as $item):
                if ($item['tahun'] !== $prevTahun):
            ?>
                <div class="year-group">
                    <span class="year-group-label"><?= $item['tahun'] ?></span>
                    <div class="year-group-line"></div>
                </div>
            <?php
                    $prevTahun = $item['tahun'];
                endif;
            ?>
                <div class="<?= getItemClass($item['tahun']) ?>">
                    <div class="timeline-dot"><div class="timeline-dot-inner"></div></div>
                    <div class="card">
                        <span class="badge <?= getBadgeClass($item['tahun']) ?>"><?= $item['tahun'] ?></span>
                        <div class="kegiatan"><?= htmlspecialchars($item['kegiatan']) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <hr>

        <div class="footer">
            <a href="index.php" class="btn">← Kembali ke Profil</a>
            <a href="blog.php" class="btn">Blog Developer →</a>
        </div>

    </div>
</body>
</html>