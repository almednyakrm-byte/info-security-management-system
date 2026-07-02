<!-- login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #2f4f7f, #1a1d23);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
        
        .glassmorphic {
            background: linear-gradient(180deg, #2f4f7f, #1a1d23);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        
        .glassmorphic::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, #2f4f7f, #1a1d23);
            background-clip: padding-box;
            border-radius: 10px;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="h-screen flex justify-center items-center">
        <div class="glassmorphic w-96 p-8 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Login</h2>
            <form id="login-form">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-slate-900">Username</label>
                    <input type="text" id="username" name="username" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-slate-900">Password</label>
                    <input type="password" id="password" name="password" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <button type="submit" class="w-full p-2 mt-4 text-sm text-white bg-indigo-500 hover:bg-indigo-700 rounded-lg">Login</button>
            </form>
            <p class="text-sm text-gray-500 mt-4">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
        </div>
    </div>

    <script>
        const form = document.getElementById('login-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });
                const data = await response.json();
                if (data.success) {
                    alert('Login successful!');
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        });
    </script>
</body>
</html>


This code creates a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. The form is validated using standard HTML input pattern validators to support Arabic and Latin characters. The AJAX JavaScript code uses the Fetch API to submit the credentials to the backend PHP script (`../backend/auth.php?action=login`) and handles the response or error alerts dynamically. The direct link to the register page is provided at the bottom of the page.