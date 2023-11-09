console.log(this);

class MyClass {

    value = 'dupa';

    logThis() {
        console.log(this);
    }
}

let myclass = new MyClass();

myclass.logThis();

function showThis() {
    console.log(this);
}

showThis();

const item = function () {
    console.log(this);
} 

item();

const test = () => {
    console.log(this);
}

test()

const dupa = function() {
    console.log(this);
    console.log('teraz bedzie function w func i wskaze na window');
    function znowuDejThis() {
        console.log(this);
    }
    znowuDejThis();
    console.log('arrow func w function')
    const test = () => {
        console.log(this);
    }
    test();
}

dupa();
// wszystko powyzej zwraca window (poza class) dzieje sie tak bo funckja  dziedziczy this po konteksie wywolania jeżeli zostanie wywołana jako metoda obiektu to odziediczy this po nim, arrow func nie dziedziczy  this 
function normalFunc() {
    console.log(this);
}

const arrowFunc = () => {
    console.log(this);
}

const obj = {
    // normalFun: normalFunc, //callback // zakomentowac do testowania bind
    // arrowFun: arrowFunc //callback
}

// obj.arrowFun(); //window f strzałkowa porpsotu nie dziedziczy this 
// obj.normalFun(); // obiekt 
console.log('---------');

const someRandomFunctionWithCallback = function (callbackPrintingThis) {
    
    callbackPrintingThis();
}

someRandomFunctionWithCallback(arrowFunc);
someRandomFunctionWithCallback(normalFunc); // window funckja dziedziczy this kiedy jest wywołana jako metoda obiektu 

someRandomFunctionWithCallback(normalFunc.bind(obj)); // obj nawet jak zakomentujemy sobie atr obj 
someRandomFunctionWithCallback(arrowFunc.bind(obj)); // window  - nieda sie strzałkwej funkcji zbindować 

