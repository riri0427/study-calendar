<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use JavaScript;

class CalendarController extends Controller
{
    public function top(Request $request)
    {
        // 一旦今日の日付で設定
        $date = Carbon::now();
        $year = $date->year;
        $month = $date->month;

        // requestにyearとmonthがあれば置き換える
        if ($request->year && $request->month) {
            $year = $request->year;
            $month = $request->month;
        }

        // 年が変わる場合の処理
        if ($request->path() === 'calendar/prev' && $request->month === '12') {
            $year -= 1;
        } else if ($request->path() === 'calendar/next' && $request->month === '1') {
            $year += 1;
        }

        $dateStr = sprintf('%04d-%02d-01', $year, $month);
        $date = new Carbon("{$year}-{$month}-01");

        // ビューで表示する今月、先月、来月の月を設定
        $lastMonthDate = $date->copy()->subMonths(1);
        $nextMonthDate = $date->copy()->addMonths(1);
        $months = array(
            'currentMonth' => $date->month,
            'lastMonth' => $lastMonthDate->month,
            'nextMonth' => $nextMonthDate->month
        );

        // カレンダーの日付を設定
        $dates = $this->createCalendarDates($date);

        return view('calendar/top')->with([
            'dates' => $dates,
            'months' => $months,
            'year' => $year
        ]);
    }

    private function createCalendarDates(Carbon $date)
    {
        // 月の日数を保持しておく
        $daysInMonth = $date->daysInMonth;
        // カレンダーを四角形にするため、前月となる左上の隙間用のデータを入れるためずらす
        $subDayCount = $date->dayOfWeek; // ずらした日数を保持しておく
        $date->subDay($date->dayOfWeek);
        // 同上。右下の隙間のための計算。
        // 月の日数 + 左上の隙間 + 右下の隙間 の合計を算出
        $count = $daysInMonth + $subDayCount;
        $count = ceil($count / 7) * 7;
        $dates = [];

        for ($i = 0; $i < $count; $i++, $date->addDay()) {
            // copyしないと全部同じオブジェクトを入れてしまうことになる
            $dates[] = $date->copy();
        }
        return $dates;
    }
}
