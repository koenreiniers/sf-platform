<?php
namespace SalesBundle\Twig;

class SalesExtension extends \Twig_Extension
{
    public function getOrderStateLevel($state)
    {
        return $this->getStateLevel($state);
    }

    public function getStateLevel($state)
    {
        $level = 'default';
        switch($state) {
            case 'processing':
                $level = 'info';
                break;
            case 'completed':
                $level = 'success';
                break;
            case 'cancelled':
                $level = 'warning';
                break;
        }
        return $level;
    }

    public function getOrderStateDescription($state)
    {
        $descriptions = [
            'new' => 'Submitted, but not yet invoiced',
            'processing' => 'Invoiced, but not yet completely shipped',
            'on_hold' => 'On hold for now',
            'closed' => 'Closed',
            'cancelled' => 'Cancelled',
            'completed' => 'Invoiced and shipped',
        ];
        return $descriptions[$state];
    }

    public function getOrderStates()
    {
        return ['new', 'processing', 'completed', 'cancelled', 'on_hold', 'closed'];
    }

    public function getOrderStateIcon($state)
    {
        $default = 'circle';
        $iconsByState = [
            'new' => 'plus',
            'processing' => 'exclamation',
            'completed' => 'check',
            'on_hold' => 'hand-stop-o',
            'cancelled' => 'remove',
        ];
        if(isset($iconsByState[$state])) {
            return $iconsByState[$state];
        }
        return $default;
    }

    public function getFilters()
    {
        return [

        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('get_order_state_icon', [$this, 'getOrderStateIcon']),
            new \Twig_SimpleFunction('get_order_states', [$this, 'getOrderStates']),
            new \Twig_SimpleFunction('get_order_state_level', [$this, 'getOrderStateLevel']),
            new \Twig_SimpleFunction('get_state_level', [$this, 'getStateLevel']),

            new \Twig_SimpleFunction('order_state_label', function($state){
                $level = $this->getOrderStateLevel($state);
                return sprintf('<span class="label label-%s">%s</span>', $level, $state);
            }, [
                'is_safe' => ['html'],
            ]),
        ];
    }
}