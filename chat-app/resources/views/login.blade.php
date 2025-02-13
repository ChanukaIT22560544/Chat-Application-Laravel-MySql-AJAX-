<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <!-- Bootstrap CDN -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* WhatsApp-Themed Background */
    body {
      background-color: #25D366;
      background: linear-gradient(135deg, #128C7E, #25D366);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Arial', sans-serif;
      margin: 0;
    }

    h2{
      font-weight: bolder;
      text-align: center;
      
    }

    label {
      font-weight: bold;
      color: #128C7E;
      margin-bottom: 5px;
    }

    /* Login Card */
    .login-container {
      background: #8bf590;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
      width: 100%;
      max-width: 400px;
      text-align: left;
      animation: fadeIn 1s ease-in-out;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }


    /* Fade-in Animation */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: scale(0.9);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    /* Heading */
    .login-container h2 {
      color: #128C7E;
      font-weight: bold;
      margin-bottom: 20px;
    }

    /* Input Fields */
    .form-control {
      border-radius: 8px;
      border: 1px solid #128C7E;
      transition: 0.3s ease;
    }

    .form-control:focus {
      box-shadow: 0 0 8px rgba(18, 140, 126, 0.5);
      border-color: #25D366;
    }

    /* Login Button */
    .btn-primary {
      background: #128C7E;
      border: none;
      border-radius: 8px;
      padding: 10px;
      font-size: 16px;
      transition: 0.3s ease-in-out;
    }

    .btn-primary:hover {
      background: #075E54;
    }

    /* Footer Link */
    .login-footer {
      margin-top: 15px;
      color: #128C7E;
    }

    .login-footer a {
      color: #075E54;
      font-weight: bold;
      text-decoration: none;
      transition: 0.3s ease;
    }

    .login-footer a:hover {
      color: #25D366;
    }

    /* Error Alert */
    .alert-danger {
      font-size: 14px;
      padding: 10px;
      margin-top: 10px;
      border-radius: 8px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Login</h2>
    <form method="POST" action="{{ route('login.submit') }}">
      @csrf
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
      </div>
      @if ($errors->any())
        <div class="alert alert-danger">
          {{ $errors->first() }}
        </div>
      @endif
      <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
    <div class="login-footer">
      <p class="mt-3">Don't have an account? <a href="{{ route('register') }}">Register</a></p>
    </div>
  </div>
</body>
</html>
