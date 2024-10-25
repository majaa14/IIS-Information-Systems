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
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
            <h2>Správa vozidel</h2>
            <h3>Přidání nového vozidla</h3>
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
                <form action="{{ route('vozidlo.create') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="form">
                        <label for="Vozidlo">SPZ vozidla</label>
                        <input type="text" id="spz" name="spz" maxlength="7" required>
                    </div>
                    <div class="form">
                        <label for="Vozidlo">Druh vozidla</label>
                        <select id="druh" name="druh" required>
                            <option value="autobus">Autobus</option>
                            <option value="trolejbus">Trolejbus</option>
                        </select>
                    </div>
                        <br>
                    <div class="form">
                        <input type="submit" value="Přidat">
                    </div>
                        <br>
                </form>
            </div>
            <h3>Výpis informací o vozidle</h3>
                <form method="post" action="{{ route('getVozidloData') }}">
                    @csrf
                    <div class="select">
                        <label for="Vozidlo">Vyberte vozidlo:</label>
                        <select name="vozidlo" id="vozidlo" onchange="this.form.submit()">
                            <option value="0">Vyberte</option>
                            @foreach ($vozidla as $vozidloItem)
                                <option value="{{ $vozidloItem->SPZ }}">{{ $vozidloItem->SPZ }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
                @if(isset($vozidloData))
                    <table class="plan">
                        <tr class="head">
                            <td>SPZ</td>
                            <td>Druh vozidla</td>
                            <td>Požadavek na pravidelný servis</td>
                            <td>Odstranit</td>
                        </tr>
                        <tr>
                            <td>{{ $vozidloData->SPZ }}</td>
                            <td>{{ $vozidloData->Druh }}</td>
                            <td>
                                <div class="form">
                                    <form method="post" action="{{ route('pozadavekPravid.create') }}">
                                        @csrf
                                        <input type="hidden" name="spz" value="{{$vozidloData->SPZ}}">
                                        <input type="submit" value="Vytvořit">
                                    </form>
                                </div>
                            </td>
                            <td>
                                <div class="form">
                                    <form method="post" action="{{ route('vozidlo.delete') }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="spz" value="{{$vozidloData->SPZ}}">
                                        <input type="submit" value="Odstranit">
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </table>
                @endif
                <br>
            <h3>Závady vozidel</h3>
                <table class="plan">
                    <tr class="head">
                        <td>SPZ</td>
                        <td>Datum vzniku</td>
                        <td>Popis</td>
                        <td>Požadavek na servis</td>
                    </tr>
                    @foreach($zavady as $zavada)
                    <tr>
                        <td>{{$zavada->SPZ}}</td>
                        <td>{{$zavada->Datum}}</td>
                        <td>{{$zavada->Popis}}</td>
                        <td>
                            <div class="form">
                                <form method="post" action="{{ route('pozadavek.create') }}">
                                    @csrf
                                    <input type="hidden" name="spz" value="{{$zavada->SPZ}}">
                                    <input type="hidden" name="popis" value="{{$zavada->Popis}}">
                                    <input type="submit" value="Vytvořit">
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
                <br>
        </div>
    </body>
</html>

