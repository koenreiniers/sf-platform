function LazyHeaderBag(xmlHttpRequest)
{
    var headers = null;

    function initHeaders()
    {
        if(headers === null) {
            headers = parseHeaders();
        }
    }

    function parseHeaders()
    {
        var headerLines = xmlHttpRequest.getAllResponseHeaders().split("\n");
        var headers = {};
        headerLines.forEach(function(headerLine){

            var pos = headerLine.indexOf(':');
            var name = headerLine.substr(0, pos).trim().toLowerCase();
            if(name == '') {
                return;
            }
            var value = headerLine.substr(pos + 1).trim();
            headers[name] = value;
        });
        return headers;
    }

    this.has = function(name) {
        initHeaders();
        return headers[name] != undefined;
    }

    this.get = function(name)
    {
        initHeaders();
        return headers[name.toLowerCase()];
    }

    this.all = function()
    {
        initHeaders();
        return headers;
    }
}

export default LazyHeaderBag;