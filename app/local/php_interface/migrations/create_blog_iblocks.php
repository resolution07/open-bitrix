<?php

/**
 * Создание инфоблока блога и демонстрационного наполнения.
 *
 * Файл идемпотентен: повторный запуск не создаёт дубликаты, а обновляет
 * существующие тип, инфоблок, свойства, рубрики и материалы.
 *
 * Запускается из local/php_interface/migrations/setup_blog.php.
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

\Bitrix\Main\Loader::includeModule('iblock');

const BLOG_DEMO_TYPE = 'blog';
const BLOG_DEMO_IBLOCK_CODE = 'blog_news';

/**
 * Палитры и данные демонстрационных статей.
 */
function blog_demo_articles(): array
{
    return [
        [
            'code' => 'local-ai-in-newsrooms',
            'name' => 'Как локальные модели ИИ меняют работу редакций',
            'section' => 'tech',
            'date' => '28.10.2026 09:30:00',
            'author' => 'Анна Ковалёва',
            'role' => 'Редактор технологий',
            'reading' => '8 мин',
            'tags' => ['ИИ', 'редакции', 'технологии'],
            'featured' => 'Y',
            'palette' => ['#1f2a44', '#df4f2a', '#f4e7d3'],
            'preview' => 'Модели, которые работают на собственном железе редакции, перестали быть экспериментом. Разбираемся, что это меняет в ежедневной работе журналистов.',
            'intro' => 'Ещё год назад локальные модели казались дорогой игрушкой для инженеров. Сегодня небольшие редакции ставят их на собственный сервер и учатся жить с новым инструментом.',
            'blocks' => [
                ['type' => 'p', 'text' => 'Главное изменение — не скорость генерации текста, а предсказуемость. Данные не уходят в облако, расходы фиксированы, а редакция сама решает, какие материалы попадают в обучающую выборку.'],
                ['type' => 'h2', 'text' => 'Что действительно экономит время'],
                ['type' => 'p', 'text' => 'Черновики расшифровок, первичная разметка интервью, подбор синонимов для заголовков — рутина, которая раньше съедала по часу в день. Теперь её выполняет модель, а человек проверяет результат.'],
                ['type' => 'quote', 'text' => 'Мы не просим ИИ писать за нас. Мы просим его убрать шум, чтобы осталось время на разговор с людьми.'],
                ['type' => 'p', 'text' => 'Второй эффект — единый стиль. Редакционный гид, загруженный в модель, помогает новым авторам быстрее понять интонацию издания.'],
                ['type' => 'h2', 'text' => 'Где начинаются ограничения'],
                ['type' => 'ul', 'items' => [
                    'Модель не проверяет факты — это по-прежнему задача редактора.',
                    'Локальное железо требует обслуживания и резервных копий.',
                    'Права на обучающие данные нужно фиксировать до, а не после запуска.',
                ]],
                ['type' => 'p', 'text' => 'Пока рано говорить о новой профессии. Но навык формулировать задачу для модели становится таким же рабочим, как умение брать интервью.'],
            ],
        ],
        [
            'code' => 'wired-internet-returns',
            'name' => 'Проводной интернет возвращается: новая ставка на надёжность',
            'section' => 'tech',
            'date' => '24.10.2026 11:00:00',
            'author' => 'Илья Макаров',
            'role' => 'Обозреватель',
            'reading' => '6 мин',
            'tags' => ['интернет', 'инфраструктура'],
            'featured' => 'N',
            'palette' => ['#123b3a', '#57b8a6', '#f0f4e8'],
            'preview' => 'После десятилетия ставки на мобильность города снова прокладывают кабель. Причина проста: беспроводная сеть не всегда выдерживает нагрузку.',
            'intro' => 'Оптоволокно снова стало аргументом в разговоре о качестве жизни. Застройщики возвращают провод в квартиры, а операторы — в тарифы.',
            'blocks' => [
                ['type' => 'p', 'text' => 'Спрос растёт не из-за ностальгии, а из-за домашней работы. Видеозвонки, облачные хранилища и удалённые рабочие места требуют стабильного канала, а не пиковой скорости.'],
                ['type' => 'h2', 'text' => 'Надёжность важнее гигабитов'],
                ['type' => 'p', 'text' => 'Пользователи всё чаще выбирают тариф с меньшей скоростью, но с гарантией. Операторы отвечают резервными линиями и понятными условиями обслуживания.'],
                ['type' => 'quote', 'text' => 'Хорошая сеть — та, о которой не думаешь. Это скучная инфраструктура, и в этом её ценность.'],
                ['type' => 'h2', 'text' => 'Что изменится в городах'],
                ['type' => 'ul', 'items' => [
                    'Кабель станет частью базового пакета при сдаче жилья.',
                    'Появятся общие стандарты резервирования для небольших домов.',
                    'Вырастет спрос на локальные дата-центры.',
                ]],
                ['type' => 'p', 'text' => 'Возвращение провода не отменяет мобильность. Оно лишь напоминает: у каждой технологии есть свой сценарий, в котором она незаменима.'],
            ],
        ],
        [
            'code' => 'smart-home-after-hype',
            'name' => 'Что осталось от идеи умного дома',
            'section' => 'tech',
            'date' => '18.10.2026 08:15:00',
            'author' => 'Сергей Гончаров',
            'role' => 'Автор',
            'reading' => '7 мин',
            'tags' => ['умный дом', 'устройства'],
            'featured' => 'N',
            'palette' => ['#2c2a4a', '#8b7bd8', '#efeaf6'],
            'preview' => 'Умные лампы перестали быть чудом, а приложение для чайника — поводом для гордости. Что из обещаний умного дома действительно прижилось?',
            'intro' => 'Идея умного дома прошла стадию восторга и стадию разочарования. Сейчас видно, что осталось в повседневности, а что вернулось на полку.',
            'blocks' => [
                ['type' => 'p', 'text' => 'Прижились сценарии, которые не требуют внимания: автоматическое освещение, климат, уведомления о протечке. Всё остальное слишком часто требует телефона.'],
                ['type' => 'h2', 'text' => 'Побеждает невидимая автоматизация'],
                ['type' => 'p', 'text' => 'Люди не хотят управлять домом. Они хотят, чтобы дом сам поддерживал комфорт и не мешал. Поэтому выигрывают устройства с простыми правилами и локальной обработкой данных.'],
                ['type' => 'quote', 'text' => 'Хороший умный дом не заметен. Если вам приходится открывать приложение, чтобы включить свет, что-то пошло не так.'],
                ['type' => 'h2', 'text' => 'Три урока десятилетия'],
                ['type' => 'ul', 'items' => [
                    'Единый протокол важнее количества функций.',
                    'Локальная работа ценнее облачных сценариев.',
                    'Ремонтопригодность — часть пользовательского опыта.',
                ]],
                ['type' => 'p', 'text' => 'Следующий шаг — не новые гаджеты, а честная совместимость. Пока она остаётся главным дефицитом.'],
            ],
        ],
        [
            'code' => 'quiet-interface-fewer-buttons',
            'name' => 'Тихий интерфейс: почему меньше кнопок — больше смысла',
            'section' => 'design',
            'date' => '26.10.2026 10:20:00',
            'author' => 'Мария Лебедева',
            'role' => 'Дизайн-редактор',
            'reading' => '5 мин',
            'tags' => ['интерфейсы', 'минимализм'],
            'featured' => 'Y',
            'palette' => ['#f1e7d8', '#c8553d', '#2b2b28'],
            'preview' => 'Интерфейсы последних лет соревновались в количестве функций. Новое поколение продуктов возвращает тишину и ясность.',
            'intro' => 'Тихий интерфейс не означает пустой. Он означает, что каждый элемент получил право на существование.',
            'blocks' => [
                ['type' => 'p', 'text' => 'Пользователь приходит с задачей, а не с желанием изучать продукт. Чем меньше визуального шума, тем быстрее он доходит до результата.'],
                ['type' => 'h2', 'text' => 'Иерархия вместо украшений'],
                ['type' => 'p', 'text' => 'Хороший экран отвечает на три вопроса: где я, что главное, что делать дальше. Всё, что не помогает ответить, можно убрать.'],
                ['type' => 'quote', 'text' => 'Минимализм — это не отсутствие деталей, а отсутствие лишних.'],
                ['type' => 'h2', 'text' => 'Как проверить решение'],
                ['type' => 'ul', 'items' => [
                    'Уберите элемент и посмотрите, стала ли задача сложнее.',
                    'Проверьте экран на маленьком устройстве и при слабом освещении.',
                    'Оставьте один главный акцент, а не три.',
                ]],
                ['type' => 'p', 'text' => 'Тишина в интерфейсе — это уважение к вниманию. И, как показывает практика, она хорошо продаётся.'],
            ],
        ],
        [
            'code' => 'colour-of-the-year-tired',
            'name' => 'Цвет года устал: как индустрия ищет новые палитры',
            'section' => 'design',
            'date' => '20.10.2026 12:40:00',
            'author' => 'Ольга Ветрова',
            'role' => 'Автор',
            'reading' => '6 мин',
            'tags' => ['цвет', 'брендинг'],
            'featured' => 'N',
            'palette' => ['#3a2f4f', '#e0a458', '#f7efe2'],
            'preview' => 'Ежегодные цветовые прогнозы всё чаще воспринимаются как маркетинговый ритуал. Дизайнеры ищут более честные источники вдохновения.',
            'intro' => 'Цвет года был удобной точкой опоры. Теперь бренды всё чаще собирают палитры из собственного контекста, а не из пресс-релиза.',
            'blocks' => [
                ['type' => 'p', 'text' => 'Глобальные прогнозы не успевают за локальными сценами. То, что важно в одном городе, не работает в другом, и это нормально.'],
                ['type' => 'h2', 'text' => 'Палитра как часть истории'],
                ['type' => 'p', 'text' => 'Сильные бренды начинают с материала, среды и аудитории. Цвет становится следствием, а не отправной точкой.'],
                ['type' => 'quote', 'text' => 'Мы перестали спрашивать, какой цвет в моде. Мы спрашиваем, какой цвет принадлежит этому месту.'],
                ['type' => 'h2', 'text' => 'Что приходит на смену'],
                ['type' => 'ul', 'items' => [
                    'Локальные исследования городской среды.',
                    'Архивы и исторические материалы.',
                    'Совместные палитры с сообществами.',
                ]],
                ['type' => 'p', 'text' => 'Отказ от единого прогноза не отменяет трендов. Он делает их разнообразнее и честнее.'],
            ],
        ],
        [
            'code' => 'city-benches-small-architecture',
            'name' => 'Городские скамейки как маленькая архитектура',
            'section' => 'design',
            'date' => '12.10.2026 09:05:00',
            'author' => 'Павел Никитин',
            'role' => 'Архитектурный обозреватель',
            'reading' => '4 мин',
            'tags' => ['город', 'архитектура'],
            'featured' => 'N',
            'palette' => ['#4d3d2f', '#9c7a54', '#f1ece3'],
            'preview' => 'Скамейка кажется мелочью, но именно она задаёт сценарий площади, парка и двора.',
            'intro' => 'Городская мебель — самый доступный интерфейс города. Скамейка приглашает сесть, ждать, разговаривать или просто смотреть.',
            'blocks' => [
                ['type' => 'p', 'text' => 'Форма, высота, наклон и расположение решают, кто сможет ей воспользоваться. Универсальное решение почти всегда хуже точного.'],
                ['type' => 'h2', 'text' => 'Детали важнее силуэта'],
                ['type' => 'p', 'text' => 'Подлокотники помогают пожилым людям, спинка задаёт направление взгляда, а тень рядом с деревом продлевает сезон использования.'],
                ['type' => 'quote', 'text' => 'Скамейка — это не предмет, а приглашение.'],
                ['type' => 'h2', 'text' => 'Что стоит проверять'],
                ['type' => 'ul', 'items' => [
                    'Можно ли сесть, не переступая через грязь.',
                    'Хватает ли места рядом с коляской или велосипедом.',
                    'Есть ли тень в середине дня.',
                ]],
                ['type' => 'p', 'text' => 'Хорошая скамейка не требует объяснений. Она просто работает — и делает город чуть более человечным.'],
            ],
        ],
        [
            'code' => 'attention-economy-stops-growing',
            'name' => 'Экономика внимания больше не растёт',
            'section' => 'business',
            'date' => '27.10.2026 14:10:00',
            'author' => 'Дмитрий Соколов',
            'role' => 'Экономический обозреватель',
            'reading' => '7 мин',
            'tags' => ['экономика', 'медиа'],
            'featured' => 'Y',
            'palette' => ['#1d2b24', '#7ea172', '#f0ece0'],
            'preview' => 'Рекламный рынок привык к бесконечному росту вовлечённости. Похоже, у этого роста появился естественный предел.',
            'intro' => 'Человеческое внимание конечно. Индустрия, которая десять лет жила его перераспределением, впервые столкнулась с потолком.',
            'blocks' => [
                ['type' => 'p', 'text' => 'Число часов в сутках не изменилось, а число каналов выросло. Дальнейший рост возможен только за счёт качества, а не объёма.'],
                ['type' => 'h2', 'text' => 'Что это значит для медиа'],
                ['type' => 'p', 'text' => 'Выигрывают издания с ясной аудиторией и прямыми отношениями с читателями. Проигрывают те, кто зависел от алгоритмической ленты.'],
                ['type' => 'quote', 'text' => 'Внимание перестало быть бесконечным ресурсом. Теперь это актив, за который нужно отвечать.'],
                ['type' => 'h2', 'text' => 'Новые метрики'],
                ['type' => 'ul', 'items' => [
                    'Возвращаемость читателей важнее разовых просмотров.',
                    'Глубина чтения важнее времени в ленте.',
                    'Доверие важнее охвата.',
                ]],
                ['type' => 'p', 'text' => 'Рынок не заканчивается. Он становится взрослее — и это хорошая новость для тех, кто умеет работать вдолгую.'],
            ],
        ],
        [
            'code' => 'small-publishers-without-platforms',
            'name' => 'Малые издательства учатся жить без рекламных платформ',
            'section' => 'business',
            'date' => '21.10.2026 10:50:00',
            'author' => 'Елена Романова',
            'role' => 'Автор',
            'reading' => '6 мин',
            'tags' => ['издательства', 'подписка'],
            'featured' => 'N',
            'palette' => ['#402b2b', '#b06a5b', '#f5ede8'],
            'preview' => 'Небольшие независимые издательства всё чаще строят прямые отношения с читателями. Это дороже, но устойчивее.',
            'intro' => 'Рекламные платформы давали охват, но забирали контроль. Новое поколение издателей выбирает меньшую аудиторию, которой можно доверять.',
            'blocks' => [
                ['type' => 'p', 'text' => 'Подписка, события, печатные тиражи и совместные проекты с сообществами — источники дохода, которые не зависят от одного алгоритма.'],
                ['type' => 'h2', 'text' => 'Дорогая независимость'],
                ['type' => 'p', 'text' => 'Прямые продажи требуют времени и компетенций. Зато читатель знает, за что платит, а редакция понимает, ради кого работает.'],
                ['type' => 'quote', 'text' => 'Лучше тысяча людей, которые ждут следующий номер, чем миллион, который пролистал ленту.'],
                ['type' => 'h2', 'text' => 'Что помогает выжить'],
                ['type' => 'ul', 'items' => [
                    'Прозрачная редакционная политика.',
                    'Регулярный ритм публикаций.',
                    'Прямой диалог с подписчиками.',
                ]],
                ['type' => 'p', 'text' => 'Универсального рецепта нет. Но отказ от погони за охватом даёт главное — возможность делать своё.'],
            ],
        ],
        [
            'code' => 'companies-count-money-again',
            'name' => 'Почему компании снова считают деньги на пальцах',
            'section' => 'business',
            'date' => '14.10.2026 09:40:00',
            'author' => 'Артём Белов',
            'role' => 'Автор',
            'reading' => '5 мин',
            'tags' => ['финансы', 'управление'],
            'featured' => 'N',
            'palette' => ['#23303a', '#6f9fb5', '#eef2f4'],
            'preview' => 'После эпохи сложных финансовых моделей бизнес возвращается к простым вопросам: сколько мы зарабатываем и на что тратим.',
            'intro' => 'Простота в финансах снова стала конкурентным преимуществом. Не потому что модели плохи, а потому что их перестали понимать.',
            'blocks' => [
                ['type' => 'p', 'text' => 'Когда каждая строка отчёта требует отдельного объяснения, управление замедляется. Компании сокращают число метрик и возвращают владельцам ясную картину.'],
                ['type' => 'h2', 'text' => 'Меньше метрик, больше смысла'],
                ['type' => 'p', 'text' => 'Три-четыре ключевых показателя, которые команда может назвать по памяти, работают лучше двадцати страниц дашбордов.'],
                ['type' => 'quote', 'text' => 'Если вы не можете объяснить модель на салфетке, вы не управляете ею.'],
                ['type' => 'h2', 'text' => 'Практика возвращения к основам'],
                ['type' => 'ul', 'items' => [
                    'Считать деньги по неделям, а не по кварталам.',
                    'Разделять постоянные и переменные расходы.',
                    'Проверять решения на малом масштабе.',
                ]],
                ['type' => 'p', 'text' => 'Простота не отменяет сложность мира. Она помогает принимать решения, когда данных всё равно недостаточно.'],
            ],
        ],
        [
            'code' => 'sleep-and-memory-new-research',
            'name' => 'Сон и память: что показали новые исследования',
            'section' => 'science',
            'date' => '25.10.2026 08:00:00',
            'author' => 'Наталья Волкова',
            'role' => 'Научный обозреватель',
            'reading' => '7 мин',
            'tags' => ['сон', 'память', 'нейронауки'],
            'featured' => 'Y',
            'palette' => ['#1b2440', '#5a6fb0', '#eef0f8'],
            'preview' => 'Новые работы уточняют, как именно сон помогает закреплять воспоминания и почему его качество важнее длительности.',
            'intro' => 'Сон долго рассматривали как пассивную паузу. Сегодня понятно: это активный процесс сортировки и переработки опыта.',
            'blocks' => [
                ['type' => 'p', 'text' => 'Исследования показывают, что разные фазы сна отвечают за разные задачи: одни помогают запомнить факты, другие — освоить навыки.'],
                ['type' => 'h2', 'text' => 'Качество важнее количества'],
                ['type' => 'p', 'text' => 'Восемь часов с частыми пробуждениями могут быть менее полезны, чем семь часов непрерывного сна. Организму важна структура циклов.'],
                ['type' => 'quote', 'text' => 'Сон — не перерыв в обучении, а его продолжение.'],
                ['type' => 'h2', 'text' => 'Что можно сделать уже сейчас'],
                ['type' => 'ul', 'items' => [
                    'Ложиться и вставать в одно и то же время.',
                    'Не заменять сон дополнительной чашкой кофе.',
                    'Давать себе паузу перед сном без экранов.',
                ]],
                ['type' => 'p', 'text' => 'Универсальной формулы нет, но регулярность остаётся самым надёжным вложением в память и внимание.'],
            ],
        ],
        [
            'code' => 'materials-that-cool-themselves',
            'name' => 'Материалы, которые умеют остывать сами',
            'section' => 'science',
            'date' => '19.10.2026 13:25:00',
            'author' => 'Михаил Орлов',
            'role' => 'Автор',
            'reading' => '6 мин',
            'tags' => ['материалы', 'физика'],
            'featured' => 'N',
            'palette' => ['#173b3f', '#58a6a0', '#eaf4f0'],
            'preview' => 'Инженеры учатся управлять теплом без вентиляторов и компрессоров. Новые материалы могут изменить электронику и строительство.',
            'intro' => 'Отводить тепло — одна из главных задач современной техники. Пассивные решения становятся всё интереснее активного охлаждения.',
            'blocks' => [
                ['type' => 'p', 'text' => 'Металлы с заданной структурой и пористые керамики позволяют перераспределять тепло внутри устройства, снижая пиковую температуру.'],
                ['type' => 'h2', 'text' => 'Тише, проще, надёжнее'],
                ['type' => 'p', 'text' => 'Пассивное охлаждение не шумит и не ломается. Ограничение — масса и стоимость, но для многих задач это приемлемый компромисс.'],
                ['type' => 'quote', 'text' => 'Лучший вентилятор — тот, которого нет.'],
                ['type' => 'h2', 'text' => 'Где это пригодится'],
                ['type' => 'ul', 'items' => [
                    'Компактная электроника без движущихся частей.',
                    'Энергоэффективные фасады зданий.',
                    'Батареи с более равномерным нагревом.',
                ]],
                ['type' => 'p', 'text' => 'До массового применения далеко, но направление понятное: меньше механики, больше продуманной структуры.'],
            ],
        ],
        [
            'code' => 'city-trees-under-observation',
            'name' => 'Городские деревья под наблюдением',
            'section' => 'science',
            'date' => '10.10.2026 11:30:00',
            'author' => 'Ирина Смирнова',
            'role' => 'Автор',
            'reading' => '5 мин',
            'tags' => ['город', 'экология', 'данные'],
            'featured' => 'N',
            'palette' => ['#263a24', '#7fae62', '#f0f5e9'],
            'preview' => 'Датчики и городские сервисы превращают уход за деревьями из сезонной кампании в постоянную работу с данными.',
            'intro' => 'Городские деревья всё чаще получают цифровой паспорт. Это меняет то, как службы планируют полив, обрезку и замену.',
            'blocks' => [
                ['type' => 'p', 'text' => 'Датчики влажности и температуры помогают понять, какие участки страдают первыми. Вместо равномерного полива появляется адресная работа.'],
                ['type' => 'h2', 'text' => 'Данные вместо интуиции'],
                ['type' => 'p', 'text' => 'Службы получают историю каждого дерева: возраст, вид, состояние, прошлые вмешательства. Решения становятся воспроизводимыми.'],
                ['type' => 'quote', 'text' => 'Дерево — это инфраструктура, которая растёт десятилетиями. Ей нужен долгий план.'],
                ['type' => 'h2', 'text' => 'Что даёт наблюдение'],
                ['type' => 'ul', 'items' => [
                    'Раннее выявление болезней и повреждений.',
                    'Планирование замены без потери зелёного каркаса.',
                    'Прозрачные отчёты для жителей.',
                ]],
                ['type' => 'p', 'text' => 'Главный эффект — не технологии, а горизонт планирования. Город начинает думать о деревьях не на сезон, а на жизнь.'],
            ],
        ],
    ];
}

