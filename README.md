# b01110011.recaptcha

reCAPTCHA v3 - это бесплатный сервис, который защищает ваш сайт от спама. Капча является невидимой для пользователей. *(что означает, не нужно больше тыкать на картинки или вводить текст)*  

Модуль встраивает данный механизм защиты на сайт.  
  
Есть поддержка многосайтовости.  
  
Расширение cURL должно быть включено в PHP.  
  
**Для работы модуля:**
1. Получить ключи рекапчи.
2. Вставить скрытое поле в форму. (если форма отправляется с помощью ajax, то и заполнить это скрытое поле методом "recaptcha.getToken()")
3. Произвести быструю настройку на странице настроек модуля.

**Получение ключей:**

Авторизоваться через google аккаунт и зарегистрировать свой сайт по ссылке  
https://www.google.com/recaptcha/admin/create  
и получить ключи для reCaptcha v3.  
*(при ситуации когда есть тестовый сайт, вписывайте его в поле "Домены")*  

**Настройка модуля:**

В административном разделе, в настройках модуля, вставить полученные ключи.  
( Настройки > Настройки продукта > Настройки модулей > Google ReCaptcha )  

**Примечание:**

Если включена защита на какой то компонент, к примеру, инфо блок и из кода добавляется в этот инфоблок информация, то она не добавится из за проверки капчи.  
Нужно передать в массив полей: ключ "RECAPTCHA_DISABLE" со значением true.  
```php
$arFields =
[
	"MODIFIED_BY"       => $USER->GetID(),
	"IBLOCK_ID"         => 1,
	"NAME"              => "Элемент 5",
	"ACTIVE"            => "Y",
	"PREVIEW_TEXT"      => "some text",
	"DETAIL_TEXT"       => "some text",
	"RECAPTCHA_DISABLE" => true
];

(new CIBlockElement())->Add($arFields);
```

**Настройка защиты "инфоблоков":**

1. В настройках модуля выбрать нужные инфоблоки для защиты от спама и сохранить изменения.
2. В шаблоне добавления информации в инфоблок вставить скрытое поле в тег формы.  
```html
<input type="hidden" name="recaptcha_token" value="">
```
Если форма отправляется через ajax, то нужно самим заполнять это поле методом window.recaptcha.getToken().  
Примеры:  
```html
<input type="submit" value="Добавить новость" onclick="this.form.recaptcha_token.value = window.recaptcha.getToken()">
```
или без скрытого поля сразу добавлять токен в запрос через js  
```js
BX.ajax({
    url: 'some url',
    method: 'POST',
    data: {
        action: "to_send_form",
        name: $(".name_field").val(),
        ...
        recaptcha_token: window.recaptcha.getToken(),
    },
    onsuccess: function(data){
        $(".form_ready").remove();
        ClearForm();
        $(".form_to_send").prepend(data);
    }
});
```

**Настройка защиты "Веб формы":**

1. В настройках модуля выбрать нужные веб формы для защиты от спама и сохранить изменения.
2. В шаблоне добавления информации в веб форму вставить скрытое поле в тег формы.  
```html
<input type="hidden" name="recaptcha_token" value="">
```
Если форма отправляется через ajax, то нужно самим заполнять это поле методом window.recaptcha.getToken().  
Примеры:  
```html
<input type="submit" value="Обратный звонок" onclick="this.form.recaptcha_token.value = window.recaptcha.getToken()">
```
или без скрытого поля сразу добавлять токен в запрос через js  
```js
BX.ajax({
    url: 'some url',
    method: 'POST',
    data: {
        action: "to_send_form",
        name: $(".name_field").val(),
        ...
        recaptcha_token: window.recaptcha.getToken(),
    },
    onsuccess: function(data){
        $(".form_ready").remove();
        ClearForm();
        $(".form_to_send").prepend(data);
    }
});
```

**Настройка защиты "Регистрации":**

1. В настройках модуля находим блок "Регистрации", жмём на "Включить капчу" для защиты от спама и сохранить изменения.
2. В шаблоне регистрации вставить скрытое поле в тег формы.  
```html
<input type="hidden" name="recaptcha_token" value="">
```
Если форма отправляется через ajax, то нужно самим заполнять это поле методом window.recaptcha.getToken().  
Примеры:  
```html
<input type="submit" value="Зарегистрироваться" onclick="this.form.recaptcha_token.value = window.recaptcha.getToken()">
```
или без скрытого поля сразу добавлять токен в запрос через js  
```js
BX.ajax({
    url: 'some url',
    method: 'POST',
    data: {
        action: "to_send_form",
        name: $(".name_field").val(),
        ...
        recaptcha_token: window.recaptcha.getToken(),
    },
    onsuccess: function(data){
        $(".form_ready").remove();
        ClearForm();
        $(".form_to_send").prepend(data);
    }
});
```
**Примечание:**
Если включена регистрация при оформлении заказа в компоненте sale.order.ajax то настройка чуть ниже в блоке "Настройка защиты 'Оформления заказа (sale.order.ajax)'"  

**Настройка защиты "Формы обратной связи (main.feedback)":**

