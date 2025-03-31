<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

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
}