function blog_demo_sections(): array
{
    return [
        ['code' => 'tech', 'name' => 'Технологии', 'sort' => 100, 'description' => 'Как устройства, сети и код меняют повседневность.'],
        ['code' => 'design', 'name' => 'Дизайн', 'sort' => 200, 'description' => 'Интерфейсы, города и вещи, которые нас окружают.'],
        ['code' => 'business', 'name' => 'Экономика', 'sort' => 300, 'description' => 'Деньги, медиа и устойчивость небольших команд.'],
        ['code' => 'science', 'name' => 'Наука', 'sort' => 400, 'description' => 'Исследования, материалы и данные о мире.'],
    ];
}

function blog_demo_detail_html(array $article): string
{
    $escape = static fn(string $value): string => htmlspecialcharsbx($value);
    $html = '<p><strong>' . $escape($article['intro']) . '</strong></p>';

    foreach ($article['blocks'] as $block)
    {
        switch ($block['type'])
        {
            case 'h2':
                $html .= '<h2>' . $escape($block['text']) . '</h2>';
                break;
            case 'p':
                $html .= '<p>' . $escape($block['text']) . '</p>';
                break;
            case 'quote':
                $html .= '<blockquote><p>' . $escape($block['text']) . '</p></blockquote>';
                break;
            case 'ul':
                $items = '';
                foreach ($block['items'] as $item)
                {
                    $items .= '<li>' . $escape($item) . '</li>';
                }
                $html .= '<ul>' . $items . '</ul>';
                break;
        }
    }

    return $html;
}

