function showShort() {
    var long = document.getElementById('forms-long');
    long.style.display = 'none';
    var short = document.getElementById('forms-short');
    short.style.display = 'block';
    
    var longButton = document.getElementById('button-long');
    var longButtonText = longButton.innerHTML;
    var shortButton = document.getElementById('button-short');
    var shortButtonText = shortButton.innerHTML;
    var newLongButton = document.createElement('a');
    newLongButton.innerHTML = longButtonText;
    newLongButton.id = 'button-long'; 
    newLongButton.href = 'javascript:showLong();';
    newLongButton.className = 'nav-link';
    longButton.parentNode.replaceChild(newLongButton, longButton);
    var newShortButton = document.createElement('span');
    newShortButton.innerHTML = shortButtonText;
    newShortButton.id = 'button-short';
    newShortButton.className = 'nav-link-sel';
    shortButton.parentNode.replaceChild(newShortButton, shortButton);
}

function showLong() {
    var long = document.getElementById('forms-long');
    long.style.display = 'block';
    var short = document.getElementById('forms-short');
    short.style.display = 'none';
	
    var longButton = document.getElementById('button-long');
    var longButtonText = longButton.innerHTML;
    var shortButton = document.getElementById('button-short');
    var shortButtonText = shortButton.innerHTML;
    var newLongButton = document.createElement('span');
    newLongButton.innerHTML = longButtonText;
    newLongButton.id = 'button-long';
    newLongButton.className = 'nav-link-sel';
    longButton.parentNode.replaceChild(newLongButton, longButton);
	var newShortButton = document.createElement('a');
	newShortButton.innerHTML = shortButtonText;
	newShortButton.id = 'button-short';
	newShortButton.href = 'javascript:showShort();';
    newShortButton.className = 'nav-link';
    shortButton.parentNode.replaceChild(newShortButton, shortButton);
}