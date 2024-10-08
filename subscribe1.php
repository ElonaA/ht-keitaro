<?php
session_start();
$order = array ();
$orderSerialized = "";
foreach ($_POST as $key => $value) {
	if ($value != '') {
		$order[$key] = $value;
		$orderSerialized .= "'" . $key . "':'" . $value . "',";
	}
}

$orderSerialized = "{" . $orderSerialized . "},\n";
$ordersFile = fopen("orders.txt", "a");
fwrite($ordersFile, $orderSerialized);
fclose($ordersFile);

$order['is_smart_form'] = true;
// Define ip
if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
$ip =  $_SERVER['HTTP_CF_CONNECTING_IP'];
}  elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
$ip =  $_SERVER['HTTP_X_REAL_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
$ip =  $_SERVER['REMOTE_ADDR'];
}

$ips = explode(",", $ip);
$order['ip'] = trim($ips[0]);

$parsed_referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);
parse_str($parsed_referer, $referer_query);

$ch = curl_init();
$submit_url = 'https://tracker.everad.com/conversion/new';
$_SESSION['submit_url'] = $submit_url;

curl_setopt($ch, CURLOPT_URL, $submit_url );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch, CURLOPT_POST,           1 );
curl_setopt($ch, CURLOPT_POSTFIELDS,     http_build_query(array_merge($referer_query, $order)) );
curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: application/x-www-form-urlencoded'));

$result = curl_exec ($ch);
$error = false;

if ($result === 0) {
    $error = "Timeout! Everad CPA API didn't respond within default period!";
} else {
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($httpCode === 400) {
    $error = "Order data is invalid! Order is not accepted!";
} else if ($httpCode === 423) {
    header('Location: ' . $_SERVER['HTTP_REFERER'] . '?is_pending_order_check_failed=true');
    exit();
} else if ($httpCode === 401) {
    $error = "Order is not accepted! No campaign_id.";
} else if ($httpCode === 403) {
    $error = "Order is not accepted! Restricted GEO. Please submit your order from another GEO.";
} else if ($httpCode !== 200) {
    $error = "Order is not accepted! Invalid or incomplete data. Please contact support.";
} else {
     foreach (json_decode($result, true) as $key => $value) {
		if ($key === "id") {
            $_SESSION['conversion_id'] = $value;
		}
	}
}}
if ($error) {
						header('Location: ' . $_SERVER['HTTP_REFERER'] . '?is_blacklist_error=true');
						exit();
					}
?>
<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Hemos recibido su solicitud</title>
	<link rel="stylesheet" href="default-css/subscribe-1.css">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"
		integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script src="default-js/call-center-reach-time.js"></script>
	<script>
		// for testing
		// window.country_code = 'RU'
	</script>
</head>

<body data-long-time-text="7 минут">
   <style>
    html,
body {
    min-height: 100%;
    display: flex;
}

