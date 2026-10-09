<?php

namespace App\Http\Controllers;

use App\Models\PointConversion;
use App\Models\Setting;
use App\Models\Task;
use App\Models\TaskCompletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = now()->toDateString();

        $tasks = Task::where('status', true)->latest()->get();

        $completedTaskIdsToday = TaskCompletion::where('user_id', $user->id)
            ->where('completed_date', $today)
            ->pluck('task_id')
            ->toArray();

        $totalTasksCount = $tasks->count();
        $completedCount = count(array_intersect($tasks->pluck('id')->toArray(), $completedTaskIdsToday));
        $allCompleted = $totalTasksCount > 0 && $completedCount === $totalTasksCount;

        // Check if daily completion bonus already claimed today
        $dailyBonusClaimed = TaskCompletion::where('user_id', $user->id)
            ->where('task_id', 0) // 0 indicates daily bonus completion
            ->where('completed_date', $today)
            ->exists();

        // Conversion Settings
        $pointsPerDollar = (int)Setting::get('points_per_dollar', 1000);
        $minConversionPoints = (int)Setting::get('min_conversion_points', 500);
        $dailyBonusPoints = (int)Setting::get('daily_bonus_points', 100);

        $recentConversions = $user->pointConversions()->take(5)->get();

        return view('tasks.index', compact(
            'user',
            'tasks',
            'completedTaskIdsToday',
            'totalTasksCount',
            'completedCount',
            'allCompleted',
            'dailyBonusClaimed',
            'pointsPerDollar',
            'minConversionPoints',
            'dailyBonusPoints',
            'recentConversions'
        ));
    }

    public function complete(Request $request, $id)
    {
        $user = Auth::user();
        $today = now()->toDateString();

        $task = Task::findOrFail($id);

        if (!$task->status) {
            return back()->with('error', 'هذه المهمة غير متاحة حالياً.');
        }

        if ($task->max_completions && $task->current_completions >= $task->max_completions) {
            return back()->with('error', 'تم استيفاء الحد الأقصى للمنفذين لهذه المهمة.');
        }

        // Check if already completed today
        $alreadyDone = TaskCompletion::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->where('completed_date', $today)
            ->exists();

        if ($alreadyDone) {
            return back()->with('error', 'لقد قمت بإكمال هذه المهمة اليوم بالفعل.');
        }

        DB::transaction(function () use ($user, $task, $today) {
            TaskCompletion::create([
                'user_id' => $user->id,
                'task_id' => $task->id,
                'points_earned' => $task->points_reward,
                'completed_date' => $today,
                'status' => 'completed',
            ]);

            $task->increment('current_completions');
            $user->addPoints($task->points_reward);
        });

        return back()->with('success', "تهانينا! تم إكمال المهمة وإضافة +{$task->points_reward} نقطة إلى رصيدك بنجاح! 🎉");
    }

    public function claimDailyBonus()
    {
        $user = Auth::user();
        $today = now()->toDateString();

        $tasks = Task::where('status', true)->get();
        $completedTaskIdsToday = TaskCompletion::where('user_id', $user->id)
            ->where('completed_date', $today)
            ->pluck('task_id')
            ->toArray();

        if ($tasks->count() === 0 || count(array_intersect($tasks->pluck('id')->toArray(), $completedTaskIdsToday)) < $tasks->count()) {
            return back()->with('error', 'يجب إكمال جميع مهمات اليوم أولاً لتحصل على المكافأة اليومية الكبرى!');
        }

        $alreadyClaimed = TaskCompletion::where('user_id', $user->id)
            ->where('task_id', 0)
            ->where('completed_date', $today)
            ->exists();

        if ($alreadyClaimed) {
            return back()->with('error', 'لقد استلمت المكافأة اليومية بالفعل لهذا اليوم.');
        }

        $dailyBonusPoints = (int)Setting::get('daily_bonus_points', 100);

        DB::transaction(function () use ($user, $today, $dailyBonusPoints) {
            TaskCompletion::create([
                'user_id' => $user->id,
                'task_id' => 0,
                'points_earned' => $dailyBonusPoints,
                'completed_date' => $today,
                'status' => 'completed',
            ]);

            $user->addPoints($dailyBonusPoints);
        });

        return back()->with('success', "رائع جداً! تم استلام مكافأة إكمال جميع المهمات اليومية بنجاح (+{$dailyBonusPoints} نقطة مجانية)! 🚀");
    }

    public function convertPoints(Request $request)
    {
        $user = Auth::user();

        $pointsPerDollar = (int)Setting::get('points_per_dollar', 1000);
        $minConversionPoints = (int)Setting::get('min_conversion_points', 500);

        $validated = $request->validate([
            'points' => ['required', 'integer', 'min:' . $minConversionPoints],
        ]);

        $pointsToConvert = $validated['points'];

        if ($user->points < $pointsToConvert) {
            return back()->with('error', "رصيدك من النقاط ({$user->points}) غير كافٍ لتحويل {$pointsToConvert} نقطة.");
        }

        $dollarAmount = round($pointsToConvert / $pointsPerDollar, 2);

        if ($dollarAmount <= 0) {
            return back()->with('error', 'المبلغ المحول قليل جداً.');
        }

        DB::transaction(function () use ($user, $pointsToConvert, $dollarAmount) {
            $user->deductPoints($pointsToConvert);

            PointConversion::create([
                'user_id' => $user->id,
                'points_spent' => $pointsToConvert,
                'balance_credited' => $dollarAmount,
            ]);

            $user->addBalance(
                $dollarAmount,
                "تحويل {$pointsToConvert} نقطة مكافآت إلى رصيد محفظة",
                null,
                'deposit'
            );
        });

        return back()->with('success', "تم تحويل {$pointsToConvert} نقطة بنجاح إلى \${$dollarAmount} USDT في محفظتك! يمكنك الآن استخدام هذا الرصيد في المتجر.");
    }
}
