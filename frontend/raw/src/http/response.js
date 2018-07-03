import LazyHeaderBag from './lazy-header-bag';

function Response(xmlHttpRequest)
{
    this.body = xmlHttpRequest.responseText;
    this.data = null;
    if(xmlHttpRequest.responseJSON) {
        this.data = xmlHttpRequest.responseJSON;

    }

    this.headers = new LazyHeaderBag(xmlHttpRequest);
    this.statusCode = xmlHttpRequest.status;
}

export default Response;