<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shuttl - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        input::placeholder { color: #9ca3af; }
        input {
            background: transparent;
            border: 2px solid #1dc7b0;
            border-radius: 999px;
            color: white;
            padding: 16px 32px !important;
            width: 100%;
            font-size: 15px;
            outline: none;
            transition: border-color 0.2s;
        }
        input:focus { border-color: #5eead4; }
        input.error { border-color: #ef4444; }
        input, input:focus, input:active {
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 999px transparent inset !important;
            box-shadow: 0 0 0 999px transparent inset !important;
            background-color: transparent !important;
            -webkit-text-fill-color: #ffffff !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body class="min-h-screen relative">

    <!-- Full screen background image -->
    <img src="{{ asset('images/loginBG.png') }}"
         alt="Background"
         class="absolute inset-0 w-full h-full object-cover">

    <!-- Dark overlay -->
    <div class="absolute inset-0" style="background: rgba(0,0,0,0.55);"></div>

    <!-- Content -->
    <div class="relative z-10 min-h-screen flex flex-col justify-center px-40">

        <h1 class="text-5xl font-bold text-white mb-10">Welcome</h1>

        @if(session('success'))
            <div class="mb-4 p-3 rounded-full text-center text-sm"
                 style="background: rgba(45,212,191,0.1); border: 1px solid #2dd4bf; color: #2dd4bf; max-width: 380px;">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-5" style="max-width: 380px;">
            @csrf

            <div>
                <input type="text"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="Name"
                       class="{{ $errors->has('email') ? 'error' : '' }}">
                @error('email')
                    <p class="text-red-400 text-xs mt-1 pl-4">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <input type="password"
                       name="password"
                       placeholder="Password"
                       class="{{ $errors->has('password') ? 'error' : '' }}">
                @error('password')
                    <p class="text-red-400 text-xs mt-1 pl-4">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <button type="submit"
                        style="background: #2dd4bf; border-radius: 999px; width: 100%; max-width: 380px; padding: 14px; font-size: 16px; font-weight: 600; color: #0a0a0a; border: none; cursor: pointer; transition: background 0.2s;"
                        onmouseover="this.style.background='#5eead4'"
                        onmouseout="this.style.background='#2dd4bf'">
                    Login
                </button>
            </div>

            <div class="flex items-center justify-between gap-3" style="max-width: 380px;">
                <a href="{{ route('landing') }}"
                   style="display:inline-block; width:100%; text-align:center; background: transparent; border: 2px solid #2dd4bf; border-radius: 999px; padding: 12px 0; color: #ffffff; text-decoration: none; font-weight: 600;"
                   onmouseover="this.style.background='rgba(45,212,191,0.1)'"
                   onmouseout="this.style.background='transparent'">
                    Back to Home
                </a>
            </div>

            <p class="text-center text-sm" style="color: #9ca3af;">
                Don't have an account yet?
                <a href="{{ route('register') }}" style="color: #9ca3af; text-decoration: underline;">Create one</a>
            </p>
        </form>
    </div>

    <!-- Logo bottom right -->
    <div class="absolute bottom-8 right-8 z-10">
        <img src="{{ asset('images/fullLogo.png') }}" alt="Shuttl" style="width: 160px;">
    </div>

</body>
</html>