function blog_demo_hex_color(string $hex): array
{
    $hex = ltrim($hex, '#');

    return [
        'r' => (int)hexdec(substr($hex, 0, 2)),
        'g' => (int)hexdec(substr($hex, 2, 2)),
        'b' => (int)hexdec(substr($hex, 4, 2)),
    ];
}

/**
 * Рисует обложку статьи в стиле шаблона и сохраняет её в /upload/blog/covers.
 */
function blog_demo_cover(string $code, array $palette): ?string
{
    $relativePath = '/upload/blog/covers/' . $code . '.png';
    $absolutePath = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/') . $relativePath;

    if (is_file($absolutePath))
    {
        return $absolutePath;
    }

    if (!function_exists('imagecreatetruecolor'))
    {
        return null;
    }

    $directory = dirname($absolutePath);
    if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory))
    {
        return null;
    }

    $width = 1200;
    $height = 800;
    $image = imagecreatetruecolor($width, $height);
    imagealphablending($image, true);

    $top = blog_demo_hex_color($palette[0]);
    $bottom = blog_demo_hex_color($palette[1]);

    for ($y = 0; $y < $height; $y++)
    {
        $ratio = $y / $height;
        $r = (int)round($top['r'] + ($bottom['r'] - $top['r']) * $ratio);
        $g = (int)round($top['g'] + ($bottom['g'] - $top['g']) * $ratio);
        $b = (int)round($top['b'] + ($bottom['b'] - $top['b']) * $ratio);
        $color = imagecolorallocate($image, $r, $g, $b);
        imageline($image, 0, $y, $width, $y, $color);
    }

    $accent = blog_demo_hex_color($palette[2]);
    $accentColor = imagecolorallocate($image, $accent['r'], $accent['g'], $accent['b']);

    $seed = crc32($code);
    mt_srand($seed ?: 1);

    for ($i = 0; $i < 5; $i++)
    {
        $radius = mt_rand(120, 360);
        $cx = mt_rand(-100, $width + 100);
        $cy = mt_rand(-100, $height + 100);
        $alpha = mt_rand(16, 44);
        $tint = imagecolorallocatealpha($image, $accent['r'], $accent['g'], $accent['b'], 127 - $alpha);
        imagefilledellipse($image, $cx, $cy, $radius * 2, $radius * 2, $tint);
    }

    $light = imagecolorallocatealpha($image, 255, 255, 255, 112);
    $bandHeight = 18;
    $bandY = (int)($height * 0.68);
    imagefilledrectangle($image, 0, $bandY, (int)($width * 0.42), $bandY + $bandHeight, $light);

    $outline = imagecolorallocatealpha($image, 255, 255, 255, 96);
    imagesetthickness($image, 3);
    imagerectangle($image, 64, 64, $width - 65, $height - 65, $outline);

    imagepng($image, $absolutePath, 7);
    imagedestroy($image);
    mt_srand();

    return $absolutePath;
}

