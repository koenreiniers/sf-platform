function HeaderBag(headers)
{
    headers = headers || {};
    this.has = function(name) {
        return headers[name] != undefined;
    };

    this.set = function(name, value)
    {
        headers[name] = value;
        return this;
    }

    this.get = function(name)
    {
        return headers[name.toLowerCase()];
    }

    this.all = function()
    {
        return headers;
    }
}

export default HeaderBag;