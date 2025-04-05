<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ 'Welcome to Time and Issue Management System' }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div x-data="{ tab: 'home' }">
            <div class="flex space-x-4 border-b pb-2">
                <button @click="tab = 'home'" class="px-4 py-2 text-sm font-medium"
                    :class="tab === 'home' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500'">
                    Home
                </button>
                <button @click="tab = 'timesheet'" class="px-4 py-2 text-sm font-medium"
                    :class="tab === 'timesheet' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500'">
                    My Timesheet
                </button>
                <button @click="tab = 'issues'" class="px-4 py-2 text-sm font-medium"
                    :class="tab === 'issues' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500'">
                    My Issues
                </button>
                <button @click="tab = 'upload'" class="px-4 py-2 text-sm font-medium"
                    :class="tab === 'upload' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500'">
                    Upload Doc
                </button>
                <button @click="tab = 'reports'" class="px-4 py-2 text-sm font-medium"
                    :class="tab === 'reports' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500'">
                    Reports
                </button>
            </div>

            <div class="mt-6 bg-white shadow-sm sm:rounded-lg p-6 text-gray-900 ">

                @include('home')

                @include('timesheet')

                <div x-show="tab === 'issues'" x-cloak>
                    <h3 class="text-lg font-semibold">My Issues</h3>
                    <p>Track and manage your reported issues.</p>
                </div>

                <div x-show="tab === 'upload'" x-cloak>
                    <h3 class="text-lg font-semibold">Upload Documents</h3>
                    <p>Upload and manage your files here.</p>
                </div>

                <div x-show="tab === 'reports'" x-cloak>
                    <h3 class="text-lg font-semibold">Reports</h3>
                    <p>Generate and view reports.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
