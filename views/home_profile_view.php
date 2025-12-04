<!-- about us -->
<section class="about-us py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
                <div class="about-img-wrapper">
                    <div class="img-decoration"></div>
                    <img src="uploads/images.jpeg" class="img-fluid rounded-4 position-relative z-2" alt="Tentang Lab SE">
                </div>
            </div>

            <div class="col-lg-6 ps-lg-2">
                <h2 class="section-title fw-semibold">About Us</h2>
                <p class="lead text-secondary mb-4">
                    Laboratorium Software Engineering (Lab SE) adalah pusat inovasi yang menjembatani teori akademis dengan kebutuhan industri nyata.
                </p>
                <p class="text-muted mb-4" style="text-align: justify; line-height: 1.7;">
                    Kami mempersiapkan mahasiswa untuk menjadi engineer yang kompeten melalui riset mendalam, pengembangan teknologi terkini, dan kolaborasi tim yang solid. Di sini, kode bukan sekadar baris perintah, melainkan solusi untuk masa depan.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Visi & Misi -->
<section id="visi-misi" class="py-5">
    <div class="container">
        <div class="col-12 text-center mb-5">
            <h2 class="display-6 fw-bold">Visi & Misi</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="brand-card">
                    <div class="card-body text-center>
                        <div class="content-body">
                            <?php if (!empty($visi_misi['content'])): ?>
                                <?= $visi_misi['content'] ?>
                            <?php else: ?>
                                Konten visi dan misi belum tersedia.
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Roadmap -->
<section id="roadmap">
    <div class="row mt-5 pt-4">

        <div class="col-12 text-center mb-5">
            <h2 class="display-6 fw-bold">Roadmap</h2>
            <p class="lead text-muted">
                Lab SE telah merancang tahapan penelitian jangka panjang untuk memastikan kontribusi yang berkelanjutan:
            </p>
        </div>

        <div class="col-12">
            <div class="timeline roadmap-section">

                <!-- Fase 1 -->
                <div class="timeline-container timeline-left">
                    <div class="timeline-content">
                        <span class="timeline-date">2024 - 2025</span>
                        <h4>Fondasi & Standarisasi</h4>
                        <p>
                            Fokus pada penguatan fundamental rekayasa perangkat lunak dan peningkatan kualitas kode.
                        </p>
                        <ul class="text-muted small mt-2 ps-3">
                            <li>Pengembangan framework untuk standardisasi proses Software Quality Assurance (SQA).</li>
                            <li>Implementasi metode Agile & Scrum dalam lingkungan akademis.</li>
                            <li>Riset terkait automated testing tools untuk meningkatkan efisiensi dan akurasi pengujian.</li>
                        </ul>
                    </div>
                </div>

                <!-- Fase 2 -->
                <div class="timeline-container timeline-right">
                    <div class="timeline-content">
                        <span class="timeline-date">2026 - 2027</span>
                        <h4>Integrasi & Skalabilitas</h4>
                        <p>
                            Fokus pada arsitektur sistem modern dan integrasi berkelanjutan.
                        </p>
                        <ul class="text-muted small mt-2 ps-3">
                            <li>Pengembangan Arsitektur Microservices & Cloud-Native Apps.</li>
                            <li>Implementasi DevOps dan CI/CD Pipeline yang efisien.</li>
                            <li>Riset keamanan aplikasi (AppSec) dan penerapan Secure Coding.</li>
                        </ul>
                    </div>
                </div>

                <!-- Fase 3 -->
                <div class="timeline-container timeline-left">
                    <div class="timeline-content">
                        <span class="timeline-date">2028 - 2030</span>
                        <h4>Kecerdasan & Otomasi Lanjut</h4>
                        <p>
                            Fokus pada pemanfaatan teknologi cerdas dalam rekayasa perangkat lunak.
                        </p>
                        <ul class="text-muted small mt-2 ps-3">
                            <li>AI-Driven Software Engineering (AI untuk generate dan optimasi kode).</li>
                            <li>Blockchain untuk integritas dan keaslian data sistem informasi.</li>
                            <li>Pengembangan Smart Systems berbasis IoT.</li>
                        </ul>
                    </div>
                </div>

                <!-- next -->
                <div class="timeline-container timeline-right">
                    <div class="timeline-content">
                        <span class="timeline-date">NEXT</span>
                        <h4>Future Innovation</h4>
                        <p>
                            Eksplorasi teknologi kuantum dan paradigma komputasi masa depan
                            untuk solusi perangkat lunak adaptif.
                        </p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>
