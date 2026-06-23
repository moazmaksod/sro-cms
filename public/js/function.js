var xhr = null;
var timerCountdown = {};

document.getElementById('themeSwitch').addEventListener('click', function() {
    var element = this;
    var theme = 'light';
    var logoPath = '/img/logo.png';
    if (!element.classList.contains('dark-theme')) {
        theme = 'dark';
        element.classList.add('dark-theme');
        logoPath = '/img/light_logo.png';
    } else {
        element.classList.remove('dark-theme');
    }

    var logos = document.getElementsByClassName('main-logo');
    for (var i = 0; i < logos.length; i++) {
        logos[i].src = logoPath;
    }
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
});

function startClockTimer(element)
{
    clockTimer(element);
    window.setInterval(function() { clockTimer(element); }, 999);
}

function clockTimer(element)
{
    if (!document.all && !document.getElementById) {
        return;
    }
    var Stunden = ServerTime.getHours();
    var Minuten = ServerTime.getMinutes();
    var Sekunden = ServerTime.getSeconds();
    ServerTime.setSeconds(Sekunden + 1);
    if (Stunden <= 9) {
        Stunden = '0' + Stunden;
    }

    if (Minuten <= 9) {
        Minuten = '0' + Minuten;
    }
    if (Sekunden <= 9) {
        Sekunden = '0' + Sekunden;
    }
    document.querySelector(element).textContent = Stunden.toString() + ':' + Minuten.toString() + ':' + Sekunden.toString();
}


function tTimer(iEndTimeStamp, iTimeStamp, sElement)
{
    iTimeStamp = iTimeStamp - Math.round(+new Date() / 1000) - iEndTimeStamp;
    var oElement = document.getElementById(sElement);
    if (!oElement) return false;
    if (iTimeStamp < 0) {
        oElement.innerHTML = '00:00:00';
        return false;
    }
    var diffDay = iTimeStamp / (3600 * 24);
    diffDay = diffDay.toString();
    diffDay = diffDay.split('.');
    var diffHour = iTimeStamp / 3600 % 24;
    diffHour = diffHour.toString();
    diffHour = diffHour.split('.');
    var diffMin = iTimeStamp / 60 % 60;
    diffMin = diffMin.toString();
    diffMin = diffMin.split('.');
    var diffSek = iTimeStamp % 60;
    diffSek = diffSek.toString();
    diffSek = diffSek.split('.');
    if(diffDay[0] != 0){
        oElement.innerHTML = diffDay[0] + 'd ' + checkLength(diffHour[0]) + ':' + checkLength(diffMin[0]) + ':' + checkLength(diffSek[0]);
        return true;
    }
    oElement.textContent = checkLength(diffHour[0]) + ':' + checkLength(diffMin[0]) + ':' + checkLength(diffSek[0]);
    return true;
}

function checkLength(sString)
{
    sString = sString.toString();
    if (sString.length === 1) {
        sString = '0' + sString;
    }
    return sString;
}

function loadCheck()
{
    Object.keys(timerCountdown).forEach(function(sKey){
        if(!tTimer(iTimeStamp, timerCountdown[sKey], sKey)){
            clearInterval(timerCountdown[sKey]);
        }
    });
}

function getCsrfToken()
{
    var meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

function paginatorAjax(element, urlData)
{
    ajaxReload();
    if (typeof element === 'string') {
        element = document.querySelector(element);
    }
    if (!element) return;
    element.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';

    var controller = new AbortController();
    xhr = { controller: controller };

    fetch(urlData, {
        method: 'POST',
        signal: controller.signal,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': getCsrfToken()
        }
    })
    .then(function(response) {
        if (!response.ok) throw new Error('Request failed');
        return response.text();
    })
    .then(function(data) {
        element.innerHTML = data;
    })
    .catch(function(e) {
        if (e.name !== 'AbortError') {
            alert('something wrong, please wait a moment');
        }
    });
}


function ajaxReload()
{
    if (xhr && xhr.controller) {
        xhr.controller.abort();
    }
}

function itemInfo()
{
    Array.from(document.querySelectorAll('[data-iteminfo]')).forEach(function(el) {
        var tip = document.createElement('div');
        tip.classList.add('tooltip');

        if (el.parentElement.querySelector('.info').innerHTML === '') {
            return;
        }

        tip.innerHTML = el.parentElement.querySelector('.info').innerHTML;
        tip.style.transform =
            'translate(' +
            (el.hasAttribute('tip-left') ? 'calc(-100% - 5px)' : '15px') + ', ' +
            (el.hasAttribute('tip-top') ? '-100%' : '0') +
            ')';
        el.appendChild(tip);
        el.onmousemove = function(e) {
            tip.style.left = e.clientX + 'px';
            tip.style.top = e.clientY + 'px';
        };
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.timerCountdown').forEach(function(el) {
        var sString = el.id;
        timerCountdown[sString] = Number(el.dataset.time);
        loadCheck();
    });
    window.setInterval(function() { loadCheck(); }, 999);
    itemInfo();

    document.querySelectorAll('.captcha-reload').forEach(function(el) {
        el.addEventListener('click', function() {
            var clicked = this;
            clicked.classList.add('fa-spin');
            var parent = clicked.closest('div');
            ajaxReload();

            var controller = new AbortController();
            xhr = { controller: controller };

            fetch(clicked.dataset.url, {
                method: 'POST',
                signal: controller.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            })
            .then(function(response) {
                if (!response.ok) throw new Error('Request failed');
                return response.json();
            })
            .then(function(data) {
                parent.querySelector('img').src = data['url'];
                parent.querySelector('input[name="captcha[id]"]').value = data['id'];
                parent.querySelector('input[name="captcha[input]"]').value = '';
                clicked.classList.remove('fa-spin');
            })
            .catch(function(e) {
                if (e.name !== 'AbortError') {
                    alert('smth wrong');
                    clicked.classList.remove('fa-spin');
                }
            });
        });
    });

    document.querySelectorAll('.coins-widget-reload').forEach(function(el) {
        el.addEventListener('click', function() {
            var clicked = this;
            clicked.classList.add('fa-spin');
            var parent = clicked.closest('div');
            ajaxReload();

            var controller = new AbortController();
            xhr = { controller: controller };

            fetch(clicked.dataset.url, {
                method: 'POST',
                signal: controller.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            })
            .then(function(response) {
                if (!response.ok) throw new Error('Request failed');
                return response.text();
            })
            .then(function(data) {
                document.getElementById('coinsWidgetSidebar').innerHTML = data;
                clicked.classList.remove('fa-spin');
            })
            .catch(function(e) {
                if (e.name !== 'AbortError') {
                    alert('smth wrong');
                    clicked.classList.remove('fa-spin');
                }
            });
        });
    });

    var inventorySwitch = document.getElementById('display-inventory-switch');
    if (inventorySwitch) {
        inventorySwitch.addEventListener('click', function() {
            var current = this.dataset.type;
            var change = 'set';
            if (current === 'set') {
                change = 'avatar';
            }
            document.getElementById('display-inventory-' + current).classList.add('d-none');
            document.getElementById('display-inventory-' + change).classList.remove('d-none');
            this.dataset.type = change;
        });
    }

    document.querySelectorAll('.ranking-main-button').forEach(function(el) {
        el.addEventListener('click', function() {
            document.querySelectorAll('.ranking-main-button').forEach(function(btn) {
                btn.classList.remove('active');
            });
            paginatorAjax('#content-replace', this.dataset.link);
            this.classList.add('active');
        });
    });
});
