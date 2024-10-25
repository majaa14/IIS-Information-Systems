<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link href=”https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css” rel=”stylesheet” integrity=”sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU” crossorigin=”anonymous”>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="UTF-8">
        <title>Dopravní podnik vesnice Třeskoprsky</title>
        <link rel="icon" href="/images/bus.png">
        <link rel="stylesheet" href="/style.css">
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
                            <li><a class="underline" href="/is">IS</a></li>
                        </ul>
                    </nav>
                    <div class="menu-icon" onclick="toggleMenu()">
                        <i class="fa-solid fa-bars"></i>
                    </div>
                </div>
            </div>
        </header> 
        <div class="content">
            <div class="card">
                <h2>Vyhledat spoj</h2>
                <form action="{{ route('spoj.find') }}" method="POST">
                    @csrf
                    <div class="form">
                        <label for="Spoj">Den v týdnu</label>
                        <select name="Den" id="Den">
                            <option value="0">Všechny</option>
                            <option value="1">Pondělí</option>
                            <option value="2">Úterý</option>
                            <option value="3">Středa</option>
                            <option value="4">Čtvrtek</option>
                            <option value="5">Pátek</option>
                            <option value="6">Sobota</option>
                            <option value="7">Neděle</option>
                        </select>
                    </div>
                    <div class="form">
                        <label for="Spoj">Čas odjezdu</label>
                        <input type="time" id="cas" name="cas">
                    </div>
                    <div class="form">
                        <label for="Závada">Linka</label>
                        <select name="linka" id="linka">
                            <option value="0">Všechny</option>
                            @foreach ($linky as $linka)
                                <option value="{{ $linka->cislo_linky }}">{{ $linka->cislo_linky }}</option>
                            @endforeach
                        </select>
                    </div>
                        <br>
                    <div class="form">
                        <input type="submit" value="Vyhledat spoj">
                    </div>
                </form>
                <h3>Spoje:</h3>
                    <br>
                <table class="plan">
                    <tr class="head">
                        <td>Den v týdnu</td>
                        <td>Odjezd</td>
                        <td>Linka</td>
                        <td>Počáteční zastávka</td>
                        <td>Konečná zastávka</td>
                        <td>Detail</td>
                    </tr>
                        @foreach ($spoje as $spoj)
                            <tr>
                                <td>{{ $spoj->den_v_tydnu }}</td>
                                <td>{{ \Carbon\Carbon::parse($spoj->cas_odjezdu)->format('H:i') }}</td>
                                <td>{{ $spoj->cislo_linky }}</td>
                                <td>{{ $spoj->nazev_1_zastavky }}</td>
                                <td>{{ $spoj->nazev_2_zastavky }}</td>
                                <td>
                                    <div class="form">
                                        <div class="upravit">
                                            <a href="{{ route('spoj.detail', ['id' => $spoj->id]) }}">Detail</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                </table>
            </div>
        </div>

        <script>
            function toggleMenu() {
                var menu = document.getElementById("menu");
                menu.classList.toggle("show");
            }
        </script>
    </body>
</html>
