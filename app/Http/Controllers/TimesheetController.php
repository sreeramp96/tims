<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\TimesheetEntry;

class TimesheetController extends Controller
{
    public function index(Request $request)
    {
        $startOfWeek = Carbon::parse($request->week)->startOfWeek();
        $entries = [];
        $totalHours = 0;
        $workingDays = 5;
        $dailyHours = 8;

        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
            if ($i < $workingDays) {
                $hours = rand(4, 8);
                $entries[$day][] = [
                    'project' => 'Project ' . rand(1, 3),
                    'issue' => 'Issue ' . rand(100, 999),
                    'hours' => $hours
                ];
                $totalHours += $hours;
            }
        }

        $weekGrandTotal = max($totalHours, ($workingDays * $dailyHours));

        return response()->json([
            'entries' => $entries,
            'totalHours' => $weekGrandTotal
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'issue' => 'required|string',
            'comment' => 'nullable|string',
            'duration' => 'required|numeric|min:0.1',
            'date' => 'required|date',
        ]);

        TimesheetEntry::create([
            'user_id' => auth()->id(),
            'project_id' => $request->project_id,
            'issue' => $request->issue,
            'comment' => $request->comment,
            'duration' => $request->duration,
            'date' => $request->date,
        ]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Time entry saved successfully.')->with('tab', 'timesheet');
    }

    public function getEntries(Request $request)
    {
        $weekStart = Carbon::parse($request->week);
        $weekEnd = $weekStart->copy()->addDays(6);
        $entries = TimesheetEntry::with('project')
            ->where('user_id', auth()->id())
            ->whereBetween('date', [$weekStart, $weekEnd])
            ->get();

        $grouped = [];

        foreach ($entries as $entry) {
            $day = $entry->date;
            $grouped[$day][] = [
                'id' => $entry->id,
                'project' => $entry->project->project,
                'issue' => $entry->issue,
                'comment' => $entry->comment,
                'hours' => $entry->duration,
                'date' => $entry->date,
            ];
        }

        return response()->json([
            'entries' => $grouped,
            'totalHours' => $entries->sum('duration'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'project_id' => 'required',
            'issue' => 'required',
            'date' => 'required|date',
            'duration' => 'required|numeric',
            'comment' => 'nullable',
        ]);

        $entry = TimesheetEntry::findOrFail($id);
        $entry->update($request->all());

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Time entry updated successfully.')->with('tab', 'timesheet');
    }


    public function destroy($id)
    {
        $entry = TimesheetEntry::findOrFail($id);
        $entry->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Time entry deleted successfully.')->with('tab', 'timesheet');
    }

    // public function copyPreviousDay(Request $request)
    // {
    //     $user = auth()->user();

    //     $today = now()->startOfDay();
    //     $yesterday = now()->subDay()->startOfDay();

    //     $yesterdayEntries = TimesheetEntry::where('user_id', $user->id)
    //         ->whereDate('date', $yesterday)
    //         ->get();

    //     if ($yesterdayEntries->isEmpty()) {
    //         return response()->json(['success' => false, 'message' => 'No entries found for yesterday.']);
    //     }

    //     foreach ($yesterdayEntries as $entry) {
    //         TimesheetEntry::create([
    //             'user_id' => $entry->user_id,
    //             'date' => $today,
    //             'project_id' => $entry->project_id,
    //             'issue' => $entry->issue,
    //             'comment' => $entry->comment,
    //             'duration' => $entry->duration,
    //         ]);
    //     }

    //     return response()->json(['success' => true]);
    // }
}
