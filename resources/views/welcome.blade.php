<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="Sistem Informasi Penjaminan Mutu Internal Politeknik Negeri Banyuwangi."
    >

    <meta
        name="theme-color"
        content="#0b2f59"
    >

    <title>
        Sistem Informasi SPMI | Politeknik Negeri Banyuwangi
    </title>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/landing.css') }}"
    >

    <script>
        document.documentElement.classList.add('js');
    </script>
</head>

<body>

{{-- =========================================================
     NAVBAR
========================================================= --}}

<header
    class="landing-header"
    id="landingHeader"
>
    <div class="landing-container navbar-inner">

        <a
            href="{{ route('landing') }}"
            class="brand"
        >
            <span class="brand-logo-wrapper">

                <img
                    src="{{ asset('images/poliwangi.png') }}"
                    alt="Logo Politeknik Negeri Banyuwangi"
                    class="brand-logo"
                >

            </span>

            <span class="brand-text">

                <strong>
                    Sistem Informasi SPMI
                </strong>

                <small>
                    Politeknik Negeri Banyuwangi
                </small>

            </span>
        </a>

        <button
            type="button"
            class="mobile-menu-button"
            id="mobileMenuButton"
            aria-label="Buka menu"
            aria-expanded="false"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav
            class="landing-navigation"
            id="landingNavigation"
        >
            <a
                href="#beranda"
                class="nav-link active"
            >
                Beranda
            </a>

            <a
                href="#tentang"
                class="nav-link"
            >
                Tentang
            </a>

            <a
                href="#fitur"
                class="nav-link"
            >
                Fitur
            </a>

            <a
                href="#alur"
                class="nav-link"
            >
                Alur Sistem
            </a>

            <a
                href="#pengguna"
                class="nav-link"
            >
                Pengguna
            </a>

            <a
                href="{{ route('login') }}"
                class="navbar-login-button"
            >
                <i class="bi bi-box-arrow-in-right"></i>

                <span>
                    Masuk Sistem
                </span>
            </a>
        </nav>

    </div>
</header>

