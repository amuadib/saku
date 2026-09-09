@php $config = config('custom'); @endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $config['app']['nama'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media (max-width: 1520px) {
            .left-svg {
                display: none;
            }
        }

        #nav-mobile-btn.close span:first-child {
            transform: rotate(45deg);
            top: 4px;
            position: relative;
            background: #a0aec0;
        }

        #nav-mobile-btn.close span:nth-child(2) {
            transform: rotate(-45deg);
            margin-top: 0px;
            background: #a0aec0;
        }
    </style>
</head>

<body class="overflow-x-hidden antialiased">
    <!-- Header Section -->
    <header class="relative z-50 h-24 w-full">
        <div class="container mx-auto flex h-full max-w-6xl items-center justify-center px-8 sm:justify-between xl:px-0">
            <a href="/" class="relative inline-block flex h-5 h-full items-center font-black leading-none">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-6 w-auto" />
                <span class="ml-3 text-xl text-gray-800">{{ $config['app']['singkatan'] }}<span
                        class="text-pink-500">.</span></span>
            </a>

            <nav id="nav"
                class="absolute left-0 top-0 z-50 mt-24 flex hidden h-64 w-full flex-col items-center justify-between border-t border-gray-200 bg-white pt-5 text-sm text-gray-800 md:relative md:mt-0 md:flex md:h-24 md:w-auto md:flex-row md:border-none md:bg-transparent md:py-0 lg:text-base">

                <div class="block flex w-full flex-col border-t border-gray-200 font-medium md:hidden">
                    @auth
                        <a href="{{ url('/admin') }}" class="w-full py-2 text-center font-bold text-pink-500">Dasbor</a>
                    @else
                        <a href="{{ url('/admin/login') }}"
                            class="w-full py-2 text-center font-bold text-pink-500">Login</a>
                    @endauth
                    <a href="#kontak"
                        class="fold-bold relative inline-block w-full bg-indigo-700 px-5 py-3 text-center text-sm leading-none text-white">Laporan</a>
                </div>
            </nav>

            <div
                class="absolute left-0 mt-48 hidden w-full flex-col items-center justify-center border-b border-gray-200 pb-8 md:relative md:mt-0 md:flex md:w-auto md:flex-row md:items-end md:justify-between md:border-none md:bg-transparent md:p-0">
                @auth
                    <a href="{{ url('/admin') }}"
                        class="relative z-40 mr-0 px-3 py-2 text-sm font-bold text-pink-500 sm:mr-3 md:mt-0 md:px-5 lg:text-white">Dasbor</a>
                @else
                    <a href="{{ url('/admin/login') }}"
                        class="relative z-40 mr-0 px-3 py-2 text-sm font-bold text-pink-500 sm:mr-3 md:mt-0 md:px-5 lg:text-white">Login</a>
                @endauth
                <svg class="absolute left-0 top-0 -ml-12 -mt-64 hidden w-screen max-w-3xl lg:block"
                    viewBox="0 0 818 815" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <defs>
                        <linearGradient x1="0%" y1="0%" x2="100%" y2="100%" id="c">
                            <stop stop-color="#E614F2" offset="0%" />
                            <stop stop-color="#FC3832" offset="100%" />
                        </linearGradient>
                        <linearGradient x1="0%" y1="0%" x2="100%" y2="100%" id="f">
                            <stop stop-color="#657DE9" offset="0%" />
                            <stop stop-color="#1C0FD7" offset="100%" />
                        </linearGradient>
                        <filter x="-4.7%" y="-3.3%" width="109.3%" height="109.3%" filterUnits="objectBoundingBox"
                            id="a">
                            <feOffset dy="8" in="SourceAlpha" result="shadowOffsetOuter1" />
                            <feGaussianBlur stdDeviation="8" in="shadowOffsetOuter1" result="shadowBlurOuter1" />
                            <feColorMatrix values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.15 0" in="shadowBlurOuter1" />
                        </filter>
                        <filter x="-4.7%" y="-3.3%" width="109.3%" height="109.3%" filterUnits="objectBoundingBox"
                            id="d">
                            <feOffset dy="8" in="SourceAlpha" result="shadowOffsetOuter1" />
                            <feGaussianBlur stdDeviation="8" in="shadowOffsetOuter1" result="shadowBlurOuter1" />
                            <feColorMatrix values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.2 0" in="shadowBlurOuter1" />
                        </filter>
                        <path
                            d="M160.52 108.243h497.445c17.83 0 24.296 1.856 30.814 5.342 6.519 3.486 11.635 8.602 15.12 15.12 3.487 6.52 5.344 12.985 5.344 30.815v497.445c0 17.83-1.857 24.296-5.343 30.814-3.486 6.519-8.602 11.635-15.12 15.12-6.52 3.487-12.985 5.344-30.815 5.344H160.52c-17.83 0-24.296-1.857-30.814-5.343-6.519-3.486-11.635-8.602-15.12-15.12-3.487-6.52-5.343-12.985-5.343-30.815V159.52c0-17.83 1.856-24.296 5.342-30.814 3.486-6.519 8.602-11.635 15.12-15.12 6.52-3.487 12.985-5.343 30.815-5.343z"
                            id="b" />
                        <path
                            d="M159.107 107.829H656.55c17.83 0 24.296 1.856 30.815 5.342 6.518 3.487 11.634 8.602 15.12 15.12 3.486 6.52 5.343 12.985 5.343 30.816V656.55c0 17.83-1.857 24.296-5.343 30.815-3.486 6.518-8.602 11.634-15.12 15.12-6.519 3.486-12.985 5.343-30.815 5.343H159.107c-17.83 0-24.297-1.857-30.815-5.343-6.519-3.486-11.634-8.602-15.12-15.12-3.487-6.519-5.343-12.985-5.343-30.815V159.107c0-17.83 1.856-24.297 5.342-30.815 3.487-6.519 8.602-11.634 15.12-15.12 6.52-3.487 12.985-5.343 30.816-5.343z"
                            id="e" />
                    </defs>
                    <g fill="none" fill-rule="evenodd" opacity=".9">
                        <g transform="rotate(65 416.452 409.167)">
                            <use fill="#000" filter="url(#a)" xlink:href="#b" />
                            <use fill="url(#c)" xlink:href="#b" />
                        </g>
                        <g transform="rotate(29 421.929 414.496)">
                            <use fill="#000" filter="url(#d)" xlink:href="#e" />
                            <use fill="url(#f)" xlink:href="#e" />
                        </g>
                    </g>
                </svg>
            </div>

            <div id="nav-mobile-btn"
                class="absolute right-0 top-0 z-50 mr-10 mt-8 block w-6 cursor-pointer select-none sm:mt-10 md:hidden">
                <span class="mt-2 block h-1 w-full transform rounded-full bg-gray-800 duration-200 sm:mt-1"></span>
                <span class="mt-1 block h-1 w-full transform rounded-full bg-gray-800 duration-200"></span>
            </div>
        </div>
    </header>
    <!-- End Header Section-->

    <!-- MAIN SECTION START -->
    <div class="flex w-full flex-col items-center justify-center lg:flex-row xl:py-24 xl:pt-32">
        @livewire('tagihanKeuangan', ['kode' => request()->query('kode')])
    </div>
    <!-- MAIN SECTION END -->

    <footer class="border-t border-gray-200 bg-white px-4 pb-8 pt-12 text-white">
        <div class="container mx-auto flex max-w-6xl flex-col justify-between overflow-hidden px-4 lg:flex-row">
            <div class="mr-4 w-full pl-12 text-left sm:pl-0 sm:text-center lg:w-1/4 lg:text-left">
                <a href="/"
                    class="block flex justify-start text-left sm:justify-center sm:text-center lg:justify-start lg:text-left">
                    <span class="flex items-start sm:items-center">
                        <img src="{{ asset('logo_full_v.png') }}" alt="" class="h-6" />
                    </span>
                </a>
                <p class="mr-4 mt-6 text-base text-gray-500">
                    Sistem Administrasi Keuangan (SAKU) SDI & SMPI Miftahul Ulum.
                </p>
            </div>
            <div class="mt-6 block w-full pl-10 text-sm sm:flex lg:mt-0 lg:w-3/4">
                <ul class="flex w-full list-none flex-col p-0 text-left font-medium text-gray-700">
                    <li class="mt-5 inline-block px-3 py-2 font-bold uppercase tracking-wide text-gray-800 md:mt-0">
                        Lembaga
                    </li>
                    <li>
                        <a href="https://sdi.miftahululum.web.id" target="_blank" rel="noopener noreferrer"
                            class="inline-block px-3 py-2 text-gray-500 no-underline hover:text-gray-600">SDI Miftahul
                            Ulum Klemunan</a>
                    </li>
                    <li>
                        <a href="https://smpi.miftahululum.web.id" target="_blank" rel="noopener noreferrer"
                            class="inline-block px-3 py-2 text-gray-500 no-underline hover:text-gray-600">SMPI Miftahul
                            Ulum</a>
                    </li>
                </ul>
                <ul class="flex w-full list-none flex-col p-0 text-left font-medium text-gray-700">
                    <li class="mt-5 inline-block px-3 py-2 font-bold uppercase tracking-wide text-gray-800 md:mt-0">
                        Aplikasi
                    </li>
                    <li>
                        <a href="https://app.miftahululum.web.id" target="_blank" rel="noopener noreferrer"
                            class="inline-block px-3 py-2 text-gray-500 no-underline hover:text-gray-600">APP</a>
                    </li>
                    <li>
                        <a href="https://saku.miftahululum.web.id" target="_blank" rel="noopener noreferrer"
                            class="inline-block px-3 py-2 text-gray-500 no-underline hover:text-gray-600">
                            SAKU
                        </a>
                    </li>
                </ul>
                <div class="flex w-full flex-col text-gray-700">
                    <div class="mt-5 inline-block px-3 py-2 font-bold uppercase text-gray-800 md:mt-0">
                        Media Sosial
                    </div>
                    <div class="mt-2 flex justify-start pl-4">
                        <a class="mr-6 block flex items-center text-gray-400 no-underline hover:text-gray-600"
                            target="_blank" rel="noopener noreferrer" href="">
                            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M23.998 12c0-6.628-5.372-12-11.999-12C5.372 0 0 5.372 0 12c0 5.988 4.388 10.952 10.124 11.852v-8.384H7.078v-3.469h3.046V9.356c0-3.008 1.792-4.669 4.532-4.669 1.313 0 2.686.234 2.686.234v2.953H15.83c-1.49 0-1.955.925-1.955 1.874V12h3.328l-.532 3.469h-2.796v8.384c5.736-.9 10.124-5.864 10.124-11.853z" />
                            </svg>
                        </a>
                        <a class="mr-6 block flex items-center text-gray-400 no-underline hover:text-gray-600"
                            target="_blank" rel="noopener noreferrer" href="">
                            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M23.954 4.569a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.691 8.094 4.066 6.13 1.64 3.161a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.061a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.937 4.937 0 004.604 3.417 9.868 9.868 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63a9.936 9.936 0 002.46-2.548l-.047-.02z" />
                            </svg>
                        </a>
                        <a class="block flex items-center text-gray-400 no-underline hover:text-gray-600"
                            target="_blank" rel="noopener noreferrer" href="https://github.com/amuadib/saku">
                            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-10 border-t border-gray-100 pt-4 pt-6 text-center text-gray-500">
            © 2026 SAKU. All rights reserved.
        </div>
    </footer>

    <!-- a little JS for the mobile nav button -->
    <script>
        if (document.getElementById("nav-mobile-btn")) {
            document
                .getElementById("nav-mobile-btn")
                .addEventListener("click", function() {
                    if (this.classList.contains("close")) {
                        document.getElementById("nav").classList.add("hidden");
                        this.classList.remove("close");
                    } else {
                        document.getElementById("nav").classList.remove("hidden");
                        this.classList.add("close");
                    }
                });
        }
    </script>
</body>

</html>
