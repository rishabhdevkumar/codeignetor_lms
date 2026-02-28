<!DOCTYPE html>
<html>
<head>
    <title>Pakka Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #E4D6B7;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        /* Background Logo Watermark */
        body::before {
            content: "";
            background: url('logo.png') no-repeat center;
            background-size: 400px;
            opacity: 0.05;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
        }

        .top-logo {
            margin-top: 40px;
            text-align: center;
            z-index: 1;
        }

        .top-logo img {
            height: 70px;
        }

        .login-card {
            background-color: #F2BC45;
            margin-top: 40px;
            padding: 60px 80px;
            border-radius: 45px;
            border: 2px solid #A42334;
            width: 450px;
            text-align: center;
            z-index: 1;
        }

        .login-card h2 {
            color: #A42334;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .login-card p {
            font-size: 14px;
            margin-bottom: 35px;
            color: #333;
        }

        .input-field {
            width: 100%;
            padding: 14px 18px;
            margin-bottom: 22px;
            border-radius: 30px;
            border: none;
            background-color: #D5DCE5;
            outline: none;
            font-size: 14px;
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            border-radius: 30px;
            border: none;
            background-color: #A42334;
            color: #FFFFFF;
            font-size: 15px;
            cursor: pointer;
            transition: 0.2s ease-in-out;
        }

        .login-btn:hover {
            transform: scale(1.02);
        }

        @media (max-width: 500px) {
            .login-card {
                width: 90%;
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>

    <div class="top-logo">
        <img src="<?php echo base_url(); ?>assets/img/logoHeader.png" alt="Pakka Logo">
    </div>

    <div class="login-card">
        <h2>Pakka Limited</h2>
        <p>User Login</p>

        <input type="text" class="input-field" placeholder="Admin">
        <input type="password" class="input-field" placeholder="Password">

        <button class="login-btn">Login</button>
    </div>

</body>
</html>