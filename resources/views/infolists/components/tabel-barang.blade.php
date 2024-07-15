<div>
    @php
        function format_angka($num)
        {
            return number_format(num: $num, thousands_separator: '.');
        }
    @endphp
    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
        <thead class="divide-y divide-gray-200 dark:divide-white/5">

            <tr class="bg-gray-50 dark:bg-white/5">
                <th class="fi-ta-header-cell fi-table-header-cell-keterangan px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6"
                    style=";">
                    <span class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                        <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                            Nama
                        </span>
                    </span>
                </th>
                <th class="fi-ta-header-cell fi-table-header-cell-keterangan px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6"
                    style=";">
                    <span class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                        <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                            Jumlah
                        </span>
                    </span>
                </th>
                <th class="fi-ta-header-cell fi-table-header-cell-keterangan px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6"
                    style=";">
                    <span class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                        <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                            Harga
                        </span>
                    </span>
                </th>
                <th class="fi-ta-header-cell fi-table-header-cell-keterangan px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6"
                    style=";">
                    <span class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                        <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                            Total
                        </span>
                    </span>
                </th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
            @foreach ($getState() as $b)
                <tr class="fi-ta-row [@media(hover:hover)]:transition [@media(hover:hover)]:duration-75">
                    <td
                        class="fi-ta-cell fi-table-cell-created-at p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        <div class="fi-ta-col-wrp">
                            <div class="flex w-full justify-start text-start disabled:pointer-events-none">
                                <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                    <div class="flex">
                                        <div class="flex max-w-max">
                                            <div class="fi-ta-text-item inline-flex items-center gap-1.5">
                                                <span
                                                    class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white"
                                                    style="">
                                                    {{ $b->barang->nama }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td
                        class="fi-ta-cell fi-table-cell-kas.nama p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        <div class="fi-ta-col-wrp">
                            <div class="flex w-full justify-start text-start disabled:pointer-events-none">
                                <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                    <div class="flex">
                                        <div class="flex max-w-max">
                                            <div class="fi-ta-text-item inline-flex items-center gap-1.5">
                                                <span
                                                    class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white"
                                                    style="">
                                                    {{ $b->jumlah }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td
                        class="fi-ta-cell fi-table-cell-jumlah p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        <div class="fi-ta-col-wrp">
                            <div class="flex w-full justify-start text-start disabled:pointer-events-none">
                                <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                    <div class="flex">
                                        <div class="flex max-w-max">
                                            <div class="fi-ta-text-item inline-flex items-center gap-1.5">
                                                <span
                                                    class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white"
                                                    style="">
                                                    Rp
                                                    {{ format_angka($b->harga) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td
                        class="fi-ta-cell fi-table-cell-keterangan p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                        <div class="fi-ta-col-wrp">
                            <div class="flex w-full justify-start text-start disabled:pointer-events-none">
                                <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                    <div class="flex">
                                        <div class="flex max-w-max">
                                            <div class="fi-ta-text-item inline-flex items-center gap-1.5">
                                                <span
                                                    class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white"
                                                    style="">
                                                    Rp
                                                    {{ format_angka($b->total) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
