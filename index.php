<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/ionicons@latest/dist/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>SMP dan SMA Pesantren MKGR Kertasemaya</title>
    <meta name="description" content="SMP dan SMA Pesantren MKGR Kertasemaya">
    <meta name="keywords" content="SMP dan SMA Pesantren MKGR Kertasemaya">
</head>
<style>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap");

:root { 
  --h2-font-size: 1.25rem;
  --normal-font-size: .938rem;
  --smaller-font-size: .75rem;
}

@media screen and (min-width: 968px) {
  :root {
    --h2-font-size: 2rem;
    --normal-font-size: 1rem;
    --smaller-font-size: .875rem;
  }
}

*, ::before, ::after {box-sizing: border-box;}

html {scroll-behavior: smooth;}

video {border-radius: 18px;}

::-webkit-scrollbar{-webkit-appearance:none;width:5px;height:5px}::-webkit-scrollbar-track{background:transparent}::-webkit-scrollbar-thumb{background:rgba(0,0,0,.15);border-radius:10px}::-webkit-scrollbar-thumb:hover{background:rgba(0,0,0,.35)}::-webkit-scrollbar-thumb:active{background:rgba(0,0,0,.35)}

body {margin: 3rem 0 0 0;font-family: 'Poppins', sans-serif;font-size: var(--normal-font-size);color: hsl(224, 56%, 12%);}

h1, h2, p {margin: 0;}

ul {margin: 0;padding: 0;list-style: none;}

a {text-decoration: none;}

img {max-width: 100%;height: auto;display: block;}

