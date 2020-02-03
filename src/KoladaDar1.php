<?php

namespace iAvatar777\widgets\KoladaDar1;

use Yii;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;


/**
 * Class KaladaDar1
 *
 * Выводит месяца в табличный календарь
 * визуально это выглядит так:
 * месяца по 41 день справа месяца по 40 дней слева
 * всего таких строк 5, последняя соответственно с одним месяцем
 *
 */
class KoladaDar1 extends Widget
{
    /** @var int 1-9 */
    public $dayStart;

    /** @var bool */
    public $isSacral = false;

    /**
     * @var array
     * массив опций для тега tr для каждой недели, индексы могут быть от 1 до 9
     */
    public $optionsWeek = [
        1 => ['style' => 'background-color: #000000; color: #ffffff;'],
        2 => ['style' => 'background-color: #ff9395;'],
        3 => ['style' => 'background-color: #ffd092;'],
        4 => ['style' => 'background-color: #fffb92;'],
        5 => ['style' => 'background-color: #ace790;'],
        6 => ['style' => 'background-color: #a1e5fe;'],
        7 => ['style' => 'background-color: #909ffa;'],
        8 => ['style' => 'background-color: #b5a4e5;'],
        9 => ['style' => 'background-color: #ffffff;'],
    ];

    /**
     * @var array
     * массив опций для тега th для каждой колонки месяца, индексы могут быть от 1 до 6
     */
    public $optionsColumn = [
        1 => ['style' => 'width: 32px;'],
        2 => ['style' => 'width: 32px;'],
        3 => ['style' => 'width: 32px;'],
        4 => ['style' => 'width: 32px;'],
        5 => ['style' => 'width: 32px;'],
        6 => ['style' => 'width: 32px;'],
    ];

    /**
     * @var array
     * массив названий недель, индексы могут быть от 1 до 9
     */
    public $weekDays = [
        1 => 'Понедельникъ',
        2 => 'Вторникъ',
        3 => 'Третейникъ',
        4 => 'Четверикъ',
        5 => 'Пятница',
        6 => 'Шестица',
        7 => 'Седьмица',
        8 => 'Осьмица',
        9 => 'Неделя',
    ];

    public $emptyCell = '';

    public function init()
    {
        parent::init();
        ob_start();
    }

    public function run()
    {
        $content = ob_get_clean();
        $head = $this->head();
        $body = $this->body();

        return Html::tag(
            'table',
            $head . $body,
            [
                'class' => 'table table-hover table-striped'
            ]
            );
    }

    private function head()
    {
        $headers = [
            ['name' => '#'],
            ['name' => 'Название'],
            ['name' => '1', 'options' => ArrayHelper::getValue($this->optionsColumn, 1, [])],
            ['name' => '2', 'options' => ArrayHelper::getValue($this->optionsColumn, 2, [])],
            ['name' => '3', 'options' => ArrayHelper::getValue($this->optionsColumn, 3, [])],
            ['name' => '4', 'options' => ArrayHelper::getValue($this->optionsColumn, 4, [])],
            ['name' => '5', 'options' => ArrayHelper::getValue($this->optionsColumn, 5, [])],
            ['name' => '6', 'options' => ArrayHelper::getValue($this->optionsColumn, 6, [])],
            ['name' => '1', 'options' => ArrayHelper::getValue($this->optionsColumn, 1, [])],
            ['name' => '2', 'options' => ArrayHelper::getValue($this->optionsColumn, 2, [])],
            ['name' => '3', 'options' => ArrayHelper::getValue($this->optionsColumn, 3, [])],
            ['name' => '4', 'options' => ArrayHelper::getValue($this->optionsColumn, 4, [])],
            ['name' => '5', 'options' => ArrayHelper::getValue($this->optionsColumn, 5, [])],
            ['name' => '6', 'options' => ArrayHelper::getValue($this->optionsColumn, 6, [])],
        ];
        $rows = [];
        foreach ($headers as $h) {
            $options = [];
            if (isset($h['options'])) {
                $options = $h['options'];
            }
            $rows[] = Html::tag('th', $h['name'], $options);
        }
        $head = Html::tag(
            'thead',
            join('', $rows),
            []
        );

        return $head;
    }

