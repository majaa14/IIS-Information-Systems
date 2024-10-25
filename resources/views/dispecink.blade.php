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
                            <li><a class="underline" href="/">Odhlásit se</a></li>
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
            <h2>Alokace řidičů a vozidel</h2>
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
            <form method="post" action="{{ route('getDispecinkData') }}">
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
            @if(isset($spoje))
                <table class="plan">
                    <tr class="head">
                        <td>ID spoje</td>
                        <td>Čas odjezdu</td>
                        <td>Den</td>
                        <td>ID řidiče</td>
                        <td>Jméno řidiče</td>
                        <td>Vozidlo SPZ</td>
                        <td>Přidat řidiče</td>
                        <td>Přidat vozidlo</td>
                    </tr>
                    @foreach($spoje as $spoj)
                    <tr>
                        <td>{{ $spoj->ID_spoj }}</td>
                        <td>{{ \Carbon\Carbon::parse($spoj->cas_odjezdu)->format('H:i')}}</td>
                        <td>{{ $spoj->den }}</td>
                        <td>{{ $spoj->FK_řidič }}</td>
                        <td>{{ $spoj->jmeno }}</td>
                        <td>{{ $spoj->FK_vozidlo }}</td>
                        <td>
                            <div class="form">
                                <form action="{{ route('ridic.add') }}" method="post">
                                    <label for="ridic">Vyberte řidiče</label>
                                    @csrf
                                    <input type="hidden" name="spoj" value="{{ $spoj->ID_spoj }}">
                                    <select id="id" name="id" required>
                                        <option value="">Vyberte</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name . " ID:" . $user->id }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="linka" value="{{ $selectedLinka }}">
                                    <input type="submit" value="Přidat">
                                </form>
                            </div>
                        </td>
                        <td>
                            <div class="form">
                                <form action="{{ route('vozidlo.add') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="spoj" value="{{ $spoj->ID_spoj }}">
                                    <label for="Vozidlo">Vyberte vozidlo</label>
                                    <select id="spz" name="spz" required>
                                        <option value="">Vyberte</option>
                                        @foreach ($vozidla as $vozidlo)
                                            <option value="{{ $vozidlo->SPZ }}">{{ $vozidlo->SPZ }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="linka" value="{{ $selectedLinka }}">
                                    <input type="submit" value="Přidat">
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
            @endif
        </div>
    </body>
</html>