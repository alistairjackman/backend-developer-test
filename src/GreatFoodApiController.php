<?php

namespace WiQApp;

use WiQApp\CurlService as CurlService;

/**
 * Class GreatFoodApiController
 *
 * Author: Alistair Jackman <alistairjackman@outlook.com>
 * Date: 16/09/20
 *
 * Main class to satisfy the two test scenarios of the wi-Q backend developer test.
 *
 * Class has only tow public functions, one for each of the two scenarios to fulfill.
 * I assume that a "real" class might have many more, all for performing CRUD operations
 * or querying the menus and products in some way
 *
 *
 * Notes on implementation or assumptions made
 * -------------------------------------------
 * - As the token in token.json has a high expiry time, this class is not doing anything like tracking the
 * access_token expiry time, which I would do in a real app.  As such, every time this class is instantiated (in this
 * case for each test scenario) I am fetching a new access token, whereas in a real system, this token could be stored
 * and used until it has expired, saving some API calls (which might imagine might have rate limits set).
 *
 * - Error checking would need some work in a real application.  At present, I am just throwing and catching
 * an exception, if any of the curl calls error (see CurlService.php).
 *
 * - In a real app I may have created classes for the data models, (menu and product) rather than just use keyed arrays
 *  it depends on how much use this 'app' would get, i.e. creating whole models might not be necessary.  I would also
 *  assume however, that as part of a bigger system, we might already have data models built.
 *
 * @package WiQApp
 */
class GreatFoodApiController
{
    /**
     * Api Base Url
     */
    private const API_URL = 'https://api.greatfood/'; // assumed

    /**
     * Api Endpoints
     */
    private const API_ENDPOINTS = [
        'POST_AUTH_TOKEN' => 'auth_token',
        'GET_MENUS' => 'menus',
        'GET_MENU_PRODUCTS' => 'menu/%1$d/products',
        'PUT_PRODUCT' => 'menu/%1$d/product/%2$d'
    ];

    /**
     * Oauth client credentials
     * @var array
     */
    private $oauth_data = [
        'client_secret' => '4j3g4gj304gj3',
        'client_id' => '1337',
        'grant_type' => 'client_credentials',
    ];

    /**
     * @var null|string
     */
    private $access_token = null;

    /**
     * GreatFoodApiController constructor.
     *
     * Make request to get access token
     */
    public function __construct()
    {
        if (is_null($this->access_token)) {
            $curlService = new CurlService();
            $response = $curlService->postRequest(
                self::API_URL. self::API_ENDPOINTS['POST_AUTH_TOKEN'],
                [],
                [
                    'client_id' => $this->oauth_data['client_id'],
                    'client_secret' => $this->oauth_data['client_secret'],
                    'grant_type' => $this->oauth_data['grant_type']
                ]
            );

            $this->access_token = json_decode($response,1)['access_token'];
        }
    }

    /**
     * Get menu products by menu name
     *
     * @param string $menuName
     *
     * @return array
     * @throws \Exception
     */
    public function getMenuProductsByMenuName(string $menuName = ""): array
    {
        $products = [];

        if (!empty($menuName)){
            $menuId = $this->getMenuIdByName($menuName);
            $products = $this->getMenuProductsByMenuId($menuId);
        }
        return $products;
    }

    /**
     * Update product by menu id and product id
     *
     * @param int $menuId
     * @param int $productId
     * @param array $product
     *
     * @return bool
     * @throws \Exception
     */
    public function updateProductByMenuIdAndProductId(int $menuId, int $productId, array $product): bool
    {
        $url = sprintf(self::API_URL . self::API_ENDPOINTS['PUT_PRODUCT'], $menuId, $productId);

        $curlService = (new CurlService())->setAuthorizationBearerToken($this->access_token);

        // remove key from product 'model'
        if (array_key_exists('id',$product)) {
            unset($product['id']);
        }

        return $curlService->putRequest($url, [], $product);
    }

    /**
     * Get all menus
     * @return array
     * @throws \Exception
     */
    private function getMenus(): array
    {
        $menus = [];

        $curlService = (new CurlService())->setAuthorizationBearerToken($this->access_token);

        $url = self::API_URL . self::API_ENDPOINTS['GET_MENUS'];

        $response = $curlService->getRequest($url);

        $menus = json_decode($response,1)['data'];

        return $menus;
    }

    /**
     * Get menu products by menu id
     *
     * @param int|null $menuId
     *
     * @return array
     * @throws \Exception
     */
    private function getMenuProductsByMenuId(int $menuId = null): array
    {
        $products = [];

        if (!is_null($menuId)) {
            $url = 'https://run.mocky.io/v3/fecf19a9-6c86-46b6-b56b-d071a221cac0';

            $curlService = (new CurlService())->setAuthorizationBearerToken($this->access_token);

            $response = $curlService->getRequest($url);

            $products = json_decode($response,1)['data'];
        }
        return $products;
    }

    /**
     * Get menu id by menu name
     *
     * @param string|null $menuName
     *
     * @return int|null
     * @throws \Exception
     */
    private function getMenuIdByName(string $menuName = null): ?int
    {
        $menuId = null;

        if (!is_null($menuName)) {
            $menus = $this->getMenus();

            foreach($menus as $menu) {
                if ($menu['name'] === $menuName) {
                    $menuId = $menu['id'];
                    break;
                }
            }
        }
        return $menuId;
    }
}