function blog_demo_ensure_type(): void
{
    if (\CIBlockType::GetByID(BLOG_DEMO_TYPE)->Fetch())
    {
        return;
    }

    $type = new \CIBlockType();
    $type->Add([
        'ID' => BLOG_DEMO_TYPE,
        'SECTIONS' => 'Y',
        'IN_RSS' => 'N',
        'SORT' => 100,
        'LANG' => [
            'ru' => [
                'NAME' => 'Блог',
                'SECTION_NAME' => 'Рубрики',
                'ELEMENT_NAME' => 'Статьи',
            ],
            'en' => [
                'NAME' => 'Blog',
                'SECTION_NAME' => 'Categories',
                'ELEMENT_NAME' => 'Articles',
            ],
        ],
    ]);
}

function blog_demo_ensure_iblock(): int
{
    $fields = [
        'NAME' => 'Журнал СИГНАЛ',
        'CODE' => BLOG_DEMO_IBLOCK_CODE,
        'API_CODE' => 'Blog',
        'IBLOCK_TYPE_ID' => BLOG_DEMO_TYPE,
        'SITE_ID' => ['s1'],
        'SORT' => 100,
        'ACTIVE' => 'Y',
        'LIST_PAGE_URL' => '/blog/',
        'DETAIL_PAGE_URL' => '/blog/#ELEMENT_CODE#/',
        'SECTION_PAGE_URL' => '/blog/category/#SECTION_CODE#/',
        'INDEX_ELEMENT' => 'Y',
        'INDEX_SECTION' => 'Y',
        'GROUP_ID' => [
            2 => 'R',
        ],
        'DESCRIPTION' => 'Материалы публичной части журнала СИГНАЛ.',
        'DESCRIPTION_TYPE' => 'text',
        'FIELDS' => [
            'CODE' => ['IS_REQUIRED' => 'Y'],
            'PREVIEW_PICTURE' => ['IS_REQUIRED' => 'N'],
            'DETAIL_PICTURE' => ['IS_REQUIRED' => 'N'],
            'PREVIEW_TEXT' => ['IS_REQUIRED' => 'N'],
            'DETAIL_TEXT' => ['IS_REQUIRED' => 'N'],
        ],
    ];

    $iblock = new \CIBlock();

    if ($iblockData = \CIBlock::GetList(
        [],
        ['=TYPE' => BLOG_DEMO_TYPE, '=CODE' => BLOG_DEMO_IBLOCK_CODE, 'CHECK_PERMISSIONS' => 'N']
    )->Fetch())
    {
        $iblock->Update((int)$iblockData['ID'], $fields);

        return (int)$iblockData['ID'];
    }

    $id = (int)$iblock->Add($fields);

    if ($id <= 0)
    {
        throw new \RuntimeException('Не удалось создать инфоблок: ' . $iblock->LAST_ERROR);
    }

    return $id;
}

