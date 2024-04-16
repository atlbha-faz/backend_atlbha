<?php
namespace App\Interfaces; 
interface ShippingInterface
{
    public function __construct();
    public function buildRequest($mothod, $data);
    public function createOrder($data);
    public function refundOrder($data);
    public function cancelOrder($id);
}
?>