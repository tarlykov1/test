# GSP Children Portal

WordPress-плагин для страницы внутрикорпоративного портала «Детские программы Газстройпрома».

## Установка

1. Загрузите папку `gsp-children-portal` в каталог `/wp-content/plugins/`.
2. Убедитесь, что главный файл находится по пути `/wp-content/plugins/gsp-children-portal/gsp-children-portal.php` — без лишней вложенной папки.
3. Откройте «Плагины» в админке WordPress и активируйте **GSP Children Portal**.
4. Создайте обычную страницу и вставьте шорткод:

```text
[gsp_children_portal]
```

## Категории

При активации плагин создаёт родительскую категорию `gsp-children` и дочерние категории:

- `gsp-children-hero` — главный hero-блок;
- `gsp-children-programs` — программы и направления;
- `gsp-children-events` — ближайшие мероприятия;
- `gsp-children-stories` — истории сотрудников;
- `gsp-children-partners` — партнёрские предложения, включая Skysmart;
- `gsp-children-faq` — вопросы и ответы;
- `gsp-children-materials` — полезные материалы, инструкции и контакты.

Контент берётся из стандартных записей WordPress (`post`) и стандартной таксономии `category`. Отдельные CPT не используются.

## Meta-поля

В обычных записях доступен meta box «Детские программы ГСП». Поля используются, если запись находится в категории `gsp-children-*`:

- `gsp_age` — возраст;
- `gsp_event_date` — дата события;
- `gsp_deadline` — дедлайн регистрации;
- `gsp_button_text` — текст кнопки;
- `gsp_external_url` — внешняя ссылка;
- `gsp_badge` — бейдж, например `-20%`;
- `gsp_order` — порядок сортировки;
- `gsp_format` — формат;
- `gsp_person_name` — имя сотрудника;
- `gsp_person_position` — должность/подразделение;
- `gsp_primary_button_text` — текст первой кнопки hero;
- `gsp_primary_button_url` — ссылка первой кнопки hero;
- `gsp_secondary_button_text` — текст второй кнопки hero;
- `gsp_secondary_button_url` — ссылка второй кнопки hero.

## Настройки

Раздел настроек находится в меню:

**Настройки → Детские программы ГСП**

На странице настроек есть:

- инструкция по шорткоду;
- список категорий;
- описание meta-полей;
- кнопка копирования шорткода;
- опция автосоздания демо-записей при активации;
- кнопка создания демо-контента без дублирования.

## Изображения

- Для hero и карточек используется featured image записи.
- Если featured image отсутствует, выводится `assets/img/placeholder.svg`.
- Текст не зашит в изображения и выводится HTML-текстом.

## Проверка, если плагин не появился в админке

Проверьте:

1. Папка плагина называется `gsp-children-portal`.
2. Главный файл называется `gsp-children-portal.php`.
3. Файл лежит напрямую в папке плагина: `/wp-content/plugins/gsp-children-portal/gsp-children-portal.php`.
4. В главном файле есть корректный заголовок `Plugin Name: GSP Children Portal`.
5. После деплоя не появилась лишняя вложенная папка вроде `/wp-content/plugins/gsp-children-portal/gsp-children-portal/gsp-children-portal.php`.
6. На сервере доступна версия PHP 7.4 или новее.
