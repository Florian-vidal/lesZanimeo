//--------------------------HOME---------------------------------//

/*
document.write('<embed src="assets/song/lesZanimeo.mp3" width="0" height="0" controls="console" " + " loop="false" autostart="true" ></embed>');
*/

$( document ).ready(function() {

    $('.btn-next').on('click', function() {
        if ($('.contentTextQuiz h1').text('BRAVO, BONNE REPONSE !')) {

        }
    });
});

//------------SWITCH ICONE MEGAPHONE-----------------//
document.getElementById('player').addEventListener('click', function (e)
{
    e = e || window.event;
    audio.muted = !audio.muted;
    e.preventDefault();
}, false);

//-------------MESSAGES POP-UP------------------//
$(function() {
    $('#player').click(function() {
        $('.button').toggleClass('play').toggleClass('mute');
    })
});

$( document ).ready(function() {
    $('.circleMegaphone').slideUp().delay(2000).slideDown();
    $('.button').slideUp().delay(2000).slideDown();
    $('.popUpMegaphone').slideUp().delay(2000).slideDown();
    $('.popUpMegaphone').slideDown().delay(5000).slideUp();
});

$( document ).ready(function() {
    $('.quiz').slideUp().delay(1000).slideDown();
    $('.popUpQuiz').slideUp().delay(8000).slideUp();
    $('.popUpQuiz').slideDown().delay(3000).slideUp();
});

$( document ).ready(function() {
    $('.popUpHome').slideUp().delay(12000).slideDown();
    $('.popUpHome').slideDown().delay(5000).slideUp();
});

$( document ).ready(function() {
    $('.popUpRegistration').slideUp().delay(18000).slideDown();
    $('.popUpRegistration').slideDown().delay(5000).slideUp();
});

//------------BACKGROUND AUDIO ON/OFF--------------//
var audio = document.getElementById('background_audio');

//-------------SONS POP-UP------------------//

function PlaySound(soundobj) {
    var thissound=document.getElementById(soundobj);
    thissound.play();
}

function StopSound(soundobj) {
    var thissound=document.getElementById(soundobj);
    thissound.pause();
    thissound.currentTime = 0;
}

//---------------------------------------FARM--------------------------------------//

//-------------MESSAGES POP-UP------------------//
$( document ).ready(function() {
    $('.popUpFamily').slideUp().delay(1000).slideDown();
    $('.popUpFamily').slideDown().delay(5000).slideUp();
});

//-------------SONS POP-UP------------------//

function PlaySound(soundobj) {
    var thissound=document.getElementById(soundobj);
    thissound.play();
}

function StopSound(soundobj) {
    var thissound=document.getElementById(soundobj);
    thissound.pause();
    thissound.currentTime = 0;
}

//---------------------------------------BIRDS--------------------------------------//

//-------------MESSAGES POP-UP------------------//
function PlaySound(soundobj) {
    var thissound=document.getElementById(soundobj);
    thissound.play();
}

function StopSound(soundobj) {
    var thissound=document.getElementById(soundobj);
    thissound.pause();
    thissound.currentTime = 0;
}

//---------------------------------------BUGS--------------------------------------//

//-------------MESSAGES POP-UP------------------//
function PlaySound(soundobj) {
    var thissound=document.getElementById(soundobj);
    thissound.play();
}

function StopSound(soundobj) {
    var thissound=document.getElementById(soundobj);
    thissound.pause();
    thissound.currentTime = 0;
}


