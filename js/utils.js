/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



const copyToClipboard = str => {
    const el = document.createElement('textarea');
    el.value = str;
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
};

function debounce(callback, delay) {
    var timeout
    return function () {
        var args = arguments
        clearTimeout(timeout)
        timeout = setTimeout(function () {
            callback.apply(this, args)
        }.bind(this), delay)
    }
}

function getForm(url, target, values, method) {
    function grabValues(x) {
        var path = [];
        var depth = 0;
        var results = [];

        function iterate(x) {
            switch (typeof x) {
                case 'function':
                case 'undefined':
                case 'null':
                    break;
                case 'object':
                    if (Array.isArray(x))
                        for (var i = 0; i < x.length; i++) {
                            path[depth++] = i;
                            iterate(x[i]);
                        }
                    else
                        for (var i in x) {
                            path[depth++] = i;
                            iterate(x[i]);
                        }
                    break;
                default:
                    results.push({
                        path: path.slice(0),
                        value: x
                    })
                    break;
            }
            path.splice(--depth);
        }
        iterate(x);
        return results;
    }
    var form = document.createElement("form");
    form.method = method;
    form.action = url;
    form.target = target;

    var values = grabValues(values);

    for (var j = 0; j < values.length; j++) {
        var input = document.createElement("input");
        input.type = "hidden";
        input.value = values[j].value;
        input.name = values[j].path[0];
        for (var k = 1; k < values[j].path.length; k++) {
            input.name += "[" + values[j].path[k] + "]";
        }
        form.appendChild(input);
    }
    return form;
}

function exportarPivotTable(cuboId, nomeArquivo) {
    new Table2Excel(`#output_${cuboId} > table > tr > td > .pvtTable`).export(nomeArquivo);
}

$(document).ready(function () {
    $("input[type=date]").click(function () {
        if ($(this).val() == '') {
            $(this).val(moment().format('YYYY-MM-DD'));
        }
    });
});