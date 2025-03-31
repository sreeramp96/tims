<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <x-app-layout>
        <x-slot name="header">
            {{-- <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ 'Dashboard' }}
            </h2> --}}
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ 'MGS Issue Management System' }}
                        <div> {{ 'Welcome to MGS Time and Issue Management System' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif
</body>

</html>
