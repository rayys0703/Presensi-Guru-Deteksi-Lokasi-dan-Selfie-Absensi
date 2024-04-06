<?php
// Aini
include_once '../cfgdb.php';

session_start(); // Mulai session

// Jika user sudah login, alihkan ke halaman dashboard
if (isset($_SESSION['username'])) {
  header("Location: beranda");
  exit();
}

$error_message = '';

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $password_hash = md5($password);

  $sql = "SELECT username FROM admin WHERE username = :username AND password = :password";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':username', $username);
  $stmt->bindValue(':password', $password_hash);
  $stmt->execute();

  if ($stmt->rowCount() == 1) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['username'] = $row['username'];
    header("Location: beranda");
    exit();
  } else {
    $error_message = 'Username atau password salah!';
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
  <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />

  <title>SMP dan SMA Pesantren MKGR Kertasemaya</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
</head>

<body>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap');

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #000;
    }

    .bg-image {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('../img/fullpage.webp');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      z-index: -1;
      /* agar elemen ini berada di bawah konten */
      animation-name: zoom;
      animation-duration: 1s;
      animation-timing-function: ease-in-out;
      animation-fill-mode: forwards;
      opacity: 0;
    }

    @keyframes zoom {
      from {
        transform: scale(1);
      }

      to {
        transform: scale(1.2);
        opacity: 0.5;
      }
    }

    @keyframes tara {
      from {
        margin-top: 0;
        opacity: 0;
      }

      to {
        margin-top: 70px;
        opacity: 1;
      }
    }

    a {
      text-decoration: none;
    }

    p {
      margin-top: 15px
    }

    .background {
      height: 520px;
      position: absolute;
      transform: translate(-50%, -50%);
      top: 50%;
      left: 50%
    }

    form h3,
    label {
      font-weight: 500
    }

    input,
    label {
      display: block
    }

    *,
    :after,
    :before {
      padding: 0;
      margin: 0;
      box-sizing: border-box
    }

    .social,
    label {
      margin-top: 30px
    }

    /*body{background: rgb(81,172,255);background: linear-gradient(90deg, rgba(81,172,255,1) 0%, rgba(156,208,255,1) 50%, rgba(250,255,255,1) 100%);}*/
    .background {
      max-width: 330px
    }

    .background .shape {
      height: 200px;
      width: 200px;
      position: absolute;
      border-radius: 50%
    }

    .shape:first-child {
      background: linear-gradient(#1845ad, #23a2f6);
      left: -20px;
      top: -50px
    }

    .shape:last-child {
      background: linear-gradient(#1845ad, #23a2f6);
      right: -20px;
      bottom: -50px
    }

    form {
      margin-top: 5vh;
      background-color: rgba(0, 0, 0, 0.8);
      max-width: 300px;
      margin-left: auto;
      margin-right: auto;
      border-radius: 10px;
      /*box-shadow:0 0 40px rgba(8,7,16,.6);*/
      padding: 50px 35px;
      transition: all 0.3s ease;
    }

    form * {
      font-family: Poppins, sans-serif;
      color: #fff;
      letter-spacing: .5px;
      outline: 0;
      border: none
    }

    .social div,
    input {
      border-radius: 3px
    }

    form h3 {
      font-size: 32px;
      line-height: 42px;
    }

    form h3,
    form p {
      text-align: center
    }

    @media only screen and (min-width: 500px) {
      form {
        max-width: 350px;
        margin-top: 0;
        animation-name: tara;
        animation-duration: .7s;
        animation-timing-function: ease-in-out;
        animation-fill-mode: forwards;
      }
    }

    label {
      font-size: 16px
    }

    input {
      height: 50px;
      width: 100%;
      background-color: rgb(255 255 255 / 8%);
      padding: 0 10px;
      margin-top: 8px;
      font-size: 14px;
      font-weight: 300
    }

    ::placeholder {
      color: #e5e5e5
    }

    button {
      margin-top: 30px;
      margin-bottom: 25px;
      width: 100%;
      background-color: #243763;
      color: #fff;
      padding: 15px 0;
      font-size: 18px;
      font-weight: 600;
      border-radius: 5px;
      cursor: pointer
    }

    .social {
      display: flex
    }

    .social div {
      background: rgba(255, 255, 255, .27);
      width: 150px;
      padding: 5px 10px 10px 5px;
      color: #eaf0fb;
      text-align: center
    }

    .social div:hover {
      background-color: rgba(255, 255, 255, .47)
    }

    .social .fb {
      margin-left: 25px
    }

    .social i {
      margin-right: 4px
    }
  </style>
  <div class="bg-image"></div>
  <form method="POST" action="" id="form-login">
    <h3>Login Admin</h3>
    <?php if ($error_message): ?>
      <div class="alert alert-danger text-center" role="alert">
        <?php echo $error_message; ?>
      </div>
    <?php endif; ?>

    <label for="username">Username</label>
    <input type="text" placeholder="Username" id="username" name="username" required>

    <label for="password">Password</label>
    <input type="password" placeholder="Kata Sandi" id="password" name="password" required>

    <button type="submit" name="login">Masuk</button>

  </form>
</body>

</html>