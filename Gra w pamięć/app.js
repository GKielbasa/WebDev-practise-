let cards = ["ciri.png", "geralt.png", "jaskier.png", "jaskier.png", "iorweth.png", "triss.png", "geralt.png", "yen.png", "ciri.png", "triss.png", "yen.png", "iorweth.png"];
//alert(cards[4])
//console.log(cards)
//karty powinny byc losowane 
//uprzatnac to gówno ponizej 
//dodac reset gry 
//mozna dodac dzwieki
//inne universa 
// no i uprościć ten crap 
let c0 = document.getElementById('c0');
var c1 = document.getElementById('c1');
let c2 = document.getElementById('c2');
let c3 = document.getElementById('c3');

let c4 = document.getElementById('c4');
let c5 = document.getElementById('c5');
let c6 = document.getElementById('c6');
let c7 = document.getElementById('c7');

let c8 = document.getElementById('c8');
let c9 = document.getElementById('c9');
let c10 = document.getElementById('c10');
let c11 = document.getElementById('c11');

c0.addEventListener("click", function() {revealCard(0); });
c1.addEventListener("click", function() {revealCard(1); });
c2.addEventListener("click", function() {revealCard(2); });
c3.addEventListener("click", function() {revealCard(3); });

c4.addEventListener("click", function() {revealCard(4); });
c5.addEventListener("click", function() {revealCard(5); });
c6.addEventListener("click", function() {revealCard(6); });
c7.addEventListener("click", function() {revealCard(7); });

c8.addEventListener("click", function() {revealCard(8); });
c9.addEventListener("click", function() {revealCard(9); });
c10.addEventListener("click", function() {revealCard(10); });
c11.addEventListener("click", function() {revealCard(11); });

let oneVisible = false;
let turnCounter = 0;
let visibleNumber;
let lock = false;
let parisLeft = 6;
function revealCard(nr)
{
    let opacityValue = $('#c'+nr).css('opacity');
    //alert('opacity:' + opacityValue);
    if (opacityValue != 0 && lock == false)
    {
        lock = true;
        let picture = "url(img/"+cards[nr]+")";

        $('#c'+nr).css('background-image', picture);
        $('#c'+nr).addClass('cardActive');
        $('#c'+nr).removeClass('card');
        //$('#c'+nr).toggleClass('cardActive');

        if(oneVisible == false)
        {
            //first card
            oneVisible = true;
            visibleNumber = nr;
            lock = false;
        }
        else 
        {
            //second card
            if(cards[visibleNumber] == cards[nr])
            {
                //alert("para");
                setTimeout(function(){hide2Cards(nr, visibleNumber)}, 750);           
            }
            else 
            {
                //alert("pudło");
                setTimeout(function(){restore2Cards(nr, visibleNumber)}, 1000);
                
            }

            turnCounter++;
            $('.score').html('Turn counter: '+turnCounter);
            oneVisible = false;
        }
    }
    
}
function hide2Cards(nr1, nr2)
{
    $('#c'+nr1).css('opacity', '0');
    $('#c'+nr2).css('opacity', '0');

    parisLeft--;

    if (parisLeft == 0 )
    {
        $('.board').html('<h1>You win!<br>Done in '+turnCounter +' turn</h1>');
    }
    lock = false;
}

function restore2Cards(nr1, nr2)
{
    $('#c'+nr1).css('background-image', 'url(img/karta.png');
    $('#c'+nr1).addClass('card');
    $('#c'+nr1).removeClass('cardActive');

    $('#c'+nr2).css('background-image', 'url(img/karta.png');
    $('#c'+nr2).addClass('card');
    $('#c'+nr2).removeClass('cardActive');

    lock = false;
}