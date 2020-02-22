<?php

namespace iAvatar777\widgets\KoladaDar1;

use iAvatar777\services\DateRus\DateRus;
use Yii;
use yii\apidoc\models\FunctionDoc;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;


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

    /**
     * @var array
     * массив названий недель, индексы могут быть от 1 до 9
     */
    public $monthNames = [
        1 => 'Рамхатъ',
        2 => 'Айлѣтъ',
        3 => 'Бейлѣтъ',
        4 => 'Гэйлѣтъ',
        5 => 'Дайлѣтъ',
        6 => 'Элѣтъ',
        7 => 'Вэйлѣтъ',
        8 => 'Хейлѣтъ',
        9 => 'Тайлѣтъ',
    ];

    /**
     * содержимое для отображения пустой ячейки дня
     * @var string
     */
    public $emptyCell = '';

    /**
     * bool - флаг. Добавлять атрибут id в тег td (формат day_[m]_[d]) для дней
     *
     * @var bool
     */
    public $isDrawIds = false;

    /**
     * bool - флаг. Добавлять подсказки к каждому дню в виде григорианской даты? true - добавлять, false - не добавлять. По умолчанию добавлять - false.
     *
     * @var bool
     */
    public $isDrawDateGrigor = false;

    /**
     * форматы даты для подсказки если isDrawDateGrigor = true. По умолчанию PHP date() `d.m.Y`
     *
     * @var string
     */
    public $DateGrigorFormat = 'd.m.Y';

    /**
     * Название класса для григ даты  если $isDrawDateGrigor=true
     * @var
     */
    public $DateGrigorClass;

    /**
     * Дата первого дня года в григорианском календаре в формате 'Y-m-d', по умолчанию текущий
     *
     * @var string
     */
    public $DateGrigorFirst;

    /**
     * формат даты для ячейки по # БОСТ №000006-7528
     * https://github.com/i-avatar777/kon/blob/master/%D0%91%D0%9E%D0%A1%D0%A2/%D0%91%D0%9E%D0%A1%D0%A2000006-7528.md
     * 'C / j K'
     *
     * @var string | function
     */
    public $cellFormat = 'C';

    public function init()
    {
        if (is_null($this->DateGrigorFirst)) {
            $d = date('d');
            $m = date('m');
            if ($m < 9 and $d < 22) {
                $y = date('Y') - 1;
            } else {
                $y = date('Y');
            }
            $this->DateGrigorFirst = $y . '-09-22';
        }
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

        $dateGrigFirstYear = new \DateTime($this->DateGrigorFirst);

        $rowsCount = 5;
        $week = 9;
        $cols = 2;
        $weekDays = $this->weekDays;
        $rows5 = [];
        /** @var int $r  строка месяцев в календаре */
        for($r = 1; $r <= $rowsCount; $r++) {
            $rows9 = [];

            // Добавляю строку с названием месяца
            $rows9[0] = join('', [
                Html::tag('td', ''),
                Html::tag('td', ''),
                Html::tag('td', $this->monthNames[(($r-1)*2 + 1)], ['colspan' => 6]),
                ($r < $rowsCount) ? Html::tag('td', $this->monthNames[(($r-1)*2 + 2)], ['colspan' => 6]) : Html::tag('td', '', ['colspan' => 6]),
            ]);

            // Добавляю девять недель
            for($i = 1; $i <= 9; $i++) {
                $row = [];
                $row[0] = Html::tag('td', $i);
                $row[1] = Html::tag('td', $weekDays[$i]);
                $this->add6cell($row, $r, $monthArray, $i, $dateGrigFirstYear);

                // Если это не последняя строка-месяцев календаря
                if ($r < $rowsCount) {
                    $this->add6cell($row, $r, $monthArray, $i, $dateGrigFirstYear, 2);
                } else {
                    for($j = 1; $j <= 6; $j++) {
                        $row[$j + 1 + 6] = Html::tag('td', $this->emptyCell);
                    }
                }
                $rows9[$i] = $row;
            }
            $rows5[$r] = $rows9;
        }

        $d5 = [];
        foreach ($rows5 as $r1) {
            $r = [];
            $r[] = Html::tag('tr', $r1[0]);
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
     * Добавляет шесть ячеек в неделе для левого (1 - первый месяц в строке) или правого месяца (2 - второй месяц в строке)
     *
     * @param array     $row                массив где формируется строка недели
     * @param int       $r                  строка общего календаря 1-5
     * @param array     $monthArray
     * @param int       $i                  месяц рус 1-9
     * @param \DateTime $dateGrigFirstYear  Дата первого для лета 21 сентября обычно
     * @param int       $f                  1 - первый месяц в строке, 2 - второй месяц в строке
     * @throws \Exception
     */
    private function add6cell(&$row, $r, $monthArray, $i, $dateGrigFirstYear, $f = 1)
    {
        for ($j = 1; $j <= 6; $j++) {
            $options = [];
            // 1 - 9
            $monthSlav = ($r-1)*2 + $f;

            $add = ($f == 2)? 6: 0;

            if ($monthArray[$monthSlav][$i][$j] != $this->emptyCell) {
                // вычисляю дату григорианского календаря
                $d = new \DateTime($dateGrigFirstYear->format('Y-m-d'));
                if ($this->isSacral) {
                    $z = (($monthSlav-1) * 41) + ($monthArray[$monthSlav][$i][$j] - 1);
                } else {
                    $z = $this->calcKolDays($monthSlav) + ($monthArray[$monthSlav][$i][$j] - 1);
                }
                $d->add(new \DateInterval('P' . $z . 'D'));

                if ($this->isDrawDateGrigor) {
                    $options['title'] = date($this->DateGrigorFormat, $d->format('U'));
                    if ($this->DateGrigorClass) {
                        $options['class'] = $this->DateGrigorClass;
                    }
                }
                if ($this->isDrawIds) {
                    $options['id'] = 'day_' . $monthSlav . '_' . $monthArray[$monthSlav][$i][$j];
                }
                VarDumper::dump($this->cellFormat);exit();
                if ($this->cellFormat instanceof \Closure) {
                    $function = $this->cellFormat;
                    $v = $function($d, ['day' => $monthArray[$monthSlav][$i][$j]]);
                } else {
                    $v = DateRus::format($this->cellFormat, $d, ['day' => $monthArray[$monthSlav][$i][$j]]);
                }
            } else {
                $v = $monthArray[$monthSlav][$i][$j];
            }
            $row[$j + 1 + $add] = Html::tag('td', $v, $options);
        }
    }

    /**
     * Вычисляет сколько дней до начала месяца (сл) в простом лете
     * Например для 1 = 0, для 2 = 41, 3 - 81
     * @param int $i 1-9
     * @return int
     */
    private function calcKolDays($i)
    {
        if ($i % 2 == 1) {
            return (($i - 1) / 2) * 81;
        } else {
            return (($i / 2) - 1) * 81 + 41;
        }
    }

    /**
     * Генерирует массив месяцев 1-9
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
     * Генерирует месяц с ячеками 6*9
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
            $out[$r] = [ 1 => $this->emptyCell ];
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