    private function body()
    {
        // заполняю пустые месяцы
        $monthArray = $this->getMonthArray($this->dayStart, $this->isSacral);

        $rowsCount = 5;
        $week = 9;
        $cols = 2;
        $weekDays = $this->weekDays;
        $rows5 = [];
        /** @var int $r  строка месяцев в календаре */
        for($r = 1; $r <= $rowsCount; $r++) {
            $rows9 = [];
            for($i = 1; $i <= 9; $i++) {
                $row = [];
                $row[0] = Html::tag('td', $i);
                $row[1] = Html::tag('td', $weekDays[$i]);
                for($j = 1; $j <= 6; $j++) {
                    $row[$j + 1] = Html::tag('td', $monthArray[($r-1)*2 + 1][$i][$j]);
                }
                if ($r < $rowsCount) {
                    for($j = 1; $j <= 6; $j++) {
                        $row[$j + 1 + 6] = Html::tag('td', $monthArray[($r-1)*2 + 2][$i][$j]);
                    }
                } else {
                    for($j = 1; $j <= 6; $j++) {
                        $row[$j + 1 + 6] = Html::tag('td', '');
                    }
                }
                $rows9[$i] = $row;
            }
            $rows5[$r] = $rows9;
        }

        $d5 = [];
        foreach ($rows5 as $r1) {
            $r = [];
            for ($g = 1; $g <= 9; $g++) {
                $tr = $r1[$g];
                $r[] = Html::tag('tr', join('', $tr), ArrayHelper::getValue($this->optionsWeek, $g, []));
            }
            $d5[] =  join('', $r);
        }
        $body = Html::tag(
            'tbody',
            join('', $d5),
            []
        );

        return $body;
    }

    /**
     * Генерирует массив месяцев для значения $this->monthArray
     *
     * @param int $day день недели с которого начинается лето от 1 до 9
     * @param bool $isSacral флаг. Это священный год? Если да то все месяца будут по 41 дню
     * @return array
     * эти месяца должны быть под индексами от 1 до 9
     * в месяце содержатся строки - недели так же это массив с индексами от 1 до 9
     * в неделе содержатся 6 дней (клеточка-столбик) с индексами от 1 до 6
     */
    public function getMonthArray($day, $isSacral = false)
    {
        $m = [];
        $d = $day; // день недели с которого начинается месяц от 1 до 9
        for($r = 1; $r <= 9; $r++) {
            $count = ($r % 2 == 1) ? 41 : ($isSacral? 41 : 40);
            $m[$r] = $this->getMonth($d, $count);
            $d = (($count + ($d-1)) % 9) + 1;
        }

        return $m;
    }

    /**
     * Генерирует месяц для значения $this->monthArray
     *
     * @param int $day день недели с которого начинается год от 1 до 9
     * @param int $count кол-во дней в месяце
     * @return array
     * в месяце содержатся строки - недели так же это массив с индексами от 1 до 9
     * в неделе содержатся 6 дней (клеточка-столбик) с индексами от 1 до 6
     */
    public function getMonth($day, $count)
    {
        $out = [];
        $add = ($count == 40)? 0 : 1;
        // заполнение пустых полей
        for ($r = 1; $r < $day; $r++) {
            $out[$r] = [ 1 => $this->emptyCell];
        }
        $d = 1; // порядковый день в месяце
        for ($r = $day; $r <= 9; $r++) {
            $out[$r] = [ 1 => $d];
            $d++;
        }
        $f = 3; // полных недель в графике по мимо первой
        $isLast = true; // флаг будет ли еще последняя обрезанная неделя?
        if ($day == 6 - $add) {
            $f = 4;
            $isLast = false;
        }
        if ($day > 6 - $add) {
            $f = 4;
        }
        for ($l = 1; $l <= $f; $l++) {
            for ($r = 1; $r <= 9; $r++) {
                $out[$r][1 + $l] = $d;
                $d++;
            }
        }
        if ($isLast) {
            $g = $count - (9*$f) - (9 - ($day - 1)); // сколько дней осталось в месяце в последнюю неделю
            for ($r = 1; $r <= $g; $r++) {
                $out[$r][$f+2] = $d;
                $d++;
            }
            for ($r = $g + 1; $r <= 9; $r++) {
                $out[$r][$f+2] = $this->emptyCell;
            }
        }
        if (!($f == 4 and $isLast == true)) {
            for ($r = 1; $r <= 9; $r++) {
                $out[$r][6] = $this->emptyCell;
            }
        }

        return $out;
    }
}