function blog_demo_ensure_properties(int $iblockId): void
{
    // Код TAGS совпадает с базовым полем элемента, поэтому ORM-сущность
    // не может скомпилировать свойство. Переименовываем старое свойство.
    $legacyTags = \CIBlockProperty::GetList(
        [],
        ['IBLOCK_ID' => $iblockId, 'CODE' => 'TAGS']
    )->Fetch();

    if ($legacyTags)
    {
        $existingTags = \CIBlockProperty::GetList(
            [],
            ['IBLOCK_ID' => $iblockId, 'CODE' => 'ARTICLE_TAGS']
        )->Fetch();

        if (!$existingTags)
        {
            $property = new \CIBlockProperty();
            $property->Update((int)$legacyTags['ID'], ['CODE' => 'ARTICLE_TAGS']);
        }
    }

    $properties = [
        [
            'NAME' => 'Автор',
            'CODE' => 'AUTHOR',
            'PROPERTY_TYPE' => 'S',
            'MULTIPLE' => 'N',
            'SORT' => 100,
            'IS_REQUIRED' => 'N',
        ],
        [
            'NAME' => 'Должность автора',
            'CODE' => 'AUTHOR_ROLE',
            'PROPERTY_TYPE' => 'S',
            'MULTIPLE' => 'N',
            'SORT' => 200,
            'IS_REQUIRED' => 'N',
        ],
        [
            'NAME' => 'Время чтения',
            'CODE' => 'READING_TIME',
            'PROPERTY_TYPE' => 'S',
            'MULTIPLE' => 'N',
            'SORT' => 300,
            'IS_REQUIRED' => 'N',
        ],
        [
            'NAME' => 'Теги',
            'CODE' => 'ARTICLE_TAGS',
            'PROPERTY_TYPE' => 'S',
            'MULTIPLE' => 'Y',
            'SORT' => 400,
            'IS_REQUIRED' => 'N',
        ],
        [
            'NAME' => 'На главной',
            'CODE' => 'FEATURED',
            'PROPERTY_TYPE' => 'L',
            'MULTIPLE' => 'N',
            'SORT' => 500,
            'IS_REQUIRED' => 'N',
            'VALUES' => [
                ['VALUE' => 'Да', 'DEF' => 'N', 'SORT' => 100],
                ['VALUE' => 'Нет', 'DEF' => 'Y', 'SORT' => 200],
            ],
        ],
    ];

    foreach ($properties as $property)
    {
        $property['IBLOCK_ID'] = $iblockId;

        $existing = \CIBlockProperty::GetList(
            [],
            ['IBLOCK_ID' => $iblockId, 'CODE' => $property['CODE']]
        )->Fetch();

        $object = new \CIBlockProperty();

        if ($existing)
        {
            $updateFields = $property;
            unset($updateFields['VALUES']);
            $object->Update((int)$existing['ID'], $updateFields);
        }
        else
        {
            $object->Add($property);
        }
    }
}

