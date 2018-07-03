function Storage(id)
{
    var data = localStorage.getItem(id);
    if(data === undefined || data === null) {
        data = {};
    } else {
        data = JSON.parse(data);
    }

    function persist()
    {
        localStorage.setItem(id, JSON.stringify(data));
    }

    this.set = function(key, value)
    {
        data[key] = value;
        persist();
    };

    this.has = function(key)
    {
        return data.hasOwnProperty(key);
    };

    this.get = function(key)
    {
        return data[key];
    }
}

export default Storage;