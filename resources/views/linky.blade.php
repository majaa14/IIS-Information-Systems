<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link href=”https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css” rel=”stylesheet” integrity=”sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU” crossorigin=”anonymous”>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="UTF-8">
        <title>Dopravní podnik vesnice Třeskoprsky</title>
        <link rel="icon" href="/images/bus.png">
        <link rel="stylesheet" href="{{ asset('style.css') }}">
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
            <h2>Správa linek</h2>
            <div class="section">
                <h3>Přidat linku</h3>
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="zavada">
                    <form action="{{ route('linka.create') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="form">
                            <label for="Linka">Číslo linky</label>
                            <input type="number" min="1" id="cislo" name="cislo" required>
                        </div>
                        <div class="form">
                            <label for="Zastávka">První hraniční zastávka</label>
                            <select name="start" id="start" required>
                                @foreach ($zastavky as $zastavka)
                                    <option value="{{ $zastavka->ID_zastávky }}">{{ $zastavka->nazev }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form">
                            <label for="Zastávka">Druhá hraniční zastávka</label>
                            <select name="end" id="end" required>
                                @foreach ($zastavky as $zastavka)
                                    <option value="{{ $zastavka->ID_zastávky }}">{{ $zastavka->nazev }}</option>
                                @endforeach
                            </select>
                        </div>
                            <br>
                        <div class="form">
                            <input type="submit" value="Vytvořit">
                        </div>
                    </form>
                </div>
                <br>
            </div>
            <div class="section">
                <h3>Editace linky</h3>

                <form method="post" action="{{ route('getLinkyData') }}">
                    @csrf
                    <div class="select">
                        <label for="Linka">Vyberte linku:</label>
                        <select name="linka" id="linka" onchange="this.form.submit()">
                            <option value="">Vyberte</option>
                            @foreach ($linky as $linka)
                                <option value="{{ $linka->id }}">{{ $linka->id }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
                @if(isset($linkaItem))
                    <table class="plan">
                        <tr class="head">
                            <td>Číslo linky</td>
                            <td>První hraniční zastávka</td>
                            <td>Druhá hraniční zastávka</td>
                            <td>Odstranit</td>
                        </tr>
                        <tr>
                            <td>{{ $linkaItem->cislo }}</td>
                            <td>{{ $linkaItem->start }}</td>
                            <td>{{ $linkaItem->end }}</td>
                            <td>
                                <div class="form">
                                    <form method="post" action="{{ route('linka.delete') }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{$linkaItem->cislo}}">
                                        <input type="submit" value="Odstranit">
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </table>
                @endif
            </div>

            <div class="section">
                <h3>Editace zastávek pro vybranou linku</h3>
                @if(isset($linkaItem))
                <div class="form">
                    <form method="post" action="{{ route('zastavka.add') }}">
                        @csrf
                        <div class="select">
                            <label for="Linka">Přidat zastávku:</label>
                            <select name="zastavka" id="zastavka">
                                @foreach ($zastavky as $zastavka)
                                    <option value="{{ $zastavka->ID_zastávky }}">{{ $zastavka->nazev }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="select">
                            <label for="Linka">Pořadí zastávky:</label>
                            <input type="number" name="poradi" id="poradi" min="1">
                        </div>
                        <input type="hidden" name="linka" value="{{$linkaItem->cislo}}">
                        <input type="submit" value="Přidat">
                    </form>
                </div>
                <br>
                @endif
            </div>

            <div class="section">
                <h3>Seznam zastávek pro vybranou linku</h3>
                @if(isset($linkaItem))
                <table class="plan">
                    <tr class="head">
                        <td>Pořadí zastávky</td>
                        <td>Název zastávky</td>
                        <td>Odstranit</td>
                    </tr>
                    @foreach ($zastavkyLinky as $zastavka)
                    <tr>
                        <td>{{ $zastavka->poradi }}</td>
                        <td>{{ $zastavka->nazev }}</td>
                        <td>
                            <div class="form">
                                <form method="post" action="{{ route('zastavka.delete') }}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="poradi" value="{{$zastavka->poradi}}">
                                    <input type="hidden" name="linka" value="{{$linkaItem->cislo}}">
                                    <input type="submit" value="Odstranit">
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
                @endif
            </div>
        </div>
    </body>
</html>
