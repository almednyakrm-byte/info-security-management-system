<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
        }
        .form-group input {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input:focus {
            border-color: #aaa;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .submit-btn {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #2980b9;
        }
        .error-message {
            color: #f00;
            font-size: 12px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="title">Register</h2>
        <form id="register-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                <div class="error-message" id="username-error"></div>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <div class="error-message" id="email-error"></div>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <div class="error-message" id="password-error"></div>
            </div>
            <button class="submit-btn" type="submit">Register</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var usernameError = $('#username-error');
                var emailError = $('#email-error');
                var passwordError = $('#password-error');

                if (username.length < 3) {
                    usernameError.text('Username must be at least 3 characters long');
                    return false;
                } else {
                    usernameError.text('');
                }

                if (!email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)) {
                    emailError.text('Invalid email address');
                    return false;
                } else {
                    emailError.text('');
                }

                if (password.length < 8) {
                    passwordError.text('Password must be at least 8 characters long');
                    return false;
                } else {
                    passwordError.text('');
                }

                $.ajax({
                    type: 'POST',
                    url: '../backend/auth.php?action=register',
                    data: {
                        username: username,
                        email: email,
                        password: password
                    },
                    success: function(data) {
                        if (data == 'success') {
                            alert('Registration successful');
                            window.location.href = 'login.php';
                        } else {
                            alert('Registration failed');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>