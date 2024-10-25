<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link href=”https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css” rel=”stylesheet” integrity=”sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU” crossorigin=”anonymous”>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="UTF-8">
        <title>Dopravní podnik vesnice Třeskoprsky</title>
        <link rel="icon" href="/images/bus.png">
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
                            <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="ml-4 text-sm text-gray-700 underline">Odhlásit se</a>
                            </form>
                            </li>
                            <li><a class="underline" href="/is">IS</a></li>
                        </ul>
                    </nav>
                    <div class="menu-icon" onclick="toggleMenu()">
                        <i class="fa-solid fa-bars"></i>
                    </div>
                </div>
            </div>
        </header> 
        <script>
            function toggleMenu() {
                var menu = document.getElementById("menu");
                menu.classList.toggle("show");
            }
        </script>
        <div class="is_body">
            <div class="menu_l_r">
            <ul class="left_menu">
                    <a href="/linky"><li class="polozka">Správa linek</li></a>
                    <a href="/spoje"><li class="polozka">Správa spojů</li></a>
                    <a href="/vozidla"><li class="polozka">Správa vozidel</li></a>
                    <a href="/dispecink"><li class="polozka">Dispečink</li></a>
                    <a href="/zaznamy"><li class="polozka">Záznamy o údržbě</li></a>
                    <a href="/plan"><li class="polozka">Zobrazit plán</li></a>
                    <a href="/zavady"><li class="polozka">Hlášení závady</li></a>
                    <a href="/uzivatele"><li class="polozka">Uživatelé</li></a>
                </ul>

                <ul class="right_menu">
                <a href="{{ route('profile.update') }}" class="ml-4 text-sm text-gray-700 underline"><li class="polozka">Upravit účet</li></a>
                </ul>
            </div>
            <h2>Vložení záznamu o údržbě</h2>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('message') }}
                </div>
            @endif
            <div class="zavada">
                <form action="{{ route('zaznam.create') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="form">
                        <label for="Požadavek na servis">ID požadavku</label>
                        <select name="id" id="id" required>
                            @foreach ($pozadavky as $pozadavek)
                                <option value="{{ $pozadavek->ID_požadavek }}">{{ $pozadavek->ID_požadavek }}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    <div class="form">
                        <label for="Záznamy o údržbě">Datum údržby</label>
                        <input type="date" id="datum" name="datum" value="{{ \Carbon\Carbon::now()->toDateString() }}" required>
                    </div>
                        <br>
                    <div class="form">
                        <label for="Záznamy o údržbě">Typ údržby</label>
                        <textarea class="popis" type="text" id="popis" name="popis" maxlength="200" required></textarea>
                    </div>
                        <br>
                    <div class="form">
                        <input type="submit" value="Odeslat">
                    </div>
                </form>
            </div>
            <h3>Požadavky na údržbu</h2>
                <table class="plan">
                    <tr class="head">
                        <td>ID požadavku</td>
                        <td>SPZ</td>
                        <td>Datum vzniku požadavku</td>
                        <td>Popis závady</td>
                    </tr>
                    @foreach($pozadavky as $pozadavek)
                    <tr>
                        <td>{{$pozadavek->ID_požadavek}}</td>
                        <td>{{$pozadavek->FK_SPZ}}</td>
                        <td>{{$pozadavek->Datum}}</td>
                        <td>{{$pozadavek->Popis}}</td>
                    </tr>
                    @endforeach
                </table>
                <br>
        </div>
    </body>
</html>
