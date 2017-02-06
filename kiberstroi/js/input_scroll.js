(function () {
    var plus = document.createElement("span");
    var minus = document.createElement("span");
    var div = document.createElement("div");
    var new_el;

    div.className = "choice";
    plus.className = "plus";
    minus.className = "minus";
    div.appendChild(plus);
    div.appendChild(minus);

    if (!Array.prototype.forEach) {
        Array.prototype.forEach = function (fn, scope) {
            for (var i = 0, len = this.length; i < len; ++i) {
                fn.call(scope, this[i], i, this);
            }
        };
    }

    arr = function (nodeList) {
        return Array.apply(null, nodeList);
    };
    
    function build(el) {
        new_el = div.cloneNode(true);
        new_el.appendChild(el.cloneNode());
        el.parentNode.replaceChild(new_el, el);
    }

    function num_click(e) {
        var el = e ? e.target : window.event.srcElement;
        if (el.tagName !== "SPAN") return;
		

        var inp = this.lastChild;
        var val = +inp.value;
		if (el.className == "plus") inp.value = +inp.value + 1;
			
		else if (el.className == "minus" && inp.value !=0) inp.value = +inp.value - 1;
    }
	
    function num_input(e) {
        var el = e ? e.target : window.event.srcElement;
        if (el.tagName !== "INPUT") return;
        var val = el.value.replace(/\D/g, '');
        el.value = val ? val : 1;
    }
	

    arr(document.querySelectorAll('input.number')).forEach(build);
    arr(document.querySelectorAll('div.choice')).forEach(function (el) {
        el.onclick = num_click;
        el.oninput = num_input;
    });
}());