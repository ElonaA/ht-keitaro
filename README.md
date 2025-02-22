# 📋 Домашнє завдання по модулю 9 "Кейтаро: Повне налаштування"

## 📄 Опис завдання:

Це домашнє завдання допоможе вам закріпити знання з модуля "Кейтаро: Повне налаштування". Завдання включає різні аспекти роботи з Кейтаро, такі як додавання оферів, партнерських програм, налаштування кампаній, звіти та тестування.

## 🔧 Технічні вимоги:

### Завдання: Повне налаштування кампанії для офера Artex.

#### Опис завдання:
1. Викачати задані ленди. Почистити від хламу код, провести `повну підготовку лендів до адаптації під офер` по звичайним критеріям роботи з лендами, які ми вже розглядали у минулих модулях.
> [!NOTE]
> Ленд 1 - `https://9-es.depanten.com/`, Ленд 2 - `https://2-cl.hondrexil.com/`, Ленд 3 - `https://cl.hondrexil.com/`

2. **Зробити адаптацію під офер**, змінити усі назви/банки/фото у коментарях зі старого офера на той, що у тз. Не забути змінити гео по тексу лендів, якщо це потрібно. Якщо на ленді немає форми треба доверстати її, з притриманням стилей ленда. Мінімалізм. У формі мають бути інпути **ім'я, телефон, кнопка відправки**.
3. ### Дані для адаптації:

   - Назва офферу: **Artex**
   - ГЕО: **Мексика**
   - Ціна на ленді: **590 mxn**
   - Посилання де можна взяти фото офера: **https://2-mx.artex-remedy.com/**


4. `Провести інтеграцію`. Налаштувати обробник, щоб усі ліди відправлялись **з нашої форми на ПП**. `Тег баєра`, усі `саби`, налаштувати генерування `subid`, та динамічного `пікселя` - усе це має з форми передаватися `динамічно`.
### Дані для інтеграції:

   - Партнерська мережа: **Everad**
   - ГЕО: **Мексика**
   - Поток (id campaign): **1116121**
   - Тег баєра: **hero**
     
> [!NOTE]
> Приклад обробника для ПП Everad `subscribe1.php` можна знайти серед файлів репозиторія з ДЗ.   

5. `Прочитати документацію Everad`, якщо не зрозуміло, підказую. Для динамічної відправки сабів у файл `subscribe1.php` після `<input type="hidden" name="sid5" value ="<?php echo $_POST['sid5']; ?>"/>` треба додати такі ж інпути з іншими сабами (sidами) і звичайно додати з цими ж сабками у форму інпути. Також додати, щоб на сторінці `subscribe1.php` вона ж наша "Дякую сторінка" відбивався динамічний піксель, додати наш страндартний код.
6. Виконати наступні кроки для повного налаштування кампанії:

### Крок 1: Додавання оферу Artex

**Додавання оферу у Кейтаро:**
   - Перейдіть до розділу **"Офери"**.
   - Натисніть **"Додати новий офер"**.
   - Заповніть усі потрібні поля. **Додайте у кінці назви оферу ваше ім'я, щоб було зрозуміло, що ви робили цей офер**.
   - Обрати групу зі списка **AcademyIT#1**
   - Збережіть офер.

### Крок 2: Додавання нової партнерської програми

**Додавання партнерської програми у Кейтаро:**
   - Перейдіть до розділу **"Партнерські програми"**.
   - Натисніть **"Додати нову програму"**.
   - Заповніть наступні поля:
     - Назва програми: **Everad (ваше ім'я)**
     - `Postback URL`: Перевірте правильність постбек URL.
    
### Крок 3: Додайте на ПП Еверад Глобальний постбек, який ви отримали на минулому кроці. 
> [!CAUTION]
> Так як глобальний постбек один, тому `додали-потестували-видалили` постбек з ПП, щоб у інших він не був доданий вже одразу. 

### Крок 4: Налаштування кампанії Кейтаро

**Створення нової кампанії:**
   - Перейдіть до розділу **"Кампанії"**.
   - Натисніть **"Додати нову кампанію"**.
   - Додайте наступні дані:
     - Назва кампанії (у кінці не забудьте додати ваше ім'я у назву)
     - Оберіть у полі домен `bame.asedyt.com` ось цей для вашої кампанії
     - Обрати групу зі списка **AcademyIT#1**
     - Додайте необхідні фільтри для на `black` поток:
       - Фільтр по ГЕО
       - Додайте усі 3 ленда у `спліт` з рівним % розподілу трафіка на них
   - Налаштуйте `вайт-ленд`:
     - Вкажіть URL вайт-ленду, або оберіть просто html пусту сторінку, або спробуйте викачати якийсь вайт та завантажити його у Кейтаро `локально`, та додати у групу зі списка у Лендінгах **AcademyIT#1**. 
     - **Налаштуйте клоаку**, внесіть базові фільтри на ботів, та по гео.
   - Збережіть кампанію.


### Крок 5: Тестування і налагодження

1. **Тестування кампанії:**
   - Проведіть тестові кліки для перевірки роботи кампанії. Зробіть тестовий лід з форми. **Не робіть одразу 30 тестів, чим менше тестів - тим краще**. Спробуйте спочатку `не перекидати на сторінку дякую`, а одразу спробувати виводити респонс, щоб зрозуміти що ви робите не так.
   - Перевірте, чи корректно доходять усі дані до Партнерської програми у **Статистику по лідам**.
   - Перегляньте результати тестування у розділі **"Звіт"** у кейтаро. Та чи корректно приходить **постбек у кейтаро** та відбиваються ліди.

2. **Вирішення проблем:**
   - Виправте можливі помилки, що виникли під час тестування.
   - Повторно протестуйте кампанію після внесення змін.



### ⚠️ Вимоги до виконання домашнього завдання:
1. Створити кампанію та оффер у кейтаро.
2. До виконаного завдання прикріпити лінки на оффер та створену кампанію.
3. Виконане домашнє завдання відправити на перевірку до наступного заняття.

Бажаємо успіху! 🚀