<main>

    {{-- =====================================================
         HERO
    ====================================================== --}}

    <section
        class="hero-section"
        id="beranda"
    >
        <div class="hero-circle hero-circle-one"></div>
        <div class="hero-circle hero-circle-two"></div>

        <div class="landing-container hero-grid">

            <div class="hero-content reveal">

                <div class="hero-badge">

                    <span class="hero-badge-dot"></span>

                    Sistem Penjaminan Mutu Terintegrasi

                </div>

                <h1>
                    Mutu Terjaga,
                    <span>Kinerja Terukur.</span>
                </h1>

                <p class="hero-description">
                    Platform digital untuk mengelola Standar Mutu,
                    Penerapan Standar, Audit Mutu Internal, temuan,
                    tindak lanjut, dan laporan dalam satu sistem
                    yang terstruktur.
                </p>

                <div class="hero-actions">

                    <a
                        href="{{ route('login') }}"
                        class="primary-button"
                    >
                        <span>
                            Masuk ke Sistem
                        </span>

                        <i class="bi bi-arrow-right"></i>
                    </a>

                    <a
                        href="#tentang"
                        class="secondary-button"
                    >
                        <i class="bi bi-play-circle"></i>

                        <span>
                            Pelajari Sistem
                        </span>
                    </a>

                </div>

                <div class="hero-benefits">

                    <div class="hero-benefit">

                        <span>
                            <i class="bi bi-shield-check"></i>
                        </span>

                        <div>
                            <strong>
                                Terstruktur
                            </strong>

                            <small>
                                Alur sesuai peran
                            </small>
                        </div>

                    </div>

                    <div class="hero-benefit">

                        <span>
                            <i class="bi bi-graph-up-arrow"></i>
                        </span>

                        <div>
                            <strong>
                                Terukur
                            </strong>

                            <small>
                                Kinerja mudah dipantau
                            </small>
                        </div>

                    </div>

                    <div class="hero-benefit">

                        <span>
                            <i class="bi bi-file-earmark-check"></i>
                        </span>

                        <div>
                            <strong>
                                Terdokumentasi
                            </strong>

                            <small>
                                Bukti tersimpan rapi
                            </small>
                        </div>

                    </div>

                </div>

            </div>

            <div class="hero-visual reveal reveal-delay-one">

                <div class="hero-image-wrapper">

                    <img
                        src="{{ asset('images/gedung-poliwangi.jpeg') }}"
                        alt="Gedung Kuliah Terpadu Politeknik Negeri Banyuwangi"
                        class="hero-image"
                    >

                    <div class="hero-image-overlay"></div>

                    <div class="hero-image-caption">

                        <span>
                            <i class="bi bi-geo-alt-fill"></i>
                        </span>

                        <div>
                            <small>
                                Politeknik Negeri Banyuwangi
                            </small>

                            <strong>
                                Kampus Vokasi Berorientasi Mutu
                            </strong>
                        </div>

                    </div>

                </div>

                <div class="floating-card floating-card-top">

                    <span class="floating-card-icon blue">
                        <i class="bi bi-clipboard2-data"></i>
                    </span>

                    <div>
                        <small>
                            Audit Mutu Internal
                        </small>

                        <strong>
                            Terintegrasi
                        </strong>
                    </div>

                </div>

                <div class="floating-card floating-card-bottom">

                    <span class="floating-card-icon green">
                        <i class="bi bi-check2-circle"></i>
                    </span>

                    <div>
                        <small>
                            Tindak Lanjut
                        </small>

                        <strong>
                            Terkendali
                        </strong>
                    </div>

                </div>

            </div>

        </div>

        <div class="landing-container hero-statistics reveal">

            <div class="statistic-item">
                <strong>
                    01
                </strong>

                <span>
                    Platform Terpadu
                </span>
            </div>

            <div class="statistic-divider"></div>

            <div class="statistic-item">
                <strong>
                    03
                </strong>

                <span>
                    Peran Pengguna
                </span>
            </div>

            <div class="statistic-divider"></div>

            <div class="statistic-item">
                <strong>
                    05
                </strong>

                <span>
                    Tahap PPEPP
                </span>
            </div>

            <div class="statistic-divider"></div>

            <div class="statistic-item">
                <strong>
                    100%
                </strong>

                <span>
                    Dokumentasi Digital
                </span>
            </div>

        </div>

    </section>

    {{-- =====================================================
         TENTANG
    ====================================================== --}}

    <section
        class="section about-section"
        id="tentang"
    >
        <div class="landing-container about-grid">

            <div class="about-visual reveal">

                <div class="about-image-wrapper">

                    <img
                        src="{{ asset('images/gedung-poliwangi.jpeg') }}"
                        alt="Gedung Politeknik Negeri Banyuwangi"
                    >

                    <div class="about-image-overlay"></div>

                    <div class="about-highlight">

                        <span>
                            <i class="bi bi-award"></i>
                        </span>

                        <div>
                            <strong>
                                Budaya Mutu
                            </strong>

                            <small>
                                Berkelanjutan dan terdokumentasi
                            </small>
                        </div>

                    </div>

                </div>

                <div class="about-decoration"></div>

            </div>

            <div class="section-content reveal reveal-delay-one">

                <span class="section-label">
                    Tentang Sistem
                </span>

                <h2>
                    Digitalisasi proses penjaminan mutu yang lebih efektif.
                </h2>

                <p>
                    Sistem Informasi SPMI membantu pengelola mutu
                    di lingkungan Politeknik Negeri Banyuwangi bekerja
                    dalam alur yang jelas, mulai dari pengelolaan standar
                    hingga peningkatan mutu berkelanjutan.
                </p>

                <div class="about-list">

                    <article class="about-list-item">

                        <span class="about-list-icon">
                            <i class="bi bi-diagram-3"></i>
                        </span>

                        <div>
                            <strong>
                                Alur Terintegrasi
                            </strong>

                            <p>
                                Standar, indikator, penerapan, audit,
                                temuan, dan tindak lanjut saling terhubung.
                            </p>
                        </div>

                    </article>

                    <article class="about-list-item">

                        <span class="about-list-icon">
                            <i class="bi bi-people"></i>
                        </span>

                        <div>
                            <strong>
                                Kolaborasi Antarperan
                            </strong>

                            <p>
                                Admin, Auditee, dan Auditor bekerja
                                sesuai kewenangan masing-masing.
                            </p>
                        </div>

                    </article>

                    <article class="about-list-item">

                        <span class="about-list-icon">
                            <i class="bi bi-clock-history"></i>
                        </span>

                        <div>
                            <strong>
                                Riwayat Terdokumentasi
                            </strong>

                            <p>
                                Data pemeriksaan dan tindak lanjut
                                dapat ditelusuri dengan lebih mudah.
                            </p>
                        </div>

                    </article>

                </div>

            </div>

        </div>
    </section>

    {{-- =====================================================
         FITUR
    ====================================================== --}}

    <section
        class="section features-section"
        id="fitur"
    >
        <div class="landing-container">

            <div class="section-heading reveal">

                <span class="section-label">
                    Fitur Utama
                </span>

                <h2>
                    Satu sistem untuk seluruh siklus mutu.
                </h2>

                <p>
                    Pengelolaan data lebih rapi, pemeriksaan lebih
                    transparan, dan tindak lanjut lebih mudah dipantau.
                </p>

            </div>

            <div class="feature-grid">

                <article class="feature-card reveal">

                    <span class="feature-number">
                        01
                    </span>

                    <span class="feature-icon">
                        <i class="bi bi-journal-check"></i>
                    </span>

                    <h3>
                        Standar Mutu
                    </h3>

                    <p>
                        Kelola Standar Mutu, Isi Standar,
                        dan Indikator secara hierarkis.
                    </p>

                    <span class="feature-bottom-line"></span>

                </article>

                <article class="feature-card reveal reveal-delay-one">

                    <span class="feature-number">
                        02
                    </span>

                    <span class="feature-icon">
                        <i class="bi bi-calendar2-check"></i>
                    </span>

                    <h3>
                        Periode AMI
                    </h3>

                    <p>
                        Atur periode audit, unit kerja,
                        jadwal, dan Tim Auditor.
                    </p>

                    <span class="feature-bottom-line"></span>

                </article>

                <article class="feature-card reveal reveal-delay-two">

                    <span class="feature-number">
                        03
                    </span>

                    <span class="feature-icon">
                        <i class="bi bi-cloud-arrow-up"></i>
                    </span>

                    <h3>
                        Penerapan dan Bukti
                    </h3>

                    <p>
                        Auditee mengisi hasil penerapan
                        dan melampirkan bukti pendukung.
                    </p>

                    <span class="feature-bottom-line"></span>

                </article>

                <article class="feature-card reveal">

                    <span class="feature-number">
                        04
                    </span>

                    <span class="feature-icon">
                        <i class="bi bi-search"></i>
                    </span>

                    <h3>
                        Pemeriksaan Auditor
                    </h3>

                    <p>
                        Auditor memeriksa hasil dan bukti
                        tanpa mengubah data Auditee.
                    </p>

                    <span class="feature-bottom-line"></span>

                </article>

                <article class="feature-card reveal reveal-delay-one">

                    <span class="feature-number">
                        05
                    </span>

                    <span class="feature-icon">
                        <i class="bi bi-exclamation-diamond"></i>
                    </span>

                    <h3>
                        Temuan dan Tanggapan
                    </h3>

                    <p>
                        Kelola temuan audit dan tanggapan
                        Auditee secara terstruktur.
                    </p>

                    <span class="feature-bottom-line"></span>

                </article>

                <article class="feature-card reveal reveal-delay-two">

                    <span class="feature-number">
                        06
                    </span>

                    <span class="feature-icon">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                    </span>

                    <h3>
                        Laporan AMI
                    </h3>

                    <p>
                        Dokumentasikan hasil pemeriksaan
                        dalam laporan audit terpadu.
                    </p>

                    <span class="feature-bottom-line"></span>

                </article>

            </div>

        </div>
    </section>

    {{-- =====================================================
         ALUR SISTEM
    ====================================================== --}}

    <section
        class="section workflow-section"
        id="alur"
    >
        <div class="landing-container workflow-grid">

            <div class="workflow-introduction reveal">

                <span class="section-label light">
                    Alur Sistem
                </span>

                <h2>
                    Proses jelas dari standar hingga tindak lanjut.
                </h2>

                <p>
                    Proses Audit Mutu Internal tidak berhenti pada
                    temuan, tetapi berlanjut sampai tindak lanjut,
                    pengendalian, dan peningkatan mutu.
                </p>

                <a
                    href="{{ route('login') }}"
                    class="workflow-login"
                >
                    Mulai menggunakan sistem

                    <i class="bi bi-arrow-up-right"></i>
                </a>

            </div>

            <div class="workflow-list reveal reveal-delay-one">

                <article class="workflow-item">

                    <span class="workflow-number">
                        01
                    </span>

                    <div>
                        <strong>
                            Penetapan Standar
                        </strong>

                        <p>
                            Admin menyiapkan Standar Mutu,
                            Isi Standar, dan Indikator.
                        </p>
                    </div>

                </article>

                <article class="workflow-item">

                    <span class="workflow-number">
                        02
                    </span>

                    <div>
                        <strong>
                            Penerapan oleh Auditee
                        </strong>

                        <p>
                            Auditee mengisi hasil penerapan
                            beserta bukti pendukung.
                        </p>
                    </div>

                </article>

                <article class="workflow-item">

                    <span class="workflow-number">
                        03
                    </span>

                    <div>
                        <strong>
                            Pemeriksaan Auditor
                        </strong>

                        <p>
                            Auditor memeriksa hasil dan bukti
                            yang telah dikirim Auditee.
                        </p>
                    </div>

                </article>

                <article class="workflow-item">

                    <span class="workflow-number">
                        04
                    </span>

                    <div>
                        <strong>
                            Temuan dan Tanggapan
                        </strong>

                        <p>
                            Auditor menambahkan temuan dan
                            Auditee memberikan tanggapan.
                        </p>
                    </div>

                </article>

                <article class="workflow-item">

                    <span class="workflow-number">
                        05
                    </span>

                    <div>
                        <strong>
                            Pengendalian dan Peningkatan
                        </strong>

                        <p>
                            Tindak lanjut diverifikasi dan menjadi
                            dasar peningkatan mutu.
                        </p>
                    </div>

                </article>

            </div>

        </div>
    </section>

    {{-- =====================================================
         PENGGUNA
    ====================================================== --}}

    <section
        class="section user-section"
        id="pengguna"
    >
        <div class="landing-container">

            <div class="section-heading reveal">

                <span class="section-label">
                    Pengguna Sistem
                </span>

                <h2>
                    Tiga peran, satu tujuan mutu.
                </h2>

                <p>
                    Setiap pengguna memperoleh akses sesuai
                    tugas dan tanggung jawabnya.
                </p>

            </div>

            <div class="role-grid">

                <article class="role-card reveal">

                    <span class="role-icon">
                        <i class="bi bi-person-gear"></i>
                    </span>

                    <span class="role-category">
                        Pengelola Sistem
                    </span>

                    <h3>
                        Admin
                    </h3>

                    <p>
                        Mengelola master data, pengguna, standar,
                        periode, jadwal, dan tim audit.
                    </p>

                    <ul>
                        <li>
                            <i class="bi bi-check2"></i>
                            Kelola Standar Mutu
                        </li>

                        <li>
                            <i class="bi bi-check2"></i>
                            Kelola Periode AMI
                        </li>

                        <li>
                            <i class="bi bi-check2"></i>
                            Kelola Tim dan Jadwal
                        </li>
                    </ul>

                </article>

                <article class="role-card featured reveal reveal-delay-one">

                    <span class="featured-badge">
                        Peran Utama
                    </span>

                    <span class="role-icon">
                        <i class="bi bi-building-check"></i>
                    </span>

                    <span class="role-category">
                        Pelaksana Standar
                    </span>

                    <h3>
                        Auditee
                    </h3>

                    <p>
                        Mengisi penerapan standar, bukti pendukung,
                        dan tanggapan atas temuan audit.
                    </p>

                    <ul>
                        <li>
                            <i class="bi bi-check2"></i>
                            Isi Hasil Penerapan
                        </li>

                        <li>
                            <i class="bi bi-check2"></i>
                            Lampirkan Bukti
                        </li>

                        <li>
                            <i class="bi bi-check2"></i>
                            Berikan Tanggapan
                        </li>
                    </ul>

                </article>

                <article class="role-card reveal reveal-delay-two">

                    <span class="role-icon">
                        <i class="bi bi-clipboard2-pulse"></i>
                    </span>

                    <span class="role-category">
                        Pemeriksa Mutu
                    </span>

                    <h3>
                        Auditor
                    </h3>

                    <p>
                        Memeriksa penerapan, membuat temuan,
                        dan memverifikasi tindak lanjut.
                    </p>

                    <ul>
                        <li>
                            <i class="bi bi-check2"></i>
                            Periksa Penerapan
                        </li>

                        <li>
                            <i class="bi bi-check2"></i>
                            Kelola Temuan
                        </li>

                        <li>
                            <i class="bi bi-check2"></i>
                            Verifikasi Penyelesaian
                        </li>
                    </ul>

                </article>

            </div>

        </div>
    </section>

    {{-- =====================================================
         CTA
    ====================================================== --}}

    <section class="cta-section">

        <div class="landing-container">

            <div class="cta-card reveal">

                <img
                    src="{{ asset('images/gedung-poliwangi.jpeg') }}"
                    alt="Gedung Politeknik Negeri Banyuwangi"
                    class="cta-background"
                >

                <div class="cta-overlay"></div>

                <div class="cta-content">

                    <span class="cta-label">
                        Siap Membangun Budaya Mutu?
                    </span>

                    <h2>
                        Kelola proses SPMI dalam satu platform terintegrasi.
                    </h2>

                    <p>
                        Masuk menggunakan akun yang telah diberikan
                        sesuai dengan peran Anda.
                    </p>

                    <a
                        href="{{ route('login') }}"
                        class="cta-button"
                    >
                        <i class="bi bi-shield-lock"></i>

                        <span>
                            Masuk ke Sistem SPMI
                        </span>

                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>

        </div>

    </section>

