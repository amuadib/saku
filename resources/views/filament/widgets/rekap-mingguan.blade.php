<x-filament-widgets::widget>
    <div class="fi-ta">
        <div
            class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-ta-header-ctn divide-y divide-gray-200 dark:divide-white/10">
                <div class="fi-ta-header flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:px-6">
                    <div class="grid gap-y-1">
                        <h3 class="fi-ta-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                            Rekap Transaksi Mingguan
                        </h3>
                    </div>
                </div>
                <div
                    class="fi-ta-content relative divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:border-t-white/10">
                    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                        <thead class="divide-y divide-gray-200 dark:divide-white/5">
                            <tr class="bg-gray-50 dark:bg-white/5">
                                <th
                                    class="fi-ta-header-cell px-3 py-3.5 text-sm font-semibold sm:ps-6 sm:last-of-type:pe-6">
                                    <span
                                        class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                                        Tanggal
                                    </span>
                                </th>
                                <th
                                    class="fi-ta-header-cell px-3 py-3.5 text-sm font-semibold sm:ps-6 sm:last-of-type:pe-6">
                                    <span
                                        class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                                        Kas
                                    </span>
                                </th>
                                <th colspan="2"
                                    class="fi-ta-header-cell px-3 py-3.5 text-sm font-semibold sm:ps-6 sm:last-of-type:pe-6">
                                    <span
                                        class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                                        Masuk
                                    </span>
                                </th>
                                <th colspan="2"
                                    class="fi-ta-header-cell px-3 py-3.5 text-sm font-semibold sm:ps-6 sm:last-of-type:pe-6">
                                    <span
                                        class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                                        Keluar
                                    </span>
                                </th>
                                <th colspan="2"
                                    class="fi-ta-header-cell px-3 py-3.5 text-sm font-semibold sm:ps-6 sm:last-of-type:pe-6">
                                    <span
                                        class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                                        Saldo
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                            @php
                                $tgl_active = '';
                            @endphp
                            @foreach ($data as $t)
                                <tr
                                    class="fi-ta-row [@media(hover:hover)]:transition [@media(hover:hover)]:duration-75">
                                    @if ($t['tanggal'] != $tgl_active)
                                        <td rowspan="{{ $data_per_tanggal[$t['tanggal']] }}"
                                            class="fi-ta-cell p-0 ps-1 last-of-type:pe-1 sm:ps-3 sm:last-of-type:pe-3">
                                            <div
                                                class="flex w-full justify-start text-start disabled:pointer-events-none">
                                                <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4 text-sm">
                                                    {{ $t['tanggal'] }}
                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                    @php
                                        $tgl_active = $t['tanggal'];
                                    @endphp
                                    <td class="fi-ta-cell p-0 ps-1 last-of-type:pe-1 sm:ps-3 sm:last-of-type:pe-3">
                                        <div class="flex w-full justify-start text-start disabled:pointer-events-none">
                                            <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4 text-sm">
                                                {{ $t['kas'] }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fi-ta-cell p-0 ps-1 last-of-type:pe-1 sm:ps-3 sm:last-of-type:pe-3">
                                        <div class="flex w-full justify-start text-start disabled:pointer-events-none">
                                            <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4 text-sm">
                                                Rp
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fi-ta-cell p-0 ps-1 last-of-type:pe-1 sm:ps-3 sm:last-of-type:pe-3">
                                        <div class="flex w-full justify-end text-end disabled:pointer-events-none">
                                            <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4 text-sm">
                                                {{ $t['masuk'] }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fi-ta-cell p-0 ps-1 last-of-type:pe-1 sm:ps-3 sm:last-of-type:pe-3">
                                        <div class="flex w-full justify-start text-start disabled:pointer-events-none">
                                            <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4 text-sm">
                                                Rp
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fi-ta-cell p-0 ps-1 last-of-type:pe-1 sm:ps-3 sm:last-of-type:pe-3">
                                        <div class="flex w-full justify-end text-end disabled:pointer-events-none">
                                            <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4 text-sm">
                                                {{ $t['keluar'] }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fi-ta-cell p-0 ps-1 last-of-type:pe-1 sm:ps-3 sm:last-of-type:pe-3">
                                        <div class="flex w-full justify-start text-start disabled:pointer-events-none">
                                            <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4 text-sm">
                                                Rp
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fi-ta-cell p-0 ps-1 last-of-type:pe-1 sm:ps-3 sm:last-of-type:pe-3">
                                        <div class="flex w-full justify-end text-end disabled:pointer-events-none">
                                            <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4 text-sm">
                                                {{ $t['saldo'] }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50 dark:bg-white/5">
                                <th colspan="2"
                                    class="fi-ta-header-cell px-3 py-3.5 text-sm font-semibold sm:ps-6 sm:last-of-type:pe-6">
                                    <span
                                        class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                                        Total
                                    </span>
                                </th>
                                <th
                                    class="fi-ta-header-cell px-3 py-3.5 text-sm font-semibold sm:ps-6 sm:last-of-type:pe-6">
                                    <span
                                        class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                                        Rp
                                    </span>
                                </th>
                                <th
                                    class="fi-ta-header-cell px-3 py-3.5 text-sm font-semibold sm:ps-6 sm:last-of-type:pe-6">
                                    <span class="group flex w-full items-center justify-end gap-x-1 whitespace-nowrap">
                                        {{ number_format(intval($masuk), thousands_separator: '.') }}
                                    </span>
                                </th>
                                <th
                                    class="fi-ta-header-cell px-3 py-3.5 text-sm font-semibold sm:ps-6 sm:last-of-type:pe-6">
                                    <span
                                        class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                                        Rp
                                    </span>
                                </th>
                                <th
                                    class="fi-ta-header-cell px-3 py-3.5 text-sm font-semibold sm:ps-6 sm:last-of-type:pe-6">
                                    <span class="group flex w-full items-center justify-end gap-x-1 whitespace-nowrap">
                                        {{ number_format(intval($keluar), thousands_separator: '.') }}
                                    </span>
                                </th>
                                <th
                                    class="fi-ta-header-cell px-3 py-3.5 text-sm font-semibold sm:ps-6 sm:last-of-type:pe-6">
                                    <span
                                        class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                                        Rp
                                    </span>
                                </th>
                                <th
                                    class="fi-ta-header-cell px-3 py-3.5 text-sm font-semibold sm:ps-6 sm:last-of-type:pe-6">
                                    <span class="group flex w-full items-center justify-end gap-x-1 whitespace-nowrap">
                                        {{ $t['saldo'] }}
                                    </span>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
