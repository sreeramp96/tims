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
        <form :action="editingEntry ? `/timesheet-entries/${editingEntry.id}` : '{{ route('save-timesheet') }}'"
            method="POST" x-ref="entryForm">
            @csrf
            <template x-if="editingEntry">
                <input type="hidden" name="_method" value="PUT">
            </template>
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-gray-700">Project</label>
                    {{-- <select name="project_id" class="block w-full mt-1 p-2 border rounded" required> --}}
                    <select name="project_id" x-model="selectedProjectId" class="block w-full mt-1 p-2 border rounded"
                        required>
                        <option value="">Select Project</option>
                        <template x-for="project in projects" :key="project.id">
                            {{-- <option :value="project.id" x-text="project.project"></option> --}}
                            <option :value="project.id" x-text="project.project"
                                :selected="project.id == selectedProjectId"></option>
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
                            <template x-if="entries[day] && entries[day].length">
                                <template x-for="(entry, index) in entries[day]" :key="entry.id">
                                    <tr class="hover:bg-gray-50">
                                        <template x-if="index === 0">
                                            <td class="border px-4 py-2 align-top whitespace-nowrap w-32 text-sm font-medium"
                                                :rowspan="entries[day].length" x-text="day">
                                            </td>
                                        </template>
                                        <td class="border px-4 py-2" x-text="entry.project"></td>
                                        <td class="border px-4 py-2" x-text="entry.issue"></td>
                                        <td class="border px-4 py-2" x-text="entry.comment"></td>
                                        <td class="border px-4 py-2 text-center" x-text="entry.hours"></td>
                                        <td class="border px-4 py-2 text-center">
                                            <button @click="editEntry(entry)"
                                                class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15.232 5.232l3.536 3.536M9 11l6.586-6.586a2 2 0 012.828 2.828L11.828 14H9v-2z" />
                                                </svg>
                                            </button>

                                            <button @click="deleteEntry(entry.id)"
                                                class="text-red-600 hover:text-red-800" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
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
            editingEntry: null,
            selectedProjectId: '',

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
                const day = d.getDay();
                const diff = d.getDate() - day + (day === 0 ? -6 : 1);
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
                    this.weekDays.push(this.formatDate(d));
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
                this.editingEntry = entry;
                this.selectedProjectId = entry.id;
                document.querySelector('[name="issue"]').value = entry.issue;
                document.querySelector('[name="date"]').value = entry.date;
                document.querySelector('[name="duration"]').value = entry.hours;
                document.querySelector('[name="comment"]').value = entry.comment;
            },

            deleteEntry(id) {
                if (!confirm("Are you sure you want to delete this entry?")) return;

                fetch(`/timesheet-entries/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json',
                        },
                    })
                    .then(response => {
                        if (!response.ok) throw new Error("Failed to delete");
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) this.loadEntries();
                    })
                    .catch(error => console.error("Error deleting entry:", error));
            }
        };
    }
</script>
