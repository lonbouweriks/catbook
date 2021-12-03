//switches between login and register @ login.php
function registerLoginSwitch(value) {
    const login = document.getElementById("login");
    const register = document.getElementById("register");
    if (value === "create") {
        login.style.display = "none";
        register.style.display = "unset";
    } else {
        login.style.display = "unset";
        register.style.display = "none";
    }
}

//checks species to display the right info depending wether the person is looking for a cat or an owner.
function checkSpecies() {

    const species = document.getElementById("species");
    const breedDiv = document.getElementById("catBreedProfile");
    const breed = document.getElementById("catbreed");
    if (species.value == "cat") {
        breedDiv.style.display = "unset";
    } else {
        breedDiv.style.display = "none";
        breed.value = ""
    }
}

//initializes hammer.js and checks filename to determine which swipe function to call
function swipe(fileName) {
    const myElement = document.getElementById("swipe");
    const mc = new Hammer(myElement);
    mc.on("swipeleft swiperight", function (ev) {
        if (fileName == "matching.php") {
            console.log('swipe')
            matchingSwipes(ev)
        }
        else if (fileName == "empty.php") {
            emptySwipes(ev)
        }
    });
}

//swipes for the matching page. takes the superglobal and increments it to find the next cat later. also checks swipe direction
function matchingSwipes(ev) {
    let $_GET = getSuperGlobal()
    if (typeof ($_GET['number']) != 'undefined') {
        $_GET['number'] = parseInt($_GET['number']) + 1
    }
    if (ev.type === "swipeleft") {
        if (typeof ($_GET['number']) != 'undefined') {
            window.location = 'matching.php?direction=left&number=' + useridcat
        }
        else {
            window.location = 'matching.php?direction=left&number=1'
        }
    } else if (ev.type === "swiperight") {
        if (typeof ($_GET['number']) != 'undefined') {

            window.location = 'matching.php?direction=right&number=' + useridcat
        }
        else {
            window.location = 'matching.php?direction=right&number=1'
        }
    }
}

//swipes for empty page
function emptySwipes(ev) {
    if (ev.type === "swipeleft") {
        window.location = 'index.php'
    }
    else if (ev.type === "swiperight") {
        window.location = 'matching.php'
    }
}

//takes the superglobal to be used in the swipe function so it can be incremented
function getSuperGlobal() {
    let $_GET = {};
    document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
        function decode(s) {
            return decodeURIComponent(s.split("+").join(" "));
        }
        $_GET[decode(arguments[1])] = decode(arguments[2]);
    });
    return $_GET;
}

//messages mouseover
window.onload = () => {
    const url = window.location.pathname;
    const fileName = url.substring(url.lastIndexOf("/") + 1);
    if (fileName === "profile.php") {
        checkSpecies()
    }
    if (fileName === "messages.php") {
        const newMessageText = document.getElementById("newMessageText");
        const newMessage = document.getElementById("newMessage");
        const sentMessage = document.getElementById("sentMessage");
        const sentMessageText = document.getElementById("sentMessageText");
        const receivedMessageText = document.getElementById("receivedMessageText");
        const receivedMessage = document.getElementById("receivedMessage");
        textArray = [newMessageText, sentMessageText, receivedMessageText]
        divArray = [newMessage, sentMessage, receivedMessage]
        if (document.getElementById('name').readOnly === true) {
            displayType = "block"
            newMessage.style.display = "block";
            newMessageText.style.color = "rgb(189, 112, 125)"
        }
        for (let i = 0; i < textArray.length; i++) {
            textArray[i].addEventListener("mouseover", (event) => {
                displayMessageOption(event, textArray[i], i)
            })
        }

        function displayMessageOption(event) {
            let displayType;
            let color
            for (let i = 0; i < textArray.length; i++) {
                if (textArray[i].id === event.target.id) {
                    displayType = "block"
                    color = "rgb(189, 112, 125)"
                }
                else {
                    displayType = "none"
                    color = "rgb(243, 147, 163)"
                }
                divArray[i].style.display = displayType;
                textArray[i].style.color = color;
            }
        }
    }
    else if (fileName === "matching.php" || fileName === "empty.php") {
        console.log('swipe')
        swipe(fileName)
    }
}