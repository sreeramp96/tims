<div x-show="tab === 'home'">
    <h3 class="text-lg font-semibold">Home</h3>
    <p>This is your home dashboard.</p>


    <div x-data="{ tab: null }">
        @php
            $projects = [
                ['id' => 1, 'name' => 'Project Alpha', 'code' => 'ALPHA-001'],
                ['id' => 2, 'name' => 'Project Beta', 'code' => 'BETA-002'],
                ['id' => 3, 'name' => 'Project Gamma', 'code' => 'GAMMA-003'],
            ];
        @endphp

        @foreach ($projects as $project)
            <div class="border rounded-lg mb-2">
                <button @click="open === {{ $project['id'] }} ? open = null : open = {{ $project['id'] }}"
                    class="w-full flex justify-between items-center px-4 py-2 bg-gray-100 hover:bg-gray-200">
                    <span class="font-medium">{{ $project['name'] }}</span>
                    <svg x-show="open !== {{ $project['id'] }}" class="w-5 h-5 text-gray-600" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                    <svg x-show="open === {{ $project['id'] }}" class="w-5 h-5 text-gray-600" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                </button>

                <!-- Accordion Content -->
                <div x-show="open === {{ $project['id'] }}" x-collapse class="px-4 py-2 bg-gray-50">
                    <p><strong>Project Code:</strong> {{ $project['code'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