body {
    font-family: 'Inter', sans-serif;
    background: #F3F6F6;
    flex: 1;
    align-items: center;

    justify-content: center;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.panel {
    background: #FFFFFF;
    box-shadow: 0px 15px 50px -20px rgba(0, 0, 0, 0.1);

    max-width: 740px;

    border-radius: 10px;
}

.panel-body {
    display: flex;
    flex-direction: column;

    padding: 50px 30px;

    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.thank-you {
    font-size: 30px;
    text-align: center;
    color: #000000;

    max-width: 490px;
    margin-bottom: 15px;

    align-self: center;
}

.thank-you__order-id {
    position: relative;
}

.thank-you__order-id:after {
    position: absolute;
    content: '';
    width: 100%;
    height: 2px;
    background-color: #000;
    bottom: -1px;
    left: 0;

}

.we-will-call-you {
    font-size: 20px;
    text-align: center;
    color: #777777;

    margin-bottom: 35px;
}

.we-will-call-you__countdown {
    color: #000000;
    font-variant-numeric: tabular-nums;
}

.data {
    background: #FAF8F0;
    border-radius: 10px;
    padding: 20px;

    display: flex;
    align-items: center;
    justify-content: space-between;
    line-height: 1.2;
    font-size: 16px;
}
.parent{
    min-height: 60px;
    margin-bottom: 40px;
}

.data__block {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.data__block .data__item {
    width: 50%;
}

.data__item:first-child {
    position: relative;
    padding-right: 30px;
}

.data__item:first-child:after {
    position: absolute;
    content: '';
    width: 1px;
    height: 100%;
    background-color: #222;
    top: 0;
    right: 20px;
}

.data__key {
    color: #777777;
    flex-shrink: 0;
}

.data__change {
    color: #777777;
    font-style: italic;
    flex-shrink: 0;
    cursor: pointer;
}

.cta {
    display: flex;
    align-items: center;
    justify-content: space-around;
}

.cta__item {
    font-size: 13px;

    display: flex;

    align-items: center;

    width: 25%;
    padding-right: 10px;
}

.cta__item svg {
    margin-right: 12px;
    width: 40px;
    height: 40px;
    flex-shrink: 0;
}

.subscribe {
    background: #FFD028;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;

    font-size: 20px;

    display: flex;
    flex-direction: column;

    align-items: center;
    justify-content: center;
}

.subscribe__bar {
    padding: 8px 15px;
    font-size: 13px;
    background: #FFD028;
    cursor: pointer;
    display: none;
    position: sticky;
    bottom: 0;
}

.subscribe__bar span {
    flex: 1;
}

.subscribe__cta {
    max-width: 400px;
    padding: 30px;
    text-align: center;
}

.subscribe__input {
    margin-bottom: 30px;
    display: flex;
    width: 100%;
    box-sizing: border-box;
    padding-left: 30px;
    padding-right: 30px;
    min-height: 50px;
}

.subscribe input {
    color: #777777;
    background: #FFFFFF;
    font-size: 16px;
    border: none;
    outline: none;
    width: 100%;
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;

    padding: 0 18px;
}

.subscribe button {
    width: 210px;
    background-color: #fff5d4;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
    font-size: 20px;
    border: none;
    outline: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-around;
    flex-shrink: 0;
}

.footer {
    margin-top: 21px;
    text-align: center;
    font-size: 13px;
}

.footer a {
    text-decoration: none;
    /* font-style: none; */
    color: #000;
}

.footer a:first-child {
    padding-bottom: 25px;
    /*border-right: 1px solid #AAAAAA;*/
    /*padding-right: 30px;*/
}

.footer a:last-child {
    /*padding-left: 30px*/
}

.correction {
    display: none;
    background: #FAF8F0;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 40px;
}

.correction__block {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.correction__close {
    cursor: pointer;
    color: #777;
    font-size: 14px;
    padding-left: 26px;
    position: relative;
    flex-shrink: 0;
}

.correction__close:after,
.correction__close:before {
    position: absolute;
    content: '';
    width: 13px;
    height: 1px;
    background-color: #777;
    top: 50%;
    transform: translateX(-50%);
    left: 0;
    transform: rotate(45deg);
}

.correction__close:before {
    transform: rotate(-45deg);
}

.correction__title {
    color: #777;
    font-size: 15px;

}

.correction__item {
    display: flex;
    flex-direction: column;
}

.correction__input {
    width: 100%;
    border-color: white;
    border: none;
    line-height: 1.2;
    font-size: 16px;
    padding: 16px;
}

.correction__input--name {
    margin-bottom: 18px;
}

.correction__input--phone {
    margin-bottom: 30px;
}

.correction__label {
    font-size: 14px;
    color: #777;
    line-height: 1;
    margin-bottom: 5px;
}

.button {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.button__item {
    display: block;
    width: calc((100% - 20px)/2);
    text-align: center;
    font-size: 20px;
    padding: 15px 0;
    border-radius: 5px;
    background: #FFD028;
    cursor: pointer;
    border: none;
}

.button__item--cancel {
    background: none;
    border: 2px solid #FFD028;
}
.br {
display: none;
}
@media (max-width: 750px) {
    .br {
        display: block;
    }
    .data__item:first-child:after {
        content: none;
    }

    .data__block {
        display: block;
    }

    .cta {
        width: 100%;
    }

    .data__block .data__item {
        width: 100%;
    }

    .panel-body {
        padding: 15px;
    }

    .thank-you {
        margin-bottom: 15px;
    }

    .we-will-call-you {
        margin-bottom: 20px;
    }
    .parent{
        min-height: 128px;
        margin-bottom: 20px;
    }
    .data {
        flex-direction: column;
        /*margin-bottom: 20px;*/
        justify-content: unset;
        align-items: unset;
    }

    .data__item {
        margin-bottom: 7px;
        padding-bottom: 7px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .cta {
        flex-direction: column;
        margin-bottom: 20px;
        max-width: 80%;
    }

    .cta__item {
        width: 100%;
    }

    .cta__item:not(:last-child) {
        padding-bottom: 18px;
    }

    .cta__item img {
        width: 30px;
        height: 30px;
    }

    .subscribe__cta {
        padding: 20px;
    }

    .subscribe__submit-text {
        display: none;
    }

    .subscribe button {
        width: unset;
        padding: 0 13px;
    }

    .subscribe__input {
        min-height: 40px;
    }

    .footer {
        display: flex;
        flex-direction: column;
    }

    .footer a:first-child {
        /*border-right: none;*/
        /*padding-right: 0;*/
    }

    .footer a:last-child {
        /*padding-left: 0;*/
    }

    .button__item {
        font-size: 18px;
    }
    
.correction__input--name {
    margin-bottom: 15px;
}

.correction__input--phone {
    margin-bottom: 20px;
}
.thank-you {
    font-size: 28px;
}
}

@media (max-width: 550px) {
    .thank-you {
    font-size: 25px;
}
    .data {
        font-size: 15px;
    }
    .button {
        flex-direction: column;

    }

    .button__item {
        width: 100%;
        max-width: 320px;
        margin: 0 auto;
        padding: 12px 5px;
    }

    .button__item:not(:last-of-type) {
        margin-bottom: 15px;
    }

    .correction {
        margin-bottom: 30px;
    }

    .correction__close {
        padding-left: 23px;
    }
}

@media (max-width: 425px) {
    .thank-you {
    font-size: 20px;
}
    .data {
        font-size: 13px;
    }
    .correction__input--name {
        margin-bottom: 10px;
    }

    .correction__input--phone {
        margin-bottom: 15px;
    }

    .button__item:not(:last-of-type) {
        margin-bottom: 10px;
    }

    .button__item {
        font-size: 15px;
    }

    .correction {
        margin-bottom: 25px;
    }

    .correction__close,
    .correction__title {
        font-size: 13px;
    }
    .subscribe {
        font-size: 15px;
    }
}

@media (max-width: 310px) {

    .thank-you {
        font-size: 20px;
    }

    .we-will-call-you {
        font-size: 15px;
    }

    .data {
        flex-direction: column;
    }

    .data__item,
    .data__change {
        font-size: 13px;
    }

    .cta {
        flex-direction: column;
    }

    .subscribe,
    .subscribe input {
        font-size: 13px;
    }

    .subscribe input {
        height: 40px;
    }

    .subscribe__cta {
        font-size: 15px;
    }
}

  </style>
  
	<div class="content">
		<div class="panel">
			<div class="panel-body">
				<div class="thank-you">¡Gracias! ¡Su pedido <span class="thank-you__order-id">№<?php echo $_SESSION['conversion_id']; ?></span> se ha realizado con éxito!</div>
                    <div class="we-will-call-you we-will-call-you__timer" style="display: none;">
                        Nos comunicaremos con usted en <span class="we-will-call-you__countdown">00:07:00</span> minuto
                    </div>
                    <div class="we-will-call-you we-will-call-you__title">
                        Dentro de poco, un operador se pondrá en contacto con usted para aclarar los detalles.
                    </div>
				<form class="parent x_resubmit_form">
					<div class="data js-data">
						<div class="data__block">
							<div class="data__item">
								<span class="data__key">Su nombre:</span>
								<span class="x_client_name"><?php echo $_POST["name"]?></span>
							</div>
							<div class="data__item">
								<span class="data__key">Su número de teléfono:</span>
								<span class="x_client_phone"><?php echo $_POST["phone"]?></span>
							</div>

						</div>

						<span class="js-change-btn data__change">
						<svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path
									d="M1 14H4.25L5.0625 13.1875M1 14V10.75L1.8125 9.9375M1 14H14M9.53125 2.21875L10.75 1L14 4.25L12.7812 5.46875M9.53125 2.21875L12.7812 5.46875M9.53125 2.21875L1.8125 9.9375M12.7812 5.46875L5.0625 13.1875M1.8125 9.9375L5.0625 13.1875"
									stroke="#777777" />
						</svg>

						Editar
					</span>
					</div>
					<div class="correction js-change">
						<div class="correction__block">
							<p class="correction__title">Cambiar datos</p>
							<div class="correction__close js-btn-cancel">
								<span>Cancelar</span>
							</div>
						</div>
						<div class="correction__item">
							<label class="correction__label">Su nombre</label>
							<input class="correction__input correction__input--name" type="text" name="name">
						</div>
						<div class="correction__item">
							<label class="correction__label">Su número de teléfono</label>
							<input class="correction__input correction__input--phone" type="text" name="phone" required>
							<input type="hidden" name="id" value="<?php echo $_SESSION['conversion_id']; ?>">
                            <input type="hidden" name="sid5" value ="<?php echo $_POST['sid5']; ?>"/>

						</div>
						<div class="correction__buttons button">
						<button class="button__item js-btn-change">
							Editar
						</button>
						<span class="button__item js-btn-cancel button__item--cancel">
							No cambiar
						</span>
						</div>
					</div>
				</form>
				<div class="cta">
					<div class="cta__item">
						<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle cx="20" cy="20" r="19" stroke="#FFD028" stroke-width="2" />
							<path d="M10.5 17.5L19 27.5L29 14" stroke="#FFD028" stroke-width="2" stroke-linecap="round"
								stroke-linejoin="round" />
						</svg>
						Verifique si el teléfono que ingresó está correcto
					</div>
					<div class="cta__item">
						<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle cx="20" cy="20" r="19" stroke="#FFD028" stroke-width="2" />
							<path d="M10.5 17.5L19 27.5L29 14" stroke="#FFD028" stroke-width="2" stroke-linecap="round"
								stroke-linejoin="round" />
						</svg>
						Desactive el modo silencioso
					</div>
					<div class="cta__item">
						<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle cx="20" cy="20" r="19" stroke="#FFD028" stroke-width="2" />
							<path d="M10.5 17.5L19 27.5L29 14" stroke="#FFD028" stroke-width="2" stroke-linecap="round"
								stroke-linejoin="round" />
						</svg>
						Mantenga su teléfono a mano
					</div>
					<div class="cta__item">
						<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle cx="20" cy="20" r="19" stroke="#FFD028" stroke-width="2" />
							<path d="M10.5 17.5L19 27.5L29 14" stroke="#FFD028" stroke-width="2" stroke-linecap="round"
								stroke-linejoin="round" />
						</svg>
						Tome la llamada del número desconocido
					</div>
				</div>
			</div>
			<div class="subscribe__bar" style="display: none;">
				<span>¡Obtenga un descuento permanente!</span>
				<svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M1 6L7 12L13 6M1 1L7 7L13 1" stroke="black"></path>
				</svg>
			</div>
			<div class="subscribe">
				<div class="subscribe__cta">¡Obtenga un descuento permanente del 50% en su próxima compra!</div>
				<form class="subscribe__input" action="/mail-subscribe.php" method="post">
					<input
						type="email" name="email" placeholder="Introduzca su correo electrónico" autocomplete="email" required
					>
					<input type="hidden" name="id" value="<?php echo $_SESSION['conversion_id']; ?>">
					<button type="submit">
						<span class="subscribe__submit-text">Suscribirse</span>
						<svg width="24" height="19" viewBox="0 0 25 19" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M24 9.5L-5.96046e-07 9.5M24 9.5L15.5294 18M24 9.5L15.5294 1" stroke="black"></path>
						</svg>
					</button>
				</form>
			</div>
		</div>
		<div class="footer">
			<a href="privacy.html" target="_blank">Política de privacidad</a>
		</div>
	</div>
	<script>
        $('.js-change-btn').click(function () {
            let nameBeforeChange = $('.x_client_name').html();
            let phoneBeforeChange = $('.x_client_phone').html()
            $('.js-data').hide();
            setTimeout(function () {
                $('.correction__input--name').val(nameBeforeChange);
                $('.correction__input--phone').val(phoneBeforeChange);
                $('.js-change').fadeIn(100);
            }, 0);

        });
        $('.js-btn-change').click(function () {
            let nameAfterChange = $('.correction__input--name').val();
            let phoneAfterChange = $('.correction__input--phone').val()
            $('.x_client_name').html(nameAfterChange);
            $('.x_client_phone').html(phoneAfterChange);
            $('.js-change').hide();

            setTimeout(function () {
                $('.js-data').fadeIn(100);
            }, 0);
        });
        $('.js-btn-cancel').click(function () {
            $('.js-change').hide();
            setTimeout(function () {
                $('.js-data').fadeIn(100);
            }, 0);
        });
	</script>
	<script>
        (function (h, o, t, j, a, r) {
            h.hj = h.hj || function () {
                (h.hj.q = h.hj.q || []).push(arguments)
            };
            h._hjSettings = {hjid: 2230893, hjsv: 6};
            a = o.getElementsByTagName("head")[0];
            r = o.createElement("script");
            r.async = 1;
            r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
            a.appendChild(r);
        })(window, document, "https://static.hotjar.com/c/hotjar-",".js?sv=");
	</script>
	<script>
        const subscribeBar = document.querySelector('.subscribe__bar')
        const subscribe = document.querySelector('.subscribe')
        subscribeBar.addEventListener('click', function () {
            subscribe.scrollIntoView()
        })

        window.addEventListener('resize', toggleSubscribeBar)
        window.addEventListener('scroll', toggleSubscribeBar)
        toggleSubscribeBar()
        function toggleSubscribeBar() {
            const visible = isInViewport(subscribe)
            if (visible) {
                subscribeBar.style.display = 'none';
            } else {
                subscribeBar.style.display = 'flex';
            }
        }
        function isInViewport(el) {
            const position = el.getBoundingClientRect();
            return position.top < window.innerHeight && position.bottom >= 0;
        }

        $(document).ready(function() {
            const countdown = document.querySelector('.we-will-call-you__countdown')

            let seconds = defineTimeout();

            if (seconds) {
                $('.we-will-call-you__title').hide();
                $('.we-will-call-you__timer').show();
                resumeCountdown()
            }

            function resumeCountdown() {
                countdown.textContent = format(seconds--)
                if (seconds >= 0) {
                    setTimeout(resumeCountdown, 1000)
                } else {
                    $('.we-will-call-you__title').show();
                    $('.we-will-call-you__timer').hide();
                }
            }
        });

        function format(totalSeconds) {
            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds - hours * 3600) / 60);
            const seconds = totalSeconds % 60;
            return pad(hours) + ':' + pad(minutes) + ':' + pad(seconds)
        }
        function pad (x) {
            const s = String(x)
            return s.length > 1 ? s : '0' + s
        }

        function defineTimeout() {
            if (!window.smart_form_call_timings) {
                return
            }

            const settings = window.smart_form_call_timings[window.country_code]
            if (!settings) {
                return
            }

            const timeAtZone = moment().tz(settings.timezone)
            const currentHour = timeAtZone.hours()

            for (let i = 0; i < settings.intervals.length; ++i) {
                const interval = settings.intervals[i]
                if (interval.fromHour <= currentHour && currentHour < interval.toHour) {
                    return interval.timing
                }
            }
        }
	</script>
	<script>
        //x_resubmit_form - класс формы переотправки.
        //В форме должен быть инпут с id заказа с name="order_id" и инпут с новым телефоном c name="phone"

        const session_id = uuid();
        const form = $('form.x_resubmit_form');
        const subscribeForm = $('form.subscribe__input');
        let timeSpent = 0;
        setInterval(function () { timeSpent++; }, 1000);
        $.post('/submit-version.php', getVersionData('created'), () => { console.log('order created');});
        localStorage.setItem('phone', getVersionData('created').phone);

        form.submit(function (ev) {
            ev.preventDefault();
            const data = {};
            form.find("input").each(function () {
                var input = $(this);
                data[input.attr("name")] = input.val();
            });
            data.id = parseInt(data.id);
            if(localStorage.getItem('phone') === getVersionData().phone) {
                $.post('/submit-version.php', getVersionData('skipped:duplicate'), () => { console.log('duplicate');});
                return;
            }
            if(getVersionData().phone.length < 3) {
                $.post('/submit-version.php', getVersionData('skipped:invalid'), () => { console.log('invalid');});
                return;
            }
            $.post('/resubmit.php', data, () => {
                console.log('phone resubmited');
                document.cookie = 'client_name=' + data.name +';';
                document.cookie = 'client_phone=' + data.phone +';';
                localStorage.setItem('phone', data.phone);
            });
            $.post('/submit-version.php', getVersionData('updated'), () => { console.log('phone resubmited');});
        });


        $(window).on('unload', function() {
            const query = $.param(getVersionData('user:left'));
            navigator.sendBeacon(`/submit-version.php?${query}`);
        });
        subscribeForm.submit(function (ev) {
            ev.preventDefault();
            const data = {};
            subscribeForm.find("input").each(function () {
                var input = $(this);
                data[input.attr("name")] = input.val();
            });
            $.post(
                '/submit-version.php',
                getVersionData('user:subscribed', data),
                () => { console.log('subscribed'); }
            );
            $.post(
                '/mail-subscribe.php',
                data,
                () => { window.location.pathname = 'success.html'; }
            );
        });

        function getVersionData(status = '', additionalParams = {}) {
            const data = { timeSpent: timeSpent + ' seconds' };

            form.find("input").each(function () {
                var input = $(this);
                data[input.attr("name")] = input.val();
            });
            if (!data.phone) {
               data.phone =  $('.x_client_phone').text();
            }
            if (!data.name) {
                data.name = $('.x_client_name').text();
            }
            data.id = parseInt(data.id);

            return {
                status: status,
                session_id: session_id,
                landing_url: document.location.href,
                data: JSON.stringify(Object.assign({}, additionalParams, data)),
                phone: data.phone,
                order_id: data.id
            };
        }


        //метод ниже генератор рандомного ключа сессии
        function uuid() {
            // Public Domain/MIT
            var d = new Date().getTime(); //Timestamp

            var d2 = (performance && performance.now && performance.now() * 1000) || 0; //Time in microseconds since page-load or 0 if unsupported

            return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(
                /[xy]/g,
                function (c) {
                    var r = Math.random() * 16; //random number between 0 and 16

                    if (d > 0) {
                        //Use timestamp until depleted
                        r = (d + r) % 16 | 0;
                        d = Math.floor(d / 16);
                    } else {
                        //Use microseconds since page-load if supported
                        r = (d2 + r) % 16 | 0;
                        d2 = Math.floor(d2 / 16);
                    }

                    return (c === "x" ? r : (r & 0x3) | 0x8).toString(16);
                }
            );
        }
</script>
<!-- other_scripts -->
</body>
</html>
