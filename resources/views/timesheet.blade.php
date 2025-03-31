<div x-show="tab === 'timesheet'" x-cloak>
    <form method="POST" action="{{ route('save-timesheet') }}">
        @csrf

        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block text-gray-700 ">Project</label>
                <select name="project" class="block w-full mt-1 p-2 border rounded">
                    <option value="">Select Project</option>
                    <option value="Project 1">Project 1</option>
                    <option value="Project 2">Project 2</option>
                    <option value="Project 3">Project 3</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 ">Filter Issues</label>
                <input type="text" name="filter" placeholder="Search issue..."
                    class="block w-full mt-1 p-2 border rounded">
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-gray-700 ">Issue</label>
            <input type="text" name="issue" class="block w-full mt-1 p-2 border rounded">
        </div>

        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block text-gray-700 ">Date</label>
                <input type="date" name="date" class="block w-full mt-1 p-2 border rounded">
            </div>

            <div>
                <label class="block text-gray-700 ">Duration (hours)</label>
                <input type="number" name="duration" class="block w-full mt-1 p-2 border rounded" min="0.5"
                    step="0.5">
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-gray-700 ">Comment</label>
            <textarea name="comment" class="block w-full mt-1 p-2 border rounded"></textarea>
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Save Time Entry
            </button>
        </div>
    </form>

    <div class="p-6">
        @php
            $today = now();
            $startOfWeek = $today->startOfWeek();
            $endOfWeek = $startOfWeek->copy()->addDays(6);
            $weekDays = [];
            for ($i = 0; $i < 7; $i++) {
                $weekDays[] = $startOfWeek->copy()->addDays($i);
            }
        @endphp

        <h2 class="text-xl font-semibold mb-4">
            Time Entries for Week: {{ $startOfWeek->format('d M') }} - {{ $endOfWeek->format('d M Y') }}
        </h2>

        <div class="flex justify-between mb-4">
            <x-primary-button variant="outline" size="sm" as="a"
                href="{{ route('timesheet', ['week' => $startOfWeek->copy()->subWeek()->format('Y-m-d')]) }}">
                <x-icon name="chevron-left" class="w-4 h-4 mr-1" /> Previous Week
            </x-primary-button>

            <x-primary-button variant="default" size="sm">
                <x-icon name="calendar" class="w-4 h-4 mr-1" /> Current Week
            </x-primary-button>

            <x-primary-button variant="outline" size="sm" as="a"
                href="{{ route('timesheet', ['week' => $startOfWeek->copy()->addWeek()->format('Y-m-d')]) }}">
                Next Week <x-icon name="chevron-right" class="w-4 h-4 ml-1" />
            </x-primary-button>
        </div>

        <div class="rounded-lg border shadow-sm overflow-hidden">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-muted text-left">
                        @foreach ($weekDays as $day)
                            <th class="border p-3 text-center">
                                <x-icon name="calendar-days" class="inline-block w-5 h-5 text-primary" />
                                <br>
                                {{ $day->format('D d') }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        @foreach ($weekDays as $day)
                            <td class="border p-4">
                                <x-badge variant="outline">Total 0 hrs</x-badge>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="bg-muted text-center">
                        <td colspan="7" class="p-3 font-semibold">
                            Week Grand Total: 0 hrs
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
