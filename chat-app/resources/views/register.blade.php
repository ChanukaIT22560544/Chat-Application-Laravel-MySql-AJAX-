<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register</title>
  <!-- Bootstrap CDN -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* WhatsApp-Themed Background */
    body {
      background: linear-gradient(135deg, #128C7E, #25D366);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Arial', sans-serif;
      margin: 0;
    }

    /* Register Card */
    .register-container {
      background: #8bf590;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
      width: 100%;
      max-width: 400px;
      text-align: left; /* Align all text to left */
      animation: fadeIn 1s ease-in-out;
    }

    h2{
      font-weight: bolder;
      text-align: center;
      color: #128C7E;
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

    /* Label and Input Alignment */
    .form-group {
      display: flex;
      flex-direction: column;
    }

    label {
      font-weight: bold;
      color: #128C7E;
      margin-bottom: 5px;
    }

    .form-control {
      border-radius: 8px;
      border: 1px solid #128C7E;
      transition: 0.3s ease;
    }

    .form-control:focus {
      box-shadow: 0 0 8px rgba(18, 140, 126, 0.5);
      border-color: #25D366;
    }

    /* Register Button */
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
    .register-footer {
      margin-top: 15px;
      color: #128C7E;
    }

    .register-footer a {
      color: #075E54;
      font-weight: bold;
      text-decoration: none;
      transition: 0.3s ease;
    }

    .register-footer a:hover {
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
  <div class="register-container">
    <h2>Register</h2>
    <form method="POST" action="{{ route('register') }}">
      @csrf
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" class="form-control" placeholder="Enter your name" required>
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
      </div>
      <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm your password" required>
      </div>
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>
    <div class="register-footer">
      <p class="mt-3">Already have an account? <a href="{{ route('login') }}">Login</a></p>
    </div>
  </div>
</body>
</html>
