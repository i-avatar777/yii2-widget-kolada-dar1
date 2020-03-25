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

    /**
     * аттрибуты для тега table
     *
     * @var array
     */
    public $tableOptions = [
        'class' => 'table table-hover table-striped'
    ];

    public function run()
    {
        $params = [
            'dayStart',
            'isSacral',
            'optionsWeek',
            'optionsColumn',
            'weekDays',
            'monthNames',
            'emptyCell',
            'isDrawIds',
            'isDrawDateGrigor',
            'DateGrigorFormat',
            'DateGrigorClass',
            'DateGrigorFirst',
            'cellFormat',
            'tableOptions',
        ];
        $rows = [];
        foreach ($params as $p) {
            $rows[$p] = $this->$p;
        }

        $v = \iAvatar777\widgets\KoladaDar\KoladaDar::init($rows);

        return $v->run();
    }
}