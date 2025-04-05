<div x-show="tab === 'timesheet'">
    <div x-data="timesheetData()" x-init="loadEntries();
    fetchProjects()">
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 text-red-800 p-3 rounded mb-4">{{ session('error') }}</div>
            @endif
        </div>
        <form method="POST" action="{{ route('save-timesheet') }}">
            @csrf

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-gray-700">Project</label>
                    <select name="project_id" class="block w-full mt-1 p-2 border rounded" required>
                        <option value="">Select Project</option>
                        <template x-for="project in projects" :key="project.id">
                            <option :value="project.id" x-text="project.project"></option>
                        </template>
                    </select>
                    <p x-show="errors.project_id" x-text="errors.project_id" class="text-red-500 text-sm mt-1"></p>
                </div>


                <x-text-input class="block w-full mt-1 p-2 border rounded" type="text" name="filter"
                    placeholder="Search issue..." label="Filter Issues" />
            </div>

            <div class="mt-4">
                <x-text-input class="block w-full mt-1 p-2 border rounded" type="text" name="issue"
                    label="Issue" />
                <p x-show="errors.issue" x-text="errors.issue" class="text-red-500 text-sm mt-1"></p>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <x-text-input class="block w-full mt-1 p-2 border rounded" type="date" name="date"
                        label="Date" />
                    <p x-show="errors.date" x-text="errors.date" class="text-red-500 text-sm mt-1"></p>
                </div>
                <div>
                    <x-text-input class="block w-full mt-1 p-2 border rounded" type="number" name="duration"
                        label="Duration (hours)" />
                    <p x-show="errors.duration" x-text="errors.duration" class="text-red-500 text-sm mt-1"></p>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-gray-700">Comment</label>
                <textarea name="comment" class="block w-full mt-1 p-2 border rounded"></textarea>
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Save Time Entry
                </button>
            </div>
        </form>

        <div class="p-6">
            <h2 class="text-xl font-semibold mb-4">
                Time Entries for Week: <span x-text="weekRange"></span>
            </h2>

            <div class="flex justify-between mb-4">
                <button @click="changeWeek(-1)" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">
                    &larr; Previous Week
                </button>
                <button @click="resetToCurrentWeek()" class="px-4 py-2 bg-blue-500 text-white rounded">
                    Current Week
                </button>
                <button @click="changeWeek(1)" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">
                    Next Week &rarr;
                </button>
            </div>

            <div class="rounded-lg border shadow-sm overflow-hidden mt-6">
                <table class="w-full table-auto border-collapse">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-4 py-2 text-left">Date</th>
                            <th class="border px-4 py-2 text-left">Project</th>
                            <th class="border px-4 py-2 text-left">Issue</th>
                            <th class="border px-4 py-2 text-left">Comment</th>
                            <th class="border px-4 py-2 text-left">Duration</th>
                            <th class="border px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="day in weekDays" :key="day">
                            <template x-if="entries[day]">
                                <template x-for="entry in entries[day]" :key="entry.id">
                                    <tr class="hover:bg-gray-50">
                                        <td class="border px-4 py-2" x-text="day"></td>
                                        <td class="border px-4 py-2" x-text="entry.project"></td>
                                        <td class="border px-4 py-2" x-text="entry.issue"></td>
                                        <td class="border px-4 py-2" x-text="entry.comment"></td>
                                        <td class="border px-4 py-2 text-center" x-text="entry.hours"></td>
                                        <td class="border px-4 py-2 text-center">
                                            <button @click="editEntry(entry)"
                                                class="text-blue-600 hover:underline mr-2">Edit</button>
                                            <button @click="deleteEntry(entry.id)"
                                                class="text-red-600 hover:underline">Delete</button>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                        </template>
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 font-semibold">
                            <td colspan="4" class="border px-4 py-2 text-right">Weekly Total</td>
                            <td class="border px-4 py-2 text-center" x-text="totalHours"></td>
                            <td class="border px-4 py-2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function timesheetData() {
        return {
            currentWeekStart: null,
            weekDays: [],
            weekRange: "",
            entries: {},
            totalHours: 0,
            projects: [],
            errors: {},

            init() {
                this.resetToCurrentWeek();
            },

            resetToCurrentWeek() {
                const now = new Date();
                this.currentWeekStart = this.getStartOfWeek(now);
                this.setWeekDays();
                this.loadEntries();
            },

            changeWeek(offset) {
                this.currentWeekStart.setDate(this.currentWeekStart.getDate() + offset * 7);
                this.setWeekDays();
                this.loadEntries();
            },

            getStartOfWeek(date) {
                const d = new Date(date);
                const day = d.getDay(); // Sunday = 0
                const diff = d.getDate() - day + (day === 0 ? -6 : 1); // Adjust if Sunday
                return new Date(d.setDate(diff));
            },

            setWeekDays() {
                this.weekDays = [];
                let start = new Date(this.currentWeekStart);
                let end = new Date(start);
                end.setDate(end.getDate() + 6);

                this.weekRange = `${this.formatDateDisplay(start)} - ${this.formatDateDisplay(end)}`;

                for (let i = 0; i < 7; i++) {
                    const d = new Date(this.currentWeekStart);
                    d.setDate(d.getDate() + i);
                    this.weekDays.push(this.formatDate(d)); // e.g., "2025-04-01"
                }
            },

            formatDate(date) {
                return new Date(date).toISOString().split("T")[0];
            },

            formatDateDisplay(date) {
                return date.toLocaleDateString('en-US', {
                    month: 'short',
                    day: '2-digit'
                });
            },

            addDays(date, days) {
                const d = new Date(date);
                d.setDate(d.getDate() + days);
                return d;
            },

            async loadEntries() {
                try {
                    let response = await fetch(`/timesheet-entries?week=${this.formatDate(this.currentWeekStart)}`);
                    if (!response.ok) throw new Error("Network error");

                    let data = await response.json();

                    this.totalHours = data.totalHours;
                    this.entries = {};

                    for (let i = 0; i < 7; i++) {
                        let date = this.formatDate(this.addDays(this.currentWeekStart, i));
                        this.entries[date] = data.entries[date] ?? [];
                    }
                } catch (error) {
                    console.error("Error fetching entries:", error);
                }
            },

            async fetchProjects() {
                try {
                    let response = await fetch('/projects');
                    if (!response.ok) throw new Error('Failed to fetch projects');
                    this.projects = await response.json();
                } catch (error) {
                    console.error("Error loading projects:", error);
                }
            },

            editEntry(entry) {
                alert('Edit not implemented yet. Entry ID: ' + entry.id);
            },

            deleteEntry(id) {
                alert("Delete functionality not implemented yet.");
            },
        };
    }
</script>
