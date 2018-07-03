import HeaderBag from './header-bag';

function HttpRequest(method, url, body, headers)
{
    this.method = method;
    this.url = url;
    this.body = body;
    this.headers = new HeaderBag(headers);
}

export default HttpRequest;