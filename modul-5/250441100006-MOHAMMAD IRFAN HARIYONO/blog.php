<?php
date_default_timezone_set('Asia/Jakarta');

$artikel_blog = array(
    "html-css" => array(
        "judul"   => "Pengalaman Pertama Belajar Coding Dasar",
        "tanggal" => "17 Januari 2024",
        "isi"     => "Pada bulan Januari 2024, di saat saya duduk di bangku kelas 3 SMA, tidak sengaja menemukan konten coding dan belum tahu pakai apa dulu, alhasil aku ngulik lah yang namanya HTML & CSS juga Javascript dasar. Dari situ mulai tertarik karena kayaknya keren kalau aku bisa. Nah dari situ aku mulai tertarik untuk mempelajarinya. (Ya kalau sekarang susah-susah gampang kalau belajar, karena aku kehalang yang namanya device yaitu laptop hehe :)",
        "img"     => "html-css-js.png",
        "link"    => "https://github.com/Irfangitcl17"
    ),
    "python-sql" => array(
        "judul"   => "Belajar Pengantar Basis Data",
        "tanggal" => "05 April 2026",
        "isi"     => "Mempelajari database SQL seperti Query yang begitu kompleks, disemester ini saya mengahadapi matkul dan juga praktikum yang belum begitu familiar jadinya harus adaptasi dulu.",
        "img"     => "DB.jpg",
        "link"    => "https://www.w3schools.com/sql/"
    ),
    "error-pertama" => array(
        "judul" => "Error Pertama Kali Saya",
        "tanggal" => "05 Oktober 2025",
        "isi" => "Saya tidak lupa pada error pertama pada saat belajar bahasa pemrograman python pada saat praktikum Algoritma Pemrograman, mulai dari situ saya belajar memahami atau istilah nya debugging dibantu dengan assisten praktikum pada saat itu.",
        "img" => "asis.jpg",
        "link" => "https://github.com/Irfangitcl17"
    ),
    "gdgoc" => array(
        "judul"   => "Menjadi Anggota Media Creative di GDG on Campus UTM",
        "tanggal" => "20 Oktober 2025",
        "isi"     => "Bergabung dengan GDG on Campus UTM memberikan saya banyak ilmu tentang desain menggunakan Figma dan software lainnya untuk kebutuhan konten komunitas.",
        "img"     => "gdgoc.jpg",
        "link"    => "https://gdg.community.dev/"
    )
);

$motivasi = array(
    array("teks" => "Coding bukan hanya tentang baris kode, tapi tentang solusi.",        "oleh" => "Melinda Gates"),
    array("teks" => "Setiap error adalah pelajaran untuk menjadi developer yang lebih baik.", "oleh" => "All Programmers"),
    array("teks" => "Konsistensi adalah kunci dalam menguasai teknologi baru.",             "oleh" => "Erick"),
    array("teks" => "Mahasiswa SI UTM harus berani berinovasi dan bereksperimen.",          "oleh" => "Pak Dosen")
);
$q = $motivasi[array_rand($motivasi)];

$aktif_slug = (isset($_GET['post']) && array_key_exists($_GET['post'], $artikel_blog))
    ? $_GET['post']
    : null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Developer - Irfan</title>
    <link rel="stylesheet" href="style/style3.css">
</head>
<body>
<div class="wrapper">

    <div class="page-header">
        <h1>Blog <span>Reflektif</span> Irfan</h1>
    </div>

    <div class="quote-box">
        "<?php echo htmlspecialchars($q['teks']); ?>"
        <?php if (!empty($q['oleh'])) : ?>
            <span>&#8212; <?php echo htmlspecialchars($q['oleh']); ?></span>
        <?php endif; ?>
    </div>

    <div class="section-title">Daftar Artikel</div>

    <div class="artikel-list">
        <?php foreach ($artikel_blog as $slug => $data) : ?>
            <a href="blog.php?post=<?php echo $slug; ?>"
               class="artikel-item<?php echo ($aktif_slug === $slug) ? ' active' : ''; ?>">
                <div>
                    <div class="artikel-item-judul"><?php echo htmlspecialchars($data['judul']); ?></div>
                    <div class="artikel-item-tanggal"><?php echo $data['tanggal']; ?></div>
                </div>
                <span class="artikel-item-arrow">&#8250;</span>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="section-title">Detail Artikel</div>

    <div class="post-card">
        <?php if ($aktif_slug !== null) :
            $post = $artikel_blog[$aktif_slug];
        ?>
            <h2><?php echo htmlspecialchars($post['judul']); ?></h2>
            <span class="post-meta">Diposting: <?php echo $post['tanggal']; ?></span>
            <p class="post-isi"><?php echo htmlspecialchars($post['isi']); ?></p>

            <?php if (!empty($post['img'])) : ?>
                <img src="img/<?php echo htmlspecialchars($post['img']); ?>"
                     alt="Dokumentasi <?php echo htmlspecialchars($post['judul']); ?>"
                     class="post-img">
            <?php endif; ?>

            <a href="<?php echo htmlspecialchars($post['link']); ?>"
               target="_blank" rel="noopener" class="post-ref">
                Referensi &rarr;
            </a>

        <?php else : ?>
            <p class="post-empty">Klik salah satu judul artikel di atas untuk membaca refleksi saya.</p>
        <?php endif; ?>
    </div>

    <div class="page-footer">
        <div style="display:flex; gap:8px;">
            <a href="timeline.php" class="btn-nav">&larr; Timeline</a>
            <a href="index.php"    class="btn-nav">Profil</a>
        </div>
    </div>

</div>
</body>
</html>