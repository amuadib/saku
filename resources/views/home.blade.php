@php
    $config = config('custom');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $config['app']['nama'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media(max-width:1520px) {
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
                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-6 w-auto">
                <span class="ml-3 text-xl text-gray-800">{{ $config['app']['singkatan'] }}<span
                        class="text-pink-500">.</span></span>
            </a>

            <nav id="nav"
                class="absolute left-0 top-0 z-50 mt-24 flex hidden h-64 w-full flex-col items-center justify-between border-t border-gray-200 bg-white pt-5 text-sm text-gray-800 md:relative md:mt-0 md:flex md:h-24 md:w-auto md:flex-row md:border-none md:bg-transparent md:py-0 lg:text-base">
                <a href="{{ url('/') }}"
                    class="transition-color ml-0 mr-0 font-bold duration-100 hover:text-indigo-600 md:ml-12 md:mr-3 lg:mr-8">Beranda</a>
                <a href="#fitur"
                    class="transition-color mr-0 font-bold duration-100 hover:text-indigo-600 md:mr-3 lg:mr-8">Fitur</a>
                <a href="#cek_tagihan"
                    class="transition-color mr-0 font-bold duration-100 hover:text-indigo-600 md:mr-3 lg:mr-8">
                    Tagihan
                </a>
                <a href="#cek_tabungan" class="transition-color font-bold duration-100 hover:text-indigo-600">
                    Tabungan
                </a>
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
                <a href="#kontak"
                    class="fold-bold relative z-40 inline-block h-full w-auto rounded bg-indigo-700 px-5 py-3 text-sm font-bold leading-none text-white shadow-md transition transition-all duration-100 duration-300 hover:shadow-xl sm:w-full lg:bg-white lg:text-indigo-700 lg:shadow-none">
                    Laporan
                </a>
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

    <!-- BEGIN HERO SECTION -->
    <div class="relative w-full items-center justify-center overflow-x-hidden lg:pb-40 lg:pt-40 xl:pb-64 xl:pt-40">
        <div
            class="container mx-auto -mt-32 flex h-full max-w-6xl flex-col items-center justify-between px-8 lg:flex-row xl:px-0">
            <div
                class="z-30 flex w-full max-w-xl flex-col items-center pt-48 text-center lg:w-1/2 lg:items-start lg:pt-20 lg:text-left xl:pt-40">
                <h1 class="relative mb-4 text-3xl font-black leading-tight text-gray-900 sm:text-6xl xl:mb-8">
                    {{ $config['app']['nama'] }} ({{ $config['app']['singkatan'] }})
                </h1>
                <p class="mb-8 pr-0 text-base text-gray-600 sm:text-lg lg:pr-20 xl:text-xl">
                    {{ $config['app']['keterangan'] }}
                </p>
                <a href="#cek_tagihan"
                    class="fold-bold relative mx-auto mt-0 inline-block w-auto self-start rounded-md border-t border-gray-200 bg-indigo-600 px-8 py-4 text-base font-bold text-white shadow-xl sm:mt-1 lg:mx-0">
                    Cek Tagihan
                </a>
                <!-- Integrates with section -->
                <div class="mt-12 hidden flex-col sm:flex lg:mt-24">
                    <p class="mb-4 text-sm font-medium uppercase tracking-widest text-gray-500">Terintegrasi dengan*
                    </p>
                    <div class="flex">
                        <img src="{{ asset('/indomaret.png') }}" alt="Indomaret"
                            class="mr-4 h-5 cursor-pointer grayscale hover:grayscale-0">
                        <img src="{{ asset('/shopeepay.png') }}" alt="Shopee Pay"
                            class="mr-4 h-5 cursor-pointer grayscale hover:grayscale-0">
                        <img src="{{ asset('/gopay.png') }}" alt="Gopay"
                            class="mr-4 h-5 cursor-pointer grayscale hover:grayscale-0">
                        <img src="{{ asset('/bca.png') }}" alt="BCA"
                            class="mr-4 h-5 cursor-pointer grayscale hover:grayscale-0">
                        <img src="{{ asset('/briva.png') }}" alt="BRI Virtual Account"
                            class="mr-4 h-5 cursor-pointer grayscale hover:grayscale-0">
                    </div>
                </div>
                <svg class="left-svg absolute left-0 -ml-64 mt-24 max-w-md" viewBox="0 0 423 423"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <defs>
                        <linearGradient x1="100%" y1="0%" x2="4.48%" y2="0%"
                            id="linearGradient-1">
                            <stop stop-color="#5C54DB" offset="0%" />
                            <stop stop-color="#6A82E7" offset="100%" />
                        </linearGradient>
                        <filter x="-9.3%" y="-6.7%" width="118.7%" height="118.7%" filterUnits="objectBoundingBox"
                            id="filter-3">
                            <feOffset dy="8" in="SourceAlpha" result="shadowOffsetOuter1" />
                            <feGaussianBlur stdDeviation="8" in="shadowOffsetOuter1" result="shadowBlurOuter1" />
                            <feColorMatrix values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0" in="shadowBlurOuter1" />
                        </filter>
                        <rect id="path-2" x="63" y="504" width="300" height="300" rx="40" />
                    </defs>
                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                        opacity=".9">
                        <g id="Desktop-HD" transform="translate(-39 -531)">
                            <g id="Hero" transform="translate(43 83)">
                                <g id="Rectangle-6" transform="rotate(45 213 654)">
                                    <use fill="#000" filter="url(#filter-3)" xlink:href="#path-2" />
                                    <use fill="url(#linearGradient-1)" xlink:href="#path-2" />
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </div>
            <div class="ms:pl-10 relative z-50 flex h-full w-full flex-col items-end justify-center lg:w-1/2">
                <div class="container relative left-0 w-full max-w-4xl lg:absolute lg:w-screen xl:max-w-6xl">
                    <img src="{{ asset('/laptop.png') }}"
                        class="mb-20 ml-0 mt-20 h-auto w-full lg:-ml-12 lg:mb-0 lg:mt-24 lg:h-full xl:mt-40">
                </div>
            </div>
        </div>
    </div>
    <!-- HERO SECTION END -->

    <!-- BEGIN FEATURES SECTION -->
    <div id="fitur"
        class="relative w-full border-t border-gray-200 px-8 py-10 md:py-16 lg:py-24 xl:px-0 xl:py-40">
        <div class="container mx-auto flex h-full max-w-6xl flex-col items-center justify-between">
            <h2 class="my-5 text-base font-medium uppercase tracking-tight text-indigo-500">Fitur SAKU</h2>
            <h3
                class="mt-2 max-w-2xl px-5 text-center text-3xl font-black leading-tight text-gray-900 sm:mt-0 sm:px-0 sm:text-6xl">
                Dirancang sesuai dengan kebutuhan Anda
            </h3>
            <div class="mt-0 flex w-full flex-col sm:mt-10 lg:mt-20 lg:flex-row">

                <div class="mx-auto mb-0 w-full max-w-md p-4 sm:mb-16 lg:mb-0 lg:w-1/3">
                    <div class="relative mr-5 flex h-full w-full flex-col items-center justify-center rounded-lg p-20">
                        <svg class="absolute h-full w-full fill-current text-gray-100" viewBox="0 0 377 340"
                            xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <g>
                                    <path
                                        d="M342.8 3.7c24.7 14 18.1 75 22.1 124s18.6 85.8 8.7 114.2c-9.9 28.4-44.4 48.3-76.4 62.4-32 14.1-61.6 22.4-95.9 28.9-34.3 6.5-73.3 11.1-95.5-6.2-22.2-17.2-27.6-56.5-47.2-96C38.9 191.4 5 151.5.9 108.2-3.1 64.8 22.7 18 61.8 8.7c39.2-9.2 91.7 19 146 16.6 54.2-2.4 110.3-35.6 135-21.6z" />
                                </g>
                            </g>
                        </svg>
                        <!-- FEATURE Icon 1 -->
                        <svg class="relative h-20 w-20" viewBox="0 0 58 58" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <defs>
                                <linearGradient x1="0%" y1="0%" x2="100%" y2="100%"
                                    id="linearGradient-1TriangleIcon1">
                                    <stop stop-color="#9C09DB" offset="0%" />
                                    <stop stop-color="#1C0FD7" offset="100%" />
                                </linearGradient>
                                <filter x="-14%" y="-10%" width="128%" height="128%"
                                    filterUnits="objectBoundingBox" id="filter-3TriangleIcon1">
                                    <feOffset dy="2" in="SourceAlpha" result="shadowOffsetOuter1" />
                                    <feGaussianBlur stdDeviation="2" in="shadowOffsetOuter1"
                                        result="shadowBlurOuter1" />
                                    <feColorMatrix
                                        values="0 0 0 0 0.141176471 0 0 0 0 0.031372549 0 0 0 0 0.501960784 0 0 0 0.15 0"
                                        in="shadowBlurOuter1" />
                                </filter>
                                <path
                                    d="M17.947 0h14.106c6.24 0 8.503.65 10.785 1.87a12.721 12.721 0 015.292 5.292C49.35 9.444 50 11.707 50 17.947v14.106c0 6.24-.65 8.503-1.87 10.785a12.721 12.721 0 01-5.292 5.292C40.556 49.35 38.293 50 32.053 50H17.947c-6.24 0-8.503-.65-10.785-1.87a12.721 12.721 0 01-5.292-5.292C.65 40.556 0 38.293 0 32.053V17.947c0-6.24.65-8.503 1.87-10.785A12.721 12.721 0 017.162 1.87C9.444.65 11.707 0 17.947 0z"
                                    id="path-2TriangleIcon1" />
                            </defs>
                            <g id="Page-1TriangleIcon1" stroke="none" stroke-width="1" fill="none"
                                fill-rule="evenodd">
                                <g id="Desktop-HDTriangleIcon1" transform="translate(-291 -1278)">
                                    <g id="FeaturesTriangleIcon1" transform="translate(170 915)">
                                        <g id="Group-9TriangleIcon1" transform="translate(0 365)">
                                            <g id="Group-8TriangleIcon1" transform="translate(125)">
                                                <g id="Rectangle-9TriangleIcon1">
                                                    <use fill="#000" filter="url(#filter-3TriangleIcon1)"
                                                        xlink:href="#path-2TriangleIcon1" />
                                                    <use fill="url(#linearGradient-1TriangleIcon1)"
                                                        xlink:href="#path-2TriangleIcon1" />
                                                </g>
                                                <g id="playTriangleIcon1" transform="translate(18 15)" fill="#FFF"
                                                    fill-rule="nonzero">
                                                    <path
                                                        d="M9.432 2.023l8.919 14.879a1.05 1.05 0 01-.384 1.452 1.097 1.097 0 01-.548.146H-.42A1.07 1.07 0 01-1.5 17.44c0-.19.052-.375.15-.538L7.567 2.023a1.092 1.092 0 011.864 0z"
                                                        id="TriangleIcon1" transform="rotate(90 8.5 10)" />
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                        <h4 class="relative mt-6 text-lg font-bold">Otomatis</h4>
                        <p class="relative mt-2 text-center text-base text-gray-600">
                            Semua pembayaran langsung tercatat secara otomatis
                        </p>
                        <a href="#_"
                            class="relative mt-2 flex text-sm font-medium text-indigo-500 underline">Learn
                            More</a>
                    </div>
                </div>

                <div class="mx-auto mb-0 w-full max-w-md p-4 sm:mb-16 lg:mb-0 lg:w-1/3">
                    <div class="relative mr-5 flex h-full w-full flex-col items-center justify-center rounded-lg p-20">
                        <svg class="absolute h-full w-full fill-current text-gray-100" viewBox="0 0 358 372"
                            xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <g>
                                    <path
                                        d="M315.7 6.5c30.2 15.1 42.6 61.8 41.5 102.5-1.1 40.6-15.7 75.2-24.3 114.8-8.7 39.7-11.3 84.3-34.3 107.2-23 22.9-66.3 23.9-114.5 30.7-48.2 6.7-101.3 19.1-123.2-4.1-21.8-23.2-12.5-82.1-21.6-130.2C30.2 179.3 2.6 141.9.7 102c-2-39.9 21.7-82.2 57.4-95.6 35.7-13.5 83.3 2.1 131.2 1.7 47.9-.4 96.1-16.8 126.4-1.6z" />
                                </g>
                            </g>
                        </svg>
                        <!-- FEATURE Icon 2 -->
                        <svg class="relative h-20 w-20" viewBox="0 0 58 58" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <defs>
                                <linearGradient x1="0%" y1="0%" x2="100%" y2="100%"
                                    id="linearGradient-1Icon2">
                                    <stop stop-color="#F2C314" offset="0%" />
                                    <stop stop-color="#FC3832" offset="100%" />
                                </linearGradient>
                                <filter x="-14%" y="-10%" width="128%" height="128%"
                                    filterUnits="objectBoundingBox" id="filter-3Icon2">
                                    <feOffset dy="2" in="SourceAlpha" result="shadowOffsetOuter1" />
                                    <feGaussianBlur stdDeviation="2" in="shadowOffsetOuter1"
                                        result="shadowBlurOuter1" />
                                    <feColorMatrix
                                        values="0 0 0 0 0.501960784 0 0 0 0 0.125490196 0 0 0 0 0 0 0 0 0.15 0"
                                        in="shadowBlurOuter1" />
                                </filter>
                                <path
                                    d="M17.947 0h14.106c6.24 0 8.503.65 10.785 1.87a12.721 12.721 0 015.292 5.292C49.35 9.444 50 11.707 50 17.947v14.106c0 6.24-.65 8.503-1.87 10.785a12.721 12.721 0 01-5.292 5.292C40.556 49.35 38.293 50 32.053 50H17.947c-6.24 0-8.503-.65-10.785-1.87a12.721 12.721 0 01-5.292-5.292C.65 40.556 0 38.293 0 32.053V17.947c0-6.24.65-8.503 1.87-10.785A12.721 12.721 0 017.162 1.87C9.444.65 11.707 0 17.947 0z"
                                    id="path-2Icon2" />
                            </defs>
                            <g id="Page-1Icon2" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Desktop-HDIcon2" transform="translate(-691 -1278)">
                                    <g id="FeaturesIcon2" transform="translate(170 915)">
                                        <g id="Group-9-CopyIcon2" transform="translate(400 365)">
                                            <g id="Group-8Icon2" transform="translate(125)">
                                                <g id="Rectangle-9Icon2">
                                                    <use fill="#000" filter="url(#filter-3Icon2)"
                                                        xlink:href="#path-2Icon2" />
                                                    <use fill="url(#linearGradient-1Icon2)"
                                                        xlink:href="#path-2Icon2" />
                                                </g>
                                                <g id="machine-learningIcon2" transform="translate(14 12)"
                                                    fill="#FFF" fill-rule="nonzero">
                                                    <path
                                                        d="M10.554 21.418v-2.68c-1.1-.204-1.932-1.143-1.932-2.271 0-.468.143-.903.388-1.267l-2.32-1.662L4.367 15.2a2.254 2.254 0 01-.005 2.541l5.28 4.05c.268-.182.577-.311.911-.373zm.892 0c.334.062.643.191.912.373l5.28-4.05a2.254 2.254 0 01-.006-2.54l-2.321-1.663L12.99 15.2c.245.364.388.8.388 1.267 0 1.128-.832 2.067-1.932 2.27v2.681zm1.538.997c.25.365.394.803.394 1.274C13.378 24.965 12.314 26 11 26s-2.378-1.035-2.378-2.311c0-.471.145-.91.394-1.274l-5.28-4.05c-.385.26-.853.413-1.358.413C1.065 18.778 0 17.743 0 16.467c0-1.129.832-2.068 1.932-2.27v-2.393C.832 11.6 0 10.662 0 9.534c0-1.277 1.065-2.312 2.378-2.312.505 0 .973.153 1.358.414l5.28-4.05a2.254 2.254 0 01-.394-1.275C8.622 1.035 9.686 0 11 0s2.378 1.035 2.378 2.311c0 .471-.145.91-.394 1.274l5.28 4.05c.385-.26.853-.413 1.358-.413C20.935 7.222 22 8.257 22 9.533c0 1.129-.832 2.068-1.932 2.27v2.393c1.1.203 1.932 1.142 1.932 2.27 0 1.277-1.065 2.312-2.378 2.312-.505 0-.973-.153-1.358-.414l-5.28 4.05zm-9.243-7.843L5.937 13l-2.196-1.572c-.27.183-.58.314-.917.376v2.392c.336.062.647.193.917.376zm.627-3.772l2.321 1.662L9.01 10.8a2.254 2.254 0 01-.388-1.267c0-1.128.832-2.067 1.932-2.27V4.582a2.403 2.403 0 01-.912-.373l-5.28 4.05a2.254 2.254 0 01.006 2.54zm13.89 3.772c.27-.183.582-.314.918-.376v-2.392a2.403 2.403 0 01-.917-.376L16.063 13l2.196 1.572zm-.62-6.313l-5.28-4.05a2.403 2.403 0 01-.912.373v2.68c1.1.204 1.932 1.143 1.932 2.271 0 .468-.143.903-.388 1.267l2.32 1.662 2.322-1.662a2.254 2.254 0 01.005-2.541zm-8 6.313A2.415 2.415 0 0111 14.156c.507 0 .977.154 1.363.416L14.559 13l-2.196-1.572a2.415 2.415 0 01-1.363.416c-.507 0-.977-.154-1.363-.416L7.441 13l2.196 1.572zM11 10.978c.821 0 1.486-.647 1.486-1.445 0-.797-.665-1.444-1.486-1.444s-1.486.647-1.486 1.444c0 .798.665 1.445 1.486 1.445zm0 6.933c.821 0 1.486-.647 1.486-1.444 0-.798-.665-1.445-1.486-1.445s-1.486.647-1.486 1.445c0 .797.665 1.444 1.486 1.444zm8.622-6.933c.82 0 1.486-.647 1.486-1.445 0-.797-.665-1.444-1.486-1.444s-1.487.647-1.487 1.444c0 .798.666 1.445 1.487 1.445zm0 6.933c.82 0 1.486-.647 1.486-1.444 0-.798-.665-1.445-1.486-1.445s-1.487.647-1.487 1.445c0 .797.666 1.444 1.487 1.444zM2.378 10.978c.821 0 1.487-.647 1.487-1.445 0-.797-.666-1.444-1.487-1.444-.82 0-1.486.647-1.486 1.444 0 .798.665 1.445 1.486 1.445zm0 6.933c.821 0 1.487-.647 1.487-1.444 0-.798-.666-1.445-1.487-1.445-.82 0-1.486.647-1.486 1.445 0 .797.665 1.444 1.486 1.444zM11 25.133c.821 0 1.486-.646 1.486-1.444 0-.798-.665-1.445-1.486-1.445s-1.486.647-1.486 1.445.665 1.444 1.486 1.444zm0-21.377c.821 0 1.486-.647 1.486-1.445S11.821.867 11 .867s-1.486.646-1.486 1.444c0 .798.665 1.445 1.486 1.445z"
                                                        id="ShapeIcon2" />
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                        <h4 class="relative mt-6 text-lg font-bold">Transparan</h4>
                        <p class="relative mt-2 text-center text-base text-gray-600">
                            Tagihan dan Pembayaran tercatat secara transparan dan real-time
                        </p>
                        <a href="#_"
                            class="relative mt-2 flex text-sm font-medium text-indigo-500 underline">Learn
                            More</a>
                    </div>
                </div>

                <div class="mx-auto mb-16 w-full max-w-md p-4 lg:mb-0 lg:w-1/3">
                    <div class="relative mr-5 flex h-full w-full flex-col items-center justify-center rounded-lg p-20">
                        <svg class="absolute h-full w-full fill-current text-gray-100" viewBox="0 0 378 410"
                            xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <g>
                                    <path
                                        d="M305.9 14.4c23.8 24.6 16.3 84.9 26.6 135.1 10.4 50.2 38.6 90.3 43.7 137.8 5.1 47.5-12.8 102.4-50.7 117.4-37.9 15.1-95.7-9.8-151.7-12.2-56.1-2.5-110.3 17.6-130-3.4-19.7-20.9-4.7-82.9-11.5-131.2C25.5 209.5-3 174.7 1.2 147c4.2-27.7 41-48.3 75-69.6C110.1 56.1 141 34.1 184 17.5c43.1-16.6 98.1-27.7 121.9-3.1z" />
                                </g>
                            </g>
                        </svg>
                        <!-- FEATURE Icon 3 -->
                        <svg class="relative h-20 w-20" viewBox="0 0 58 58" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <defs>
                                <linearGradient x1="0%" y1="0%" x2="100%" y2="100%"
                                    id="linearGradient-1Icon3">
                                    <stop stop-color="#32FBFC" offset="0%" />
                                    <stop stop-color="#3214F2" offset="100%" />
                                </linearGradient>
                                <filter x="-14%" y="-10%" width="128%" height="128%"
                                    filterUnits="objectBoundingBox" id="filter-3Icon3">
                                    <feOffset dy="2" in="SourceAlpha" result="shadowOffsetOuter1" />
                                    <feGaussianBlur stdDeviation="2" in="shadowOffsetOuter1"
                                        result="shadowBlurOuter1" />
                                    <feColorMatrix
                                        values="0 0 0 0 0.031372549 0 0 0 0 0.149019608 0 0 0 0 0.658823529 0 0 0 0.15 0"
                                        in="shadowBlurOuter1" />
                                </filter>
                                <path
                                    d="M17.947 0h14.106c6.24 0 8.503.65 10.785 1.87a12.721 12.721 0 015.292 5.292C49.35 9.444 50 11.707 50 17.947v14.106c0 6.24-.65 8.503-1.87 10.785a12.721 12.721 0 01-5.292 5.292C40.556 49.35 38.293 50 32.053 50H17.947c-6.24 0-8.503-.65-10.785-1.87a12.721 12.721 0 01-5.292-5.292C.65 40.556 0 38.293 0 32.053V17.947c0-6.24.65-8.503 1.87-10.785A12.721 12.721 0 017.162 1.87C9.444.65 11.707 0 17.947 0z"
                                    id="path-2Icon3" />
                            </defs>
                            <g id="Page-1Icon3" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Desktop-HDIcon3" transform="translate(-1091 -1278)">
                                    <g id="FeaturesIcon3" transform="translate(170 915)">
                                        <g id="Group-9-Copy-2Icon3" transform="translate(800 365)">
                                            <g id="Group-8Icon3" transform="translate(125)">
                                                <g id="Rectangle-9Icon3">
                                                    <use fill="#000" filter="url(#filter-3Icon3)"
                                                        xlink:href="#path-2Icon3" />
                                                    <use fill="url(#linearGradient-1Icon3)"
                                                        xlink:href="#path-2Icon3" />
                                                </g>
                                                <g id="smart-notificationsIcon3" transform="translate(15 11)"
                                                    fill="#FFF" fill-rule="nonzero">
                                                    <path
                                                        d="M12.519 3.243a6.808 6.808 0 00-.187 1.298h-8.44a2.595 2.595 0 00-2.595 2.594v12.973a2.595 2.595 0 002.595 2.595h12.973a2.595 2.595 0 002.594-2.595v-8.44c.445-.02.88-.084 1.298-.187v8.627A3.892 3.892 0 0116.865 24H3.892A3.892 3.892 0 010 20.108V7.135a3.892 3.892 0 013.892-3.892h8.627zm6.616 6.487a4.865 4.865 0 110-9.73 4.865 4.865 0 010 9.73z"
                                                        id="IconIcon3" />
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                        <h4 class="relative mt-6 text-lg font-bold">Pemberitahuan</h4>
                        <p class="relative mt-2 text-center text-base text-gray-600">
                            Pemberitahuan pembayaran dan tagihan melalui Whatsapp
                        </p>
                        <a href="#_"
                            class="relative mt-2 flex text-sm font-medium text-indigo-500 underline">Learn
                            More</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END FEATURES SECTION -->

    <div class="flex min-h-screen w-full flex-col items-center justify-center lg:flex-row xl:bg-gray-800">
        @livewire('cekTagihanComponent')
        @livewire('cekTabunganComponent')
    </div>

    @livewire('pengaduanComponent')

    <footer class="border-t border-gray-200 bg-white px-4 pb-8 pt-12 text-white">
        <div class="container mx-auto flex max-w-6xl flex-col justify-between overflow-hidden px-4 lg:flex-row">
            <div class="mr-4 w-full pl-12 text-left sm:pl-0 sm:text-center lg:w-1/4 lg:text-left">
                <a href="/"
                    class="block flex justify-start text-left sm:justify-center sm:text-center lg:justify-start lg:text-left">
                    <span class="flex items-start sm:items-center">
                        <img src="{{ asset('logo_full_v.png') }}" alt="" class="h-6">
                    </span>
                </a>
                <p class="mr-4 mt-6 text-base text-gray-500">Sistem Administrasi Keuangan (SAKU) SDI & SMPI Miftahul
                    Ulum.
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
                        Ketentuan</li>
                    <li><a href="#_"
                            class="inline-block px-3 py-2 text-gray-500 no-underline hover:text-gray-600">Privasi</a>
                    </li>
                    <li><a href="#_"
                            class="inline-block px-3 py-2 text-gray-500 no-underline hover:text-gray-600">
                            Syarat dan Ketentuan
                        </a></li>
                </ul>
                <div class="flex w-full flex-col text-gray-700">
                    <div class="mt-5 inline-block px-3 py-2 font-bold uppercase text-gray-800 md:mt-0">Media Sosial
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
        <div class="text-xs text-gray-500">* dalam proses pengembangan</div>
        <div class="mt-10 border-t border-gray-100 pt-4 pt-6 text-center text-gray-500">© 2024 SAKU. All rights
            reserved.</div>
    </footer>

    <!-- a little JS for the mobile nav button -->
    <script>
        if (document.getElementById('nav-mobile-btn')) {
            document.getElementById('nav-mobile-btn').addEventListener('click', function() {
                if (this.classList.contains('close')) {
                    document.getElementById('nav').classList.add('hidden');
                    this.classList.remove('close');
                } else {
                    document.getElementById('nav').classList.remove('hidden');
                    this.classList.add('close');
                }
            });
        }
    </script>
</body>

</html>