function blog_demo_ensure_sections(int $iblockId): array
{
    $ids = [];

    foreach (blog_demo_sections() as $section)
    {
        $existing = \CIBlockSection::GetList(
            [],
            ['IBLOCK_ID' => $iblockId, '=CODE' => $section['code'], 'CHECK_PERMISSIONS' => 'N'],
            false,
            ['ID']
        )->Fetch();

        $fields = [
            'IBLOCK_ID' => $iblockId,
            'NAME' => $section['name'],
            'CODE' => $section['code'],
            'SORT' => $section['sort'],
            'ACTIVE' => 'Y',
            'DESCRIPTION' => $section['description'],
            'DESCRIPTION_TYPE' => 'text',
        ];

        $object = new \CIBlockSection();

        if ($existing)
        {
            $id = (int)$existing['ID'];
            $object->Update($id, $fields);
        }
        else
        {
            $id = (int)$object->Add($fields);
        }

        if ($id <= 0)
        {
            throw new \RuntimeException('Не удалось создать рубрику «' . $section['name'] . '»: ' . $object->LAST_ERROR);
        }

        $ids[$section['code']] = $id;
    }

    return $ids;
}

function blog_demo_enum_id(int $iblockId, string $propertyCode, string $value): int
{
    $property = \CIBlockProperty::GetList(
        [],
        ['IBLOCK_ID' => $iblockId, 'CODE' => $propertyCode]
    )->Fetch();

    if (!$property)
    {
        return 0;
    }

    $enum = \CIBlockPropertyEnum::GetList(
        ['SORT' => 'ASC'],
        ['PROPERTY_ID' => $property['ID'], 'VALUE' => $value]
    )->Fetch();

    return $enum ? (int)$enum['ID'] : 0;
}

