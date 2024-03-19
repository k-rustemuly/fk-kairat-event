<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Variant;
use Illuminate\Database\Seeder;

class QuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'title_kk' => 'Какая команда лучше защитит свои ворота?',
                'title_ru' => 'Какая команда лучше защитит свои ворота?',
                'variants' => [
                    [
                        'title_kk' => 'FC «Кайрат»',
                        'title_ru' => 'FC «Кайрат»',
                    ],
                    [
                        'title_kk' => 'SD Family',
                        'title_ru' => 'SD Family',
                    ]
                ]
            ],
            [
                'title_kk' => 'Какая команда первой наберет 1000 очков?',
                'title_ru' => 'Какая команда первой наберет 1000 очков?',
                'variants' => [
                    [
                        'title_kk' => 'ФК «Кайрат»',
                        'title_ru' => 'ФК «Кайрат»',
                    ],
                    [
                        'title_kk' => 'SD Family',
                        'title_ru' => 'SD Family',
                    ],
                    [
                        'title_kk' => 'Никто',
                        'title_ru' => 'Никто',
                    ]
                ]
            ],
            [
                'title_kk' => 'Какая из команд забьет больше угловых?',
                'title_ru' => 'Какая из команд забьет больше угловых?',
                'variants' => [
                    [
                        'title_kk' => 'FC «Кайрат»',
                        'title_ru' => 'FC «Кайрат»',
                    ],
                    [
                        'title_kk' => 'SD Family',
                        'title_ru' => 'SD Family',
                    ]
                ]
            ],
            [
                'title_kk' => 'Какого цвета шапка была на девушке в нижнем ряду?',
                'title_ru' => 'Какого цвета шапка была на девушке в нижнем ряду?',
                'variants' => [
                    [
                        'title_kk' => 'Синяя',
                        'title_ru' => 'Синяя',
                    ],
                    [
                        'title_kk' => 'Желтая',
                        'title_ru' => 'Желтая',
                    ],
                    [
                        'title_kk' => 'Оранжевая',
                        'title_ru' => 'Оранжевая',
                    ],
                    [
                        'title_kk' => 'Зеленая',
                        'title_ru' => 'Зеленая',
                    ]
                ]
            ],
            [
                'title_kk' => 'Сколько мячей в воротах?',
                'title_ru' => 'Сколько мячей в воротах?',
                'variants' => []
            ],
            [
                'title_kk' => 'Узнал кому из футболистов принадлежит силуэт?',
                'title_ru' => 'Узнал кому из футболистов принадлежит силуэт?',
                'variants' => [
                    [
                        'title_kk' => 'Криштиану Роналду',
                        'title_ru' => 'Криштиану Роналду',
                    ],
                    [
                        'title_kk' => 'Элдер Сантана',
                        'title_ru' => 'Элдер Сантана',
                    ],
                    [
                        'title_kk' => 'Килиан Мбаппе',
                        'title_ru' => 'Килиан Мбаппе',
                    ],
                    [
                        'title_kk' => 'Геге',
                        'title_ru' => 'Геге',
                    ]
                ]
            ],
            [
                'title_kk' => 'Как думаешь, кто справится первым?',
                'title_ru' => 'Как думаешь, кто справится первым?',
                'variants' => [
                    [
                        'title_kk' => 'ФК Кайрат',
                        'title_ru' => 'ФК Кайрат',
                    ],
                    [
                        'title_kk' => 'Фанаты',
                        'title_ru' => 'Фанаты',
                    ]
                ]
            ],
            [
                'title_kk' => 'Ты уже знаешь правильный ответ? Как думаешь, кто победит?',
                'title_ru' => 'Ты уже знаешь правильный ответ? Как думаешь, кто победит?',
                'variants' => [
                    [
                        'title_kk' => 'ФК Кайрат',
                        'title_ru' => 'ФК Кайрат',
                    ],
                    [
                        'title_kk' => 'Фанаты',
                        'title_ru' => 'Фанаты',
                    ]
                ]
            ],
            [
                'title_kk' => 'Какая команда справится лучше?',
                'title_ru' => 'Какая команда справится лучше?',
                'variants' => [
                    [
                        'title_kk' => 'ФК Кайрат',
                        'title_ru' => 'ФК Кайрат',
                    ],
                    [
                        'title_kk' => 'Фанаты',
                        'title_ru' => 'Фанаты',
                    ]
                ]
            ],
            [
                'title_kk' => 'Какая команда надует 5 мячей быстрее?',
                'title_ru' => 'Какая команда справится лучше?',
                'variants' => [
                    [
                        'title_kk' => 'ФК Кайрат',
                        'title_ru' => 'ФК Кайрат',
                    ],
                    [
                        'title_kk' => 'Фанаты',
                        'title_ru' => 'Фанаты',
                    ],
                    [
                        'title_kk' => 'Закончат одновременно',
                        'title_ru' => 'Закончат одновременно',
                    ]
                ]
            ],
            [
                'title_kk' => 'Какая команда забьет больше мячей?',
                'title_ru' => 'Какая команда забьет больше мячей?',
                'variants' => [
                    [
                        'title_kk' => 'ФК Кайрат',
                        'title_ru' => 'ФК Кайрат',
                    ],
                    [
                        'title_kk' => 'Фанаты',
                        'title_ru' => 'Фанаты',
                    ]
                ]
            ],
        ];
        foreach($questions as $question)
        {
            $q = Question::create([
                'title_kk' => $question['title_kk'],
                'title_ru' => $question['title_ru'],
            ]);
            foreach($question['variants'] as $varinat)
            {
                Variant::create([
                    'question_id' => $q->id,
                    'title_kk' => $varinat['title_kk'],
                    'title_ru' => $varinat['title_ru'],
                ]);
            }
        }
    }
}