.section-title {position: relative;font-size: var(--h2-font-size);color: #243763;margin-top: 1rem;margin-bottom: 2rem;text-align: center;}

.section-title::after {position: absolute;content: '';width: 64px;height: 0.18rem;left: 0;right: 0;margin: auto;top: 2rem;background-color: #243763;}

.section {padding-top: 3rem;padding-bottom: 2rem;}

.section:nth-child(odd){background:#fff}
.section:nth-child(even){background:#f5f5f5}

.bd-grid {max-width: 1024px;display: grid;margin-left: 1rem;margin-right: 1rem;}

.l-header {width: 100%;position: fixed;top: 20px;left: 0;z-index: 100;}

.nav {padding:0 15px;border-radius:10px;background-color: #243763;box-shadow: 0 0 15px rgba(0,0,0,.5);height: 3rem;display: flex;justify-content: space-between;align-items: center;font-weight: 600;}

@media screen and (max-width: 768px) {
  .nav__menu {position: fixed;top: calc(3rem + 30px);right: -100%;width: 50%;height: 100%;padding: 2rem;border-radius:30px 0;background-color: hsl(224, 56%, 12%);transition: .5s;}
}

.nav__item {margin-bottom: 2rem;}

.nav__link {position: relative;color: #fff;text-shadow: 0px 2px 2px rgba(0, 0, 0, .5);padding: 7px 15px;}

.nav__logo {color: #fff;text-shadow: 0px 2px 2px rgba(0, 0, 0, .5);}

.nav__toggle {color: #fff;font-size: 1.5rem;cursor: pointer;}

.active {background-color: rgba(0, 0, 0, 0.2);border-radius: 50px;}

.show {right: 0;}

section.homes{padding-block:2rem 6rem;width:100%;}

.mainL{margin-inline: auto;margin-top:50px;padding-inline: 22px;max-width: 1024px;align-items: center;justify-content: center;}

.homeL {display:flex;/*color:#fff;text-shadow: 0px 2px 2px rgba(0, 0, 0, .5);*/gap:40px;flex-direction: column;}
h1.t{font-size: 40px;margin-bottom: 30px;}

.homeR {display:flex;gap:15px;align-self:flex-start;width: 50%;align-items: center;justify-content: center;margin-left:auto;margin-right: auto;}

@keyframes componentAnim{0%{transform:translate(0)}30%{transform:translateY(-10px)}50%{transform:translateY(4px)}70%{transform:translateY(-15px)}to{transform:translate(0)}}

.homeR img{height:170px; margin-top:50px} 
.homeR img:nth-child(1){animation:componentAnim 15s ease infinite} 
.homeR img:nth-child(2){animation:componentAnim 13s ease infinite reverse; margin-top:70px} 
.homeR img:nth-child(3){animation:componentAnim 18s ease infinite}

.button {
  display: inline-block;
  background-color: #243763;
  color: #fff;
  padding: .75rem 2.5rem;
  font-weight: 600;
  border-radius: .5rem;
  transition: .3s;
  text-shadow: none;
}

.button:hover {
  box-shadow: 0px 10px 36px rgba(0, 0, 0, 0.15);
}

.about__container {
  row-gap: 2rem;
  text-align: center;
}

.about__subtitle {
  margin-bottom: 1rem;
}

.about__img {
  justify-self: center;
}

.about__img img {
  width: 200px;
  border-radius: .5rem;
}

.footer {
  background-color: hsl(224, 56%, 12%);
  color: #fff;
  text-align: center;
  font-weight: 600;
  padding: 2rem 0;
}

.footer__title {
  font-size: 1.2rem;
  margin-bottom: 2rem;
}

.footer__social {
  margin-bottom: 2rem;
}

.footer__icon {
  font-size: 1.5rem;
  color: #fff;
  margin: 0 1rem;
}

.footer__copy {
  font-size: 14px;
  font-weight: 400;
}

@media screen and (max-width: 320px) {
  .homeR img{height:130px;}
}

@media screen and (min-width: 512px){
    .homeR img{height:250px;}
}

@media screen and (min-width: 576px) {
  .about__container {grid-template-columns: repeat(2, 1fr);align-items: center;text-align: initial;}
}


@media screen and (min-width: 768px) {
  body {margin: 0;}
  .section {/*padding-top: 4rem;*/padding-bottom: 6rem;}
  section.homes{padding-block:3rem 6rem;}
  .section-title {margin-top: 2em;margin-bottom: 3rem;}
  .section-title::after {width: 80px;top: 3rem;}
  .nav {height: calc(3rem + 1.5rem);}
  .nav__list {display: flex;padding-top: 0;}
  .nav__item {margin-left: 2.5rem;margin-bottom: 0;}
  .nav__toggle {display: none;}
  .nav__link {color: #fff;}
  .home {padding: 8rem 0 2rem;}
  .home__img {width: 400px;bottom: 18%;}
  .about__container {padding-top: 2rem;}
  .about__img img {width: 375px;}

  .mainL{display:flex;flex-wrap: wrap;margin-top: calc(3rem + 100px);}
  .homeL{width: 50%;}
  .homeR{margin-left:unset;margin-right: unset;}
}

@media screen and (min-width: 992px) {
  .bd-grid {margin-left: auto;margin-right: auto;}
  h1.t{font-size: 40px;}
  .homeR img{height:260px;}
}

.button{position: relative;z-index: 1;transition: all 0.2s;display:inline-flex;align-items:center;cursor:pointer; margin:10px 0;padding:12px 15px;outline:0;border:0; border-radius:8px;line-height:20px; font-size:14px;font-family:var(--fontB); white-space:nowrap;overflow:hidden;max-width:320px} .button.ln{color:inherit;background:transparent; border:1px solid #989b9f} 
.button.ln:hover{border-color:#fff;box-shadow:0 0 0 1px #243763; inset} .btnF{display:flex;justify-content:center; margin:10px 0;width:calc(100% + 12px);left:-6px;right:-6px;position:relative} .btnF >*{margin:0 6px} .button.ln:after, .button.ln:before{background:transparent;}
.button.alt{margin-left:18px;background:#fff;border:2px solid #243763;color:#243763}
.icon{flex-shrink:0;display:inline-flex} .icon::before{content:'';width:18px;height:18px;background-size:18px;background-repeat:no-repeat;background-position:center} .icon::after{content:'';padding:0 6px} .icon.dl::before, .drK .button.ln .icon.dl::before{background-image:url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23fefefe' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5'><polyline points='8 17 12 21 16 17'/><line x1='12' y1='12' x2='12' y2='21'/><path d='M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29'/></svg>")} .icon.demo::before{background-image:url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23fefefe' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5'><path d='M7.39999 6.32003L15.89 3.49003C19.7 2.22003 21.77 4.30003 20.51 8.11003L17.68 16.6C15.78 22.31 12.66 22.31 10.76 16.6L9.91999 14.08L7.39999 13.24C1.68999 11.34 1.68999 8.23003 7.39999 6.32003Z'/><path d='M10.11 13.6501L13.69 10.0601'/></svg>")} .button.ln .icon.dl::before{background-image:url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2308102b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5'><polyline points='8 17 12 21 16 17'/><line x1='12' y1='12' x2='12' y2='21'/><path d='M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29'/></svg>")}

.icn{flex-shrink:0;display:inline-flex}
.icn::before{content:'';width:24px;height:24px;}
.icn.tiktok::before{background-image:url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><path d='M24,3H8A5,5,0,0,0,3,8V24a5,5,0,0,0,5,5H24a5,5,0,0,0,5-5V8A5,5,0,0,0,24,3Zm3,21a3,3,0,0,1-3,3H8a3,3,0,0,1-3-3V8A3,3,0,0,1,8,5H24a3,3,0,0,1,3,3Z'/><path d='M22,12a3,3,0,0,1-3-3,1,1,0,0,0-2,0V19a3,3,0,1,1-3-3,1,1,0,0,0,0-2,5,5,0,1,0,5,5V13a4.92,4.92,0,0,0,3,1,1,1,0,0,0,0-2Z'/></svg>")}
.icn.instagram::before{background-image:url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><path d='M22,3H10a7,7,0,0,0-7,7V22a7,7,0,0,0,7,7H22a7,7,0,0,0,7-7V10A7,7,0,0,0,22,3Zm5,19a5,5,0,0,1-5,5H10a5,5,0,0,1-5-5V10a5,5,0,0,1,5-5H22a5,5,0,0,1,5,5Z'/><path d='M16,9.5A6.5,6.5,0,1,0,22.5,16,6.51,6.51,0,0,0,16,9.5Zm0,11A4.5,4.5,0,1,1,20.5,16,4.51,4.51,0,0,1,16,20.5Z'/><circle cx='23' cy='9' r='1'/></svg>")}
.icn.github::before{background-image:url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><g><path d='M16,3a13,13,0,0,0-3.46,25.53,1,1,0,1,0,.53-1.92,11,11,0,1,1,7-.4,15.85,15.85,0,0,0-.3-3.92A6.27,6.27,0,0,0,24.68,16a6.42,6.42,0,0,0-1.05-3.87,7.09,7.09,0,0,0-.4-3.36,1,1,0,0,0-1.1-.67,8,8,0,0,0-3.37,1.28A11.35,11.35,0,0,0,16,9a13.09,13.09,0,0,0-3,.43A5.74,5.74,0,0,0,9.62,8.25a1,1,0,0,0-1,.66,7.06,7.06,0,0,0-.37,3.19A7.15,7.15,0,0,0,7.2,16a6.66,6.66,0,0,0,5,6.28,7.43,7.43,0,0,0-.15.79c-1,.06-1.58-.55-2.32-1.48a3.45,3.45,0,0,0-1.94-1.53,1,1,0,0,0-1.15.76A1,1,0,0,0,7.35,22c.16,0,.55.52.77.81a4.74,4.74,0,0,0,3.75,2.25,4.83,4.83,0,0,0,1.3-.18h0a1,1,0,0,0,.29-.14l0,0a.72.72,0,0,0,.18-.21.34.34,0,0,0,.08-.09.85.85,0,0,0,.06-.17,1.52,1.52,0,0,0,.06-.2v0a4.11,4.11,0,0,1,.46-1.91,1,1,0,0,0-.76-1.65A4.6,4.6,0,0,1,9.2,16a4.84,4.84,0,0,1,.87-3,1,1,0,0,0,.24-.83,5,5,0,0,1,0-1.85,3.59,3.59,0,0,1,1.74.92,1,1,0,0,0,1,.23A12.49,12.49,0,0,1,16,11a9.91,9.91,0,0,1,2.65.43,1,1,0,0,0,1-.18,5,5,0,0,1,2-1,4.11,4.11,0,0,1,0,1.91,1.05,1.05,0,0,0,.32,1A4,4,0,0,1,22.68,16a4.29,4.29,0,0,1-4.41,4.46,1,1,0,0,0-.94.65,1,1,0,0,0,.28,1.11c.59.51.5,4,.47,5.36a1,1,0,0,0,.38.81,1,1,0,0,0,.62.21,1.07,1.07,0,0,0,.25,0A13,13,0,0,0,16,3Z'></path></g></svg>")}

.action {
    display: flex;
    gap: 18px;
    flex-direction: column;
}

.link {
    display: flex;
    gap: 18px;
}

.button.ln svg{margin-right: 8px;}
svg{width:24px;height:24px; fill:#000} svg.line, svg .line{fill:none; stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.25}
.extL::after{content:''; display:inline-block; width:14px;height:14px;margin-inline-start:5px; background: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23989b9f' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><line x1='7' y1='17' x2='17' y2='7'/><polyline points='7 7 17 7 17 17'/></svg>") center / 16px no-repeat}

.flexIn.baseline {
    display: flex;
    gap: 18px;
    align-items: baseline;
    font-size: .93em;
}

.swal2-title {
  font-size: 1.5rem;
}
</style>
<body>
    <header class="l-header">
        <nav class="nav bd-grid">
            <div>
                <a href="#" class="nav__logo" id="inilogo">Presensi Guru</a>
            </div>

            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item"><a href="#Beranda" class="nav__link active">Beranda</a></li>
                    <!--<li class="nav__item"><a href="#Camera" class="nav__link">Camera</a></li>-->
                </ul>
            </div>

            <div class="nav__toggle" id="nav-toggle">
                <i class='bx bx-menu'></i>
            </div>
        </nav>
    </header>

    <main class="l-main">

        <section class="homes" id="Beranda">
            <div class="mainL">

                <div class="homeL">
                    <div class="slogan">
                        <h1 class="t">Presensi Guru<br>berbasis Web</h1>
                        <p>Sistem presensi guru berbasis web memungkinkan para guru untuk melakukan absensi dengan cepat
                            dan mudah<br>di <b>SMP dan SMA Pesantren MKGR Kertasemaya</b>.</p>
                    </div>
                    <div class="action">
                        <div class="link">
                            <a href="login" class="button">Masuk</a>
                            <a href="daftar" class="button ln">Daftar</a>
                        </div>
                        <!--<div class="flexIn baseline">
                            <div><a class="extL" href="#Camera">Selengkapnya</a></div>
                        </div>-->
                    </div>
                </div>

                <div class="homeR">
                    <img alt="Pics" src="https://presensi.feeldream.repl.co/pic4.png">
                    <img alt="Pics" src="https://presensi.feeldream.repl.co/pic2.png">
                    <img alt="Pics" src="https://presensi.feeldream.repl.co/pic3.png">
                </div>

            </div>
        </section>

        <!--<section class="camera section" id="Camera">
            <h2 class="section-title">Camera</h2>
            <div class="about__container bd-grid">
                <div class="text-center" id="my_camera" style="border-radius: 18px;"></div>
                <div class="about__text">
                    <p style="text-align:justify;margin-bottom: 10px;">Kami menghadirkan terobosan inovasi terbaru
                        mengenai pengembangan teknologi sistem presensi untuk sekolah <b>SMP dan SMA Pesantren MKGR KERTASEMAYA</b>
                        dengan mewajibkan pengguna berswafoto (selfie) sebagai tanda bukti yang membuat data absensi
                        semakin akurat dan meminimalisir kecurangan.</p>
                    <a class="button ln" onClick="ambil_gambar();"><svg class='line' xmlns='http://www.w3.org/2000/svg'
                            viewBox='0 0 24 24'>
                            <g transform='translate(2.500000, 3.042105)'>
                                <path
                                    d='M12.9381053,9.456 C12.9381053,7.71915789 11.5296842,6.31073684 9.79284211,6.31073684 C8.056,6.31073684 6.64757895,7.71915789 6.64757895,9.456 C6.64757895,11.1928421 8.056,12.6012632 9.79284211,12.6012632 C11.5296842,12.6012632 12.9381053,11.1928421 12.9381053,9.456 Z'>
                                </path>
                                <path
                                    d='M9.79252632,17.158 C17.8377895,17.158 18.7956842,14.7474737 18.7956842,9.52431579 C18.7956842,5.86326316 18.3114737,3.90431579 15.262,3.06221053 C14.982,2.97378947 14.6714737,2.80536842 14.4198947,2.52852632 C14.0135789,2.08326316 13.7167368,0.715894737 12.7356842,0.302210526 C11.7546316,-0.110421053 7.81463158,-0.0914736842 6.84936842,0.302210526 C5.88515789,0.696947368 5.57147368,2.08326316 5.16515789,2.52852632 C4.91357895,2.80536842 4.60410526,2.97378947 4.32305263,3.06221053 C1.27357895,3.90431579 0.789368421,5.86326316 0.789368421,9.52431579 C0.789368421,14.7474737 1.74726316,17.158 9.79252632,17.158 Z'>
                                </path>
                                <line x1='14.7045' y1='5.957895' x2='14.7135' y2='5.957895'></line>
                            </g>
                        </svg>Ambil Foto</a>
                </div>
            </div>

            <script src="header/webcam.js"></script>

            <script language="JavaScript">
                Webcam.set({
                    width: 320,
                    height: 320,
                    image_format: 'jpeg',
                    jpeg_quality: 70
                });
                Webcam.attach('#my_camera');

                function ambil_gambar() {
                    Webcam.snap(function (data_uri) {
                        Swal.fire({
                            title: 'OK!',
                            text: 'Berhasil Melakukan Absensi',
                            imageUrl: '' + data_uri,
                            imageAlt: 'Swafoto',
                        })
                    });

                }
            </script>
        </section>
            -->

    </main>

    <footer class="footer">
        <p class="footer__title">Presensi Guru dengan Deteksi Lokasi & Selfie Absensi</p>
        <!--<div class="footer__social">
            <a href="https://www.instagram.com/rayyarrr" class="footer__icon" target="_blank"><i class='icn instagram' ></i></a>
            <a href="https://www.tiktok.com/@feelthisray" class="footer__icon" target="_blank"><i class='icn tiktok' ></i></a>
            <a href="https://github.com/feeldreams" class="footer__icon" target="_blank"><i class='icn github' ></i></a>
        </div>-->
        <p class="footer__copy">&#169; 2023 - All rights reserved</p>
    </footer>

    <script src="https://unpkg.com/scrollreveal"></script>

    <script>
        const showMenu = (toggleId, navId) => {
            const toggle = document.getElementById(toggleId),
                nav = document.getElementById(navId)

            if (toggle && nav) {
                toggle.addEventListener('click', () => {
                    nav.classList.toggle('show')
                })
            }
        }
        showMenu('nav-toggle', 'nav-menu')

        const navLink = document.querySelectorAll('.nav__link')

        function linkAction() {
            const navMenu = document.getElementById('nav-menu')
            navMenu.classList.remove('show')
        }
        navLink.forEach(n => n.addEventListener('click', linkAction))

        const sections = document.querySelectorAll('section[id]')
        idweb = inilogo.innerHTML

        function scrollActive() {
            const scrollY = window.pageYOffset

            sections.forEach(current => {
                const sectionHeight = current.offsetHeight
                const sectionTop = current.offsetTop - 50;
                sectionId = current.getAttribute('id')

                if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                    document.querySelector('.nav__menu a[href*=' + sectionId + ']').classList.add('active');
                    inilogo.innerHTML = sectionId;
                } else {
                    document.querySelector('.nav__menu a[href*=' + sectionId + ']').classList.remove('active')
                }

                if (sectionId == "Beranda") { inilogo.innerHTML = idweb; }
            })
        }
        window.addEventListener('scroll', scrollActive)

        // Animasi

        const sr = ScrollReveal({
            origin: 'top',
            distance: '40px',
            duration: 1500,
            delay: 150,
            reset: true
        });

        sr.reveal('.homeL', {});
        sr.reveal('.homeR, .about__text, .skills__img, video', {});

    </script>
</body>
</html>