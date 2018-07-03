<?php
namespace AppBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Intl\Intl;

class AppExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * AppExtension constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function formatMoney($number, $currency = null, $decimals = 2)
    {
        if($currency === null) {
            $currency = $this->container->get('app.currency_registry')->getBaseCurrency();
        }
        $decimalSeparator = $this->getDecimalSeparator();
        $thousandSeparator = $decimalSeparator === '.' ? ',' : '.';
        $amount = number_format($number, $decimals, $decimalSeparator, $thousandSeparator);

        $symbol = Intl::getCurrencyBundle()->getCurrencySymbol($currency);

        return $symbol.' '.$amount;
    }

    public function formatDate(\DateTime $date, $precision = 's', $format = 'd-m-Y H:i:s')
    {
        $precisions = ['y','m','d','h','i','s'];

        $includeTime = in_array($precision, ['h', 'i', 's']);
        $timeFormat = '';
        if($precision === 's') {
            $timeFormat = 'H:i:s';
        } else if($precision === 'h') {
            $timeFormat = 'H';
        } else if($precision === 'i') {
            $timeFormat = 'H:i';
        }

        if(!in_array($precision, $precisions)) {
            throw new \Exception(sprintf('Invalid precision "%s"', $precision));
        }

        $days = [
            'Today' => new \DateTime(),
            'Yesterday' => ((new \DateTime())->sub(new \DateInterval('P1D'))),
            'Tomorrow' => ((new \DateTime())->add(new \DateInterval('P1D'))),
        ];

        foreach($days as $dayName => $dayDate) {
            if($dayDate->format('d-m-Y') === $date->format('d-m-Y')) {
                $out = $dayName;

                if($includeTime) {
                    $out .= ' at '.$date->format($timeFormat);
                }

                return $out;
            }
        }

        return $date->format($format);
    }

    private function getDecimalSeparator()
    {
        return ',';
    }

    private function getThousandSeparator()
    {
        return $this->getDecimalSeparator() === '.' ? ',' : '.';
    }

    public function formatQty($qty)
    {
        $decimalSeparator = $this->getDecimalSeparator();
        $thousandSeparator = $this->getThousandSeparator();
        $str = number_format($qty, 4, $decimalSeparator, $thousandSeparator);

        if(strpos($str, $decimalSeparator) === false) {
            return $str;
        }

        list($integers, $decimals) = explode($decimalSeparator, $str);

        $out = $integers;

        $decimals = rtrim($decimals, '0');

        if(!empty($decimals)) {
            $out = $out .$decimalSeparator.$decimals;
        }
        return $out;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('money_format', [$this, 'formatMoney']),
            new \Twig_SimpleFilter('qty_format', [$this, 'formatQty']),
            new \Twig_SimpleFilter('date_format', [$this, 'formatDate']),
        ];
    }

    public function getReturnUrl($fallback = null)
    {
        return $this->container->get('request_stack')->getCurrentRequest()->server->get('HTTP_REFERER', $fallback);
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('get_return_url', [$this, 'getReturnUrl']),
            #new \Twig_SimpleFunction('get_base_currency', [$this, 'getBaseCurrency']),
        ];
    }
}