function blog_demo_ensure_elements(int $iblockId, array $sectionIds): void
{
    $featuredYes = blog_demo_enum_id($iblockId, 'FEATURED', 'Да');

    foreach (blog_demo_articles() as $article)
    {
        $existing = \CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => $iblockId,
                '=CODE' => $article['code'],
                'CHECK_PERMISSIONS' => 'N',
            ],
            false,
            false,
            ['ID']
        )->Fetch();

        $properties = [
            'AUTHOR' => $article['author'],
            'AUTHOR_ROLE' => $article['role'],
            'READING_TIME' => $article['reading'],
            'ARTICLE_TAGS' => $article['tags'],
        ];

        if ($featuredYes > 0)
        {
            $properties['FEATURED'] = $article['featured'] === 'Y' ? $featuredYes : '';
        }

        $fields = [
            'IBLOCK_ID' => $iblockId,
            'IBLOCK_SECTION_ID' => $sectionIds[$article['section']] ?? false,
            'NAME' => $article['name'],
            'CODE' => $article['code'],
            'ACTIVE' => 'Y',
            'ACTIVE_FROM' => $article['date'],
            'SORT' => 500,
            'PREVIEW_TEXT' => $article['preview'],
            'PREVIEW_TEXT_TYPE' => 'text',
            'DETAIL_TEXT' => blog_demo_detail_html($article),
            'DETAIL_TEXT_TYPE' => 'html',
            'PROPERTY_VALUES' => $properties,
        ];

        $object = new \CIBlockElement();

        if ($existing)
        {
            $object->Update((int)$existing['ID'], $fields);
            continue;
        }

        $cover = blog_demo_cover($article['code'], $article['palette']);

        if ($cover !== null)
        {
            $file = \CFile::MakeFileArray($cover);
            $file['MODULE_ID'] = 'iblock';
            $fields['PREVIEW_PICTURE'] = $file;
            $fields['DETAIL_PICTURE'] = $file;
        }

        if ($object->Add($fields) <= 0)
        {
            throw new \RuntimeException('Не удалось создать статью «' . $article['name'] . '»: ' . $object->LAST_ERROR);
        }
    }
}

blog_demo_ensure_type();
$blogIblockId = blog_demo_ensure_iblock();
blog_demo_ensure_properties($blogIblockId);
$blogSectionIds = blog_demo_ensure_sections($blogIblockId);
blog_demo_ensure_elements($blogIblockId, $blogSectionIds);

$GLOBALS['BLOG_DEMO_RESULT'] = [
    'iblockId' => $blogIblockId,
    'sections' => $blogSectionIds,
];
