<div>
    <div class="fi-ta">
        <div
            class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-ta-header-ctn divide-y divide-gray-200 dark:divide-white/10">
                <div class="fi-ta-header flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:px-6">
                    <div class="grid gap-y-1">
                        {{-- <h3 class="fi-ta-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        Rekap Transaksi Mingguan
                    </h3> --}}
                        <div class="flex">

                            <div class="fi-fo-field-wrp w-32">
                                <div class="grid gap-y-2">
                                    <div class="flex items-center justify-between gap-x-3">
                                        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3"
                                            for="as_id">
                                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                Kas
                                            </span>
                                        </label>
                                    </div>
                                    <div class="grid auto-cols-fr gap-y-2">
                                        <div
                                            class="fi-input-wrp [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-2 [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600 dark:[&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500 fi-fo-select flex rounded-lg bg-white shadow-sm ring-1 ring-gray-950/10 transition duration-75 dark:bg-white/5 dark:ring-white/20">
                                            <div class="min-w-0 flex-1">
                                                <select
                                                    class="fi-select-input [&amp;_optgroup]:bg-white [&amp;_optgroup]:dark:bg-gray-900 [&amp;_option]:bg-white [&amp;_option]:dark:bg-gray-900 block w-full border-none bg-transparent py-1.5 pe-8 ps-3 text-base text-gray-950 transition duration-75 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] dark:text-white dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] sm:text-sm sm:leading-6"
                                                    wire:model="kas_id" wire:change="updateTable">

                                                    <option value="All">
                                                        Semua
                                                    </option>
                                                    @foreach ($kas_list as $id => $nama)
                                                        <option value="{{ $id }}">
                                                            {{ $nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="fi-fo-field-wrp w-32">
                                <div class="grid gap-y-2">
                                    <div class="flex items-center justify-between gap-x-3">
                                        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3"
                                            for="as_id">
                                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                Tanggal Awal
                                            </span>
                                        </label>
                                    </div>
                                    <div class="grid auto-cols-fr gap-y-2">
                                        <div
                                            class="fi-input-wrp [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600 dark:[&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500 m-3 flex rounded-lg bg-white shadow-sm ring-1 ring-gray-950/10 transition duration-75 dark:bg-white/5 dark:ring-white/20 [&:not(:has(.fi-ac-action:focus))]:focus-within:ring-2">
                                            <div class="min-w-0 flex-1">
                                                <input
                                                    class="fi-input block w-full border-none bg-white/0 py-1.5 pe-3 ps-0 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6"
                                                    autocomplete="off" type="date" wire:model="awal"
                                                    wire:change="updateTable">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="fi-fo-field-wrp w-32">
                                <div class="grid gap-y-2">
                                    <div class="flex items-center justify-between gap-x-3">
                                        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3"
                                            for="as_id">
                                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                Tanggal Akhir
                                            </span>
                                        </label>
                                    </div>
                                    <div class="grid auto-cols-fr gap-y-2">
                                        <div
                                            class="fi-input-wrp [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600 dark:[&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500 m-3 flex rounded-lg bg-white shadow-sm ring-1 ring-gray-950/10 transition duration-75 dark:bg-white/5 dark:ring-white/20 [&:not(:has(.fi-ac-action:focus))]:focus-within:ring-2">
                                            <div class="min-w-0 flex-1">
                                                <input
                                                    class="fi-input block w-full border-none bg-white/0 py-1.5 pe-3 ps-0 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6"
                                                    autocomplete="off" type="date" wire:model="akhir"
                                                    wire:change="updateTable">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="fi-ta-content relative divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:border-t-white/10">
                    <table
                        class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
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
                            @if (count($data))
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
                                            <div
                                                class="flex w-full justify-start text-start disabled:pointer-events-none">
                                                <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4 text-sm">
                                                    {{ $t['kas'] }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fi-ta-cell p-0 ps-1 last-of-type:pe-1 sm:ps-3 sm:last-of-type:pe-3">
                                            <div
                                                class="flex w-full justify-start text-start disabled:pointer-events-none">
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
                                            <div
                                                class="flex w-full justify-start text-start disabled:pointer-events-none">
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
                                            <div
                                                class="flex w-full justify-start text-start disabled:pointer-events-none">
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
                            @else
                                <td colspan="8"
                                    class="fi-ta-cell p-0 ps-1 last-of-type:pe-1 sm:ps-3 sm:last-of-type:pe-3">
                                    <div class="flex w-full justify-center text-center disabled:pointer-events-none">
                                        <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4 text-sm">
                                            Belum ada data
                                        </div>
                                    </div>
                                </td>
                            @endif
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
                                        {{ $t['saldo'] ?? 0 }}
                                    </span>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
