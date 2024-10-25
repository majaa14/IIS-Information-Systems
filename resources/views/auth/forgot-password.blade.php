<!DOCTYPE html>
<html lang="en">
    <head>
        <link href=”https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css” rel=”stylesheet” integrity=”sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU” crossorigin=”anonymous”>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="UTF-8">
        <title>Dopravní podnik vesnice Třeskoprsky</title>
        <link rel="icon" href="images/bus.png">
        <link rel="stylesheet" href="style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Montserrat:wght@600&display=swap" rel="stylesheet">
    </head>
    <body>
        <header>
            <div class="container">
                <div class="navigationbar">
                    <div class="title">
                        <h1>Dopravní podnik <br>vesnice Třeskoprsky</h1>
                    </div>
                    <nav>
                        <ul id="menu">
                            <li><a class="underline" href="/">Domů</a></li>
                            <li><a class="underline" href="/ucet">Účet</a></li>
                        </ul>
                    </nav>
                    <div class="menu-icon" onclick="toggleMenu()">
                        <i class="fa-solid fa-bars"></i>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="mb-4 text-sm text-gray-600">
            {{ __('Zapomněli jste své heslo? Žádný problém. Napište nám Váš email a pošleme Vám na něj resetovací odkaz a zvolíte si nové heslo.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="form">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="column">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="column">
                <x-primary-button>
                    {{ __('Zaslat resetovací odkaz na email') }}
                </x-primary-button>
            </div>
        </form>
        </div>
        <script>
            function toggleMenu() {
                var menu = document.getElementById("menu");
                menu.classList.toggle("show");
            }
        </script>
    </body>
</html>