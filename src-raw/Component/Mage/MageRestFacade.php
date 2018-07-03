<?php
namespace Raw\Component\Mage;

use Psr\Http\Message\ResponseInterface;

class MageRestFacade
{
    /**
     * @var MageRest
     */
    private $client;

    /**
     * MageRestFacade constructor.
     * @param MageRest $client
     */
    public function __construct(MageRest $client)
    {
        $this->client = $client;
    }

    /**
     * @return MageRest
     */
    public function getClient()
    {
        return $this->client;
    }

    public function getProducts($options = [])
    {
        return $this->getCollection('products', $options);
    }

    public function getCustomers($options = [])
    {
        return $this->getCollection('customers', $options);
    }

    public function getWebsites($options = [])
    {
        return $this->getCollection('websites', $options);
    }

    public function opt(array $options = [])
    {
        return new GetOptions($options);
    }

    protected function updateOrderState($id, $state)
    {
        return $this->getClient()->put('orders/'.$id, json_encode([
            'state' => $state,
        ]));
    }

    public function getCollection($url, $options = [])
    {
        if(is_array($options)) {
            $options = new GetOptions($options);
        }

        $limit = $options->get('limit');

        $batchSize = $limit;
        $maxBatchSize = 100;
        if($batchSize === null || $batchSize > $maxBatchSize) {
            $batchSize = $maxBatchSize;
        }

        $options
            ->limit($batchSize)
            ->page(1)
        ;


        $results = [];
        $continue = true;
        do {
            $newResults = $this->decodeResponse($this->getClient()->get($this->buildUrl($url, $options)));
            foreach($newResults as $id => $newResult) {
                if(isset($results[$id])) {
                    $continue = false;
                    continue;
                }
                $results[$id] = $newResult;
            }
            $options->page($options->get('page') + 1);
        } while((count($results) < $limit || $limit === null) && count($newResults) >= $batchSize && $continue === true);

        if($limit !== null) {
            $results = array_slice($results, 0, $limit);
        }

        return $results;
    }


    public function unholdOrder($id)
    {
        return $this->updateOrderState($id, 'unholded');
    }

    public function holdOrder($id)
    {
        return $this->updateOrderState($id, 'holded');
    }

    public function cancelOrder($id)
    {
        return $this->updateOrderState($id, 'canceled');
    }

    private function buildUrl($url, $options = [])
    {
        if(is_array($options)) {
            $options = new GetOptions($options);
        }
        $options = $options->all();
        if(count($options) > 0) {
            $queryString = http_build_query($options);
            $url = $url.'?'.$queryString;
        }
        return $url;
    }

    /**
     * @param array|GetOptions $options
     *
     * @return array
     */
    public function getOrders($options = [])
    {
        return $this->getCollection('orders', $options);
        $url = 'orders';
        $url = $this->buildUrl($url, $options);

        $response = $this->client->get($url);

        $orders = json_decode((string)$response->getBody(), true);

        return $orders;
    }


    private function decodeResponse(ResponseInterface $response)
    {
        $contentType = $response->getHeaderLine('Content-Type');
        if(strpos($contentType, 'application/json') !== false) {
            return json_decode((string)$response->getBody(), true);
        }
        throw new \Exception(sprintf('Unable to decode content type "%s"', $contentType));
    }

    /**
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function getOrder($id)
    {
        $orders = $this->getOrders($this->opt()->addFilter('entity_id', 'eq', $id));
        return current($orders);
        return $this->decodeResponse($this->client->get('orders/'.$id));
    }

    /**
     * @param $orderId
     * @param array $qtyPerItemId
     * @param null $comment
     * @param bool $email
     * @param bool $includeComment
     * @return mixed
     * @throws \Exception
     */
    public function createInvoice($orderId, array $qtyPerItemId, $comment = null, $email = false, $includeComment = false)
    {
        $response = $this->client->post('invoices', json_encode([
            'order_id' => $orderId,
            'items' => $qtyPerItemId,
            'comment' => $comment,
            'email' => $email,
            'include_comment' => $includeComment,
        ]));

        return $this->getIdFromLocation($response->getHeaderLine('Location'));
    }

    /**
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function getShipment($id)
    {
        return $this->decodeResponse($this->client->get('shipments/'.$id));
    }

    private function getIdFromLocation($location)
    {
        $parts = explode('/', $location);

        $id = array_pop($parts);

        return $id;
    }

    /**
     * Creates a new shipment
     *
     * @param int $orderId
     * @param array $qtyPerItemId
     * @param null $comment
     * @param bool $email
     * @param bool $includeComment
     *
     * @return int The id of the newly created shipment
     */
    public function createShipment($orderId, array $qtyPerItemId = [], $comment = null, $email = false, $includeComment = false)
    {
        $data = [
            'order_id' => $orderId,
            'items' => $qtyPerItemId,
            'comment' => $comment,
            'email' => $email,
            'include_comment' => $includeComment,
        ];

        $response = $this->client->post('shipments', json_encode($data));

        $location = $response->getHeaderLine('Location');

        return $this->getIdFromLocation($location);

    }
}