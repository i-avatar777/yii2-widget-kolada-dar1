# yii2-widget-kolada-dar1

Виджет для Yii2 для рисования календаря Коляда Дар на лето

Версия 1.0.0

Выводит месяца $monthArray в табличный календарь
визуально это выглядит так:
месяца по 41 день справа месяца по 40 дней слева
всего таких строк 5, последняя соответственно с одним месяцем

в данных $monthArray:
эти месяца должны быть под индексами от 1 до 9
в месяце содержатся строки - недели так же это массив с индексами от 1 до 9
в неделе содержатся 5 дней (клеточка-столбик) с индексами от 1 до 6

![](images/2020-01-28_11-17-51.png)

## Пример использования

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
]); 
```

`optionsWeek` - массив опций для тега `tr` для каждой недели, индексы могут быть от 1 до 9

`optionsColumn` - массив опций для тега `th` для каждой колонки месяца, индексы могут быть от 1 до 6

`monthArray` - массив месяцев, индексы от 1 до 9. Месяц это массив недель от 1 до 9. Неделя это массив дней от 1 до 6. День содержист число или строку обозначающую порядковый номер месяца