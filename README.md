# yii2-widget-kolada-dar1

Виджет для Yii2 для рисования календаря Коляда Дар на лето

Версия 1.0.2

Выводит месяца $monthArray в табличный календарь
визуально это выглядит так:
месяца по 41 день справа месяца по 40 дней слева
всего таких строк 5, последняя соответственно с одним месяцем

![](images/2020-01-28_18-00-24.png)

Пример одного первого блока с заголовком

![](images/2020-01-28_18-01-09.png)

## Пример использования

```
$monthArray = \avatar\widgets\KaladaDar1::getMonthArray(9, false);
echo \avatar\widgets\KaladaDar1::widget([
    'monthArray'  => $monthArray,
]); 
```

`monthArray` - массив месяцев, индексы от 1 до 9. Месяц это массив строк (недель) от 1 до 9. Неделя это массив ячеек (дней) от 1 до 6. Ячейка (День) содержит число обозначающую порядковый номер месяца

Расширенный вид запуска с параметрами стилей таблицы:

```
$monthArray = \avatar\widgets\KaladaDar1::getMonthArray(9, false);
echo \avatar\widgets\KaladaDar1::widget([
    'monthArray'  => $monthArray,
    'optionsWeek' => [
         1 => ['style' => 'background-color: #000000; color: #ffffff;'],
         2 => ['style' => 'background-color: #ffa6a6;'],
         3 => ['style' => 'background-color: #ffd2a6;'],
         4 => ['style' => 'background-color: #ffffa6;'],
         5 => ['style' => 'background-color: #a7fca4;'],
         6 => ['style' => 'background-color: #a6a6ff;'],
         7 => ['style' => 'background-color: #bda5d1;'],
         8 => ['style' => 'background-color: #d4a6f7;'],
         9 => ['style' => 'background-color: #ffffff;'],
     ],
    'optionsColumn' => [
         1 => ['style' => 'width: 32px;'],
         2 => ['style' => 'width: 32px;'],
         3 => ['style' => 'width: 32px;'],
         4 => ['style' => 'width: 32px;'],
         5 => ['style' => 'width: 32px;'],
         6 => ['style' => 'width: 32px;'],
     ],
    'weekDays' => [
        1 => 'Понедельникъ',
        2 => 'Вторникъ',
        3 => 'Третейникъ',
        4 => 'Четверикъ',
        5 => 'Пятница',
        6 => 'Шестица',
        7 => 'Седьмица',
        8 => 'Осьмица',
        9 => 'Неделя',
    ],
]); 
```

`optionsWeek` - массив опций для тега `tr` для каждой недели, индексы могут быть от 1 до 9

`optionsColumn` - массив опций для тега `th` для каждой колонки месяца, индексы могут быть от 1 до 6

`weekDays` - массив названий недель, индексы могут быть от 1 до 9

## Ссылки

Славяно-Арийский Календарь
http://energodar.net/ha-tha.php?str=vedy%2Fkalenda 