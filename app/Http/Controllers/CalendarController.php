<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function top()
    {
        // TODO: 一旦今日の日付で設定してあるので、リクエストに応じて変えられるようにする
        $date = Carbon::now();
        $year = $date->year;
        $month = $date->month;

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
        // 月末が日曜日の場合の挙動を修正
        $addDay = ($date->copy()->endOfMonth()->isSunday()) ? 7 : 0;
        // カレンダーを四角形にするため、前月となる左上の隙間用のデータを入れるためずらす
        $date->subDay($date->dayOfWeek);
        // 同上。右下の隙間のための計算。
        // 変数に修正
        $count = 31 + $addDay + $date->dayOfWeek;
        $count = ceil($count / 7) * 7;
        $dates = [];

        for ($i = 0; $i < $count; $i++, $date->addDay()) {
            // copyしないと全部同じオブジェクトを入れてしまうことになる
            $dates[] = $date->copy();
        }
        return $dates;
    }
}