</main>

{{-- =========================================================
     FOOTER
========================================================= --}}

<footer class="landing-footer">

    <div class="landing-container footer-grid">

        <div class="footer-about">

            <div class="footer-brand">

                <img
                    src="{{ asset('images/poliwangi.png') }}"
                    alt="Logo Poliwangi"
                >

                <div>
                    <strong>
                        Sistem Informasi SPMI
                    </strong>

                    <span>
                        Politeknik Negeri Banyuwangi
                    </span>
                </div>

            </div>

            <p>
                Mendukung pengelolaan penjaminan mutu internal
                yang terstruktur, transparan, dan berkelanjutan.
            </p>

        </div>

        <div class="footer-column">

            <h3>
                Navigasi
            </h3>

            <a href="#beranda">
                Beranda
            </a>

            <a href="#tentang">
                Tentang Sistem
            </a>

            <a href="#fitur">
                Fitur Utama
            </a>

            <a href="#alur">
                Alur Sistem
            </a>

        </div>

        <div class="footer-column footer-contact">

            <h3>
                Politeknik Negeri Banyuwangi
            </h3>

            <p>
                <i class="bi bi-geo-alt"></i>

                Jalan Raya Jember KM 13,
                Banyuwangi 68461
            </p>

            <p>
                <i class="bi bi-envelope"></i>

                poliwangi@poliwangi.ac.id
            </p>

            <p>
                <i class="bi bi-telephone"></i>

                (0333) 636780
            </p>

        </div>

    </div>

    <div class="landing-container footer-bottom">

        <span>
            &copy; {{ date('Y') }}
            Sistem Informasi SPMI Poliwangi.
        </span>

        <span>
            Mendukung peningkatan mutu berkelanjutan.
        </span>

    </div>

