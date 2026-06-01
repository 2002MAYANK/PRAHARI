<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prahari Admin Login</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, Helvetica, sans-serif;
        }

        body{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:#000;
            overflow:hidden;
        }

        /* BACKGROUND */

        .background{
            position:absolute;
            width:100%;
            height:100%;
            background:
            linear-gradient(135deg,#081b5c,#05103d,#0a2463);
        }

        /* LOGIN BOX */

        .login-container{
            position:relative;
            width:420px;
            padding:50px 40px;
            border-radius:30px;
            backdrop-filter:blur(15px);
            background:rgba(255,255,255,0.08);
            border:1px solid rgba(255,255,255,0.15);
            box-shadow:0 0 30px rgba(0,0,0,0.4);
            overflow:hidden;
        }

        /* PINK GLOW */

        .login-container::before{
            content:'';
            position:absolute;
            width:300px;
            height:300px;
            background:radial-gradient(circle,#ff0080,#ff008000);
            top:-80px;
            left:-100px;
            opacity:0.6;
        }

        .login-container::after{
            content:'';
            position:absolute;
            width:250px;
            height:250px;
            background:radial-gradient(circle,#6a00ff,#6a00ff00);
            bottom:-100px;
            right:-100px;
            opacity:0.5;
        }

        .content{
            position:relative;
            z-index:2;
        }

        /* PROFILE ICON */

        .profile{
            width:110px;
            height:110px;
            border-radius:50%;
            background:#ddd;
            margin:0 auto 40px;
            display:flex;
            justify-content:center;
            align-items:center;
            font-size:50px;
            color:#888;
        }

        .input-group{
            margin-bottom:30px;
        }

        .input-group input{
            width:100%;
            background:transparent;
            border:none;
            border-bottom:2px solid rgba(255,255,255,0.5);
            padding:12px 10px;
            color:white;
            font-size:16px;
            outline:none;
        }

        .input-group input::placeholder{
            color:#ddd;
        }

        .actions{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-top:20px;
        }

        .login-btn{
            width:150px;
            padding:12px;
            border:none;
            border-radius:30px;
            background:white;
            color:#333;
            font-weight:bold;
            cursor:pointer;
            transition:0.3s;
        }

        .login-btn:hover{
            background:#ddd;
        }

        .forgot{
            color:white;
            text-decoration:none;
            font-size:14px;
        }

        .register{
            text-align:center;
            margin-top:60px;
            color:white;
            letter-spacing:3px;
            font-size:28px;
        }

        .error{
            color:#ffb3b3;
            margin-bottom:20px;
            text-align:center;
        }

    </style>

</head>

<body>

    <div class="background"></div>

    <div class="login-container">

        <div class="content">

            <!-- PROFILE -->

            <div class="profile">
                👤
            </div>

            <!-- ERROR -->

            @if(session('error'))
                <div class="error">
                    {{ session('error') }}
                </div>
            @endif

            <!-- FORM -->

            <form method="POST" action="/admin/login">

                @csrf

                <div class="input-group">
                    <input type="email"
                           name="email"
                           placeholder="Email ID">
                </div>

                <div class="input-group">
                    <input type="password"
                           name="password"
                           placeholder="Password">
                </div>

                <div class="actions">

                    <button type="submit" class="login-btn">
                        LOGIN
                    </button>

                    <a href="#" class="forgot">
                        Forgot Password?
                    </a>

                </div>

            </form>

            <div class="register">
                PRAHARI
            </div>

        </div>

    </div>

</body>
</html>