По умолчанию в этом компоненте не выводится ошибка капчи. Инструкция, по настройке выводу ошибки, чуть ниже.  
1. В настройках модуля находим блок "Форма обратной связи", выбираем нужные почтовые шаблоны для защиты от спама и сохранить изменения.
2. В шаблоне Формы обратной связи вставить скрытое поле в тег формы.  
```html
<input type="hidden" name="recaptcha_token" value="">
```
Если форма отправляется через ajax, то нужно самим заполнять это поле методом window.recaptcha.getToken().  
Примеры:  
```html
<input type="submit" value="Обратная связь" onclick="this.form.recaptcha_token.value = window.recaptcha.getToken()">
```
или без скрытого поля сразу добавлять токен в запрос через js  
```js
BX.ajax({
    url: 'some url',
    method: 'POST',
    data: {
        action: "to_send_form",
        name: $(".name_field").val(),
        ...
        recaptcha_token: window.recaptcha.getToken(),
    },
    onsuccess: function(data){
        $(".form_ready").remove();
        ClearForm();
        $(".form_to_send").prepend(data);
    }
});
``` 

**Как вывести сообщение об ошибке капчи в "Форме обратной связи main.feedback"?**
Нужно скопировать компонент (папку main.feedback) из *bitrix\components\bitrix\main.feedback* в *local\components\bitrix\main.feedback* .  
Далее открыть файл component.php на редактирование.  
Найти строки:  
```php
$_SESSION["MF_NAME"] = htmlspecialcharsbx($_POST["user_name"]);
$_SESSION["MF_EMAIL"] = htmlspecialcharsbx($_POST["user_email"]);
LocalRedirect($APPLICATION->GetCurPageParam("success=".$arResult["PARAMS_HASH"], Array("success")));
```
Заменить их на:  
```php
if($ex = $APPLICATION->GetException())
{
    $arResult["ERROR_MESSAGE"][] = $ex->GetString();
}
else
{
    $_SESSION["MF_NAME"] = htmlspecialcharsbx($_POST["user_name"]);
    $_SESSION["MF_EMAIL"] = htmlspecialcharsbx($_POST["user_email"]);
    LocalRedirect($APPLICATION->GetCurPageParam("success=".$arResult["PARAMS_HASH"], Array("success")));
}
```
И всё! Вывод ошибок капчи будет работать.  

**Настройка защиты "Оформления заказа (sale.order.ajax)":**

1. В настройках модуля находим блок "Оформление заказа", жмём на "Включить капчу" для защиты от спама и сохранить изменения.
2. Перейти в папку шаблона компонента. Открыть файл order_ajax.js.  
У меня это находится тут bitrix\templates\eshop_bootstrap_v4\components\bitrix\sale.order.ajax\bootstrap_v5\order_ajax.js  
на 186 строке идёт отправка данных методом ajax  
в объект data вставить токен рекапчи:
```js
data: {
    recaptcha_token: window.recaptcha.getToken(),
    via_ajax: 'Y',
    action: 'saveOrderAjax',
    sessid: BX.bitrix_sessid(),
    SITE_ID: this.siteId,
    signedParamsString: this.signedParamsString
},
```
при включённой регистрации, через этот компонент, на 261 строке тоже добавить токен:  
в настройках модуля, можно не жать галочку "включить капчу" для компонента sale.order.ajax если нужно защитить только регистрацию.   
```js
getData: function(action, actionData)
{
    var data = {
        recaptcha_token: window.recaptcha.getToken(),
        order: this.getAllFormData(),
        sessid: BX.bitrix_sessid(),
        via_ajax: 'Y',
        SITE_ID: this.siteId,
        signedParamsString: this.signedParamsString
    };

    data[this.params.ACTION_VARIABLE] = action;

    if (action === 'enterCoupon' || action === 'removeCoupon')
        data.coupon = actionData;

    return data;
},
```

**Как получить токен, если я отправляю данные через AJAX?**
```js
window.recaptcha.getToken()
```

**Как работает модуль?** *(для программистов)*

js часть:  
При загрузке страницы, в объекте window создаётся объект recaptcha, в котором запрашивается и хранится токен.  
Каждые 100 секунд токен обновляется.  
Далее ищутся все поля с именем recaptcha_token *(input[name=recaptcha_token])* и к формам, в которых эти поля находятся, вешается событие onsubmit.  
При отправке формы помещается токен в скрытое поле и запрашивается новый токен.  
Такой механизм запроса токена "заранее", реализован с целью убрать задержку перед отправкой формы.  
  
php часть:  
При построении каждой страницы, регистрируются события для проверки токена, отправленного вместе с формой. Этот токен и секретный ключ отправляются на сервер гугла для получения результата.  
Если токен верный и пришедшая оценка выше или равна той что в настройках, тогда данные формы обрабатываются, иначе выводится текст с ошибкой.  

**Как тестировать?**

Открываем консоль в браузере и уничтожаем объект recaptcha = null (window.recaptcha), перед добавлением данных.  
Для компонента Оформления заказа (sale.order.ajax) лучше переопределить функцию получения токена recaptcha.getToken = function(){return 123;}  
Если была сделана соответствующая настройка на странице настроек модуля (выбран инфоблок), то после добавления данных будет ошибка капчи.