</footer>

<script>
    const landingHeader =
        document.getElementById('landingHeader');

    const mobileMenuButton =
        document.getElementById('mobileMenuButton');

    const landingNavigation =
        document.getElementById('landingNavigation');

    const navigationLinks =
        document.querySelectorAll('.nav-link');

    function updateHeader() {
        landingHeader.classList.toggle(
            'scrolled',
            window.scrollY > 20
        );
    }

    function closeMobileMenu() {
        mobileMenuButton.classList.remove('active');
        landingNavigation.classList.remove('open');

        mobileMenuButton.setAttribute(
            'aria-expanded',
            'false'
        );

        document.body.classList.remove('menu-open');
    }

    mobileMenuButton.addEventListener(
        'click',
        function () {
            const menuOpen =
                !landingNavigation.classList.contains('open');

            this.classList.toggle(
                'active',
                menuOpen
            );

            landingNavigation.classList.toggle(
                'open',
                menuOpen
            );

            this.setAttribute(
                'aria-expanded',
                String(menuOpen)
            );

            document.body.classList.toggle(
                'menu-open',
                menuOpen
            );
        }
    );

    navigationLinks.forEach(function (link) {
        link.addEventListener(
            'click',
            closeMobileMenu
        );
    });

    window.addEventListener(
        'scroll',
        updateHeader,
        {
            passive: true
        }
    );

    updateHeader();

    const revealObserver =
        new IntersectionObserver(
            function (entries, observer) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add('visible');

                    observer.unobserve(
                        entry.target
                    );
                });
            },
            {
                threshold: 0.12,
                rootMargin: '0px 0px -40px 0px'
            }
        );

    document
        .querySelectorAll('.reveal')
        .forEach(function (element) {
            revealObserver.observe(element);
        });

    window.addEventListener(
        'resize',
        function () {
            if (window.innerWidth > 920) {
                closeMobileMenu();
            }
        }
    );
</script>

</body>
</html>