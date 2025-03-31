<div x-show="tab === 'timesheet'">
    <div x-data="timesheetData()" x-init="fetchEntries()">
        <form method="POST" action="{{ route('save-timesheet') }}">
            @csrf

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-gray-700">Project</label>
                    <select name="project" class="block w-full mt-1 p-2 border rounded">
                        <option value="">Select Project</option>
                        <option value="Project 1">Project 1</option>
                        <option value="Project 2">Project 2</option>
                        <option value="Project 3">Project 3</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700">Filter Issues</label>
                    <input type="text" name="filter" placeholder="Search issue..."
                        class="block w-full mt-1 p-2 border rounded">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-gray-700">Issue</label>
                <input type="text" name="issue" class="block w-full mt-1 p-2 border rounded">
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-gray-700">Date</label>
                    <input type="date" name="date" class="block w-full mt-1 p-2 border rounded">
                </div>

                <div>
                    <label class="block text-gray-700">Duration (hours)</label>
                    <input type="number" name="duration" class="block w-full mt-1 p-2 border rounded" min="0.5"
                        step="0.5">
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
                <button @click="resetWeek()" class="px-4 py-2 bg-blue-500 text-white rounded">
                    Current Week
                </button>
                <button @click="changeWeek(1)" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">
                    Next Week &rarr;
                </button>
            </div>

            <div class="rounded-lg border shadow-sm overflow-hidden">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <template x-for="day in weekDays" :key="day">
                                <th class="border p-3 text-center">
                                    <span x-text="day"></span>
                                </th>
                            </template>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <template x-for="day in weekDays" :key="day">
                                <td class="border p-4">
                                    <template x-if="entries[day]">
                                        <ul>
                                            <template x-for="entry in entries[day]" :key="entry.project">
                                                <li class="p-2 border-b">
                                                    <strong x-text="entry.project"></strong> <br>
                                                    Issue: <span x-text="entry.issue"></span> <br>
                                                    Hours: <span x-text="entry.hours"></span>
                                                </li>
                                            </template>
                                        </ul>
                                    </template>
                                    <template x-if="!entries[day]">
                                        <span>No Data</span>
                                    </template>
                                </td>
                            </template>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-200 text-center">
                            <td colspan="7" class="p-3 font-semibold">
                                Week Grand Total: <span x-text="totalHours"></span> hrs
                            </td>
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
            currentWeek: new Date(),
            weekDays: [],
            weekRange: "",
            entries: {},
            totalHours: 0,

            async fetchEntries() {
                let startOfWeek = this.getStartOfWeek(this.currentWeek);
                let endOfWeek = new Date(startOfWeek);
                endOfWeek.setDate(endOfWeek.getDate() + 6);

                this.weekRange = `${this.formatDate(startOfWeek)} - ${this.formatDate(endOfWeek)}`;
                this.weekDays = this.getWeekDays(startOfWeek);

                try {
                    let response = await fetch(`/timesheet?week=${this.formatDate(startOfWeek, true)}`);
                    if (!response.ok) throw new Error("Failed to fetch timesheet data");

                    let data = await response.json();
                    console.log("Fetched Data:", data);

                    if (data.entries && Object.keys(data.entries).length > 0) {
                        this.entries = data.entries;
                        this.totalHours = data.totalHours;
                    } else {
                        console.warn("No entries found for this week.");
                    }

                } catch (error) {
                    console.error("Error fetching timesheet data:", error);
                }
            },

            changeWeek(offset) {
                let newDate = new Date(this.currentWeek);
                newDate.setDate(newDate.getDate() + offset * 7);
                this.currentWeek = newDate;
                this.fetchEntries();
            },

            resetWeek() {
                this.currentWeek = new Date();
                this.fetchEntries();
            },

            getStartOfWeek(date) {
                let d = new Date(date);
                d.setDate(d.getDate() - d.getDay());
                return d;
            },

            getWeekDays(startOfWeek) {
                let days = [];
                for (let i = 0; i < 7; i++) {
                    let day = new Date(startOfWeek);
                    day.setDate(day.getDate() + i);
                    days.push(this.formatDate(day, true));
                }
                return days;
            },

            formatDate(date, full = false) {
                return full ?
                    date.toISOString().split("T")[0] :
                    date.toLocaleDateString("en-US", {
                        month: "short",
                        day: "2-digit"
                    });
            },
        };
    }
</script>
